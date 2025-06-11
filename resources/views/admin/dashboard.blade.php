@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Variables de couleur - Nouvelle palette */
    :root {
       --primary: {{ $themeColors['primary_color'] ?? '#0A2E2E' }}; 
        --secondary: {{ $themeColors['secondary_color'] ?? '#2A6363' }}; 
        --tertiary: #8E6E53;
        --light: #C69C72; 
        --text-dark: #000000; 
        --text-light: #FFFFFF; 
        --success: #5DBB63;
        --danger: #dc3545;
        --border: #E6D8C3; 
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    /* Reset et base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Conteneur principal */
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* En-tête du dashboard */
    .dashboard-header {
        margin-bottom: 2rem;
    }

    .dashboard-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .dashboard-subtitle {
        color: var(--secondary);
        font-size: 1rem;
    }

    /* Grille des statistiques */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, var(--primary), var(--secondary));
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--secondary);
        margin-left: 0.5rem;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(198, 156, 114, 0.2);
        color: var(--primary);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 0.5rem;
        margin-left: 0.5rem;
    }

    .stat-change {
        display: flex;
        align-items: center;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }

    .stat-change.positive {
        color: var(--success);
    }

    .stat-change.negative {
        color: var(--danger);
    }

    /* Grille des graphiques */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-card {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        border: 1px solid var(--border);
    }

    .chart-header {
        margin-bottom: 1rem;
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 0.5rem;
    }

    .chart-subtitle {
        color: var(--secondary);
        font-size: 0.9rem;
    }

    .chart-container {
        height: 300px;
        position: relative;
    }

    /* Sections de contenu */
    .content-section {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        border: 1px solid var(--border);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary);
    }

    /* Liste des activités récentes */
    .activities-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .activity-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .activity-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex: 1;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }

    .activity-title {
        font-weight: 600;
        color: var(--secondary);
    }

    .activity-time {
        color: var(--tertiary);
        font-size: 0.85rem;
    }

    .activity-description {
        color: var(--text-dark);
        margin-bottom: 0.25rem;
    }

    .activity-details {
        font-size: 0.85rem;
        color: var(--tertiary);
        font-style: italic;
    }

    /* Tableau des points de vente */
    .table-container {
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        text-align: left;
        padding: 1rem;
        background-color: rgba(10, 46, 46, 0.05);
        color: var(--primary);
        font-weight: 600;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border);
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover td {
        background-color: rgba(10, 46, 46, 0.02);
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background-color: rgba(93, 187, 99, 0.1);
        color: var(--success);
    }

    .badge-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger);
    }

    .badge-primary {
        background-color: rgba(10, 46, 46, 0.1);
        color: var(--primary);
    }

    /* Boutons d'action */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: var(--secondary);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--primary);
        border: 1px solid var(--primary);
    }

    .btn-outline:hover {
        background-color: rgba(10, 46, 46, 0.05);
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Tableau de bord</h1>
        <p class="dashboard-subtitle">Bienvenue dans votre espace de gestion des points de vente</p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Points de vente</div>
                <div class="stat-icon">
                    <i class="fas fa-store"></i>
                </div>
            </div>
            <div class="stat-value">{{ $statsData['totalStores'] }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $statsData['newLocationsThisMonth'] }} nouveaux ce mois
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Produits</div>
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="stat-value">{{ $statsData['totalProducts'] }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $statsData['newProductsThisMonth'] }} nouveaux ce mois
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Personnel</div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $statsData['totalStaff'] }}</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                {{ $statsData['newStaffThisMonth'] }} nouveaux ce mois
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Taux d'activité</div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value">{{ $statsData['activeStoresPercentage'] }}%</div>
            <div class="stat-change {{ $statsData['activeStoresChange'] >= 0 ? 'positive' : 'negative' }}">
                <i class="fas fa-arrow-{{ $statsData['activeStoresChange'] >= 0 ? 'up' : 'down' }}"></i>
                {{ abs($statsData['activeStoresChange']) }}% par rapport au mois dernier
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Répartition par ville</h3>
                <p class="chart-subtitle">Distribution des points de vente par ville</p>
            </div>
            <div class="chart-container">
                <canvas id="cityDistributionChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Répartition par service</h3>
                <p class="chart-subtitle">Distribution des points de vente par service</p>
            </div>
            <div class="chart-container">
                <canvas id="serviceDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Produits par catégorie</h3>
                <p class="chart-subtitle">Nombre de produits par catégorie</p>
            </div>
            <div class="chart-container">
                <canvas id="productsByCategoryChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Personnel par rôle</h3>
                <p class="chart-subtitle">Répartition du personnel par rôle</p>
            </div>
            <div class="chart-container">
                <canvas id="staffByRoleChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="content-section">
        <div class="section-header">
            <h3 class="section-title">Activités récentes</h3>
        </div>
        <div class="activities-list">
            @foreach($recentActivities as $activity)
            <div class="activity-item">
                <div class="activity-avatar">{{ $activity['avatar'] }}</div>
                <div class="activity-content">
                    <div class="activity-header">
                        <div class="activity-title">{{ $activity['storeName'] }}</div>
                        <div class="activity-time">{{ \Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</div>
                    </div>
                    <div class="activity-description">
                        @if($activity['type'] == 'creation')
                            Point de vente créé
                        @elseif($activity['type'] == 'modification')
                            Point de vente modifié
                        @elseif($activity['type'] == 'product_added')
                            Nouveau produit ajouté
                        @elseif($activity['type'] == 'staff_added')
                            Nouveau membre du personnel ajouté
                        @elseif($activity['type'] == 'schedule_updated')
                            Horaire régulier modifié
                        @elseif($activity['type'] == 'exception_added')
                            Exception d'horaire ajoutée
                        @elseif($activity['type'] == 'holiday_added')
                            Jour férié ajouté
                        @endif
                    </div>
                    @if(isset($activity['details']))
                    <div class="activity-details">
                        {{ $activity['details'] }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tableau des points de vente -->
    <div class="content-section">
        <div class="section-header">
            <h3 class="section-title">Points de vente récents</h3>
            <a href="{{ route('admin.stores.index') }}" class="btn btn-outline">
                <i class="fas fa-list"></i>
                Voir tous les points de vente
            </a>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Ville</th>
                        <th>Services</th>
                        <th>Produits</th>
                        <th>Personnel</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storesList as $store)
                    <tr>
                        <td>{{ $store['name'] }}</td>
                        <td>{{ $store['city'] }}</td>
                        <td>
                            @foreach($store['services'] as $service)
                                <span class="badge badge-primary">{{ $service }}</span>
                            @endforeach
                        </td>
                        <td>{{ $store['productsCount'] }}</td>
                        <td>{{ $store['staffCount'] }}</td>
                        <td>
                            <span class="badge {{ $store['status'] == 'active' ? 'badge-success' : 'badge-danger' }}">
                                {{ $store['status'] == 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.stores.manage', $store['id']) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-cog"></i>
                                Gérer
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les graphiques
        initCharts();
    });

    function initCharts() {
        // Données pour le graphique de répartition par ville
        const cityDistributionData = {
            labels: {!! json_encode(array_column($storesByCity, 'name')) !!},
            datasets: [{
                label: 'Nombre de points de vente',
                data: {!! json_encode(array_column($storesByCity, 'value')) !!},
                backgroundColor: [
                    '#0A2E2E', '#2A6363', '#8E6E53', '#C69C72', '#E6D8C3', '#F9F5EF'
                ],
                borderWidth: 1
            }]
        };
        
        // Données pour le graphique de répartition par service
        const serviceDistributionData = {
            labels: {!! json_encode(array_column($storesByService, 'name')) !!},
            datasets: [{
                label: 'Nombre de points de vente',
                data: {!! json_encode(array_column($storesByService, 'value')) !!},
                backgroundColor: [
                    '#0A2E2E', '#2A6363', '#8E6E53', '#C69C72'
                ],
                borderWidth: 1
            }]
        };
        
        // Données pour le graphique de produits par catégorie
        const productsByCategoryData = {
            labels: {!! json_encode(array_column($productsByCategory, 'name')) !!},
            datasets: [{
                label: 'Nombre de produits',
                data: {!! json_encode(array_column($productsByCategory, 'value')) !!},
                backgroundColor: [
                    '#0A2E2E', '#2A6363', '#8E6E53', '#C69C72', '#E6D8C3', '#F9F5EF'
                ],
                borderWidth: 1
            }]
        };
        
        // Données pour le graphique de personnel par rôle
        const staffByRoleData = {
            labels: {!! json_encode(array_column($staffByRole, 'name')) !!},
            datasets: [{
                label: 'Nombre de membres',
                data: {!! json_encode(array_column($staffByRole, 'value')) !!},
                backgroundColor: [
                    '#0A2E2E', '#2A6363', '#8E6E53', '#C69C72', '#E6D8C3', '#F9F5EF'
                ],
                borderWidth: 1
            }]
        };

        // Initialiser le graphique de répartition par ville
        const cityDistributionChart = document.getElementById('cityDistributionChart');
        if (cityDistributionChart) {
            new Chart(cityDistributionChart, {
                type: 'bar',
                data: cityDistributionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' points de vente';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        
        // Initialiser le graphique de répartition par service
        const serviceDistributionChart = document.getElementById('serviceDistributionChart');
        if (serviceDistributionChart) {
            new Chart(serviceDistributionChart, {
                type: 'pie',
                data: serviceDistributionData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed + ' points de vente';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Initialiser le graphique de produits par catégorie
        const productsByCategoryChart = document.getElementById('productsByCategoryChart');
        if (productsByCategoryChart) {
            new Chart(productsByCategoryChart, {
                type: 'bar',
                data: productsByCategoryData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' produits';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }
        
        // Initialiser le graphique de personnel par rôle
        const staffByRoleChart = document.getElementById('staffByRoleChart');
        if (staffByRoleChart) {
            new Chart(staffByRoleChart, {
                type: 'doughnut',
                data: staffByRoleData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.parsed + ' membres';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
