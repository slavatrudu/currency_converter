<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('num_code', 20)->comment('Цифровой код валюты')->unique();
            $table->string('char_code', 20)->comment('Символьный код валюты')->unique();
            $table->integer('nominal')->comment('Номинал');
            $table->string('name_currency')->comment('Название валюты');
            $table->double('value_currency', 8, 4)->comment('Курс');
            $table->double('value_unit_currency', 8, 4)->comment('Курс за 1 единицу валюты');
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
        Schema::dropIfExists('currency_rates');
    }
}
