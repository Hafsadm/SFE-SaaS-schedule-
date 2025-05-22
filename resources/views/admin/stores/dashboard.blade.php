@extends('layouts.app')

@section('title', 'Tableau de bord Super Admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Tableau de bord Super Admin</h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Statistiques des utilisateurs -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Statistiques des utilisateurs</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="h1">{{ $totalUsers }}</div>
                                            <div>Total utilisateurs</div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="h1">{{ $adminUsers }}</div>
                                            <div>Admins</div>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div class="h1">{{ $superAdminUsers }}</div>
                                            <div>Super Admins</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-sm btn-primary">
                                        Gérer les utilisateurs
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Statistiques des points de vente -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">Statistiques des points de vente</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <div class="h1">{{ $totalStores }}</div>
                                            <div>Total</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1">{{ $mainStores }}</div>
                                            <div>Principaux</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1">{{ $subsidiaries }}</div>
                                            <div>Filiales</div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <div class="h1">{{ $independentStores }}</div>
                                            <div>Indépendants</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin.stores.index') }}" class="btn btn-sm btn-success">
                                        Gérer les points de vente
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques par utilisateur -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">Points de vente par utilisateur</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Utilisateur</th>
                                                    <th>Email</th>
                                                    <th>Rôle</th>
                                                    <th>Nombre de points de vente</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userStats as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if($user->role === 'super_admin')
                                                            <span class="badge bg-danger">Super Admin</span>
                                                        @elseif($user->role === 'admin')
                                                            <span class="badge bg-primary">Admin</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $user->role }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->stores_count }}</td>
                                                    <td>
                                                        <a href="{{ route('super-admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i> Modifier
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
