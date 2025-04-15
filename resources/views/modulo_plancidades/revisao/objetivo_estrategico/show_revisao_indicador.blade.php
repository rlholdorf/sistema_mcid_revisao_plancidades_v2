@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior03="'Indicador {{$dadosIndicador->indicador_objetivo_estrategico_id}}'"
        :link3="'{{url('/plancidades/revisao/objetivo_estrategico/listar/'.$dadosIndicador->indicador_objetivo_estrategico_id)}}'"
        :telanterior02="'Consultar Indicadores para Revisão'"
        :link2="'{{url('/plancidades/revisao/objetivo_estrategico/consulta')}}'"
        :telanterior01="'PlanCidades'" 
        :link1="'{{url('/plancidades')}}'"
        :telatual="'Revisar Indicador de Objetivo Estratégico'">

    </historico-navegacao>


    <div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

        <cabecalho-relatorios :botaoEditar='false' titulo="{{$dadosIndicador->id}} - {{$dadosIndicador->txt_denominacao_indicador}}"
            :linkcompartilhar="'{{ url("/") }}'"
            :barracompartilhar="false">
        </cabecalho-relatorios>
        <p>
        Nesta página, você poderá visualizar todos os detalhes da revisão.
        </p>
        
        <hr>

        <show-finalizar-revisao-indicador 
        :url="'{{ url('/') }}'"
        :dados-revisao="{{json_encode($dadosRevisao)}}"
        :dados-indicador="{{json_encode($dadosIndicador)}}"
        :dados-indicador-revisao="{{json_encode($dadosIndicadorRevisao)}}"
        :dados-meta-revisao="{{json_encode($dadosMetaRevisao)}}"
        :dados-regionalizacao-revisao="{{json_encode($dadosRegionalizacaoRevisao)}}"
        :dados-regionalizacao="{{json_encode($dadosRegionalizacao)}}"
        :situacao-revisao="{{json_encode($situacaoRevisao)}}"
        >
        </show-finalizar-revisao-indicador>
        <span class="br-divider sm my-3"></span>
    </div>
@endsection