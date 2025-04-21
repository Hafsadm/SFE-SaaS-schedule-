
<style>/* Header Styles */
    .app-header {
        background-color: #F5F5DC; /* Beige clair */
        padding: 1rem 2rem;
        box-shadow: 0 2px 4px rgba(92, 64, 51, 0.1);
        border-bottom: 1px solid #D2B48C; /* Bordure beige foncé */
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }
    
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .logo {
        font-size: 1.5rem;
        font-weight: 600;
        color: #5C4033; /* Marron foncé */
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .logo:hover {
        color: #A67C52; /* Marron clair */
    }
    
    .nav-links {
        display: flex;
        gap: 1.5rem;
    }
    
    .nav-link {
        color: #5C4033; /* Marron foncé */
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.5rem 0;
        position: relative;
    }
    
    .nav-link:hover {
        color: #A67C52; /* Marron clair */
    }
    
    .nav-link.active {
        color: #A67C52; /* Marron clair */
    }
    
    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #A67C52; /* Marron clair */
    }
    
    /* Dark mode styles for header */
    @media (prefers-color-scheme: dark) {
        .app-header {
            background-color: #3E2D1F; /* Marron foncé */
            border-bottom-color: #5C4033;
        }
        
        .logo, .nav-link {
            color: #E0C9B4; /* Beige clair */
        }
        
        .logo:hover, .nav-link:hover {
            color: #D2B48C; /* Beige doré */
        }
        
        .nav-link.active {
            color: #D2B48C;
        }
        
        .nav-link.active::after {
            background-color: #D2B48C;
        }
    }
    
    /* Adjust container to account for fixed header */
    .register-container {
        padding-top: 80px; /* Hauteur du header */
        min-height: calc(100vh - 80px);
    }</style>
    <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-color: #F8F4E6; /* Beige très clair */
        }

        .register-box {
            width: 100%;
            max-width: 28rem;
            padding: 2rem;
            background-color: #F5F5DC; /* Beige légèrement plus chaud */
            box-shadow: 0 4px 6px -1px rgba(92, 64, 51, 0.1), 0 2px 4px -1px rgba(92, 64, 51, 0.06);
            border-radius: 0.5rem;
            border: 1px solid #D2B48C; /* Bordure beige foncé */
        }

        .register-title {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #5C4033; /* Marron foncé */
            font-weight: 600;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #5C4033; /* Marron foncé */
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #D2B48C; /* Beige foncé */
            border-radius: 0.375rem;
            background-color: #FFFDF8; /* Beige très pâle */
            color: #5C4033; /* Marron foncé */
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #A67C52; /* Marron clair */
            box-shadow: 0 0 0 2px rgba(166, 124, 82, 0.2);
        }

        .error-message {
            margin-top: 0.5rem;
            color: #C17C74; /* Rouge-marron */
            font-size: 0.875rem;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
        }

        .login-link {
            color: #A67C52; /* Marron clair */
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #5C4033; /* Marron foncé */
            text-decoration: underline;
        }

        .register-button {
            padding: 0.75rem 1.5rem;
            background-color: #A67C52; /* Marron clair */
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .register-button:hover {
            background-color: #8C5E3B; /* Marron plus foncé */
        }

        /* Dark mode styles */
        @media (prefers-color-scheme: dark) {
            .register-container {
                background-color: #2A2118; /* Fond marron très foncé */
            }

            .register-box {
                background-color: #3E2D1F; /* Marron foncé */
                border-color: #5C4033;
            }

            .register-title,
            .form-label {
                color: #E0C9B4; /* Beige clair pour contraste */
            }

            .form-input {
                background-color: #4A3A2D;
                border-color: #5C4033;
                color: #F0E0D0;
            }

            .login-link {
                color: #D2B48C;
            }

            .login-link:hover {
                color: #F5F5DC;
            }

            .register-button {
                background-color: #8C5E3B;
            }

            .register-button:hover {
                background-color: #A67C52;
            }
        }
    </style>

    <div class="register-container">

        <header class="app-header">
            <div class="header-container">
                <a href="/" class="logo">Horaire</a>
                <nav class="nav-links">
                    <a href="{{ route('login') }}" class="nav-link active">Connexion</a>
                    <a href="{{ route('register') }}" class="nav-link">Inscription</a>
                </nav>
            </div>
        </header>

        <div class="register-box">
            <div class="register-title">
                {{ __('Inscription') }}
            </div>
            

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label" for="name">{{ __('Nom') }}</label>
                    <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">{{ __('Mot de passe') }}</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">{{ __('Confirmer le mot de passe') }}</label>
                    <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password">
                </div>

                <div class="form-footer">
                    <a class="login-link" href="{{ route('login') }}">
                        {{ __('Déjà un compte ? Se connecter') }}
                    </a>
                    <button type="submit" class="register-button">
                        {{ __('S\'inscrire') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
