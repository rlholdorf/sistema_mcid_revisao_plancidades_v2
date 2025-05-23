@extends('layouts.app')

@section('content')


<historico-navegacao
      :url="'{{ url('/home') }}'"      
      :telanterior01="'Debentures e Reidi'"      
      :telanterior02="'Cadastraar Reidi'"      
      :telatual="'Cadastrar Projeto'"
      >

</historico-navegacao>


<div class="main-content pl-sm-3 mt-5 container-fluid" id="main-content" >

  <cabecalho-relatorios
            titulo="Cadastrar Projeto"   
            :barracompartilhar="false">
  </cabecalho-relatorios>

  <form role="form" method="POST" action='{{ url("debentures_reidi/projeto/reidis/salvar") }}'>
    @csrf

    <projeto-reidis 
        :url="'{{ url('/') }}'"
      
        ></projeto-reidis>
        
   
    <div class="p-3 text-right">      
        <button class="br-button primary mr-3" type="submit">AtualSAlvarizar
        </button>        
        <button class="br-button danger mr-3" type="button" onclick="javascript:window.history.go(-1)">Voltar
        </button>       
    </div> 
  </form>

  
  



</div>




@endsection