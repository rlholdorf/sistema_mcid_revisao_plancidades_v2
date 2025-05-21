<template>
    <div>
        
        <progresso-revisao-projeto
        :url="url"
        :dados-revisao="dadosRevisao"
        :active="'etapas'">

        </progresso-revisao-projeto>

        <hr>

        <div class="form-group">

            <p class="text-center"><b>Detalhamento das Etapas - Situação Atual</b></p>

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

            <hr>

            <p class="text-center"><b>Detalhamento das Etapas - Revisada</b></p>

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
                            <th class="text-center" style="border-left:1px">Alterar</th>
                            <th class="text-center" style="border-left:1px">Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dadosEtapasRevisao">
                            <td>{{ (index+1) }}</td>
                            <td>{{ item.dsc_etapa }}</td>
                            <td>{{ item.dsc_marco }}</td>
                            <td class="text-center">{{ item.vlr_peso_etapa }}</td>
                            <td class="text-center">{{ formatarData(item.dte_previsao_inicio_etapa) }}</td>
                            <td class="text-center">{{ formatarData(item.dte_previsao_conclusao_etapa) }}</td>
                            <td class="text-center">{{ item.situacao.txt_nome_situacao }}</td>
                            <td class="text-center">
                                <button type="button" class="br-button circle primary small" data-bs-toggle="modal" :data-bs-target="'#'+item.etapa_id" data-toggle="tooltip" data-placement="top" title="Monitorar etapa" >
                                    <i class="fa fa-pen"></i> 
                                </button></td>
                            <td class="text-center">
                                <button type="button" class="br-button circle primary small" data-bs-toggle="modal" :data-bs-target="'#excluirEtapa'+item.etapa_id" data-toggle="tooltip" data-placement="top" title="Excluir etapa" >
                                    <i class="fa fa-times-circle"></i>
                                </button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Soma dos Pesos -->
            <div class="alert" :class="pesosSomamCem() ? 'alert-success' : 'alert-danger'" role="alert" style="margin-top: 20px;">
                Soma dos Pesos das Etapas Revisadas: <b>{{ somarPesosEtapasRevisao() }}</b>
                <span v-if="!pesosSomamCem()"> (A soma deve ser exatamente 100)</span>
            </div>

            <slot>
            
            </slot>

    <!-- Botões Formulário -->
            <div class="row">
                <div class="col col-xs-12 col-sm-12">
                    <div class="p-3 text-right">
                        <button class="br-button primary mr-3" type="submit" :disabled="!pesosSomamCem()">Salvar Revisão
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
    props: ['url', 'dadosProjeto', 'dadosProjetoRevisao', 'dadosEtapas', 'revisaoCadastrada', 'dadosRevisao', 'dadosEtapasRevisao', 'viewProjetosRevisao'],
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
        somarPesosEtapasRevisao() {
            // Garante que todos os valores são numéricos
            return this.dadosEtapasRevisao
                .reduce((total, item) => total + (parseFloat(item.vlr_peso_etapa) || 0), 0);
        },
        pesosSomamCem() {
            return this.somarPesosEtapasRevisao() === 100;
        }
    },
    mounted() {
        
        axios.get(this.url + '/api/plancidades/objetivosEstrategicos').then(resposta=>{
            this.objetivosEstrategicos = resposta.data;
        }).catch(error=>{
            console.log(error);
        });
        
        axios.get(this.url + '/api/plancidades/unidadesResponsaveis').then(resposta=>{
            this.unidadesResponsaveis = resposta.data;
        }).catch(error=>{
            console.log(error);
        });

        axios.get(this.url + '/api/plancidades/periodicidades').then(resposta=>{
            this.periodicidades = resposta.data;
        }).catch(error=>{
            console.log(error);
        });

        axios.get(this.url + '/api/plancidades/polaridades').then(resposta=>{
            this.polaridades = resposta.data;
        }).catch(error=>{
            console.log(error);
        });
    }
}
</script>
