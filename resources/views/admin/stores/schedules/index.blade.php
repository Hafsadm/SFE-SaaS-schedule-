@extends('layouts.app')

@section('content')
<div class="schedules-container">
    <div class="schedules-header">
        <h1 class="schedules-title">Horaires de {{ $store->nom }}</h1>
        <div class="header-actions">
            <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="add-button">
                <i data-lucide="plus"></i>
                Ajouter un horaire
            </a>
            <a href="{{ route('admin.stores.index') }}" class="back-button">
                <i data-lucide="arrow-left"></i>
                Retour aux magasins
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success') || session('error'))
    <script>
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500); // supprime complètement du DOM après fondu
            });
        }, 3000); // 3000ms = 3 secondes
    </script>
    @endif

    <div class="schedules-grid">
        <!-- Horaires réguliers -->
        <div class="schedule-section">
            <h2>Horaires réguliers</h2>
            @if($regularSchedules->isEmpty())
                <div class="empty-state">
                    <p>Aucun horaire régulier défini</p>
                </div>
            @else
                <div class="schedule-cards">
                    @foreach($regularSchedules as $schedule)
                        <div class="schedule-card {{ $schedule->day_of_week === 'sunday' ? 'sunday' : '' }}">
                            <div class="card-header">
                                <h3>{{ $schedule->getDayName() }}</h3>
                                @if($schedule->is_closed)
                                    <span class="status-badge closed1">Fermé</span>
                                @endif
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.schedules.edit', [$store, $schedule]) }}" class="edit-button" title="Modifier">
                                        <i data-lucide="edit"> Modifier </i>
                                    </a>
                                    <form action="{{ route('admin.stores.schedules.destroy', [$store, $schedule]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet horaire ?')">
                                            <i data-lucide="trash-2">Supprimer</i>
                                        </button>
                                    </form>
                                </div>
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Exceptions -->
        <div class="schedule-section">
            <h2>Exceptions</h2>
            @if($exceptionSchedules->isEmpty())
                <div class="empty-state">
                    <p>Aucune exception définie</p>
                </div>
            @else
                <div class="schedule-cards">
                    @foreach($exceptionSchedules as $exception)
                        <div class="schedule-card">
                            <div class="card-header">
                                <h3>{{ $exception->exception_date->format('d/m/Y') }}</h3>
                                @if($exception->is_closed)
                                <span class="status-badge closed">Fermé</span>
                            @endif
                                <span class="reason">{{ $exception->exception_raison }}</span>
                              
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.exceptions.edit', [$store, $exception]) }}" class="edit-button" title="Modifier">
                                        <i data-lucide="edit"> Modifier </i>
                                    </a>
                                    <form action="{{ route('admin.stores.exceptions.destroy', [$store, $exception]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette exception ?')">
                                            <i data-lucide="trash-2"> Supprimer </i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-content">
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Jours fériés -->
        <div class="schedule-section">
            <h2>Jours fériés</h2>
            @if($holidaySchedules->isEmpty())
                <div class="empty-state">
                    <p>Aucun jour férié défini</p>
                </div>
            @else
                <div class="schedule-cards">
                    @foreach($holidaySchedules as $holiday)
                        <div class="schedule-card holiday">
                            <div class="card-header">
                                <h3>{{ $holiday->holiday_date->format('d/m/Y') }}</h3>
                                <span class="status-badge closed">Fermé</span>
                                <span class="reason">{{ $holiday->holiday_name }}</span>
                              
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.holidays.edit', [$store, $holiday]) }}" class="edit-button" title="Modifier">
                                        <i data-lucide="edit">  Modifier </i>  
                                    </a>
                                    <form action="{{ route('admin.stores.holidays.destroy', [$store, $holiday]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jour férié ?')">
                                            <i data-lucide="trash-2">  Supprimer </i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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

    .schedules-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .schedules-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: var(--light-beige);
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .schedules-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .schedules-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background-color: var(--primary);
        border-radius: 2px;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .add-button, .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--dark-beige) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: var(--card-shadow);
    }

    .add-button:hover, .back-button:hover {
        background: linear-gradient(135deg, #4BA793 0%, #2A5F6F 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 145, 130, 0.3);
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 30px 0;
        border-left: 4px solid;
    }

    .alert-success {
        background-color: rgba(93, 187, 99, 0.1);
        border-color: var(--success);
        color: var(--success);
    }

    .alert-error {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: var(--error);
        color: var(--error);
    }

    .schedules-grid {
        display: grid;
        gap: 2rem;
    }

    .schedule-section {
        background: var(--text-light);
        padding: 1.5rem;
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .schedule-section h2 {
        color: var(--primary);
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        position: relative;
        padding-left: 0.75rem;
    }

    .schedule-section h2::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 60%;
        background-color: var(--primary);
        border-radius: 2px;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #666;
        background-color: rgba(249, 245, 239, 0.5);
        border-radius: 30px 0;
        border: 1px dashed var(--border);
    }

    .schedule-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .schedule-card {
        background: var(--text-light);
        padding: 1.5rem;
        border-radius: 20px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(66, 145, 130, 0.2);
    }

    .schedule-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 20px 0 0 0;
    }

    .schedule-card.sunday {
        background-color: var(--sunday-bg);
    }

    .schedule-card.holiday {
        background-color: var(--holiday-bg);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .card-header h3 {
        margin: 0;
        color: var(--primary);
        font-size: 1.1rem;
    }

    .status-badge {
        border-radius: 30px 0;
        font-size: 0.75rem;
        font-weight: 900;
    }

    .status-badge.closed, .status-badge.closed1 {

        background-color: rgba(170, 22, 37, 0.1);
        color: var(--error);
    }

    .reason {
        color: #666;
        font-size: 0.875rem;
        width: 100%;
        margin-top: 0.5rem;
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
    }

    .edit-button {
       background: #1d4b4b;
        color:#ffffff;
        transition: all 0.2s;
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 0.9rem;
    }


    .delete-button{
        background: #143636;
        cursor: pointer;
        color:#ffffff;
        transition: all 0.2s;
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 0.9rem;

    }

    .edit-button:hover {
        color: var(--secondary);
        background-color: rgba(66, 145, 130, 0.1);
    }

    .delete-button:hover {
        color: var(--error);
        background-color: rgba(220, 53, 69, 0.1);
    }

    .time-slots {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .time-slot {
        padding: 0.75rem;
        background: rgba(249, 245, 239, 0.5);
        border-radius: 10px 0;
        border: 1px solid var(--border);
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .schedules-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .schedules-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .add-button, .back-button {
            width: 100%;
            justify-content: center;
        }

        .schedule-cards {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
    /* Container principal */
    /* .schedules-container {
        background-color: #121f1f;
    } */

    /* En-tête */
    .schedules-header {
        background-color: #0a2e2e;
        border-color: #2a6363;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    /* Cartes d'horaire */
    .schedule-card {
        background-color: #0a2e2e;
        border-color: #2a6363;
        color: #e0e0e0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Cartes spéciales */
    .schedule-card.sunday {
        background-color: rgba(27, 88, 88, 0.4);
        border-color: #429182;
    }

    .schedule-card.holiday {
        background-color: #154444d8;
        border-color: #1d4b4b;
    }

    /* Textes et éléments */
    .schedule-card h3,
    .schedule-card .reason {
        color: #ffffff;
    }

    .status-badge.closed,
    .status-badge.closed1 {
        background-color: rgba(220, 53, 69, 0.2);
        color: #ff6b6b;
    }

    /* Créneaux horaires */
    .time-slot {
        background-color: #121f1f;
        border-color: #2a6363;
        color: #e0e0e0;
    }

    /* Boutons */
    .add-button,
    .back-button {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* Sections */
    .schedule-section {
        background-color: #0a2e2e;
        border-color: #2a6363;
    }

    /* Titres */
    .schedules-title,
    .schedule-section h2 {
        color: #429182;
    }

    /* Éléments de formulaire */
    .form-control,
    .time-input {
        background-color: #121f1f;
        color: #ffffff;
        border-color: #2a6363;
    }

    /* Boutons d'action */
    .edit-button:hover {
        background-color: rgba(66, 145, 130, 0.2);
    }

    .delete-button:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection