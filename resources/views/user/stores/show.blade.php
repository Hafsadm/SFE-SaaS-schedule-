

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_xQsTc41ShFh3sMnafHjUEht-8ZrDoM8&callback=initMap" async defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

<style>
    /* Variables de couleur */
    :root {
    --primary: #429182;
    --secondary: #337b8d;
    --light-beige: #F9F5EF;
    --dark-beige: #1b5858;
    --text-dark: #000000;
    --text-light: #FFFFFF;
    --success: #5DBB63;
    --border: #E6D8C3;
    /* Ajoutez ces variables manquantes */
    --light: #f8fafc;
    --dark: #1e293b;
    --white: #ffffff;
    --danger: #ef4444;
    --warning: #f59e0b;
    --light-gray: #e2e8f0;
}

/* Reset et base */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Georgia, 'Times New Roman', Times, serif;
  line-height: 1.6;
  color: var(--dark);
  background-color: var(--light);
  max-width: 1990px;
  margin: 0 auto;
  padding: 0 20px;
  overflow-x: hidden;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.container {
  width: 100%;
  position: relative;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

/* En-tête du magasin */

.store-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  background: linear-gradient(130deg, #1b5858, #639caa);
  color: var(--white);
  padding: 2rem 1rem; /* Modifié pour avoir un padding latéral */
  width: 90%; /* Prend toute la largeur disponible */
  max-width: 1200px; /* Maximum comme le container */
  margin: 1rem auto 2rem auto; /* Centrage horizontal */
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border-radius: 2rem; /* Optionnel pour les coins arrondis */
}



.store-title {
  font-size: 2.5rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  letter-spacing: -0.025em;
}

.store-subtitle {
    align-items: center;
    display: inline-flex;
  font-size: 1.125rem;
  opacity: 0.9;
  margin-bottom: 1.5rem;
}

.store-badges {

  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.store-badge {
  align-items: center;
  display: inline-flex;
  background-color: rgba(155, 219, 228, 0.2);
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  
  backdrop-filter: blur(4px);

}

.store-status {
  display: inline-flex;
  align-items: center;
  gap: 1.5rem;
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-weight: 600;
  background-color: rgba(255, 255, 255, 0.1);
}

.store-status.open {
  background-color: rgba(16, 185, 129, 0.2);
  color: var(--success);
}

.store-status.closed {
  background-color: rgba(239, 68, 68, 0.2);
  color: var(--danger);
}

/* Structure du contenu */
.store-content {
  padding-bottom: 3rem;
}
.store-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  width: 100%;
}

@media (min-width: 1024px) {
  .store-grid {
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
    align-items: start; /* Ajout important */
  }
}

.main-column {
  width: 100%;
  overflow: hidden; /* Empêche les débordements */
}


.sidebar {
    padding: 1.5rem;
    background: linear-gradient(165deg,#639caa, #1b5858 , #639caa);
    border-radius: 1rem;
    border: 1px solid rgba(116, 163, 177, 0.2);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: #1b5858;
    position: sticky;
    top: 20px;
    height: fit-content;
    backdrop-filter: blur(8px);
    z-index: 10;
}

/* .sidebar {
    padding: 1.5rem;
    background: linear-gradient(195deg, var(--primary), #1b5858);
    border-radius: 2.5rem;
  outline: 1px dashed rgb(142, 170, 172);
} */
/* 
.sidebar {
background-color: rgb(29, 105, 131)
  border-radius: 2.5rem;
  padding: 1.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
  border: var(--border) solid;
 outline: 1px dashed rgb(72, 105, 116);
  position: sticky;
  top: 20px; 
  height: fit-content;
    padding: 2rem;
    z-index: 10;
    position: relative;
    display: block !important;
  opacity: 1 !important;
  visibility: visible !important;
} */


/* Assure que le contenu principal ne dépasse pas */
.main-column > .section {
  max-width: 100%;
  overflow: hidden;
}

/* Correction pour les cartes de produits */
.products-grid {
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

/* Garantit que la sidebar reste dans le flux */
.sidebar .section {
  width: 100%;
  box-sizing: border-box;
}


/* Sections */
.section {
  background-color: var(--white);
  border-radius: 0.5rem;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: var(--dark);
}

/* Galerie */
.gallery-container {
  border-radius: 0.5rem;
  overflow: hidden;
}

.swiper {
  width: 100%;
  height: 400px;
  border-radius: 0.5rem;
}

.swiper-slide {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: var(--light-gray);
}

.swiper-slide img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.swiper-pagination-bullet {
  background-color: var(--white);
  opacity: 0.7;
}

.swiper-pagination-bullet-active {
  background-color: var(--primary);
  opacity: 1;
}

.swiper-button-next,
.swiper-button-prev {
  color: var(--white);
  background-color: rgba(0, 0, 0, 0.3);
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  backdrop-filter: blur(4px);
}

.swiper-button-next::after,
.swiper-button-prev::after {
  font-size: 1rem;
}

/* Grille d'informations */
.info-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}

@media (min-width: 640px) {
  .info-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.info-item {
  display: flex;
  flex-direction: column;
}

.info-label {
  font-size: 0.875rem;
  color: var(--secondary);
  margin-bottom: 0.25rem;
}

.info-value {
  font-weight: 500;
}

/* Produits */
.products-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}

@media (min-width: 640px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .products-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.product-card {
  background-color: var(--light);
  border-radius: 0.5rem;
  padding: 1.5rem;
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid var(--light-gray);
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

.product-image {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 64px;
  height: 64px;
  background-color: var(--white);
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  color: var(--primary);
  border: 1px solid var(--light-gray);
}

.product-name {
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.product-price {
  font-weight: 700;
  color: var(--primary);
}

/* Équipe */
.staff-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}

@media (min-width: 640px) {
  .staff-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.staff-card {
  background-color: var(--light);
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid var(--light-gray);
}

.staff-avatar {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 64px;
  height: 64px;
  background-color: var(--white);
  border-radius: 50%;
  margin-bottom: 1rem;
  color: var(--primary);
  border: 1px solid var(--light-gray);
}

.staff-name {
  font-weight: 600;
  margin-bottom: 0.25rem;
}

.staff-role {
  color: var(--primary);
  font-weight: 500;
  margin-bottom: 0.75rem;
  font-size: 0.875rem;
}

.staff-bio {
  color: var(--secondary);
  font-size: 0.875rem;
}

/* FAQ */
.faq-container {
  border-radius: 0.5rem;
  overflow: hidden;
}

.faq-item {
  border-bottom: 1px solid var(--light-gray);
}

.faq-item:last-child {
  border-bottom: none;
}

.faq-question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  cursor: pointer;
  font-weight: 500;
}

.faq-question:hover {
  color: var(--primary);
}

.faq-answer {
  padding-bottom: 1rem;
  color: var(--secondary);
  display: none;
}

.faq-answer.show {
  display: block;
}

/* Horaires */
.opening-hours {
  width: 100%;
}

.opening-day {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid var(--light-gray);
}

.opening-day:last-child {
  border-bottom: none;
}

.day-name {
  font-weight: 500;
}

.day-hours {
  color: var(--secondary);
}

/* Carte */
.map-container {
  height: 360px;
  background-color: var(--light-gray);
  border-radius: 0.5rem;
  margin-bottom: 1rem;
  overflow: hidden;
}

/* Avis */
.reviews-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.review-card {
  background-color: var(--light);
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid var(--light-gray);
}

.review-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
}

.reviewer-info {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.reviewer-avatar {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 40px;
  height: 40px;
  background-color: var(--white);
  border-radius: 50%;
  color: var(--primary);
  border: 1px solid var(--light-gray);
}

.reviewer-name {
  font-weight: 600;
}

.review-date {
  font-size: 0.75rem;
  color: var(--secondary);
}

.review-rating {
  display: flex;
  gap: 0.25rem;
}

.star {
  color: var(--warning);
}

.review-content {
  color: var(--dark);
}

/* Boutons */
.action-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  text-align: center;
  transition: all 0.2s;
  cursor: pointer;
  border: none;
}

.btn-primary {
  background-color: var(--primary);
  color: var(--white);
}

.btn-primary:hover {
  background-color: var(--secondary);
    color: var(--white);

}

.btn-secondary {
  background-color: var(--secondary);
  color: var(--white);
}

.btn-secondary:hover {
  background-color: #475569;
}

.btn-outline {
  background-color: transparent;
  color: var(--primary);
  border: 1px solid var(--primary);
}

.btn-outline:hover {
  background-color: rgba(59, 130, 246, 0.1);
}

.share-buttons {
  display: flex;
  gap: 0.75rem;
  margin-top: 1rem;
}

.share-button {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: var(--light);
  color: var(--dark);
  transition: all 0.2s;
}

.share-button:hover {
  background-color: var(--light-gray);
  transform: translateY(-2px);
}

/* Magasins similaires */
.similar-stores {
  margin-top: 3rem;
}

.similar-stores-title {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: var(--dark);
}

.similar-stores-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
}

@media (min-width: 640px) {
  .similar-stores-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .similar-stores-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

.similar-store-card {
  background-color: var(--white);
  border-radius: 0.5rem;
  padding: 1.5rem;
  transition: transform 0.2s, box-shadow 0.2s;
  border: 1px solid var(--light-gray);
}

.similar-store-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
}

.similar-store-name {
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.similar-store-address {
  color: var(--secondary);
  font-size: 0.875rem;
  margin-bottom: 0.75rem;
}

.similar-store-status {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.875rem;
  margin-bottom: 1rem;
  margin:0   0.5rem ;
}

.similar-store-status.open {
  color: var(--success);
}

.similar-store-status.closed {
  color: var(--danger);
}

.similar-store-link {
  color: var(--primary);
  font-weight: 500;
  font-size: 0.875rem;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  margin: 0.5rem 0 0 0 ;


}

/* .similar-store-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1); /* Ligne de séparation subtile */
} */

.similar-store-link:hover {
  text-decoration: underline;
}

/* Bouton retour en haut */
.back-to-top {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: var(--primary);
  color: var(--white);
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  cursor: pointer;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s;
  z-index: 50;
}

.back-to-top.visible {
  opacity: 1;
  visibility: visible;
}

.back-to-top:hover {
  background-color: var(--primary-hover);
  transform: translateY(-2px);
}

/* Utilitaires */
.mb-4 {
  margin-bottom: 1rem;
}

.text-center {
  text-align: center;
}

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.section {
  animation: fadeIn 0.3s ease-out forwards;
}

/* Responsive */
@media (max-width: 768px) {
  .store-title {
    font-size: 2rem;
  }
  
  .store-subtitle {
    font-size: 1rem;
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
                {{-- <div class="section">
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
                </div> --}}

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


                  <!-- Magasins similaires -->
        @if(count($similarStores) > 0)
        <div class="similar-stores">
            <h2 class="similar-stores-title">Autres points de vente à {{ $store->ville }}</h2>
            <div class="similar-stores-grid">
                @foreach($similarStores as $similarStore)
                <div class="similar-store-card">
                    <h3 class="similar-store-name">{{ $similarStore->nom }}</h3>
                    <p class="similar-store-address">{{ $similarStore->adresse }}</p>
                    <div class ="similar-store-footer" >
                        <p class="similar-store-status {{ $similarStore->is_open ? 'open' : 'closed' }}">
                            <i data-lucide="{{ $similarStore->is_open ? 'check-circle' : 'x-circle' }}" class="w-4 h-4"></i>
                            {{ $similarStore->today_status }}
                        </p>
                        <a href="{{ route('stores.show', $similarStore->id) }}" class="similar-store-link">
                            Voir les détails
                        </a>
                    </div>

                </div>
                @endforeach
            </div>
        </div>
        @endif
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
             </div>
                <!-- Carte -->
                {{-- <div class="section">
                    <h2 class="section-title">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                        Localisation
                    </h2>
                    <div class="map-container" id="map"></div>
                    <div class="info-item">
                        <span class="info-label">Adresse</span>
                        <span class="info-value">{{ $store->adresse }}, {{ $store->ville }}</span>
                    </div>
                </div> --}}

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
                            @if(isset($store->exterior_image) && !empty($store->exterior_image))
                                <div class="swiper-slide">
                                    <img src="{{ asset('Pic Stores/' . $store->exterior_image) }}" alt="Extérieur de {{ $store->nom }}" onerror="this.src='https://via.placeholder.com/800x400?text=Extérieur+{{ urlencode($store->nom) }}'">
                                </div>
                            @else
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Extérieur+{{ urlencode($store->nom) }}" alt="Extérieur de la boutique">
                                </div>
                            @endif
                            
                            @if(isset($store->interior_image) && !empty($store->interior_image))
                                <div class="swiper-slide">
                                    <img src="{{ asset('Pic Stores/' . $store->interior_image) }}" alt="Intérieur de {{ $store->nom }}" onerror="this.src='https://via.placeholder.com/800x400?text=Intérieur+{{ urlencode($store->nom) }}'">
                                </div>
                            @else
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Intérieur+{{ urlencode($store->nom) }}" alt="Intérieur de la boutique">
                                </div>
                            @endif
                            
                            @if(isset($store->equipment_image) && !empty($store->equipment_image))
                                <div class="swiper-slide">
                                    <img src="{{ asset('Pic Stores/' . $store->equipment_image) }}" alt="Équipement de {{ $store->nom }}" onerror="this.src='https://via.placeholder.com/800x400?text=Équipement+{{ urlencode($store->nom) }}'">
                                </div>
                            @else
                                <div class="swiper-slide">
                                    <img src="https://via.placeholder.com/800x400?text=Équipement+{{ urlencode($store->nom) }}" alt="Équipement de la boutique">
                                </div>
                            @endif
                        </div>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
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

    let currentImageIndex = 0;
    const galleryImages = [
        @if(isset($store->exterior_image) && !empty($store->exterior_image))
            {
                src: "{{ asset('Pic Stores/' . $store->exterior_image) }}",
                caption: "Extérieur de {{ $store->nom }}"
            },
        @endif
        @if(isset($store->interior_image) && !empty($store->interior_image))
            {
                src: "{{ asset('Pic Stores/' . $store->interior_image) }}",
                caption: "Intérieur de {{ $store->nom }}"
            },
        @endif
        @if(isset($store->equipment_image) && !empty($store->equipment_image))
            {
                src: "{{ asset('Pic Stores/' . $store->equipment_image) }}",
                caption: "Équipement de {{ $store->nom }}"
            },
        @endif
    ];
    
    // Fonction pour ouvrir la galerie modale
    function openGalleryModal(imageSrc, caption) {
        const modal = document.getElementById('galleryModal');
        const modalImg = document.getElementById('galleryModalImage');
        const modalCaption = document.getElementById('galleryModalCaption');
        
        modal.style.display = 'flex';
        modalImg.src = imageSrc;
        modalCaption.innerHTML = caption;
        
        // Trouver l'index de l'image actuelle
        currentImageIndex = galleryImages.findIndex(img => img.src === imageSrc);
    }
    
    // Fonction pour fermer la galerie modale
    function closeGalleryModal() {
        document.getElementById('galleryModal').style.display = 'none';
    }
    
    // Fonction pour changer d'image dans la galerie modale
    function changeGalleryImage(direction) {
        currentImageIndex += direction;
        
        // Boucler si nécessaire
        if (currentImageIndex >= galleryImages.length) {
            currentImageIndex = 0;
        } else if (currentImageIndex < 0) {
            currentImageIndex = galleryImages.length - 1;
        }
        
        const modalImg = document.getElementById('galleryModalImage');
        const modalCaption = document.getElementById('galleryModalCaption');
        
        modalImg.src = galleryImages[currentImageIndex].src;
        modalCaption.innerHTML = galleryImages[currentImageIndex].caption;
    }
    
    // Fermer la modale si on clique en dehors de l'image
    window.onclick = function(event) {
        const modal = document.getElementById('galleryModal');
        if (event.target === modal) {
            closeGalleryModal();
        }
    }
</script>


</body>
</html>