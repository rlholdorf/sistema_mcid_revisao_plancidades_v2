<template>
        <div class="my-5">
            <div class="text-center mb-3">
                <span class="text-bold">Progresso da Revisão</span>
            </div>
            <nav class="br-step" data-initial="" data-label="top" data-scroll="data-scroll" role="none">
                <div class="step-progress" role="listbox" aria-orientation="horizontal" aria-label="Lista de Opções">
                <button :class="{'step-progress-btn':true, 'active':detalhamento}" role="option" aria-posinset="1" aria-setsize="3" type="button" :data-alert="dadosRevisao.bln_projeto?'success':'info'" @click="irParaPagina('/plancidades/revisao/projeto/' + dadosRevisao.id + '/criar', detalhamento)" ><span class="step-info">Detalhamento do Projeto</span><span class="step-alert"></span></button>               
                <button :class="{'step-progress-btn':true, 'active':etapas}" role="option" aria-posinset="2" aria-setsize="3" type="button" :data-alert="dadosRevisao.bln_etapas?'success':'info'" @click="irParaPagina('/plancidades/revisao/etapas/projeto/' + dadosRevisao.id + '/criar', etapas)" ><span class="step-info">Etapas do Projeto</span><span class="step-alert"></span></button>
                <button :class="{'step-progress-btn':true, 'active':finalizar}" role="option" aria-posinset="3" aria-setsize="3" type="button" data-alert="warning" @click="irParaPagina('/plancidades/revisao/projeto/exibir/' + dadosRevisao.id, finalizar)"><span class="step-info">Finalizar</span><i class="step-icon fas fa-check" aria-hidden="true"></i><span class="step-alert"></span></button>
                </div>
            </nav>
        </div>
</template>

<script>
export default {
    props: ['url', 'dadosRevisao', 'active'],
    data() {
        return {
            detalhamento:false,
            etapas:false,
            finalizar:false
        }
    },
    methods: {
        irParaPagina(destino, paginaAtual){
            if(paginaAtual){
                return;
            }
            window.location.href = this.url + destino;
        },

        setActive(){
            if(this.active=='detalhamento'){
                this.detalhamento = true;
            }
            if(this.active=='etapas'){
                this.etapas = true;
            }
            if(this.active=='finalizar'){
                this.finalizar = true;
            }
            
        },
        //debug
        printDadosRevisao(){
        }
    },
    mounted() {
        this.setActive();
    }
}
</script>
