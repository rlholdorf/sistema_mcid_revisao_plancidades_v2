<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class RevisaoIniciativas extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_plancidades.tab_revisao_iniciativas';

   public $timestamps = true; // tabela possui coluna de data de criação/atualização

   public function periodoRevisao()
   {
      return $this->hasOne(PeriodosMonitoramento::class, 'id', 'periodo_revisao_id');
   }

}