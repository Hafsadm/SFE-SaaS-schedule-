@extends('layouts.app')

@section('title', 'Horaires de ' . $store->nom)

@section('content')
<div class="store-schedules-container">
    <!-- En-tête -->
    <div class="store-header">
        <div class="header-content">
            <h1 class="store-title">Horaires de {{ $store->nom }}</h1>
            <div class="header-actions">
                <a href="{{ route('admin.schedules.dashboard') }}" class="action-button secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour au tableau de bord
                </a>
                <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="action-button primary">
                    <i class="fas fa-plus"></i>
                    Ajouter un horaire
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du magasin -->
    <div class="store-info-card">
        <div class="store-info-content">
            <div class="info-group">
                <div class="info-label">Adresse</div>
                <div class="info-value">{{ $store->adresse }}, {{ $store->code_postal ?? '' }} {{ $store->ville }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Contact</div>
                <div class="info-value">{{ $store->phone ?? 'Non renseigné' }} | {{ $store->email ?? 'Non renseigné' }}</div>
            </div>
        </div>
        <div class="store-status {{ $store->is_closed ? 'closed' : 'open' }}">
            {{ $store->is_closed ? 'Fermé aujourd\'hui' : 'Ouvert aujourd\'hui' }}
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
    <div class="schedules-grid">
        <!-- Horaires réguliers -->
        <div class="schedule-section">
            <div class="section-header">
                <h2 class="section-title">Horaires réguliers</h2>
            </div>
            <div class="section-content">
                @if($regularSchedules->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-week"></i>
                        <p>Aucun horaire régulier défini</p>
                        <a href="{{ route('admin.stores.schedules.regular', $store) }}" class="empty-action">
                            Ajouter un horaire régulier
                        </a>
                    </div>
                @else
                    <div class="schedule-cards">
                        @foreach($regularSchedules as $schedule)
                            <div class="schedule-card {{ $schedule->day_of_week === 'sunday' ? 'sunday' : '' }}">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $schedule->getDayName() }}</h3>
                                    @if($schedule->is_closed)
                                        <span class="status-badge closed">Fermé</span>
                                    @endif
                                </div>
                                <div class="card-content">
                                    @if(!$schedule->is_closed)
                                        <div class="time-slots">
                                            @foreach($schedule->time_slots as $slot)
                                                <div class="time-slot">
                                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.schedules.edit', [$store, $schedule]) }}" class="card-action edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.stores.schedules.destroy', [$store, $schedule]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="card-action delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Exceptions -->
        <div class="schedule-section">
            <div class="section-header">
                <h2 class="section-title">Exceptions</h2>
                <a href="{{ route('admin.stores.exceptions.create', $store) }}" class="section-action">
                    <i class="fas fa-plus"></i>
                    Ajouter
                </a>
            </div>
            <div class="section-content">
                @if($exceptionSchedules->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <p>Aucune exception définie</p>
                        <a href="{{ route('admin.stores.exceptions.create', $store) }}" class="empty-action">
                            Ajouter une exception
                        </a>
                    </div>
                @else
                    <div class="schedule-cards">
                        @foreach($exceptionSchedules as $exception)
                            <div class="schedule-card exception">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $exception->exception_date->format('d/m/Y') }}</h3>
                                    @if($exception->is_closed)
                                        <span class="status-badge closed">Fermé</span>
                                    @else
                                        <span class="status-badge modified">Modifié</span>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <div class="exception-reason">{{ $exception->exception_raison }}</div>
                                    @if(!$exception->is_closed)
                                        <div class="time-slots">
                                            @foreach($exception->time_slots as $slot)
                                                <div class="time-slot">
                                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.exceptions.edit', [$store, $exception]) }}" class="card-action edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.stores.exceptions.destroy', [$store, $exception]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="card-action delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette exception ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Jours fériés -->
        <div class="schedule-section">
            <div class="section-header">
                <h2 class="section-title">Jours fériés</h2>
                <a href="{{ route('admin.stores.holidays.create', $store) }}" class="section-action">
                    <i class="fas fa-plus"></i>
                    Ajouter
                </a>
            </div>
            <div class="section-content">
                @if($holidaySchedules->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar-day"></i>
                        <p>Aucun jour férié défini</p>
                        <a href="{{ route('admin.stores.holidays.create', $store) }}" class="empty-action">
                            Ajouter un jour férié
                        </a>
                    </div>
                @else
                    <div class="schedule-cards">
                        @foreach($holidaySchedules as $holiday)
                            <div class="schedule-card holiday">
                                <div class="card-header">
                                    <h3 class="card-title">{{ $holiday->holiday_date->format('d/m/Y') }}</h3>
                                    <span class="status-badge closed">Fermé</span>
                                </div>
                                <div class="card-content">
                                    <div class="holiday-name">{{ $holiday->holiday_name }}</div>
                                </div>
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.holidays.edit', [$store, $holiday]) }}" class="card-action edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.stores.holidays.destroy', [$store, $holiday]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="card-action delete" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jour férié ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
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
    .store-schedules-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .store-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 30px 0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .store-title {
        color: var(--text-light);
        font-size: 1.8rem;
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .store-title::before {
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

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .action-button.primary {
        background-color: var(--light);
        color: var(--primary);
    }

    .action-button.primary:hover {
        background-color: #d8b08a;
        transform: translateY(-2px);
    }

    .action-button.secondary {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
    }

    .action-button.secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Informations du magasin */
    .store-info-card {
        background-color: var(--text-light);
        border-radius: 20px 0;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .store-info-content {
        display: flex;
        gap: 2rem;
    }

    .info-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-weight: 600;
        color: var(--primary);
        font-size: 0.9rem;
    }

    .info-value {
        color: #666;
    }

    .store-status {
        padding: 0.5rem 1rem;
        border-radius: 20px 0;
        font-weight: 600;
    }

    .store-status.open {
        background-color: rgba(93, 187, 99, 0.2);
        color: var(--success);
    }

    .store-status.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
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

    /* Grille des horaires */
    .schedules-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    /* Sections */
    .schedule-section {
        background-color: var(--text-light);
        border-radius: 30px 0;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .section-header {
        padding: 1.25rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-title {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .section-action {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        border-radius: 20px 0;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .section-action:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .section-content {
        padding: 1.5rem;
    }

    /* Cartes d'horaires */
    .schedule-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
    }

    .schedule-card {
        background-color: #f9f9f9;
        border-radius: 15px 0;
        padding: 1.25rem;
        border: 1px solid var(--border);
        position: relative;
        transition: all 0.3s ease;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(10, 46, 46, 0.1);
    }

    .schedule-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: var(--primary);
        border-radius: 15px 0 0 0;
    }

    .schedule-card.sunday::before {
        background-color: var(--tertiary);
    }

    .schedule-card.exception::before {
        background-color: var(--warning);
    }

    .schedule-card.holiday::before {
        background-color: var(--tertiary);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .card-title {
        margin: 0;
        font-size: 1.1rem;
        color: var(--primary);
        font-weight: 600;
    }

    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 15px 0;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.closed {
        background-color: rgba(220, 53, 69, 0.2);
        color: var(--error);
    }

    .status-badge.modified {
        background-color: rgba(245, 158, 11, 0.2);
        color: var(--warning);
    }

    .card-content {
        margin-bottom: 1rem;
    }

    .time-slots {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .time-slot {
        padding: 0.5rem 0.75rem;
        background-color: rgba(10, 46, 46, 0.05);
        border-radius: 10px 0;
        font-size: 0.9rem;
    }

    .exception-reason, .holiday-name {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 0.75rem;
    }

    .card-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .card-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 0;
        color: var(--text-light);
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .card-action.edit {
        background-color: var(--primary);
    }

    .card-action.edit:hover {
        background-color: var(--secondary);
    }

    .card-action.delete {
        background-color: var(--error);
    }

    .card-action.delete:hover {
        background-color: #c82333;
    }

    /* État vide */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 1rem;
        text-align: center;
        color: #999;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.1rem;
        margin: 0 0 1rem 0;
    }

    .empty-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 20px 0;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .empty-action:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .store-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .action-button {
            width: 100%;
            justify-content: center;
        }

        .store-info-card {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .store-info-content {
            flex-direction: column;
            gap: 1rem;
        }

        .schedule-cards {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .schedule-card, .store-info-card {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .card-title {
            color: var(--text-light);
        }
        
        .info-label {
            color: var(--text-light);
        }
        
        .info-value, .exception-reason, .holiday-name {
            color: #aaa;
        }
        
        .time-slot {
            background-color: rgba(42, 99, 99, 0.2);
            color: var(--text-light);
        }
    }
</style>

<script>
    // Initialiser les icônes Font Awesome
    document.addEventListener('DOMContentLoaded', function() {
        // Si vous utilisez Lucide au lieu de Font Awesome
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
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
    });
</script>
@endsection

