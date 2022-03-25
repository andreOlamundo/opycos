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
             
          <div class="row" style="height: 50px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 70px;">
        <div class="col-md-12">
          <h2>Alterar Requisição</h2>     
        </div>
      </div>

      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">
             <!--<ol class="breadcrumb" style="margin-bottom: 5px;">                            
            <li><a href="{{route('requestopycos.create')}}" id="btn" style="text-decoration: none"><b>Requisições</b></a></li>                           
            <li class="active">Alteração</li>
          </ol>-->
    
            @if (session('message'))
          <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b> {{ session('message') }}</b>
          </div>
          <script type="text/javascript">
                    $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
                      $(".alert-dismissible").alert('close');
                    });
                  </script>
          @endif
                  @if (session('message-failure'))
          <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b> {{ session('message-failure') }}</b>
          </div>
           <script type="text/javascript">
            $(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
              $(".alert-dismissible").alert('close');
            });
          </script>
          @endif              

          <form method="post" 
          action="{{route('requestopycos.update', $requisitions->id)}}" 
          enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }} 
          <div class="card-panel" style="margin-top: 2px; margin-bottom: 2px; padding: 12px 10px;">
          <div class="row" style="margin-bottom: 2px;" >
               <div class="col-md-2" style="margin-right: -10px;">
    <div class="input-field">
      <input type='text' name="request_cod" pattern="{5,15}" minlength="8" maxlength="15" value="{{ $requisitions->request_cod or old('request_cod')}}"  placeholder="0000000-0" title="Número da Requisição" required="required"> 
     <label for="request_cod" style="font-size: 15px;" >Nº</label>
    </div>
    </div>
  

       <div class="col-md-4" style="margin-right: -10px;">
       <div class="input-field">
         <input type="text" name="request_desc" onkeypress='mascara( this, soLetras );' title="Breve Descriçaõ da requisição"  value="{{ $requisitions->request_desc or old('request_desc')}}" placeholder="Descriçaõ da requisição" required />
         <label for="request_desc" style="font-size: 15px;">DESCRIÇÃO</label>
       </div>              
     </div>


    <div class="col-md-1" style="margin-right: -10px;">
    <div class="input-field">
      <input type='text' name="request_valor"  maxlength='6' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' value="{{number_format($requisitions->request_valor, 2,',','.')}}" placeholder="R$ 0,00" onkeypress='mascara( this, mvalor );' title="Valor da Requisição" required="required"> 
     <label for="request_valor" style="font-size: 15px;">PREÇO</label>
    </div>
    </div>


        <div class="col-md-1" style="margin-right: -10px;">
       <div class="input-field">
         <input type="number" name="quantidade" title="Quantidde"  value="{{$requisitions->quantidade or old('quantidade') }}" placeholder="Quantidade" readonly="readonly" />
         <label for="peso" style="font-size: 15px;">QTD</label>
       </div>              
     </div>

       <div class="col-md-1" style="margin-right: -10px;">
                         <div class="input-field">
                           <input type="text" name="peso" title="Peso"  value="{{$requisitions->peso or old('peso') }}" id="masked-1" placeholder="Peso do Produto" required="required" />
                           <label for="peso" style="font-size: 15px;">PESO</label>
                         </div>              
   </div>

   

    <div class="col-md-3" style="margin-right: -10px;">
          <div class="input-field">
            <select class="form-control" name="cliente_id" title="Requisiçao destinada ao Cliente:">         
            
              <option value="{{ $requisitions->cliente_id}}">{{ $requisitions->Cliente->name }}</option>

    
            </select>
            <label for="cliente_id" style="font-size: 12px; margin-top: -30px;">REQUISIÇÃO DESTINADA AO CLIENTE:</label> 

          </div>
         
           </div>

      </div>

      <div class="row">
         <div class="col-md-3">
          <div class="input-field">
            <select name="statusdasabled" style="height: 29px;" class="form-control" title="Status da requisição" disabled="disabled">              
              <option value="{{$requisitions->status or old('status') }}">{{ $requisitions->status == 'AP' ? 'Aguardando Produção' : '' }} {{ $requisitions->status == 'FI' ? 'Produção Finalizada' : '' }}</option>
              <option value="FI">Produção Finalizada</option> 
               <option value="AP">Aguardando Produção</option>       
            </select>
            <label for="status" style="font-size: 13px; margin-top: -30px;">STATUS DA REQUISIÇÃO</label> 
          </div>

          <input type="hidden" name="status" value="FI">

           </div>
         </div>  
         <a href="{{route('requestopycos.index')}}" 
                class="btn btn-default" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><b>Voltar</b></a>
                 &nbsp;       
             <a href="{{route('requestopycos.cancelar', $requisitions->id)}}" 
                class="btn waves-effect red" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; " onclick="return confirm('Tem certeza que deseja cancelar a requisição?. Após o cancelamento não será possível realizar outras alterações, se estiver em dúvida, clique em CANCELAR!')"><b>Cancelar</b></a>
                 &nbsp;
            <button type="submit" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; " onclick="return confirm('Atenção!... Se tiver alterado o Status da Requisição para Produção Finalizada, não será possível realizar outras alterações posteriores, se estiver em dúvida, clique em CANCELAR!')" 
            class="btn waves-effect waves-light  blue darken-2"><span class="glyphicon glyphicon-floppy-disk"></span>
            <b>Salvar</b>
          </button>
  
    </div>

    </form> 
  </div>
</div>            
</div>
</div>
</div>

@endsection