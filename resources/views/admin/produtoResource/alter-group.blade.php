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
          <h2>Alterar Categoria</h2>     
           </div>
      </div>

      <div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
        <div class="col-md-12">
                   

          <form method="post" 
          action="{{route('categoria.update', $category->id)}}" 
          enctype="multipart/form-data">
          {!! method_field('put') !!}
          {{ csrf_field() }} 
          <div class="card-panel"  style="height: 120px; margin-top: 0px; margin-bottom: 2px; padding: 12px 10px;">
          <div class="row">
            <div class="col-md-2">              
              <div class="input-field">
              <input type="hidden" name="grup_cod" value="{{$category->grup_cod or old('grup_cod')}}">
               <input type="hidden" name="grup_desc" value="{{$category->grup_desc or old('grup_desc')}}">


               <input type="text" name="grup_categ_cod" placeholder='Código da categoria' 
               title="Código da categoria" value="{{$category->grup_categ_cod or old('grup_categ_cod')}}" autofocus>
               <label for="grup_categ_cod" style="font-size: 15px;">Código</label>
             </div>
           </div>

    <div class="col-md-8">
            <div class="input-field">
              <input type='text'maxlength="191" value="{{$category->grup_desc_categ or old('grup_desc_categ')}}" name="grup_desc_categ" title="Breve Descrição da categoria" required/>
              <label for="grup_desc_categ" style="font-size: 15px;">Descrição da categoria</label>
            </div>
          </div>                  
           

    
          <div class="col-md-12"><br>
            <a href="{{route('categoria.index')}}" class="btn btn-default" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px; ">
          <b>Voltar</b>
        </a>    
            <button type="submit" 
            class="btn waves-effect waves-light  blue darken-2" style="margin-top: -35px; width: 130px; height: 25px; padding: 2px 1px;"><span class="glyphicon glyphicon-floppy-disk"></span><b>
        Salvar</b>
          </button>
        </div>
            </div>
      </div>
    </div>
    </form> 
  </div>
</div>            
</div>
</div>
</div>

@endsection