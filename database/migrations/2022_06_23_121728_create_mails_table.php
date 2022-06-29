<?php

use App\Models\Mail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mails', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');

            $table->string('from');
            $table->string('to');
            $table->string('subject');
            $table->text('text_content');
            $table->text('html_content');
            $table->json('attachments')->nullable();
            $table->string('webhook_url')->nullable();

            $table->unsignedBigInteger('user_id')->references('id')->on('users');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mails');
    }
};
