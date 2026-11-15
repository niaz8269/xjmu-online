<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Facades\DB;

/** Text-only CRUD screens: home facts/programs/steps/why/accreditations, MBBS curriculum, FAQs, programs, scholarships. */
class AdminContentSimpleTest extends AdminContentCase
{
    /** slug, table, required field(s) payload that is invalid, valid payload, changed field, [added, updated, deleted, missing] flashes */
    public static function pages(): array
    {
        return [
            'home-facts' => ['manage-home-facts', 'home_facts', ['label' => '', 'value' => 'x'], ['label' => 'Duration', 'value' => '6 years'], ['label' => 'Length'],
                ['Fact added.', 'Fact updated.', 'Fact deleted.', 'Label is required.']],
            'home-programs' => ['manage-home-programs', 'home_programs', ['title' => ''], ['title' => 'MBBS', 'description' => 'd', 'url' => 'mbbs'], ['url' => 'x'],
                ['Program card added.', 'Program card updated.', 'Program card deleted.', 'Title is required.']],
            'home-steps' => ['manage-home-steps', 'home_steps', ['title' => ''], ['title' => 'Apply', 'description' => 'd'], ['title' => 'Apply now'],
                ['Item added.', 'Item updated.', 'Item deleted.', 'Title is required.']],
            'home-why' => ['manage-home-why', 'home_why', ['title' => ''], ['title' => 'Why', 'description' => 'd'], ['title' => 'Why us'],
                ['Item added.', 'Item updated.', 'Item deleted.', 'Title is required.']],
            'home-accreditations' => ['manage-home-accreditations', 'home_accreditations', ['title' => ''], ['title' => 'WHO', 'description' => 'd'], ['title' => 'WHO listed'],
                ['Item added.', 'Item updated.', 'Item deleted.', 'Title is required.']],
            'mbbs-curriculum' => ['manage-mbbs-curriculum', 'mbbs_curriculum', ['year_tag' => 'Y1', 'title' => ''], ['year_tag' => 'Year 1', 'title' => 'Basics', 'description' => 'd'], ['title' => 'Basics 2'],
                ['Curriculum stage added.', 'Curriculum stage updated.', 'Curriculum stage deleted.', 'Year and title are required.']],
            'faqs' => ['manage-faqs', 'faqs', ['question' => 'q', 'answer' => ''], ['question' => 'Q?', 'answer' => 'A.', 'category' => 'c'], ['answer' => 'B.'],
                ['FAQ added.', 'FAQ updated.', 'FAQ deleted.', 'Question and answer are required.']],
            'programs' => ['manage-programs', 'programs', ['name' => ''], ['name' => 'MBBS', 'degree' => 'MBBS', 'category' => 'master', 'duration' => '6y'], ['name' => 'MBBS 2', 'category' => 'weird'],
                ['Program added.', 'Program updated.', 'Program deleted.', 'Program name is required.']],
            'scholarships' => ['manage-scholarships', 'scholarships', ['name' => ''], ['name' => 'Full', 'coverage' => '100%', 'eligibility' => 'e'], ['coverage' => '50%'],
                ['Scholarship added.', 'Scholarship updated.', 'Scholarship deleted.', 'Scholarship name is required.']],
        ];
    }

    #[DataProvider('pages')]
    public function test_create_update_delete_cycle($slug, $table, $invalid, $valid, $change, $msgs): void
    {
        [$added, $updated, $deleted, $required] = $msgs;
        $url = '/admin/' . $slug;

        // empty list renders
        $this->admin()->get($url)->assertOk();

        // validation message, nothing inserted
        $this->admin()->post($url, $invalid + ['id' => 0, 'is_published' => 'on'])->assertRedirect($url);
        $this->assertSame(0, DB::table($table)->count());
        $this->admin()->get($url)->assertOk()->assertSee($required, false);
        $this->admin()->get($url)->assertDontSee($required, false); // flash is consumed

        // create (published checkbox on, sort 5)
        $this->admin()->post($url, $valid + ['id' => 0, 'sort_order' => '5', 'is_published' => 'on'])->assertRedirect($url);
        $row = (array) DB::table($table)->first();
        $this->assertSame(5, (int) $row['sort_order']);
        $this->assertSame(1, (int) $row['is_published']);
        $this->admin()->get($url)->assertOk()->assertSee($added, false);

        // edit form is prefilled
        $this->admin()->get($url . '?action=edit&id=' . $row['id'])->assertOk()
            ->assertSee('name="id" value="' . $row['id'] . '"', false);
        $this->admin()->get($url . '?action=new')->assertOk()->assertSee('name="id" value="0"', false);

        // update (checkbox absent => unpublished)
        $this->admin()->post($url, $change + $valid + ['id' => $row['id'], 'sort_order' => '7'])->assertRedirect($url);
        $upd = $this->row($table, $row['id']);
        $this->assertSame(0, (int) $upd['is_published']);
        $this->assertSame(7, (int) $upd['sort_order']);
        foreach ($change as $k => $v) {
            if ($k === 'category' && $table === 'programs') {
                $this->assertSame('bachelor', $upd[$k]); // unknown category falls back
            } else {
                $this->assertSame($v, $upd[$k]);
            }
        }
        $this->admin()->get($url)->assertSee($updated, false)->assertSee('Hidden', false);

        // delete
        $this->admin()->post($url, ['_do' => 'delete', 'id' => $row['id']])->assertRedirect($url);
        $this->assertSame(0, DB::table($table)->count());
        $this->admin()->get($url)->assertSee($deleted, false);
    }

    #[DataProvider('pages')]
    public function test_guests_are_redirected($slug): void
    {
        $this->get('/admin/' . $slug)->assertRedirect('/login');
        $this->post('/admin/' . $slug, ['_do' => 'delete', 'id' => 1])->assertRedirect('/login');
        $this->get('/admin/' . $slug . '.php')->assertRedirect('/login');
    }

    public function test_list_order_and_trimming(): void
    {
        DB::table('faqs')->insert([
            ['question' => 'second', 'answer' => 'a', 'sort_order' => 2],
            ['question' => 'first', 'answer' => 'a', 'sort_order' => 1],
        ]);
        $this->admin()->get('/admin/manage-faqs')->assertSeeInOrder(['first', 'second']);
        $this->admin()->post('/admin/manage-faqs', ['question' => '  padded  ', 'answer' => ' x ', 'id' => 0]);
        $this->assertSame('padded', DB::table('faqs')->where('answer', 'x')->value('question'));
    }
}
