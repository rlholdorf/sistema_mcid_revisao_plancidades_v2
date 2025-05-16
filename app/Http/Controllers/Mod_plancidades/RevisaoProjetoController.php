<?php

namespace App\Http\Controllers\Mod_plancidades;

use App\Mod_plancidades\MetasIndicadoresObjetivosEstrategicos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\Projetos;
use App\Mod_plancidades\ProjetosRevisao;
use App\Mod_plancidades\EtapasProjeto;
use App\Mod_plancidades\EtapasProjetoRevisao;
use App\Mod_plancidades\MetasObjetivosEstrategicos;
use App\Mod_plancidades\RevisaoProjetos;
use App\Mod_plancidades\MonitoramentoIndicadoresObjEspecificos;
use App\Mod_plancidades\RegionalizacaoMetaObjEstr;
use App\Mod_plancidades\RlcMetasMonitoramentoIndicadores;
use App\Mod_plancidades\RlcMonitoramentoObjEspecificos;
use App\Mod_plancidades\RlcRestricaoMetaMonitoramentoIndic;
use App\Mod_plancidades\RlcSituacaoRevisaoProjetos;
use App\Mod_plancidades\ViewIndicadoresObjetivosEstrategicos;
use App\Mod_plancidades\ViewApuracaoMetaIndicador;
use App\Mod_plancidades\ViewMonitoramentoIndicadoresObjEstrategicos;
use App\Mod_plancidades\ViewProjetos;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoMonitoramentoIndicadores;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class RevisaoProjetoController extends Controller
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
        $monitoramentos = ViewMonitoramentoIndicadoresObjEstrategicos::where('view_monitoramento_indicadores.indicador_objetivo_estrategico_id', $indicadorId)
        ->orderBy('view_monitoramento_indicadores.monitoramento_indicador_id', 'DESC')
        ->leftJoin('mcid_hom_plancidades.view_validacao_monitoramento_indicadores','view_validacao_monitoramento_indicadores.monitoramento_indicador_id','=','view_monitoramento_indicadores.monitoramento_indicador_id')
        ->select('view_monitoramento_indicadores.*','view_validacao_monitoramento_indicadores.situacao_monitoramento_id','view_validacao_monitoramento_indicadores.txt_situacao_monitoramento')
        ->get();

        if(count($monitoramentos) > 0){
            return view("modulo_plancidades.objetivo_estrategico.listar_monitoramentos_indicador", compact('monitoramentos'));
        }else{
            flash()->erro("Erro", "Nenhum monitoramento encontrado...");
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
        //Se a revisão do projeto já existe, redireciona para página de edição
        $projetoExiste = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();
        if (!empty($projetoExiste)) {
            session()->reflash();
            return Redirect::route("plancidades.revisao.projeto.editar", ['revisaoId'=> $revisaoId]);
        }

        $revisaoCadastrada = RlcSituacaoRevisaoProjetos::where('revisao_projeto_id', $revisaoId)->orderBy('created_at', 'desc')->first();
        $situacoes_nao_editaveis = array(3,5,6);

        if (in_array($revisaoCadastrada->situacao_revisao_id, $situacoes_nao_editaveis)) {
            flash()->erro("Erro", "Não foi possível atualizar a revisao.");
            return Redirect::route("plancidades.revisao.projeto.listarRevisoes", ['projetoId'=> $revisaoCadastrada->projeto_id]);
        }
        else{
            $dadosRevisao = RevisaoProjetos::find($revisaoId);
            $dadosRevisao->bln_projeto = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_etapas = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;
            
            $dadosProjeto = ViewProjetos::where('projeto_id', $dadosRevisao->projeto_id)->first();
            return view('modulo_plancidades.revisao.projeto.criar_revisao_projeto', compact('dadosProjeto','revisaoCadastrada', 'dadosRevisao'));
        
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
        
        $projetoRevisaoExiste = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();

        if($projetoRevisaoExiste){
            return Redirect::route("plancidades.revisao.projeto.editar", ["revisaoId" => $revisaoId]);
        }

        $dados_revisao = RevisaoProjetos::find($revisaoId);

        $dados_projeto_revisao = new ProjetosRevisao();
        
        $dados_projeto_revisao->revisao_projeto_id = $revisaoId;
        $dados_projeto_revisao->projeto_id = $dados_revisao->projeto_id;

        $dados_projeto_revisao->txt_enunciado_projeto = $request->txt_enunciado_projeto_nova;
        $dados_projeto_revisao->dsc_objetivo_projeto = $request->dsc_objetivo_projeto_nova;
        $dados_projeto_revisao->objetivo_estrategico_pei_id = $request->objetivo_estrategico_pei_id_nova;
        $dados_projeto_revisao->unidade_responsavel_id = $request->unidade_responsavel_id_nova;
        $dados_projeto_revisao->bln_ppa = $request->bln_ppa_nova;
        $dados_projeto_revisao->dsc_beneficios = $request->dsc_beneficios_nova;
        $dados_projeto_revisao->dsc_premissas = $request->dsc_premissas_nova;
        $dados_projeto_revisao->dsc_restricoes = $request->dsc_restricoes_nova;
        $dados_projeto_revisao->dsc_nome_patrocinador = $request->dsc_nome_patrocinador_nova;
        $dados_projeto_revisao->cargo_patrocinador_id = $request->cargo_patrocinador_id_nova;
        $dados_projeto_revisao->unidade_patrocinador_id = $request->unidade_patrocinador_id_nova;
        $dados_projeto_revisao->dsc_nome_patrocinador_substituto = $request->dsc_nome_patrocinador_substituto_nova;
        $dados_projeto_revisao->cargo_patrocinador_substituto_id = $request->cargo_patrocinador_substituto_id_nova;
        $dados_projeto_revisao->unidade_patrocinador_substituto_id = $request->unidade_patrocinador_substituto_id_nova;
        $dados_projeto_revisao->dsc_nome_gerente = $request->dsc_nome_gerente_nova;
        $dados_projeto_revisao->cargo_gerente_id = $request->cargo_gerente_id_nova;
        $dados_projeto_revisao->unidade_gerente_id = $request->unidade_gerente_id_nova;
        $dados_projeto_revisao->dsc_nome_gerente_substituto = $request->dsc_nome_gerente_substituto_nova;
        $dados_projeto_revisao->cargo_gerente_substituto_id = $request->cargo_gerente_substituto_id_nova;
        $dados_projeto_revisao->unidade_gerente_substituto_id = $request->unidade_gerente_substituto_id_nova;


        $dados_projeto_revisao->created_at = date('Y-m-d H:i:s');
        $dados_projeto_revisao->user_id = $user->id;
        $dados_salvos = $dados_projeto_revisao->save();


        $situacao_revisao_indicadores = new RlcSituacaoRevisaoProjetos();
        $situacao_revisao_indicadores->revisao_projeto_id = $dados_revisao->id;
        $situacao_revisao_indicadores->situacao_revisao_id = '1';
        $situacao_revisao_indicadores->user_id = $user->id;
        $situacao_revisao_indicadores->created_at = date('Y-m-d H:i:s');
        $situacao_revisao_indicadores->projeto_id = $dados_revisao->projeto_id;
        $situacao_revisao_indicadores->save();
        
        if ($dados_salvos) {
            DB::commit();
            flash()->sucesso("Sucesso", "Revisão do Indicador de Objetivo Estratégico cadastrada com sucesso!");
            return Redirect::route("plancidades.revisao.etapas.projeto.criar", ["revisaoId" => $revisaoId]);
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
        $dados_monitoramento = ViewMonitoramentoIndicadoresObjEstrategicos::find($id);

        switch ($dados_monitoramento->unidade_medida_id){
            case 1:
                $dados_monitoramento->unidade_medida_simbolo = '(R$)';
                break;
            case 2:
                $dados_monitoramento->unidade_medida_simbolo = '(%)';
                break;
            case 3:
                $dados_monitoramento->unidade_medida_simbolo = '(ADI)';
                break;
            case 4:
                $dados_monitoramento->unidade_medida_simbolo = '(m²)';
                break;
            case 5:
                $dados_monitoramento->unidade_medida_simbolo = '(UN)';
                break;
            default:
                $dados_monitoramento->unidade_medida_simbolo = '';
        }

        $resumoApuracaoMeta = ViewResumoApuracaoMetaIndicador::where('monitoramento_indicador_id', $id)->first();

        $metaIndicador = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id', $dados_monitoramento->indicador_objetivo_estrategico_id)->first();

        $regionalizacaoMetas = RegionalizacaoMetaObjEstr::where('meta_objetivos_estrategicos_id', $metaIndicador->id)
            ->leftJoin('mcid_hom_plancidades.rlc_metas_monitoramento_indicadores','rlc_metas_monitoramento_indicadores.regionalizacao_meta_indicador_id','=','tab_regionalizacao_metas_objetivos_estrategicos.id')
            ->where('rlc_metas_monitoramento_indicadores.monitoramento_indicador_id',$id)
            ->orderBy('tab_regionalizacao_metas_objetivos_estrategicos.id')
            ->get();
        $regionalizacaoMetas->load('regionalizacao', 'metasIndicadores.indicador');

        $restricoes = RlcRestricaoMetaMonitoramentoIndic::where('monitoramento_indicador_id', $dados_monitoramento->monitoramento_indicador_id)->get();
        $restricoes->load('monitoramentoIndicador', 'restricaoAtingimentoMeta');

        $situacao_monitoramento = ViewValidacaoMonitoramentoIndicadores::where('monitoramento_indicador_id', $id)->first();

        return view(
            'modulo_plancidades.objetivo_estrategico.show_monitoramento_indicador',
             compact(
                'dados_monitoramento', 
                'resumoApuracaoMeta', 
                'metaIndicador', 
                'regionalizacaoMetas', 
                'restricoes',
                'situacao_monitoramento'
            )
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($revisaoId)
    {
        
        $revisaoCadastrada = RlcSituacaoRevisaoProjetos::where('revisao_projeto_id', $revisaoId)->orderBy('created_at', 'desc')->first();
        $dadosRevisao = RevisaoProjetos::find($revisaoId);
            $dadosRevisao->bln_projeto = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_etapas = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;

        if($revisaoCadastrada && ($revisaoCadastrada->situacao_revisao_id == 3 || $revisaoCadastrada->situacao_revisao_id == 5 || $revisaoCadastrada->situacao_revisao_id == 6 )) {
            flash()->erro("Erro", "Não foi possível atualizar a revisao.");
            return Redirect::route("plancidades.revisao.projeto.listarRevisoes", ['projetoId'=> $revisaoCadastrada->projeto_id]);
        }
        else{
            $dadosProjetoRevisao = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();

            if($dadosProjetoRevisao){
                $dadosProjeto = ViewProjetos::where('projeto_id', $dadosProjetoRevisao->projeto_id)->first();
                return view('modulo_plancidades.revisao.projeto.editar_revisao_projeto', compact('dadosProjeto', 'dadosRevisao','revisaoCadastrada', 'dadosProjetoRevisao'));
            }else{
                return Redirect::route('plancidades.revisao.projeto.criar', ["revisaoId" => $revisaoId]);
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

        $dados_revisao = RevisaoProjetos::find($revisaoId);
        $dados_projeto_revisao = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();

        $dados_projeto_revisao->user_id = $user->id;
        $dados_projeto_revisao->revisao_projeto_id = $revisaoId;

        $dados_projeto_revisao->txt_enunciado_projeto = $request->txt_enunciado_projeto_nova;
        $dados_projeto_revisao->dsc_objetivo_projeto = $request->dsc_objetivo_projeto_nova;
        $dados_projeto_revisao->objetivo_estrategico_pei_id = $request->objetivo_estrategico_pei_id_nova;
        $dados_projeto_revisao->unidade_responsavel_id = $request->unidade_responsavel_id_nova;
        $dados_projeto_revisao->bln_ppa = $request->bln_ppa_nova;
        $dados_projeto_revisao->dsc_beneficios = $request->dsc_beneficios_nova;
        $dados_projeto_revisao->dsc_premissas = $request->dsc_premissas_nova;
        $dados_projeto_revisao->dsc_restricoes = $request->dsc_restricoes_nova;
        $dados_projeto_revisao->dsc_nome_patrocinador = $request->dsc_nome_patrocinador_nova;
        $dados_projeto_revisao->cargo_patrocinador_id = $request->cargo_patrocinador_id_nova;
        $dados_projeto_revisao->unidade_patrocinador_id = $request->unidade_patrocinador_id_nova;
        $dados_projeto_revisao->dsc_nome_patrocinador_substituto = $request->dsc_nome_patrocinador_substituto_nova;
        $dados_projeto_revisao->cargo_patrocinador_substituto_id = $request->cargo_patrocinador_substituto_id_nova;
        $dados_projeto_revisao->unidade_patrocinador_substituto_id = $request->unidade_patrocinador_substituto_id_nova;
        $dados_projeto_revisao->dsc_nome_gerente = $request->dsc_nome_gerente_nova;
        $dados_projeto_revisao->cargo_gerente_id = $request->cargo_gerente_id_nova;
        $dados_projeto_revisao->unidade_gerente_id = $request->unidade_gerente_id_nova;
        $dados_projeto_revisao->dsc_nome_gerente_substituto = $request->dsc_nome_gerente_substituto_nova;
        $dados_projeto_revisao->cargo_gerente_substituto_id = $request->cargo_gerente_substituto_id_nova;
        $dados_projeto_revisao->unidade_gerente_substituto_id = $request->unidade_gerente_substituto_id_nova;

        $dados_projeto_revisao->updated_at = date('Y-m-d H:i:s');
        $dados_salvos = $dados_projeto_revisao->update();
        
        
        //Não há necessidade de tabela de relação entre Revisão e Situação. A situação da revisão pode ser salva diretamente na tab da revisão - Renan: combinamos de manter igual Monitoramento e depois ajustar.
        if ($dados_salvos) {

            DB::commit();
            flash()->sucesso("Sucesso", "Revisão do Projeto atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.etapas.projeto.criar", ['revisaoId'=> $revisaoId]);
        
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
    
    public function iniciarRevisao($projetoId)
    {   
        $revisaoCadastrada = RlcSituacaoRevisaoProjetos::where('projeto_id', $projetoId)->orderBy('created_at', 'desc')->first();

        $situacoes = array('5', '6', null);

        if (!empty($revisaoCadastrada)) {
            if(!in_array($revisaoCadastrada->situacao_revisao_id, $situacoes)) {
                DB::rollBack();
                flash()->erro("Erro", "Já existe uma revisão em andamento.");
                return back();
            }
        }

        $dadosProjeto = ViewProjetos::where('projeto_id', $projetoId)->first();
      
        return view('modulo_plancidades.revisao.projeto.iniciar_revisao_projeto', compact('dadosProjeto'));

    }

    public function salvarRevisao(Request $request)
    {

        $user = Auth()->user();

        DB::beginTransaction();
        
        $where = [];
        $where[] = ['projeto_id', $request->projeto_id];
        $where[] = ['num_ano_periodo_revisao', $request->anoRevisao];
        $where[] = ['periodo_revisao_id', $request->periodoRevisao];
        
        $revisaoCadastrada = RevisaoProjetos::where($where)->first();

        if (!empty($revisaoCadastrada)) {
            DB::rollBack();
            flash()->erro("Erro", "Já existe uma revisão em andamento.");
            return back();
        }   

        $dados_revisao = new RevisaoProjetos();

        $dados_revisao->user_id = $user->id;
        $dados_revisao->projeto_id = $request->projeto_id;
        $dados_revisao->periodo_revisao_id = $request->periodoRevisao;
        $dados_revisao->num_ano_periodo_revisao = $request->anoRevisao;
        $dados_revisao->created_at = date('Y-m-d H:i:s');

        $dados_salvos = $dados_revisao->save();
        
        $situacao_revisao_projetos = new RlcSituacaoRevisaoProjetos();
        $situacao_revisao_projetos->revisao_projeto_id = $dados_revisao->id;
        $situacao_revisao_projetos->situacao_revisao_id = '1';
        $situacao_revisao_projetos->txt_observacao = '';
        $situacao_revisao_projetos->user_id = $user->id;
        $situacao_revisao_projetos->created_at = date('Y-m-d H:i:s');
        $situacao_revisao_projetos->projeto_id = $request->projeto_id;
        $situacao_revisao_projetos->save();
        
        if ($dados_salvos) {
            DB::commit();

            flash()->sucesso("Sucesso", "Revisão do Projeto cadastrada com sucesso!");
            return Redirect::route("plancidades.revisao.projeto.criar", ["revisaoId" => $dados_revisao->id]);
        } else {
            DB::rollBack();
            flash()->erro("Erro", "Não foi possível cadastrar a revisão.");
            return back();
        }
    }

    public function consultarProjetos()
    {
        return view("modulo_plancidades.revisao.projeto.consultar_projeto");
    }

    

    // public function teste(Request $request)
    // {
    //     return ($request);
    // }

}
