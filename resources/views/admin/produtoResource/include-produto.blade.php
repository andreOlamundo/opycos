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
          <h2>Novo Produto</h2>
          <a href="{{route('categoria.create')}}" 
          class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; margin-right: 120px;" title="Lista de pedidos consignados">
          <i class="fa fa-bookmark" aria-hidden="true"></i><b> Categorias</b></a>
          <a href="{{route('product.index')}}" 
          class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 110px; height: 25px; padding: 2px 1px;">
          <i class="fa fa-list-ol" aria-hidden="true"></i> <b>Produtos</b></a> 
          <div class="divider" style="margin-bottom: 1px;"></div>

        </div>
      </div>
      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">      
           @if (session('message'))
          <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
            <a href="#" class="close" 
            data-dismiss="alert"
            aria-label="close">&times;</a>
            <b> {{ session('message') }}</b>
          </div>
           <script type="text/javascript">
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
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
          $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
            $(".alert-dismissible").alert('close');
          });
        </script>
          @endif

         <div class="card-panel" style="height: 110px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
           <form method="post" 
          action="{{route('product.store')}}">
          {{ csrf_field() }}        
          <div class="row">
        
            <div class="col-md-1" style="margin-right: -10px;">           
              <div class="input-field">  
                <input type="text" name="prod_cod" value="{{ old('prod_cod') }}" title="Informe o código do produto contendo 5 dígitos" maxlength='5' placeholder=""  minlength="5" onkeypress='mascara( this, mnum );' required autofocus/>
                <label for="prod_cod" style="font-size: 15px;">CÓDIGO</label>
              </div>
            </div>         
             <!-- <div class="col-md-2"> 
             <div class="input-field">                   
              <input type='text' required='required' value="{{ old('prod_preco_prof') }}" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' title="PREÇO P/ PROFISSIONAIS.-PJ" placeholder="R$ 0,00" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_prof" style="font-size: 15px;" >PREÇO P/ PROF.-PJ</label> 
            </div>
          </div>
              <div class="col-md-2"> 
           <div class="input-field"> 
            <input type="text" required="required" value="{{ old('prod_preco_balcao') }}" title="PREÇO BALCÃO" maxlength='15'  name='prod_preco_balcao' placeholder="R$ 0,00" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$'  onkeypress='mascara( this, mvalor );'>
            <label for="prod_preco_balcao"  style="font-size: 15px;" >PREÇO BALCÃO</label>
          </div>
        </div>-->  

         <div class="col-md-4"  style="margin-right: -10px;">
       <div class="input-field">
         <input type="text" name="prod_desc" id="prod_desc" title="Breve Descriçaõ do Produto"  value="{{ old('prod_desc') }}" placeholder="" required />
         <label for="prod_desc" style="font-size: 15px;">DESCRIÇÃO</label><!--onkeypress='mascara( this, soLetras );'-->
       </div>              
     </div>
           <div class="col-md-1"  style="margin-right: 10px;">         
             <div class="input-field">    
                <input type='text' required='required' value="{{ old('prod_preco_padrao') }}" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="PREÇO R$ 0,00" placeholder="R$ 0,00" class="money">
                <label for="prod_preco_padrao" style="font-size: 15px;">PREÇO</label>
              </div>
            </div>

               <div class="col-md-1" style="margin-right: -10px;">
       <div class="input-field">
         <input type="number" name="quantidade" title="Quantidade"  value="{{ old('quantidade') }}" max="200" placeholder="" />
         <label for="quantidade" style="font-size: 15px;">QTD</label>
       </div>              
     </div>

              <div class="col-md-1" style="margin-right: -10px;">
       <div class="input-field">
         <input type="text" name="peso" title="Peso"  value="{{ old('peso') }}" id="masked-1" placeholder="" />
         <label for="peso" style="font-size: 15px;">PESO</label>
       </div>              
     </div>


             <div class="col-md-3" style="margin-right: -10px;">
          <div class="input-field">
            <select id="groups" name="grup_cod" value="{{ old('grup_cod') }}" title="Categoria do produto" required="required">
              @foreach($list_CategProd as $registro)
              <option></option>
              <option value="{{ $registro->id }}">{{$registro->grup_desc_categ}}</option>

              @endforeach     
            </select>
            <label for="grup_cod" style="font-size: 12px; margin-top: -30px;">CATEGORIA DO PRODUTO</label> 

          </div>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
          <script type="text/javascript">
            $("#groups").select2({
              placeholder:'---Selecione a Categoria---'
            });
          </script>

           </div>
   

  </div>
      <a href="{{route('product.index')}}" 
          class="btn btn-default" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; " title="Lista de pedidos consignados">Voltar</a> 
                <button type="submit" 
        class="btn waves-effect waves-light  blue darken-2"  style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b>
        Salvar</b>
      </button> 
       </form>   
