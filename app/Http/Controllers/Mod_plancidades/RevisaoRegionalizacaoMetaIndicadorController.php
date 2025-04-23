<?php

namespace App\Http\Controllers\Mod_plancidades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\IndicadoresObjetivosEstrategicosRevisao;
use App\Mod_plancidades\MetasObjetivosEstrategicos;
use App\Mod_plancidades\MetasObjetivosEstrategicosRevisao;
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
use App\Mod_plancidades\ViewIndicadoresIniciativasRevisao;
use App\Mod_plancidades\ViewRevisaodicadores;
use App\Mod_plancidades\ViewResumoApuracaoMetaIndicador;
use App\Mod_plancidades\ViewValidacaoMonitoramentoIndicadores;
use App\Mod_plancidades\ViewValiddicadores;
use Exception;
use Illuminate\Support\Facades\DB;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;

class RevisaoRegionalizacaoMetaIndicadorController extends Controller
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
    public function index($iniciativaId)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($revisaoId)
    {   
        $regionalizacaoRevisaoExiste = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->first();
        if(!empty($regionalizacaoRevisaoExiste)){
            return Redirect::route("plancidades.revisao.regionalizacao.objetivoEstrategico.editar", ["revisaoId" => $revisaoId]);
        }

        $revisaoCadastrada = RlcSituacaoRevisaoIndicadores::where('revisao_indicador_id', $revisaoId)->orderBy('created_at', 'desc')->first();

        $dadosRevisao = RevisaoIndicadores::find($revisaoId);
        $dadosRevisao->bln_indicador = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
        $dadosRevisao->bln_metas = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;
        $dadosRevisao->bln_regionalizacao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->first()?true:false;

        $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::where('id', $dadosRevisao->indicador_objetivo_estrategico_id)->first();
        $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosMeta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id' , $dadosRevisao->indicador_objetivo_estrategico_id)->first();
        $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosRegionalizacao = RegionalizacaoMetaObjEstr::where('meta_objetivos_estrategicos_id', $dadosMeta->id)->with('regionalizacao')->get();
        
        return view('modulo_plancidades.revisao.objetivo_estrategico.regionalizacao.criar_revisao_regionalizacao_meta_indicador', compact('dadosIndicador', 'dadosIndicadorRevisao', 'dadosMetaRevisao', 'revisaoCadastrada', 'dadosRevisao', 'dadosRegionalizacao'));
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

        try{
            $dados_revisao = RevisaoIndicadores::find($revisaoId);

            foreach ($request->novaRegionalizacao as $item) {
                $regionalizacao = (object) $item;
                
                $where = [];
                $where[] = ['revisao_indicador_id', $revisaoId];
                $where[] = ['regionalizacao_id', $regionalizacao->regionalizacao_id];
        
                $regionalizacaoExiste = RegionalizacaoMetaObjEstrRevisao::where($where)->first();

                if($regionalizacaoExiste){
                   throw new \Exception("Regionalização já cadastrada.");
                }

                $dados_regionalizacao_revisao = new RegionalizacaoMetaObjEstrRevisao();
                $dados_regionalizacao = RegionalizacaoMetaObjEstr::where('txt_sigla_objetivos_estrategicos_metas_region', $regionalizacao->txt_sigla_objetivos_estrategicos_metas_region)->first();
                
                $dados_regionalizacao_revisao->txt_sigla_objetivos_estrategicos_metas_region = $regionalizacao->txt_sigla_objetivos_estrategicos_metas_region;
                $dados_regionalizacao_revisao->meta_objetivos_estrategicos_id = $dados_regionalizacao->meta_objetivos_estrategicos_id;
                $dados_regionalizacao_revisao->regionalizacao_id = $regionalizacao->regionalizacao_id;
                $dados_regionalizacao_revisao->vlr_esperado_ano_2 = $regionalizacao->vlr_esperado_ano_2;
                $dados_regionalizacao_revisao->vlr_esperado_ano_3 = $regionalizacao->vlr_esperado_ano_3;
                $dados_regionalizacao_revisao->vlr_esperado_ano_4 = $regionalizacao->vlr_esperado_ano_4;
                $dados_regionalizacao_revisao->revisao_indicador_id = $revisaoId;
                $dados_regionalizacao_revisao->created_at = date('Y-m-d H:i:s');
                $dados_regionalizacao_revisao->user_id = $user->id;

                $dados_salvos = $dados_regionalizacao_revisao->save();
                
                if(!$dados_salvos){
                    throw new \Exception('Erro ao salvar a regionalização.');
                }
            }

            DB::commit();
            flash()->sucesso("Sucesso", "Revisão das regionalizações cadastrada com sucesso!");
            return Redirect::route("plancidades.revisao.objetivoEstrategico.show", ["revisaoId" => $dados_revisao->id]);

        } catch (\Exception $e){
            DB::rollBack();
            flash()->erro("Erro", $e->getMessage());
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

        $dadosIndicador = ViewIndicadoresObjetivosEstrategicosMetas::where('id', $dadosRevisao->indicador_objetivo_estrategico_id)->first();
        $dadosIndicadorRevisao = IndicadoresObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosMeta = MetasObjetivosEstrategicos::where('indicador_objetivo_estrategico_id' , $dadosRevisao->indicador_objetivo_estrategico_id)->first();
        $dadosMetaRevisao = MetasObjetivosEstrategicosRevisao::where('revisao_indicador_id', $revisaoId)->first();
        $dadosRegionalizacao = RegionalizacaoMetaObjEstr::where('meta_objetivos_estrategicos_id', $dadosMeta->id)->with('regionalizacao')->get();
        $dadosRegionalizacaoRevisao = RegionalizacaoMetaObjEstrRevisao::where('revisao_indicador_id', $revisaoId)->get();

        
        return view('modulo_plancidades.revisao.objetivo_estrategico.regionalizacao.editar_revisao_regionalizacao_meta_indicador', compact('dadosIndicador', 'dadosIndicadorRevisao', 'dadosMeta', 'dadosMetaRevisao', 'revisaoCadastrada', 'dadosRevisao', 'dadosRegionalizacao', 'dadosRegionalizacaoRevisao'));
    
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

        $user = Auth()->user();
        DB::beginTransaction();

        try{
            $dados_revisao = RevisaoIndicadores::find($revisaoId);

            foreach ($request->novaRegionalizacao as $item) {
                $regionalizacao = (object) $item;

                $where = [];
                $where[] = ['revisao_indicador_id', $revisaoId];
                $where[] = ['regionalizacao_id', $regionalizacao->regionalizacao_id];

                $dados_regionalizacao_revisao = RegionalizacaoMetaObjEstrRevisao::where($where)->first();
                $dados_regionalizacao = RegionalizacaoMetaObjEstr::where('txt_sigla_objetivos_estrategicos_metas_region', $regionalizacao->txt_sigla_objetivos_estrategicos_metas_region)->first();                
                
                $dados_regionalizacao_revisao->txt_sigla_objetivos_estrategicos_metas_region = $dados_regionalizacao->txt_sigla_objetivos_estrategicos_metas_region;
                $dados_regionalizacao_revisao->meta_objetivos_estrategicos_id = $dados_regionalizacao->meta_objetivos_estrategicos_id;
                $dados_regionalizacao_revisao->regionalizacao_id = $regionalizacao->regionalizacao_id;
                $dados_regionalizacao_revisao->vlr_esperado_ano_2 = $regionalizacao->vlr_esperado_ano_2;
                $dados_regionalizacao_revisao->vlr_esperado_ano_3 = $regionalizacao->vlr_esperado_ano_3;
                $dados_regionalizacao_revisao->vlr_esperado_ano_4 = $regionalizacao->vlr_esperado_ano_4;
                $dados_regionalizacao_revisao->revisao_indicador_id = $revisaoId;
                $dados_regionalizacao_revisao->updated_at = date('Y-m-d H:i:s');
                $dados_regionalizacao_revisao->user_id = $user->id;

                $dados_salvos = $dados_regionalizacao_revisao->update();
                
                if(!$dados_salvos){
                    throw new \Exception('Erro ao atualizar a regionalização ' . $dados_regionalizacao->txt_sigla_objetivos_estrategicos_metas_region);
                }
            }

            DB::commit();
            flash()->sucesso("Sucesso", "Revisão das regionalizações atualizada com sucesso!");
            return Redirect::route("plancidades.revisao.objetivoEstrategico.show", ["revisaoId" => $dados_revisao->id]);

        } catch (\Exception $e){
            DB::rollBack();
            flash()->erro("Erro", $e->getMessage());
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

        $revisoes = ViewRevisdicadoresObjEstrategicos::where($where)->orderBy('indicador_objetivo_estrategico_id')->paginate(10);

        if (count($revisoes) > 0) {
            return view("modulo_plancidades.revisao.objetivo_estrategico.listar_revisoes_indicador", compact('revisoes'));
        } else {
            flash()->erro("Erro", "Nenhum monitoramento encontrado...");
            return back();
        }
    }

}
