<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Schema ported from the legacy xjmu.online MySQL dump (kept column names so
 * the old data can be imported 1:1 with `php artisan xjmu:import`).
 */
return new class extends Migration
{
    public function up(): void
    {
        $content = function (string $name, callable $extra = null) {
            Schema::create($name, function (Blueprint $t) use ($extra) {
                $t->increments('id');
                if ($extra) { $extra($t); }
                $t->integer('sort_order')->default(0);
                $t->boolean('is_published')->default(true);
                $t->timestamp('created_at')->nullable();
                $t->timestamp('updated_at')->nullable();
            });
        };

        $content('home_accreditations', function ($t) { $t->string('title', 150); $t->text('description')->nullable(); });
        $content('home_facts', function ($t) { $t->string('label', 150); $t->string('value')->nullable(); });
        $content('home_programs', function ($t) { $t->string('title', 150); $t->text('description')->nullable(); $t->string('url')->nullable(); });
        $content('home_steps', function ($t) { $t->string('title', 150); $t->text('description')->nullable(); });
        $content('home_why', function ($t) { $t->string('title', 150); $t->text('description')->nullable(); });
        $content('mbbs_curriculum', function ($t) { $t->string('year_tag', 60); $t->string('title', 150); $t->text('description')->nullable(); });
        $content('testimonials', function ($t) {
            $t->string('name', 150); $t->string('role', 200)->nullable(); $t->string('location', 200)->nullable();
            $t->text('quote'); $t->string('photo')->nullable(); $t->string('category', 30)->default('student');
        });
        $content('programs', function ($t) {
            $t->string('name', 160); $t->string('degree', 80)->nullable(); $t->string('category', 40)->nullable();
            $t->string('duration', 80)->nullable(); $t->string('language', 80)->nullable();
            $t->string('tuition', 120)->nullable(); $t->text('description')->nullable();
        });
        $content('scholarships', function ($t) {
            $t->string('name', 160); $t->string('coverage', 160)->nullable(); $t->text('eligibility')->nullable(); $t->text('description')->nullable();
        });
        $content('faqs', function ($t) { $t->string('question'); $t->text('answer'); $t->string('category', 80)->nullable(); });
        $content('gallery', function ($t) { $t->string('title', 160)->nullable(); $t->string('category', 80)->nullable(); $t->string('image_path'); });
        $content('downloads', function ($t) {
            $t->string('title', 200); $t->string('description')->nullable(); $t->string('category', 80)->nullable();
            $t->string('file_path'); $t->integer('file_size')->nullable();
        });
        $content('consultants', function ($t) {
            $t->string('name', 120); $t->string('role', 120)->nullable(); $t->string('whatsapp', 40)->nullable();
            $t->string('phone', 40)->nullable(); $t->string('email', 120)->nullable(); $t->string('wechat', 80)->nullable();
            $t->string('photo')->nullable(); $t->string('qr_image')->nullable();
        });

        Schema::create('page_sections', function (Blueprint $t) {
            $t->increments('id');
            $t->string('page_key', 60); $t->string('section_key', 80);
            $t->string('heading')->nullable(); $t->text('body')->nullable();
            $t->integer('sort_order')->default(0); $t->boolean('is_published')->default(true);
            $t->timestamp('updated_at')->nullable();
            $t->unique(['page_key', 'section_key']);
        });

        Schema::create('site_settings', function (Blueprint $t) {
            $t->increments('id');
            $t->string('setting_key', 100)->unique(); $t->text('setting_value')->nullable();
            $t->string('group_name', 50)->default('general'); $t->string('label', 160);
            $t->string('input_type', 20)->default('text'); $t->integer('sort_order')->default(0);
            $t->timestamp('updated_at')->nullable();
        });

        Schema::create('blog_categories', function (Blueprint $t) {
            $t->increments('id');
            $t->string('name', 100); $t->string('slug', 100)->unique(); $t->text('description')->nullable();
            $t->string('icon')->nullable(); $t->integer('display_order')->default(0);
            $t->timestamp('created_at')->nullable();
        });

        Schema::create('blogs', function (Blueprint $t) {
            $t->increments('id');
            $t->string('title'); $t->string('slug')->unique(); $t->text('excerpt'); $t->longText('content');
            $t->string('author_name', 100)->nullable(); $t->string('author_title', 150)->nullable();
            $t->string('featured_image')->nullable(); $t->integer('category_id')->nullable();
            $t->boolean('is_featured')->default(false); $t->string('status', 20)->default('draft');
            $t->integer('views')->default(0); $t->timestamp('published_at')->nullable();
            $t->string('meta_description', 160)->nullable(); $t->string('meta_keywords')->nullable();
            $t->timestamp('created_at')->nullable(); $t->timestamp('updated_at')->nullable();
        });

        Schema::create('events', function (Blueprint $t) {
            $t->increments('id');
            $t->string('title'); $t->string('slug')->unique(); $t->text('excerpt')->nullable(); $t->longText('content')->nullable();
            $t->date('event_date'); $t->date('event_end_date')->nullable();
            $t->time('event_time_start')->nullable(); $t->time('event_time_end')->nullable();
            $t->string('venue')->nullable(); $t->string('featured_image', 500)->nullable();
            $t->string('status', 20)->default('published');
            $t->dateTime('created_at')->nullable(); $t->dateTime('updated_at')->nullable();
        });

        Schema::create('applications', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('user_id')->index();
            $t->boolean('is_submitted')->default(false); $t->timestamp('submitted_at')->nullable();
            $t->string('status', 20)->default('draft'); $t->text('admin_remark')->nullable();
            $t->string('degree_applying_for')->nullable(); $t->string('major_applying_for')->nullable();
            $t->string('admin_status')->nullable(); $t->timestamp('updated_at')->nullable();
        });

        Schema::create('personal_info', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('user_id')->index();
            foreach (['given_name','surname','chinese_name','gender','marital_status','country_of_birth','city_of_birth','religion','nationality','native_language','passport_no','permanent_address','tel','email','alternate_email','wechat_id','skype_no','emergency_contact_name','emergency_gender','emergency_relationship','emergency_tel','emergency_email'] as $c) {
                $t->string($c)->nullable();
            }
            $t->date('dob')->nullable(); $t->date('passport_expiry')->nullable();
            $t->text('present_address')->nullable(); $t->text('emergency_address')->nullable();
            $t->timestamp('created_at')->nullable();
        });

        Schema::create('education_records', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('user_id')->index(); $t->string('entry_type', 20);
            foreach (['education_level','field_of_study','country','institute_name','qualification'] as $c) { $t->string($c)->nullable(); }
            $t->date('from_date')->nullable(); $t->date('to_date')->nullable();
            $t->timestamp('created_at')->nullable();
        });

        Schema::create('documents', function (Blueprint $t) {
            $t->increments('id');
            $t->integer('user_id')->index(); $t->integer('document_id');
            $t->string('file_name')->nullable(); $t->string('file_path')->nullable();
            $t->timestamp('uploaded_on')->nullable();
        });

        Schema::create('contact_messages', function (Blueprint $t) {
            $t->id();
            $t->string('name'); $t->string('email'); $t->string('phone')->nullable();
            $t->string('subject')->nullable(); $t->text('message'); $t->timestamps();
        });

        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('program')->nullable(); $t->string('name'); $t->string('nationality')->nullable();
            $t->string('email'); $t->string('whatsapp')->nullable(); $t->string('source')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['leads','contact_messages','documents','education_records','personal_info','applications','events','blogs','blog_categories','site_settings','page_sections','consultants','downloads','gallery','faqs','scholarships','programs','testimonials','mbbs_curriculum','home_why','home_steps','home_programs','home_facts','home_accreditations'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
