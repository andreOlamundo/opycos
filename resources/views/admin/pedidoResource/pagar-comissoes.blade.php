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

      <div class="row" style="height: 80px; width: 1170px; position: fixed; background-color: white; z-index: 1001; top: 50px; margin-bottom: 80px;">
        <div class="col-md-12">
          <h2>Pagamento de Comissões</h2>
          <div class="divider" style="margin-bottom: 2px; margin-top: -8px;" ></div>
        </div>
      </div>  

      <div class="row" style="height: 80px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">   
         @if (Session::has('mensagem-sucesso'))
         <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
          <strong>{{ Session::get('mensagem-sucesso') }}</strong>
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
        @endif
        @if (Session::has('mensagem-falha'))
        <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px;">
          <strong>{{ Session::get('mensagem-falha') }}</strong>
          <a href="#" class="close" 
          data-dismiss="alert"
          aria-label="close">&times;</a>
        </div>
        <script type="text/javascript">
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
        @endif

        <div class="card-panel" style="height: 80px; padding: 15px 10px;">    

         <div class="row" style="margin-bottom: -10px;">
          <form method="POST" action="#">
           {{ csrf_field() }} 

          
           <div class="col-md-5"> 
             <div class="col-md-2">          
               <label style="font-size: 12px;">Pedido
                <input type="text" readonly name="pedido_id" value=""> </label>
              </div> 
              <div class="col-md-3">    
               <label style="font-size: 12px;">Data
                <input type="text" readonly value=""  title=""> </label>
              </div>                                 
              </div> 
              <div class="col-md-7">                
               <div class="col-md-5"> 
                 <label style="font-size: 12px;">Vendedor
                   <input type="text" title="Vendedor" readonly value=""></label>                   
                 </div>
                 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
      },
      options = {onKeyPress: function(val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
      }
    };

    /*$('.phone').mask(maskBehavior, options);
    $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
    $('.cep').mask('00000-000');*/
    $('.percent').mask('000%', {reverse: true}).attr('maxlength','4');

  });
</script>

                 <div class="col-md-3">
                   <label style="font-size: 12px;">Comissão(R$)
                    <input type="text" readonly name="valor_comissao" value=""></label> 
                  </div> 
                   <div class="col-md-2"> 
                  <label style="font-size: 12px;">Comissão(%)
                    <input type="text" name="percent_comissao" class="percent" title="Comissão de 1% à 100%" placeholder="0%" required></label>        
              </div>
                         <button type="submit" class="btn btn-small btn-primary" style="margin-top: 15px; font-size: 12px;" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="top" title="Calcular comissão sobre o valor total do pedido."><!--<i class="fa fa-money"></i>-->
                    <b>Calcular</b>
                  </button> 

                    </div>
                
                </form>



              </div>
            </div>


          </div>

        </div>


<div class="row" style="margin-top: 130px;">       
    <div class="table-responsive">
     <table class="table-sm table-bordered" >

      <thead>
       <tr style="background-color: #fcf8e3;">                    
        <th >Código</th>    
        <th >Desconto</th>                   
        <th >Total</th>
      </tr>
    </thead> 

    <tbody> 
     <tr> 

      <td>
   ...
     </td> 
     <td>
      ...
     </td>
     <td>     
   ...
     </td> 
    </tr>

</tbody> 
 <tfoot>
            <tr>
              <th style="border-right: none;"></th>
              <th style="border-right: none;"></th>             
              <th></th>           
         
            </tr>
            
          </tfoot>

</table>
</div>




<a href="{{route('pedido.comissoes')}}" class="btn btn-default"><b>VOLTAR</b></a>

  <button  onclick="myFunction()" title="Realizar pagamento" class="btn waves-effect amber">
    <strong>Pagar Comissão</strong>   
    <script type="text/javascript">
      function myFunction() {
        document.getElementById("myCheck").click();
      }
    </script>
  </button> 

  <button type="button" style="display:none" id="myCheck" name="op1"  data-toggle="modal" data-target="#myModal2"></button>
  <div class="modal fade" id="myModal2" role="dialog">

  <div class="modal-dialog">
    <form method="POST" action="#"> 
      {{ csrf_field() }}  
      <!-- Modal content-->  
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detalhes do Pagamento</h2>
      </div>
      <div class="modal-body">      
      
       <label style="font-size: 12px;">Observações                      
       <input type="text" name="obs_comissao" required placeholder="Obs:" >
       </label>
                <input type="hidden" name="pedido_id" value="">
        <input type="hidden" name="vendedor_id"  value="">
        
      </div>
       <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal" style="margin-top: 11px;">Voltar</button>            
    <button type="submit"  class="btn waves-effect waves-light  blue darken-2"><span class="glyphicon glyphicon-floppy-disk"></span><b> Salvar</b></button>
   </div>
</form>
</div>
</div>
</div>
</div>
</div>

@endsection