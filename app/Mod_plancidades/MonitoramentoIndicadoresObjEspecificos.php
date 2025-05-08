<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class MonitoramentoIndicadoresObjEspecificos extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_hom_plancidades.tab_monitoramento_indicador_obj_especifico';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização


}