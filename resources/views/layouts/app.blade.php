<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - AgriTech Béchar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('app.css') }}">
    <link rel="stylesheet" href="{{ asset('common-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('language.css') }}">
    @yield('styles')
</head>
<body>
    <!-- Loader -->
    <div class="loader-container" id="loader">
        <div class="loader">
            <div class="loader-circle"></div>
            <div class="loader-icon">
                <i class="fas fa-leaf"></i>
            </div>
        </div>
        <div class="loader-text">Chargement...</div>
    </div>

    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-brand">AgriTech</h3>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="sidebar-user-info">
                    <h5>{{ auth()->user()->name }}</h5>
                    <p>{{ auth()->user()->role->name }}</p>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}">
                            <i class="fas fa-user"></i>
                            <span>Profil</span>
                        </a>
                    </li>
                    @if(auth()->user()->isAdmin())
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ request()->routeIs('settings.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Déconnexion</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <div class="header-left">
                    <button class="sidebar-toggle-mobile" id="sidebarToggleMobile">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page-title')</h1>
                </div>
                <div class="header-right">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="header-avatar">
                            @else
                                <div class="header-avatar-placeholder">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="d-none d-md-inline-block ms-2">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2"></i> Paramètres</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('header-logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </a>
                                <form id="header-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('auth.js') }}"></script>
    <script src="{{ asset('role-permissions.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader when page is loaded
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 500);

            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
            const appContainer = document.querySelector('.app-container');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    appContainer.classList.toggle('sidebar-collapsed');
                });
            }

            if (sidebarToggleMobile) {
                sidebarToggleMobile.addEventListener('click', function() {
                    appContainer.classList.toggle('sidebar-mobile-open');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (appContainer.classList.contains('sidebar-mobile-open') &&
                    !event.target.closest('.sidebar') &&
                    !event.target.closest('.sidebar-toggle-mobile')) {
                    appContainer.classList.remove('sidebar-mobile-open');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
