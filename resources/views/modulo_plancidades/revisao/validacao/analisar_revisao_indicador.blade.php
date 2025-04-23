@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior03="'Lista de Revisões de Indicadores'"
        :link3="'{{url('/plancidades/revisao/validacao/objetivo_estrategico/listarRevisoes')}}'"
        :telanterior02="'Consultar Revisões Preenchidas'"
        :link2="'{{url('/plancidades/revisao/validacao/consulta')}}'"
        :telanterior01="'PlanCidades'" 
        :link1="'{{url('/plancidades')}}'"
        :telatual="'Analisar Revisao Indicador'">

    </historico-navegacao>


    <div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

        <cabecalho-relatorios :botaoEditar='false' titulo="{{$dadosIndicador->id}} - {{$dadosIndicador->txt_denominacao_indicador}}"
            :linkcompartilhar="'{{ url("/") }}'"
            :barracompartilhar="false">
        </cabecalho-relatorios>
        <p>
           Nesta página, você poderá visualizar todos os detalhes da revisão. Leia atentamente as informações prestadas e, em caso de dúvidas, contate o preenchedor.
           <br>
           Ao final da página, selecione a nova situação da revisão após análise, bem como registre eventuais necessidades de ajuste antes de salvar.
        </p>
        <hr>
        <form role="form" method="POST" action='{{ route("plancidades.revisao.validacao.indicadores.atualizar",['revisaoIndicadorid'=> $dadosRevisao->id]) }}'>
            @csrf
            <altera-situacao-revisao-indicador 
            :url="'{{ url('/') }}'"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :dados-indicador="{{json_encode($dadosIndicador)}}"
            :dados-indicador-revisao="{{json_encode($dadosIndicadorRevisao)}}"
            :dados-meta-revisao="{{json_encode($dadosMetaRevisao)}}"
            :dados-regionalizacao-revisao="{{json_encode($dadosRegionalizacaoRevisao)}}"
            :dados-regionalizacao="{{json_encode($dadosRegionalizacao)}}"
            :revisoes="{{json_encode($revisoes)}}"
            :usuario-preenchimento="{{json_encode($usuarioPreenchimento)}}"
                >
            </altera-situacao-revisao-indicador>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>
@endsection