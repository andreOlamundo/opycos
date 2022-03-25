          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script><!-- -->
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

          $('.phone').mask(maskBehavior, options);
          $('.money').mask('000.000.000.000.000,00', {reverse: true}).attr('maxlength','6'); 
          $('.cep').mask('00000-000');
              
              });
          </script>



          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.sp_celphones').mask('(00) 00000-0000');
     $('.phone_with_ddd').mask('(00) 0000-0000');
    });
</script>
<script type="text/javascript">

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