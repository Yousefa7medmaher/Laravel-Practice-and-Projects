<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-shopping-bag me-2"></i>Web Service
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ url('/') }}">
                       <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                       href="{{ route('products.index') }}">
                       <i class="fas fa-store me-1"></i> Products
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                       <i class="fas fa-th-list me-1"></i> Categories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="#">Electronics</a></li>
                        <li><a class="dropdown-item" href="#">Fashion</a></li>
                        <li><a class="dropdown-item" href="#">Home & Garden</a></li>
                        <li><a class="dropdown-item" href="#">Sports</a></li>
                        <li><a class="dropdown-item" href="#">Beauty</a></li>
                    </ul>
                </li>

                @auth
                    @role('customer')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
                           href="{{ route('purchases.index') }}">
                           <i class="fas fa-box me-1"></i> My Purchases
                        </a>
                    </li>
                    @endrole

                    @hasanyrole('admin|employee')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                           <i class="fas fa-cog me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('products.create') }}">
                                    <i class="fas fa-plus me-1"></i> Add Product
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.manage') }}">
                                    <i class="fas fa-users-cog me-1"></i> Manage Users
                                </a>
                            </li>
                            @role('admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('employees.index') }}">
                                    <i class="fas fa-user-tie me-1"></i> Employees
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                    <i class="fas fa-clipboard-list me-1"></i> Orders
                                </a>
                            </li>
                            @endrole
                        </ul>
                    </li>
                    @endhasanyrole
                @endauth
            </ul>

            <ul class="navbar-nav">
                @role('customer')
                <li class="nav-item position-relative me-3">
                    <a class="nav-link" href="{{ route('cart.show') }}">
                        <i class="fas fa-shopping-cart"></i>
                        @php $cartCount = session('cart') ? collect(session('cart'))->sum('quantity') : 0; @endphp
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>
                @endrole

                @guest
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary btn-sm px-3 me-2" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                @else
                    @role('customer')
                    <li class="nav-item me-3">
                        <span class="nav-link credit-balance">
                            <i class="fas fa-wallet me-1"></i> ${{ number_format(auth()->user()->getCreditBalance(), 2) }}
                        </span>
                    </li>
                    @endrole
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.profile') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Navbar Spacer -->
<div style="height: 76px;"></div>
