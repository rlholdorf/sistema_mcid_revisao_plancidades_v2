<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class MetasEntregas extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_hom_plancidades.tab_metas_entregas';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização


}
