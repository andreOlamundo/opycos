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
          <h2>Nova Categoria</h2>          
          <a href="{{route('categoria.index')}}" 
          class="btn btn-small waves-effect waves-light  blue darken-2 pull-right" style="margin-top: -35px; width: 140px; height: 25px; padding: 2px 1px;">
          <i class="fa fa-bookmark" aria-hidden="true"></i><b> Categorias</b></a> 
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
          action="{{route('categoria.store')}}">
          {{ csrf_field() }}
          <div class="row"> 
            <div class="col-md-2"> 
          <div class="input-field">
            <input type="hidden" name="grup_cod" value="777">
            <input type="hidden" name="grup_desc" value="Grupo">          
               <input type="text" name="grup_categ_cod" placeholder='Código da categoria' 
               title="Código deve conter de dois a cinco dígitos" maxlength="5" minlength="2" value="{{ old('grup_categ_cod') }}" required autofocus>
               <label for="grup_categ_cod" style="font-size: 15px;">Código</label>
             </div>    
            </div>
        <div class="col-md-8">
         <div class="input-field">
           <input type="text" name="grup_desc_categ" value="{{ old('grup_desc_categ') }}" onkeypress='mascara( this, soLetras );' placeholder="Descriçaõ da Categoria" title="Breve Descriçaõ da Categoria" required />
           <label for="grup_desc_categ" style="font-size: 15px;">Descriçaõ da Categoria</label>
         </div>              
       </div>
       <div class="col-md-12">  
        <br>

        <a href="{{route('categoria.index')}}" class="btn btn-default" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; ">
          <b>Voltar</b>
        </a>            
    
        <button type="submit" 
       class="btn waves-effect waves-light  blue darken-2"  style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b>
        Salvar</b>
      </button>
    </div>
  </div>
</form>

           
</div>

</div>
</div>

</div>
</div>
</div>

@endsection