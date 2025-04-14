<?php

namespace App\Mod_plancidades;

use Illuminate\Database\Eloquent\Model;

class ObjetivosEspecificosPPA extends Model
{

   protected $connection  = 'pgsql_corp';

   protected $table = 'mcid_plancidades.tab_objetivo_especifico_ppa';

   public $timestamps = false; // tabela possui coluna de data de criação/atualização


}