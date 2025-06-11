@extends('layouts.app')

@section('title', 'Gestion des traductions')

@section('content')
<div class="translations-container">
    <!-- En-tête -->
    <div class="translations-header">
        <div class="header-content">
            <h1 class="translations-title">Gestion des traductions</h1>
            <p class="translations-subtitle">Gérez les langues et traductions automatiques de votre SaaS avec Google Cloud Translation</p>
            <div class="header-actions">
                <a href="{{ route('admin.settings.index') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Retour aux paramètres
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-globe"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_languages'] }}</div>
                <div class="stat-label">Langues configurées</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['active_languages'] }}</div>
                <div class="stat-label">Langues actives</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-language"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['total_translations'] }}</div>
                <div class="stat-label">Traductions totales</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-robot"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['auto_translations'] }}</div>
                <div class="stat-label">Traductions auto</div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="translations-content">
        <!-- Section Langues disponibles -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-globe"></i>
                    Langues configurées
                </h2>
                <button class="add-language-btn" onclick="openAddLanguageModal()">
                    <i class="fas fa-plus"></i>
                    Ajouter une langue
                </button>
            </div>
            
            <div class="languages-grid" id="languagesGrid">
                @foreach($languages as $language)
                <div class="language-card" data-language="{{ $language->code }}">
                    <div class="language-info">
                        <div class="language-flag">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div class="language-details">
                            <h3>{{ $language->name }}</h3>
                            <span class="language-code">{{ strtoupper($language->code) }}</span>
                        </div>
                    </div>
                    <div class="language-status">
                        <span class="status-badge {{ $language->is_active ? 'active' : 'inactive' }}">
                            {{ $language->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </div>
                    <div class="language-actions">
                        <button class="action-btn translate-btn" onclick="openTranslateModal('{{ $language->code }}')">
                            <i class="fas fa-language"></i>
                        </button>
                        <button class="action-btn delete-btn" onclick="removeLanguage('{{ $language->code }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Section Outils de traduction -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-tools"></i>
                    Outils de traduction
                </h2>
            </div>
            
            <div class="tools-grid">
                <!-- Détection de langue -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="tool-content">
                        <h3>Détection de langue</h3>
                        <p>Détectez automatiquement la langue d'un texte</p>
                        <textarea id="detectText" placeholder="Saisissez votre texte ici..." rows="3"></textarea>
                        <button class="tool-btn" onclick="detectLanguage()">
                            <i class="fas fa-search"></i>
                            Détecter
                        </button>
                        <div id="detectResult" class="result-area" style="display: none;"></div>
                    </div>
                </div>

                <!-- Traduction rapide -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="tool-content">
                        <h3>Traduction rapide</h3>
                        <p>Traduisez rapidement un texte</p>
                        <div class="translate-form">
                            <div class="language-selectors">
                                <select id="sourceLanguage">
                                    <option value="">Auto-détection</option>
                                    @foreach($supportedLanguages as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-arrow-right"></i>
                                <select id="targetLanguage">
                                    @foreach($supportedLanguages as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <textarea id="quickTranslateText" placeholder="Texte à traduire..." rows="3"></textarea>
                            <button class="tool-btn" onclick="quickTranslate()">
                                <i class="fas fa-language"></i>
                                Traduire
                            </button>
                            <div id="quickTranslateResult" class="result-area" style="display: none;"></div>
                        </div>
                    </div>
                </div>

                <!-- Traduction de fichiers Laravel -->
                <div class="tool-card">
                    <div class="tool-icon">
                        <i class="fas fa-file-code"></i>
                    </div>
                    <div class="tool-content">
                        <h3>Fichiers Laravel</h3>
                        <p>Traduisez vos fichiers de langue Laravel</p>
                        <div class="file-translate-form">
                            <div class="language-selectors">
                                <select id="sourceFileLang">
                                    @foreach($languages as $language)
                                        <option value="{{ $language->code }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-arrow-right"></i>
                                <select id="targetFileLang">
                                    @foreach($supportedLanguages as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="tool-btn" onclick="translateLanguageFiles()">
                                <i class="fas fa-file-export"></i>
                                Traduire fichiers
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter une langue -->
<div id="addLanguageModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ajouter une nouvelle langue</h3>
            <button class="close-btn" onclick="closeAddLanguageModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="addLanguageForm">
                <div class="form-group">
                    <label for="newLanguageCode">Langue</label>
                    <select id="newLanguageCode" name="language_code" required>
                        <option value="">Sélectionnez une langue</option>
                        @foreach($supportedLanguages as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" checked>
                        <span class="checkmark"></span>
                        Activer cette langue
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="cancel-btn" onclick="closeAddLanguageModal()">Annuler</button>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-plus"></i>
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }};
        --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }};
        --tertiary: #8E6E53;
        --success: #5DBB63;
        --error: #dc3545;
        --warning: #f59e0b;
        --border: #E6D8C3;
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }

    .translations-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
    }

    .translations-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 25px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        box-shadow: var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .translations-header::before {
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

    .translations-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .translations-subtitle {
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
        color: white;
        border-radius: 15px;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--card-shadow);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* Sections */
    .section-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }

    .add-language-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .add-language-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.2);
    }

    /* Grille des langues */
    .languages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .language-card {
        background: #f8fafc;
        border: 2px solid var(--border);
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .language-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    .language-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .language-flag {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .language-details h3 {
        margin: 0 0 0.25rem 0;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .language-code {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-badge.active {
        background: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .status-badge.inactive {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error);
    }

    .language-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .translate-btn {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .translate-btn:hover {
        background: #3b82f6;
        color: white;
    }

    .delete-btn {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error);
    }

    .delete-btn:hover {
        background: var(--error);
        color: white;
    }

    /* Outils */
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }

    .tool-card {
        background: #f8fafc;
        border: 1px solid var(--border);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .tool-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.1);
    }

    .tool-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .tool-content h3 {
        margin: 0 0 0.5rem 0;
        color: var(--primary);
        font-size: 1.2rem;
    }

    .tool-content p {
        margin: 0 0 1rem 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .tool-content textarea,
    .tool-content select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 1rem;
        font-family: inherit;
        resize: vertical;
    }

    .language-selectors {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .language-selectors select {
        flex: 1;
        margin-bottom: 0;
    }

    .language-selectors i {
        color: var(--primary);
    }

    .tool-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        width: 100%;
        justify-content: center;
    }

    .tool-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.2);
    }

    .result-area {
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal-content {
        background: white;
        margin: 5% auto;
        border-radius: 20px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border);
    }

    .modal-header h3 {
        margin: 0;
        color: var(--primary);
        font-size: 1.3rem;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #64748b;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        background: #f1f5f9;
        color: var(--error);
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--primary);
    }

    .form-group select {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
    }

    .checkbox-label {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        font-weight: 500 !important;
    }

    .checkbox-label input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border);
        border-radius: 4px;
        position: relative;
        transition: all 0.3s ease;
    }

    .checkbox-label input[type="checkbox"]:checked + .checkmark {
        background: var(--primary);
        border-color: var(--primary);
    }

    .checkbox-label input[type="checkbox"]:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .modal-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    .cancel-btn {
        padding: 0.75rem 1.5rem;
        background: transparent;
        color: #64748b;
        border: 2px solid #64748b;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .cancel-btn:hover {
        background: #64748b;
        color: white;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(10, 46, 46, 0.2);
    }

    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-50px) scale(0.9); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .translations-container {
            padding: 1rem;
        }

        .translations-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .translations-title {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .languages-grid {
            grid-template-columns: 1fr;
        }

        .tools-grid {
            grid-template-columns: 1fr;
        }

        .language-selectors {
            flex-direction: column;
        }

        .modal-content {
            margin: 10% auto;
            width: 95%;
        }
    }
