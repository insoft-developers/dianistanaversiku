
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield("title") | DianIstana</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="{{ asset('') }}assets/template/main/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('') }}assets/template/main/lib/animate/animate.min.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/template/main/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('') }}assets/template/main/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('') }}assets/template/main/css/style.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/css/swiffy-slider.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/swiffy-slider@1.6.0/dist/js/swiffy-slider.min.js" crossorigin="anonymous" defer></script>
    <script src="https://unpkg.com/smoothscroll-polyfill/dist/smoothscroll.min.js" crossorigin="anonymous" defer></script>
</head>

<body class="bg-white">
    <div class="container-xxl bg-white p-0">
        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                <a href="{{ route("home_public") }}" class="navbar-brand d-flex align-items-center text-center">
                    {{-- <div class="icon p-2 me-2"> --}}
                        <img class="img-fluid" src="{{ asset('') }}assets/template/main/img/logo-dianistana.png" alt="Icon" style="width: 220px; height: 50px;">
                    {{-- </div> --}}
                    {{-- <h1 class="m-0 text-primary">DianIstana</h1> --}}
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Login Or Register</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="{{ route("login_user") }}" class="dropdown-item">Login Masuk</a>
                                <a href="{{ route("register_user") }}" class="dropdown-item">Register Akun</a>
                            </div>
                        </div>
                        {{-- <a href="index.html" class="nav-item nav-link active">Home</a> --}}
                        <a href="#" class="nav-item nav-link">Booking Unit</a>
                        <a href="#" class="nav-item nav-link">System Ticketing</a>
                        <a href="#" class="nav-item nav-link">Contact US</a>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->

        @yield("content")

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 col-md-6">
                        <h5 class="text-white mb-4">DianIstana</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Clubhouse Dian Istana Jl.Raya Dian Istana Surabaya 60187</p>
                        <p class="mb-2">
                            <i class="fa fa-phone-alt me-3"></i> +62317530123
                        </p>
                        <p class="mb-2">
                            <i class="fa fa-phone-alt me-3"></i> +628155299000
                        </p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@dian-istana.com</p>
                        {{-- <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div> --}}
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <h5 class="text-white mb-4">MORE INFORMATION</h5>
                        <a class="btn btn-link text-white-50" href="">Booking Unit</a>
                        <a class="btn btn-link text-white-50" href="">System Ticketing</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            © Copyright Dian Istana (from 1997). All Rights Reserved.
                            {{-- &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved.  --}}
							
							<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
							{{-- Designed By  --}}
                            <a class="border-bottom" style="display: none;" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        {{-- <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/template/main/lib/wow/wow.min.js"></script>
    <script src="{{ asset('') }}assets/template/main/lib/easing/easing.min.js"></script>
    <script src="{{ asset('') }}assets/template/main/lib/waypoints/waypoints.min.js"></script>
    <script src="{{ asset('') }}assets/template/main/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('') }}assets/template/main/js/main.js"></script>
</body>

</html>