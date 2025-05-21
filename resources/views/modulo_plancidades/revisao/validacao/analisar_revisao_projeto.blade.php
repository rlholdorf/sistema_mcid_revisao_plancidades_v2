@extends('layouts.app')

@section('scriptscss')
<link rel="stylesheet" type="text/css" href="{{URL::asset('css/custom.css')}}"  media="screen" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/relatorio_executivo.css') }}"  media="screen" > 

@endsection

@section('content')


    <historico-navegacao :url="'{{ url('/home') }}'" 
        :telanterior03="'Lista de Revisões de Projetos'"
        :link3="'{{url('/plancidades/revisao/validacao/projeto/listarRevisoes')}}'"
        :telanterior02="'Consultar Revisões Preenchidas'"
        :link2="'{{url('/plancidades/revisao/validacao/consulta')}}'"
        :telanterior01="'PlanCidades'" 
        :link1="'{{url('/plancidades')}}'"
        :telatual="'Analisar Revisao Projeto'">

    </historico-navegacao>


    <div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

        <cabecalho-relatorios :botaoEditar='false' titulo="{{$dadosProjeto->projeto_id}} - {{$dadosProjeto->txt_enunciado_projeto}}"
            :linkcompartilhar="'{{ url("/") }}'"
            :barracompartilhar="false">
        </cabecalho-relatorios>
        <p>
           Nesta página, você poderá visualizar todos os detalhes da revisão. Leia atentamente as informações prestadas e, em caso de dúvidas, contate o preenchedor.
           <br>
           Ao final da página, selecione a nova situação da revisão após análise, bem como registre eventuais necessidades de ajuste antes de salvar.
        </p>
        <hr>
        <form role="form" method="POST" action='{{ route("plancidades.revisao.validacao.projetos.atualizar",['revisaoProjetoid'=> $dadosRevisao->id]) }}'>
            @csrf
            <altera-situacao-revisao-projeto 
            :url="'{{ url('/') }}'"
            :dados-revisao="{{json_encode($dadosRevisao)}}"
            :dados-projeto="{{json_encode($dadosProjeto)}}"
            :dados-projeto-revisao="{{json_encode($dadosProjetoRevisao)}}"
            :dados-etapas-revisao="{{json_encode($dadosEtapasRevisao)}}"
            :revisoes="{{json_encode($revisoes)}}"
            :usuario-preenchimento="{{json_encode($usuarioPreenchimento)}}"
                >
            </altera-situacao-revisao-projeto>
            <span class="br-divider sm my-3"></span>
        </form>
    </div>
@endsection