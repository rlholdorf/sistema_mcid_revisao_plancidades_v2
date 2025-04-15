<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class IndicadoresIniciativasRevisao extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_plancidades.tab_indicadores_iniciativas_revisao';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização


}