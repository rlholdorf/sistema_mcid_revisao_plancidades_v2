<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class SituacoesRevisoes extends Model
{

   protected $connection   = 'pgsql_corp';

   protected $table = 'mcid_hom_plancidades.opc_situacao_revisao';

   public $timestamps = false; // tabela possui coluna de data de criação/atualização

   public $incrementing = false;
}
