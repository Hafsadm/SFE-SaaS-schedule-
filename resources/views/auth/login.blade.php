
    <style>
        /* Header Styles */
        .app-header {
            background-color: #F5F5DC; /* Beige clair */
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(92, 64, 51, 0.1);
            border-bottom: 1px solid #D2B48C; /* Bordure beige foncé */
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
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-link {
            color: #5C4033; /* Marron foncé */
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
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

        /* Existing login styles */
        .login-container {
            min-height: calc(100vh - 72px); /* Adjust for header height */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-color: #F8F4E6; /* Beige très clair */
        }

        .login-box {
            width: 100%;
            max-width: 28rem;
            padding: 1.5rem;
            background-color: #F5F5DC; /* Beige légèrement plus chaud */
            box-shadow: 0 4px 6px -1px rgba(92, 64, 51, 0.1), 0 2px 4px -1px rgba(92, 64, 51, 0.06);
            border-radius: 0.5rem;
            border: 1px solid #D2B48C; /* Bordure beige foncé */
        }

        .login-title {
            margin-bottom: 1rem;
            font-size: 1.25rem;
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

        .remember-me {
            display: flex;
            align-items: center;
            margin-top: 1rem;
            color: #5C4033; /* Marron foncé */
        }

        .remember-me input {
            margin-right: 0.5rem;
            accent-color: #A67C52; /* Marron clair */
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
        }

        .forgot-password {
            color: #A67C52; /* Marron clair */
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #5C4033; /* Marron foncé */
            text-decoration: underline;
        }

        .login-button {
            padding: 0.75rem 1.5rem;
            background-color: #A67C52; /* Marron clair */
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #8C5E3B; /* Marron plus foncé */
        }

        /* Dark mode styles */
        @media (prefers-color-scheme: dark) {
            .app-header {
                background-color: #3E2D1F; /* Marron foncé */
                border-bottom-color: #5C4033;
            }
            
            .logo, .nav-link {
                color: #E0C9B4; /* Beige clair */
            }
            
            .nav-link:hover, .nav-link.active {
                color: #D2B48C; /* Beige doré */
            }
            
            .nav-link.active::after {
                background-color: #D2B48C;
            }

            .login-container {
                background-color: #2A2118; /* Fond marron très foncé */
            }

            .login-box {
                background-color: #3E2D1F; /* Marron foncé */
                border-color: #5C4033;
            }

            .login-title,
            .form-label,
            .remember-me {
                color: #E0C9B4; /* Beige clair pour contraste */
            }

            .form-input {
                background-color: #4A3A2D;
                border-color: #5C4033;
                color: #F0E0D0;
            }

            .forgot-password {
                color: #D2B48C;
            }

            .forgot-password:hover {
                color: #F5F5DC;
            }

            .login-button {
                background-color: #8C5E3B;
            }

            .login-button:hover {
                background-color: #A67C52;
            }
        }
    </style>

    <header class="app-header">
        <div class="header-container">
            <a href="/" class="logo">Horaire</a>
            <nav class="nav-links">
                <a href="{{ route('login') }}" class="nav-link active">Connexion</a>
                <a href="{{ route('register') }}" class="nav-link">Inscription</a>
            </nav>
        </div>
    </header>

    <div class="login-container">
        <div class="login-box">
            <div class="login-title">
                {{ __('Connexion') }}
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label class="form-label" for="email">{{ __('Email') }}</label>
                    <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">{{ __('Mot de passe') }}</label>
                    <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="remember-me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">{{ __('Se souvenir de moi') }}</label>
                </div>

                <div class="form-footer">
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif

                    <button type="submit" class="login-button">
                        {{ __('Se connecter') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
