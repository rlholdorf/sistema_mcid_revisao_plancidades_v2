<?php

namespace App\Http\Controllers\Mod_plancidades;

use App\Mod_plancidades\MetasIndicadoresObjetivosEstrategicos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ProjetosRevisao;
use App\Mod_plancidades\EtapasProjetoRevisao;
use App\Mod_plancidades\EtapasProjetosRevisao;
use App\Mod_plancidades\MonitoramentoIndicadores;
use App\Mod_plancidades\MonitoramentoIndicadoresObjEspecificos;
use App\Mod_plancidades\RegionalizacaoMetaObjEstr;
use App\Mod_plancidades\RegionalizacaoMetaObjEstrRevisao;
use App\Mod_plancidades\RevisaoProjetos;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoRevisaoProjetos;
use App\User;
use App\Mod_plancidades\ViewProjetos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosMetas;
use App\Mod_plancidades\ViewRevisaoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoRevisaoProjetos;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;


class ValidacaoRevisaoProjetoController extends Controller
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
        $dadosRevisao = RevisaoProjetos::with('periodoRevisao')->find($revisaoId);
        $dadosProjeto = ViewProjetos::where('projeto_id', $dadosRevisao->projeto_id)->first();
        $dadosProjetoRevisao = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();
        $dadosEtapasRevisao = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->with('situacao')->orderBy('id')->get();
        $revisoes = ViewValidacaoRevisaoProjetos::where('revisao_projeto_id', $revisaoId)->orderBy('created_at', 'desc')->first();       
        $usuarioPreenchimento = User::where('id', $dadosRevisao->user_id)->first();

        return view('modulo_plancidades.revisao.validacao.analisar_revisao_projeto', compact('dadosProjeto', 'dadosRevisao', 'dadosProjetoRevisao', 'dadosEtapasRevisao', 'revisoes', 'usuarioPreenchimento'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $revisaoProjetoid)
    {
        // return $revisaoProjetoid;
        // return $request;

        $user = Auth()->user();
            DB::beginTransaction();

        $revisoes = ViewValidacaoRevisaoProjetos::where('revisao_projeto_id', $revisaoProjetoid)->first();

        $dados_validacao = new RlcSituacaoRevisaoProjetos();
        
        if($request->situacao_revisao_id != null && $request->txt_observacao != null){
            $dados_validacao->revisao_projeto_id = $revisaoProjetoid;
            $dados_validacao->situacao_revisao_id = $request->situacao_revisao_id;
            $dados_validacao->user_id = $user->id;
            $dados_validacao->created_at = date('Y-m-d H:i:s');
            $dados_validacao->projeto_id = $revisoes->projeto_id;
            $dados_validacao->txt_observacao = $request->txt_observacao;
            
        
            $dados_salvos = $dados_validacao->save();
        }
        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Situação da revisao do Projeto atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.validacao.projetos.listar");
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
