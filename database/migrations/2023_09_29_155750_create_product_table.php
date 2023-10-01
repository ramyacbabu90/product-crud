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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->set('unit', ['Quantity', 'Liter', 'Kilogram', 'Meter']);
            $table->float('price')->default(0);
            $table->float('discount_rate')->default(0);
            $table->float('discount_amount')->default(0);
            $table->date('discount_from_date')->nullable();
            $table->date('discount_to_date')->nullable();
            $table->float('tax_rate')->default(0);
            $table->float('tax_amount')->default(0);
            $table->boolean('stock_status')->default(0)->comment('0:no 1:yes');
            $table->integer('stock_quantity')->default(0);
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
        Schema::dropIfExists('products');
    }
};
