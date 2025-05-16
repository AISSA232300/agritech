@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('page-title', 'Détails de l\'utilisateur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="profile-image img-fluid rounded-circle mb-3">
                    @else
                        <div class="profile-image-placeholder rounded-circle mb-3 mx-auto">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <h4>{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->role->name }}</p>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} mb-3">
                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i> Modifier
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Nom d'utilisateur</div>
                        <div class="col-md-8">{{ $user->username }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Date de création</div>
                        <div class="col-md-8">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Dernière mise à jour</div>
                        <div class="col-md-8">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Préférences</h5>
                </div>
                <div class="card-body">
                    @if($user->preference)
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Langue</div>
                            <div class="col-md-8">
                                @if($user->preference->language == 'fr')
                                    Français
                                @elseif($user->preference->language == 'en')
                                    English
                                @elseif($user->preference->language == 'ar')
                                    العربية
                                @else
                                    {{ $user->preference->language }}
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Thème</div>
                            <div class="col-md-8">
                                @if($user->preference->theme == 'light')
                                    <i class="fas fa-sun me-2"></i> Clair
                                @elseif($user->preference->theme == 'dark')
                                    <i class="fas fa-moon me-2"></i> Sombre
                                @else
                                    {{ $user->preference->theme }}
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Aucune préférence définie</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
    }

    .profile-image-placeholder {
        width: 150px;
        height: 150px;
        background-color: #4CAF50;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
    }
</style>
@endsection
