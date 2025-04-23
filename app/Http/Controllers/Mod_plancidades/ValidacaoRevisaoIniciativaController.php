<?php

namespace App\Http\Controllers\Mod_plancidades;

use App\Mod_plancidades\MetasIndicadoresObjetivosEstrategicos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresIniciativasRevisao;
use App\Mod_plancidades\IniciativasRevisao;
use App\Mod_plancidades\MetasIniciativas;
use App\Mod_plancidades\MetasIniciativasRevisao;
use App\Mod_plancidades\MonitoramentoIndicadores;
use App\Mod_plancidades\MonitoramentoIndicadoresObjEspecificos;
use App\Mod_plancidades\RegionalizacaoMetaIniciativa;
use App\Mod_plancidades\RegionalizacaoMetaIniciativaRevisao;
use App\Mod_plancidades\RevisaoIniciativas;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoRevisaoIniciativas;
use App\User;
use App\Mod_plancidades\ViewIndicadoresIniciativas;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosMetas;
use App\Mod_plancidades\ViewRevisaoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoRevisaoIniciativas;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class ValidacaoRevisaoIniciativaController extends Controller
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
    public function edit($revisaoId)
    {
        $dadosRevisao = RevisaoIniciativas::with('periodoRevisao')->find($revisaoId);
        $dadosIniciativa = ViewIndicadoresIniciativas::where('iniciativa_id', $dadosRevisao->iniciativa_id)->first();
        $dadosIniciativaRevisao = IniciativasRevisao::where('revisao_iniciativa_id', $revisaoId)->first();
        $dadosIndicadorIniciativaRevisao = IndicadoresIniciativasRevisao::where('revisao_iniciativa_id', $revisaoId)->first();
        $dadosMetaRevisao = MetasIniciativasRevisao::where('revisao_iniciativa_id', $revisaoId)->first();
        $dadosMeta = MetasIniciativas::where('iniciativa_id', $dadosRevisao->iniciativa_id)->first();
        $dadosRegionalizacaoRevisao = RegionalizacaoMetaIniciativaRevisao::where('revisao_iniciativa_id', $revisaoId)->get();
        $dadosRegionalizacao = RegionalizacaoMetaIniciativa::where('meta_iniciativa_id', $dadosMeta->id)->with('regionalizacao')->get();
        $revisoes = ViewValidacaoRevisaoIniciativas::where('revisao_iniciativa_id', $revisaoId)->orderBy('created_at', 'desc')->first();       
        $usuarioPreenchimento = User::where('id', $dadosRevisao->user_id)->first();

        return view('modulo_plancidades.revisao.validacao.analisar_revisao_iniciativa', compact('dadosIniciativa', 'dadosRevisao', 'dadosIniciativaRevisao', 'dadosIndicadorIniciativaRevisao', 'dadosMetaRevisao', 'dadosMeta', 'dadosRegionalizacao', 'dadosRegionalizacaoRevisao', 'revisoes', 'usuarioPreenchimento'));   
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $revisaoIniciativaid)
    {
        // return $revisaoIniciativaid;
        // return $request;

        $user = Auth()->user();
            DB::beginTransaction();

        $revisoes = ViewValidacaoRevisaoIniciativas::where('revisao_iniciativa_id', $revisaoIniciativaid)->first();

        $dados_validacao = new RlcSituacaoRevisaoIniciativas();
        
        if($request->situacao_revisao_id != null && $request->txt_observacao != null){
            $dados_validacao->revisao_iniciativa_id = $revisaoIniciativaid;
            $dados_validacao->situacao_revisao_id = $request->situacao_revisao_id;
            $dados_validacao->user_id = $user->id;
            $dados_validacao->created_at = date('Y-m-d H:i:s');
            $dados_validacao->iniciativa_id = $revisoes->iniciativa_id;
            $dados_validacao->txt_observacao = $request->txt_observacao;
            
        
            $dados_salvos = $dados_validacao->save();
        }
        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Situação da revisao da Iniciativa atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.validacao.iniciativas.listar");
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível atualizar a situação da revisão.");
            return back();
    }
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
       

}
