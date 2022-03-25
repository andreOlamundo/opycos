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
          <h2>Alterar Produto</h2>     
           </div>
      </div>

      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">
                 

          <form method="post" 
          action="{{route('product.update', $product->id)}}" 
          enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }} 
          <div class="card-panel" style="height: 160px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
          <div class="row">
            <div class="col-md-2">              
              <div class="input-field">

               <input type="text" name="prod_cod" placeholder='Digite o Código' 
              maxlength='5' title="Digite o Código contendo 5 Digitos" onkeypress='mascara( this, mnum );' value="{{$product->prod_cod or old('prod_cod')}}" 
               required autofocus>
               <label for="prod_cod" style="font-size: 15px;">Código</label>
             </div>
           </div> 
                   <div class="col-md-4">
            <div class="input-field">
              <input type='text' maxlength="191" value="{{$product->prod_desc or old('prod_desc')}}" name="prod_desc" placeholder="Digite Aqui:" title="Breve Descriçaõ do Produto" required/>
              <label for="prod_desc" style="font-size: 15px;">Descrição</label>
            </div>
          </div> 
           <div class="col-md-2">
             <div class="input-field">

              <input type='text' required='required' placeholder='0,00' value="{{number_format($product->prod_preco_padrao, 2,',','.' or old('prod_preco_padrao'))}}" maxlength='15' name="prod_preco_padrao" pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title="Preço de venda Padrão" class="money">
              <label for="prod_preco_padrao" style="font-size: 15px;">Preço</label>
            </div>
          </div>


               <div class="col-md-2">
       <div class="input-field">
         <input type="number" name="quantidade" title="Quantidade"  value="{{$product->quantidade or old('quantidade') }}" placeholder="Quantidade" />
         <label for="peso" style="font-size: 15px;">QTD</label>
       </div>              
     </div>

              <div class="col-md-2">
       <div class="input-field">
         <input type="text" name="peso" title="{{$product->peso or old('peso') }}"  value="{{$product->peso or old('peso') }}" id="masked-1" placeholder="{{$product->peso or old('peso') }}">      
         <label for="peso" style="font-size: 15px;">PESO (Kg)</label>
       </div>              
     </div>

    

         <!-- <div class="col-md-2">
            <div class="input-field"> 
              <input type='text' required='required' placeholder='0,00' class="form-control" value="{{number_format($product->prod_preco_prof, 2,',','.' or old('prod_preco_prof'))}}" maxlength='15' name='prod_preco_prof' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title=" Preço de venda para Profissionais" onkeypress='mascara( this, mvalor );'> 
              <label for="prod_preco_prof" style="font-size: 15px;">Preço segmentado</label>
            </div>  
          </div>

          <div class="col-md-2">
            <div class="input-field">
              <input type='text' required='required' placeholder='0,00' class='form-control' value="{{number_format($product->prod_preco_balcao, 2,',','.' or old('prod_preco_balcao'))}}" maxlength='15'  name='prod_preco_balcao' pattern='([0-9]{1,3}\.)?[0-9]{1,3},[0-9]{2}$' size='15' title=" Preço de venda Balcão" onkeypress='mascara( this, mvalor );'>
              <label for="prod_preco_balcao" style="font-size: 15px;">Preço segmentado</label>
            </div>
          </div>-->
               <div class="col-md-4">
      <div class="input-field">
        <select id="grupCateg" name="grup_cod" title="Revisão obrigatória do campo" required="required">
          @if (isset($product->grup_cod ))
          <option value="{{ $product->grup_cod }}">{{$product->grupCod->grup_desc_categ}}</option>
          @foreach($list_CategProd as $registro)        
          <option value="{{ $registro->id }}">{{$registro->grup_desc_categ}}</option>
          @endforeach  

          @else
          @foreach($list_CategProd as $registro)
          <option></option>
          <option value="{{ $registro->id }}">{{$registro->grup_desc_categ}}</option>

          @endforeach  
          @endif   
        </select>
        <label for="grup_cod" style="font-size: 15px; margin-top: -30px;">Categoria do produto </label> 

      </div>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
      <script type="text/javascript">
        $("#grupCateg").select2({
          placeholder:'{{$product->grupCateg->grup_desc_categ or old('grup_desc_categ')}}'
        });
      </script> 
    </div>
          <!--<div class="col-md-4"> 
             <div class="input-field">
                                               
              <select id="groups" name="grup_categ_cod" title="Categoria do produto" required="required">
                @foreach($list_CategProd as $registro)
                <option></option>
                <option value="{{ $registro->grup_cod }}">{{$registro->grup_desc}}</option>

                @endforeach     
              </select>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>      
              <script type="text/javascript">
                $("#groups").select2({
                  placeholder:'{{$product->grupCateg->grup_desc or old('grup_desc')}}'

                });
              </script> 
              <label for="prod_preco_balcao" style="font-size: 15px; margin-top: -30px;">Grupo do produto </label> 
            </div>
            
          </div> -->
          <div class="col-md-2"> 
             <div class="input-field">
              <select name="ativo" class="form-control" title="Ativar ou Desativar" style="height: 30px;">
                <option value="{{$product->ativo or old('ativo')}}" style="background: #dddddd;">@if($product->ativo == 's'){{ $product->ativo == 's' ? 'Ativo' : '' }} @else {{ $product->ativo == 'n' ? 'Inativo' : '' }} @endif</option>
                @if($product->ativo == 's')
                <option value="n">Desativar</option>
                @else
                <option value="s">Ativar</option>
                @endif
               
              </select>
                 <label for="ativo" style="font-size: 15px; margin-top: -30px;">Ativo ou Inativo</label> 
             </div>
           </div>

  

    
          <div class="col-md-12">
            <br>
             <a href="{{route('product.index')}}" class="btn btn-default" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; ">
          <b>Voltar</b>
        </a>
            <button type="submit" 
            class="btn waves-effect waves-light  blue darken-2"  style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b>
        Salvar</b>
          </button>
        </div>
      </div>
    </div>
    </form> 
  </div>
</div>            
</div>
</div>
</div>
  <!-- MAASK FORM -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
         
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
 initVal default: generated,
 //roundingZeros default: true
});
     </script>

@endsection