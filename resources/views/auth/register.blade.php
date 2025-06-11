<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Inscription') }} - {{ config('app.name', 'Horaire') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }}; 
            --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }}; 
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', sans-serif;
            background: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Particules animées en arrière-plan */
        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .particle:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .particle:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 10%;
            animation-delay: 2s;
        }

        .particle:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        .particle:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            right: 20%;
            animation-delay: 1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        .register-container {
            display: flex;
            width: 70%;
            max-width: 1100px;
            height: 650px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            position: relative;
            z-index: 1;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Section gauche - Illustration animée */
        .left-section {
            flex: 1;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--text-light);
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .illustration-container {
            width: 280px;
            height: 280px;
            position: relative;
            margin-bottom: 30px;
            z-index: 2;
        }

        .animated-illustration {
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0);
            }
        }

        /* SVG Animation personnalisée pour l'inscription */
        .signup-management-svg {
            width: 200px;
            height: 200px;
        }

        .user-icon {
            animation: bounce 2s ease-in-out infinite;
        }

        .form-icon {
            animation: bounce 2s ease-in-out infinite 0.5s;
        }

        .check-icon {
            animation: bounce 2s ease-in-out infinite 1s;
        }

        .gear {
            animation: gearRotate 8s linear infinite;
            transform-origin: center;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes gearRotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(-360deg); }
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-icon {
            position: absolute;
            width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: floatIcon 4s ease-in-out infinite;
        }

        .floating-icon:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-icon:nth-child(2) {
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-icon:nth-child(3) {
            bottom: 30%;
            left: 20%;
            animation-delay: 1s;
        }

        @keyframes floatIcon {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.6;
            }
            50% {
                transform: translateY(-15px) rotate(180deg);
                opacity: 1;
            }
        }

        .left-content {
            z-index: 2;
            text-align: center;
        }

        .left-content h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 12px;
            line-height: 1.3;
            animation: fadeInUp 1s ease-out 0.5s both;
        }

        .left-content p {
            font-size: 14px;
            font-weight: 300;
            opacity: 0.9;
            line-height: 1.5;
            animation: fadeInUp 1s ease-out 0.7s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section droite - Formulaire */
        .right-section {
            flex: 1;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeInRight 1s ease-out 0.3s both;
            overflow-y: auto;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .register-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .register-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 100%);
            border-radius: 12px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-size: 20px;
            animation: iconBounce 2s ease-in-out infinite;
        }

        @keyframes iconBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .register-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .register-subtitle {
            font-size: 14px;
            color: #6B7280;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            height: 42px;
            padding: 0 15px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #F7F9F8;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--primary);
            transition: all 0.3s ease;
            position: relative;
        }

        .form-input::placeholder {
            color: #ADAEBC;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--secondary-light);
            box-shadow: 0 0 0 3px rgba(10, 46, 46, 0.1);
            transform: translateY(-2px);
        }

        .error-message {
            margin-top: 0.5rem;
            color: var(--error);
            font-size: 0.875rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .register-button {
            width: 100%;
            height: 48px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--tertiary) 100%);
            border: none;
            border-radius: 10px;
            color: var(--text-light);
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: var(--card-shadow);
            margin-bottom: 18px;
            position: relative;
            overflow: hidden;
        }

        .register-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .register-button:hover::before {
            left: 100%;
        }

        .register-button:hover {
            transform: translateY(-2px);
            box-shadow: 0px 6px 12px rgba(10, 46, 46, 0.2);
        }

        .register-button:active {
            transform: translateY(0);
        }

        .register-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15px;
        }

        .login-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 400;
            transition: all 0.3s ease;
            position: relative;
        }

        .login-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background-color: var(--tertiary);
            transition: all 0.3s ease;
        }

        .login-link:hover::after {
            width: 100%;
            left: 0;
        }

        .login-link:hover {
            color: var(--tertiary);
        }

        /* Navigation Header */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            box-shadow: var(--card-shadow);
            z-index: 1000;
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logo:hover {
            color: var(--tertiary);
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            position: relative;
        }

        .nav-link:hover {
            color: var(--tertiary);
            background: rgba(10, 46, 46, 0.1);
        }

        .nav-link.active {
            color: var(--text-light);
            background: linear-gradient(135deg, var(--primary) 0%, var(--tertiary) 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 80px;
            }

            .register-container {
                flex-direction: column;
                max-width: 400px;
                height: auto;
                margin: 10px;
            }

            .left-section {
                padding: 30px 20px;
                min-height: 250px;
            }

            .illustration-container {
                width: 200px;
                height: 200px;
                margin-bottom: 20px;
            }

            .signup-management-svg {
                width: 150px;
                height: 150px;
            }

            .left-content h1 {
                font-size: 20px;
            }

            .left-content p {
                font-size: 13px;
            }

            .right-section {
                padding: 30px 25px;
            }

            .register-title {
                font-size: 24px;
            }

            .form-footer {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 1rem;
            }

            .nav-link {
                padding: 0.4rem 0.8rem;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 5px;
                max-width: 350px;
            }

            .right-section {
                padding: 25px 20px;
            }

            .app-header {
                padding: 0.8rem 1rem;
            }

            .logo {
                font-size: 1.3rem;
            }
        }

        /* Animation des particules dynamiques */
        @keyframes floatUp {
            to {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* Scrollbar personnalisée pour la section droite */
        .right-section::-webkit-scrollbar {
            width: 6px;
        }

        .right-section::-webkit-scrollbar-thumb {
            background-color: var(--primary);
            border-radius: 4px;
        }

        .right-section {
            scrollbar-width: thin;
            scrollbar-color: var(--primary) transparent;
        }
    </style>
</head>
<body>
    <!-- Header de navigation -->
    <header class="app-header">
        <div class="header-container">
            <a href="/" class="logo">{{ config('app.name', 'Horaire') }}</a>
            <nav class="nav-links">
                <a href="{{ route('login') }}" class="nav-link">{{ __('Connexion') }}</a>
                <a href="{{ route('register') }}" class="nav-link active">{{ __('Inscription') }}</a>
            </nav>
        </div>
    </header>

    <!-- Animation d'arrière-plan -->
    <div class="background-animation">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="register-container">
        <!-- Section gauche avec illustration animée -->
        <div class="left-section">
            <div class="floating-elements">
                <div class="floating-icon"></div>
                <div class="floating-icon"></div>
                <div class="floating-icon"></div>
            </div>
            
            <div class="illustration-container">
                <div class="animated-illustration">
                    <!-- SVG Animation personnalisée pour l'inscription -->
                    <svg class="signup-management-svg" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                        <!-- Utilisateur principal -->
                        <g class="user-icon">
                            <circle cx="100" cy="70" r="25" fill="none" stroke="#ffffff" stroke-width="3"/>
                            <circle cx="100" cy="60" r="12" fill="#C69C72"/>
                            <path d="M85 85 Q100 75 115 85 L115 95 Q100 85 85 95 Z" fill="#ffffff"/>
                        </g>
                        
                        <!-- Formulaire d'inscription -->
                        <g class="form-icon">
                            <rect x="60" y="120" width="80" height="60" rx="8" fill="none" stroke="#C69C72" stroke-width="2"/>
                            <line x1="70" y1="135" x2="130" y2="135" stroke="#ffffff" stroke-width="2"/>
                            <line x1="70" y1="145" x2="120" y2="145" stroke="#ffffff" stroke-width="2"/>
                            <line x1="70" y1="155" x2="125" y2="155" stroke="#ffffff" stroke-width="2"/>
                            <line x1="70" y1="165" x2="110" y2="165" stroke="#ffffff" stroke-width="2"/>
                        </g>
                        
                        <!-- Icônes de validation -->
                        <g class="check-icon">
                            <circle cx="150" cy="50" r="15" fill="#C69C72" opacity="0.8"/>
                            <path d="M145 50 L148 53 L155 46" stroke="#ffffff" stroke-width="2" fill="none"/>
                        </g>
                        
                        <g class="check-icon">
                            <circle cx="50" cy="50" r="12" fill="#ffffff" opacity="0.6"/>
                            <path d="M46 50 L48 52 L54 46" stroke="#C69C72" stroke-width="2" fill="none"/>
                        </g>
                        
                        <!-- Engrenages -->
                        <g class="gear">
                            <circle cx="170" cy="120" r="10" fill="none" stroke="#ffffff" stroke-width="2"/>
                            <circle cx="170" cy="120" r="6" fill="#ffffff" opacity="0.3"/>
                        </g>
                        
                        <g class="gear">
                            <circle cx="30" cy="120" r="8" fill="none" stroke="#C69C72" stroke-width="2"/>
                            <circle cx="30" cy="120" r="5" fill="#C69C72" opacity="0.3"/>
                        </g>
                        
                        <!-- Éléments décoratifs animés -->
                        <circle cx="40" cy="160" r="3" fill="#C69C72" opacity="0.6">
                            <animate attributeName="r" values="3;5;3" dur="2s" repeatCount="indefinite"/>
                        </circle>
                        
                        <circle cx="160" cy="160" r="4" fill="#ffffff" opacity="0.6">
                            <animate attributeName="r" values="4;6;4" dur="3s" repeatCount="indefinite"/>
                        </circle>
                        
                        <!-- Lignes de connexion animées -->
                        <line x1="75" y1="95" x2="100" y2="120" stroke="#C69C72" stroke-width="2" opacity="0.4">
                            <animate attributeName="opacity" values="0.4;0.8;0.4" dur="2s" repeatCount="indefinite"/>
                        </line>
                        
                        <line x1="125" y1="95" x2="100" y2="120" stroke="#ffffff" stroke-width="2" opacity="0.4">
                            <animate attributeName="opacity" values="0.4;0.8;0.4" dur="2s" repeatCount="indefinite" begin="1s"/>
                        </line>
                        
                        <!-- Étoiles scintillantes -->
                        <g opacity="0.7">
                            <path d="M180 30 L182 35 L187 35 L183 38 L185 43 L180 40 L175 43 L177 38 L173 35 L178 35 Z" fill="#ffffff">
                                <animate attributeName="opacity" values="0.3;1;0.3" dur="1.5s" repeatCount="indefinite"/>
                            </path>
                        </g>
                        
                        <g opacity="0.5">
                            <path d="M20 40 L21 43 L24 43 L22 45 L23 48 L20 46 L17 48 L18 45 L16 43 L19 43 Z" fill="#C69C72">
                                <animate attributeName="opacity" values="0.2;0.8;0.2" dur="2s" repeatCount="indefinite" begin="0.5s"/>
                            </path>
                        </g>
                    </svg>
                </div>
            </div>
            
            <div class="left-content">
                <h1>Rejoignez notre communauté</h1>
                <p>Créez votre compte et commencez à gérer vos horaires efficacement.</p>
            </div>
        </div>

        <!-- Section droite avec formulaire Laravel -->
        <div class="right-section">
            <div class="register-header">
                <div class="register-icon">👤</div>
                <h1 class="register-title">{{ __('Inscription') }}</h1>
                <p class="register-subtitle">Créez votre compte Horaire</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Nom complet') }}</label>
                    <input 
                        id="name" 
                        class="form-input" 
                        type="text" 
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                        placeholder="ex: Jean Dupont"
                    >
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Adresse email') }}</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="username"
                        placeholder="ex: jean.dupont@email.com"
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                    <input 
                        id="password" 
                        class="form-input" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="Minimum 8 caractères"
                    >
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
                    <input 
                        id="password_confirmation" 
                        class="form-input" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Répétez votre mot de passe"
                    >
                </div>

                <button type="submit" class="register-button">
                    {{ __('Créer mon compte') }}
                </button>

                <div class="form-footer">
                    <a class="login-link" href="{{ route('login') }}">
                        {{ __('Déjà un compte ? Se connecter') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Animation des particules dynamiques
        function createFloatingParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'absolute';
            particle.style.width = Math.random() * 6 + 4 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = 'rgba(255, 255, 255, 0.3)';
            particle.style.borderRadius = '50%';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = '100%';
            particle.style.pointerEvents = 'none';
            particle.style.animation = `floatUp ${Math.random() * 3 + 4}s linear forwards`;
            
            document.querySelector('.background-animation').appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 7000);
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            // Focus automatique sur le champ nom
            document.getElementById('name').focus();
            
            // Créer des particules flottantes périodiquement
            setInterval(createFloatingParticle, 2000);
            
            // Animation d'entrée pour les éléments
            setTimeout(() => {
                document.querySelector('.register-container').style.transform = 'scale(1)';
            }, 100);
        });

        // Effet de parallaxe sur le mouvement de la souris
        document.addEventListener('mousemove', (e) => {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            const particles = document.querySelectorAll('.particle');
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                const x = mouseX * speed;
                const y = mouseY * speed;
                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });

        // Animation des inputs au focus
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Validation en temps réel du mot de passe
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        function validatePasswordMatch() {
            if (confirmPasswordInput.value && passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }

        passwordInput.addEventListener('input', validatePasswordMatch);
        confirmPasswordInput.addEventListener('input', validatePasswordMatch);
    </script>
</body>
</html>
