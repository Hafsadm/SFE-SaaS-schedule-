@extends('layouts.app')

@section('title', 'Paramètres d\'apparence')

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="header-content">
            <h1 class="settings-title">Paramètres d'apparence</h1>
            <div class="header-actions">
                <a href="{{ route('admin.settings.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux paramètres
                </a>
            </div>
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

    <!-- Formulaire des paramètres d'apparence -->
    <div class="settings-form-container">
        <form action="{{ route('admin.settings.appearance.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
            @csrf
            
            <div class="form-section">
                <h2 class="section-title">Thème</h2>
                
                <div class="theme-options">
                    <div class="theme-option">
                        <input type="radio" name="theme" id="theme-light" value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'checked' : '' }}>
                        <label for="theme-light" class="theme-label light">
                            <div class="theme-preview light"></div>
                            <span>Clair</span>
                        </label>
                    </div>
                    
                    <div class="theme-option">
                        <input type="radio" name="theme" id="theme-dark" value="dark" {{ ($settings['theme'] ?? '') === 'dark' ? 'checked' : '' }}>
                        <label for="theme-dark" class="theme-label dark">
                            <div class="theme-preview dark"></div>
                            <span>Sombre</span>
                        </label>
                    </div>
                    
                    <div class="theme-option">
                        <input type="radio" name="theme" id="theme-auto" value="auto" {{ ($settings['theme'] ?? '') === 'auto' ? 'checked' : '' }}>
                        <label for="theme-auto" class="theme-label auto">
                            <div class="theme-preview auto"></div>
                            <span>Automatique</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2 class="section-title">Couleurs</h2>
                
                <div class="form-group">
                    <label for="primary_color">Couleur principale</label>
                    <div class="color-picker-container">
                        <input type="color" id="primary_color" name="primary_color" value="{{ $settings['primary_color'] ?? '#0A2E2E' }}">
                        <input type="text" id="primary_color_text" value="{{ $settings['primary_color'] ?? '#0A2E2E' }}" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="secondary_color">Couleur secondaire</label>
                    <div class="color-picker-container">
                        <input type="color" id="secondary_color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#2A6363' }}">
                        <input type="text" id="secondary_color_text" value="{{ $settings['secondary_color'] ?? '#2A6363' }}" readonly>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="accent_color">Couleur d'accent</label>
                    <div class="color-picker-container">
                        <input type="color" id="accent_color" name="accent_color" value="{{ $settings['accent_color'] ?? '#8E6E53' }}">
                        <input type="text" id="accent_color_text" value="{{ $settings['accent_color'] ?? '#8E6E53' }}" readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2 class="section-title">Logo</h2>
                
                <div class="form-group">
                    <label for="logo">Logo de l'application</label>
                    <div class="logo-upload-container">
                        <div class="current-logo">
                            @if(isset($settings['logo']))
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo actuel">
                            @else
                                <div class="no-logo">
                                    <i class="fas fa-image"></i>
                                    <span>Aucun logo</span>
                                </div>
                            @endif
                        </div>
                        <div class="logo-upload">
                            <label for="logo" class="upload-button">
                                <i class="fas fa-upload"></i>
                                Choisir un fichier
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*">
                            <span class="file-info">PNG, JPG ou SVG. Max 2MB.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="save-button">
                    <i class="fas fa-save"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --primary: #0A2E2E; 
        --secondary: #2A6363; 
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --warning: #f59e0b;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }

    /* Base */
    .settings-container {
        max-width: 1000px;
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

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        border-radius: 30px 0;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
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

    /* Formulaire */
    .settings-form-container {
        background-color: var(--text-light);
        border-radius: 30px 0;
        padding: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-size: 1.25rem;
        color: var(--primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border);
    }

    /* Options de thème */
    .theme-options {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .theme-option {
        position: relative;
    }

    .theme-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .theme-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
    }

    .theme-preview {
        width: 120px;
        height: 80px;
        border-radius: 10px 0;
        border: 2px solid transparent;
        transition: all 0.2s ease;
        overflow: hidden;
        position: relative;
    }

    .theme-preview.light {
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .theme-preview.light::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 20px;
        background-color: var(--primary);
    }

    .theme-preview.dark {
        background-color: #0A2E2E;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .theme-preview.dark::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 20px;
        background-color: #2A6363;
    }

    .theme-preview.auto {
        background: linear-gradient(to right, #ffffff 50%, #0A2E2E 50%);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .theme-preview.auto::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 20px;
        background: linear-gradient(to right, var(--primary) 50%, #2A6363 50%);
    }

    .theme-option input[type="radio"]:checked + .theme-label .theme-preview {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(10, 46, 46, 0.2);
    }

    /* Groupes de formulaire */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--primary);
    }

    /* Sélecteurs de couleur */
    .color-picker-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .color-picker-container input[type="color"] {
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 10px 0;
        cursor: pointer;
        padding: 0;
        background: none;
    }

    .color-picker-container input[type="text"] {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 10px 0;
        font-family: monospace;
        width: 100px;
        text-align: center;
    }

    /* Upload de logo */
    .logo-upload-container {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .current-logo {
        width: 120px;
        height: 120px;
        border-radius: 10px 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
        border: 1px solid var(--border);
    }

    .current-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .no-logo {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        color: #999;
    }

    .no-logo i {
        font-size: 2rem;
    }

    .logo-upload {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .upload-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 20px 0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .upload-button:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .logo-upload input[type="file"] {
        display: none;
    }

    .file-info {
        font-size: 0.85rem;
        color: #666;
    }

    /* Actions du formulaire */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
    }

    .save-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .save-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(10, 46, 46, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .settings-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .theme-options {
            justify-content: center;
        }

        .logo-upload-container {
            flex-direction: column;
            align-items: center;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .settings-form-container {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .section-title {
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .form-group label {
            color: var(--text-light);
        }
        
        .color-picker-container input[type="text"] {
            background-color: #0A2E2E;
            color: var(--text-light);
            border-color: #2A6363;
        }
        
        .current-logo {
            background-color: #121f1f;
            border-color: #2A6363;
        }
        
        .file-info {
            color: #aaa;
        }
        
        .form-actions {
            border-color: #2A6363;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Synchroniser les valeurs des sélecteurs de couleur avec les champs texte
        const colorInputs = document.querySelectorAll('input[type="color"]');
        colorInputs.forEach(input => {
            const textInput = document.getElementById(input.id + '_text');
            
            input.addEventListener('input', function() {
                textInput.value = input.value;
            });
        });
        
        // Faire disparaître les alertes après 5 secondes
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
        
        // Afficher le nom du fichier sélectionné pour le logo
        const logoInput = document.getElementById('logo');
        const fileInfo = document.querySelector('.file-info');
        
        logoInput.addEventListener('change', function() {
            if (logoInput.files.length > 0) {
                fileInfo.textContent = logoInput.files[0].name;
            } else {
                fileInfo.textContent = 'PNG, JPG ou SVG. Max 2MB.';
            }
        });
    });
</script>
@endsection
