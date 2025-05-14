
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Professional Trading Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('/images/trading-bg.jpg');
            background-size: cover;
            height: 100vh;
            color: white;
        }
        .trading-card {
            transition: transform 0.3s;
        }
        .trading-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#trading">Trading</a></li>
                    <li class="nav-item"><a class="nav-link" href="#platforms">Platforms</a></li>
                    <li class="nav-item"><a class="nav-link" href="#transactions">Transactions</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link btn btn-primary ms-2" href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section d-flex align-items-center">
        <div class="container text-center">
            <h1 class="display-3 mb-4">Trade with Confidence</h1>
            <p class="lead mb-4">Access global markets with our advanced trading platforms</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
                <a href="#platforms" class="btn btn-outline-light btn-lg">Explore Platforms</a>
            </div>
        </div>
    </section>

    <!-- Trading Options -->
    <section id="trading" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Trading Options</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card trading-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line feature-icon mb-3"></i>
                            <h4>Binary Trading</h4>
                            <p>Simple up/down trading with fixed time expiry and returns</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card trading-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-exchange-alt feature-icon mb-3"></i>
                            <h4>Forex Trading</h4>
                            <p>Trade major, minor, and exotic currency pairs</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card trading-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-building feature-icon mb-3"></i>
                            <h4>Shares Trading</h4>
                            <p>Invest in global stocks and ETFs</p>
                            <a href="#" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trading Platforms -->
    <section id="platforms" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Trading Platforms</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4>MetaTrader 4</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Advanced charting</li>
                                <li><i class="fas fa-check text-success me-2"></i>Expert Advisors</li>
                                <li><i class="fas fa-check text-success me-2"></i>Mobile trading</li>
                            </ul>
                            <a href="#" class="btn btn-primary">Download MT4</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4>MetaTrader 5</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Enhanced features</li>
                                <li><i class="fas fa-check text-success me-2"></i>Multiple asset classes</li>
                                <li><i class="fas fa-check text-success me-2"></i>Advanced analysis tools</li>
                            </ul>
                            <a href="#" class="btn btn-primary">Download MT5</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Transactions Section -->
    <section id="transactions" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Quick Transactions</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-wallet feature-icon mb-3"></i>
                            <h4>Deposit Funds</h4>
                            <p>Multiple payment methods available</p>
                            <a href="{{ route('deposits.create') }}" class="btn btn-success">Make Deposit</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-money-bill-wave feature-icon mb-3"></i>
                            <h4>Withdraw Funds</h4>
                            <p>Fast and secure withdrawals</p>
                            <a href="{{ route('withdrawals.create') }}" class="btn btn-primary">Request Withdrawal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>About Us</h5>
                    <p>Your trusted partner in online trading</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">Terms & Conditions</a></li>
                        <li><a href="#" class="text-light">Privacy Policy</a></li>
                        <li><a href="#" class="text-light">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>Email: support@example.com</p>
                    <p>Phone: +1 234 567 890</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
