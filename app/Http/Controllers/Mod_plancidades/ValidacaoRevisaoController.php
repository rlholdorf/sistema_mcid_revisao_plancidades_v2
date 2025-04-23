<?php

namespace App\Http\Controllers\Mod_plancidades;

use App\Mod_plancidades\MetasIndicadoresObjetivosEstrategicos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\MetasObjetivosEstrategicos;
use App\Mod_plancidades\MonitoramentoIndicadores;
use App\Mod_plancidades\MonitoramentoIndicadoresObjEspecificos;
use App\Mod_plancidades\RegionalizacaoMetaObjEstr;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoMonitoramentoIndicadores;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosMetas;
use App\Mod_plancidades\ViewMonitoramentoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoRevisaoIndicadores;
use App\Mod_plancidades\ViewValidacaoRevisaoIniciativas;
use App\Mod_plancidades\ViewValidacaoRevisaoProjetos;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class ValidacaoRevisaoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('redirecionar'); 


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($indicadorId)
    {
      }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($indicadorId)
    {
      }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $monitoramento_indicador_id)
    {
        return $monitoramento_indicador_id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    

    public function iniciarConsulta()
    {
        return view("modulo_plancidades.revisao.validacao.iniciar_consulta_validacao_revisao");
    }


    //Indicadores
    public function listarRevisoesPendentesIndicadores()
    {
        $revisoes = ViewValidacaoRevisaoIndicadores::where('situacao_revisao_id', '3')->get();        
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_indicador", compact('revisoes'));
    }

    public function listarRevisoesIndicadores()
    {

        $revisoes = ViewValidacaoRevisaoIndicadores::get();
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_indicador", compact('revisoes'));
    }
    //---



    //Iniciativas
    public function listarRevisoesPendentesIniciativas()
    {
        $revisoes = ViewValidacaoRevisaoIniciativas::where('situacao_revisao_id', '3')->get();
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_iniciativa", compact('revisoes'));
    }

    public function listarRevisoesIniciativas()
    {
        $revisoes = ViewValidacaoRevisaoIniciativas::get();       
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_iniciativa", compact('revisoes'));
    }

    //---
    

    //Projetos
    public function listarRevisoesPendentesProjetos()
    {
        $revisoes = ViewValidacaoRevisaoProjetos::where('situacao_revisao_id', '3')->get();
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_projeto", compact('revisoes'));
    }

    public function listarRevisoesProjetos()
    {
        $revisoes = ViewValidacaoRevisaoProjetos::get();
        return view("modulo_plancidades.revisao.validacao.listar_validacao_revisoes_projeto", compact('revisoes'));
    }

    //---

}
