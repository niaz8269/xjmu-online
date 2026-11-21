<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staff notes and status history for applications (added 22 Nov 2026 with the owner's consent).
 * A NEW table only: `applications` and every other existing table are untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('crm_application_log')) {
            Schema::create('crm_application_log', function (Blueprint $t) {
                $t->increments('id');
                $t->unsignedInteger('user_id');                  // the applicant (applications.user_id)
                $t->string('type', 20);                          // note | status | email
                $t->unsignedInteger('admin_id')->nullable();
                $t->string('admin_name', 160)->nullable();
                $t->string('old_status', 60)->nullable();
                $t->string('new_status', 60)->nullable();
                $t->text('body')->nullable();                    // the note, or the remark sent to the applicant
                $t->timestamp('created_at')->nullable();
                $t->index('user_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crm_application_log');
    }
};
