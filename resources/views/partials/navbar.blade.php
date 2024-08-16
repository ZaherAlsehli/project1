<nav class="site-navbar py-4 js-sticky-header site-navbar-target" role="banner">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <div class="site-logo mr-auto w-25">
                <a href="{{ url('/') }}">OneSchool</a>
            </div>

            <div class="mx-auto text-center">
                <nav class="site-navigation position-relative text-right" role="navigation">
                    <ul class="site-menu main-menu js-clone-nav mx-auto d-none d-lg-block m-0 p-0">
                        <li><a href="#home-section" class="nav-link">Home</a></li>
                        <li><a href="#courses-section" class="nav-link">Courses</a></li>
                        <li><a href="#programs-section" class="nav-link">Programs</a></li>
                        <li><a href="#teachers-section" class="nav-link">Teachers</a></li>
                    </ul>
                </nav>
            </div>

            <div class="ml-auto w-25 d-flex justify-content-end">
                @if(Auth::check())
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('favorites.index') }}">Favorites</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endif
            </div>
        </div>
    </div>
</nav>
