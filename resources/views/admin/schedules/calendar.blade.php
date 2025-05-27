@extends('layouts.app')

@section('content')
<div class="calendar-container">
    <div class="calendar-header">
        <h1 class="calendar-title">Calendrier des horaires</h1>
        <div class="header-actions">
            <a href="{{ route('admin.schedules.dashboard') }}" class="back-button">
                <i data-lucide="arrow-left"></i>
                Retour au tableau de bord
            </a>
        </div>
    </div>

    <div class="month-navigation">
        <div class="month-selector">
            <a href="{{ route('admin.schedules.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="month-nav-button">
                <i data-lucide="chevron-left"></i>
            </a>
            <h2 class="current-month">{{ ucfirst($firstDay->locale('fr')->format('F Y')) }}</h2>
            <a href="{{ route('admin.schedules.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="month-nav-button">
                <i data-lucide="chevron-right"></i>
            </a>
        </div>
        <div class="store-filter">
            
            <select id="store-select" class="store-select" onchange="filterCalendarByStore(this.value)">
                <option value="all" style=" font-family: Georgia, 'Times New Roman', Times, serif; color: #2a6363 ; ">Tous les points de vente</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->nom }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="calendar-grid">
        <div class="calendar-days">
            <div class="day-header">Lundi</div>
            <div class="day-header">Mardi</div>
            <div class="day-header">Mercredi</div>
            <div class="day-header">Jeudi</div>
            <div class="day-header">Vendredi</div>
            <div class="day-header">Samedi</div>
            <div class="day-header sunday">Dimanche</div>
        </div>

        <div class="calendar-dates">
            @php
                // Déterminer le premier jour à afficher (lundi précédent le 1er du mois)
                $startDate = clone $firstDay;
                $startDate->startOfWeek(Carbon\Carbon::MONDAY);
                
                // Déterminer le dernier jour à afficher (dimanche suivant le dernier jour du mois)
                $endDate = clone $lastDay;
                $endDate->endOfWeek(Carbon\Carbon::SUNDAY);
                
                // Jour courant pour l'itération
                $currentDate = clone $startDate;
            @endphp

            @while($currentDate <= $endDate)
                @php
                    $isCurrentMonth = $currentDate->month == $month;
                    $isToday = $currentDate->isToday();
                    $dateStr = $currentDate->format('Y-m-d');
                    $isSunday = $currentDate->dayOfWeek == Carbon\Carbon::SUNDAY;
                    
                    // Vérifier s'il y a des exceptions pour cette date
                    $hasExceptions = isset($exceptions[$dateStr]) && count($exceptions[$dateStr]) > 0;
                    
                    // Vérifier s'il y a des jours fériés pour cette date
                    $hasHolidays = isset($holidays[$dateStr]) && count($holidays[$dateStr]) > 0;
                @endphp

                <div class="calendar-date {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }} {{ $isSunday ? 'sunday' : '' }} {{ $hasExceptions ? 'has-exception' : '' }} {{ $hasHolidays ? 'has-holiday' : '' }}" data-date="{{ $dateStr }}">
                    <div class="date-number">{{ $currentDate->day }}</div>
                    
                    @if($hasExceptions || $hasHolidays)
                        <div class="date-events">
                            @if($hasExceptions)
                                @foreach($exceptions[$dateStr] as $exception)
                                    <div class="date-event exception" data-store-id="{{ $exception->store_id }}">
                                        <span class="event-dot"></span>
                                        <span class="event-store">{{ $exception->store->nom }}</span>
                                        <span class="event-status {{ $exception->is_closed ? 'closed' : 'open' }}">
                                            {{ $exception->is_closed ? 'Fermé' : 'Horaires spéciaux' }}
                                        </span>
                                        <a href="{{ route('admin.stores.exceptions.edit', [$exception->store, $exception]) }}" class="event-action" title="Modifier">
                                            <i data-lucide="edit"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                            
                            @if($hasHolidays)
                                @foreach($holidays[$dateStr] as $holiday)
                                    <div class="date-event holiday" data-store-id="{{ $holiday->store_id }}">
                                        <span class="event-dot"></span>
                                        <span class="event-store">{{ $holiday->store->nom }}</span>
                                        <span class="event-name">{{ $holiday->holiday_name }}</span>
                                        <a href="{{ route('admin.stores.holidays.edit', [$holiday->store, $holiday]) }}" class="event-action" title="Modifier">
                                            <i data-lucide="edit"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('admin.stores.bulk-schedule') }}?date={{ $dateStr }}" class="add-event-button" title="Ajouter un événement">
                        <i data-lucide="plus"></i>
                    </a>
                </div>

                @php
                    $currentDate->addDay();
                @endphp
            @endwhile
        </div>
    </div>

    <div class="calendar-legend">
        <div class="legend-item">
            <span class="legend-color today"></span>
            <span class="legend-text">Aujourd'hui</span>
        </div>
        <div class="legend-item">
            <span class="legend-color exception"></span>
            <span class="legend-text">Exception</span>
        </div>
        <div class="legend-item">
            <span class="legend-color holiday"></span>
            <span class="legend-text">Jour férié</span>
        </div>
        <div class="legend-item">
            <span class="legend-color sunday"></span>
            <span class="legend-text">Dimanche</span>
        </div>
    </div>
