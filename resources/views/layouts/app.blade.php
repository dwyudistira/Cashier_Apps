<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cahier Apps</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --navbar-bg: #ffffff;
            --text-color: #334155;
            --text-light: #64748b;
            --border-radius: 0.5rem;
            --transition: all 0.3s ease;
            --creative-tim-primary: #344767;
            --creative-tim-secondary: #7b809a;
            --creative-tim-accent: #e91e63;
            --creative-tim-light-bg: #f0f2f5;
            --creative-tim-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            background-color: var(--light-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem 0;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .sidebar-brand h4 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
        }

        .sidebar-brand i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
            color: var(--primary-color);
        }

        .sidebar-menu {
            padding: 0 1rem;
            height: calc(100vh - 100px);
            overflow-y: auto;
        }

        .sidebar-section {
            margin: 1.5rem 0;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--text-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .sidebar-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
            text-align: center;
            color: var(--text-light);
            transition: var(--transition);
        }

        .sidebar-link:hover {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .sidebar-link:hover i {
            color: var(--primary-color);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: white;
            box-shadow: 0 4px 6px rgba(99, 102, 241, 0.3);
        }

        .sidebar-link.active i {
            color: white;
        }

        /* Navbar Styles */
        .navbar-creative-tim {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            background-color: var(--navbar-bg);
            box-shadow: var(--creative-tim-shadow);
            padding: 0.5rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            right: 0;
            z-index: 100;
            transition: var(--transition);
            padding: 20px;
        }

        .navbar-left {
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            color: var(--creative-tim-primary);
            font-weight: 600;
            font-size: 1rem;
            margin-right: 1rem;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item {
            color: var(--creative-tim-secondary);
        }

        .breadcrumb-item.active {
            color: var(--creative-tim-primary);
            font-weight: 500;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            padding: 0 0.5rem;
            color: var(--creative-tim-secondary);
        }

        /* User Profile Styles */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            position: relative;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .user-name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--creative-tim-primary);
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
            min-width: 200px;
            z-index: 1000;
            display: none;
        }

        .user-profile:hover .user-dropdown {
            display: block;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            color: var(--text-color);
            text-decoration: none;
            display: block;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.5rem;
            color: var(--text-light);
        }

        .dropdown-divider {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            margin: 0.5rem 0;
        }

        /* Main Content */
        .main-content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            padding: 6rem 2rem 2rem;
            transition: var(--transition);
            min-height: 100vh;
        }

        .page-title {
            color: var(--creative-tim-primary);
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .navbar-creative-tim, .main-content {
                width: 100%;
                margin-left: 0;
            }
            
            .navbar-toggler {
                display: block;
            }
        }

        /* Toggle Button for Mobile */
        .navbar-toggler {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-color);
            cursor: pointer;
            margin-right: 1rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-cash-register"></i>
            <h4>Cashier Pro</h4>
        </div>
        
        <div class="sidebar-menu">
            <div class="sidebar-section">

                <!-- Dashboard -->
                @if(Auth::user()->role == "Administrator")
                    <a href="{{ route('admin.dashboard') }}"class="sidebar-link active">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                @elseif (Auth::user()->role == "Petugas")
                    <a href="{{ route('petugas.dashboard') }}"class="sidebar-link active">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                <!-- Pembelian -->
                @if(Auth::user()->role == "Administrator")
                    <a href="{{ route('admin.pembelian') }}" class="sidebar-link">
                        <i class="fas fa-table"></i>
                        <span>Pembelian</span>
                    </a>
                @elseif (Auth::user()->role == "Petugas")
                    <a href="{{ route('petugas.pembelian') }}" class="sidebar-link">
                        <i class="fas fa-table"></i>
                        <span>Pembelian</span>
                    </a>
                @endif

                <!-- Produk -->
                @if(Auth::user()->role == "Administrator")
                    <a href="{{ route('admin.product') }}" class="sidebar-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Produk</span>
                    </a>
                @elseif (Auth::user()->role == "Petugas")
                    <a href="{{route('petugas.product') }}" class="sidebar-link">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Produk</span>
                    </a>
                @endif

                <!-- User -->
                @if(Auth::user()->role == "Administrator")
                    <a href="{{ route('admin.user') }}" class="sidebar-link">
                        <i class="fas fa-chart-line"></i>
                        <span>User</span>
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="sidebar-link">
                    @csrf
                    <button type="submit" class="sidebar-link" style="border: none; background: none; padding: 0; width: 100%; text-align: left;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Navbar with User Profile -->
    <nav class="navbar-creative-tim">
        <!-- Toggler Button -->
        <button class="navbar-toggler">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Search Bar -->
        <div class="navbar-left">
            <div class="input-group">
                <div class="form-outline" data-mdb-input-init>
                    <input type="search" id="form1" class="form-control" placeholder="Search"/>
                </div>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="user-profile">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="user-avatar">
            <span class="user-name">{{ Auth::user()->name }} | {{ Auth::user()->role }}</span>
        </div>
    </nav>


    <!-- Main Content -->
    <main class="main-content">
        {{ $slot }}
    </main>
</body>
</html>