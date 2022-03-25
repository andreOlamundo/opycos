@extends('templates.vendedor-login')

@section('css-view')
@endsection

@section('js-view')
@endsection

@section('templates.menu-superior-vendedor')
@endsection

@section('conteudo-view')
<div id="line-one">
  <div id="line-one">
    <div class="container">

      <!-- CONTAINER -->

      <h2><b> Link Whatsapp</b></h2>  
        <div class="divider" style="margin-bottom: 3px; margin-top: -8px;" ></div>    

      <div class="row">
        <div class="col-md-12">
         <ol class="breadcrumb" style="margin-bottom: 5px;">                     
            <li><a href="{{route('clientesinter.index')}}" id="btn" style="text-decoration: none"><b>Clientes</b></a></li>
            <li><a href="{{route('clientesinter.create')}}" id="btn" style="text-decoration: none"><b>Cadastro</b></a></li>
             <li class="active"> Pré-Cadastro Whatsapp</li>
          </ol>

            @if (Session::has('mensagem-sucesso'))
             <div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px; margin-top: 1px;">
              <strong>{{ Session::get('mensagem-sucesso') }}</strong>
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
            </div>
            @endif
            @if (Session::has('mensagem-falha'))
            <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px; margin-top: 1px;">
              <strong>{{ Session::get('mensagem-falha') }}</strong>
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
            </div>
            @endif

             @if (session('message'))
            <div class="alert alert-danger alert-dismissible fade in" style="margin-bottom: 1px; margin-top: 1px;">
              <a href="#" class="close" 
              data-dismiss="alert"
              aria-label="close">&times;</a>
              <b> {{ session('message') }}</b>
            </div>
            @endif    
           
          <div class="card-panel">
           
            <div class="row">            
            
            <div class="col-md-12">
                <form action="{{ route('cadastroInter.preview') }}" method="POST" accept-charset="utf-8">
                {{ csrf_field() }}
                     
                   @forelse ($previews as $preview)

                    
                  <div class="col-md-3">
                  <div class="input-field">                

                   <input type="hidden" name="status" value="{{ $preview->status }}">

                   <input id="phone" type="hidden" name="cel" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4,4}$" maxlength="15" minlength="14" value="{{ $preview->cel }}">   
                   
                  <input type="text" name="celInput" title="Cel: {{ $preview->celInput }}" title="{{ $preview->celInput }}" readonly value="{{ $preview->celInput }}">
                  <label for="cel" style="font-size: 15px;">Celular</label>
                 </div>                    
                 
                 <div class="input-field">
                  <input id="nome" onkeypress='mascara( this, soLetras );' type="text" title="Nome: {{ $preview->name }}"  maxlength="64" name="name" value="{{ $preview->name }}" readonly>
                  <label for="nome" style="font-size: 15px;">Nome</label>     
                </div>

                <div class="input-field">
                  <input id="email" type="email" title="E-mail: {{ $preview->email }}" title="{{ $preview->email }}" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="{{ $preview->email }}" readonly> 
                  <label for="email" style="font-size: 15px;">Email</label>
                </div> 
                               
                   @foreach($vendedores as $vendedor)
               
                  <input type="hidden" name="vendedor_id" value="{{ $vendedor->id }}">
               
                    @endforeach          
                
             
            </div>
  </form> 

  <div class="col-md-9">
              
              <div class="input-field">
                <input id="message" type="text" name="msg"  value="Olá, {{ $preview->name }}, clique no link e cadastre-se para acompanhar seus pedidos: http://pedidos.opycos.com.br/clientes/whatsapp/{{ $preview->id }}" readonly>
                <label for="msg" style="font-size: 15px; margin-top: 5px;" >Mensagem</label>
              </div>
              
            </div>


<div class="col-md-4">

           <button id="by-link" type="button" onsubmit='disableButton()' title="Gerar Link" class="btn waves-effect waves-light">Gerar_Link<i class="material-icons right" style="font-size: 25px; margin-top: -7px;">insert_link</i></button>
            <!--<button  disable-on-click data-disable-text="Gerado!" id="by-popup" title="Gerar Link" type="button" class="btn btn-info">PopUp</button>-->

            <div id="console-container">
              
              
            </div>
          </div>

            <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.bundle.min.js"></script>
          </div>
        </div>
      </div>
      <script type="text/javascript">

                $("#by-link").click(function(){
    //do need full
    $(this).fadeOut();
})
        
        let phone = document.getElementById('phone')
        let message = document.getElementById('message')

// buttons
let linkHandler = document.getElementById('by-link')
let popUpHandler = document.getElementById('by-popup')

// font: 
let isMobile = (function(a) {
  if ( /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)) ) {
    return true
  } else {
    return false
  }
})(navigator.userAgent || navigator.vendor || window.opera)

