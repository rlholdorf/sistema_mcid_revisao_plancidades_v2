@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior02="'Consultar Indicadores para Revisão'"
        :link2="'{{url('/plancidades/revisao/objetivo_estrategico/consulta')}}'"
        :telanterior01="'PlanCidades'" 
        :link1="'{{url('/plancidades')}}'"
        :telatual="'Revisar Indicador'">

    </historico-navegacao>


    <div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

        <cabecalho-relatorios :botaoEditar='false' titulo="{{$dadosIndicador->id}} - {{$dadosIndicador->txt_denominacao_indicador}}"
            :linkcompartilhar="'{{ url("/") }}'"
            :barracompartilhar="false">
        </cabecalho-relatorios>
        <p>
           Nesta página, você poderá visualizar as informações de regionalização da meta do indicador.
           <br>
           Ao lado de cada atributo, existe um campo para informar alterações que devam ser feitas na regionalização.
           <br>
           Ao final da página, clique em Avançar para salvar as alterações e continuar a revisão.
        </p>
        
        <hr>

        <form role="form" method="POST" action='{{ route("plancidades.revisao.regionalizacao.objetivoEstrategico.salvar",['revisaoId'=> $revisaoCadastrada->revisao_indicador_id]) }}'>
            @csrf
            <criar-revisao-regionalizacao-meta-indicador 
            :url="'{{ url('/') }}'"
            :dados-indicador="{{json_encode($dadosIndicador)}}"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :dados-regionalizacao="{{json_encode($dadosRegionalizacao)}}"
            :revisao-cadastrada="{{json_encode($revisaoCadastrada)}}"
            v-bind:dados-meta-revisao="{{json_encode($dadosMetaRevisao)}}"
            >
            </criar-revisao-regionalizacao-meta-indicador>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>
@endsection