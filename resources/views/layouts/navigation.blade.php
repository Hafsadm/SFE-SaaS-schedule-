<nav class="main-nav">
    <div class="nav-container">
        <div class="nav-content">
            <!-- Section Gauche : Logo + Titre -->
            <div class="nav-left">
                <a href="{{ route('dashboard') }}" class="brand-link">
                    @if(Auth::check() && Auth::user()->logo_url)
                        <img src="{{ Auth::user()->logo_url }}" alt="Logo" class="brand-logo">
                    @else
                        <i class="fas fa-store brand-icon"></i>
                    @endif
                    <span class="brand-title">{{ Auth::user()->website_name ?? 'Horaire' }}</span>
                </a>
            </div>

            <!-- Section Centre : Menu Principal -->
            <div class="nav-center">
                @auth
                    <div class="main-menu">
                        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Tableau de bord</span>
                        </a>
                        <a href="{{ route('admin.stores.index') }}" class="menu-item {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                            <i class="fas fa-store"></i>
                            <span>Point de vente</span>
                        </a>
                        <a href="{{ route('admin.schedules.dashboard') }}" class="menu-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                            <i class="fas fa-clock"></i>
                            <span>Horaire</span>
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Section Droite : Profil + Actions -->
            <div class="nav-right">
                @auth
                    <div class="user-section">
                        <div class="user-dropdown">
                            <button class="user-button" id="userDropdownToggle">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-info">
                                    <span class="user-name">{{ Auth::user()->name }}</span>
                                    <span class="user-role">Administrateur</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            
                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">
                                    <div class="user-details">
                                        <strong>{{ Auth::user()->name }}</strong>
                                        <small>{{ Auth::user()->email }}</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Mon profil</span>
                                </a>
                                <a href="{{ route('admin.settings.index') }}" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    <span>Paramètres</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-item">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="auth-section">
                        <a href="{{ route('login') }}" class="auth-btn login-btn">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Connexion</span>
                        </a>
                        <a href="{{ route('register') }}" class="auth-btn register-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Inscription</span>
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Menu Mobile Toggle -->
            <button class="mobile-toggle" id="mobileToggle">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>

        <!-- Menu Mobile -->
        <div class="mobile-menu" id="mobileMenu">
            @auth
                <div class="mobile-user-info">
                    <div class="mobile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="mobile-user-details">
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ Auth::user()->email }}</small>
                    </div>
                </div>
                <div class="mobile-menu-items">
                    <a href="{{ route('dashboard') }}" class="mobile-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('admin.stores.index') }}" class="mobile-menu-item {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                        <i class="fas fa-store"></i>
                        <span>Point de vente</span>
                    </a>
                    <a href="{{ route('admin.schedules.dashboard') }}" class="mobile-menu-item {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <span>Horaire</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="mobile-menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </a>
                    <div class="mobile-menu-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="mobile-menu-item">
                        <i class="fas fa-user-circle"></i>
                        <span>Mon profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="mobile-menu-item logout-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="mobile-auth">
                    <a href="{{ route('login') }}" class="mobile-auth-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Connexion</span>
                    </a>
                    <a href="{{ route('register') }}" class="mobile-auth-btn register">
                        <i class="fas fa-user-plus"></i>
                        <span>Inscription</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<style>
/* Navigation principale avec layout en 3 sections */
.main-nav {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.nav-content {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    align-items: center;
    height: 75px;
    gap: 2rem;
}

/* Section Gauche - Logo + Titre */
.nav-left {
    display: flex;
    align-items: center;
}

.brand-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    color: var(--text-light);
    transition: all 0.3s ease;
}

.brand-link:hover {
    color: var(--light);
    transform: translateY(-1px);
}

.brand-logo {
    height: 45px;
    width: auto;
    max-width: 120px;
    object-fit: contain;
    border-radius: 8px;
}

.brand-icon {
    font-size: 2rem;
    color: var(--light);
}

