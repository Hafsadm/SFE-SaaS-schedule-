@extends('layouts.app')

@section('content')
<div class="type-selection-container">
    <div class="type-selection-header">
        <h1 class="type-selection-title">Ajouter un horaire pour {{ $store->nom }}</h1>
        <a href="{{ route('admin.stores.schedules.index', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour aux horaires
        </a>
    </div>

    <div class="type-cards">
        <a href="{{ route('admin.stores.schedules.create.regular', $store) }}" class="type-card">
            <div class="type-icon">
                <i data-lucide="calendar"></i>
            </div>
            <h2>Horaire régulier</h2>
            <p>Définir les horaires habituels pour un jour de la semaine</p>
        </a>

        <a href="{{ route('admin.stores.schedules.create.exception', $store) }}" class="type-card">
            <div class="type-icon">
                <i data-lucide="alert-circle"></i>
            </div>
            <h2>Exception</h2>
            <p>Définir un horaire exceptionnel pour une date spécifique</p>
        </a>

        <a href="{{ route('admin.stores.schedules.create.holiday', $store) }}" class="type-card">
            <div class="type-icon">
                <i data-lucide="star"></i>
            </div>
            <h2>Jour férié</h2>
            <p>Définir un horaire pour un jour férié</p>
        </a>
    </div>
</div>

<style>
    :root {
        --primary-color: #8B4513;
        --secondary-color: #F5F5DC;
        --text-color: #333;
        --border-color: #D2B48C;
    }

    .type-selection-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .type-selection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .type-selection-title {
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
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        text-decoration: none;
        color: var(--text-color);
        transition: all 0.2s;
    }

    .type-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .type-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        background-color: var(--secondary-color);
        border-radius: 50%;
        margin-bottom: 1rem;
    }

    .type-icon i {
        width: 32px;
        height: 32px;
        color: var(--primary-color);
    }

    .type-card h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0 0 0.5rem 0;
        text-align: center;
    }

    .type-card p {
        text-align: center;
        margin: 0;
        color: #666;
    }

    @media (max-width: 768px) {
        .type-selection-header {
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