@extends('layouts.app')

@section('title', 'Profil')

@section('page-title', 'Mon Profil')

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
                    <div class="mt-3">
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-primary">
                            <i class="fas fa-key me-2"></i> Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nom d'utilisateur</label>
                        <div class="form-control bg-light">{{ $user->username }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nom complet</label>
                        <div class="form-control bg-light">{{ $user->name }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="form-control bg-light">{{ $user->email }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <div class="form-control bg-light">{{ $user->role->name }}</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('profile.change-password') }}" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i> Changer le mot de passe
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Activités récentes</h5>
                    <span class="badge bg-primary">{{ $recentActivities->count() }}</span>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                        <div class="timeline">
                            @foreach($recentActivities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        @if($activity->module == 'profile')
                                            <i class="fas fa-user"></i>
                                        @elseif($activity->module == 'planification')
                                            <i class="fas fa-calendar-alt"></i>
                                        @elseif($activity->module == 'pivot')
                                            <i class="fas fa-chart-bar"></i>
                                        @else
                                            <i class="fas fa-check"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <h6 class="mb-0">{{ $activity->description }}</h6>
                                        <small class="text-muted">
                                            {{ $activity->created_at->diffForHumans() }} -
                                            <span class="badge bg-secondary">{{ $activity->module }}</span>
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Aucune activité récente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
