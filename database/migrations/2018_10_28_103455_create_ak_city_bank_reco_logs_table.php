<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAkCityBankRecoLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ak_city_bank_reco_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('teminal_name');
            $table->integer('count');
            $table->double('txn_amount');
            $table->double('comission');
            $table->double('payable_amount');
            $table->string('fileName');
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
        Schema::dropIfExists('ak_city_bank_reco_logs');
    }
}
