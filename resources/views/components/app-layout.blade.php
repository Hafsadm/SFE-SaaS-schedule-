<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        /* Variables de couleur */
        :root {
            --color-primary: #8B5A2B;
            --color-primary-light: #A67C52;
            --color-secondary: #D2B48C;
            --color-background: #F5F5DC;
            --color-card: #FFFFFF;
            --color-text: #3E2723;
            --color-text-light: #5D4037;
            --color-border: #D7CCC8;
            --color-danger: #C17C74;
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            :root {
                --color-primary: #D2B48C;
                --color-primary-light: #E0C9B4;
                --color-secondary: #4E342E;
                --color-background: #1E1E1E;
                --color-card: #2D2424;
                --color-text: #EFEBE9;
                --color-text-light: #D7CCC8;
                --color-border: #5D4037;
            }
        }

        /* Reset et base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* Layout principal */
        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* En-tête */
        .page-header {
            background-color: var(--color-card);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Contenu principal */
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Cartes */
        .card {
            background-color: var(--color-card);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--color-border);
        }

        /* Navigation */
        .navigation {
            background-color: var(--color-card);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header, .main-content {
                padding: 1rem;
            }
            
            .card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .card {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Navigation -->
        <nav class="navigation">
            @include('layouts.navigation')
        </nav>

        <!-- En-tête de page -->
        @if (isset($header))
            <header class="page-header">
                <div class="header-content">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Contenu principal -->
        <main class="main-content">
            {{ $slot }}
        </main>
    </div>
</body>
</html>