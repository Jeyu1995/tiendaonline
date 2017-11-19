<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand main-title" href="http://localhost:8000/admin/home"><h4>Stone MakeUp</h4></a>
    </div>
    <div class="navbar-collapse collapse show" id="bs-example-navbar-collapse-1">
      <p class="navbar-text"><i class="fa fa-dashboard"></i></p>
        <ul class="nav navbar-nav navbar-right">
        <li><a href="http://localhost:8000/admin/home">Admin home</a></li>

          <li><a href="{{ route('admin.category.index') }}">Categorias</a></li>
        <li><a href="#">Productos</a></li>
        <li><a href="#">Pedidos</a></li>
        <li><a href="#">Usuarios</a></li>
            <li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
			<i class="fa fa-user"></i>   {{ Auth::user()->name }}<span class="caret"></span>
		</a>
		<ul class="dropdown-menu" role="menu">
			<li><a href="{{ route('logout') }}">Finalizar sesión</a></li>
		</ul>
	</li>    
  </ul>
  </div>
</div>
</nav>