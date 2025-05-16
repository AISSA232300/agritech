<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - AgriTech Béchar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('login.css') }}">
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

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-10">
                <div class="card login-card">
                    <div class="row g-0">
                        <div class="col-md-6 d-flex align-items-center justify-content-center">
                            <div class="login-animation-container">
                                <div id="login-animation"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body p-5">
                                <div class="text-center mb-4">
                                    <h2 class="login-title">AgriTech Béchar</h2>
                                    <p class="login-subtitle">Connectez-vous à votre compte</p>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Nom d'utilisateur</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                        </div>
                                    </div>
                                    <div class="mb-4 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">Se souvenir de moi</label>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg">Connexion</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.8/lottie.min.js"></script>
    <script src="{{ asset('auth.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loader when page is loaded
            setTimeout(function() {
                document.getElementById('loader').classList.add('hidden');
            }, 1000);

            // Initialize login animation
            const loginContainer = document.getElementById('login-animation');
            if (loginContainer && typeof lottie !== 'undefined') {
                const loginAnimation = lottie.loadAnimation({
                    container: loginContainer,
                    renderer: 'svg',
                    loop: true,
                    autoplay: true,
                    path: '{{ asset("animations/login-farmeranim.json") }}'
                });
            }
        });
    </script>
</body>
</html>
