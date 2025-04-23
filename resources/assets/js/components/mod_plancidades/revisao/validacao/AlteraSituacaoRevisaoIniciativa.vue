<template>
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
                    <label for="situacaoRevisao">Situação da Revisão</label>
                    <p v-text="revisoes.txt_situacao_revisao"></p>
                </div>

                <div class="column col-xs-12 col-sm-12">
                    <label for="usuarioRevisao">Responsável pela Revisão</label>
                    <p v-text="usuarioPreenchimento.name"></p>
                </div>
            </div><!-- div row -->

            <hr>

            <p class="text-center"><b>Detalhamento da Iniciativa</b></p>
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_enunciado_iniciativa">Denominação da Iniciativa</label>
                    <p v-text="dadosIniciativa.txt_enunciado_iniciativa"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Denominação da Iniciativa</label>
                        <textarea class="input-medium" id="txt_enunciado_iniciativa_nova" name="txt_enunciado_iniciativa_nova" rows="2" disabled
                            v-model="dadosIniciativaRevisao.txt_enunciado_iniciativa">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_iniciativa">Descrição da Iniciativa</label>
                    <p v-text="dadosIniciativa.dsc_iniciativa"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Descrição da Iniciativa</label>
                        <textarea class="input-medium" id="dsc_iniciativa_nova" name="dsc_iniciativa_nova" rows="5" disabled
                            v-model="dadosIniciativaRevisao.dsc_iniciativa">
                        </textarea>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="bln_pac">É entrega/iniciativa do PAC?</label>
                    <p v-text="dadosIniciativa.bln_pac ? 'Sim': 'Não'"></p>
                </div>
        
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo enquadramento de entrega/iniciativa do PAC</label>
                    <select id="bln_pac_nova" class="form-select br-select" name="bln_pac_nova" disabled v-model="dadosIniciativaRevisao.bln_pac">
                    <option value="">Selecione se a meta é ou não do PAC</option>
                    <option value="true">Sim</option>
                    <option value="false">Não</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3" >
                <div class="col col-xs-12 br-textarea">
                    <label for="txt_justificativa_revisao_iniciativa">Justificativa para as alterações da Entrega (Obrigatório)</label>
                    <textarea disabled class="input-medium" id="txt_justificativa_revisao_iniciativa" name="txt_justificativa_revisao_iniciativa" rows="2"
                    v-model="dadosIniciativaRevisao.txt_justificativa_revisao_iniciativa">
                    </textarea>
                    <p class="text-base mt-1">(Obs.: em caso de atualização decorrente de alteração orçamentária, informar ação(ões) orçamentária(s) - 
                    código e título - que financia(m) a meta e a justificativa)</p>
                </div>
            </div>

            <hr>

            <p class="text-center"><b>Detalhamento do Indicador da Iniciativa</b></p>
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_denominacao_indicador">Denominação do Indicador da Iniciativa</label>
                    <p v-text="dadosIniciativa.txt_denominacao_indicador"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Denominação do Indicador da Iniciativa</label>
                        <textarea class="input-medium" id="txt_denominacao_indicador_nova" name="txt_denominacao_indicador_nova" rows="1" disabled
                        v-model="dadosIndicadorRevisao.txt_denominacao_indicador">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_indicador">Descrição do Indicador da Iniciativa</label>
                    <p v-text="dadosIniciativa.dsc_indicador"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Descrição do Indicador da Iniciativa</label>
                        <textarea class="input-medium" id="dsc_indicador_nova" name="dsc_indicador_nova" rows="5" disabled
                        v-model="dadosIndicadorRevisao.dsc_indicador">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_sigla_indicador">Sigla do Indicador da Iniciativa</label>
                    <p v-text="dadosIniciativa.txt_sigla_indicador"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Sigla do Indicador da Iniciativa</label>
                        <textarea class="input-medium" id="txt_sigla_indicador_nova" name="txt_sigla_indicador_nova" rows="1" disabled
                        v-model="dadosIndicadorRevisao.txt_sigla_indicador">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="vlr_indice_referencia">Valor de Referência</label>
                    <p v-text="dadosIniciativa.vlr_indice_referencia"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo Valor de Referência</label>
                        <textarea class="input-medium" id="vlr_indice_referencia_nova" name="vlr_indice_referencia_nova" rows="1" disabled
                            v-model="dadosIndicadorRevisao.vlr_indice_referencia">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_unidade_medida">Unidade de Medida</label>
                    <p v-text="dadosIniciativa.txt_unidade_medida"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Unidade de Medida</label>
                        <select id="txt_unidade_medida_nova" class="form-select br-select" name="txt_unidade_medida_nova"
                            v-model="dadosIndicadorRevisao.unidade_medida_id" disabled>
                            <option value="" v-text="textoEscolhaUnidadeMedida"></option>
                            <option v-for="item in unidadesMedida" v-text="item.txt_unidade_medida" :value="item.id"
                                :key="item.id"></option>
                        </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_data_divulgacao_ou_disponibilizacao">Data de Divulgação ou Disponibilidade</label>
                    <p v-text="dadosIniciativa.txt_data_divulgacao_ou_disponibilizacao"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Novo Data de Divulgação ou Disponibilidade</label>
                        <textarea class="input-medium" id="txt_data_divulgacao_ou_disponibilizacao_nova" name="txt_data_divulgacao_ou_disponibilizacao_nova" rows="1" disabled
                        v-model="dadosIndicadorRevisao.txt_data_divulgacao_ou_disponibilizacao">
                        </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_periodicidades">Periodicidade</label>
                    <p v-text="dadosIniciativa.dsc_periodicidades"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Periodicidade</label>
                        <select id="periodicidade_id_nova" class="form-select br-select" name="periodicidade_id_nova" disabled
                        v-model="dadosIndicadorRevisao.periodicidade_id">
                            <option value="" v-text="textoEscolhaPeriodicidade"></option>
                            <option v-for="item in periodicidades" v-text="item.dsc_periodicidades" :value="item.id"
                                :key="item.id"></option>
                        </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_polaridade">Polaridade do Indicador</label>
                    <p v-text="dadosIniciativa.txt_polaridade"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Polaridade do Indicador</label>
                    <select id="polaridade_id_nova" class="form-select br-select" name="polaridade_id_nova"
                    v-model="dadosIndicadorRevisao.polaridade_id" disabled>
                        <option value="" v-text="textoEscolhaPolaridade"></option>
                        <option v-for="item in polaridades" v-text="item.txt_polaridade" :value="item.id"
                            :key="item.id"></option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_formula_calculo">Fórmula de Cálculo do Indicador</label>
                    <p v-text="dadosIniciativa.txt_formula_calculo ? dadosIniciativa.txt_formula_calculo : 'Verificar no Espelho do PPA'"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Fórmula de Cálculo do Indicador</label>
                    <textarea class="input-medium" id="txt_formula_calculo_nova" name="txt_formula_calculo_nova" rows="5" disabled v-model="dadosIndicadorRevisao.txt_formula_calculo">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_fonte_dados_variaveis_calculo">Fonte de Dados das Variáveis do Indicador</label>
                    <p v-text="dadosIniciativa.txt_fonte_dados_variaveis_calculo"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Fonte de Dados das Variáveis do Indicador</label>
                    <textarea class="input-medium" id="txt_fonte_dados_variaveis_calculo_nova" name="txt_fonte_dados_variaveis_calculo_nova" rows="5" disabled v-model="dadosIndicadorRevisao.txt_fonte_dados_variaveis_calculo">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_forma_disponibilizacao">Forma de Disponibilização do Indicador</label>
                    <p v-text="dadosIniciativa.txt_forma_disponibilizacao"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Forma de Disponibilização do Indicador</label>
                    <textarea class="input-medium" id="txt_forma_disponibilizacao_nova" name="txt_forma_disponibilizacao_nova" rows="5" disabled v-model="dadosIndicadorRevisao.txt_forma_disponibilizacao">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_procedimento_calculo">Procedimento de Cálculo do Indicador</label>
                    <p v-text="dadosIniciativa.dsc_procedimento_calculo"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Procedimento de Cálculo do Indicador</label>
                    <textarea class="input-medium" id="dsc_procedimento_calculo_nova" name="dsc_procedimento_calculo_nova" rows="5" disabled v-model="dadosIndicadorRevisao.dsc_procedimento_calculo">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3" >
                <div class="col col-xs-12 br-textarea">
                    <label for="txt_justificativa_revisao_indicador_iniciativa">Justificativa para as alterações do Indicador da Entrega (Obrigatório)</label>
                    <textarea disabled class="input-medium" id="txt_justificativa_revisao_indicador_iniciativa" name="txt_justificativa_revisao_indicador_iniciativa" rows="2"
                    v-model="dadosIndicadorRevisao.txt_justificativa_revisao_indicador_iniciativa">
                    </textarea>
                    <p class="text-base mt-1">(Obs.: em caso de atualização decorrente de alteração orçamentária, informar ação(ões) orçamentária(s) - 
                    código e título - que financia(m) a meta e a justificativa)</p>
                </div>
            </div>

            <hr>

            <p class="text-center"><b>Detalhamento da Meta do Indicador da Iniciativa</b></p>
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="txt_dsc_meta">Denominação da Meta</label>
                    <p v-text="dadosMeta.txt_dsc_meta"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Denominação da Meta</label>
                    <textarea class="input-medium" id="txt_dsc_meta_nova" name="txt_dsc_meta_nova" rows="2" disabled v-model="dadosMetaRevisao.txt_dsc_meta">
                    </textarea>
                </div>
            </div>
                        
            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="bln_meta_cumulativa">Cumulatividade da Meta</label>
                    <p v-text="dadosMeta.bln_meta_cumulativa ? 'Sim': 'Não'"></p>
                </div>
        
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Cumulatividade da Meta</label>
                    <select id="bln_meta_cumulativa_nova" class="form-select br-select" name="bln_meta_cumulativa_nova" disabled v-model="dadosMetaRevisao.bln_meta_cumulativa">
                    <option value="">Selecione se a meta é ou não cumulativa</option>
                    <option value="true">Sim</option>
                    <option value="false">Não</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="vlr_esperado_ano_2">Meta para 2025 {{this.formatarUnidadeMedida(dadosIniciativa.unidade_medida_id)}}</label>
                    <p v-text="dadosMeta.vlr_esperado_ano_2"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-input">
                    <label>Nova Meta para 2025 {{this.formatarUnidadeMedida(dadosIndicadorRevisao.unidade_medida_id)}}</label>
                    <br>
                    <input id="vlr_esperado_ano_2_nova"  disabled
                    type="number" 
                    name="vlr_esperado_ano_2_nova"
                    step="0.01"
                    v-model="dadosMetaRevisao.vlr_esperado_ano_2"
                    >
                </div>
            </div>    

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="vlr_esperado_ano_3">Meta para 2026 {{this.formatarUnidadeMedida(dadosIniciativa.unidade_medida_id)}}</label>
                    <p v-text="dadosMeta.vlr_esperado_ano_3"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-input">
                    <label>Nova Meta para 2026 {{this.formatarUnidadeMedida(dadosIndicadorRevisao.unidade_medida_id)}}</label>
                    <br>
                    <input id="vlr_esperado_ano_3_nova"  disabled
                    type="number" 
                    name="vlr_esperado_ano_3_nova"
                    step="0.01"
                    v-model="dadosMetaRevisao.vlr_esperado_ano_3"
                    >
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="vlr_meta_final_cenario_alternativo">Meta para 2027 {{this.formatarUnidadeMedida(dadosIniciativa.unidade_medida_id)}}</label>
                    <p v-text="dadosMeta.vlr_meta_final_cenario_alternativo"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-input">
                    <label>Nova Meta para 2027 {{this.formatarUnidadeMedida(dadosIndicadorRevisao.unidade_medida_id)}}</label>
                    <br>
                    <input id="vlr_esperado_ano_4_nova" disabled
                    type="number" 
                    name="vlr_esperado_ano_4_nova"
                    step="0.01"
                    v-model="dadosMetaRevisao.vlr_esperado_ano_4"
                    >
                </div>
            </div>

            <div class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="bln_meta_regionalizada">A meta é regionalizada?</label>
                    <p v-text="dadosMeta.bln_meta_regionalizada ? 'Sim': 'Não'"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>A meta será regionalizada?</label>
                    <select id="bln_meta_regionalizada_nova" class="form-select br-select" name="bln_meta_regionalizada_nova" disabled v-model="dadosMetaRevisao.bln_meta_regionalizada">
                    <option value="">Selecione se a meta é ou não regionalizada</option>
                    <option value="true">Sim</option>
                    <option value="false">Não</option>
                    </select>
                </div>
            </div>

            <div v-if="dadosMetaRevisao.bln_meta_regionalizada == false" class="row mt-3">
                <div class="column col-6 col-xs-12">
                    <label for="dsc_justificativa_ausencia_regionalizacao">Justificativa para não regionalização</label>
                    <p v-text="dadosMeta.dsc_justificativa_ausencia_regionalizacao"></p>
                </div>
            
                <div class="column col-6 col-xs-12 br-textarea">
                    <label>Nova Justificativa para não regionalização</label>
                    <textarea class="input-medium" id="dsc_justificativa_ausencia_regionalizacao_nova" name="dsc_justificativa_ausencia_regionalizacao_nova" rows="5" disabled v-model="dadosMetaRevisao.dsc_justificativa_ausencia_regionalizacao">
                    </textarea>
                </div>
            </div>

            <div class="row mt-3" >
                <div class="col col-xs-12 br-textarea">
                    <label for="txt_justificativa_revisao_meta_iniciativa">Justificativa para as alterações da Meta do Indicador da Entrega (Obrigatório)</label>
                    <textarea disabled class="input-medium" id="txt_justificativa_revisao_meta_iniciativa" name="txt_justificativa_revisao_meta_iniciativa" rows="2"
                    v-model="dadosMetaRevisao.txt_justificativa_revisao_meta_iniciativa">
                    </textarea>
                    <p class="text-base mt-1">(Obs.: em caso de atualização decorrente de alteração orçamentária, informar ação(ões) orçamentária(s) - 
                    código e título - que financia(m) a meta e a justificativa)</p>
                </div>
            </div>
            
            <hr>

            <div v-if="dadosMetaRevisao.bln_meta_regionalizada">
                <div class="row mt-3">
                    <div class="text-center">
                        <span class="fs-5 fw-bold">Metas Regionalizadas</span>
                    </div>
                    <div class="column col-6 col-xs-12">
                        <div class="text-center">
                            <p class="fw-bold">Metas Atualmente Cadastradas</p>
                        </div>
                        <div class="table-responsive mt-3">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Região</th>
                                        <th class="text-center">Meta para 2025</th>
                                        <th class="text-center">Meta para 2026</th>
                                        <th class="text-center">Meta para 2027</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in dadosRegionalizacao">
                                        <td>{{ (index+1) }}</td>
                                        <td class="text-center">{{ item.regionalizacao.txt_regionalizacao }}</td>
                                        <td class="text-center"><i>{{ item.vlr_esperado_ano_2 }}</i></td>
                                        <td class="text-center"><i>{{ item.vlr_esperado_ano_3 }}</i></td>
                                        <td class="text-center"><i>{{ item.vlr_meta_final_cenario_alternativo }}</i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="column col-6 col-xs-12">
                        <div class="text-center">
                            <p class="fw-bold">Metas Revisadas</p>
                        </div>
                        <div class="table-responsive mt-3">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Nova Meta para 2025</th>
                                        <th class="text-center">Nova Meta para 2026</th>
                                        <th class="text-center">Nova Meta para 2027</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in dadosRegionalizacaoRevisao">
                                        <td>{{ (index+1) }}</td>
                                        <td class="text-center" v-if="item.vlr_esperado_ano_2"><b>{{ item.vlr_esperado_ano_2 }}</b></td>
                                        <td class="text-center" v-else>Inalterado</td>
                                        <td class="text-center" v-if="item.vlr_esperado_ano_3"><b>{{ item.vlr_esperado_ano_3 }}</b></td>
                                        <td class="text-center" v-else>Inalterado</td>
                                        <td class="text-center" v-if="item.vlr_esperado_ano_4"><b>{{ item.vlr_esperado_ano_4 }}</b></td>
                                        <td class="text-center" v-else>Inalterado</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                
                <hr>
            
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
</template>