const makeLink = function(mode) {

  let mount = function() {
    if ( isMobile ) {
      let target = `whatsapp://send?`
      if ( !!phone && phone.value !== '' ) {
        target += `phone=${encodeURIComponent(phone.value)}&`
      }
      if ( !!message && message.value !== '' ) {
        target += `text=${encodeURIComponent(message.value)}`
      }
      return target
    } else {
      let target = `https://api.whatsapp.com/send?`
      if ( !!phone && phone.value !== '' ) {
        target += `phone=${encodeURIComponent(phone.value)}&`
      }
      if ( !!message && message.value !== '' ) {
        target += `text=${encodeURIComponent(message.value)}`
      }
      return target
    }
    
  }

  let openLink = function() {
    $('#console-container').append(`<p style="color: #9e9e9e; font-size: 15px; margin-left: 4px; margin-top: 5px;"><b>Link de cadastro pronto para ser enviado</b></p>
     <a href="${mount()}" target="_blank" title="Envia o Link ao número de celular cadastrado." onclick="setTimeout(myFunction, 3000);" class="btn waves-effect waves-light pull-center">Enviar<i class="medium material-icons right" style="font-size: 25px; margin-top: -7px;">send</i></a>`)
  
  }



  let openPopUp = function() {
    let h = 650,
    w = 550,
    l = Math.floor(((screen.availWidth || 1024) - w) / 2),
    t = Math.floor(((screen.availHeight || 700) - h) / 2)
        // open popup
        let options = `height=600,width=550,top=${t},left=${l},location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=0`
        $('#console-container').append(`<a href="${mount()}" target="_blank" title="Envia o Link ao número de celular cadastrado ou a mais de um número caso cliente possua outros números de celular." class="btn waves-effect pull-center">PopUp</a><br><br>`)
      }
      
      switch (mode) {
        case 'link':
        openLink()
        break
        case 'popup':
        openPopUp()
        break
      }
    } 




// events handler(s)
linkHandler.addEventListener('click', function(e) {
  makeLink('link')
}, false)
popUpHandler.addEventListener('click', function(e) {
  makeLink('popup')
}, false)


   function myFunction() {
        document.getElementById("myCheck").click();
      }
</script>


<form method="POST" action="{{ route('concluirI.Link.Whats') }}">
  {{ csrf_field() }}
  <input type="hidden" name="preview_id" value="{{ $preview->preview_id }}">
  <!--<a href="{{ route('cliente.linkInterWhatsapp') }}" title="Limpar formulário" class="btn btn-default">
      <b>Restaurar</b>
    </a> 
        <a href="{{ redirect()->back()->getTargetUrl() }}" class="btn btn-default">
          <b>Voltar</b>
        </a>-->
  <button type="submit" class="btn waves-effect blue darken-2" id="myCheck" style="display: none;">
   <strong>Concluir</strong>
 </button> 
  
</form>

@empty
<div class="col-md-3" style="margin-bottom: -10px;">
<input type="hidden" name="status" value="E"> 


         <div class="input-field">
          <input type="text" name="cel" pattern="\([0-9]{2}\) [0-9]{4,5}-[0-9]{4,4}$" maxlength="15" minlength="14" class="phone" title="Digite apenas números!.Caracteres especiais incluídos automaticamente. Ex:(11) 98768-7896 Não é necessario incluir o número '0' Zero, afrente do código de área." value="{{ old('cel') }}" placeholder="ddd+ celular" autofocus required="required">
          <label for="cel" style="font-size: 15px;">Celular</label>
        </div>  



<div class="input-field" style="margin-top: 15px;">
  <input id="nome" onkeypress='mascara( this, soLetras );' type="text" title="Campo Obrigatório: Forneça dados válidos A-Z" placeholder="Forneça o nome do cliente" maxlength="33" name="name" value="{{ old('name') }}" required>
  <label for="nome" style="font-size: 15px;">Nome</label>     
</div>

<div class="input-field">
  <input id="email" type="email" placeholder="Forneça o e-mail do cliente" title="opycos@opycos.com.br" name="email" value="{{ old('email') }}"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" > 
  <label for="email" style="font-size: 15px;">Email</label> 
   <input type="hidden" name="password" value="{{ $mypassword }}">
</div>
   @foreach($vendedores as $vendedor)
   <input type="hidden" name="vendedor_id" value="{{ $vendedor->id }}">
   @endforeach
  
<button type="submit" class="btn waves-effect blue darken-2" data-position="top" data-delay="50" data-toggle="tooltip" data-placement="bottom" title="PRÉ-CADASTRO. As informações preliminares serão GRAVADAS, servirão de parametro para perssonalizar a menssagem que será enviada ao CLIENTE." style="margin-top: 10px">
 <b>Pré-Cadastro</b>
</button>
</div>



@endforelse
</div>
</div>
</div>
</div>
</div>
@endsection