

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
    /* Variables de couleur */
    :root {
        --primary: #A67C52; /* Marron doré */
        --primary-light: #C8AD7F;
        --primary-dark: #8A6642;
        --secondary: #D2B48C; /* Beige doré */
        --light-beige: #F5F5DC;
        --dark-beige: #E0C9B4;
        --text-dark: #333333;
        --text-light: #F8F4E6;
        --success: #82B183; /* Vert doux */
        --error: #C17C74; /* Rouge doux */
        --border: #E0C9B4;
        --card-shadow: 0 4px 12px rgba(92, 64, 51, 0.1);
        --transition: all 0.3s ease;
    }

    /* Reset et base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Georgia', sans-serif;
        color: var(--text-dark);
        background-color: #fff;
        line-height: 1.6;
        background-attachment: fixed; 
        position: relative;
        
    }

    /* En-tête */
    .store-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        padding: 3rem 0;
        color: var(--text-light);
        position: relative;
        overflow: hidden;
    }

    .store-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
    }

    .store-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .store-subtitle {
        font-size: 1.2rem;
        font-weight: 400;
        opacity: 0.9;
    }

    .store-badges {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .store-badge {
        background-color: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .store-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 1.5rem;
    }

    .store-status.open {
        background-color: rgba(130, 177, 131, 0.2);
        color: var(--success);
    }

    .store-status.closed {
        background-color: rgba(193, 124, 116, 0.2);
        color: var(--error);
    }

    /* Contenu principal */
    .store-content {
        padding: 3rem 0;
    }

    .store-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Sections */
    .section {
        background-color: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--primary);
    }

    /* Galerie d'images */
    .gallery-container {
        position: relative;
        margin-bottom: 2rem;
    }

    .swiper {
        width: 100%;
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
    }

    .swiper-slide {
        text-align: center;
        background: #f8f8f8;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .swiper-pagination-bullet-active {
        background-color: var(--primary);
    }

    .swiper-button-next,
    .swiper-button-prev {
        color: var(--primary);
    }

    /* Informations */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.7;
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
    }

    /* Accessibilité */
    .accessibility-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .accessibility-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background-color: var(--light-beige);
        border-radius: 8px;
    }

    .accessibility-item i {
        color: var(--primary);
    }

    /* Produits */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1.5rem;
    }

    .product-card {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        transition: var(--transition);
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: white;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image i {
        color: var(--primary);
        font-size: 2rem;
    }

    .product-name {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .product-price {
        color: var(--primary);
        font-weight: 500;
    }

    /* Personnel */
    .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .staff-card {
        background-color: var(--light-beige);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: var(--transition);
    }

    .staff-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .staff-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: white;
        margin: 0 auto 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .staff-avatar i {
        color: var(--primary);
        font-size: 2rem;
    }

    .staff-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .staff-role {
        color: var(--primary);
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .staff-bio {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.8;
    }

    /* Horaires */
    .weekly-hours {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .day-schedule {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border);
    }

    .day-schedule:last-child {
        border-bottom: none;
    }

    .day-schedule.today {
        background-color: var(--light-beige);
        margin: 0 -1.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
    }

    .day-name {
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .today-badge {
        background-color: var(--primary);
        color: white;
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
    }

    .time-slot {
        font-weight: 500;
    }

    .closed-text {
        color: var(--error);
        font-weight: 500;
    }

    .no-hours {
        color: var(--text-dark);
        opacity: 0.6;
        font-style: italic;
    }

    /* Carte */
    .map-container {
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    /* Avis */
    .reviews-container {
        margin-bottom: 1.5rem;
    }

    .review-card {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
    }

    .review-card:last-child {
        border-bottom: none;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--light-beige);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reviewer-avatar i {
        color: var(--primary);
    }

    .reviewer-name {
        font-weight: 600;
    }

    .review-date {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.7;
    }

    .review-rating {
        display: flex;
        gap: 0.25rem;
    }

    .star {
        color: #FFD700;
    }

    .review-content {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Actions */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
    }

    .btn-secondary {
        background-color: white;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-secondary:hover {
        background-color: var(--light-beige);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover {
        background-color: var(--light-beige);
    }

    /* Partage */
    .share-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .share-button {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--light-beige);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        transition: var(--transition);
    }

    .share-button:hover {
        background-color: var(--primary);
        color: white;
    }

    /* FAQ */
    .faq-item {
        border-bottom: 1px solid var(--border);
        padding: 1rem 0;
    }

    .faq-item:last-child {
        border-bottom: none;
    }

    .faq-question {
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-answer {
        padding-top: 1rem;
        display: none;
        font-size: 0.95rem;
        color: var(--text-dark);
        opacity: 0.8;
    }

    .faq-answer.active {
        display: block;
    }

    /* Magasins similaires */
    .similar-stores {
        margin-top: 3rem;
    }

    .similar-stores-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--primary);
    }

    .similar-stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .similar-store-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        transition: var(--transition);
    }

    .similar-store-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .similar-store-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .similar-store-address {
        font-size: 0.9rem;
        color: var(--text-dark);
        opacity: 0.8;
        margin-bottom: 1rem;
    }

    .similar-store-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .similar-store-status.open {
        color: var(--success);
    }

    .similar-store-status.closed {
        color: var(--error);
    }

    .similar-store-link {
        display: inline-block;
        color: var(--primary);
        font-weight: 500;
        text-decoration: none;
        transition: var(--transition);
    }

    .similar-store-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Retour en haut */
    .back-to-top {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: var(--transition);
        opacity: 0;
        visibility: hidden;
        z-index: 100;
    }

    .back-to-top.visible {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        background-color: var(--primary-dark);
        transform: translateY(-5px);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .store-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .store-title {
            font-size: 2rem;
        }
        
        .info-grid, .products-grid, .staff-grid, .similar-stores-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }

    @media (max-width: 576px) {
        .container {
            padding: 0 1rem;
        }
        
        .store-badges {
            flex-wrap: wrap;
        }
    }
</style>
</head>

<body>
<!-- En-tête du magasin -->
<div class="store-header">
    <div class="container">
        <h1 class="store-title">{{ strtoupper($store->nom) }}</h1>
        <p class="store-subtitle">{{ $store->adresse }}, {{ $store->ville }}</p>
        
        <div class="store-badges">
            @foreach($store->services as $service)
                <span class="store-badge">{{ $service }}</span>
            @endforeach
        </div>
        
        <div class="store-status {{ $store->is_open ? 'open' : 'closed' }}">
            <i data-lucide="{{ $store->is_open ? 'check-circle' : 'x-circle' }}" class="w-5 h-5"></i>
            {{ $store->today_status }}
            @if($store->is_open && $store->ouvert_jusqua)
                (jusqu'à {{ \Carbon\Carbon::parse($store->ouvert_jusqua)->format('H:i') }})
            @endif
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="store-content">
    <div class="container">
        <div class="store-grid">
            <!-- Colonne principale -->
            <div class="main-column">
                <!-- Galerie d'images -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="image" class="w-5 h-5"></i>
                        Galerie
                    </h2>
                    <div class="gallery-container">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <!-- Images de la boutique -->
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Extérieur+{{ urlencode($store->nom) }}" alt="Extérieur de la boutique">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Intérieur+{{ urlencode($store->nom) }}" alt="Intérieur de la boutique">
                                </div>
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Équipement+{{ urlencode($store->nom) }}" alt="Équipement de la boutique">
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations générales -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="info" class="w-5 h-5"></i>
                        Informations
                    </h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value">{{ $store->phone ?? 'Non renseigné' }}</span>
                        </div>
                       <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $store->email ?? 'contact@' . strtolower(str_replace(' ', '', $store->nom)) . '.com' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Site web</span>
                            <span class="info-value">{{ $store->website ?? 'www.' . strtolower(str_replace(' ', '', $store->nom)) . '.com' }}</span>
                        </div> 
                        <div class="info-item">
                            <span class="info-label">Année d'ouverture</span>
                            <span class="info-value">{{ $store->annee_ouverture ?? '2020' }}</span>
                        </div>
                    </div> 
                </div>
                
                {{-- <!-- Accessibilité -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="accessibility" class="w-5 h-5"></i>
                        Accessibilité
                    </h2>
                    <div class="accessibility-list">
                        <div class="accessibility-item">
                            <i data-lucide="wheelchair" class="w-5 h-5"></i>
                            <span>Accès handicapé</span>
                        </div>
                        <div class="accessibility-item">
                            <i data-lucide="parking" class="w-5 h-5"></i>
                            <span>Parking à proximité</span>
                        </div>
                        <div class="accessibility-item">
                            <i data-lucide="bus" class="w-5 h-5"></i>
                            <span>Transport en commun</span>
                        </div>
                        <div class="accessibility-item">
                            <i data-lucide="baby" class="w-5 h-5"></i>
                            <span>Espace enfants</span>
                        </div>
                    </div>
                </div>
                 --}}
                <!-- Produits disponibles -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        Produits disponibles
                    </h2>
                    <div class="products-grid">
                        @if(isset($store->products) && count($store->products) > 0)
                            @foreach($store->products as $product)
                                <div class="product-card">
                                    <div class="product-image">
                                        <i data-lucide="package" class="w-8 h-8"></i>
                                    </div>
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <p class="product-price">{{ $product->price }} €</p>
                                </div>
                            @endforeach
                        @else
                            <!-- Produits par défaut basés sur les services -->
                            @foreach($store->services as $index => $service)
                                @if($service == 'Dentiste')
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="smile" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Consultation dentaire</h3>
                                        <p class="product-price">À partir de 25 €</p>
                                    </div>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="sparkles" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Blanchiment</h3>
                                        <p class="product-price">À partir de 150 €</p>
                                    </div>
                                @elseif($service == 'Opticien')
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="glasses" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Montures</h3>
                                        <p class="product-price">À partir de 59 €</p>
                                    </div>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="eye" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Verres progressifs</h3>
                                        <p class="product-price">À partir de 129 €</p>
                                    </div>
                                @elseif($service == 'Audition')
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="ear" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Appareils auditifs</h3>
                                        <p class="product-price">À partir de 750 €</p>
                                    </div>
                                    <div class="product-card">
                                        <div class="product-image">
                                            <i data-lucide="battery-charging" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="product-name">Accessoires</h3>
                                        <p class="product-price">À partir de 15 €</p>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <!-- Personnel -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        Notre équipe
                    </h2>
                    <div class="staff-grid">
                        @if(isset($store->staff) && count($store->staff) > 0)
                            @foreach($store->staff as $member)
                                <div class="staff-card">
                                    <div class="staff-avatar">
                                        <i data-lucide="user" class="w-8 h-8"></i>
                                    </div>
                                    <h3 class="staff-name">{{ $member->name }}</h3>
                                    <p class="staff-role">{{ $member->role }}</p>
                                    <p class="staff-bio">{{ $member->bio }}</p>
                                </div>
                            @endforeach
                        @else
                            <!-- Personnel par défaut basé sur les services -->
                            @foreach($store->services as $index => $service)
                                @if($service == 'Dentiste')
                                    <div class="staff-card">
                                        <div class="staff-avatar">
                                            <i data-lucide="user" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="staff-name">Dr. Martin</h3>
                                        <p class="staff-role">Dentiste</p>
                                        <p class="staff-bio">Plus de 15 ans d'expérience en dentisterie générale et esthétique.</p>
                                    </div>
                                @elseif($service == 'Opticien')
                                    <div class="staff-card">
                                        <div class="staff-avatar">
                                            <i data-lucide="user" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="staff-name">Sophie Dubois</h3>
                                        <p class="staff-role">Opticienne</p>
                                        <p class="staff-bio">Spécialiste en adaptation de lentilles et conseils personnalisés.</p>
                                    </div>
                                @elseif($service == 'Audition')
                                    <div class="staff-card">
                                        <div class="staff-avatar">
                                            <i data-lucide="user" class="w-8 h-8"></i>
                                        </div>
                                        <h3 class="staff-name">Pierre Laurent</h3>
                                        <p class="staff-role">Audioprothésiste</p>
                                        <p class="staff-bio">Expert en solutions auditives innovantes et personnalisées.</p>
                                    </div>
                                @endif
                            @endforeach
                            <div class="staff-card">
                                <div class="staff-avatar">
                                    <i data-lucide="user" class="w-8 h-8"></i>
                                </div>
                                <h3 class="staff-name">Marie Dupont</h3>
                                <p class="staff-role">Responsable boutique</p>
                                <p class="staff-bio">À votre service pour vous accueillir et vous orienter vers nos spécialistes.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- FAQ -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="help-circle" class="w-5 h-5"></i>
                        Questions fréquentes
                    </h2>
                    <div class="faq-container">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span>Comment prendre rendez-vous ?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                            <div class="faq-answer">
                                Vous pouvez prendre rendez-vous en ligne via notre site web, par téléphone au {{ $store->phone ?? 'numéro affiché ci-dessus' }}, ou directement en boutique.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span>Acceptez-vous les mutuelles ?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                            <div class="faq-answer">
                                Oui, nous acceptons la plupart des mutuelles et proposons le tiers payant. N'hésitez pas à nous contacter pour vérifier si votre mutuelle est partenaire.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span>Proposez-vous des facilités de paiement ?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                            <div class="faq-answer">
                                Oui, nous proposons des solutions de paiement en plusieurs fois sans frais. Renseignez-vous auprès de notre équipe pour plus d'informations.
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq(this)">
                                <span>Faut-il prendre rendez-vous pour essayer des montures ?</span>
                                <i data-lucide="chevron-down" class="w-5 h-5"></i>
                            </div>
                            <div class="faq-answer">
                                Non, vous pouvez venir essayer des montures sans rendez-vous pendant nos heures d'ouverture. Notre équipe sera ravie de vous conseiller.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Colonne latérale -->
            <div class="sidebar">
                <!-- Horaires -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                        Horaires d'ouverture
                    </h2>
                    {!! $store->formatted_weekly_hours !!}
                </div>
                
                <!-- Carte -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Localisation
                    </h2>
                    <div class="map-container" id="map"></div>
                    <div class="info-item">
                        <span class="info-label">Adresse</span>
                        <span class="info-value">{{ $store->adresse }}, {{ $store->ville }}</span>
                    </div>
                </div>
                
                <!-- Avis -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="star" class="w-5 h-5"></i>
                        Avis clients
                    </h2>
                    <div class="reviews-container">
                        <!-- Avis par défaut -->
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="reviewer-name">Jean Dupont</div>
                                        <div class="review-date">Il y a 2 semaines</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                </div>
                            </div>
                            <div class="review-content">
                                Excellent service, personnel très professionnel et à l'écoute. Je recommande vivement !
                            </div>
                        </div>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">
                                        <i data-lucide="user" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <div class="reviewer-name">Marie Martin</div>
                                        <div class="review-date">Il y a 1 mois</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star" class="w-4 h-4 star"></i>
                                    <i data-lucide="star-off" class="w-4 h-4 star"></i>
                                </div>
                            </div>
                            <div class="review-content">
                                Très satisfaite de ma visite. Les conseils étaient pertinents et les prix raisonnables.
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                        Actions
                    </h2>
                    <div class="action-buttons">
                        @if($store->lien_rdv)
                            <a href="{{ $store->lien_rdv }}" target="_blank" class="btn btn-primary">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                                Prendre rendez-vous
                            </a>
                        @else
                            <a href="tel:{{ $store->phone ?? '' }}" class="btn btn-primary">
                                <i data-lucide="phone" class="w-5 h-5"></i>
                                Appeler
                            </a>
                        @endif
                        
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($store->adresse . ', ' . $store->ville) }}" target="_blank" class="btn btn-secondary">
                            <i data-lucide="navigation" class="w-5 h-5"></i>
                            Itinéraire
                        </a>
                        
                        <button class="btn btn-outline" onclick="shareStore()">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                            Partager
                        </button>
                    </div>
                    
                    <div class="share-buttons" id="shareButtons" style="display: none;">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="share-button">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode('Découvrez ' . $store->nom . ' à ' . $store->ville) }}" target="_blank" class="share-button">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode('Découvrez ' . $store->nom . ' à ' . $store->ville . ': ' . url()->current()) }}" target="_blank" class="share-button">
                            <i data-lucide="message-circle" class="w-5 h-5"></i>
                        </a>
                        <a href="mailto:?subject={{ urlencode('Découvrez ' . $store->nom . ' à ' . $store->ville) }}&body={{ urlencode('Voici un point de vente qui pourrait t\'intéresser: ' . url()->current()) }}" class="share-button">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Magasins similaires -->
        @if(count($similarStores) > 0)
        <div class="similar-stores">
            <h2 class="similar-stores-title">Autres points de vente à {{ $store->ville }}</h2>
            <div class="similar-stores-grid">
                @foreach($similarStores as $similarStore)
                <div class="similar-store-card">
                    <h3 class="similar-store-name">{{ $similarStore->nom }}</h3>
                    <p class="similar-store-address">{{ $similarStore->adresse }}</p>
                    <p class="similar-store-status {{ $similarStore->is_open ? 'open' : 'closed' }}">
                        <i data-lucide="{{ $similarStore->is_open ? 'check-circle' : 'x-circle' }}" class="w-4 h-4"></i>
                        {{ $similarStore->today_status }}
                    </p>
                    <a href="{{ route('admin.stores.show', $similarStore->id) }}" class="similar-store-link">
                        Voir les détails
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Bouton retour en haut -->
<button id="backToTop" class="back-to-top">
    <i data-lucide="chevron-up" class="w-6 h-6"></i>
</button>



<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap" async defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les icônes Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        
        // Initialiser le slider
        const swiper = new Swiper('.swiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
            },
        });
        
        // Bouton retour en haut
        const backToTopButton = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });
        
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    
    // Initialiser la carte Google Maps
    function initMap() {
        const storeLocation = {
            lat: {{ $store->latitude ?? 48.8566 }},
            lng: {{ $store->longitude ?? 2.3522 }}
        };
        
        const map = new google.maps.Map(document.getElementById('map'), {
            center: storeLocation,
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });
        
        const marker = new google.maps.Marker({
            position: storeLocation,
            map: map,
            title: '{{ $store->nom }}',
            animation: google.maps.Animation.DROP
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 10px; max-width: 200px;">
                    <h3 style="margin-bottom: 5px; color: #A67C52; font-weight: 600;">{{ $store->nom }}</h3>
                    <p style="margin-bottom: 10px; color: #333; font-size: 14px;">{{ $store->adresse }}</p>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($store->adresse . ', ' . $store->ville) }}" target="_blank" style="color: #A67C52; text-decoration: underline; font-size: 14px;">
                        Ouvrir dans Google Maps
                    </a>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        // Ouvrir l'infoWindow par défaut
        infoWindow.open(map, marker);
    }
    
    // Fonction pour afficher/masquer les réponses FAQ
    function toggleFaq(element) {
        const answer = element.nextElementSibling;
        const icon = element.querySelector('i');
        
        if (answer.style.display === 'block') {
            answer.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        } else {
            answer.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        }
    }
    
    // Fonction pour afficher/masquer les boutons de partage
    function shareStore() {
        const shareButtons = document.getElementById('shareButtons');
        shareButtons.style.display = shareButtons.style.display === 'none' ? 'flex' : 'none';
    }
</script>

    
</body>
</html>