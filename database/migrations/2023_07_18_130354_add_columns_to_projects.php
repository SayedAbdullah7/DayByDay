<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('unit_type', ['villa', 'apartment', 'challet', 'mall', 'office', 'studio'])->nullable()->before('created_at');
            $table->enum('sub_type', ['duplex', 'pent house', 'stand alone', 'twin', 'town', 'administrative', 'medical', 'commercial'])->nullable()->before('created_at');
            $table->integer('bedroom')->unsigned()->nullable()->before('created_at');
            $table->integer('bathroom')->unsigned()->nullable()->before('created_at');
            $table->integer('dressing_room')->unsigned()->nullable()->before('created_at');
            $table->integer('area')->unsigned()->nullable()->before('created_at');
            $table->integer('bua')->unsigned()->nullable()->before('created_at');
            $table->integer('land_area')->unsigned()->nullable()->before('created_at');
            $table->integer('garage')->unsigned()->nullable()->before('created_at');
            $table->integer('roof_area')->unsigned()->nullable()->before('created_at');
            $table->integer('floor_number')->unsigned()->nullable()->before('created_at');
            $table->integer('apartment_number')->unsigned()->nullable()->before('created_at');
            $table->integer('elevator')->unsigned()->nullable()->before('created_at');
            $table->enum('view', ['garden', 'pool', 'sea', 'lake'])->nullable()->before('created_at');
            $table->enum('finished_status', ['un finished', 'semi finished', 'fully finished'])->nullable()->before('created_at');
            $table->text('furniture_status')->nullable()->before('created_at');
            $table->text('comment')->nullable()->before('created_at');
            $table->enum('payment_method', ['installments', 'cash'])->nullable()->before('created_at');
            $table->enum('installments_policy', ['down payment', 'quarter payment', 'period'])->nullable()->before('created_at');
            $table->integer('period')->unsigned()->nullable()->before('created_at');
            $table->bigInteger('price')->unsigned()->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['unit_type', 'sub_type', 'bedroom', 'bathroom', 'dressing_room', 'area', 'bua', 'land_area', 'garage', 'roof_area', 'floor_number', 'apartment_number', 'elevator', 'view', 'finished_status', 'furniture_status', 'comment', 'payment_method', 'installments_policy', 'period', 'price']);
        });
    }
}
