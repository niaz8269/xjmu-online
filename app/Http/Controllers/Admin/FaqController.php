<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends ContentCrudController
{
    protected string $table = 'faqs';
    protected string $slug = 'manage-faqs';
    protected string $pageTitle = 'FAQs';
    protected string $pageSubtitle = 'Frequently asked questions shown on the public site';
    protected string $view = 'admin.content.faqs';
    protected string $orderBy = 'sort_order, id DESC';
    protected string $deletedMessage = 'FAQ deleted.';
    protected bool $genericDbError = true;

    protected function persist(Request $request): string
    {
        $id = $this->int($request, 'id');
        $question = $this->str($request, 'question');
        $answer = $this->str($request, 'answer');
        $vals = ['question' => $question, 'answer' => $answer, 'category' => $this->str($request, 'category'),
                 'sort_order' => $this->int($request, 'sort_order'), 'is_published' => $this->published($request)];
        if ($question === '' || $answer === '') {
            return 'Question and answer are required.';
        }
        if ($id > 0) {
            DB::table($this->table)->where('id', $id)->update($vals);
            return 'FAQ updated.';
        }
        DB::table($this->table)->insert($vals);
        return 'FAQ added.';
    }
}
