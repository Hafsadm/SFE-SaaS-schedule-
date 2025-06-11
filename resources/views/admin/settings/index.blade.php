@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="header-content">
            <h1 class="settings-title">Paramètres Administrateur</h1>
            <p class="settings-subtitle">Configurez votre espace d'administration et personnalisez votre SaaS</p>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="settings-grid">
        <!-- Apparence -->
        <a href="{{ route('admin.settings.appearance') }}" class="settings-card appearance-card">
            <div class="card-icon">
                <i class="fas fa-palette"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Apparence</h2>
                <p class="card-description">Personnalisez les couleurs, le thème et l'apparence de votre SaaS</p>
                <div class="card-features">
                    <span class="feature-tag">Couleurs personnalisées</span>
                    <span class="feature-tag">Thèmes</span>
                </div>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <!-- Système -->
        <a href="{{ route('admin.settings.system') }}" class="settings-card system-card">
            <div class="card-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Système</h2>
                <p class="card-description">Configuration générale, langue et paramètres essentiels</p>
                <div class="card-features">
                    <span class="feature-tag">Langue & Région</span>
                    <span class="feature-tag">Maintenance</span>
                </div>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <!-- Profil utilisateur -->
        <a href="{{ route('profile.edit') }}" class="settings-card profile-card">
            <div class="card-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Profil Admin</h2>
                <p class="card-description">Gérez vos informations personnelles et préférences</p>
                <div class="card-features">
                    <span class="feature-tag">Sécurité</span>
                    <span class="feature-tag">Notifications</span>
                </div>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>

    <!-- Statistiques rapides -->
    <div class="quick-stats">
        <h3 class="stats-title">Aperçu rapide</h3>
        <div class="stats-grid">
            <div class="stat-card theme-stat">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Clair</div>
                    <div class="stat-label">Thème actuel</div>
                </div>
            </div>

            <div class="stat-card language-stat">
                <div class="stat-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Français</div>
                    <div class="stat-label">Langue</div>
                </div>
            </div>

            <div class="stat-card update-stat">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">Aujourd'hui</div>
                    <div class="stat-label">Dernière MAJ</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Variables de couleur */
    :root {
        --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }}; 
        --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }}; 
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --danger: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

    /* En-tête amélioré */
    .settings-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .settings-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .header-content {
        position: relative;
        z-index: 2;
    }

    .settings-title {
        color: var(--text-light);
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .settings-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin: 0;
        font-weight: 300;
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert.success {
        background: linear-gradient(135deg, rgba(93, 187, 99, 0.1) 0%, rgba(93, 187, 99, 0.05) 100%);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert.error {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
        color: var(--danger);
        border-left: 4px solid var(--danger);
    }

    /* Grille des paramètres */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* Cartes de paramètres améliorées */
    .settings-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        position: relative;
        overflow: hidden;
    }

    .settings-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .settings-card:hover::before {
        transform: scaleX(1);
    }

    .settings-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .appearance-card .card-icon {
        background: linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%);
        color: white;
    }

    .system-card .card-icon {
        background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);
        color: white;
    }

    .profile-card .card-icon {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .settings-card:hover .card-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .card-content {
        flex: 1;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0 0 0.75rem 0;
        transition: color 0.3s ease;
    }

    .card-description {
        font-size: 0.95rem;
        color: #64748b;
        margin: 0 0 1rem 0;
        line-height: 1.5;
    }

    .card-features {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .feature-tag {
        background: rgba(10, 46, 46, 0.1);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .card-arrow {
        color: var(--primary);
        font-size: 1.25rem;
        opacity: 0.5;
        transition: all 0.3s ease;
        align-self: center;
    }

    .settings-card:hover .card-arrow {
        opacity: 1;
        transform: translateX(5px);
    }

    /* Statistiques rapides */
    .quick-stats {
        margin-top: 3rem;
    }

    .stats-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
    }

    .theme-stat .stat-icon {
        background: linear-gradient(135deg, #8B5CF6, #EC4899);
    }

    .language-stat .stat-icon {
        background: linear-gradient(135deg, #3B82F6, #06B6D4);
    }

    .update-stat .stat-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-container {
            padding: 1rem;
        }

        .settings-header {
            padding: 1.5rem;
            text-align: center;
        }

        .settings-title {
            font-size: 2rem;
        }

        .settings-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .settings-card {
            padding: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation d'entrée pour les cartes
        const cards = document.querySelectorAll('.settings-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 150);
        });

        // Faire disparaître les alertes après 5 secondes
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);

        // Effet de parallaxe léger sur l'en-tête
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.settings-header');
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            if (header) {
                header.style.transform = `translateY(${rate}px)`;
            }
        });
    });
</script>
@endsection
