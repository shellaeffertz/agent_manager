<?php

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
        Schema::create('website_creator_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('website_link');
            $table->string('website_credentials');
            $table->string('status')->default('On Hold');
            $table->boolean('is_sent')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
            $table->unsignedBigInteger('drop_id')->nullable();
            $table->foreign('drop_id')
                ->references('id')
                ->on('drop_manager_tasks')
                ->onDelete('set null');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('company_creator_tasks')
                ->onDelete('set null');
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
        Schema::dropIfExists('website_creator_tasks');
    }
};
