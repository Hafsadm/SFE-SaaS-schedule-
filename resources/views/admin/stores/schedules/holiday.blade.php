@extends('layouts.app')

@section('content')
<div class="schedule-form-container">
    <div class="schedule-form-header">
        <h1 class="schedule-form-title">Jour férié pour {{ $store->nom }}</h1>
        <a href="{{ route('admin.stores.schedules.select-type', $store) }}" class="back-button">
            <i data-lucide="arrow-left"></i>
            Retour au choix du type
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.stores.holidays.store', $store) }}" method="POST" class="schedule-form">
        @csrf

        <div class="form-group">
            <label for="holiday_date">Date du jour férié</label>
            <input type="date" name="holiday_date" id="holiday_date" class="form-control" value="{{ old('holiday_date') }}" min="{{ date('Y-m-d') }}" required>
            @error('holiday_date')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="holiday_name">Nom du jour férié</label>
            <input type="text" name="holiday_name" id="holiday_name" class="form-control" value="{{ old('holiday_name') }}" required
                   placeholder="Ex: Noël, Pâques, 1er Mai...">
            @error('holiday_name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">
                <i data-lucide="save"></i>
                Enregistrer le jour férié
            </button>
        </div>
    </form>
</div>

<style>
    :root {
        --primary-color: #8B4513;
        --secondary-color: #F5F5DC;
        --text-color: #333;
        --border-color: #D2B48C;
        --error-color: #F44336;
    }

    .schedule-form-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .schedule-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem;
        background-color: var(--secondary-color);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .schedule-form-title {
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

    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 4px;
    }

    .alert-error {
        background-color: #FFEBEE;
        color: var(--error-color);
        border: 1px solid var(--error-color);
    }

    .schedule-form {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-color);
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: white;
        color: var(--text-color);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .submit-btn:hover {
        background-color: #6B2B00;
        transform: translateY(-1px);
    }

    .error-message {
        color: var(--error-color);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .schedule-form-header {
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