<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JM Innovatech POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
            color: #0d6efd !important;
        }
        .hero {
            background: linear-gradient(to right, #0d6efd, #007bff);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-weight: 700;
        }
        .features {
            padding: 60px 0;
        }
        .feature-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            padding: 30px;
            height: 100%;
        }
        footer {
            background: #0d6efd;
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">JM Innovatech POS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item mx-2">
                    <a href="#features" class="nav-link">Features</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="#pricing" class="nav-link">Pricing</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="#contact" class="nav-link">Contact</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-primary">Sign In</a>
                </li>
                <li class="nav-item mx-2">
                    <a href="{{ route('register') }}" class="btn btn-primary">Sign Up</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <h1 class="display-5 mb-3">Smart. Fast. Reliable POS System</h1>
        <p class="lead mb-4">The all-in-one Point of Sale system for every business — from pharmacies to supermarkets.</p>
        <a href="{{ route('login') }}" class="btn btn-light btn-lg me-3">Try Demo</a>
        <a href="https://wa.me/254791446968" target="_blank" class="btn btn-outline-light btn-lg">
            <i class="bi bi-whatsapp"></i> Chat Support
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="features">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">Powerful Features</h2>
            <p class="text-muted">Everything you need to manage your business efficiently</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>🛒 Retail & Wholesale</h5>
                    <p>Handle sales for any business type — pharmacies, hardware, shops, and more.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>📦 Stock Management</h5>
                    <p>Track product quantities, alerts, and restocking with ease.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>💰 Expense & Profit Reports</h5>
                    <p>Get clear insights into daily sales, expenses, and profit margins.</p>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>👥 Customers & Suppliers</h5>
                    <p>Manage your clients, vendors, and credit transactions easily.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>🔐 Roles & Permissions</h5>
                    <p>Assign secure access levels for admins, cashiers, and managers.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <h5>📲 M-PESA Integration</h5>
                    <p>Accept payments via M-PESA, PesaPal, ChapaPay, and Airtel Money.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section class="py-5 bg-light" id="pricing">
    <div class="container text-center">
        <h2 class="fw-bold text-primary mb-4">Simple Pricing</h2>
        <div class="card mx-auto border-0 shadow-lg rounded-4" style="max-width: 400px;">
            <div class="card-body">
                <h3 class="fw-bold">KSH. 20,000</h3>
                <p class="text-muted mb-3">One-Time Installation Fee</p>
                <ul class="list-unstyled text-start mb-4">
                    <li>✅ Lifetime Access</li>
                    <li>✅ No Monthly Fees</li>
                    <li>✅ Free Updates</li>
                    <li>✅ 7-Day Free Trial</li>
                </ul>
                <a href="https://wa.me/254791446968" target="_blank" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-whatsapp"></i> Request Installation
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="contact">
    <div class="container">
        <p class="mb-0">© {{ date('Y') }} JM Innovatech Solutions — All Rights Reserved.</p>
        <small>Contact: <a href="mailto:info@jminnovatechsolution.co.ke" class="text-white">info@jminnovatechsolution.co.ke</a></small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
