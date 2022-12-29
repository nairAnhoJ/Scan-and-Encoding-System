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
        Schema::create('encode_forms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('doctype_id');
            $table->string('field1_name')->nullable();
            $table->string('field1_name_nospace')->nullable();
            $table->string('field1_type')->nullable();
            $table->string('field2_name')->nullable();
            $table->string('field2_name_nospace')->nullable();
            $table->string('field2_type')->nullable();
            $table->string('field3_name')->nullable();
            $table->string('field3_name_nospace')->nullable();
            $table->string('field3_type')->nullable();
            $table->string('field4_name')->nullable();
            $table->string('field4_name_nospace')->nullable();
            $table->string('field4_type')->nullable();
            $table->string('field5_name')->nullable();
            $table->string('field5_name_nospace')->nullable();
            $table->string('field5_type')->nullable();
            $table->string('field6_name')->nullable();
            $table->string('field6_name_nospace')->nullable();
            $table->string('field6_type')->nullable();
            $table->string('field7_name')->nullable();
            $table->string('field7_name_nospace')->nullable();
            $table->string('field7_type')->nullable();
            $table->string('field8_name')->nullable();
            $table->string('field8_name_nospace')->nullable();
            $table->string('field8_type')->nullable();
            $table->string('field9_name')->nullable();
            $table->string('field9_name_nospace')->nullable();
            $table->string('field9_type')->nullable();
            $table->string('field10_name')->nullable();
            $table->string('field10_name_nospace')->nullable();
            $table->string('field10_type')->nullable();
            $table->string('field11_name')->nullable();
            $table->string('field11_name_nospace')->nullable();
            $table->string('field11_type')->nullable();
            $table->string('field12_name')->nullable();
            $table->string('field12_name_nospace')->nullable();
            $table->string('field12_type')->nullable();
            $table->string('field13_name')->nullable();
            $table->string('field13_name_nospace')->nullable();
            $table->string('field13_type')->nullable();
            $table->string('field14_name')->nullable();
            $table->string('field14_name_nospace')->nullable();
            $table->string('field14_type')->nullable();
            $table->string('field15_name')->nullable();
            $table->string('field15_name_nospace')->nullable();
            $table->string('field15_type')->nullable();
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
        Schema::dropIfExists('encode_forms');
    }
};