<script>

export default {
    props: ['url', 'dadosIniciativa', 'dadosRevisao','dadosIniciativaRevisao', 'dadosIndicadorRevisao', 'dadosMetaRevisao', 'dadosMeta', 'dadosRegionalizacao', 'dadosRegionalizacaoRevisao', 'revisoes', 'usuarioPreenchimento'],
    data() {
        return {
        //----Campos Select
            bln_meta_regionalizada_nova:'',
            unidadesMedida:'',
            unidadeMedida:'',
            novaUnidadeMedida:this.dadosIniciativa.unidade_medida_simbolo,
            periodicidades:'',
            periodicidade:'',
            polaridades:'',
            polaridade:'',

            //----Validação Revisão
            txt_situacao_revisao: '',
            situacao_revisao_observacao: '',
            situacao_revisao_id: '',

            //----Textos de Escolhas
            textoEscolhaUnidadeMedida: "Escolha uma nova Unidade de Medida:",
            textoEscolhaPeriodicidade: "Escolha uma nova Periodicidade:",
            textoEscolhaPolaridade: "Escolha uma nova Polaridade:",

            situacoesRevisao:'',
            situacaoRevisao:'',
            textoEscolhaSituacao: "Escolha a nova situação da Revisão",
        }
    },
    methods: {
        formatarUnidadeMedida(unidadeMedidaId){
            switch (unidadeMedidaId){
            case 1:
                return '(R$)';
            case 2:
                return '(%)';
            case 3:
                return '(ADI)';
            case 4:
                return '(m²)';
            case 5:
                return '(UN)';
            default:
                return '';
            }
        },        
    },
    mounted() {
        console.log(this.dadosIndicadorRevisao);
        axios.get(this.url + '/api/plancidades/unidadesMedida').then(resposta=>{
            this.unidadesMedida = resposta.data;
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

        axios.get(this.url + '/api/plancidades/situacao_revisao').then(resposta=>{
            this.situacoesRevisao = resposta.data;
            }).catch(error=>{
            console.log(error);
        });
    }
}
</script>
