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
     <h2>Representante comercial</h2>   
     <div class="row">
    <div class="col-md-12"> 
           <ul class="collection">
          <li style="margin-left: 12px; margin-top: 8px; margin-bottom: -50px;"><a class="btn-floating btn-medium yellow pulse"><i class="material-icons">perm_identity</i></a>

          </li>

          <li class="collection-item avatar">

            <span class="title"><b>{{$vendedor->name}}</b></span>
            <p>{{$vendedor->email}}<br>
             <b>{{$vendedor->cel}}</b>
           </p><br>
         </li>
       </ul>
    </div>
  </div>
</div>

@endsection

