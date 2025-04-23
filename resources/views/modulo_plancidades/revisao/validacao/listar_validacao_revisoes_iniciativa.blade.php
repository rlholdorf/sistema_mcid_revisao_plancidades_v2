@extends('layouts.app')

@section('content')
<historico-navegacao :url="'{{ url('/home') }}'" 
    :telanterior02="'Consultar Revisões Preenchidas'"
    :link2="'{{url('/plancidades/revisao/validacao/consulta')}}'"
    :telanterior01="'PlanCidades'" 
    :link1="'{{url('/plancidades')}}'"
    :telatual="'Lista de Revisões de Iniciativas'">
</historico-navegacao>


<div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content">

    <cabecalho-relatorios :titulo="'Lista de Revisões de Iniciativas'" :linkcompartilhar="'{{ url('/') }}'" :barracompartilhar="false">
    </cabecalho-relatorios>


    @if(count($revisoes) > 0)
        <div class="form-group row">
            <div class="col col-xs-12 col-sm-12">

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="text-center">
                            <tr class="text-center ">
                                <th>ID</th>
                                <th>Objetivo Estratégico</th>
                                <th class="text-center">Unidade Responsável</th>
                                <th>Denominação Iniciativa</th>
                                <th class="text-center">Período de Revisão</th>
                                <th class="text-center">Data Cadastro</th>
                                <th class="text-center">Situação da Revisão</th>
                                <th class="text-center acao">Exibir Revisão</th>
                                <th class="text-center acao">Alterar Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($revisoes as $revisao)
                                <tr class="conteudoTabela">
                                    <td>{{ $revisao->revisao_iniciativa_id}}</td>
                                    <td>{{ $revisao->txt_titulo_objetivo_estrategico_pei}}</td>
                                    <td class="text-center">{{ $revisao->txt_sigla_orgao}}</td>
                                    <td>{{ $revisao->txt_enunciado_iniciativa}}</td>
                                    <td class="text-center">{{($revisao->dsc_periodo_monitoramento != null ) ? ($revisao->dsc_periodo_monitoramento)."/".($revisao->num_ano_periodo_revisao) : 'Não revisado'}}</td>
                                    <td class="text-center">{{($revisao->created_at) ? date('d/m/Y',strtotime($revisao->created_at)) : ''}}</td>
                                    <td class="text-center">{{$revisao->txt_situacao_revisao}}</td>
                                    <td class="text-center acao"><a class="br-button circle primary small"
                                        href='{{ route("plancidades.revisao.iniciativa.show", ['revisaoId' => $revisao->revisao_iniciativa_id]) }}' target="_blank">
                                        <i class="fas fa-eye"></i></a></td>
                                    <td class="text-center acao"><a class="br-button circle primary small"
                                        href='{{ route("plancidades.revisao.validacao.iniciativas.editar", ['revisaoIniciativaid' => $revisao->revisao_iniciativa_id]) }}'>
                                        <i class="fas fa-pen"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div>
            <p class="text-center"><strong>Não existem revisões a serem analisadas</strong></p>
        </div>
    @endif

    
    <div class="p-3 text-right">
        <button class="br-button primary mr-3" type="button" name="Imprimir" onclick="window.print();">Imprimir
        </button>

        <a class="br-button danger mr-3" href="{{url('plancidades/revisao/validacao/consulta')}}">Voltar
        </a>
    </div>

</div>
@endsection