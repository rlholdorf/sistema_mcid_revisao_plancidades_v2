@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior03="'Projeto {{$dadosProjeto->projeto_id}}'"
        :link3="'{{url('/plancidades/revisao/projeto/listar/'.$dadosProjeto->projeto_id)}}'"
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
            Nesta página, você poderá visualizar todos os detalhes da revisão.
        </p>
        
        <hr>

            <show-finalizar-revisao-projeto 
            :url="'{{ url('/') }}'"
            :dados-projeto="{{json_encode($dadosProjeto)}}"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :dados-projeto-revisao="{{json_encode($dadosProjetoRevisao)}}"
            :dados-etapas="{{json_encode($dadosEtapas)}}"
            :dados-etapas-revisao="{{json_encode($dadosEtapasRevisao)}}"
            :situacao-revisao="{{json_encode($situacaoRevisao)}}"
            >
            </show-finalizar-revisao-projeto>
            <span class="br-divider sm my-3"></span>

    </div>
@endsection