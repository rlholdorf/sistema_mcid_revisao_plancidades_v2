<?php

namespace App\Http\Controllers\Mod_plancidades;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mod_plancidades\EtapasProjeto;
use App\Mod_plancidades\EtapasProjetoRevisao;
use App\Mod_plancidades\ProjetosRevisao;
use App\Mod_plancidades\RevisaoProjetos;
use App\Mod_plancidades\RlcSituacaoRevisaoProjetos;
use App\Mod_plancidades\ViewProjetos;
use Illuminate\Support\Facades\DB;
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
        $dadosEtapas = EtapasProjeto::where('projeto_id' , $dadosRevisao->projeto_id)->get();
        $dadosEtapasRevisao = EtapasProjetoRevisao::where('revisao_projeto_id', $revisaoId)->first();
        
        return view('modulo_plancidades.revisao.projeto.etapas.criar_revisao_etapas', compact('dadosProjeto', 'dadosProjetoRevisao', 'dadosEtapas', 'revisaoCadastrada', 'dadosRevisao', 'dadosEtapasRevisao'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return "store";
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
    public function edit($id)
    {
        return "edit";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return "update";
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