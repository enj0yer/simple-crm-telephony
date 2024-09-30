<?php

namespace Enj0yer\CrmTelephony\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCallerEntityOperatorTable extends Migration
{
    public function up()
    {
        DB::transaction(function () {
            Schema::create('caller_entity_operator', function (Blueprint $table) {
                $table->id();
                $table->integer('caller_entity_id');
                $table->string('operator_name');
                $table->string('caller_entity_type');
                $table->index(['caller_entity_id', 'caller_entity_type'], 'caller_entity_id_and_type_index');
                $table->index('operator_name', 'caller_entity_id_and_type_index');
                $table->timestamps();
            });
        });

    }

    public function down()
    {
        DB::transaction(function () {
            Schema::dropIfExists('caller_entity_operator');
        });

    }


}