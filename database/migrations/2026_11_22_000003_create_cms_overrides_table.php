<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Content edited in the admin panel that used to live only in code files: page titles and SEO tags, page FAQs,
 * the guides, and the applicant-portal texts (added 22 Nov 2026 with the owner's consent).
 * A NEW table only. Each row overrides one piece of built-in content; deleting the row restores the built-in text.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cms_overrides')) {
            Schema::create('cms_overrides', function (Blueprint $t) {
                $t->increments('id');
                $t->string('item_key', 190)->unique();   // meta:/fees | faqs:home | guide:<slug> | portal
                $t->longText('value');                    // JSON
                $t->string('updated_by', 160)->nullable();
                $t->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_overrides');
    }
};
