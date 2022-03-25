                <script type="text/javascript">

                /*  function calcularFrete() {
 
  document.getElementById("textbox").click();
  document.getElementById("textbox1").click();

                

};*/

/*  function cepIsset()
  {
     
    $("#cep").val("");
    document.getElementById("textbox1").click();
      $("#cep").blur(function() {
            document.getElementById("textbox").click();
            document.getElementById("textbox1").click();

          });
  }*/

   function cepIsset(){
            if($('input[name="local"]').prop('checked')){
          $("#local").val("Y");
          $('[name="localisset"]').show();
            //document.getElementById("textbox").click();
            //document.getElementById("textbox1").click();

        } else 

        {
          $('[name="localisset"]').hide();
          $("#local").val("");
        } 

          $("#cep").val("");
                
      

          /*$("#cep").blur(function() {
            document.getElementById("textbox").click();
            document.getElementById("textbox1").click();

          });*/
                    };



                  function LoadFrete(){
                     $("#valor_frete").val("Pesquisando...");
                   //console.log("humm its change");               
                  var pedido_id_load = $('#pedido_id_load').val();
                  var cep_alter = $('.cep').val();
                  var cdservico_alt = $('#textbox1').val();
                   //var status = 'FI';
                   $.ajax({
                    type:'get',
                    url:'{!!URL::to('infoFrete') !!}',
                    datatype:'html',
                    cache: false,
                    data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},                                     
                    success:function(data){
                                      //console.log('success');
                                      console.log(pedido_id_load);
                                      console.log(cep_alter);
                                      console.log(cdservico_alt);
                                       $('#valor_frete').val(data);
                                       
                                        //console.log(data.length);                                   
                                      }, beforeSend: function(){


                                      },
                                      erro:function(){
                                        console.log('Erro!');
                                      }

                                    }); 

               
                 };


                  $(document).on('click','#textbox',function(){

                      $("#prazo_entrega").val("Pesquisando...");

                  var pedido_id_load = $('#pedido_id_load').val();
                  var cep_alter = $('.cep').val();
                  var cdservico_alt = $('#textbox1').val();
                  //var acrescimo = 2;

                  

           $.ajax({
      type:'get',
      url:'{!!URL::to('infoFretePrazoEntrega') !!}',
      data:{pedido_id_load:pedido_id_load, cep_alter:cep_alter, cdservico_alt:cdservico_alt},
      dataType:'html',
      cache: false,
      success:function(data){

       
       $('#prazo_entrega').val(data + ' Dias');

      },
      erro:function(){
        console.log('Erro!');
      }

     });

    });
               </script>