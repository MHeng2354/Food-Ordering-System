<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodOrder System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        main { flex: 1; overflow-y: auto; scroll-behavior: smooth; }
        .navbar { z-index: 1030 !important; }
        .navbar-collapse { z-index: 1031 !important; }
        .dropdown-menu { z-index: 1032 !important; }
        .hero {
            overflow: hidden;
            color: #fff;
        }
        .section-title {
            font-weight: 700;
            letter-spacing: -0.03em;
        }
        .feature-card {
            min-height: 220px;
        }
        .dish-card img {
            height: 220px;
            object-fit: cover;
        }
        .banner-text {
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        }
        .social-link { transition: color 0.3s; }
        .social-link:hover { color: white !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/homepage">🍕 Ching Chong Express</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/homepage">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="/promotions">Promotions</a></li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/cart">Cart ({{ session('cart') ? count(session('cart')) : 0 }})</a>
                    </li>

                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @else
                        @if(Auth::user()->role == 'admin')
                            <li class="nav-item"><a class="nav-link text-warning" href="/admin/dashboard">Admin Panel</a></li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/orders">My Orders</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content') 
    </main>

    <footer class="bg-dark text-white pt-4 pb-2 mt-auto">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-4 text-center">
                    <h5>About Us</h5>
                    <p class="text-white-50 text-decoration-none">Learn more about our story and mission.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="/menu" class="text-white-50 text-decoration-none social-link">Menu</a></li>
                        <li><a href="/about" class="text-white-50 text-decoration-none social-link">About Us</a></li>
                        <li><a href="/contact" class="text-white-50 text-decoration-none social-link">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Follow Us</h5>
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <a href="https://facebook.com" class="text-white-50 social-link" target="_blank"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com" class="text-white-50 social-link" target="_blank"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com" class="text-white-50 social-link" target="_blank"><i class="bi bi-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr class="bg-white">
            <p class="small mb-0">&copy; 2026 Ching Chong Express. Built for UECS2354 Laravel Assignment. No cats or dogs were harmed in the process :) </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>