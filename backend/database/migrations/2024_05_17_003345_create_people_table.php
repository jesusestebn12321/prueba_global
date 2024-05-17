<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_last_name', 20);
            $table->string('second_last_name', 20);
            $table->string('first_name', 20);
            $table->string('other_names', 50)->nullable();
            $table->enum('country', ['Colombia', 'United States']);
            $table->enum('identification_type', ['Cédula de Ciudadanía', 'Cédula de Extranjería', 'Pasaporte', 'Permiso Especial']);
            $table->string('identification_number', 20)->unique();
            $table->string('email', 300)->unique();
            $table->date('hire_date');
            $table->enum('area', ['Administración', 'Financiera', 'Compras', 'Infraestructura', 'Operación', 'Talento Humano', 'Servicios Varios']);
            $table->enum('status', ['Activo'])->default('Activo');
            $table->timestamp('registered_at');
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
        Schema::dropIfExists('people');
    }
}
