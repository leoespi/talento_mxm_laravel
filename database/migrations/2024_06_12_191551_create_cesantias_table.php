    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateCesantiasTable extends Migration
    {
        
        public function up()
        {
            Schema::create('cesantias', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
                $table->string('tipo_cesantia_reportada',50)->nullable();
                $table->string('estado', 50)->nullable(); 
                $table->string('justificacion',500)->nullable();
                $table->uuid('uuid');
                $table->json('images')->nullable();
                $table->timestamps();
            });
        }

    
        public function down()
        {
            Schema::dropIfExists('cesantias');
        }
    }
