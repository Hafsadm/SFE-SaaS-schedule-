@extends('layouts.app')

@section('title', 'Calendrier des horaires')

@section('content')
<div class="calendar-container">
    <!-- En-tête -->
    <div class="calendar-header">
        <div class="header-content">
            <h1 class="calendar-title">Calendrier des horaires</h1>
            <div class="header-actions">
                <a href="{{ route('admin.schedules.dashboard') }}" class="action-button secondary">
                    <i class="fas fa-arrow-left"></i>
                    Retour au tableau de bord
                </a>
                <div class="month-navigation">
                    <a href="{{ route('admin.schedules.calendar', ['month' => $month == 1 ? 12 : $month - 1, 'year' => $month == 1 ? $year - 1 : $year]) }}" class="nav-button">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <span class="current-month">{{ Carbon\Carbon::createFromDate($year, $month, 1)->locale('fr')->isoFormat('MMMM YYYY') }}</span>
                    <a href="{{ route('admin.schedules.calendar', ['month' => $month == 12 ? 1 : $month + 1, 'year' => $month == 12 ? $year + 1 : $year]) }}" class="nav-button">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="calendar-filters">
        <div class="filter-group">
            <label for="store-filter">Point de vente:</label>
            <select id="store-filter" onchange="filterCalendarEvents()">
                <option value="all">Tous les points de vente</option>
                @foreach($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->nom }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="event-type-filter">Type d'événement:</label>
            <select id="event-type-filter" onchange="filterCalendarEvents()">
                <option value="all">Tous les types</option>
                <option value="exception">Exceptions</option>
                <option value="holiday">Jours fériés</option>
            </select>
        </div>
    </div>

    <!-- Calendrier -->
    <div class="calendar">
        <!-- Jours de la semaine -->
        <div class="calendar-weekdays">
            <div class="weekday">Lun</div>
            <div class="weekday">Mar</div>
            <div class="weekday">Mer</div>
            <div class="weekday">Jeu</div>
            <div class="weekday">Ven</div>
            <div class="weekday">Sam</div>
            <div class="weekday sunday">Dim</div>
        </div>

        <!-- Jours du mois -->
        <div class="calendar-days">
            @php
                // Déterminer le premier jour du mois dans la grille (peut être du mois précédent)
                $firstGridDay = clone $firstDay;
                $dayOfWeek = $firstGridDay->dayOfWeek;
                if ($dayOfWeek == 0) $dayOfWeek = 7; // Dimanche = 7 au lieu de 0
                $firstGridDay->subDays($dayOfWeek - 1);
                
                // Déterminer le dernier jour dans la grille (peut être du mois suivant)
                $lastGridDay = clone $lastDay;
                $dayOfWeek = $lastGridDay->dayOfWeek;
                if ($dayOfWeek == 0) $dayOfWeek = 7;
                $lastGridDay->addDays(7 - $dayOfWeek);
                
                // Jour actuel pour la boucle
                $currentDay = clone $firstGridDay;
                
                // Aujourd'hui pour la mise en évidence
                $today = Carbon\Carbon::today();
            @endphp
            
            @while($currentDay <= $lastGridDay)
                @php
                    $isCurrentMonth = $currentDay->month == $month;
                    $isToday = $currentDay->isSameDay($today);
                    $dateString = $currentDay->format('Y-m-d');
                    $hasExceptions = isset($exceptions[$dateString]);
                    $hasHolidays = isset($holidays[$dateString]);
                    $isSunday = $currentDay->dayOfWeek == 0;
                @endphp
                
                <div class="calendar-day {{ !$isCurrentMonth ? 'other-month' : '' }} {{ $isToday ? 'today' : '' }} {{ $isSunday ? 'sunday' : '' }}"
                     data-date="{{ $dateString }}">
                    <div class="day-header">
                        <span class="day-number">{{ $currentDay->day }}</span>
                        @if($hasExceptions || $hasHolidays)
                            <div class="day-indicators">
                                @if($hasExceptions)
                                    <span class="indicator exception" title="Exception"></span>
                                @endif
                                @if($hasHolidays)
                                    <span class="indicator holiday" title="Jour férié"></span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="day-events">
                        @if($hasExceptions)
                            @foreach($exceptions[$dateString] as $exception)
                                <div class="event exception" data-store="{{ $exception->store_id }}" data-type="exception">
                                    <div class="event-title">{{ $exception->store->nom }}</div>
                                    <div class="event-status {{ $exception->is_closed ? 'closed' : 'modified' }}">
                                        {{ $exception->is_closed ? 'Fermé' : 'Modifié' }}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        @if($hasHolidays)
                            @foreach($holidays[$dateString] as $holiday)
                                <div class="event holiday" data-store="{{ $holiday->store_id }}" data-type="holiday">
                                    <div class="event-title">{{ $holiday->holiday_name }}</div>
                                    <div class="event-store">{{ $holiday->store->nom }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                @php
                    $currentDay->addDay();
                @endphp
            @endwhile
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
    .calendar-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* En-tête */
    .calendar-header {
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

    .calendar-title {
        color: var(--text-light);
        font-size: 1.8rem;
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
        background-color: var(--light);
        border-radius: 2px;
    }

    .header-actions {
        display: flex;
        align-items: center;
        gap: 1.5rem;
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

    .action-button.secondary {
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
    }

    .action-button.secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .month-navigation {
        display: flex;
        align-items: center;
        gap: 1rem;
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 30px 0;
    }

    .current-month {
        color: var(--text-light);
        font-weight: 600;
        font-size: 1.1rem;
        text-transform: capitalize;
    }

    .nav-button {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--text-light);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .nav-button:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    /* Filtres */
    .calendar-filters {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        background-color: var(--text-light);
        padding: 1.25rem;
        border-radius: 20px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .filter-group label {
        font-weight: 500;
        color: var(--primary);
    }

    .filter-group select {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border);
        border-radius: 15px 0;
        background-color: #f9f9f9;
        color: var(--text-dark);
        min-width: 200px;
    }

    /* Calendrier */
    .calendar {
        background-color: var(--text-light);
        border-radius: 30px 0;
        overflow: hidden;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .calendar-weekdays {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background-color: var(--primary);
        color: var(--text-light);
        padding: 1rem 0;
        text-align: center;
        font-weight: 600;
    }

    .weekday.sunday {
        color: var(--light);
    }

    .calendar-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        grid-auto-rows: minmax(120px, auto);
    }

    .calendar-day {
        border: 1px solid var(--border);
        padding: 0.75rem;
        min-height: 120px;
        position: relative;
    }

    .calendar-day.other-month {
        background-color: #f9f9f9;
        opacity: 0.7;
    }

    .calendar-day.today {
        background-color: rgba(10, 46, 46, 0.05);
    }

    .calendar-day.sunday {
        background-color: rgba(142, 110, 83, 0.05);
    }

    .day-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .day-number {
        font-weight: 600;
        color: var(--primary);
    }

    .calendar-day.today .day-number {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--primary);
        color: var(--text-light);
        border-radius: 50%;
    }

    .day-indicators {
        display: flex;
        gap: 0.25rem;
    }

    .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .indicator.exception {
        background-color: var(--warning);
    }

    .indicator.holiday {
        background-color: var(--tertiary);
    }

    .day-events {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .event {
        padding: 0.5rem;
        border-radius: 10px 0;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .event:hover {
        transform: translateY(-2px);
    }

    .event.exception {
        background-color: rgba(245, 158, 11, 0.1);
        border-left: 3px solid var(--warning);
    }

    .event.holiday {
        background-color: rgba(142, 110, 83, 0.1);
        border-left: 3px solid var(--tertiary);
    }

    .event-title {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-status, .event-store {
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .event-status.closed {
        color: var(--error);
    }

    .event-status.modified {
        color: var(--warning);
    }

    .event-store {
        color: #666;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .calendar-days {
            grid-auto-rows: minmax(100px, auto);
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .calendar-title::before {
            display: none;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
            gap: 1rem;
        }

        .action-button, .month-navigation {
            width: 100%;
            justify-content: center;
        }

        .calendar-filters {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-group {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .filter-group select {
            width: 100%;
        }

        .calendar-weekdays {
            font-size: 0.8rem;
        }

        .calendar-days {
            grid-auto-rows: minmax(80px, auto);
        }

        .calendar-day {
            padding: 0.5rem;
            font-size: 0.8rem;
        }

        .event {
            padding: 0.35rem;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .calendar-filters, .calendar {
            background-color: #0A2E2E;
            border-color: #2A6363;
        }
        
        .filter-group label {
            color: var(--text-light);
        }
        
        .filter-group select {
            background-color: #1a3e3e;
            border-color: #2A6363;
            color: var(--text-light);
        }
        
        .calendar-day {
            border-color: #2A6363;
            color: var(--text-light);
        }
        
        .calendar-day.other-month {
            background-color: #0a2424;
        }
        
        .calendar-day.today {
            background-color: rgba(42, 99, 99, 0.2);
        }
        
        .calendar-day.sunday {
            background-color: rgba(142, 110, 83, 0.1);
        }
        
        .day-number {
            color: var(--text-light);
        }
        
        .event-title {
            color: var(--text-light);
        }
        
        .event-store {
            color: #aaa;
        }
    }
</style>

<script>
    // Filtrer les événements du calendrier
    function filterCalendarEvents() {
        const storeFilter = document.getElementById('store-filter').value;
        const typeFilter = document.getElementById('event-type-filter').value;
        const events = document.querySelectorAll('.event');
        
        events.forEach(event => {
            const storeId = event.dataset.store;
            const eventType = event.dataset.type;
            
            const matchesStore = storeFilter === 'all' || storeId === storeFilter;
            const matchesType = typeFilter === 'all' || eventType === typeFilter;
            
            event.style.display = matchesStore && matchesType ? 'block' : 'none';
        });
    }
    
    // Initialiser les icônes Font Awesome
    document.addEventListener('DOMContentLoaded', function() {
        // Si vous utilisez Lucide au lieu de Font Awesome
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