</style>

<script>
// Variables globales
let currentLanguages = @json($languages);
let supportedLanguages = @json($supportedLanguages);

// Gestion du modal d'ajout de langue
function openAddLanguageModal() {
    document.getElementById('addLanguageModal').style.display = 'block';
}

function closeAddLanguageModal() {
    document.getElementById('addLanguageModal').style.display = 'none';
    document.getElementById('addLanguageForm').reset();
}

// Fermer le modal en cliquant à l'extérieur
window.onclick = function(event) {
    const modal = document.getElementById('addLanguageModal');
    if (event.target === modal) {
        closeAddLanguageModal();
    }
}

// Ajouter une nouvelle langue
document.getElementById('addLanguageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('.submit-btn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ajout...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("admin.translations.add-language") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                language_code: formData.get('language_code'),
                is_active: formData.get('is_active') ? true : false
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Langue ajoutée avec succès', 'success');
            closeAddLanguageModal();
            location.reload(); // Recharger pour afficher la nouvelle langue
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajout', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    } finally {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
});

// Supprimer une langue
async function removeLanguage(languageCode) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette langue et toutes ses traductions ?')) {
        return;
    }
    
    try {
        const response = await fetch(`{{ route("admin.translations.remove-language", "") }}/${languageCode}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Langue supprimée avec succès', 'success');
            document.querySelector(`[data-language="${languageCode}"]`).remove();
        } else {
            showNotification(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}

// Détection de langue
async function detectLanguage() {
    const text = document.getElementById('detectText').value.trim();
    const resultDiv = document.getElementById('detectResult');
    
    if (!text) {
        showNotification('Veuillez saisir un texte', 'warning');
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.translations.detect-language") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ text })
        });
        
        const data = await response.json();
        
        if (data.success) {
            resultDiv.innerHTML = `
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-flag" style="color: var(--primary);"></i>
                    <div>
                        <strong>Langue détectée:</strong> ${data.language_name}<br>
                        <small>Code: ${data.detected_language.toUpperCase()}</small>
                    </div>
                </div>
            `;
            resultDiv.style.display = 'block';
        } else {
            showNotification(data.message || 'Erreur lors de la détection', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}

// Traduction rapide
async function quickTranslate() {
    const text = document.getElementById('quickTranslateText').value.trim();
    const sourceLanguage = document.getElementById('sourceLanguage').value;
    const targetLanguage = document.getElementById('targetLanguage').value;
    const resultDiv = document.getElementById('quickTranslateResult');
    
    if (!text || !targetLanguage) {
        showNotification('Veuillez remplir tous les champs', 'warning');
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.translations.auto-translate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                texts: [text],
                source_language: sourceLanguage || null,
                target_language: targetLanguage
            })
        });
        
        const data = await response.json();
        
        if (data.success && data.translations.length > 0) {
            const translation = data.translations[0];
            resultDiv.innerHTML = `
                <div style="padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <strong>Traduction:</strong><br>
                    ${translation.value}
                </div>
            `;
            resultDiv.style.display = 'block';
        } else {
            showNotification(data.message || 'Erreur lors de la traduction', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}

// Traduction des fichiers Laravel
async function translateLanguageFiles() {
    const sourceLanguage = document.getElementById('sourceFileLang').value;
    const targetLanguage = document.getElementById('targetFileLang').value;
    
    if (!sourceLanguage || !targetLanguage) {
        showNotification('Veuillez sélectionner les langues source et cible', 'warning');
        return;
    }
    
    if (sourceLanguage === targetLanguage) {
        showNotification('Les langues source et cible doivent être différentes', 'warning');
        return;
    }
    
    if (!confirm(`Traduire tous les fichiers de langue de ${sourceLanguage} vers ${targetLanguage} ?`)) {
        return;
    }
    
    try {
        const response = await fetch('{{ route("admin.translations.translate-files") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                source_language: sourceLanguage,
                target_language: targetLanguage
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Fichiers traduits avec succès', 'success');
        } else {
            showNotification(data.message || 'Erreur lors de la traduction', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}

// Système de notifications
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            ${message}
        </div>
    `;
    
    // Styles pour la notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '1rem 1.5rem',
        borderRadius: '10px',
        color: 'white',
        fontWeight: '500',
        zIndex: '9999',
        animation: 'slideInRight 0.3s ease-out',
        minWidth: '300px',
        boxShadow: '0 8px 25px rgba(0, 0, 0, 0.15)'
    });
    
    // Couleurs selon le type
    const colors = {
        success: 'linear-gradient(135deg, #10b981, #059669)',
        error: 'linear-gradient(135deg, #ef4444, #dc2626)',
        warning: 'linear-gradient(135deg, #f59e0b, #d97706)',
        info: 'linear-gradient(135deg, #3b82f6, #2563eb)'
    };
    
    notification.style.background = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Supprimer après 5 secondes
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

// Ajouter les animations CSS pour les notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endsection
