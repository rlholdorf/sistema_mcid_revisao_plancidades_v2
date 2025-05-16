<template>
    <div>
        <div class="form-group">

            <p class="text-center"><b>Detalhamento das Etapas</b></p>

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
                            <th class="text-center" style="border-left:1px">Alterar</th>
                            <th class="text-center" style="border-left:1px">Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in dadosEtapas">
                            <td>{{ (index+1) }}</td>
                            <td>{{ item.dsc_etapa }}</td>
                            <td>{{ item.dsc_marco }}</td>
                            <td class="text-center">{{ item.vlr_peso_etapa }}</td>
                            <td class="text-center">{{ formatarData(item.dte_previsao_inicio_etapa) }}</td>
                            <td class="text-center">{{ formatarData(item.dte_previsao_conclusao_etapa) }}</td>
                            <td class="text-center">
                                <button type="button" class="br-button circle primary small" title="Monitorar etapa" >
                                    <i class="fa fa-pen"></i>  <!-- Implementar, provavelmente modal -->
                                </button></td>
                            <td class="text-center">
                                <button type="button" class="br-button circle primary small" title="Monitorar etapa" >
                                    <i class="fa fa-times-circle"></i> <!-- Implementar, provavelmente modal -->
                                </button></td>
                        </tr>
                    </tbody>
                </table>

                <slot>
            
                </slot>

            </div>

    <!-- Botões Formulário -->
            <div class="row">
                <div class="col col-xs-12 col-sm-12">
                    <div class="p-3 text-right">
                        <button class="br-button primary mr-3" type="submit" name="salvar_revisão" :value="true">Salvar Revisão
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
