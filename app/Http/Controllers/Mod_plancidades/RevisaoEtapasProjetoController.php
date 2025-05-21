<?php

namespace App\Http\Controllers\Mod_plancidades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\EtapasProjeto;
use App\Mod_plancidades\EtapasProjetoRevisao;
use App\Mod_plancidades\ProjetosRevisao;
use App\Mod_plancidades\RevisaoProjetos;
use App\Mod_plancidades\RlcSituacaoRevisaoProjetos;
use App\Mod_plancidades\SituacoesEtapasProjetos;
use App\Mod_plancidades\ViewProjetos;
use App\Mod_plancidades\ViewProjetosRevisao;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Redirect;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegrationAssertPostConditionsForV7AndPrevious;

class RevisaoEtapasProjetoController extends Controller
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($revisaoId)
    {
        $etapaRevisaoExiste = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first();
        if(!empty($etapaRevisaoExiste)){
            return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);
        }

        $revisaoCadastrada = RlcSituacaoRevisaoProjetos::where('revisao_projeto_id', $revisaoId)->orderBy('created_at', 'desc')->first(); 

        $dadosRevisao = RevisaoProjetos::find($revisaoId);
            $dadosRevisao->bln_projeto = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;
            $dadosRevisao->bln_etapas = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first()?true:false;

        $dadosProjeto = ViewProjetos::where('projeto_id', $dadosRevisao->projeto_id)->first();
        $dadosProjetoRevisao = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();
        $dadosEtapas = EtapasProjeto::where('projeto_id' , $dadosRevisao->projeto_id)->with('situacao')->orderBy('id')->get();
        $dadosEtapasRevisao = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->with('situacao')->orderBy('id')->get();
        
        return view('modulo_plancidades.revisao.projeto.etapas.criar_revisao_etapas', compact('dadosProjeto', 'dadosProjetoRevisao', 'dadosEtapas', 'revisaoCadastrada', 'dadosRevisao', 'dadosEtapasRevisao'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $revisaoId)
    {
        
        $etapaRevisaoExiste = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first();

        if(!empty($etapaRevisaoExiste)){
            return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);
        }

        $user = Auth()->user();
        DB::beginTransaction();

        try{
            $dadosRevisao = RevisaoProjetos::find($revisaoId);

            $dadosEtapas = EtapasProjeto::where('projeto_id' , $dadosRevisao->projeto_id)->orderBy('id')->get();

            foreach($dadosEtapas as $item){

                $etapa = (object) $item;
                    
                    $where = [];
                    $where[] = ['revisao_projeto_id', $revisaoId];
                    $where[] = ['etapa_id', $etapa->id];
            
                    $etapaExiste = EtapasProjetoRevisao::where($where)->first();

                    if($etapaExiste){
                    throw new \Exception("Etapa já cadastrada.");
                    }
                
                $dados_etapa_revisao = new EtapasProjetoRevisao();
                
                $dados_etapa_revisao->revisao_projeto_id = $revisaoId;
                $dados_etapa_revisao->projeto_id = $dadosRevisao->projeto_id;
                $dados_etapa_revisao->etapa_id = $item->id;
                $dados_etapa_revisao->dsc_etapa = $item->dsc_etapa;
                $dados_etapa_revisao->dsc_marco = $item->dsc_marco;
                $dados_etapa_revisao->vlr_peso_etapa = $item->vlr_peso_etapa;
                $dados_etapa_revisao->dte_previsao_inicio_etapa = $item->dte_efetiva_inicio_etapa ? $item->dte_efetiva_inicio_etapa : $item->dte_previsao_inicio_etapa;
                $dados_etapa_revisao->dte_previsao_conclusao_etapa = $item->dte_efetiva_conclusao_etapa ? $item->dte_efetiva_conclusao_etapa : $item->dte_previsao_conclusao_etapa;
                $dados_etapa_revisao->situacao_etapa_projeto_id = $item->situacao_etapa_projeto_id ? $item->situacao_etapa_projeto_id : 1 ;                
                $dados_etapa_revisao->created_at = date('Y-m-d H:i:s');
                $dados_etapa_revisao->user_id = $user->id;

                $dados_salvos = $dados_etapa_revisao->save();
                
                if(!$dados_salvos){
                    throw new \Exception('Erro ao salvar a etapa.');
                }
            }

            $situacao_revisao_projetos = new RlcSituacaoRevisaoProjetos();
            $situacao_revisao_projetos->revisao_projeto_id = $dados_revisao->id;
            $situacao_revisao_projetos->situacao_revisao_id = '2';
            $situacao_revisao_projetos->user_id = $user->id;
            $situacao_revisao_projetos->created_at = date('Y-m-d H:i:s');
            $situacao_revisao_projetos->projeto_id = $dados_revisao->projeto_id;
            $situacao_revisao_projetos->save();

            DB::commit();

            if($request->avancar_sem_alteracao = true){
                flash()->sucesso("Sucesso", "Etapas revisadas com sucesso!");
                return Redirect::route("plancidades.revisao.projeto.show", ["revisaoId" => $revisaoId]);
            } 
            
            if($request->revisar_etapas = true){
                flash()->sucesso("Sucesso", "Etapas cadastradas para revisão com sucesso!");
                return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);
            }

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
        //
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

        $dadosProjeto = ViewProjetos::where('projeto_id', $dadosRevisao->projeto_id)->first();
        $dadosProjetoRevisao = ProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();
        $dadosEtapas = EtapasProjeto::where('projeto_id' , $dadosRevisao->projeto_id)->with('situacao')->orderBy('id')->get();
        $dadosEtapasRevisao = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->with('situacao')->orderBy('id')->get();
        $situacaoEtapas = SituacoesEtapasProjetos::select('id', 'txt_nome_situacao as nome')->get();
        $viewProjetosRevisao = ViewProjetosRevisao::where('revisao_projeto_id', $revisaoId)->first();
        
        return view('modulo_plancidades.revisao.projeto.etapas.editar_revisao_etapas', compact('dadosProjeto', 'dadosProjetoRevisao', 'dadosEtapas', 'revisaoCadastrada', 'dadosRevisao', 'dadosEtapasRevisao', 'situacaoEtapas', 'viewProjetosRevisao'));
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

        $user = Auth()->user();
        DB::beginTransaction();

        try{

            $where = [];
            $where[] = ['revisao_projeto_id', $revisaoId];
            $where[] = ['etapa_id', $request->id];

            $dadosEtapasRevisao = EtapasProjetoRevisao::where($where)->first();
            
            if(!$dadosEtapasRevisao){
                throw new \Exception("Etapa não encontrada.");
            }

            $dadosEtapasRevisao->revisao_projeto_id = $revisaoId;
            $dadosEtapasRevisao->projeto_id = $request->projeto_id;
            $dadosEtapasRevisao->dsc_etapa = $request->dsc_etapa;
            $dadosEtapasRevisao->dsc_marco = $request->dsc_marco;
            $dadosEtapasRevisao->vlr_peso_etapa = $request->vlr_peso_etapa;
            $dadosEtapasRevisao->dte_previsao_inicio_etapa = $request->dte_previsao_inicio_etapa;
            $dadosEtapasRevisao->dte_previsao_conclusao_etapa = $request->dte_previsao_conclusao_etapa;
            $dadosEtapasRevisao->situacao_etapa_projeto_id = $request->situacao_etapa_projeto_id;                
            $dadosEtapasRevisao->updated_at = date('Y-m-d H:i:s');
            $dadosEtapasRevisao->user_id = $user->id;

            $dados_salvos = $dadosEtapasRevisao->update();
            
            if(!$dados_salvos){
                throw new \Exception('Erro ao salvar a etapa.');
            }
            
            DB::commit();

            flash()->sucesso("Sucesso", "Etapa revisada com sucesso!");
            return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);
            
        } catch (\Exception $e){
            DB::rollBack();
            flash()->erro("Erro", $e->getMessage());
            return back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $revisaoId)
    {
        try{
            $where = [];
            $where[] = ['revisao_projeto_id', $revisaoId];
            $where[] = ['etapa_id', $request->etapa_id];
        
            $dadosEtapasRevisao = EtapasProjetoRevisao::where($where)->first();
            
            if(!$dadosEtapasRevisao){
                throw new \Exception("Etapa não encontrada.");
            }

            $dados_deletados = $dadosEtapasRevisao->delete();

            if (!$dados_deletados){
                throw new \Exception('Erro ao deletar a etapa.');
            }

            DB::commit();
            flash()->sucesso("Sucesso", "Etapa excluída com sucesso!");
            return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);

        } catch (\Exception $e){
            DB::rollBack();
            flash()->erro("Erro", $e->getMessage());
            return back();
        }
    }

