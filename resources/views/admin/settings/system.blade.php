@extends('layouts.app')

@section('title', 'Paramètres système')

@section('content')
<div class="settings-container">
    <!-- En-tête -->
    <div class="settings-header">
        <div class="header-content">
            <h1 class="settings-title">Paramètres système</h1>
            <p class="settings-subtitle">Configuration générale de votre SaaS - paramètres simples et essentiels</p>
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

    <!-- Formulaire des paramètres système -->
    <div class="settings-form-container">
        <form action="{{ route('admin.settings.system.update') }}" method="POST" class="settings-form" id="systemForm">
            @csrf
            
            <div class="form-sections">
                <!-- Informations de l'application -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-building"></i>
                        Informations de l'application
                    </h2>
        
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="app_name">Nom de l'application</label>
                            <input type="text" id="app_name" name="app_name" 
                                   value="{{ old('app_name', $settings['app_name'] ?? 'Mon SaaS') }}" 
                                   class="form-input" placeholder="Mon SaaS" required>
                            <small class="form-help">Le nom qui apparaîtra dans l'interface</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_name">Nom de la société</label>
                            <input type="text" id="company_name" name="company_name" 
                                   value="{{ old('company_name', $settings['company_name'] ?? 'Ma Société') }}" 
                                   class="form-input" placeholder="Ma Société" required>
                            <small class="form-help">Nom de votre entreprise</small>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="app_description">Description</label>
                            <textarea id="app_description" name="app_description" rows="3" class="form-input" 
                                      placeholder="Description de votre application">{{ old('app_description', $settings['app_description'] ?? 'Système de gestion des horaires de points de vente') }}</textarea>
                            <small class="form-help">Description courte de votre application</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="website_url">URL du site web</label>
                            <input type="text" id="website_url" name="website_url" 
                                   value="{{ old('website_url', $settings['website_url'] ?? 'mon-saas.com') }}" 
                                   class="form-input" placeholder="mon-saas.com" required>
                            <small class="form-help">Adresse de votre site web (sans http://)</small>
                        </div>
                    </div>
                </div>

                <!-- Paramètres régionaux -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-globe"></i>
                        Paramètres régionaux
                    </h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="timezone">Fuseau horaire</label>
                            <select id="timezone" name="timezone" class="form-select" required>
                                <option value="Europe/Paris" {{ ($settings['timezone'] ?? 'Europe/Paris') === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (UTC+1)</option>
                                <option value="Europe/London" {{ ($settings['timezone'] ?? '') === 'Europe/London' ? 'selected' : '' }}>Europe/London (UTC+0)</option>
                                <option value="America/New_York" {{ ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York (UTC-5)</option>
                                <option value="Asia/Tokyo" {{ ($settings['timezone'] ?? '') === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (UTC+9)</option>
                            </select>
                            <small class="form-help">Fuseau horaire par défaut de l'application</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="language">Langue</label>
                            <select id="language" name="language" class="form-select" required>
                                <option value="fr" {{ ($settings['language'] ?? 'fr') === 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="en" {{ ($settings['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ ($settings['language'] ?? '') === 'es' ? 'selected' : '' }}>Español</option>
                                <option value="de" {{ ($settings['language'] ?? '') === 'de' ? 'selected' : '' }}>Deutsch</option>
                            </select>
                            <small class="form-help">Langue par défaut de l'interface</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="date_format">Format de date</label>
                            <select id="date_format" name="date_format" class="form-select" required>
                                <option value="d/m/Y" {{ ($settings['date_format'] ?? 'd/m/Y') === 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                            <small class="form-help">Format d'affichage des dates</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="time_format">Format d'heure</label>
                            <select id="time_format" name="time_format" class="form-select" required>
                                <option value="H:i" {{ ($settings['time_format'] ?? 'H:i') === 'H:i' ? 'selected' : '' }}>24h (14:30)</option>
                                <option value="g:i A" {{ ($settings['time_format'] ?? '') === 'g:i A' ? 'selected' : '' }}>12h (2:30 PM)</option>
                            </select>
                            <small class="form-help">Format d'affichage des heures</small>
                        </div>
                    </div>
                </div>

                <!-- Mode maintenance -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-tools"></i>
                        Mode maintenance
                    </h2>
                    
                    <div class="maintenance-section">
                        <div class="maintenance-toggle">
                            <div class="toggle-info">
                                <h3>Activer le mode maintenance</h3>
                                <p>Désactive l'accès public à l'application pour effectuer des mises à jour</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="maintenance_mode" value="1" 
                                       {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }} id="maintenanceToggle">
                                <span class="slider"></span>
                            </label>
                        </div>
                        
                        <div class="maintenance-message" id="maintenanceMessage" style="{{ ($settings['maintenance_mode'] ?? false) ? '' : 'display: none;' }}">
                            <div class="form-group">
                                <label for="maintenance_message">Message de maintenance</label>
                                <textarea id="maintenance_message" name="maintenance_message" rows="3" class="form-input" 
                                          placeholder="Message affiché aux utilisateurs pendant la maintenance">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'L\'application est temporairement indisponible pour maintenance. Veuillez réessayer plus tard.') }}</textarea>
                                <small class="form-help">Ce message sera affiché aux visiteurs pendant la maintenance</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="form-section">
                    <h2 class="section-title">
                        <i class="fas fa-bolt"></i>
                        Actions rapides
                    </h2>
                    
             <div class="quick-actions">
    <button type="button" class="action-card" id="clearCacheBtn">
        <div class="action-icon">
            <i class="fas fa-broom"></i>
        </div>
        <div class="action-content">
            <h3>Vider le cache</h3>
            <p>Actualise les données en cache</p>
        </div>
    </button>
    
    <button type="button" class="action-card" id="exportConfigBtn">
        <div class="action-icon">
            <i class="fas fa-download"></i>
        </div>
        <div class="action-content">
            <h3>Exporter JSON</h3>
            <p>Format JSON</p>
        </div>
    </button>
    
    <button type="button" class="action-card" id="exportPdfBtn">
        <div class="action-icon">
            <i class="fas fa-file-pdf"></i>
        </div>
        <div class="action-content">
            <h3>Exporter PDF</h3>
            <p>Format PDF</p>
        </div>
    </button>
    
    <button type="button" class="action-card" id="testConnectionBtn">
        <div class="action-icon">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="action-content">
            <h3>Test connexion</h3>
            <p>Vérifier la connectivité</p>
        </div>
    </button>
</div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="reset-button" id="resetFormBtn">
                    <i class="fas fa-undo"></i>
                    Réinitialiser
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
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.95rem;
    }

    .form-input, .form-select {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-help {
        font-size: 0.85rem;
        color: #64748b;
        font-style: italic;
    }

    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }

    /* Section maintenance */
    .maintenance-section {
        background: #f8fafc;
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid var(--border);
    }

    .maintenance-toggle {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .toggle-info h3 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .toggle-info p {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Switch personnalisé */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color:var(--primary);
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .maintenance-message {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border);
        animation: slideDown 0.3s ease-out;
    }

    /* Actions rapides */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .action-card {
        background: white;
        border: 2px solid var(--border);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
    }

    .action-card:hover {
        border-color:var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .action-content h3 {
        margin: 0 0 0.25rem 0;
        color: var(--primary);
        font-size: 1rem;
    }

    .action-content p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
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

    .reset-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: transparent;
        color: #64748b;
        border: 2px solid #64748b;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .reset-button:hover {
        background: #64748b;
        color: white;
        transform: translateY(-2px);
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
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
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

        .form-grid {
            grid-template-columns: 1fr;
        }

        .maintenance-toggle {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .quick-actions {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .reset-button,
        .save-button {
            width: 100%;
            justify-content: center;
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

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const maintenanceToggle = document.getElementById('maintenanceToggle');
    const maintenanceMessage = document.getElementById('maintenanceMessage');
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('systemForm');
    const resetFormBtn = document.getElementById('resetFormBtn');
    const clearCacheBtn = document.getElementById('clearCacheBtn');
    const exportConfigBtn = document.getElementById('exportConfigBtn');
    const testConnectionBtn = document.getElementById('testConnectionBtn');
    
    // Gestion du mode maintenance
    if (maintenanceToggle) {
        maintenanceToggle.addEventListener('change', function() {
            if (this.checked) {
                maintenanceMessage.style.display = 'block';
                maintenanceMessage.style.animation = 'slideDown 0.3s ease-out';
            } else {
                maintenanceMessage.style.display = 'none';
            }
        });
    }
    
    // Gestion du formulaire
    if (form) {
        form.addEventListener('submit', function(e) {
            saveButton.disabled = true;
            saveButton.classList.add('saving');
            saveButton.querySelector('span').textContent = 'Sauvegarde...';
        });
    }
    
    // Réinitialiser le formulaire
    if (resetFormBtn) {
        resetFormBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les paramètres ?')) {
                form.reset();
                if (maintenanceMessage) {
                    maintenanceMessage.style.display = maintenanceToggle.checked ? 'block' : 'none';
                }
            }
        });
    }
    
    // Actions rapides avec AJAX
    if (clearCacheBtn) {
        clearCacheBtn.addEventListener('click', function() {
            const button = this;
            button.style.transform = 'scale(0.95)';
            
            fetch('{{ route("admin.settings.clear-cache") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.style.transform = 'scale(1)';
                alert(data.message);
            })
            .catch(error => {
                button.style.transform = 'scale(1)';
                alert('Erreur lors de l\'opération');
                console.error('Error:', error);
            });
        });
    }
    
  if (document.getElementById('exportPdfBtn')) {
    document.getElementById('exportPdfBtn').addEventListener('click', function() {
        const button = this;
        button.style.transform = 'scale(0.95)';
        
        // Redirection vers la route d'export PDF
        window.location.href = '{{ route("admin.settings.export-config-pdf") }}';
        
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 300);
    });
} 
    if (testConnectionBtn) {
        testConnectionBtn.addEventListener('click', function() {
            const button = this;
            button.style.transform = 'scale(0.95)';
            
            fetch('{{ route("admin.settings.test-connection") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                button.style.transform = 'scale(1)';
                alert(data.message);
            })
            .catch(error => {
                button.style.transform = 'scale(1)';
                alert('Erreur lors du test');
                console.error('Error:', error);
            });
        });
    }
    
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