</div>

<style>
  :root {
           --primary: #0A2E2E; 
        --secondary: #143333; 
        --primary-light: #5aad9e;
        --primary-dark: #337b8d;
        --secondary-light: #ffffff;
        --secondary-dark: #0a2e2e;
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(10, 46, 46, 0.1);
    }


    /* Container principal */
    .calendar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .calendar-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--secondary);
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .calendar-title::before {
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

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: var(--shadow);
    }

    .back-button:hover {
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(66, 145, 130, 0.2);
    }

    /* Navigation du mois */
    .month-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--card-bg);
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .month-selector {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .month-nav-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(27, 88, 88, 0.05);
        color: var(--secondary);
        border-radius: 50%;
        text-decoration: none;
        transition: var(--transition);
    }

    .month-nav-button:hover {
        background: rgba(27, 88, 88, 0.1);
        transform: translateY(-2px);
    }

    .current-month {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-light);
        margin: 0;
        text-transform: capitalize;
    }

    .store-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        
    }

    .store-filter label {
        font-weight: 500;
        color: #8E6E53;
        font-family: Georgia, 'Times New Roman', Times, serif;
        font-size: 1rem;
    }

    .store-select {
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 30px 0;
        background: #000000;
        color: #0a2e2e;
        min-width: 200px;
        transition: var(--transition);
    }

    .store-select:focus {
        outline: none;
        color: #000000;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(66, 145, 130, 0.1);
    }

    /* Grille du calendrier */
    .calendar-grid {
        background: var(--card-bg);
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: var(--secondary);
        color: var(--text-light);
    }

    .day-header {
        padding: 1rem;
        text-align: center;
        font-weight: 600;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .day-header.sunday {
        background: var(--secondary-dark);
    }

    .day-header:last-child {
        border-right: none;
    }

    .calendar-dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: minmax(120px, auto);
    }

    .calendar-date {
        position: relative;
        padding: 0.75rem;
        border-right: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        min-height: 120px;
        transition: var(--transition);
    }

    .calendar-date:nth-child(7n) {
        border-right: none;
    }

    .calendar-date:hover {
        background: rgba(249, 245, 239, 0.5);
    }

    .calendar-date.other-month {
        background: rgba(0, 0, 0, 0.02);
        color: var(--text-muted);
    }

    .calendar-date.today {
        background: rgba(66, 145, 130, 0.05);
    }

    .calendar-date.today .date-number {
        background: var(--primary);
        color: var(--text-light);
    }

    .calendar-date.sunday {
        background: rgba(27, 88, 88, 0.05);
    }

    .calendar-date.has-exception {
        background: rgba(255, 193, 7, 0.05);
    }

    .calendar-date.has-holiday {
        background: rgba(23, 162, 184, 0.05);
    }

    .date-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .date-events {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-top: 0.5rem;
        max-height: calc(100% - 40px);
        overflow-y: auto;
    }

    .date-event {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        position: relative;
    }

    .date-event.exception {
        background: rgba(255, 193, 7, 0.1);
        border-left: 3px solid var(--warning);
    }

    .date-event.holiday {
        background: rgba(23, 162, 184, 0.1);
        border-left: 3px solid var(--info);
    }

    .event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--warning);
    }

    .date-event.holiday .event-dot {
        background: var(--info);
    }

    .event-store {
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
    }

    .event-status {
        margin-left: auto;
        padding: 0.1rem 0.5rem;
        border-radius: 30px 0;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .event-status.closed {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error);
    }

    .event-status.open {
        background: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .event-name {
        margin-left: auto;
        font-style: italic;
    }

    .event-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(27, 88, 88, 0.1);
        color: var(--secondary);
        transition: var(--transition);
    }

    .event-action:hover {
        background: rgba(27, 88, 88, 0.2);
        transform: translateY(-1px);
    }

    .event-action i {
        width: 12px;
        height: 12px;
    }

    .add-event-button {
        position: absolute;
        bottom: 0.5rem;
        right: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--primary);
        color: var(--text-light);
        opacity: 0;
        transition: var(--transition);
    }

    .calendar-date:hover .add-event-button {
        opacity: 1;
    }

    .add-event-button:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .add-event-button i {
        width: 16px;
        height: 16px;
    }

    /* Légende du calendrier */
    .calendar-legend {
        display: flex;
        gap: 2rem;
        padding: 1.5rem;
        background: var(--card-bg);
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
    }

    .legend-color.today {
        background: rgba(66, 145, 130, 0.2);
        border: 2px solid var(--primary);
    }

    .legend-color.exception {
        background: rgba(255, 193, 7, 0.2);
        border: 2px solid var(--warning);
    }

    .legend-color.holiday {
        background: rgba(23, 162, 184, 0.2);
        border: 2px solid var(--info);
    }

    .legend-color.sunday {
        background: rgba(27, 88, 88, 0.2);
        border: 2px solid var(--secondary);
    }

    .legend-text {
        font-size: 0.9rem;
        color: var(--text-dark);
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .month-navigation {
            flex-direction: column;
            gap: 1rem;
        }
        
        .store-filter {
            width: 100%;
        }
        
        .store-select {
            flex: 1;
        }
        
        .calendar-days, .calendar-dates {
            font-size: 0.9rem;
        }
        
        .calendar-date {
            padding: 0.5rem;
            min-height: 100px;
        }
    }

    @media (max-width: 768px) {
        .calendar-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .back-button {
            width: 100%;
            justify-content: center;
        }
        
        .calendar-days {
            display: none;
        }
        
        .calendar-dates {
            display: flex;
            flex-direction: column;
        }
        
        .calendar-date {
            border-right: none;
            min-height: auto;
            padding: 1rem;
        }
        
        .calendar-date::before {
            content: attr(data-day);
            font-weight: 600;
            margin-right: 0.5rem;
        }
        
        .calendar-legend {
            flex-wrap: wrap;
            gap: 1rem;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        :root {
            --card-bg: #0a2e2e;
            --background: #121f1f;
            --text-dark: #e0e0e0;
            --text-muted: #aaaaaa;
            --border-color: #2a6363;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .calendar-container {
            background-color: var (#e0e0e0);
        }
        
        .month-navigation,
        .calendar-grid,
        .calendar-legend {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }
        
        .store-select {
            background-color: rgba(27, 88, 88, 0.1);
            color: var(--text-dark);
            border-color: var(--border-color);
        }
        
        .calendar-date {
            border-color: var(--border-color);
        }
        
        .calendar-date:hover {
            background: rgba(27, 88, 88, 0.1);
        }
        
        .calendar-date.other-month {
            background: rgba(0, 0, 0, 0.1);
        }
        
        .calendar-date.today {
            background: rgba(66, 145, 130, 0.1);
        }
        
        .calendar-date.sunday {
            background: rgba(27, 88, 88, 0.15);
        }
        
        .calendar-date.has-exception {
            background: rgba(255, 193, 7, 0.1);
        }
        
        .calendar-date.has-holiday {
            background: rgba(23, 162, 184, 0.1);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Lucide
        lucide.createIcons();
        
        // Ajouter les attributs data-day aux dates pour l'affichage mobile
        const days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        const calendarDates = document.querySelectorAll('.calendar-date');
        
        calendarDates.forEach((date, index) => {
            const dayIndex = index % 7;
            date.setAttribute('data-day', days[dayIndex]);
        });
    });
    
    // Fonction pour filtrer le calendrier par point de vente
    function filterCalendarByStore(storeId) {
        const events = document.querySelectorAll('.date-event');
        
        if (storeId === 'all') {
            // Afficher tous les événements
            events.forEach(event => {
                event.style.display = 'flex';
            });
            
            // Réinitialiser les classes des dates
            document.querySelectorAll('.calendar-date').forEach(date => {
                if (date.querySelector('.date-event.exception')) {
                    date.classList.add('has-exception');
                }
                if (date.querySelector('.date-event.holiday')) {
                    date.classList.add('has-holiday');
                }
            });
        } else {
            // Filtrer les événements par point de vente
            events.forEach(event => {
                if (event.dataset.storeId === storeId) {
                    event.style.display = 'flex';
                } else {
                    event.style.display = 'none';
                }
            });
            
            // Mettre à jour les classes des dates
            document.querySelectorAll('.calendar-date').forEach(date => {
                const hasVisibleException = date.querySelector('.date-event.exception[data-store-id="' + storeId + '"]');
                const hasVisibleHoliday = date.querySelector('.date-event.holiday[data-store-id="' + storeId + '"]');
                
                date.classList.toggle('has-exception', hasVisibleException !== null);
                date.classList.toggle('has-holiday', hasVisibleHoliday !== null);
            });
        }
    }
</script>
@endsection
