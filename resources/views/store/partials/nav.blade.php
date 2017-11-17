<nav class="navbar navbar-success">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggler collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand main-title" href="{{ route('home') }}"><h4>Stone MakeUp</h4></a>
    </div>

    <div class="navbar-collapse collapse show" id="bs-example-navbar-collapse-1">
      <p class="navbar-text"></p>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ route('cart-show') }}"><i class="fa fa-shopping-cart"></i></a></li> 
          <li><a href="#">Conocenos</a></li>
          <li><a href="#">Contacto</a></li>
          @include('store.partials.menu-user')
    
  </ul>
  </div>
</div>
</nav>