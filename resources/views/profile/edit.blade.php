@extends('layouts.app')
@section('content')
    <style>
        /* Variables de couleur */
        :root {
    /* Palette principale - tons chauds beige/marron */
    --primary: #8B5A2B;  /* Marron chaud */
    --primary-hover: #A67C52;  /* Marron plus clair */
    --secondary: #D2B48C;  /* Beige doré */
    --secondary-hover: #E0C9B4;  /* Beige clair */
    
    /* Accents */
    --danger: #C17C74;  /* Rouge terreux */
    --danger-hover: #B5655D;
    --success: #82B183;  /* Vert mousse */
    
    /* Neutres */
    --text: #3E2723;  /* Marron très foncé */
    --text-light: #5D4037;  /* Marron foncé */
    --bg: #F5F5DC;  /* Beige très clair */
    --card-bg: #FFFFFF;  /* Blanc pur */
    --border: #D7CCC8;  /* Beige grisâtre */
}

/* Dark mode raffiné */
@media (prefers-color-scheme: dark) {
    :root {
        --primary: #D2B48C;  /* Beige doré devient primaire */
        --primary-hover: #E0C9B4;
        --text: #EFEBE9;  /* Beige très clair */
        --text-light: #D7CCC8;
        --bg: #968d74;  /* Noir chaud */
        --card-bg: #44372a;  /* Marron foncé */
        --border: #5d5037;  /* Marron moyen */
        --secondary: #4E342E;  /* Marron sombre */
        --secondary-hover: #3E2723;
    }
}

        /* Base */
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        /* Conteneur */
        .container {
            max-width: 42rem;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        /* Cartes */
        .card {
            background: var(--card-bg);
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-description {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }

        /* Formulaires */
        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.375rem;
            background-color: var(--card-bg);
            color: var(--text);
            font-size: 1rem;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Boutons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.15s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text);
        }

        .btn-secondary:hover {
            background-color: var(--secondary-hover);
        }

        /* Messages */
        .message {
            font-size: 0.875rem;
            margin-left: 1rem;
        }

        .message-success {
            color: var(--success);
        }

        .message-error {
            color: var(--danger);
        }

        /* Flex utilities */
        .flex {
            display: flex;
            align-items: center;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 50;
        }

        .modal-overlay.show {
            display: block;
        }

        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            border-radius: 0.5rem;
            padding: 1.5rem;
            width: 90%;
            max-width: 24rem;
            z-index: 51;
            display: none;
        }

        .modal.show {
            display: block;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .modal-content {
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .container {
                padding: 0 0.75rem;
            }
            
            .card {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="container">
        <!-- Profil -->
        <div class="card">
            <h2 class="card-title">Profile Information</h2>
            <p class="card-description">Update your account's profile information and email address.</p>
            
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')
                
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                    @error('name')
                        <p class="message-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                    @error('email')
                        <p class="message-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    @if (session('status') === 'profile-updated')
                        <p class="message-success">Saved</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Mot de passe -->
        <div class="card">
            <h2 class="card-title">Update Password</h2>
            <p class="card-description">Ensure your account is using a long, random password to stay secure.</p>
            
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('put')
                
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="message-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="message-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                    @if (session('status') === 'password-updated')
                        <p class="message-success">Saved</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Suppression de compte -->
        <div class="card">
            <h2 class="card-title">Delete Account</h2>
            <p class="card-description">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            
            <button 
                onclick="showModal()"
                class="btn btn-danger"
            >
                Delete Account
            </button>
        </div>
    </div>

    <!-- Modal de suppression -->
    <div id="modal-overlay" class="modal-overlay" onclick="hideModal()"></div>
    
    <div id="modal" class="modal">
        <h3 class="modal-title">Are you sure you want to delete your account?</h3>
        <p class="modal-content">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            
            <div class="form-group">
                <label for="delete_password">Password</label>
                <input id="delete_password" name="password" type="password" placeholder="Your password">
                @error('password', 'userDeletion')
                    <p class="message-error">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex gap-2" style="justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="hideModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </div>

    <script>
        // Gestion de la modale
        function showModal() {
            document.getElementById('modal-overlay').classList.add('show');
            document.getElementById('modal').classList.add('show');
        }
        
        function hideModal() {
            document.getElementById('modal-overlay').classList.remove('show');
            document.getElementById('modal').classList.remove('show');
        }
        
        // Fermer avec la touche Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') hideModal();
        });
        
        // Masquer les messages après 3 secondes
        setTimeout(() => {
            const messages = document.querySelectorAll('.message-success');
            messages.forEach(msg => msg.style.display = 'none');
        }, 3000);
    </script>


@endsection
