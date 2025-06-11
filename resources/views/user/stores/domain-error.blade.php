<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domaine non configuré</title>
    <style>
        :root {
            --primary: #0A2E2E;
            --secondary: #2A6363;
            --accent: #8E6E53;
            --light: #C69C72;
            --text-dark: #000000;
            --text-light: #FFFFFF;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }
        
        .container {
            max-width: 600px;
            background-color: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .logo {
            margin-bottom: 2rem;
        }
        
        h1 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        
        p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .domain {
            font-weight: bold;
            color: var(--accent);
            padding: 0.5rem 1rem;
            background-color: rgba(142, 110, 83, 0.1);
            border-radius: 4px;
            display: inline-block;
            margin: 0.5rem 0;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .footer {
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <svg width="120" height="40" viewBox="0 0 120 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="8" fill="#0A2E2E"/>
                <path d="M8 20H32" stroke="white" stroke-width="4" stroke-linecap="round"/>
                <path d="M20 8L20 32" stroke="white" stroke-width="4" stroke-linecap="round"/>
                <path d="M50 12H54.5L60 28H64L69.5 12H74L66.5 32H61.5L54 12Z" fill="#0A2E2E"/>
                <path d="M76 12H80.5V32H76V12Z" fill="#0A2E2E"/>
                <path d="M84 12H88.5V28H98V32H84V12Z" fill="#0A2E2E"/>
                <path d="M100 12H104.5V32H100V12Z" fill="#0A2E2E"/>
                <path d="M108 12H120V16H112.5V20H119V24H112.5V28H120V32H108V12Z" fill="#0A2E2E"/>
            </svg>
        </div>
        
        <h1>Domaine non configuré</h1>
        
        <p>Le domaine <span class="domain">{{ $domain }}</span> n'est pas configuré dans notre système.</p>
        
        <p>{{ $message ?? 'Si vous êtes le propriétaire de ce domaine, veuillez vous connecter à votre compte administrateur et configurer ce domaine dans les paramètres.' }}</p>
        
        <a href="https://horaires.app/login" class="btn">Se connecter</a>
        
        <div class="footer">
            &copy; {{ date('Y') }} Horaires App - Tous droits réservés
        </div>
    </div>
</body>
</html>
