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
					<h2>Novo Administrator</h2>     
				</div>
			</div>

			<div class="row" style="height: 50px; width: 1170px; position: fixed; z-index: 1001; top: 100px; ">
				<div class="col-md-12">
					<!--<ol class="breadcrumb" style="margin-bottom: 5px;">                          
						<li><a href="{{route('admins.index')}}" id="btn" style="text-decoration: none"><b>Administradores</b></a></li>
							<li class="active">Cadastro</li>
						</ol>-->

						@if (session('message'))
						<div class="alert alert-success alert-dismissible fade in" style="margin-bottom: 1px;">
							<a href="#" class="close" 
							data-dismiss="alert"
							aria-label="close">&times;</a>
							<b> {{ session('message') }}</b>
						</div>
						<script type="text/javascript">
							$(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
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
							$(".alert-dismissible").fadeTo(7000, 500).slideUp(500, function(){
								$(".alert-dismissible").alert('close');
							});
						</script>
						@endif


						<div class="fixed-action-btn">
							<a class="btn-floating btn-large lighten-2" title="Alterar Perfil">
								<i class="large material-icons">mode_edit</i>
							</a>
							<ul>
								<li><a href="{{route('admins.create')}}" class="btn-floating red" title="Administrador"><i class="material-icons">assignment_ind</i></a></li>
								<li><a href="{{route('vendedores.create')}}" class="btn-floating yellow" title="Vendedor"><i class="material-icons">account_box</i></a></li>
								<li>

									<a href="{{route('clientes.create')}}" class="btn-floating green" title="Cliente"><i class="material-icons">perm_identity</i></a></li>

								</ul>

							</div>    

							<div class="card-panel" style="height: 240px; margin-top: 2px; margin-bottom: 2px; padding: 12px 10px;">
								<div class="row">           
									<form method="post" 
									action="{{ route('admins.store') }}" 
									enctype="multipart/form-data">
									{{ csrf_field() }}
									<div class="col-md-4">

										<div class="input-field">
											<input id="nome" onkeypress='mascara( this, soLetras );' type="text" title="'Campo obrigatório'" maxlength="64" name="name" value="{{ old('name') }}" required placeholder="Nome do Usuário">
											<label for="nome" style="font-size: 15px;">Nome</label>     
										</div>

										<div class="input-field">        
											<input pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" placeholder="(00) 90000-0001" minlength="15" type="tel" title="'Campo obrigatório' (00)00000-0000" onkeypress='mascara( this, mtel );' maxlength="15" name="cel" value="{{ old('cel') }}" style="margin-top: 10px;" required />    <label for="cel" style="font-size: 15px; margin-top: 10px;">ddd + Celular</label>
										</div>

										<div class="input-field">        
											<input title="(00) 1000-0001" onkeypress='mascara( this, mtel );' type="tel"  pattern="\([0-9]{2}\) [0-9]{4,6}-[0-9]{3,4}$" placeholder="(00) 1000-0001"  minlength="14" maxlength="14" name="tel" value="{{ old('tel') }}"/>
											<label for="tel" style="font-size: 15px;">ddd + Telefone</label>
										</div>

										<div class="input-field">
											<input type="text" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" minlength="14" title="'Campo obrigatório' 000.000.000-00" name="cpf" onkeypress='mascara(this,mcpf)' value="{{ old('cpf') }}" maxlength="14" placeholder="Cadastro Pessoa Física" />
											<label for="cpf" style="font-size: 15px;">CPF</label>
										</div>




									</div>
									<div class="col-md-4">

										<div class="input-field">
											<input id="email" type="email" title="'Campo obrigatário' E-mail de acesso" name="email" value="{{ old('email') }}" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" placeholder="E-mail de acesso" required > 
											<label for="email" style="font-size: 15px;">Email</label>
										</div>
										<div class="input-field">
											<input type="password" name="password" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$" minlength="6" title="Senha com no mínimo seis caracteres Alfanuméricos (Letras e números)" required autocomplete="off" placeholder="Senha de acesso"> 
											<label for="password" style="font-size: 15px;">Senha</label>
										</div>

										<div class="input-field">
											<input type="password" name="password_confirmation" title="Repita a Senha" pattern="^(?=.*\d)(?=.*[a-z])(?!.*\s).*$"  minlength="6" required autocomplete="off" placeholder="Repita a Senha"/>
											<label for="password_confirmation" style="font-size: 15px;">Repita Senha</label>
										</div>

										<a href="{{route('admins.index')}}" class="btn btn-default" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; ">
											<b>Voltar</b>
										</a>

										<button type="submit" title="Enviar formulário"	class="btn waves-effect waves-light  blue darken-2" style="margin-top: -20px; width: 130px; height: 25px; padding: 2px 1px; "><span class="glyphicon glyphicon-floppy-disk"></span>
											<b>Salvar</b>
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				var elems = document.querySelectorAll('.fixed-action-btn');
				var instances = M.FloatingActionButton.init(elems, {
					direction: 'left'
				});
			});

  // Or with jQuery

  $(document).ready(function(){
  	$('.fixed-action-btn').floatingActionButton();
  });

  var instance = M.FloatingActionButton.getInstance(elem);
  instance.open();
  instance.close();
  instance.destroy();
</script>



@endsection

