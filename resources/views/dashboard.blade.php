@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('page-title', 'Tableau de bord')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue, {{ auth()->user()->name }}!</h5>
                    <p class="card-text">
                        Voici votre tableau de bord AgriTech Béchar.
                        @if(auth()->user()->hasManagementAccess())
                            <span class="badge bg-primary">Accès Gestionnaire</span>
                        @else
                            <span class="badge bg-secondary">Accès Employé</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations météorologiques</h5>
                </div>
                <div class="card-body">
                    @if($weatherSettings)
                        <div class="weather-info">
                            <p><strong>Fournisseur API:</strong> {{ $weatherSettings->api_provider }}</p>
                            <p><strong>Localisation:</strong> {{ $weatherSettings->location }}</p>
                            <!-- Weather data would be displayed here -->
                            <div class="alert alert-info">
                                Les données météorologiques seront affichées ici une fois que l'API sera configurée.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Vous n'avez pas encore configuré vos paramètres météorologiques.
                            <a href="{{ route('settings.index') }}" class="alert-link">Configurer maintenant</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Votre profil</h5>
                </div>
                <div class="card-body">
                    <div class="profile-summary">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="profile-image img-fluid rounded-circle">
                                @else
                                    <div class="profile-image-placeholder rounded-circle">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><strong>Rôle:</strong> {{ auth()->user()->role->name }}</p>
                                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                                <p><strong>Téléphone:</strong> {{ auth()->user()->phone ?? 'Non spécifié' }}</p>
                                <a href="{{ route('profile.index') }}" class="btn btn-sm btn-primary">Modifier le profil</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
