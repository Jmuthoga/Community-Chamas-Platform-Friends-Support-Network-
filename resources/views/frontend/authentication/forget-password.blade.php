<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Forget Password | {{ readConfig('site_name') }}
    </title>
    <!-- FAVICON ICON -->
    <link rel="shortcut icon" href="{{ assetImage(readconfig('site_logo')) }}" type="image/svg+xml">
    <!-- BACK-TOP CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/back-top/backToTop.css') }}">
    <!-- BOOTSTRAP CSS (5.3) -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <!-- APP-CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">

     <!-- FSN THEME COLORS -->
    <style>
        :root {
            --fsn-blue: #123A63;
            --fsn-blue-light: #1C4F7A;
            --fsn-green: #2E7D32;
            --fsn-green-light: #4CAF50;
        }

        body {
            background-color: #f4f7fb !important;
            color: #123A63;
        }

        .authentications .left-content {
            background-color: var(--fsn-blue);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .authentications .left-content img {
            max-width: 100%;
            height: auto;
        }

        .authentications .right-content {
            background-color: #ffffff;
            padding: 3rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }

        .authentication-form-header h3.form-title,
        .authentication-form-header p.form-des {
            color: var(--fsn-blue);
        }

        .authentication-form .form-label {
            color: var(--fsn-blue) !important;
        }

        .authentication-form .form-control {
            border: 1px solid var(--fsn-blue-light);
            color: var(--fsn-blue);
        }

        .authentication-form .form-control:focus {
            border-color: var(--fsn-green);
            box-shadow: 0 0 0 0.2rem rgba(46,125,50,.25);
        }

        .create-account-btn {
            background-color: var(--fsn-blue);
            color: #ffffff;
            border: 1px solid var(--fsn-blue);
        }

        .create-account-btn:hover {
            background-color: var(--fsn-blue-light);
            border-color: var(--fsn-blue-light);
        }

        a {
            color: var(--fsn-blue);
        }

        a:hover {
            color: var(--fsn-blue-light);
        }

        .forget {
            color: var(--fsn-blue);
        }

        .forget:hover {
            color: var(--fsn-blue-light);
        }

        .customcheck-label {
            color: var(--fsn-blue);
        }
        
                .authentications {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .authentications .right-content {
            width: 100%;
            max-width: 750px;
        }
    </style>
</head>

<body>
    <x-simple-alert />

    <!-- AUTHENTICATION-START (LOGIN) -->
    <section class="authentications">
        <!--<div class="left-content">-->
        <!--    <figure class="">-->
        <!--        <img src="{{ asset('assets/images/authentication/poss.png') }}" alt="register image">-->
        <!--    </figure>-->
        <!--</div>-->
        <div class="right-content">
            <form action="{{ route('forget.password') }}" method="post"
                class="authentication-form px-lg-5 forgot-form needs-validation" novalidate>
                @csrf
                <div class="authentication-form-header">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <a href="{{ route('frontend.home') }}" class="logo">
                            <img src="{{ assetImage(readconfig('site_logo')) }}" width="200px" alt="brand-logo">
                        </a>
                    </div>
                    <h3 class="form-title">Forgot Password?</h3>
                    <p class="form-des">Please enter the email you use to sign in.</p>
                </div>
                <div class="authentication-form-content">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter email"
                                    autocomplete="off" name="email" required>
                                <div class="invalid-feedback">
                                    Enter a valid email address
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="create-account-btn w-100">Request password reset</button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="authentication-form-footer">
                    <p>Back to <a href="{{ route('login') }}">Log in </a></p>
                </div>
            </form>
        </div>
    </section>
    <!-- AUTHENTICATION-END -->


    <!-- BOOTSTRAP JS (5.3) -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- BOOTSTRAP-TOOLTIP -->
    <script src="{{ asset('assets/js/tooltip/tooltip.js') }}"></script>
    <!-- BACK-TOP JS -->
    <script src="{{ asset('assets/js/back-top/backToTop.js') }}"></script>
    <script src="{{ asset('assets/js/back-top/backtop.js') }}"></script>
    <!-- COPYRIGHT JS -->
    <script src="{{ asset('assets/js/copyright/copyright.js') }}"></script>
    <!-- VALIDATION  -->
    <script src="{{ asset('assets/js/validation/validation.js') }}"></script>

</body>

</html>
