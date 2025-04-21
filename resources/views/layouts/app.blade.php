<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: Georgia, 'Times New Roman', Times, serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #8d8575;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .nav-logo, .nav-link, .user-button, .auth-link, .header-content {
            font-family: Georgia, 'Times New Roman', Times, serif;
        }

        .main-container {
            min-height: 100vh;
            background-color: #fffbeb;
            display: flex;
            flex-direction: column;
        }

        .main-container.dark {
            background-color: #887f79;
        }

        .content-wrapper {
            flex: 1;
            margin-top: 70px;
            padding: 20px;
        }

        .header {
            background-color: #fef3c7;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            margin-top: 70px;
        }

        .header.dark {
            background-color: #f5ebe6;
        }

        .header-content {
            max-width: 80rem;
            margin: 0 auto;
            padding: 2.5rem 2rem;
            font-size: 1.1em;
        }

        @media (min-width: 640px) {
            .header-content {
                padding: 2.5rem 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .header-content {
                padding: 1.5rem 2rem;
            }
        }

        @media (max-width: 768px) {
            .content-wrapper, .header {
                margin-top: 120px;
            }
        }
        
        /* Styles de navigation */
        .main-nav {
            background: linear-gradient(135deg, #cecece 0%, #ebebeb 100%);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(70px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 0px 10px;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .nav-logo .logo-link {
            text-decoration: none;
            color: #331c0c;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .nav-logo .logo-link:hover {
            color: #db8d34;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-link {
            text-decoration: none;
            color: #50371f;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(128, 72, 21, 0.1);
            color: #a1804f;
            transform: translateY(-2px);
        }

        .nav-link i {
            font-size: 1.1rem;
        }

        .user-dropdown {
            position: relative;
        }

        .user-button {
            background: none;
            border: none;
            color: #bd844f;
            font-weight: 500;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .user-button:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: #ce9f5a;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 0.5rem;
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .user-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: #946919;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: #796134;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        .logout-button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
        }

        .auth-links {
            display: flex;
            gap: 1rem;
        }

        .auth-link {
            text-decoration: none;
            color: #6d4f18;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .auth-link:hover {
            background-color: rgba(52, 152, 219, 0.1);
            color: #be8b1d;
        }

        .auth-link.register {
            background-color: #7c620e;
            color: white;
        }

        .auth-link.register:hover {
            background-color: #b97629;
        }

        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                height: auto;
                padding: 1rem 0;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
                margin: 1rem 0;
            }

            .nav-link {
                justify-content: center;
            }

            .user-dropdown {
                width: 100%;
            }

            .user-button {
                width: 100%;
                justify-content: center;
            }

            .dropdown-menu {
                width: 100%;
            }

            .auth-links {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="main-container">
        @include('layouts.navigation')

        <div class="content-wrapper">
            @if(View::hasSection('header'))
                @yield('header')
            @endif

            <main>
                @yield('content')
            </main>
        </div>

        @stack('scripts')
    </div>

    <script>
        // Gestion du mode sombre
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        const container = document.querySelector('.main-container');
        const header = document.querySelector('.header');

        function updateDarkMode(e) {
            if (e.matches) {
                container.classList.add('dark');
                header?.classList.add('dark');
            } else {
                container.classList.remove('dark');
                header?.classList.remove('dark');
            }
        }

        updateDarkMode(prefersDark);
        prefersDark.addEventListener('change', updateDarkMode);
    </script>
</body>
</html>