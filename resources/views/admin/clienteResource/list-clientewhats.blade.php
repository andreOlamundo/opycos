@extends('templates.cliente-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-cliente')
@endsection

@section('conteudo-view')
<div id="line-one">
  <div id="line-one">
    <div class="container"> 

      <h2> </h2> 
      
      <div class="row">
        <div class="col-md-12"> 
         <ul class="collection">
          <li style="margin-left: 10px; margin-top: 10px; margin-bottom: -50px;"><a class="btn-floating btn-medium green pulse"><i class="material-icons">perm_identity</i></a>

          </li>

          <li class="collection-item avatar">

            <span class="title"><b>  Primeiro acesso</b></span>
            <p>Seja Bem vindo(a)<br>
             <b>Confirme seu número de celular para iniciarmos seu cadastro</b>
           </p><br>
         </li>
       </ul>
             @if (Session::has('mensagem-sucesso'))
             <div class="alert alert-success alert-dismissible">
              <strong>{{ Session::get('mensagem-sucesso') }}</strong>
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
            </div>
            @endif
            @if (Session::has('mensagem-falha'))
            <div class="alert alert-danger alert-dismissible">
              <strong>{{ Session::get('mensagem-falha') }}</strong>
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
            </div>
            @endif

             @if (session('message'))
            <div class="alert alert-danger alert-dismissible">
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
              <b> {{ session('message') }}</b>
            </div>
            @endif  


       @forelse ($clientes as $cliente)
        <div class="card-panel">
          <div class="row">
            <div class="col-md-12">
             <form method="POST" action="{{route('search-cliente')}}"> 
        {{ csrf_field() }}
           <div class="col-md-4"> 
            <div class="input-field" style="margin-bottom: 15px;">
             <input type="hidden" name="id" value="{{ $cliente->id }}">
             <input type="hidden" name="status" value="{{ $cliente->status }}">
             <input type="text" name="name" value="{{ $cliente->name }}" placeholder="{{ $cliente->name }}" readonly="readonly">
             <label for="name" style="font-size: 15px;">Nome</label>
           </div>     
               
       <!--  <div class="input-field">
          <input type="text" name="celInput" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" maxlength="15" minlength="14" id="sp_celphones" class="sp_celphones" title="Digite apenas números!.Caracteres especiais incluídos automaticamente. Ex:(11) 98768-7896 Não é necessario incluir o número '0' Zero, afrente do código de área." placeholder="ddd+ celular" autofocus>
          <label for="celInput" style="font-size: 15px;">Celular com 9 dígitos</label>
        </div> <br>
             <div class="input-field">
          <input type="text" name="celInput" pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" maxlength="15" minlength="14" id="phone_with_ddd" class="phone_with_ddd" title="Digite apenas números!.Caracteres especiais incluídos automaticamente. Ex:(71) 8578-3746 - Não é necessario incluir o número '0' Zero, afrente do código de área." placeholder="ddd+ celular" autofocus>
          <label for="celInput" style="font-size: 15px;">Celular com 8 dígitos</label>
        </div>  -->  

         <div class="input-field">
          <input type="text" name="celInput" pattern="\([0-9]{2}\) [0-9]{5,5}-[0-9]{4,4}$" maxlength="15" minlength="14" class="phone" title="Digite apenas números!.Caracteres especiais incluídos automaticamente. Ex:(11) 98768-7896 Não é necessario incluir o número '0' Zero, afrente do código de área." placeholder="ddd+ celular" autofocus>
          <label for="celInput" style="font-size: 15px;">Celular</label>
        </div>  
            


   <button type="submit" data-toggle="tooltip" data-placement="top" style="margin-top: 10px;" title="{{ $cliente->name }} Confirme seu número de celular." class="btn waves-effect amber">Confirmar</button>
      </div>
      </form>
    </div>
    </div>
  </div>



@empty

@forelse ($clienteAuth as $auth)


<a href="{{ URL('/admin/login') }}" data-toggle="tooltip" data-placement="top" title="Olá {{ $auth->name }} acesse o portal para acompanhar seus pedidos e ter acesso ao seu cadastro" class="btn waves-effect amber">
  <b>Acesse:</b>
</a>  


@empty

@endforelse 

@endforelse 








</div>
</div>
</div>
</div>
</div>
  


@endsection