.brand-title {
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

/* Section Centre - Menu Principal */
.nav-center {
    display: flex;
    justify-content: center;
}

.main-menu {
    display: flex;
    gap: 0.5rem;
    background: rgba(255, 255, 255, 0.1);
    padding: 0.5rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.menu-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    color: var(--text-light);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    white-space: nowrap;
    position: relative;
}

.menu-item:hover {
    background: rgba(255, 255, 255, 0.15);
    color: var(--light);
    transform: translateY(-2px);
}

.menu-item.active {
    background: rgba(255, 255, 255, 0.2);
    color: var(--light);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.menu-item i {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

/* Section Droite - Profil */
.nav-right {
    display: flex;
    justify-content: flex-end;
}

.user-section {
    display: flex;
    align-items: center;
}

.user-dropdown {
    position: relative;
}

.user-button {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: var(--text-light);
    padding: 0.5rem 1rem;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.user-button:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
}

.user-avatar {
    width: 35px;
    height: 35px;
    background: var(--light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.1rem;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-align: left;
}

.user-name {
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.2;
}

.user-role {
    font-size: 0.8rem;
    opacity: 0.8;
    line-height: 1.2;
}

.dropdown-arrow {
    font-size: 0.8rem;
    transition: transform 0.3s ease;
}

.user-dropdown.active .dropdown-arrow {
    transform: rotate(180deg);
}

/* Dropdown Menu */
.dropdown-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 15px);
    background: white;
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    padding: 0;
    min-width: 280px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    overflow: hidden;
}

.user-dropdown.active .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.dropdown-header {
    padding: 1.25rem;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

.user-details strong {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.user-details small {
    opacity: 0.9;
    font-size: 0.85rem;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    text-decoration: none;
    color: var(--primary);
    transition: all 0.3s ease;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
}

.dropdown-item:hover {
    background: rgba(10, 46, 46, 0.08);
    color: var(--secondary);
}

.dropdown-item i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

.dropdown-divider {
    height: 1px;
    background: var(--border);
    margin: 0.5rem 0;
}

.logout-item {
    color: #dc3545;
}

.logout-item:hover {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* Section Auth (non connecté) */
.auth-section {
    display: flex;
    gap: 0.75rem;
}

.auth-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.login-btn {
    color: var(--text-light);
    background: rgba(255, 255, 255, 0.1);
}

.login-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    color: var(--light);
}

.register-btn {
    color: var(--primary);
    background: var(--light);
}

.register-btn:hover {
    background: white;
    transform: translateY(-2px);
}

/* Menu Mobile Toggle */
.mobile-toggle {
    display: none;
    flex-direction: column;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    z-index: 1001;
}

.hamburger-line {
    width: 25px;
    height: 3px;
    background-color: var(--text-light);
    margin: 3px 0;
    transition: 0.3s;
    border-radius: 2px;
}

.mobile-toggle.active .hamburger-line:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.mobile-toggle.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-toggle.active .hamburger-line:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

/* Menu Mobile */
.mobile-menu {
    display: none;
    position: fixed;
    top: 75px;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 1.5rem;
    transform: translateY(-100%);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    max-height: calc(100vh - 75px);
    overflow-y: auto;
}

.mobile-menu.active {
    transform: translateY(0);
    opacity: 1;
    visibility: visible;
}

.mobile-user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    margin-bottom: 1.5rem;
}

.mobile-avatar {
    width: 50px;
    height: 50px;
    background: var(--light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.5rem;
}

.mobile-user-details {
    color: white;
}

.mobile-user-details strong {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.mobile-user-details small {
    opacity: 0.9;
    font-size: 0.85rem;
}

.mobile-menu-items {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mobile-menu-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    color: var(--text-light);
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    background: none;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
}

.mobile-menu-item:hover,
.mobile-menu-item.active {
    background: rgba(255, 255, 255, 0.15);
    color: var(--light);
}

.mobile-menu-item i {
    width: 25px;
    text-align: center;
    font-size: 1.2rem;
}

.mobile-menu-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
    margin: 1rem 0;
}

.mobile-auth {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.mobile-auth-btn {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    color: var(--text-light);
    background: rgba(255, 255, 255, 0.1);
}

.mobile-auth-btn.register {
    background: var(--light);
    color: var(--primary);
}

.mobile-auth-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .nav-content {
        grid-template-columns: 1fr 1.5fr 1fr;
        gap: 1rem;
    }
    
    .main-menu {
        gap: 0.25rem;
    }
    
    .menu-item {
        padding: 0.75rem 1rem;
    }
    
    .user-info {
        display: none;
    }
}

@media (max-width: 992px) {
    .nav-content {
        grid-template-columns: 1fr auto;
        gap: 1rem;
    }
    
    .nav-center,
    .nav-right {
        display: none;
    }
    
    .mobile-toggle {
        display: flex;
    }
    
    .mobile-menu {
        display: block;
    }
}

@media (max-width: 480px) {
    .nav-container {
        padding: 0 1rem;
    }
    
    .nav-content {
        height: 65px;
    }
    
    .brand-logo {
        height: 35px;
    }
    
    .brand-title {
        font-size: 1.4rem;
    }
    
    .mobile-menu {
        top: 65px;
        padding: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du menu mobile
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
        
        // Fermer le menu mobile quand on clique sur un lien
        const mobileMenuItems = mobileMenu.querySelectorAll('.mobile-menu-item, .mobile-auth-btn');
        mobileMenuItems.forEach(item => {
            item.addEventListener('click', () => {
                mobileToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            });
        });
    }
    
    // Gestion du dropdown utilisateur
    const userDropdownToggle = document.getElementById('userDropdownToggle');
    const userDropdown = userDropdownToggle?.closest('.user-dropdown');
    
    if (userDropdownToggle && userDropdown) {
        userDropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        // Fermer le dropdown quand on clique ailleurs
        document.addEventListener('click', function() {
            userDropdown.classList.remove('active');
        });
        
        // Empêcher la fermeture quand on clique dans le dropdown
        const dropdownMenu = document.getElementById('userDropdownMenu');
        if (dropdownMenu) {
            dropdownMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    }
    
    // Fermer les menus quand on redimensionne la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            mobileToggle?.classList.remove('active');
            mobileMenu?.classList.remove('active');
        }
        
        if (userDropdown) {
            userDropdown.classList.remove('active');
        }
    });
});
</script>
