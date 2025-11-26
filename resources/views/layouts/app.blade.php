<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SILAP') }} - Kemenag Palembang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
            --danger-gradient: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            background: var(--dark-gradient);
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 280px;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand h4 {
            background: linear-gradient(45deg, #fff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .user-profile {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 0.75rem;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.875rem 1.5rem;
            border-radius: 0;
            margin: 0.125rem 0.75rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            transform: translateX(8px);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 0;
            min-height: 100vh;
        }

        .top-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 2rem;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .page-content {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: transparent;
            border: none;
            padding: 1.5rem 1.5rem 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            border-radius: 12px;
            font-weight: 500;
            padding: 0.675rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-success {
            background: var(--success-gradient);
        }

        .btn-warning {
            background: var(--warning-gradient);
        }

        .btn-danger {
            background: var(--danger-gradient);
        }

        /* Form Controls */
        .form-control,
        .form-select {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: white;
        }

        /* Statistics Cards */
        .stat-card {
            background: var(--primary-gradient);
            color: white;
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .stat-card.success {
            background: var(--success-gradient);
        }

        .stat-card.warning {
            background: var(--warning-gradient);
        }

        .stat-card.danger {
            background: var(--danger-gradient);
        }

        /* Badges */
        .badge {
            padding: 0.5rem 0.875rem;
            border-radius: 50px;
            font-weight: 500;
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border: none;
            padding: 1.5rem 1.5rem 0;
        }

        /* Table */
        .table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table th {
            background: #f8fafc;
            font-weight: 600;
            color: #4a5568;
            border: none;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9;
        }

        /* Animations */
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

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .page-content {
                padding: 1rem;
            }
        }

        /* Notification Bell */
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 50px;
            min-width: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand text-center">
                <h4 class="mb-1">SILAP</h4>
                <p class="text-white-50 small mb-0">Kemenag Palembang</p>
            </div>
            @auth
            <div class="user-profile">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=667eea&color=fff&size=120"
                    alt="Avatar"
                    class="user-avatar">
                <div class="text-white">
                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                    <small class="text-white-50">
                        <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i>
                        {{ ucfirst(Auth::user()->role) }}
                    </small>
                </div>
            </div>

            <nav class="nav flex-column mt-3">
                @if(Auth::user()->role === 'masyarakat')
                <a class="nav-link {{ request()->routeIs('masyarakat.dashboard') ? 'active' : '' }}"
                    href="{{ route('masyarakat.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('masyarakat.create-permohonan') ? 'active' : '' }}"
                    href="{{ route('masyarakat.create-permohonan') }}">
                    <i class="fas fa-plus-circle me-2"></i>
                    Ajukan Permohonan
                </a>
                @elseif(Auth::user()->role === 'petugas')
                <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}"
                    href="{{ route('petugas.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>
                @endif

                @if(Auth::user()->role === 'masyarakat')
                <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                    href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-cog me-2"></i>
                    Profile
                </a>
                @endif

                <div class="mt-auto pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start text-white-50">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </nav>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <!-- Top Navigation -->
            <header class="top-navbar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-dark fw-bold">@yield('title', 'Dashboard')</h4>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ now()->locale('id')->translatedFormat('l, d F Y • H:i') }}
                        </small>
                    </div>

                    <!-- Ganti bagian notification di header dengan kode ini -->
                    <div class="d-flex align-items-center">
                        @auth
                        @if(Auth::user()->role === 'masyarakat' && Auth::user()->unreadNotifications->count() > 0)
                        <div class="dropdown me-3">
                            <button class="btn btn-primary position-relative"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ Auth::user()->unreadNotifications->count() }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="width: 380px; max-height: 400px; overflow-y: auto;">
                                <!-- Header -->
                                <div class="dropdown-header d-flex justify-content-between align-items-center py-3 px-3 border-bottom">
                                    <h6 class="mb-0 fw-bold">
                                        <i class="fas fa-bell text-primary me-2"></i>
                                        Notifikasi Terbaru
                                    </h6>
                                    <span class="badge bg-primary">{{ Auth::user()->unreadNotifications->count() }}</span>
                                </div>

                                <!-- Notification Items -->
                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                <div class="dropdown-item-text p-3 border-bottom notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                                <i class="fas fa-info-circle text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="fw-semibold mb-0 text-dark">Status Permohonan</h6>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="mb-0 text-muted small">
                                                {{ $notification->data['message'] ?? 'Notifikasi baru tersedia' }}
                                            </p>
                                            @if(isset($notification->data['nomor_permohonan']))
                                            <div class="mt-2">
                                                <span class="badge bg-light text-dark border">
                                                    {{ $notification->data['nomor_permohonan'] }}
                                                </span>
                                                @if(isset($notification->data['status']))
                                                <span class="badge 
                                @switch($notification->data['status'])
                                    @case('menunggu') bg-warning @break
                                    @case('diproses') bg-info @break  
                                    @case('selesai') bg-success @break
                                    @case('ditolak') bg-danger @break
                                    @default bg-secondary
                                @endswitch
                            ">
                                                    {{ ucfirst($notification->data['status']) }}
                                                </span>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="dropdown-item-text text-center py-4">
                                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                    <p class="mb-0 text-muted">Tidak ada notifikasi</p>
                                </div>
                                @endforelse

                                <!-- Footer -->
                                @if(Auth::user()->unreadNotifications->count() > 5)
                                <div class="dropdown-item-text text-center py-2 border-top">
                                    <small class="text-muted">
                                        Dan {{ Auth::user()->unreadNotifications->count() - 5 }} notifikasi lainnya...
                                    </small>
                                </div>
                                @endif

                                <!-- Mark all as read button -->
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                <div class="dropdown-item-text text-center py-2 border-top">
                                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                                        <i class="fas fa-check-double me-1"></i>
                                        Tandai Semua Dibaca
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endauth

                        <div class="badge bg-success">
                            <i class="fas fa-wifi me-1"></i>
                            Online
                        </div>
                    </div>

                    <style>
                        .notification-item {
                            transition: background-color 0.3s ease;
                        }

                        .notification-item:hover {
                            background-color: #f8f9fa !important;
                        }

                        .dropdown-menu {
                            border-radius: 12px;
                            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
                        }

                        .dropdown-header {
                            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                            border-radius: 12px 12px 0 0 !important;
                        }

                        /* Custom scrollbar for dropdown */
                        .dropdown-menu::-webkit-scrollbar {
                            width: 4px;
                        }

                        .dropdown-menu::-webkit-scrollbar-track {
                            background: #f1f1f1;
                            border-radius: 4px;
                        }

                        .dropdown-menu::-webkit-scrollbar-thumb {
                            background: #ccc;
                            border-radius: 4px;
                        }

                        .dropdown-menu::-webkit-scrollbar-thumb:hover {
                            background: #999;
                        }
                    </style>

                    <script>
                        function markAllAsRead() {
                            // AJAX call to mark all notifications as read
                            fetch('/notifications/mark-all-read', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                        'Content-Type': 'application/json',
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Reload page to update notification count
                                        window.location.reload();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                });
                        }

                        // Auto-refresh notification count every 30 seconds
                        setInterval(function() {
                            // You can add AJAX call here to refresh notification count without full page reload
                            // This is optional for better user experience
                        }, 30000);
                    </script>


                </div>
            </header>

            <!-- Page Content -->
            <main class="page-content">
                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show fade-in-up" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2 fs-4"></i>
                        <div>
                            <strong>Berhasil!</strong>
                            {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show fade-in-up" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                        <div>
                            <strong>Error!</strong>
                            {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Main Content -->
                <div class="fade-in-up">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Menu Toggle -->
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Auto-dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>