@extends('layouts.app')

@section('title', 'Paramètres')

@section('page-title', 'Paramètres')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="weather-tab" data-bs-toggle="tab" data-bs-target="#weather" type="button" role="tab" aria-controls="weather" aria-selected="true">
                        <i class="fas fa-cloud me-2"></i> Météo
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab" aria-controls="preferences" aria-selected="false">
                        <i class="fas fa-sliders-h me-2"></i> Préférences
                    </button>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="tab-content" id="settingsTabsContent">
        <!-- Weather Settings Tab -->
        <div class="tab-pane fade show active" id="weather" role="tabpanel" aria-labelledby="weather-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Paramètres météorologiques</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update-weather') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="api_provider" class="form-label">Fournisseur API</label>
                                    <select class="form-select @error('api_provider') is-invalid @enderror" id="api_provider" name="api_provider" required>
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach($weatherProviders as $key => $provider)
                                            <option value="{{ $key }}" {{ old('api_provider', $weatherSettings->api_provider ?? '') == $key ? 'selected' : '' }}>
                                                {{ $provider }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('api_provider')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="api_key" class="form-label">Clé API</label>
                                    <input type="text" class="form-control @error('api_key') is-invalid @enderror" id="api_key" name="api_key" value="{{ old('api_key', $weatherSettings->api_key ?? '') }}" required>
                                    @error('api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Vous pouvez obtenir une clé API en vous inscrivant sur le site du fournisseur sélectionné.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">Localisation</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $weatherSettings->location ?? 'Béchar, Algeria') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Enregistrer les paramètres
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Preferences Tab -->
        <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Préférences utilisateur</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('settings.update-preferences') }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="language" class="form-label">Langue</label>
                                    <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                        @foreach($languages as $key => $language)
                                            <option value="{{ $key }}" {{ old('language', $preferences->language ?? 'fr') == $key ? 'selected' : '' }}>
                                                {{ $language }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Thème</label>
                                    <div class="d-flex">
                                        <div class="form-check me-4">
                                            <input class="form-check-input" type="radio" name="theme" id="theme_light" value="light" {{ old('theme', $preferences->theme ?? 'light') == 'light' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="theme_light">
                                                <i class="fas fa-sun me-2"></i> Clair
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="theme" id="theme_dark" value="dark" {{ old('theme', $preferences->theme ?? 'light') == 'dark' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="theme_dark">
                                                <i class="fas fa-moon me-2"></i> Sombre
                                            </label>
                                        </div>
                                    </div>
                                    @error('theme')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Enregistrer les préférences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
