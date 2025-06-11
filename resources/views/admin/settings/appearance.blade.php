@extends('layouts.app')

@section('title', 'Paramètres d\'apparence')

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="header-content">
            <h1 class="settings-title">Paramètres d'apparence</h1>
            <p class="settings-subtitle">Personnalisez l'apparence de votre SaaS pour vos utilisateurs et votre interface admin</p>
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

    @if($errors->any())
        <div class="alert error">
            <i class="fas fa-exclamation-circle"></i>
            <ul style="margin: 0; padding-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire des paramètres d'apparence -->
    <div class="settings-form-container">
        <form action="{{ route('admin.settings.appearance.update') }}" method="POST" enctype="multipart/form-data" class="settings-form" id="appearanceForm">
            @csrf
            
            <div class="form-sections">
                <!-- Section Logo -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-image"></i>
                        Logo de votre SaaS
                    </h2>
                    
                    <div class="logo-section">
                        <div class="logo-preview">
                            @if(isset($settings['logo']) && $settings['logo'])
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo actuel" id="logoPreview">
                            @else
                                <div class="no-logo" id="noLogoPlaceholder">
                                    <i class="fas fa-image"></i>
                                    <span>Aucun logo</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="logo-upload">
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <h3>Glissez votre logo ici</h3>
                                <p>ou cliquez pour sélectionner</p>
                                <span class="file-types">PNG, JPG, SVG (max 2MB)</span>
                                <input type="file" id="logo" name="logo" accept="image/*" hidden>
                            </div>
                            <div class="upload-info" id="uploadInfo" style="display: none;">
                                <i class="fas fa-check-circle"></i>
                                <span id="fileName"></span>
                                <button type="button" id="removeFile" class="remove-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Thème -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-moon"></i>
                        Thème de l'interface
                    </h2>
                    
                    <div class="theme-options">
                        <div class="theme-option">
                            <input type="radio" name="theme" id="theme-light" value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'checked' : '' }}>
                            <label for="theme-light" class="theme-label">
                                <div class="theme-preview light">
                                    <div class="theme-header"></div>
                                    <div class="theme-content">
                                        <div class="theme-sidebar"></div>
                                        <div class="theme-main"></div>
                                    </div>
                                </div>
                                <div class="theme-info">
                                    <i class="fas fa-sun"></i>
                                    <span>Thème Clair</span>
                                    <small>Interface claire et moderne</small>
                                </div>
                            </label>
                        </div>
                        
                        <div class="theme-option">
                            <input type="radio" name="theme" id="theme-dark" value="dark" {{ ($settings['theme'] ?? '') === 'dark' ? 'checked' : '' }}>
                            <label for="theme-dark" class="theme-label">
                                <div class="theme-preview dark">
                                    <div class="theme-header"></div>
                                    <div class="theme-content">
                                        <div class="theme-sidebar"></div>
                                        <div class="theme-main"></div>
                                    </div>
                                </div>
                                <div class="theme-info">
                                    <i class="fas fa-moon"></i>
                                    <span>Thème Sombre</span>
                                    <small>Idéal pour les yeux</small>
                                </div>
                            </label>
                        </div>
                        
                        <div class="theme-option">
                            <input type="radio" name="theme" id="theme-auto" value="auto" {{ ($settings['theme'] ?? '') === 'auto' ? 'checked' : '' }}>
                            <label for="theme-auto" class="theme-label">
                                <div class="theme-preview auto">
                                    <div class="theme-header"></div>
                                    <div class="theme-content">
                                        <div class="theme-sidebar"></div>
                                        <div class="theme-main"></div>
                                    </div>
                                </div>
                                <div class="theme-info">
                                    <i class="fas fa-adjust"></i>
                                    <span>Automatique</span>
                                    <small>Suit les préférences système</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section Couleurs -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-palette"></i>
                        Palette de couleurs personnalisée
                    </h2>
                    
                    <!-- Palettes prédéfinies -->
                    <div class="color-presets">
                        <h3>Palettes prédéfinies</h3>
                        <div class="presets-grid">
                            <button type="button" class="preset-option" data-primary="#0A2E2E" data-secondary="#2A6363" data-accent="#8E6E53">
                                <div class="preset-colors">
                                    <span style="background: #0A2E2E;"></span>
                                    <span style="background: #2A6363;"></span>
                                    <span style="background: #8E6E53;"></span>
                                </div>
                                <span class="preset-name">Océan</span>
                            </button>
                            
                            <button type="button" class="preset-option" data-primary="#1B4332" data-secondary="#2D5A3D" data-accent="#52B788">
                                <div class="preset-colors">
                                    <span style="background: #1B4332;"></span>
                                    <span style="background: #2D5A3D;"></span>
                                    <span style="background: #52B788;"></span>
                                </div>
                                <span class="preset-name">Forêt</span>
                            </button>
                            
                            <button type="button" class="preset-option" data-primary="#8B2635" data-secondary="#A53860" data-accent="#F4A261">
                                <div class="preset-colors">
                                    <span style="background: #8B2635;"></span>
                                    <span style="background: #A53860;"></span>
                                    <span style="background: #F4A261;"></span>
                                </div>
                                <span class="preset-name">Coucher</span>
                            </button>
                            
                             <button type="button" class="preset-option" data-primary="#000000" data-secondary="#4B4B4B" data-accent="#FFFFFF">
                            <div class="preset-colors">
                                <span style="background: #000000;"></span>
                                <span style="background: #4B4B4B;"></span>
                                <span style="background: #FFFFFF; border: 1px solid #ccc;"></span>
                            </div>
                            <span class="preset-name">Noir & Blanc</span>
                        </button>
                        </div>
                    </div>
                    
                    <!-- Couleurs personnalisées -->
                    <div class="custom-colors">
                        <h3>Couleurs personnalisées</h3>
                        <div class="colors-grid">
                            <div class="color-group">
                                <label for="primary_color">Couleur principale</label>
                                <div class="color-input-group">
                                    <input type="color" id="primary_color" name="primary_color" value="{{ $settings['primary_color'] ?? '#0A2E2E' }}">
                                    <input type="text" id="primary_color_text" value="{{ $settings['primary_color'] ?? '#0A2E2E' }}" readonly>
                                    <div class="color-preview" id="primary_preview"></div>
                                </div>
                                <small>Couleur principale de votre interface</small>
                            </div>
                            
                            <div class="color-group">
                                <label for="secondary_color">Couleur secondaire</label>
                                <div class="color-input-group">
                                    <input type="color" id="secondary_color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#2A6363' }}">
                                    <input type="text" id="secondary_color_text" value="{{ $settings['secondary_color'] ?? '#2A6363' }}" readonly>
                                    <div class="color-preview" id="secondary_preview"></div>
                                </div>
                                <small>Couleur pour les éléments secondaires</small>
                            </div>
                            
                            <div class="color-group">
                                <label for="accent_color">Couleur d'accent</label>
                                <div class="color-input-group">
                                    <input type="color" id="accent_color" name="accent_color" value="{{ $settings['accent_color'] ?? '#8E6E53' }}">
                                    <input type="text" id="accent_color_text" value="{{ $settings['accent_color'] ?? '#8E6E53' }}" readonly>
                                    <div class="color-preview" id="accent_preview"></div>
                                </div>
                                <small>Couleur pour les boutons et liens</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Aperçu -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-eye"></i>
                        Aperçu en temps réel
                    </h2>
                    
                    <div class="preview-container">
                        <div class="preview-interface" id="previewInterface">
                            <div class="preview-header">
                                <div class="preview-logo">Logo</div>
                                <div class="preview-nav">
                                    <span>Dashboard</span>
                                    <span>Stores</span>
                                    <span>Settings</span>
                                </div>
                            </div>
                            <div class="preview-content">
                                <div class="preview-sidebar">
                                    <div class="preview-menu-item active">Menu 1</div>
                                    <div class="preview-menu-item">Menu 2</div>
                                    <div class="preview-menu-item">Menu 3</div>
                                </div>
                                <div class="preview-main">
                                    <div class="preview-card">
                                        <h4>Carte exemple</h4>
                                        <p>Contenu de la carte</p>
                                        <button class="preview-button">Action</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="preview-toggle" id="previewToggle">
                    <i class="fas fa-eye"></i>
                    Aperçu complet
                </button>
                <button type="submit" class="save-button" id="saveButton">
                    <i class="fas fa-save"></i>
                    <span>Enregistrer les modifications</span>
                </button>
            </div>
        </form>
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
        --error: #dc3545;
        --warning: #f59e0b;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }

    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

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

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
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

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        border-radius: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Alertes */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 15px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    .alert.success {
        background: linear-gradient(135deg, rgba(93, 187, 99, 0.1) 0%, rgba(93, 187, 99, 0.05) 100%);
        color: var(--success);
        border-left: 4px solid var(--success);
    }

    .alert.error {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    .settings-form-container {
        background: white;
        border-radius: 25px;
        padding: 2.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .form-sections {
        display: grid;
        gap: 3rem;
    }

    .form-section {
        position: relative;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    /* Section Logo */
    .logo-section {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 2rem;
        align-items: start;
    }

    .logo-preview {
        width: 200px;
        height: 200px;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border: 2px dashed var(--border);
        transition: all 0.3s ease;
    }

    .logo-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .no-logo {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        color: #94a3b8;
        text-align: center;
    }

    .no-logo i {
        font-size: 3rem;
    }

    .upload-area {
        border: 2px dashed var(--border);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .upload-area:hover {
        border-color: var(--primary);
        background: rgba(10, 46, 46, 0.02);
    }

    .upload-area i {
        font-size: 3rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }

    .upload-area h3 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
        font-size: 1.25rem;
    }

    .upload-area p {
        margin: 0 0 1rem 0;
        color: #64748b;
    }

    .file-types {
        font-size: 0.85rem;
        color: #94a3b8;
    }

    .upload-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(93, 187, 99, 0.1);
        border-radius: 10px;
        color: var(--success);
    }

    .remove-btn {
        background: none;
        border: none;
        color: var(--error);
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .remove-btn:hover {
        background: rgba(220, 53, 69, 0.1);
    }

    /* Section Thème */
    .theme-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .theme-option input[type="radio"] {
        display: none;
    }

    .theme-label {
        display: block;
        cursor: pointer;
        border-radius: 15px;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .theme-option input[type="radio"]:checked + .theme-label {
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.15);
    }

    .theme-preview {
        height: 120px;
        position: relative;
        overflow: hidden;
    }

    .theme-preview.light {
        background: #ffffff;
    }

    .theme-preview.dark {
        background: #1e293b;
    }

    .theme-preview.auto {
        background: linear-gradient(to right, #ffffff 50%, #1e293b 50%);
    }

    .theme-header {
        height: 25px;
        background: var(--primary);
    }

    .theme-preview.dark .theme-header {
        background: #374151;
    }

    .theme-content {
        display: flex;
        height: 95px;
    }

    .theme-sidebar {
        width: 30%;
        background: #f1f5f9;
    }

    .theme-preview.dark .theme-sidebar {
        background: #374151;
    }

    .theme-main {
        flex: 1;
        background: #ffffff;
    }

    .theme-preview.dark .theme-main {
        background: #1e293b;
    }

    .theme-info {
        padding: 1rem;
        text-align: center;
    }

    .theme-info i {
        font-size: 1.5rem;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .theme-info span {
        display: block;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .theme-info small {
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Section Couleurs */
    .color-presets {
        margin-bottom: 2rem;
    }

    .color-presets h3 {
        margin: 0 0 1rem 0;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .presets-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }

    .preset-option {
        background: white;
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .preset-option:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .preset-colors {
        display: flex;
        gap: 3px;
        margin-bottom: 0.5rem;
        justify-content: center;
    }

    .preset-colors span {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }

    .preset-name {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--primary);
    }

    .custom-colors h3 {
        margin: 0 0 1.5rem 0;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .colors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .color-group {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border);
    }

    .color-group label {
        display: block;
        margin-bottom: 1rem;
        font-weight: 600;
        color: var(--primary);
    }

    .color-input-group {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .color-input-group input[type="color"] {
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        padding: 0;
        background: none;
    }

    .color-input-group input[type="text"] {
        flex: 1;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: monospace;
        text-align: center;
        background: white;
    }

    .color-preview {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        border: 1px solid var(--border);
    }

    .color-group small {
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Section Aperçu */
    .preview-container {
        background: #f1f5f9;
        border-radius: 15px;
        padding: 2rem;
        border: 1px solid var(--border);
    }

    .preview-interface {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .preview-header {
        background: var(--primary);
        color: white;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .preview-logo {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .preview-nav {
        display: flex;
        gap: 1.5rem;
    }

    .preview-nav span {
        opacity: 0.8;
        cursor: pointer;
        transition: opacity 0.3s ease;
    }

    .preview-nav span:hover {
        opacity: 1;
    }

    .preview-content {
        display: flex;
        min-height: 200px;
    }

    .preview-sidebar {
        width: 200px;
        background: var(--secondary);
        padding: 1rem;
    }

    .preview-menu-item {
        padding: 0.75rem 1rem;
        color: white;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.7;
    }

    .preview-menu-item.active,
    .preview-menu-item:hover {
        background: rgba(255, 255, 255, 0.1);
        opacity: 1;
    }

    .preview-main {
        flex: 1;
        padding: 1.5rem;
        background: #f8fafc;
    }

    .preview-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .preview-card h4 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
    }

    .preview-card p {
        margin: 0 0 1rem 0;
        color: #64748b;
    }

    .preview-button {
        background: var(--tertiary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .preview-button:hover {
        background: #7a5a47;
    }

    /* Actions du formulaire */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 2px solid var(--border);
        gap: 1rem;
    }

    .preview-toggle {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .preview-toggle:hover {
        background: var(--primary);
        color: white;
    }

    .save-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        font-weight: 500;
        position: relative;
        overflow: hidden;
    }

    .save-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.2);
    }

    .save-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .save-button.saving {
        background: #94a3b8;
    }

    .save-button.saving::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .settings-container {
            padding: 1rem;
        }

        .settings-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .settings-title {
            font-size: 2rem;
        }

        .logo-section {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .theme-options {
            grid-template-columns: 1fr;
        }

        .colors-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .preview-toggle,
        .save-button {
            width: 100%;
            justify-content: center;
        }

        .preview-content {
            flex-direction: column;
        }

        .preview-sidebar {
            width: 100%;
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const uploadArea = document.getElementById('uploadArea');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const noLogoPlaceholder = document.getElementById('noLogoPlaceholder');
    const uploadInfo = document.getElementById('uploadInfo');
    const fileName = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFile');
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('appearanceForm');
    
    // Inputs de couleur
    const primaryColor = document.getElementById('primary_color');
    const secondaryColor = document.getElementById('secondary_color');
    const accentColor = document.getElementById('accent_color');
    const primaryText = document.getElementById('primary_color_text');
    const secondaryText = document.getElementById('secondary_color_text');
    const accentText = document.getElementById('accent_color_text');
    
    // Aperçu
    const previewInterface = document.getElementById('previewInterface');
    const previewToggle = document.getElementById('previewToggle');
    
    // Gestion de l'upload de logo
    uploadArea.addEventListener('click', () => logoInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--primary)';
        uploadArea.style.background = 'rgba(10, 46, 46, 0.05)';
    });
    
    uploadArea.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--border)';
        uploadArea.style.background = '#f8fafc';
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.style.borderColor = 'var(--border)';
        uploadArea.style.background = '#f8fafc';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileUpload(files[0]);
        }
    });
    
    logoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileUpload(e.target.files[0]);
        }
    });
    
    function handleFileUpload(file) {
        if (!file.type.startsWith('image/')) {
            alert('Veuillez sélectionner un fichier image.');
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('Le fichier est trop volumineux. Maximum 2MB.');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            // Mettre à jour l'aperçu
            if (logoPreview) {
                logoPreview.src = e.target.result;
            } else {
                // Créer un nouvel élément image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Aperçu du logo';
                img.id = 'logoPreview';
                
                // Remplacer le placeholder
                if (noLogoPlaceholder) {
                    noLogoPlaceholder.parentNode.replaceChild(img, noLogoPlaceholder);
                }
            }
            
            // Afficher les informations du fichier
            fileName.textContent = file.name;
            uploadArea.style.display = 'none';
            uploadInfo.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
    
    removeFileBtn.addEventListener('click', () => {
        logoInput.value = '';
        uploadArea.style.display = 'block';
        uploadInfo.style.display = 'none';
        
        // Restaurer le placeholder
        if (logoPreview) {
            const placeholder = document.createElement('div');
            placeholder.className = 'no-logo';
            placeholder.id = 'noLogoPlaceholder';
            placeholder.innerHTML = '<i class="fas fa-image"></i><span>Aucun logo</span>';
            logoPreview.parentNode.replaceChild(placeholder, logoPreview);
        }
    });
    
    // Synchronisation des couleurs
    function syncColorInputs() {
        primaryText.value = primaryColor.value;
        secondaryText.value = secondaryColor.value;
        accentText.value = accentColor.value;
        
        // Mettre à jour l'aperçu
        updatePreview();
    }
    
    primaryColor.addEventListener('input', syncColorInputs);
    secondaryColor.addEventListener('input', syncColorInputs);
    accentColor.addEventListener('input', syncColorInputs);
    
    // Palettes prédéfinies
    const presetButtons = document.querySelectorAll('.preset-option');
    presetButtons.forEach(button => {
        button.addEventListener('click', () => {
            const primary = button.dataset.primary;
            const secondary = button.dataset.secondary;
            const accent = button.dataset.accent;
            
            primaryColor.value = primary;
            secondaryColor.value = secondary;
            accentColor.value = accent;
            
            syncColorInputs();
            
            // Animation de sélection
            button.style.transform = 'scale(0.95)';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 150);
        });
    });
    
    // Mise à jour de l'aperçu en temps réel
    function updatePreview() {
        const root = document.documentElement;
        root.style.setProperty('--primary', primaryColor.value);
        root.style.setProperty('--secondary', secondaryColor.value);
        root.style.setProperty('--tertiary', accentColor.value);
        
        // Mettre à jour l'aperçu de l'interface
        const previewHeader = previewInterface.querySelector('.preview-header');
        const previewSidebar = previewInterface.querySelector('.preview-sidebar');
        const previewButton = previewInterface.querySelector('.preview-button');
        
        if (previewHeader) previewHeader.style.background = primaryColor.value;
        if (previewSidebar) previewSidebar.style.background = secondaryColor.value;
        if (previewButton) previewButton.style.background = accentColor.value;
    }
    
    // Initialiser l'aperçu
    updatePreview();
    
    // Gestion du formulaire
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        saveButton.disabled = true;
        saveButton.classList.add('saving');
        saveButton.querySelector('span').textContent = 'Sauvegarde...';
        
        // Simulation de sauvegarde (remplacer par votre logique)
        setTimeout(() => {
            // Ici, vous pouvez soumettre le formulaire réellement
            form.submit();
        }, 1500);
    });
    
    // Faire disparaître les alertes
    setTimeout(() => {
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
    
    // Animation d'entrée
    const sections = document.querySelectorAll('.form-section');
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            section.style.transition = 'all 0.6s ease';
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, index * 200);
    });
});
</script>
@endsection
