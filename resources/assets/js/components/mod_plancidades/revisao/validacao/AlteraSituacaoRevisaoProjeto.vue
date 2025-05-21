<template>
    <div>
        
        <div class="form-group">

            <p class="text-center"><b>Informações da Revisão</b></p>
            <div class="row mt-3">
                <div class="column col-xs-12 col-sm-3">
                    <label>Ano da Revisão</label>
                    <p v-text="dadosRevisao.num_ano_periodo_revisao"></p>
                </div>

                <div class="column col-xs-12 col-sm-3">
                    <label>Periodo da Revisão</label>
                    <p v-text="dadosRevisao.periodo_revisao.dsc_periodo_monitoramento"></p>
                </div>

                <div class="column col-xs-12 col-sm-3">
                    <label>Situação da Revisão</label>
                    <p v-text="revisoes.txt_situacao_revisao"></p>
                </div>

                <div class="column col-xs-12 col-sm-12">
                    <label for="usuarioRevisao">Responsável pela Revisão</label>
                    <p v-text="usuarioPreenchimento.name"></p>
                </div>
            </div><!-- div row -->

            <hr>

            <p class="text-center"><b>Detalhamento do Projeto</b></p>
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_enunciado_projeto">Enunciado do Projeto</label>
                    <p v-text="dadosProjeto.txt_enunciado_projeto"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo Enunciado do Projeto</label>
                        <textarea disabled class="input-medium" id="txt_enunciado_projeto_nova" name="txt_enunciado_projeto_nova" rows="2"
                        v-model="dadosProjetoRevisao.txt_enunciado_projeto">
                        </textarea>
                </div>
            </div>

             <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_objetivo_projeto">Objetivo do Projeto</label>
                    <p v-text="dadosProjeto.dsc_objetivo_projeto"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo Objetivo do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_objetivo_projeto_nova" name="dsc_objetivo_projeto_nova" rows="5"
                    v-model="dadosProjetoRevisao.dsc_objetivo_projeto">
                    </textarea>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_titulo_objetivo_estrategico_pei">Objetivo Estratégico Vinculado</label>
                    <p v-text="dadosProjeto.txt_titulo_objetivo_estrategico_pei"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo Objetivo Estratégico Vinculado</label>
                    <select id="objetivo_estrategico_pei_id_nova" class="form-select br-select" name="objetivo_estrategico_pei_id_nova"
                    v-model="dadosProjetoRevisao.objetivo_estrategico_pei_id" disabled>
                        <option value="" v-text="textoEscolhaObjetivoEstrategico"></option>
                        <option v-for="item in objetivosEstrategicos" v-text="item.txt_titulo_objetivo_estrategico_pei" :value="item.id" 
                        :key="item.id"></option>
                    </select> 
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_unidade_responsavel">Unidade Responsável</label>
                    <p v-text="dadosProjeto.txt_unidade_responsavel"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Unidade Responsável</label>
                    <select id="unidade_responsavel_id_nova" class="form-select br-select" name="unidade_responsavel_id_nova"
                    v-model="dadosProjetoRevisao.unidade_responsavel_id" disabled>
                        <option value="" v-text="textoEscolhaUnidadeResponsavel"></option>
                        <option v-for="item in unidadesResponsaveis" v-text="item.txt_unidade_responsavel" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="bln_ppa">É Medida Institucional e Normativa do PPA?</label>
                    <p v-text="dadosProjeto.bln_ppa ? 'Sim': 'Não'"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova identificação de Medida Institucional e Normativa do PPA</label>
                    <select id="bln_ppa_nova" class="form-select br-select" name="bln_ppa_nova"
                    v-model="dadosProjetoRevisao.bln_ppa" disabled>
                        <option value="">Selecione se o projeto é ou não Medida Institucional e Normativa do PPA</option>
                        <option value="true">Sim</option>
                        <option value="false">Não</option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_beneficios">Benefícios do Projeto</label>
                    <p v-text="dadosProjeto.dsc_beneficios"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Descrição de Benefícios do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_beneficios_nova" name="dsc_beneficios_nova" rows="5"
                    v-model="dadosProjetoRevisao.dsc_beneficios">
                    </textarea>
                </div>
            </div>            

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_premissas">Premissas do Projeto</label>
                    <p v-text="dadosProjeto.dsc_premissas"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Descrição de Premissas do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_premissas_nova" name="dsc_premissas_nova" rows="2"
                    v-model="dadosProjetoRevisao.dsc_premissas">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_restricoes">Restrições do Projeto</label>
                    <p v-text="dadosProjeto.dsc_restricoes"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Descrição de Restrições do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_restricoes_nova" name="dsc_restricoes_nova" rows="2"
                    v-model="dadosProjetoRevisao.dsc_restricoes">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label>Patrocinador(a) do Projeto</label>
                    <p class="mt-2" v-text="'Nome: '+dadosProjeto.dsc_nome_patrocinador"></p>
                    <p class="mt-4" v-text="'Cargo: '+dadosProjeto.dsc_cargo"></p>
                    <p class="mt-3" v-text="'Unidade: '+dadosProjeto.txt_unidade_responsavel_patrocinador"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo(a) Patrocinador(a) do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_nome_patrocinador_nova" name="dsc_nome_patrocinador_nova" placeholder="Escreva o nome do novo Patrocinador..." rows="1"
                    v-model="dadosProjetoRevisao.dsc_nome_patrocinador"></textarea>
                    <select disabled id="cargo_patrocinador_id_nova" class="form-select br-select mb-1" name="cargo_patrocinador_id_nova"
                    v-model="dadosProjetoRevisao.cargo_patrocinador_id">
                        <option value="" v-text="textoEscolhaCargoPatrocinador"></option>
                        <option v-for="item in cargos" v-text="item.dsc_cargo" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                    <select disabled id="unidade_patrocinador_id_nova" class="form-select br-select mb-1" name="unidade_patrocinador_id_nova"
                    v-model="dadosProjetoRevisao.unidade_patrocinador_id">
                        <option value="" v-text="textoEscolhaUnidadePatrocinador"></option>
                        <option v-for="item in unidadesResponsaveis" v-text="item.txt_unidade_responsavel" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label>Gerente(a) do Projeto</label>
                    <p class="mt-2" v-text="'Nome: '+dadosProjeto.dsc_nome_gerente"></p>
                    <p class="mt-4" v-text="'Cargo: '+dadosProjeto.dsc_cargo_gerente"></p>
                    <p class="mt-3" v-text="'Unidade: '+dadosProjeto.txt_unidade_responsavel_gerente"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo(a) Gerente(a) do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_nome_gerente_nova" name="dsc_nome_gerente_nova" placeholder="Escreva o nome do novo Gerente..." rows="1"
                    v-model="dadosProjetoRevisao.dsc_nome_gerente"></textarea>
                    <select disabled id="cargo_gerente_id_nova" class="form-select br-select mb-1" name="cargo_gerente_id_nova"
                    v-model="dadosProjetoRevisao.cargo_gerente_id">
                        <option value="" v-text="textoEscolhaCargoGerente"></option>
                        <option v-for="item in cargos" v-text="item.dsc_cargo" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                    <select disabled id="unidade_gerente_id_nova" class="form-select br-select mb-1" name="unidade_gerente_id_nova"
                    v-model="dadosProjetoRevisao.unidade_gerente_id">
                        <option value="" v-text="textoEscolhaUnidadeGerente"></option>
                        <option v-for="item in unidadesResponsaveis" v-text="item.txt_unidade_responsavel" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label>Patrocinador(a) Substituto do Projeto</label>
                    <p class="mt-2" v-text="dadosProjeto.dsc_nome_patrocinador_substututo ? 'Nome: '+dadosProjeto.dsc_nome_patrocinador_substututo : 'Sem patrocinador substituto definido'"></p>
                    <p class="mt-4" v-text="dadosProjeto.dsc_cargo_substututo ? 'Cargo: '+dadosProjeto.dsc_cargo_substututo : '' "></p>
                    <p class="mt-3" v-text="dadosProjeto.txt_unidade_responsavel_patrocinador_substututo ? 'Unidade: '+dadosProjeto.txt_unidade_responsavel_patrocinador_substututo : ''"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo(a) Patrocinador(a) Substituto do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_nome_patrocinador_substituto_nova" name="dsc_nome_patrocinador_substituto_nova" placeholder="Escreva o nome do novo Patrocinador Substituto..." rows="1"
                    v-model="dadosProjetoRevisao.dsc_nome_patrocinador_substituto"></textarea>
                    <select disabled id="cargo_patrocinador_substituto_id_nova" class="form-select br-select mb-1" name="cargo_patrocinador_substituto_id_nova"
                    v-model="dadosProjetoRevisao.cargo_patrocinador_substituto_id">
                        <option value="" v-text="textoEscolhaCargoPatrocinadorSubstituto"></option>
                        <option v-for="item in cargos" v-text="item.dsc_cargo" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                    <select disabled id="unidade_patrocinador_substituto_id_nova" class="form-select br-select mb-1" name="unidade_patrocinador_substituto_id_nova"
                    v-model="dadosProjetoRevisao.unidade_patrocinador_substituto_id">
                        <option value="" v-text="textoEscolhaUnidadePatrocinadorSubstituto"></option>
                        <option v-for="item in unidadesResponsaveis" v-text="item.txt_unidade_responsavel" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label>Gerente(a) Substituto do Projeto</label>
                    <p class="mt-2" v-text="dadosProjeto.dsc_nome_gerente_substituto ? 'Nome: '+dadosProjeto.dsc_nome_gerente_substituto : 'Sem gerente substituto definido'"></p>
                    <p class="mt-4" v-text="dadosProjeto.dsc_cargo_gerente_substututo ? 'Cargo: '+dadosProjeto.dsc_cargo_gerente_substututo : ''"></p>
                    <p class="mt-3" v-text="dadosProjeto.txt_unidade_responsavel_gerente_substututo ? 'Unidade: '+dadosProjeto.txt_unidade_responsavel_gerente_substututo : ''"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo(a) Gerente(a) Substituto do Projeto</label>
                    <textarea disabled class="input-medium" id="dsc_nome_gerente_substituto_nova" name="dsc_nome_gerente_substituto_nova" placeholder="Escreva o nome do novo Gerente Substituto..." rows="1"
                    v-model="dadosProjetoRevisao.dsc_nome_gerente_substituto"></textarea>
                    <select disabled id="cargo_gerente_substituto_id_nova" class="form-select br-select mb-1" name="cargo_gerente_substituto_id_nova"
                    v-model="dadosProjetoRevisao.cargo_gerente_substituto_id">
                        <option value="" v-text="textoEscolhaCargoGerenteSubstituto"></option>
                        <option v-for="item in cargos" v-text="item.dsc_cargo" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                    <select disabled id="unidade_gerente_substituto_id_nova" class="form-select br-select mb-1" name="unidade_gerente_substituto_id_nova"
                    v-model="dadosProjetoRevisao.unidade_gerente_substituto_id">
                        <option value="" v-text="textoEscolhaUnidadeGerenteSubstituto"></option>
                        <option v-for="item in unidadesResponsaveis" v-text="item.txt_unidade_responsavel" :value="item.id" 
                        :key="item.id"></option>
                    </select>
                </div>
            </div>

            <hr>

            <p class="text-center"><b>Detalhamento das Etapas do Projeto</b></p>

            <div class="table-responsive mt-3">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome da etapa (Produto)</th>
                            <th>Marco da entrega</th>
                            <th class="text-center">Peso relativo</th>
                            <th class="text-center">Data de Início</th>
                            <th class="text-center">Data de Conclusão</th>
                            <th class="text-center">Situação da Etapa</th>
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
                        </tr>
                    </tbody>
                </table>
            </div>            

            <!-- Botões Formulário -->
            <div class="row">
                <div class="titulo">
                    <h3>Análise da Revisão</h3> 
                </div>
                <div class="column col-xs-12 col-sm-6">
                    <label for="situacao_revisao">Situação Atual da Revisão</label>
                    <p v-text="revisoes.txt_situacao_revisao"></p>
                </div>
                <div class="column col-xs-12 col-sm-6">
                    <label for="situacao_revisao_id">Situação da Revisão após Análise</label>
                    <select id="situacao_revisao_id"
                        @change="situacaoRevisao" class="form-select br-select" name="situacao_revisao_id" required>
                        <option value="" v-text="textoEscolhaSituacao"></option>
                        <option v-for="item in situacoesRevisao" v-text="item.txt_situacao_revisao" :value="item.id"
                        :key="item.id"></option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col col-xs-12 br-textarea">
                    <label for="observacao_revisao">Observações de Análise da Revisão</label>
                    <textarea class="input-medium" id="txt_observacao" name="txt_observacao"
                            rows="5" v-model="revisoes.txt_observacao" required>
                    </textarea>
                </div>
            </div>

            <div class="row">
                <div  class="col col-xs-12 col-sm-6">                    
                </div>
                
                <div class="col col-xs-12 col-sm-6">
                    <div class="p-3 text-right">
                        <button class="br-button primary mr-3" type="submit" name="botao_salvar" :value="true">Salvar
                        </button>
                        
                        <button class="br-button danger mr-3" type="button"
                        onclick="javascript:window.history.go(-1)">Voltar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['url', 'dadosProjeto', 'dadosRevisao', 'dadosProjetoRevisao', 'dadosEtapas', 'dadosEtapasRevisao', 'revisoes', 'usuarioPreenchimento'],
    data() {
        return {
        //----Campos Select
            cargos:'',
            cargo:'',
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
            textoEscolhaCargoPatrocinador:"Escolha o cargo do novo Patrocinador...",
            textoEscolhaUnidadePatrocinador:"Escolha a Unidade do novo Patrocinador...",
            textoEscolhaCargoGerente:"Escolha o cargo do novo Gerente...",
            textoEscolhaUnidadeGerente:"Escolha a Unidade do novo Gerente...",
            textoEscolhaCargoPatrocinadorSubstituto:"Escolha o cargo do novo Patrocinador Substituto...",
            textoEscolhaUnidadePatrocinadorSubstituto:"Escolha a Unidade do novo Patrocinador Substituto...",
            textoEscolhaCargoGerenteSubstituto:"Escolha o cargo do novo Gerente Substituto...",
            textoEscolhaUnidadeGerenteSubstituto:"Escolha a Unidade do novo Gerente Substituto...",

            //----Validação Revisão
            txt_situacao_revisao: '',
            situacao_revisao_observacao: '',
            situacao_revisao_id: '',

            situacoesRevisao:'',
            situacaoRevisao:'',
            textoEscolhaSituacao: "Escolha a nova situação da Revisão",
            
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

        IrParaPagina(destino){
            console.log('entrou');
            window.location.href = this.url + destino;
        },

        IrParaFinalizacao(revisaoId){
            window.location.href = "/plancidades/revisao/projeto/finalizar/"+revisaoId;
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

        axios.get(this.url + '/api/plancidades/cargos').then(resposta=>{
            this.cargos = resposta.data;
        }).catch(error=>{
            console.log(error);
        });      
        
        axios.get(this.url + '/api/plancidades/situacao_revisao').then(resposta=>{
            this.situacoesRevisao = resposta.data;
            }).catch(error=>{
            console.log(error);
        });
    }
}
</script>
