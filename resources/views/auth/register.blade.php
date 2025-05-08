

<style>
    /* Header Styles */
    .app-header {
        background-color: #F5F5DC; 
        padding: 1rem 2rem;
        box-shadow: 0 2px 4px rgba(92, 64, 51, 0.1);
        border-bottom: 1px solid #37696b; 
        margin-top: 10px;   
        border-radius: 60px  30px ;

    }

    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        border-radius: 30px 60px ;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 600;
        color: #37696b; /* Marron foncé */
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        gap: 1.5rem;
    }

    .nav-link {
        color: #335c59; /* Marron foncé */
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
        padding: 0.5rem 0;
        position: relative;
    }

    .nav-link:hover {
        color: #496662; /* Marron clair */
    }

    .nav-link.active {
        color: #52a698; /* Marron clair */
    }

    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #52a698; /* Marron clair */
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
        color: #335c57; /* Marron foncé */
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
        color: #335c59; /* Marron foncé */
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #285852; /* Beige foncé */
        border-radius: 30px 60px ;
        background-color: #FFFDF8; /* Beige très pâle */
        color: #335c53; /* Marron foncé */
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #52a6a2; /* Marron clair */
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
        color: #33545c; /* Marron foncé */
    }

    .remember-me input {
        margin-right: 0.5rem;
        accent-color: #365358; /* Marron clair */
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
    }

    .forgot-password {
        color: #447a7a; /* Marron clair */
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #33545c; /* Marron foncé */
        text-decoration: underline;
    }

    .login-button {
        padding: 0.75rem 1.5rem;
        background-color: #185f64; /* Marron clair */
        color: white;
        border: none;
        border-radius: 30px 60px ;
        cursor: pointer;
        width: 100%;
        transition: background-color 0.3s ease;
        margin-left: auto;
        margin-right: auto;
    }

    .login-button:hover {
        background-color: #245a57; /* Marron plus foncé */
    }

    /* Dark mode styles */
    @media (prefers-color-scheme: dark) {
        .app-header {
            background-color: #1f3d3e; /* Marron foncé */
            border-bottom-color: #1f7a7a;
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
            background-color: #ffffff; /* Fond marron très foncé */
        }

        .login-box {
            background-color: #22463f; /* Marron foncé */
            border-color: #37696b;
        }

        .login-title,
        .form-label,
        .remember-me {
            color: #E0C9B4; /* Beige clair pour contraste */
        }

        .form-input {
            background-color: #37696b;
            border-color: #294d4e;
            color: #F0E0D0;
        }

        .forgot-password {
            color: #D2B48C;
        }

        .forgot-password:hover {
            color: #F5F5DC;
        }

        .login-button {
            background-color: #37696b;
        }

        .login-button:hover {
            background-color: #22463f;
        }
    }
</style>
   
   
   <style>
        .register-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-color: #ffffff; /* Beige très clair */
        }

        .register-box {
            width: 100%;
            max-width: 28rem;
            margin-top: -60px;
            padding: 2rem;
            background-color: #F5F5DC; /* Beige légèrement plus chaud */
            box-shadow: 0 4px 6px -1px rgba(92, 64, 51, 0.1), 0 2px 4px -1px rgba(92, 64, 51, 0.06);
            border-radius: 0.5rem;
            border: 1px solid #D2B48C; /* Bordure beige foncé */
        }

        .register-title {
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #22463f;
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
            color: #2c554b; 
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #264745; /* Beige foncé */
            border-radius:  60px 30px ;
            background-color: #FFFDF8; /* Beige très pâle */
            color: #22584f; 
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #33584f; /* Marron clair */
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
            color:#22463f; 
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: #335c59; /* Marron foncé */
            text-decoration: underline;
        }

        .register-button {
            padding: 0.75rem 1.5rem;
            background-color: #F5F5DC; 
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .register-button:hover {
            background-color: #F5F5DC; 
        }

        /* Dark mode styles */
        @media (prefers-color-scheme: dark) {
          

            .register-box {
                background-color: #22463f; 
                border-color:#7c7c62;
            }

            .register-title,
            .form-label {
                color: #E0C9B4; /* Beige clair pour contraste */
            }

            .form-input {
                background-color: #2d494a;
                border-color: #22463f;
                color: #F0E0D0;
            }

            .login-link {
                color: #b8ac7a;
            }

            .login-link:hover {
                color: #F5F5DC;
            }

            .register-button {
                background-color: #3a5250;
                border-radius: 60px  30px;

            }

            .register-button:hover {
                background-color: #26635e;

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

    <div class="register-container">

      

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
