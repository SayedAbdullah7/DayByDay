<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_id');
            $table->string('title');
            $table->text('description');
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->integer('user_assigned_id')->unsigned();
            $table->foreign('user_assigned_id')->references('id')->on('users');
            $table->integer('user_created_id')->unsigned();
            $table->foreign('user_created_id')->references('id')->on('users');
            $table->integer('client_id')->unsigned()->unsigned();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->date('deadline');
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('projects');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
