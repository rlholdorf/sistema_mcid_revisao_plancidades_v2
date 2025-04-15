<?php

namespace App\Http\Controllers\Mod_plancidades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicosRevisao;
use App\Mod_plancidades\MetasObjetivosEstrategicos;
use App\Mod_plancidades\MetasObjetivosEstrategicosRevisao;
use App\Mod_plancidades\RegionalizacaoMetaObjEstrRevisao;
use App\Mod_plancidades\RevisaoIndicadores;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoMonitoramentoIndicadores;
use App\Mod_plancidades\RlcSituacaoRevisaoIndicadores;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewIndicadoresIniciativas;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicosRevisao;
use App\Mod_plancidades\ViewRevisaoIndicadores;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoMonitoramentoIndicadores;
use App\Mod_plancidades\ViewValidacaoRevisaoIndicadores;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class RevisaoMetaIndicadorController extends Controller
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
        $revisoes = ViewRevisaoIndicadores::where('view_revisao_iniciativas.iniciativa_id', $indicadorId)
        ->orderBy('view_revisao_iniciativas.revisao_indicador_id', 'DESC')
        ->leftJoin('mcid_hom_plancidades.view_validacao_revisao_iniciativas','view_validacao_revisao_iniciativas.revisao_indicador_id','=','view_revisao_iniciativas.revisao_indicador_id')
        ->select('view_revisao_iniciativas.*','view_validacao_revisao_iniciativas.situacao_revisao_id','view_validacao_revisao_iniciativas.txt_situacao_revisao')
        ->get();

        if(count($revisoes) > 0){
            return view("modulo_plancidades.revisao.iniciativa.listar_revisoes_iniciativa", compact('revisoes'));
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
        //Se a revisão da meta do indicador já existe, redireciona para página de edição
        $metaRevisaoExiste = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        if (!empty($metaRevisaoExiste)) {
            session()->reflash(); //mantêm a mensagem em caso de redirect
            return Redirect::route("plancidades.revisao.meta.objetivoEstrategico.editar", ['revisaoId'=> $revisaoId]);
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
            //return $dadosRevisao;

            $dadosMeta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id' , $dadosRevisao->indicador_objetivo_estrategico_id)->first();
            $dadosIndicador = IndicadoresObjetivosEstrategicos::find($dadosRevisao->indicador_objetivo_estrategico_id);
            $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

            return view('modulo_plancidades.revisao.objetivo_estrategico.metas.criar_revisao_meta_indicador', compact('dadosMeta', 'dadosIndicador','revisaoCadastrada', 'dadosRevisao', 'dadosMetaRevisao'));
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
        //return $request;
        // return $revisaoId;

        $user = Auth()->user();
        DB::beginTransaction();

        $metaRevisaoExiste = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        if($metaRevisaoExiste){
            return Redirect::route('plancidades.revisao.meta.objetivoEstrategico.editar', ["revisaoId" => $revisaoId]);
        }

        $dados_revisao = RevisaoIndicadores::find($revisaoId);
        $dados_meta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id', $dados_revisao->indicador_objetivo_estrategico_id)->first();
        
        $dados_meta_revisao = new MetasObjetivosEstrategicosRevisao();

        $dados_meta_revisao->user_id = $user->id;
        $dados_meta_revisao->revisao_indicador_id = $revisaoId;
        $dados_meta_revisao->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;
        $dados_meta_revisao->meta_indicador_objetivo_estrategico_id = $dados_meta->id;
        $dados_meta_revisao->txt_dsc_meta = $request->txt_dsc_meta_nova;
        $dados_meta_revisao->bln_meta_cumulativa = $request->bln_meta_cumulativa_nova;
        $dados_meta_revisao->vlr_esperado_ano_2 = $request->vlr_esperado_ano_2_nova;
        $dados_meta_revisao->vlr_esperado_ano_3 = $request->vlr_esperado_ano_3_nova;
        $dados_meta_revisao->vlr_esperado_ano_4 = $request->vlr_esperado_ano_4_nova;
        $dados_meta_revisao->bln_meta_regionalizada = $request->bln_meta_regionalizada_nova ? $request->bln_meta_regionalizada_nova : $dados_meta->bln_meta_regionalizada;
        $dados_meta_revisao->dsc_justificativa_ausencia_regionalizacao = $request->dsc_justificativa_ausencia_regionalizacao_nova;
        $dados_meta_revisao->created_at = date('Y-m-d H:i:s');

        $dados_salvos = $dados_meta_revisao->save();

        if ($dados_salvos) {

            $situacao_revisao_indicadores = new RlcSituacaoRevisaoIndicadores();
            $situacao_revisao_indicadores->revisao_indicador_id = $dados_revisao->id;
            $situacao_revisao_indicadores->situacao_revisao_id = '2';
            $situacao_revisao_indicadores->user_id = $user->id;
            $situacao_revisao_indicadores->created_at = date('Y-m-d H:i:s');
            $situacao_revisao_indicadores->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;
            $situacao_revisao_indicadores->save();

            DB::commit();
            flash()->sucesso("Sucesso", "Revisão da meta do Indicador cadastrada com sucesso!");
            if($dados_meta_revisao->bln_meta_regionalizada == 'true'){
                return Redirect::route("plancidades.revisao.regionalizacao.objetivoEstrategico.criar", ["revisaoId" => $revisaoId]);
            }
            else{
                return Redirect::route("plancidades.revisao.objetivoEstrategico.show", ["revisaoId" => $revisaoId]);
            }
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
            $dadosIndicador = ViewIndicadoresObjetivosEstrategicosRevisao::where('id', $dadosRevisao->indicador_objetivo_estrategico_id)->first();
            $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

            switch ($dadosIndicador->unidade_medida_id){
                case 1:
                    $dadosIndicador->unidade_medida_simbolo = '(R$)';
                    break;
                case 2:
                    $dadosIndicador->unidade_medida_simbolo = '(%)';
                    break;
                case 3:
                    $dadosIndicador->unidade_medida_simbolo = '(ADI)';
                    break;
                case 4:
                    $dadosIndicador->unidade_medida_simbolo = '(m²)';
                    break;
                case 5:
                    $dadosIndicador->unidade_medida_simbolo = '(UN)';
                    break;
                default:
                    $dadosIndicador->unidade_medida_simbolo = '';
            }

            switch ($dadosIndicadorRevisao->unidade_medida_id){
                case 1:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '(R$)';
                    break;
                case 2:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '(%)';
                    break;
                case 3:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '(ADI)';
                    break;
                case 4:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '(m²)';
                    break;
                case 5:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '(UN)';
                    break;
                default:
                    $dadosIndicadorRevisao->unidade_medida_simbolo = '';
            }
            
            
            
            $dadosMeta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id', $dadosRevisao->indicador_objetivo_estrategico_id)->first();
            $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
            $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

            if($dadosMetaRevisao){
                return view('modulo_plancidades.revisao.objetivo_estrategico.metas.editar_revisao_meta_indicador', compact('dadosRevisao', 'dadosIndicador', 'dadosIndicadorRevisao', 'dadosMeta', 'dadosMetaRevisao', 'revisaoCadastrada'));
            }else{
                return Redirect::route('plancidades.revisao.meta.objetivoEstrategico.criar', ["revisaoId" => $revisaoId]);
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
        //return $request;
        // return $revisaoId;

        $user = Auth()->user();
        DB::beginTransaction();

        $dados_revisao = RevisaoIndicadores::find($revisaoId);
        $dados_meta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id', $dados_revisao->indicador_objetivo_estrategico_id)->first();
        $dados_meta_revisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();

        $dados_meta_revisao->user_id = $user->id;
        $dados_meta_revisao->revisao_indicador_id = $revisaoId;
        $dados_meta_revisao->indicador_objetivo_estrategico_id = $dados_revisao->indicador_objetivo_estrategico_id;
        $dados_meta_revisao->meta_indicador_objetivo_estrategico_id = $dados_meta->id;
        $dados_meta_revisao->txt_dsc_meta = $request->txt_dsc_meta_nova;
        $dados_meta_revisao->bln_meta_cumulativa = $request->bln_meta_cumulativa_nova;
        $dados_meta_revisao->vlr_esperado_ano_2 = $request->vlr_esperado_ano_2_nova;
        $dados_meta_revisao->vlr_esperado_ano_3 = $request->vlr_esperado_ano_3_nova;
        $dados_meta_revisao->vlr_esperado_ano_4 = $request->vlr_esperado_ano_4_nova;
        $dados_meta_revisao->bln_meta_regionalizada = $request->bln_meta_regionalizada_nova ? $request->bln_meta_regionalizada_nova : $dados_meta->bln_meta_regionalizada;
        $dados_meta_revisao->dsc_justificativa_ausencia_regionalizacao = $request->dsc_justificativa_ausencia_regionalizacao_nova;
        $dados_meta_revisao->updated_at = date('Y-m-d H:i:s');

        //return $dados_meta_revisao;
        $dados_salvos = $dados_meta_revisao->update();

        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Revisão da meta do Indicador atualizada com sucesso!");
            if($dados_meta_revisao->bln_meta_regionalizada == 'true'){
                return Redirect::route("plancidades.revisao.regionalizacao.objetivoEstrategico.criar", ["revisaoId" => $revisaoId]);
            }
            else{
                return Redirect::route("plancidades.revisao.objetivoEstrategico.show", ["revisaoId" => $revisaoId]);
            }
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível cadastrar a revisão.");
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
    

    public function consultarIniciativas()
    {
        return view("modulo_plancidades.revisao.iniciativa.consultar_iniciativa");
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
