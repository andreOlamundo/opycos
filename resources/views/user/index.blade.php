@extends('templates.master')


@section('css-view')
@endsection

@section('templates.menu-superior')
@endsection

@section('js-view')
@endsection

@section('conteudo-view')

 @if(session('success'))
<div class="alert alert-success alert-dismissible">
    <a href="#" class="close" 
    data-dismiss="alert"
    aria-label="close">&times;</a>{{ session('success')['messages'] }}</div>
 @endif


	{!! Form::open(['route' => 'user.store', 'method' => 'post', 'class' => 'form-padrao']) !!}


		@include('templates.formulario.input', ['input' => 'name', 'attributes' => ['class' => 'form-control login_input', 'placeholder' => "Usuário", 'required' => "required"]])


		@include('templates.formulario.input', ['input' => 'notas', 'attributes' => ['placeholder' => 'Notas', 'required' => 'required']])

		@include('templates.formulario.input', ['input' => 'email', 'attributes' => ['placeholder' => "Email", 'required' => "required"]])

		@include('templates.formulario.password', ['input' => 'password', 'attributes' => ['placeholder' => "Password", 'required' => "required"]])

		@include('templates.formulario.submit', ['input' => 'Cadastrar'])
		

	{!! Form::close() !!}


@endsection
