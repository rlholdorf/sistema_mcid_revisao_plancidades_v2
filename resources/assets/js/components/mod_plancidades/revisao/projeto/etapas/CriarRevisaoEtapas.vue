<template>
    <div>
        
        <progresso-revisao-projeto
        :url="url"
        :dados-revisao="dadosRevisao"
        :active="'etapas'">

        </progresso-revisao-projeto>

        <hr>

        <div class="form-group">

            <p class="text-center"><b>Detalhamento das Etapas Situação Atual</b></p>

            <div class="table-responsive mt-3">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome da etapa (Produto)</th>
                            <th>Marco da entrega</th>
                            <th class="text-center" style="border-left:1px">Peso relativo</th>
                            <th class="text-center" style="border-left:1px">Data de Início</th>
                            <th class="text-center">Data de Conclusão</th>
                            <th class="text-center" style="border-left:1px">Situação da Etapa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dadosEtapas">
                            <td>{{ (index+1) }}</td>
                            <td>{{ item.dsc_etapa }}</td>
                            <td>{{ item.dsc_marco }}</td>
                            <td class="text-center">{{ item.vlr_peso_etapa }}</td>
                            <td class="text-center">{{ item.dte_efetiva_inicio_etapa ? formatarData(item.dte_efetiva_inicio_etapa) : formatarData(item.dte_previsao_inicio_etapa) }}</td>
                            <td class="text-center">{{ item.dte_efetiva_conclusao_etapa ? formatarData(item.dte_efetiva_conclusao_etapa) : formatarData(item.dte_previsao_conclusao_etapa) }}</td>
                            <td class="text-center">{{ item.situacao ? item.situacao.txt_nome_situacao : 'Não Monitorado' }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

    <!-- Botões Formulário -->
            <div class="row">
                <div class="col col-xs-12 col-sm-12">
                    <div class="p-3 text-right">
                        <button class="br-button success mr-3" type="submit" name="avancar_sem_alteracao" :value="true">Avançar
                        </button>

                        <button class="br-button primary mr-3" type="submit" name="revisar_etapas" :value="true">Revisar Etapas
                        </button>

                        <a class="br-button danger mr-3" type="button" :href='this.url+"/plancidades/revisao/projeto/consulta"'>Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['url', 'dadosProjeto', 'dadosProjetoRevisao', 'dadosEtapas', 'revisaoCadastrada', 'dadosRevisao', 'dadosEtapasRevisao'],
    data() {
        return {
        //----Campos Select
            objetivosEstrategicos:'',
            objetivoEstrategico:'',        
            unidadesResponsaveis:'',
            unidadeResponsavel:'',
            periodicidades:'',
            periodicidade:'',
            polaridades:'',
            polaridade:'',

            //----Textos de Escolhas
            textoEscolhaObjetivoEstrategico: "Escolha um novo Objetivo Estratégico",
            textoEscolhaUnidadeResponsavel: "Escolha uma nova Unidade Responsável:",
            textoEscolhaPeriodicidade: "Escolha uma nova Periodicidade:",
            textoEscolhaPolaridade: "Escolha uma nova Polaridade:",
        }
    },
    methods: {
        formatarData(data){
            let dataTemp = data.split("-");
            let dataFormatada = '';
            
            for (let i = (dataTemp.length-1); i >= 0; i--){
                dataFormatada += String(dataTemp[i]) 
                if (i > 0){
                    dataFormatada += '/';
                }
            }
            return dataFormatada;
        },
        
    },
    mounted() {
        
    }
}
</script>