public function adicionarEtapa(Request $request, $revisaoId)
    {

        $user = Auth()->user();
        DB::beginTransaction();

        try{

            $dados_etapa_revisao = new EtapasProjetoRevisao();
                
                $dados_etapa_revisao->revisao_projeto_id = $revisaoId;
                $dados_etapa_revisao->projeto_id = $request->projeto_id;
                $dados_etapa_revisao->etapa_id = $request->etapa_id;
                $dados_etapa_revisao->dsc_etapa = $request->dsc_etapa;
                $dados_etapa_revisao->dsc_marco = $request->dsc_marco;
                $dados_etapa_revisao->vlr_peso_etapa = $request->vlr_peso_etapa;
                $dados_etapa_revisao->dte_previsao_inicio_etapa = $request->dte_previsao_inicio_etapa;
                $dados_etapa_revisao->dte_previsao_conclusao_etapa = $request->dte_previsao_conclusao_etapa;
                $dados_etapa_revisao->situacao_etapa_projeto_id = $request->situacao_etapa_projeto_id;                
                $dados_etapa_revisao->created_at = date('Y-m-d H:i:s');
                $dados_etapa_revisao->user_id = $user->id;

                $dados_salvos = $dados_etapa_revisao->save();
            
            if(!$dados_salvos){
                throw new \Exception('Erro ao salvar a etapa.');
            }
            
            DB::commit();

            flash()->sucesso("Sucesso", "Etapa adicionada com sucesso!");
            return Redirect::route("plancidades.revisao.etapas.projeto.editar", ["revisaoId" => $revisaoId]);
            
        } catch (\Exception $e){
            DB::rollBack();
            flash()->erro("Erro", $e->getMessage());
            return back();
        }

        
    }

}