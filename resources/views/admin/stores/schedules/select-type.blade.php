@extends('layouts.app')

@section('content')
<div class="select-type-container">
    <div class="select-type-header">
        <h1 class="select-type-title">Choisir le type d'horaire pour {{ $store->nom }}</h1>
        <a href="{{ route('admin.stores.schedules.index', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour aux horaires
        </a>
    </div>

    <div class="type-cards">
        <div class="type-card">
            <div class="card-icon">
                <i data-lucide="calendar"></i>
            </div>
            <h2>Horaire régulier</h2>
            <p>Définir les horaires d'ouverture habituels pour chaque jour de la semaine.</p>
            <a href="{{ route('admin.stores.schedules.regular', $store) }}" class="type-button">
                Choisir
            </a>
        </div>

        <div class="type-card">
            <div class="card-icon">
                <i data-lucide="alert-triangle"></i>
            </div>
            <h2>Exception</h2>
            <p>Définir une exception pour une date spécifique (fermeture exceptionnelle, horaires modifiés...).</p>
            <a href="{{ route('admin.stores.exceptions.create', $store) }}" class="type-button">
                Choisir
            </a>
        </div>

        <div class="type-card">
            <div class="card-icon">
                <i data-lucide="calendar-off"></i>
            </div>
            <h2>Jour férié</h2>
            <p>Définir un jour férié où le magasin sera fermé.</p>
            <a href="{{ route('admin.stores.holidays.create', $store) }}" class="type-button">
                Choisir
            </a>
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #429182; 
        --secondary: #337b8d; 
        --light-beige: #F9F5EF;
        --dark-beige: #1b5858; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --error: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 4px 12px rgba(27, 88, 88, 0.1);
    }

    .select-type-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .select-type-header {
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

    .select-type-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
        position: relative;
        padding-left: 1rem;
    }

    .select-type-title::before {
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

    .back-button {
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

    .back-button:hover {
        background: linear-gradient(135deg, #4BA793 0%, #2A5F6F 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(66, 145, 130, 0.3);
    }

    .type-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .type-card {
        background: var(--text-light);
        padding: 2rem;
        border-radius: 30px 0;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .type-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(66, 145, 130, 0.2);
    }

    .type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
        border-radius: 30px 0 0 0;
    }

    .card-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(66, 145, 130, 0.1) 0%, rgba(51, 123, 141, 0.1) 100%);
        border-radius: 50%;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .type-card:hover .card-icon {
        background: linear-gradient(135deg, rgba(66, 145, 130, 0.2) 0%, rgba(51, 123, 141, 0.2) 100%);
        transform: scale(1.05);
    }

    .card-icon i {
        width: 40px;
        height: 40px;
        color: var(--primary);
    }

    .type-card h2 {
        color: var(--primary);
        margin-bottom: 0.75rem;
        font-weight: 600;
    }

    .type-card p {
        color: #666;
        margin-bottom: 1.5rem;
        flex-grow: 1;
        line-height: 1.5;
    }

    .type-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        /* background: linear-gradient(135deg, #D2B48C 0%, #8a755b 100%); */
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);

        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        transition: all 0.3s ease;
        width: 93%;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(66, 145, 130, 0.2);
    }

    .type-button:hover {
        background: linear-gradient(135deg, #4BA793 0%, #2A6A7D 100%);
        /* background: linear-gradient(135deg, #887760 0%, #D2B48C 100%); */
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(66, 145, 130, 0.3);
    }

    @media (max-width: 768px) {
        .select-type-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .select-type-title {
            padding-left: 0;
        }

        .select-type-title::before {
            display: none;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Dark mode */
    @media (prefers-color-scheme: dark) {
        .select-type-container {
            background-color: #f1f1f1;
        }
        
        .select-type-header {
            background-color: #0a2e2e;
            border-color: #2a6363;
        }
        
        .select-type-title {
            color: var(--text-light);
        }
        
        .type-card {
            background-color: #0a2e2e;
            border-color: #2a6363;
        }
        
        .type-card h2 {
            color: var(--text-light);
        }
        
        .type-card p {
            color: #aaa;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection