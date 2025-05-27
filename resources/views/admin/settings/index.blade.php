@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="header-content">
            <h1 class="settings-title">Paramètres du système</h1>
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
        <a href="{{ route('admin.settings.appearance') }}" class="settings-card">
            <div class="card-icon">
                <i class="fas fa-palette"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Apparence</h2>
                <p class="card-description">Personnalisez les couleurs, le thème et l'apparence générale de l'application.</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <!-- Notifications -->
        {{-- <a href="{{ route('admin.settings.notifications') }}" class="settings-card">
            <div class="card-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Notifications</h2>
                <p class="card-description">Configurez les préférences de notifications par email et dans l'application.</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a> --}}

        <!-- Sécurité -->
        {{-- <a href="{{ route('admin.settings.security') }}" class="settings-card">
            <div class="card-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Sécurité</h2>
                <p class="card-description">Gérez les paramètres de sécurité, l'authentification à deux facteurs et les sessions.</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a> --}}

        <!-- Système -->
        <a href="{{ route('admin.settings.system') }}" class="settings-card">
            <div class="card-icon">
                <i class="fas fa-cogs"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Système</h2>
                <p class="card-description">Configurez les paramètres système, la langue, le fuseau horaire et le mode maintenance.</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <!-- Profil utilisateur -->
        <a href="{{ route('profile.edit') }}" class="settings-card">
            <div class="card-icon">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="card-content">
                <h2 class="card-title">Profil utilisateur</h2>
                <p class="card-description">Modifiez vos informations personnelles, votre mot de passe et vos préférences.</p>
            </div>
            <div class="card-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

<style>
   <style>
    /* Variables de couleur - Nouvelle palette */
    :root {
        --primary: #0A2E2E; 
        --secondary: #2A6363; 
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


    /* Base */
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .settings-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 30px 0;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .settings-title {
        color: var(--text-light);
        font-size: 1.8rem;
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .settings-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background-color: var(--light);
        border-radius: 2px;
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 20px 0;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert.success {
        background-color: rgba(93, 187, 99, 0.1);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert.error {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    /* Grille des paramètres */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    /* Cartes de paramètres */
    .settings-card {
        background-color: var(--light);
        border-radius: 20px 0;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
        box-shadow: var(--card-shadow); 
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .settings-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(27, 68, 68, 0.15);
        background-color: rgba(14, 26, 26, 0.02);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px 0;
        background-color: var(--secondary);
        color: var(--text-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .card-content {
        flex: 1;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0 0 0.5rem 0;
    }

    .card-description {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }

    .card-arrow {
        color: var(--primary);
        font-size: 1.25rem;
        opacity: 0.5;
        transition: all 0.2s ease;
    }

    .settings-card:hover .card-arrow {
        opacity: 1;
        transform: translateX(3px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .settings-title::before {
            display: none;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .settings-card {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .card-title {
            color: var(--text-light);
        }
        
        .card-description {
            color: #aaa;
        }
        
        .settings-card:hover {
            background-color: rgba(9, 41, 41, 0.3);
        }
        
        .card-arrow {
            color: var(--text-light);
        }
    }
</style>

<script>
    // Faire disparaître les alertes après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 5000);
    });
</script>
@endsection
