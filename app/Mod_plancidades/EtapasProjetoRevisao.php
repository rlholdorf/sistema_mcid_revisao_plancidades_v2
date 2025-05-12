<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class EtapasProjetoRevisao extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $keyType = 'string';

   protected $table = 'mcid_hom_plancidades.tab_etapas_projetos_revisao';

   public $timestamps = false; // tabela possui coluna de data de criação/atualização
}
