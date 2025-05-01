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
                                <span class="reason">{{ $exception->exception_raison }}</span>
                                @if($exception->is_closed)
                                    <span class="status-badge closed">Fermé</span>
                                @endif
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
                                <span class="reason">{{ $holiday->holiday_name }}</span>
                                <span class="status-badge closed">Fermé</span>
                                <div class="card-actions">
                                    <a href="{{ route('admin.stores.holidays.edit', [$store, $holiday]) }}" class="edit-button" title="Modifier">
                                        <i data-lucide="edit"> Modifier</i> 
                                    </a>
                                    <form action="{{ route('admin.stores.holidays.destroy', [$store, $holiday]) }}" method="POST" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-button" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jour férié ?')">
                                            <i data-lucide="trash-2"> Supprimer </i>
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
        --primary-color: #8B4513;
        --secondary-color: #F5F5DC;
        --text-color: #333;
        --border-color: #D2B48C;
        --success-color: #4CAF50;
        --error-color: #F44336;
        --sunday-color: #FFF3E0;
        --holiday-color: #FFEBEE;
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
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .schedules-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .add-button, .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .add-button:hover, .back-button:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-success {
        background-color: #E8F5E9;
        color: var(--success-color);
        border: 1px solid var(--success-color);
    }

    .alert-error {
        background-color: #FFEBEE;
        color: var(--error-color);
        border: 1px solid var(--error-color);
    }

    .schedules-grid {
        display: grid;
        gap: 2rem;
    }

    .schedule-section {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .schedule-section h2 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .empty-state {
        padding: 2rem;
        text-align: center;
        color: #666;
        background-color: #f9f9f9;
        border-radius: 4px;
        border: 1px dashed #ccc;
    }

    .schedule-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .schedule-card {
        background: var(--secondary-color);
        padding: 1rem;
        border-radius: 4px;
        border: 1px solid var(--border-color);
        position: relative;
    }

    .schedule-card.sunday {
        background-color: var(--sunday-color);
        border-color: #FFE0B2;
    }

    .schedule-card.holiday {
        background-color: var(--holiday-color);
        border-color: #FFCDD2;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
        flex-wrap: wrap;
    }

    .card-header h3 {
        margin: 0;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .status-badge {
        padding: 1.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.875rem;
        margin-left: auto;
        margin-bottom: auto;
    }

    .status-badge.closed {
        background-color: #FFEBEE;
        color: var(--error-color);
    }

    .status-badge.closed1 {
        padding: 1.25rem 0.5rem;
        border-radius: 4px;
        font-size: 1rem;
        margin-bottom: auto;
        margin-right: auto 0;
        color: var(--error-color);
    }

    .reason {
        color: #666;
        font-size: 0.875rem;
        width: 100%;
        margin-top: 0.25rem;
    }

    .card-actions {
        display: flex;
        gap: 0.5rem;
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
    }

    .edit-button, .delete-button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.25rem;
        color: #633e1b;
        transition: color 0.2s;
    }

    .edit-button:hover {
        color: var(--primary-color);
    }

    .delete-button:hover {
        color: var(--error-color);
    }

    .time-slots {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .time-slot {
        padding: 0.5rem;
        background: white;
        border-radius: 4px;
        border: 1px solid var(--border-color);
    }

    @media (max-width: 768px) {
        .schedules-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .add-button, .back-button {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection