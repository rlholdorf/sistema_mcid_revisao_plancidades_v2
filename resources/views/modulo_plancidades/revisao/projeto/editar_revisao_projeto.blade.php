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
           Nesta página, você poderá visualizar todas as informações do projeto - as etapas do projeto serão exibidas na próxima página.
           <br>
           Ao lado de cada atributo, existe um campo para informar alterações que devam ser feitas no projeto.
           <br>
           Ao final da página, clique em Salvar Revisão para salvar.
        </p>
        
        <hr>

        <form role="form" method="POST" action='{{ route("plancidades.revisao.projeto.atualizar",['revisaoId'=> $revisaoCadastrada->revisao_projeto_id]) }}'>
            @csrf
            <editar-revisao-projeto 
            :url="'{{ url('/') }}'"
            :dados-projeto="{{json_encode($dadosProjeto)}}"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :revisao-cadastrada="{{json_encode($revisaoCadastrada)}}"
            :dados-projeto-revisao="{{json_encode($dadosProjetoRevisao)}}"
            >
            </editar-revisao-projeto>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>
@endsection