<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class EtapasProjeto extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $keyType = 'string';

   protected $table = 'mcid_hom_plancidades.tab_etapas_projetos';

   public $timestamps = false; // tabela possui coluna de data de criação/atualização

   public function situacao()
   {
      return $this->hasOne(SituacoesEtapasProjetos::class, 'id', 'situacao_etapa_projeto_id');
   }

}
