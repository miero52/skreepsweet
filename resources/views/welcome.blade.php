<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SILAP - Sistem Layanan Administrasi Publik | Kemenag Palembang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Elements */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite linear;
        }

        .floating-shape:nth-child(1) {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: -5s;
        }

        .floating-shape:nth-child(3) {
            width: 80px;
            height: 80px;
            top: 40%;
            left: 80%;
            animation-delay: -10s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        /* Main Content */
        .hero-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .hero-content {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            font-size: 4rem;
            font-weight: 700;
            color: white;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
            letter-spacing: 3px;
        }

        .subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .organization {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .features-grid {
            margin: 2.5rem 0;
        }

        .feature-item {
            text-align: center;
            color: white;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .feature-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .feature-item:nth-child(3) {
            animation-delay: 0.4s;
        }

        .feature-item:nth-child(4) {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            transition: transform 0.3s ease;
        }

        .feature-item:hover .feature-icon {
            transform: translateY(-5px);
        }

        .action-buttons {
            text-align: center;
            margin-top: 2.5rem;
        }

        .btn-custom {
            padding: 0.875rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 0.5rem;
            min-width: 180px;
        }

        .btn-login {
            background: rgba(255, 255, 255, 0.9);
            color: #667eea;
            border: 2px solid transparent;
        }

        .btn-login:hover {
            background: white;
            color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .btn-register {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .btn-register:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: white;
            color: white;
            transform: translateY(-2px);
        }

        .info-text {
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            margin-top: 2rem;
            font-size: 0.95rem;
        }

        .footer-info {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.85rem;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .logo-section h1 {
                font-size: 3rem;
            }

            .subtitle {
                font-size: 1.3rem;
            }

            .btn-custom {
                width: 100%;
                margin: 0.5rem 0;
            }

            .features-grid {
                margin: 1.5rem 0;
            }
        }

        /* Pulse animation for register button */
        .btn-register {
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-register:hover::before {
            width: 300px;
            height: 300px;
        }
    </style>
</head>

<body>
    <!-- Animated Background -->
    <div class="bg-animation">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <!-- Main Content -->
    <div class="hero-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-6">
                    <div class="hero-content">
                        <!-- Logo Section -->
                        <div class="logo-section">
                            <div class="mb-3">
                                <i class="fas fa-building fa-3x text-white opacity-75"></i>
                            </div>
                            <h1>SILAP</h1>
                            <p class="subtitle">Sistem Layanan Administrasi Publik</p>
                            <p class="organization">Kementerian Agama Palembang</p>
                        </div>

                        <!-- Features Grid -->
                        <div class="features-grid">
                            <div class="row">
                                <div class="col-6 col-md-3">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-clock fa-lg"></i>
                                        </div>
                                        <h6 class="fw-semibold">24/7 Online</h6>
                                        <small class="opacity-75">Layanan tersedia kapan saja</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-shield-alt fa-lg"></i>
                                        </div>
                                        <h6 class="fw-semibold">Data Aman</h6>
                                        <small class="opacity-75">Keamanan terjamin</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-rocket fa-lg"></i>
                                        </div>
                                        <h6 class="fw-semibold">Proses Cepat</h6>
                                        <small class="opacity-75">Respon dalam 24 jam</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="feature-item">
                                        <div class="feature-icon">
                                            <i class="fas fa-download fa-lg"></i>
                                        </div>
                                        <h6 class="fw-semibold">Download Langsung</h6>
                                        <small class="opacity-75">Hasil bisa diunduh</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="{{ route('login') }}" class="btn-custom btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Masuk ke Sistem
                            </a>
                            <a href="{{ route('register') }}" class="btn-custom btn-register">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar Sebagai Masyarakat
                            </a>
                        </div>

                        <!-- Info Text -->
                        <div class="info-text">
                            <p class="mb-2">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Untuk Petugas:</strong> Silakan login dengan akun yang sudah disediakan
                            </p>
                            <p class="mb-0">
                                Proses pengajuan surat keterangan dan izin yang mudah, cepat, dan terpercaya
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="footer-info">
        <div>© 2025 Kementerian Agama Palembang - Sistem Layanan Administrasi Publik</div>
        <div class="mt-1">
            <i class="fas fa-phone me-2"></i>(0711) 123456
            <span class="mx-2">•</span>
            <i class="fas fa-envelope me-2"></i>info@kemenag-palembang.go.id
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>