@extends('layouts.app')

@section('content')
<div class="schedules-dashboard-container">
    <!-- En-tête avec statistiques -->
    <div class="dashboard-header">
        <div class="header-content">
            <h1 class="dashboard-title">Tableau de bord des horaires</h1>
            <div class="header-actions">
                <a href="{{ route('admin.schedules.calendar') }}" class="action-button secondary">
                    <i data-lucide="calendar"></i>
                    Vue calendrier
                </a>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon open">
                    <i data-lucide="check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Ouverts aujourd'hui</h3>
                    <div class="stat-value">{{ $stats['open_today'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon closed">
                    <i data-lucide="x-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Fermés aujourd'hui</h3>
                    <div class="stat-value">{{ $stats['closed_today'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon exception">
                    <i data-lucide="alert-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>Exceptions à venir</h3>
                    <div class="stat-value">{{ $stats['total_exceptions'] }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon holiday">
                    <i data-lucide="calendar-off"></i>
                </div>
                <div class="stat-content">
                    <h3>Jours fériés à venir</h3>
                    <div class="stat-value">{{ $stats['upcoming_holidays'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="dashboard-content">
        <!-- Sidebar avec exceptions et jours fériés à venir -->
        <div class="dashboard-sidebar">
            <!-- Exceptions à venir -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h2>Exceptions à venir</h2>
                    <span class="count-badge">{{ count($upcomingExceptions) }}</span>
                </div>
                
                @if($upcomingExceptions->isEmpty())
                    <div class="empty-state small">
                        <i data-lucide="calendar-x"></i>
                        <p>Aucune exception à venir</p>
                    </div>
                @else
                    <div class="upcoming-list">
                        @foreach($upcomingExceptions as $exception)
                            <div class="upcoming-item">
                                <div class="date-badge exception">
                                    <span class="date-day">{{ $exception->exception_date->format('d') }}</span>
                                    <span class="date-month">{{ $exception->exception_date->locale('fr')->format('M') }}</span>
                                </div>
                                <div class="upcoming-details">
                                    <h4>{{ $exception->store->nom }}</h4>
                                    <p class="upcoming-reason">{{ $exception->exception_raison }}</p>
                                    <div class="upcoming-status">
                                        @if($exception->is_closed)
                                            <span class="status-badge closed">Fermé</span>
                                        @else
                                            <div class="mini-hours">
                                                @foreach($exception->time_slots as $slot)
                                                    <span class="mini-slot">{{ $slot['start'] }}-{{ $slot['end'] }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('admin.stores.exceptions.edit', [$exception->store, $exception]) }}" class="upcoming-action" title="Modifier">
                                    <i data-lucide="edit"></i>
                                </a>
                            </div>
                        @endforeach
                        
                        @if(count($upcomingExceptions) >= 5)
                            <a href="#" class="view-all-link">
                                Voir toutes les exceptions
                                <i data-lucide="chevron-right"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Jours fériés à venir -->
            <div class="sidebar-section">
                <div class="section-header">
                    <h2>Jours fériés à venir</h2>
                    <span class="count-badge">{{ count($upcomingHolidays) }}</span>
                </div>
                
                @if($upcomingHolidays->isEmpty())
                    <div class="empty-state small">
                        <i data-lucide="calendar-off"></i>
                        <p>Aucun jour férié à venir</p>
                    </div>
                @else
                    <div class="upcoming-list">
                        @foreach($upcomingHolidays as $holiday)
                            <div class="upcoming-item">
                                <div class="date-badge holiday">
                                    <span class="date-day">{{ $holiday->holiday_date->format('d') }}</span>
                                    <span class="date-month">{{ $holiday->holiday_date->locale('fr')->format('M') }}</span>
                                </div>
                                <div class="upcoming-details">
                                    <h4>{{ $holiday->store->nom }}</h4>
                                    <p class="upcoming-reason">{{ $holiday->holiday_name }}</p>
                                    <div class="upcoming-status">
                                        <span class="status-badge closed">Fermé</span>
                                    </div>
                                </div>
                                <a href="{{ route('admin.stores.holidays.edit', [$holiday->store, $holiday]) }}" class="upcoming-action" title="Modifier">
                                    <i data-lucide="edit"></i>
                                </a>
                            </div>
                        @endforeach
                        
                        @if(count($upcomingHolidays) >= 5)
                            <a href="#" class="view-all-link">
                                Voir tous les jours fériés
                                <i data-lucide="chevron-right"></i>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
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
    .schedules-dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
        
    }


    /* En-tête avec statistiques */
    .dashboard-header {
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .dashboard-title {
        font-size: 1.8rem;
        font-weight: 600;
        color:var(--secondary);
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .dashboard-title::before {
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

    .action-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
        box-shadow: var(--shadow);
    }

    .action-button.primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--border-color) 100%);
        color: var(--background);
    }

    .action-button.secondary {
        background: var(--card-bg);
        color: var(--secondary-light);
        border: 1px solid var(--secondary);
    }

    .action-button.primary:hover {
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(66, 145, 130, 0.2);
        
    }

    .action-button.secondary:hover {
        background: rgba(27, 88, 88, 0.05);
        transform: translateY(-2px);
        color: var(--primary)  ;
    }

    /* Cartes de statistiques */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: var(--transition);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(27, 88, 88, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(66, 145, 130, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
    }

    .stat-icon.open {
        background: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .stat-icon.closed {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error);
    }

    .stat-icon.exception {
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning);
    }

    .stat-icon.holiday {
        background: rgba(23, 162, 184, 0.1);
        color: var(--info);
    }

    .stat-content h3 {
        margin: 0 0 0.25rem 0;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--secondary-light);
    }

    /* Contenu principal */
    .dashboard-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    /* Section header */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .section-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: var(--secondary-light);
        position: relative;
        padding-left: 0.75rem;
    }

    .section-header h2::before {
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

    .count-badge {
        background: rgba(66, 145, 130, 0.1);
        color: var(--light);
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius:  0;
    }

    /* État vide */
    .empty-state {
        display: grid-template-columns: repeat(3, 1fr);

        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        background: rgba(249, 245, 239, 0.5);
        border-radius: 30px 0;
        border: 1px dashed var(--border-color);
        color: var(--text-muted);
        text-align: center;
    }

    .empty-state i {
        color: var(--text-muted);
        margin-bottom: 1rem;
        width: 48px;
        height: 48px;
    }

    .empty-state p {
        margin: 0 0 1rem 0;
        font-size: 1.1rem;
    }

    .empty-state.small {
        padding: 2rem 1rem;
    }

    .empty-state.small i {
        width: 36px;
        height: 36px;
        margin-bottom: 0.75rem;
    }

    .empty-state.small p {
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    /* Badges de statut */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 30px 0;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-badge.open {
        background: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .status-badge.closed {
        background: rgba(220, 53, 69, 0.1);
        color: var(--error);
    }

    /* Sidebar */
    .dashboard-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .sidebar-section {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 30px 0;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    /* Liste des événements à venir */
      .upcoming-list {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .upcoming-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        
        padding: 1rem;
        background: rgba(249, 245, 239, 0.5);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }

    .upcoming-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 10px rgba(27, 88, 88, 0.1);
    }

    .date-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        height: 50px;
        border-radius: 10px 0;
        padding: 0.25rem;
    }

    .date-badge.exception {
        background: rgba(255, 193, 7, 0.1);
        color: var(--warning);
        border: 1px solid rgba(255, 193, 7, 0.3);
    }

    .date-badge.holiday {
        background: rgba(23, 162, 184, 0.1);
        color: var(--info);
        border: 1px solid rgba(23, 162, 184, 0.3);
    }

    .date-day {
        font-size: 1.2rem;
        font-weight: 700;
        line-height: 1;
    }

    .date-month {
        font-size: 0.8rem;
    
        text-transform: uppercase;
    }
    .upcoming-details {
        flex: 1;
    }

    .upcoming-details h4 {
        margin: 0 0 0.25rem 0;
        font-size: 0.95rem;
        color: var(--secondary-light);
    }

    .upcoming-reason {
        margin: 0 0 0.5rem 0;
        font-size: 0.85rem;
        color: var(--light);
    }

    .upcoming-status {
        display: flex;
        align-items: center;
    }

    .mini-hours {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .mini-slot {
        font-size: 0.75rem;
        padding: 0.1rem 0.4rem;
        background: rgba(27, 88, 88, 0.05);
        border-radius: 4px;
    }

    .upcoming-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(27, 88, 88, 0.05);
        color: var(--secondary-light);
        transition: var(--transition);
    }

    .upcoming-action:hover {
        background: rgba(27, 88, 88, 0.1);
        transform: translateY(-2px);
    }

    .upcoming-action i {
        width: 16px;
        height: 16px;
    }

    .view-all-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        margin-top: 0.5rem;
        background: rgba(27, 88, 88, 0.05);
        color: var(--secondary);
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .view-all-link:hover {
        background: rgba(27, 88, 88, 0.1);
    }

    .view-all-link i {
        width: 16px;
        height: 16px;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .dashboard-sidebar {
            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .header-actions {
            width: 100%;
        }
        
        .action-button {
            flex: 1;
            justify-content: center;
        }
        
        .dashboard-sidebar {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        :root {
            --card-bg: #0a2e2e;
            --background: #ffffff;
            --text-dark: #ffffff;
            --text-muted: #ffffff;
            --border-color: #2a6363;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .schedules-dashboard-container {
            background-color: var(--background);
        }
        
        .stat-card, 
        .sidebar-section {
            background-color: var(--card-bg);
            border-color: var(--border-color);
        }
        
        .empty-state, 
        .upcoming-item {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--border-color);
        }
        
        .mini-slot {
            background: rgba(27, 88, 88, 0.2);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Lucide
        lucide.createIcons();
        
        // Animation des alertes
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length > 0) {
            setTimeout(function() {
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);
        }
    });
</script>
@endsection
