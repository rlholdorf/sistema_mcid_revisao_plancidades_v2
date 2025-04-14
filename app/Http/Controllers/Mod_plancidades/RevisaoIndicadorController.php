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
use App\Mod_plancidades\ObjetivosEspecificosPPA;
use App\Mod_plancidades\RegionalizacaoMetaObjEstr;
use App\Mod_plancidades\RegionalizacaoMetaObjEstrRevisao;
use App\Mod_plancidades\RevisaoIndicadores;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoMonitoramentoIndicadores;
use App\Mod_plancidades\RlcSituacaoRevisaoIndicadores;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosMetas;
use App\Mod_plancidades\ViewRevisaoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoMonitoramentoIndicadores;
use App\Mod_plancidades\ViewValidacaoRevisaoIndicadores;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class RevisaoIndicadorController extends Controller
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
        $revisoes = ViewRevisaoIndicadoresObjEstrategicos::where('view_revisao_indicadores.indicador_objetivo_estrategico_id', $indicadorId)
        ->orderBy('view_revisao_indicadores.revisao_indicador_id', 'DESC')
        ->leftJoin('mcid_hom_plancidades.view_validacao_revisao_indicadores','view_validacao_revisao_indicadores.revisao_indicador_id','=','view_revisao_indicadores.revisao_indicador_id')
        ->select('view_revisao_indicadores.*','view_validacao_revisao_indicadores.situacao_revisao_id','view_validacao_revisao_indicadores.txt_situacao_revisao')
        ->get();

        if(count($revisoes) > 0){
            return view("modulo_plancidades.revisao.objetivo_estrategico.listar_revisoes_indicador", compact('revisoes'));
        }else{
            flash()->erro("Erro", "Nenhuma revisao encontrada...");
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($revisaoId)
    {   
        //Se a revisão do indicador já existe, redireciona para página de edição
        $indicadorExiste = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        if (!empty($indicadorExiste)) {
            session()->reflash();
            return Redirect::route("plancidades.revisao.objetivoEstrategico.editar", ['revisaoId'=> $revisaoId]);
        }

        $revisaoCadastrada = RlcSituacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoId)->orderBy('created_at', 'desc')->first();
        $situacoes_nao_editaveis = array(3,5,6);

        if (in_array($revisaoCadastrada->situacao_revisao_id, $situacoes_nao_editaveis)) {
            flash()->erro("Erro", "Não foi possível atualizar a revisao.");
            return Redirect::route("plancidades.revisao.objetivoEstrategico.listarRevisoes", ['indicadorId'=> $revisaoCadastrada->indicador_objetivo_estrategico_id]);
        }
        else{
            $dadosRevisao = RevisaoIndicadores::find($revisaoId);
            $dadosRevisao->bln_indicador = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_metas = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_regionalizacao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;

            $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::where('id', $dadosRevisao->indicador_objetivo_estrategico_id)->first();
            $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
            return view('modulo_plancidades.revisao.objetivo_estrategico.criar_revisao_indicador', compact('dadosIndicador','revisaoCadastrada', 'dadosRevisao', 'dadosMetaRevisao'));
        
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $revisaoId)
    {
        // return $request;
        
        $user = Auth()->user();
        DB::beginTransaction();
        
        $indicadorRevisaoExiste = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

        if($indicadorRevisaoExiste){
            return Redirect::route("plancidades.revisao.objetivoEstrategico.editar", ["revisaoId" => $revisaoId]);
        }

        $dados_revisao = RevisaoIndicadores::find($revisaoId);
        $dados_indicador = IndicadoresObjetivosEstrategicos::find($dados_revisao->indicador_objetivo_estrategico_id);
        $dados_indicador_revisao = new IndicadoresObjetivosEstrategicosRevisao();
        
        $dados_indicador_revisao->revisao_indicador_id = $revisaoId;
        $dados_indicador_revisao->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;

        $dados_indicador_revisao->txt_denominacao_indicador = $request->txt_denominacao_indicador_nova;
        $dados_indicador_revisao->dsc_indicador = $request->dsc_indicador_nova;
        $dados_indicador_revisao->txt_sigla_indicador = $request->txt_sigla_indicador_nova;
        $dados_indicador_revisao->vlr_indice_referencia = $request->vlr_indice_referencia_nova;
        $dados_indicador_revisao->unidade_medida_id = $request->unidade_medida_id_nova;
        $dados_indicador_revisao->txt_data_divulgacao_ou_disponibilizacao = $request->txt_data_divulgacao_ou_disponibilizacao_nova;
        $dados_indicador_revisao->periodicidades_id = $request->periodicidade_id_nova;
        $dados_indicador_revisao->polaridades_id = $request->polaridade_id_nova;
        $dados_indicador_revisao->txt_formula_calculo = $request->txt_formula_calculo_nova;
        $dados_indicador_revisao->txt_fonte_dados_variaveis_calculo = $request->txt_fonte_dados_variaveis_calculo_nova;
        $dados_indicador_revisao->txt_forma_disponibilizacao = $request->txt_forma_disponibilizacao_nova;
        $dados_indicador_revisao->dsc_procedimento_calculo = $request->dsc_procedimento_calculo_nova;
        $dados_indicador_revisao->bln_ppa = $dados_indicador->bln_ppa;

        $dados_indicador_revisao->objetivo_estrategico_pei_id = '-';
        $dados_indicador_revisao->created_at = date('Y-m-d H:i:s');
        $dados_indicador_revisao->user_id = $user->id;
        $dados_salvos = $dados_indicador_revisao->save();


        $situacao_revisao_indicadores = new RlcSituacaoRevisaoIndicadores();
        $situacao_revisao_indicadores->revisao_indicador_id = $dados_revisao->id;
        $situacao_revisao_indicadores->situacao_revisao_id = '2';
        $situacao_revisao_indicadores->user_id = $user->id;
        $situacao_revisao_indicadores->created_at = date('Y-m-d H:i:s');
        $situacao_revisao_indicadores->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;
        $situacao_revisao_indicadores->save();
        
        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Revisão do Indicador de Objetivo Estratégico cadastrada com sucesso!");
            return Redirect::route("plancidades.revisao.meta.objetivoEstrategico.criar", ["revisaoId" => $revisaoId]);
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível cadastrar a revisão.");
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($revisaoId)
    {
        $dadosRevisao = RevisaoIndicadores::with('periodoRevisao')->find($revisaoId);
        $dadosRevisao->bln_indicador = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
        $dadosRevisao->bln_metas = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
        $dadosRevisao->bln_regionalizacao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;

        $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::find($dadosRevisao->indicador_objetivo_estrategico_id);
        $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosRegionalizacaoRevisao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->get();
        $dadosRegionalizacao = RegionalizacaoMetaObjEstr::where('meta_objetivos_estrategicos_id', $dadosMetaRevisao->meta_indicador_objetivo_estrategico_id)->with('regionalizacao')->get();
        $situacaoRevisao = RlcSituacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoId)->orderBy('created_at', 'desc')->first();

        return view('modulo_plancidades.revisao.objetivo_estrategico.show_revisao_indicador', compact('dadosIndicador', 'dadosRevisao', 'dadosIndicadorRevisao', 'dadosMetaRevisao', 'dadosRegionalizacaoRevisao', 'dadosRegionalizacao', 'situacaoRevisao'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($revisaoId)
    {
        $revisaoCadastrada = RlcSituacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoId)->orderBy('created_at', 'desc')->first();
        $dadosRevisao = RevisaoIndicadores::find($revisaoId);

        $dadosRevisao->bln_indicador = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_metas = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_regionalizacao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;

        if($revisaoCadastrada && ($revisaoCadastrada->situacao_revisao_id == 3 || $revisaoCadastrada->situacao_revisao_id == 5 || $revisaoCadastrada->situacao_revisao_id == 6 )) {
            flash()->erro("Erro", "Não foi possível atualizar a revisao.");
            return Redirect::route("plancidades.revisao.objetivoEstrategico.listarRevisoes", ['indicadorId'=> $revisaoCadastrada->indicador_objetivo_estrategico_id]);
        }
        else{
            $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
            $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

            if($dadosIndicadorRevisao){
                $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::where('id', $dadosIndicadorRevisao->indicador_objetivo_estrategico_id)->first();
                return view('modulo_plancidades.revisao.objetivo_estrategico.editar_revisao_indicador', compact('dadosIndicador', 'dadosRevisao','revisaoCadastrada', 'dadosIndicadorRevisao', 'dadosMetaRevisao'));
            }else{
                return Redirect::route('plancidades.revisao.objetivoEstrategico.criar', ["revisaoId" => $revisaoId]);
            }
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $revisaoId)
    {
        //return ($request);
        //return ($revisaoId);

        $user = Auth()->user();
        DB::beginTransaction();

        $dados_revisao = RevisaoIndicadores::find($revisaoId);
        $dados_indicador = IndicadoresObjetivosEstrategicos::find($dados_revisao->indicador_objetivo_estrategico_id);
        $dados_indicador_revisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

        $dados_indicador_revisao->user_id = $user->id;
        $dados_indicador_revisao->revisao_indicador_id = $request->revisao_indicador_id;

        //No caso de esses campos não serem preenchidos, eles manterão o valor da iniciativa atual
        //=============
        $dados_indicador_revisao->txt_denominacao_indicador = $request->txt_denominacao_indicador_nova;
        $dados_indicador_revisao->dsc_indicador = $request->dsc_indicador_nova;
        $dados_indicador_revisao->txt_sigla_indicador = $request->txt_sigla_indicador_nova;
        $dados_indicador_revisao->vlr_indice_referencia = $request->vlr_indice_referencia_nova;
        $dados_indicador_revisao->unidade_medida_id = $request->unidade_medida_id_nova;
        $dados_indicador_revisao->txt_data_divulgacao_ou_disponibilizacao = $request->txt_data_divulgacao_ou_disponibilizacao_nova;
        $dados_indicador_revisao->periodicidades_id = $request->periodicidade_id_nova;
        $dados_indicador_revisao->polaridades_id = $request->polaridade_id_nova;
        $dados_indicador_revisao->txt_formula_calculo = $request->txt_formula_calculo_nova;
        $dados_indicador_revisao->txt_fonte_dados_variaveis_calculo = $request->txt_fonte_dados_variaveis_calculo_nova;
        $dados_indicador_revisao->txt_forma_disponibilizacao = $request->txt_forma_disponibilizacao_nova;
        $dados_indicador_revisao->dsc_procedimento_calculo = $request->dsc_procedimento_calculo_nova;
        $dados_indicador_revisao->bln_ppa = $dados_indicador->bln_ppa;
        //=============

        $dados_indicador_revisao->objetivo_estrategico_pei_id = '-';
        $dados_indicador_revisao->updated_at = date('Y-m-d H:i:s');
        $dados_salvos = $dados_indicador_revisao->update();
        
        
        //Não há necessidade de tabela de relação entre Revisão e Situação. A situação da revisão pode ser salva diretamente na tab da revisão - Renan: combinamos de manter igual Monitoramento e depois ajustar.
        if ($dados_salvos) {
            $situacao_revisao_indicador = new RlcSituacaoRevisaoIndicadores();
            $situacao_revisao_indicador->revisao_indicador_id = $revisaoId;
            $situacao_revisao_indicador->user_id = $user->id;
            $situacao_revisao_indicador->situacao_revisao_id = '2';
            $situacao_revisao_indicador->created_at = date('Y-m-d H:i:s');
            $situacao_revisao_indicador->indicador_objetivo_estrategico_id = $request->indicador_objetivo_estrategico_id;
            $situacao_revisao_indicador->save();
        

            DB::commit();
            flash()->sucesso("Sucesso", "Revisão do indicador de Objetivo Estratégico atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.meta.objetivoEstrategico.criar", ['revisaoId'=> $revisaoId]);
        
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível atualizar a revisao.");
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
    
    public function iniciarRevisao($indicadorId)
    {   
        $revisaoCadastrada = RlcSituacaoRevisaoIndicadores::where('indicador_objetivo_estrategico_id', $indicadorId)->orderBy('created_at', 'desc')->first();

        $situacoes = array('5', '6', null);

        if (!empty($revisaoCadastrada)) {
            if(!in_array($revisaoCadastrada->situacao_revisao_id, $situacoes)) {
                DB::rollBack();
                flash()->erro("Erro", "Já existe uma revisão em andamento.");
                return back();
            }
        }

        $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::find($indicadorId);

        $dadosOEPPA = ObjetivosEspecificosPPA::where('indicadores_objetivos_estrategicos_id', $indicadorId)->first();
        
        return view('modulo_plancidades.revisao.objetivo_estrategico.iniciar_revisao_indicador', compact('dadosIndicador', 'dadosOEPPA'));

    }

    public function finalizarRevisao($revisaoId){

        //return ($revisaoId);

        $user = Auth()->user();
        DB::beginTransaction();

        $dados_revisao = RevisaoIndicadores::where('id', $revisaoId)->first();

        $situacao_revisao_indicadores = new RlcSituacaoRevisaoIndicadores();
        $situacao_revisao_indicadores->revisao_indicador_id = $revisaoId;
        $situacao_revisao_indicadores->situacao_revisao_id = '3';
        $situacao_revisao_indicadores->user_id = $user->id;
        $situacao_revisao_indicadores->created_at = date('Y-m-d H:i:s');
        $situacao_revisao_indicadores->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;
        $dados_salvos = $situacao_revisao_indicadores->save();



        if ($dados_salvos) {
            DB::commit();

            flash()->sucesso("Sucesso", "Revisão do Indicador de Objetivo Estratégico cadastrada com sucesso!");
             return Redirect::route("plancidades.revisao.objetivoEstrategico.listarRevisoes", ["indicador_objetivo_estrategico_id" => $dados_revisao->indicador_objetivo_estrategico_id]);
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível cadastrar a revisão.");
            return back();
        }
    }

    public function salvarRevisao(Request $request)
    {
        //return ($request);

        $user = Auth()->user();

        DB::beginTransaction();
        
        $where = [];
        $where[] = ['indicador_objetivo_estrategico_id', $request->indicador];
        $where[] = ['num_ano_periodo_revisao', $request->anoRevisao];
        $where[] = ['periodo_revisao_id', $request->periodoRevisao];
        
        $revisaoCadastrada = RevisaoIndicadores::where($where)->first();

        if (!empty($revisaoCadastrada)) {
            DB::rollBack();
            flash()->erro("Erro", "Já existe uma revisão em andamento.");
            return back();
        }   

        $dados_revisao = new RevisaoIndicadores();

        $dados_revisao->user_id = $user->id;
        $dados_revisao->indicador_objetivo_estrategico_id = $request->indicador;
        $dados_revisao->periodo_revisao_id = $request->periodoRevisao;
        $dados_revisao->num_ano_periodo_revisao = $request->anoRevisao;
        $dados_revisao->created_at = date('Y-m-d H:i:s');

        $dados_salvos = $dados_revisao->save();
        
        $situacao_revisao_indicadores = new RlcSituacaoRevisaoIndicadores();
        $situacao_revisao_indicadores->revisao_indicador_id = $dados_revisao->id;
        $situacao_revisao_indicadores->situacao_revisao_id = '2';
        $situacao_revisao_indicadores->user_id = $user->id;
        $situacao_revisao_indicadores->created_at = date('Y-m-d H:i:s');
        $situacao_revisao_indicadores->indicador_objetivo_estrategico_id = $request->indicador;
        $situacao_revisao_indicadores->save();
        
        if ($dados_salvos) {
            DB::commit();

            flash()->sucesso("Sucesso", "Revisão do Indicador cadastrada com sucesso!");
            return Redirect::route("plancidades.revisao.objetivoEstrategico.criar", ["revisaoId" => $dados_revisao->id]);
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível cadastrar a revisão.");
            return back();
        }
    }

    public function consultarIndicadores()
    {
        return view("modulo_plancidades.revisao.objetivo_estrategico.consultar_indicador");
    }

    public function pesquisarRevisoes(Request $request)
    {
        $where = [];

        if ($request->orgaoResponsavel) {
            $where[] = ['orgao_pei_id', $request->orgaoResponsavel];
        }

        if ($request->objetivoEstrategico) {
            $where[] = ['objetivo_estrategico_pei_id', $request->objetivoEstrategico];
        }

        if ($request->indicador) {
            $where[] = ['indicador_objetivo_estrategico_id', $request->indicador];
        }

        $revisoes = ViewRevisaoIndicadoresObjEstrategicos::where($where)->orderBy('indicador_objetivo_estrategico_id')->paginate(10);

        if (count($revisoes) > 0) {
            return view("modulo_plancidades.revisao.objetivo_estrategico.listar_revisoes_indicador", compact('revisoes'));
        } else {
            flash()->erro("Erro", "Nenhum monitoramento encontrado...");
            return back();
        }
    }

}