</div>

</div>
</div>

 <div class="row" style="margin-top: 180px;">

  </div>

</div>
</div>
</div>
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
    $('.percent').mask('000%', {reverse: true}).attr('maxlength','4');

  });
</script>

<!--Mask Plugin -->

<script type="text/javascript">
       /*var console = {
 log: function (msg) {
 $('#log').prepend('<p>' + JSON.stringify(msg) + '</p>');
 }
 }
 */

jQuery.fn.extend({


    maskWeight: function (userOptions) {

        window._maskData = {

            selector: $(this),
            arr: [/*'0', '0', '0', '0', '0', '0'*/],
            insertCount: 0,
            numberPressed: false,

            options: {},

            defOptions: {
                integerDigits: 3,
                decimalDigits: 3,
                decimalMark: '.',
                initVal: '',//'000,000',
                roundingZeros: true,
                digitsCount: 6,
                callBack: null,
                doFocus: true,
            },

            initializeOptions: function (userOptions) {

                this.options = $.extend(true, this.defOptions);
                this.arr = [];
                this.insertCount = 0;
                this.numberPressed = false;

                if (userOptions) {

                    for (var prop in userOptions) {

                        if (userOptions[prop] !== undefined && userOptions[prop] !== null) {
                            this.options[prop] = userOptions[prop];
                        }

                    }

                }

                if (this.options.decimalDigits == 0) {
                    this.options.decimalMark = '';
                }

                var initValFromUser = false;

                if (this.options.initVal == '') {

                    if (this.options.roundingZeros) {
                        this.options.initVal += '0';
                    } else {
                        for (var i = 0; i < this.options.integerDigits; i++) {
                            this.options.initVal += '0';
                        }
                    }

                    this.options.initVal += this.options.decimalMark;

                    for (var i = 0; i < this.options.decimalDigits; i++) {
                        this.options.initVal += '0';
                    }

                } else {
                    initValFromUser = true;
                }

                this.options.digitsCount = this.options.integerDigits + this.options.decimalDigits;
                this.arr = [];

                for (var i = 0; i < this.options.digitsCount; i++) {
                    this.arr.push('0');
                }

                if (initValFromUser && parseInt(this.options.initVal) > 0) {
                    this.createInitialValueArr();
                }

            },

            createInitialValueArr: function () {

                this.options.initVal = this.options.decimalDigits == 0 ? parseInt(this.options.initVal) : parseFloat(this.options.initVal.toString().replace(',', '.')).toFixed(this.options.decimalDigits).replace('.', this.options.decimalMark);

                var splitted = this.options.initVal.toString().replace('.', '').replace(',', '').split('');

                for (var i = 0; i < splitted.length; i++) {
                    this.insert(splitted[i]);
                }

            },

            insert: function (num) {

                var insert = this.mask(num);
                this.selector.val(insert);

                this.setCartetOnEnd();

            },

            mask: function (num) {

                if (num == 'backspace') {

                    if (this.insertCount > 0) {
                        this.arr.pop();
                        this.arr.unshift('0');
                        this.insertCount--;
                    }

                } else {

                    if (this.insertCount < this.options.digitsCount) {
                        this.arr.shift();
                        this.arr.push(num.toString());
                        this.insertCount++;
                    }

                }

                var value = '';

                for (var i = 0; i < this.arr.length; i++) {
                    value += this.arr[i];
                }


                value = this.reduce(value);

                return value;

            },

            reduce: function (value) {

                if (this.options.decimalDigits == 0) {
                    if (this.options.roundingZeros) {
                        return parseInt(value);
                    } else {
                        return value;
                    }
                } else {
                    if (this.options.roundingZeros) {
                        return parseInt(value.substring(0, this.options.integerDigits)) + this.options.decimalMark + value.substring(this.options.integerDigits, this.options.digitsCount);
                    } else {
                        return value.substring(0, this.options.integerDigits) + this.options.decimalMark + value.substring(this.options.integerDigits, this.options.digitsCount);
                    }
                }


            },

            getNumber: function (e) {
                return String.fromCharCode(e.keyCode || e.which);
            },

            setCartetOnEnd: function () {

                var self = this;

                setTimeout(function () {

                    var len = self.selector.val().length;

                    if(self.options.doFocus){
                        self.selector[0].focus();
                    }

                    self.selector[0].setSelectionRange(len, len);

                    //self.selector.selectionStart = self.selector.selectionEnd = 10000;

                }, 1);

            },

            isNumberOrBackspace: function (num) {

                if (num == 'backspace') {
                    return true;
                }

                if (parseInt(num) || parseInt(num) == 0) {
                    return true;
                }

                return false;

            },

            init: function () {

                var self = this;

                this.selector.val(this.options.initVal);

                this.selector.on('click', function (e) {
                    self.setCartetOnEnd();
                });

                var ua = navigator.userAgent.toLowerCase();
                var isAndroid = ua.indexOf("android") > -1;
                if (isAndroid) {

                    window._maskDataLastVal = this.selector.val();

                    this.selector[0].removeEventListener('input', window._maskDataAndroidMaskHandler, true);

                    setTimeout(function () {

                        window._maskDataAndroidMaskHandler = function (e) {
                            e.preventDefault();
                            e.stopPropagation();

                            if (self.selector.val().length < window._maskDataLastVal.length) {
                                self.insert('backspace');
                            } else {
                                var num = self.selector.val().charAt(self.selector.val().length - 1);

                                if(parseFloat(self.selector.val().replace(',','.')) == 0 && parseInt(num) == 0){
                                    self.insert('backspace');
                                }else{
                                    self.insert(num);
                                }

                            }

                            window._maskDataLastVal = self.selector.val();

                            if(self.options.callBack){
                                self.options.callBack();
                            }

                            return false;

                        };

                        self.selector[0].addEventListener('input', window._maskDataAndroidMaskHandler, true);

                    }, 0);


                } else {

                    this.selector.on('keydown', function (e) {
                        var key = e.keyCode || e.which;

                        if (key == 8 || key == 46) {
                            e.preventDefault();
                            e.stopPropagation();
                            self.insert('backspace');
                        }

                        if(self.options.callBack){
                            self.options.callBack();
                        }

                    });

                    this.selector.on('keypress', function (e) {

                        var key = e.keyCode || e.which;

                        e.preventDefault();
                        e.stopPropagation();

                        var num = self.getNumber(e);

                        if (self.isNumberOrBackspace(num)) {

                            if(parseFloat(self.selector.val().replace(',','.')) == 0 && parseInt(num) == 0){
                                self.insert('backspace');
                            }else{
                                self.insert(num);
                            }

                        }

                        if(self.options.callBack){
                            self.options.callBack();
                        }

                    });

                }

            }

        };

        window._maskData.initializeOptions(userOptions);
        window._maskData.init();

    },

    removeMask: function () {

        if (window._maskData) {
            $(this).unbind();
            window._maskData = null;
        }

    },

});

$('#masked-1').maskWeight({
 integerDigits: 1,
 decimalDigits: 3,
 decimalMark: ',',
 //initVal default: generated
 //roundingZeros default: true
});
     </script>
         

@endsection