<nav class="navbar navbar-success">
  <div class="container-fluid">
    <div class="navbar-header">
      <button class="navbar-toggler collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand main-title" href="{{ route('home') }}"><h4>Stone MakeUp</h4></a>
    </div>

    <div class="navbar-collapse collapse show" id="bs-example-navbar-collapse-1" style="">
      <p class="navbar-text"></p>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ route('cart-show') }}"><i class="fa fa-shopping-cart"></i></a></li> 
          <li><a href="#">Conocenos</a></li>
          <li><a href="#">Contacto</a></li>

     <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-user"></i><span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#">Iniciar sesion</a></li>
        <li><a href="#">Registrarse</a></li>
       

    </ul>
  </li>
  </ul>
  </div>
</div>
</nav>