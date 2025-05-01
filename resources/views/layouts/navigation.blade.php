

<nav class="main-nav">
    <div class="nav-container">
        <div class="nav-content">
            <div class="nav-logo">
                <a href="{{ route('dashboard') }}" class="logo-link">
                    <i class="fas fa-store"></i> Horaire
                </a>
            </div>

            @auth
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                    <a href="{{ route('admin.stores.index') }}" class="nav-link {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        Points de vente
                    </a>
                </div>

                <div class="user-dropdown">
                    <button class="user-button">
                        <i class="fas fa-user"></i>
                        {{ Auth::user()->name }}
                    </button>
                    
                    <div class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>
                            Mon profil
                        </a>
                  
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-button">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="auth-links">
                    <a href="{{ route('login') }}" class="auth-link">
                        <i class="fas fa-sign-in-alt"></i>
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="auth-link">
                        <i class="fas fa-user-plus"></i>
                        Inscription
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>