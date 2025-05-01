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
        --primary-color: #8B4513;
        --secondary-color: #F5F5DC;
        --text-color: #333;
        --border-color: #D2B48C;
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
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .select-type-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }

    .back-button {
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

    .back-button:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .type-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .type-card {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .card-icon {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--secondary-color);
        border-radius: 50%;
        margin-bottom: 1rem;
    }

    .card-icon i {
        width: 32px;
        height: 32px;
        color: var(--primary-color);
    }

    .type-card h2 {
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .type-card p {
        color: #666;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .type-button {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.2s;
        width: 100%;
        text-align: center;
    }

    .type-button:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .select-type-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .back-button {
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