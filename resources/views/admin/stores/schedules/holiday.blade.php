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
        --primary: #2a6363;
        --primary-light: #3a7a7a;
        --secondary: #0a2e2e;
        --accent: #D2B48C;
        --accent-light: #e5d5b8;
        --text: #333333;
        --text-light: #f8f8f8;
        --border: #c4b7a0;
        --error: #e74c3c;
        --success: #2ecc71;
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
        background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
        border-radius: 30px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .schedule-form-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--accent);
        margin: 0;
        letter-spacing: -0.5px;
    }

    .back-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background-color: var(--primary);
        color: var(--text-light);
        border: none;
        border-radius: 30px 0;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .alert {
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-error {
        background-color: rgba(231, 76, 60, 0.1);
        color: var(--error);
        border-left: 4px solid var(--error);
    }

    .schedule-form {
        background: var(--secondary);
        padding: 2rem;
        border-radius: 30px 0;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border);
    }

    .form-group {
        margin-bottom: 2rem;
    }

    .form-group label {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: 0.2px;
        
    }

    .form-control {
        width: 97%;
        padding: 1rem;
        border: 2px solid var(--border);
        border-radius: 10px 0;
        background: white;
        color: var(--text);
        font-size: 1rem;
        transition: all 0.3s ease;
        margin-right:90px;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(42, 99, 99, 0.2);
        outline: none;
    }

    .form-actions {
        display: flex;
        justify-content: flex-start;
        padding-left: 20px;
        margin-top: 2rem;
    }

    .submit-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.25rem;
        background-color: var(--primary);
        color: var(--accent);
        border: none;
        border-radius: 30px 0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 1.1rem;
        margin-left: auto;
        margin-right: -10px;
        margin-top: 20px;
        font-family: Georgia, 'Times New Roman', Times, serif;
    }

    .submit-btn:hover {
        background-color: var(--secondary);
        transform: translateY(-2px);
    }

    .error-message {
        color: var(--error);
        font-size: 0.9rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .schedule-form-header {
            flex-direction: column;
            gap: 1.25rem;
            text-align: center;
        }

        .back-button {
            width: 100%;
            justify-content: center;
        }

        .schedule-form {
            padding: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection