<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Your premier online shopping destination for quality products">
    <meta name="keywords" content="shop, online, products, ecommerce">
    <title>@yield('title', 'Web Service - Premium Shopping Experience')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Dark Theme CSS -->
    <link href="{{ asset('css/dark-theme.css') }}" rel="stylesheet">

    <!-- Enhanced UI CSS -->
    <link href="{{ asset('css/enhanced-ui.css') }}" rel="stylesheet">

    <!-- Modern Style CSS -->
    <link href="{{ asset('css/modern-style.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="preloader-spinner"></div>
    </div>

    @include('layouts.menu')

    <main class="py-4">
        <div class="container">
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <div class="footer-logo">Web Service</div>
                    <p class="footer-text">Your premier destination for quality products and exceptional service. We're committed to providing the best shopping experience.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        @auth
                            <li><a href="{{ route('users.profile') }}">My Profile</a></li>
                            @role('customer')
                                <li><a href="{{ route('purchases.index') }}">My Purchases</a></li>
                            @endrole
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-title">Categories</h5>
                    <ul class="footer-links">
                        <li><a href="#">Electronics</a></li>
                        <li><a href="#">Fashion</a></li>
                        <li><a href="#">Home & Garden</a></li>
                        <li><a href="#">Sports</a></li>
                        <li><a href="#">Beauty</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">Contact Us</h5>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Street Name, City, Country</span>
                        </li>
                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span>+1 234 567 8900</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@webservice.com</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} Web Service. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Theme Toggle Button -->
    <button id="theme-toggle" class="theme-toggle" title="Toggle Dark/Light Mode">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Theme Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preloader
            const preloader = document.getElementById('preloader');
            window.addEventListener('load', function() {
                preloader.classList.add('hidden');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            });

            // Theme Toggle
            const themeToggle = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;
            const bodyElement = document.body;
            const themeIcon = themeToggle.querySelector('i');

            // Check if user has a theme preference in localStorage
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                htmlElement.classList.add('dark-theme');
                bodyElement.classList.add('dark-theme');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }

            // Toggle theme when button is clicked
            themeToggle.addEventListener('click', function() {
                if (htmlElement.classList.contains('dark-theme')) {
                    // Switch to light theme
                    htmlElement.classList.remove('dark-theme');
                    bodyElement.classList.remove('dark-theme');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                } else {
                    // Switch to dark theme
                    htmlElement.classList.add('dark-theme');
                    bodyElement.classList.add('dark-theme');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                }
            });

            // Navbar Scroll Effect
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Animation on scroll
            const animateElements = document.querySelectorAll('.animate-on-scroll');

            const animateOnScroll = function() {
                animateElements.forEach(function(element) {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementPosition < windowHeight - 50) {
                        const animationClass = element.dataset.animation || 'animate-fade-in';
                        element.classList.add(animationClass);
                    }
                });
            };

            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Run once on page load
        });
    </script>
</body>
</html>