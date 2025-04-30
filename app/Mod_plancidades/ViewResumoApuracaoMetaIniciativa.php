<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ViewResumoApuracaoMetaIniciativa extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_hom_plancidades.view_resumo_apuracao_meta_iniciativa';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização


}