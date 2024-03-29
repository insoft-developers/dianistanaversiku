<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SignIn | DianIstana</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('') }}assets/template/src/assets/img/favicon.ico"/>
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/light/loader.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/dark/loader.css" rel="stylesheet" type="text/css" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('') }}assets/template/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="{{ asset('') }}assets/template/src/plugins/src/font-icons/fontawesome-5.15.4/css/all.css">
    <link rel="stylesheet" href="{{ asset('') }}assets/template/src/plugins/src/sweetalerts2/sweetalerts2.css">
    <link href="{{ asset('') }}assets/template/src/plugins/css/light/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/plugins/css/dark/sweetalerts2/custom-sweetalert.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/light/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('') }}assets/template/layouts/modern-light-menu/css/dark/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('') }}assets/template/src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

</head>
<body class="form">

    <div class="auth-container d-flex">
        <div class="container mx-auto align-self-center">
            <div class="row">
                <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-8 col-12 d-flex flex-column align-self-center mx-auto">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
                            <form id="formData" action="#" method="POST" class="mt-0">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <h2 class="text-secondary">Sign In Admin DianIstana</h2>
                                        <p>Enter your Username and password to login</p>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" id="username" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input me-3" type="checkbox"
                                                   name="remember_me" id="remember_me">
                                                <label class="form-check-label" for="remember_me">
                                                    Remember me
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <button type="button" id="btnSignIn" class="btn btn-secondary w-100"><i class="fas fa-sign-in-alt"></i>&nbsp; SIGN IN</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('') }}assets/template/src/plugins/src/global/vendors.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('') }}assets/template/src/plugins/src/sweetalerts2/sweetalerts2.min.js"></script>
    <script>
        var url_ori = '{{ url("") }}';
        var tokenCsrf = $('meta[name=csrf-token]').attr('content');
    </script>
    {{ assets_js_back("signin") }}
    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>
</html>
