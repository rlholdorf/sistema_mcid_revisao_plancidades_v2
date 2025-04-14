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
           Nesta página, você poderá visualizar todas as informações do indicador, suas metas e regionalizações (caso houver).
           <br>
           Ao lado de cada atributo, existe um campo para informar alterações que devam ser feitas no indicador.
           <br>
           Ao final da página, clique em Salvar Revisão para salvar. Ao final, clique em Finalizar para enviar para análise.
        </p>
        
        <hr>

        <form role="form" method="POST" action='{{ route("plancidades.revisao.objetivoEstrategico.salvar",['revisaoId'=> $revisaoCadastrada->revisao_indicador_id]) }}'>
            @csrf
            <criar-revisao-indicador 
            :url="'{{ url('/') }}'"
            :dados-indicador="{{json_encode($dadosIndicador)}}"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :dados-meta-revisao="{{json_encode($dadosMetaRevisao)}}"
            :revisao-cadastrada="{{json_encode($revisaoCadastrada)}}"
            >
            </criar-revisao-indicador>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>
@endsection