@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior02="'Consultar Projetos para Revisão'"
        :link2="'{{url('/plancidades/revisao/projeto/consulta')}}'"
        :telanterior01="'PlanCidades'" 
        :link1="'{{url('/plancidades')}}'"
        :telatual="'Revisar Projeto'">

    </historico-navegacao>


    <div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

        <cabecalho-relatorios :botaoEditar='false' titulo="{{$dadosProjeto->projeto_id}} - {{$dadosProjeto->txt_enunciado_projeto}}"
            :linkcompartilhar="'{{ url("/") }}'"
            :barracompartilhar="false">
        </cabecalho-relatorios>
        <p>
           Nesta página, você poderá visualizar todas as informações das etapas do projeto.
           <br>
           Após revisar as etapas, clique em Salvar Revisão.
        </p>
        
        <hr>

        <form role="form" method="GET" action='{{ route("plancidades.revisao.projeto.show",['revisaoId'=> $revisaoCadastrada->revisao_projeto_id]) }}'>
            <editar-revisao-etapas 
            :url="'{{ url('/') }}'"
            :dados-projeto="{{json_encode($dadosProjeto)}}"
            :dados-projeto-revisao="{{json_encode($dadosProjetoRevisao)}}"
            :dados-etapas="{{json_encode($dadosEtapas)}}"
            :dados-etapas-revisao="{{json_encode($dadosEtapasRevisao)}}"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :revisao-cadastrada="{{json_encode($revisaoCadastrada)}}"
            :view-projetos-revisao="{{json_encode($viewProjetosRevisao)}}"
            >

            <div class="row">
                <button type="button" class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#addEtapa">
                Adicionar Etapa
                </button>
            </div>       
            
            </editar-revisao-etapas>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>


