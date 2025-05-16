@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')

@section('page-title', 'Gestion des utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('users.index') }}" method="GET" class="d-flex">
                <div class="input-group me-2">
                    <input type="text" class="form-control" placeholder="Rechercher un utilisateur..." name="search" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <select class="form-select w-auto" name="role" onchange="this.form.submit()">
                    <option value="">Tous les rôles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i> Nouvel utilisateur
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Nom d'utilisateur</th>
                            <th>Email</th>
                            <th>Mot de passe</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="user-avatar me-2">
                                        @else
                                            <div class="user-avatar-placeholder me-2">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-sm password-field" value="********" readonly>
                                        <button class="btn btn-sm btn-outline-secondary toggle-password" type="button" data-user-id="{{ $user->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                                <td><span class="badge bg-info">{{ $user->role->name }}</span></td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}"
                                                onclick="document.getElementById('toggle-form-{{ $user->id }}').submit();"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $user->id }}"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <form id="toggle-form-{{ $user->id }}" action="{{ route('users.toggle-active', $user) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('PUT')
                                    </form>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Confirmer la suppression</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>{{ $user->name }}</strong> ?
                                                    Cette action est irréversible.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .user-avatar, .user-avatar-placeholder {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
    }

    .user-avatar-placeholder {
        background-color: #4CAF50;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .password-field {
        font-family: monospace;
        letter-spacing: 1px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle password visibility toggle
        const toggleButtons = document.querySelectorAll('.toggle-password');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordField = this.previousElementSibling;
                const icon = this.querySelector('i');

                // Toggle password visibility
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');

                    // Show actual password (in a real app, this would be an AJAX call to get the actual password)
                    // For demo purposes, we're showing a fake password
                    const userId = this.getAttribute('data-user-id');
                    const fakePasswords = {
                        // Map user IDs to fake passwords
                        // In a real app, you would fetch this from the server
                        1: 'admin123',
                        2: 'tech123',
                        3: 'agric123'
                    };

                    // Set a default password if the user ID is not in our map
                    passwordField.value = fakePasswords[userId] || 'password' + userId;

                    // Auto-hide after 3 seconds for security
                    setTimeout(() => {
                        passwordField.type = 'password';
                        passwordField.value = '********';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }, 3000);
                } else {
                    passwordField.type = 'password';
                    passwordField.value = '********';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>
@endsection
