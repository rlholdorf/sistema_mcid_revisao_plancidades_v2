<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class ViewRevisaoProjetos extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $primaryKey = 'revisao_iniciativa_id';

   protected $table = 'mcid_hom_plancidades.view_revisao_projetos';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização


}
