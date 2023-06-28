
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('CTB_gasto', function (Blueprint $table) {
            $table->id();
            $table->integer('idTipoGasto');
            $table->foreign("idTipoGasto")->references("id")->on("CTB_tipoGasto")->onDelete("cascade")->onUpdate("cascade");
            $table->string('nombre');
            $table->string('detalle');
            $table->timestamps();
            $table->softDeletes();

            
        });
    }

    public function down()
    {
        Schema::dropIfExists('CTB_gasto');
        Schema::table('CTB_gasto', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