@foreach ($dadosEtapasRevisao as $dados)
<div class="modal fade" id="{{$dados->etapa_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Informações da Etapa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form role="form" method="POST" action='{{ route("plancidades.revisao.etapas.projeto.atualizar", ['revisaoId'=>$revisaoCadastrada->revisao_projeto_id]) }}'>                    
            @csrf
            <input id='id' name='id'  class="br-input" type="hidden" value="{{$dados->etapa_id}}"></input>
            <input id='projeto_id' name='projeto_id'  class="br-input" type="hidden" value="{{$dados->projeto_id}}"></input>
            <div class="modal-body">
                <div class="card-body">         
                    <div class="row">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Nome da Etapa</span>
                            <br>
                            <span>{{ $dados->dsc_etapa }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12 br-textarea">
                            <span class="fw-bold">Novo Nome da Etapa</span>
                            <br>
                            <textarea id='dsc_etapa' name='dsc_etapa' class="br-textarea" type="text" rows='2' required></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Marco de Conclusão da Etapa</span>
                            <br>
                            <span>{{ $dados->dsc_marco }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12 br-textarea">
                            <span class="fw-bold">Novo Marco de Conclusão da Etapa</span>
                            <br>
                            <textarea id='dsc_marco' name='dsc_marco' class="br-textarea" type="text" rows='1' required></textarea>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Peso da Etapa</span>
                            <br>
                            <span>{{ $dados->vlr_peso_etapa }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12 br-textarea">
                            <span class="fw-bold">Novo Peso da Etapa</span>
                            <br>
                            <input id="vlr_peso_etapa" 
                                type="number" 
                                name="vlr_peso_etapa"
                                step="0.01"
                                required>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Data de Início da Etapa</span>
                            <br>
                            <span>{{date('d/m/Y', strtotime($dados->dte_previsao_inicio_etapa)) }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12 br-textarea">
                            <span class="fw-bold">Nova Data de Início da Etapa</span>
                            <br>
                            <input id='dte_previsao_inicio_etapa' name='dte_previsao_inicio_etapa'  class="br-input" type="date" required></input>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Data de Conclusão da Etapa</span>
                            <br>
                            <span>{{date('d/m/Y', strtotime($dados->dte_previsao_conclusao_etapa)) }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12 br-textarea">
                            <span class="fw-bold">Nova Data de Conclusão da Etapa</span>
                            <br>
                            <input id='dte_previsao_conclusao_etapa' name='dte_previsao_conclusao_etapa'  class="br-input" type="date" required></input>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Situação da Etapa</span>
                            <br>
                            <span>{{ $dados->situacao->txt_nome_situacao }}</span>
                        </div>
                    
                        <div class="column col-6 col-xs-12">
                            <span class="fw-bold">Nova Situação da Etapa</span>
                            <select
                                id="situacao_etapa_projeto_id"
                                class="form-select br-select"
                                name="situacao_etapa_projeto_id"
                                required>
                                    <option value="">Escolha um item</option>
                                    @foreach ($situacaoEtapas as $situacao)
                                        <option value="{{$situacao->id}}"  {{($dados->situacao_etapa_projeto_id == $situacao->id) ? 'selected': ''}}>{{$situacao->nome}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <span class="mr-3">Para garantir que a etapa seja revisada, clique no botão Salvar</span>
                <button class="br-button primary mr-3" type="submit" name="Salvar Edição" >Salvar</button>
                <button type="button" class="br-button danger mr-3" data-bs-dismiss="modal">Fechar</button>
            
            </div>
        </form>
      </div>
    </div>
  </div>  
@endforeach

<!-- Modal Inclusão Etapa-->
<div class="modal fade" id="addEtapa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form role="form" method="POST" action='{{ route("plancidades.revisao.etapas.projeto.adicionar", ['revisaoId'=>$revisaoCadastrada->revisao_projeto_id]) }}'>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Nova Etapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="projeto_id" name="projeto_id" value="{{$dadosProjeto->projeto_id}}">
                    <input type="hidden" id="etapa_id" name="etapa_id" value="{{$dadosProjeto->projeto_id}}nET{{$viewProjetosRevisao->qtd_etapas+1}}">
                    <div class="card-body">         
                        <div class="row">
                            <span class="fw-bold">Nome da Nova Etapa</span>
                            <br>
                            <textarea id='dsc_etapa' name='dsc_etapa' class="br-textarea" type="text" rows='2' required></textarea>
                        </div>
                        
                        <div class="row mt-3">
                            <span class="fw-bold">Marco de Conclusão da Nova Etapa</span>
                            <br>
                            <textarea id='dsc_marco' name='dsc_marco' class="br-textarea" type="text" rows='1' required></textarea>
                        </div>

                        <div class="row mt-3">
                            <span class="fw-bold">Peso da Nova Etapa</span>
                            <br>
                            <input id="vlr_peso_etapa" 
                                type="number" 
                                name="vlr_peso_etapa"
                                step="0.01"
                                required>
                        </div>
                        
                        <div class="row mt-3 text-center">
                            <div class="column col-6 col-xs-12">
                                <span class="fw-bold">Data de Início da Nova Etapa</span>
                                <br>
                                <input id='dte_previsao_inicio_etapa' name='dte_previsao_inicio_etapa'  class="br-input" type="date" required></input>
                            </div>

                            <div class="column col-6 col-xs-12">
                                <span class="fw-bold">Data de Conclusão da Nova Etapa</span>
                                <br>
                                <input id='dte_previsao_conclusao_etapa' name='dte_previsao_conclusao_etapa'  class="br-input" type="date" required></input>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <span class="fw-bold">Situação da Nova Etapa</span>
                            <select
                                id="situacao_etapa_projeto_id"
                                class="form-select br-select"
                                name="situacao_etapa_projeto_id"
                                required>
                                    <option value="">Escolha um item</option>
                                    @foreach ($situacaoEtapas as $situacao)
                                        <option value="{{$situacao->id}}"  {{($dados->situacao_etapa_projeto_id == $situacao->id) ? 'selected': ''}}>{{$situacao->nome}}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="mr-3">Para garantir que a etapa seja criada, clique no botão Salvar</span>
                    <button class="br-button primary mr-3" type="submit" name="botao_salvar" :value="true" >Salvar
                    </button>
                    <button class="br-button danger mr-3" type="button" data-bs-dismiss="modal">Fechar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($dadosEtapasRevisao as $dados)
<!-- Modal Exclusão Etapa-->
<div class="modal fade" id="excluirEtapa{{$dados->etapa_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form role="form" method="POST" action='{{ route("plancidades.revisao.etapas.projeto.excluir", ['revisaoId'=>$revisaoCadastrada->revisao_projeto_id]) }}'>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Excluir Etapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="etapa_id" name="etapa_id" value="{{$dados->etapa_id}}">
                    <div class="card-body">         
                        <div class="row mt-3">
                            <span class="fw-bold">Nome da Etapa</span>
                            <br>
                            <span>{{ $dados->dsc_etapa }}</span>
                        </div>
                
                        <div class="row mt-3">
                            <span class="fw-bold">Marco de Conclusão da Etapa</span>
                            <br>
                            <span>{{ $dados->dsc_marco }}</span>
                        </div>

                        <div class="row mt-3">
                            <span class="fw-bold">Peso da Etapa</span>
                            <br>
                            <span>{{ $dados->vlr_peso_etapa }}</span>
                        </div>
                    
                        <div class="row mt-3">
                            <span class="fw-bold">Data de Início da Etapa</span>
                            <br>
                            <span>{{date('d/m/Y', strtotime($dados->dte_previsao_inicio_etapa)) }}</span>
                        </div>
                    
                        <div class="row mt-3">
                            <span class="fw-bold">Data de Conclusão da Etapa</span>
                            <br>
                            <span>{{date('d/m/Y', strtotime($dados->dte_previsao_conclusao_etapa)) }}</span>
                        </div>
                    
                        <div class="row mt-3">
                            <span class="fw-bold">Situação da Etapa</span>
                            <br>
                            <span>{{ $dados->situacao->txt_nome_situacao }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="fw-bold">Deseja excluir a etapa?</span>
                    <span class="text">(Essa ação não poderá ser desfeita)</span>
                    <button class="br-button danger mr-3" type="submit" name="excluir" :value="true" >Excluir Etapa
                    </button>
                    <button class="br-button primary mr-3" type="button" data-bs-dismiss="modal">Fechar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection