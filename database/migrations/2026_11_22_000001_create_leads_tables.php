<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lead management (added 22 Nov 2026 with the owner's consent). NEW tables only: no existing table is touched.
 * leads      : one row per enquirer (website forms, registered-not-applied students, manual entries).
 * lead_notes : staff notes and the automatic history of each lead.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crm_leads')) {
            Schema::create('crm_leads', function (Blueprint $t) {
                $t->increments('id');
                $t->string('source', 30)->default('manual');     // contact | partner | event | registered | manual | whatsapp | phone | agent
                $t->string('name', 160);
                $t->string('email', 190)->nullable();
                $t->string('phone', 60)->nullable();
                $t->string('nationality', 100)->nullable();
                $t->string('program', 160)->nullable();
                $t->string('subject', 200)->nullable();
                $t->text('message')->nullable();
                $t->string('status', 30)->default('New');
                $t->unsignedInteger('assigned_to')->nullable();   // users.id of an admin
                $t->date('next_follow_up')->nullable();
                $t->unsignedInteger('user_id')->nullable();       // users.id when the lead is a registered student
                $t->timestamp('created_at')->nullable();
                $t->timestamp('updated_at')->nullable();
                $t->index('status');
                $t->index('email');
                $t->index('next_follow_up');
                $t->index('user_id');
            });
        }

        if (! Schema::hasTable('crm_lead_notes')) {
            Schema::create('crm_lead_notes', function (Blueprint $t) {
                $t->increments('id');
                $t->unsignedInteger('lead_id');
                $t->unsignedInteger('admin_id')->nullable();
                $t->string('admin_name', 160)->nullable();
                $t->text('note');
                $t->timestamp('created_at')->nullable();
                $t->index('lead_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_lead_notes');
        Schema::dropIfExists('crm_leads');
    }
};
