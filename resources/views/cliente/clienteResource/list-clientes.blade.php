@extends('templates.admin-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-admin')
@endsection

@section('conteudo-view')
<div id="line-one">
<div id="line-one">
<div class="container"> 

      <h2><b>Listagem de Clientes</b></h2> 
      
  <div class="row">
    <div class="col-md-12">
      <ol class="breadcrumb" style="margin-bottom: 10px;">                     
        <li class="active"><b>Clientes</b></li>
        <li><a href="{{route('clientes.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>
      </ol>
      @if (session('message'))
<div class="alert alert-success alert-dismissible">

  <a href="#" class="close" 
  data-dismiss="alert"
  aria-label="close">&times;</a>
  <b>{{ session('message') }}</b>
</div>
@endif
 <form method="POST" action="{{route('Cliente.search')}}"> 
        {{ csrf_field() }}
      <div class="card-panel">
      	<div class="row">
      	      <div class="col-md-4">  
        <div class="input-field">   
          <select id="cliente" onchange="submit()" name="id">
            @foreach($dadosClientes as $dc)
            <option></option>
            <option value="{{ $dc->id }}">{{$dc->cpf}} {{$dc->cnpj}} &hybull;
         {{$dc->name}} </option>

            @endforeach     
          </select>
          <label for="id" style="font-size: 15px; margin-top: -30px;">Escolha um cliente para pesquisa </label>
         </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
        <script type="text/javascript">
          $("#cliente").select2({
            placeholder:'---Selecione o Cliente---'
          });
        </script>                  
      </div>
        <a href="{{route('clientes.create')}}" 
    class="btn-floating btn-large waves-effect waves-light btn-primary pull-right">
    <i class="material-icons">add</i></a>
</div>
            
    </div> 
</div>




    <div class="col-md-12"> 
     

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <tr class="warning">

                <th style=" width: 140px;">CPF / CNPJ                  
                             
              </th> 

                <th>Nome<br>                     
                                           
                </th>
                           

              <th>Email
               
             </th>                       
             <th>Telefone
              
            </th> 
            <th>Celular
              
            </th>             
            <th style="width: 200px;">Endereço
              
            </th>
            <th style="width: 80px;">Cep
             

           </th>                                  
           <th id="center" style=" width: 110px;">Ações <br>
            <a href="{{route('clientes.index')}}"             
            title="Limpar Pesquisa" class="btn waves-effect"><i class="fa fa-eraser"></i></a> &nbsp; 

            <button data-toggle="tooltip" 
            data-placement="top"
            title="Pesquisar" type="submit" class="btn waves-effect">
           <i class="fa fa-search"></i>                                              
          </button>

        </th>                
      </tr>
    </thead>            
  </form>
  <tbody>
    @foreach($clientes as $cliente)
    <tr>      
      <td title="CPF" >{{$cliente->cpf}} {{$cliente->cnpj}}</td>
      <td title="Nome">{{$cliente->name}}</td>
      <td title="email">{{$cliente->email}}</td>                
      <td title="Telefone">{{$cliente->tel}}</td>
      <td title="Celular">{{$cliente->cel}}</td>     
      <td title="Endereço">{{$cliente->endereço}}</td>
      <td title="Cep">{{$cliente->cep}}</td>

      <td title="Ações" id="center">
        <a href="{{route('clientes.edit', $cliente->id)}}" 
         data-toggle="tooltip" 
         data-placement="top"
         title="Alterar" class="btn waves-effect amber"><i class="small material-icons">border_color</i></a>
         &nbsp;


         <form style="display: inline-block;" method="POST" 
         action="{{route('clientes.destroy', $cliente->id)}}"                                                        
         data-toggle="tooltip" data-placement="top"
         title="Excluir" 
         onsubmit="return confirm('Confirma exclusão?')">
         {{method_field('DELETE')}}{{ csrf_field() }}                                                
         <button type="submit" class="btn waves-effect deep-orange">
          <i class="fa fa-trash"></i>                                                    
        </button></form></td>               
      </tr>

      @endforeach
    </tbody>
  </table>
</div>


<div class="row">
  <div class="col-sm-5 pull-left">
    <p class="text-cinza"> Mostrando 1 até 5 de {{$total}} Registros</p>
  </div>
  <div class="pull-right">
    @if (isset($dataForm))
    {!! $clientes->appends($dataForm)->links() !!}
    @else
    {!! $clientes->links() !!}
    @endif

  </div>

</div>
</div>
</div>
</div>


</div>
</div>

<script>
  function alterna(tipo) {

    if (tipo == 1) {
      document.getElementById("tipo1").style.display = "block";
      document.getElementById("tipo2").style.display = "none";
    } else {
      document.getElementById("tipo1").style.display = "none";
      document.getElementById("tipo2").style.display = "block";
    }

  }

</script>
@endsection

