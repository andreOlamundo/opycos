 @include('templates.formulario.select', ['select'=>'vendedor_id', 'data'=>$list_vendedores,'attributes' => ['class' => 'form-control form-control-sm', 'placeholder' => "---Selecione o(a) Vendedor(a)---", 'title' => "Pedido pertencente ao Vendedor?",'required' => "required"]])