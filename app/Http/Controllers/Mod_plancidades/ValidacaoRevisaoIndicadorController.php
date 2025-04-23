<?php

namespace App\Http\Controllers\Mod_plancidades;

use App\Mod_plancidades\MetasIndicadoresObjetivosEstrategicos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicosRevisao;
use App\Mod_plancidades\MetasObjetivosEstrategicos;
use App\Mod_plancidades\MetasObjetivosEstrategicosRevisao;
use App\Mod_plancidades\MonitoramentoIndicadores;
use App\Mod_plancidades\MonitoramentoIndicadoresObjEspecificos;
use App\Mod_plancidades\RegionalizacaoMetaObjEstr;
use App\Mod_plancidades\RegionalizacaoMetaObjEstrRevisao;
use App\Mod_plancidades\RevisaoIndicadores;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoRevisaoIndicadores;
use App\User;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosMetas;
use App\Mod_plancidades\ViewRevisaoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoRevisaoIndicadores;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;


class ValidacaoRevisaoIndicadorController extends Controller
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
        $dadosRevisao = RevisaoIndicadores::with('periodoRevisao')->find($revisaoId);
        $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::find($dadosRevisao->indicador_objetivo_estrategico_id);
        $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosRegionalizacaoRevisao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->get();
        $dadosRegionalizacao = RegionalizacaoMetaObjEstr::where('meta_objetivos_estrategicos_id', $dadosIndicador->objetivo_estrategico_meta_id)->with('regionalizacao')->get();
        $revisoes = ViewValidacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoId)->orderBy('created_at', 'desc')->first();       
        $usuarioPreenchimento = User::where('id', $dadosRevisao->user_id)->first();

        return view('modulo_plancidades.revisao.validacao.analisar_revisao_indicador', compact('dadosIndicador', 'dadosRevisao', 'dadosIndicadorRevisao', 'dadosMetaRevisao', 'dadosRegionalizacaoRevisao', 'dadosRegionalizacao', 'revisoes', 'usuarioPreenchimento'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $revisaoIndicadorid)
    {
        // return $revisaoIndicadorid;
        // return $request;

        $user = Auth()->user();
            DB::beginTransaction();

        $revisoes = ViewValidacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoIndicadorid)->first();

        $dados_validacao = new RlcSituacaoRevisaoIndicadores();
        
        if($request->situacao_revisao_id != null && $request->txt_observacao != null){
            $dados_validacao->revisao_indicador_id = $revisaoIndicadorid;
            $dados_validacao->situacao_revisao_id = $request->situacao_revisao_id;
            $dados_validacao->user_id = $user->id;
            $dados_validacao->created_at = date('Y-m-d H:i:s');
            $dados_validacao->indicador_objetivo_estrategico_id = $revisoes->indicador_objetivo_estrategico_id;
            $dados_validacao->txt_observacao = $request->txt_observacao;
            
        
            $dados_salvos = $dados_validacao->save();
        }
        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Situação da revisao do Indicador atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.validacao.indicadores.listar");
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
