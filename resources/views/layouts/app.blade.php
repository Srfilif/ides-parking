{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistema de Parqueadero')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
            font-size: 13px;
            font-weight: 400;
            line-height: 1.471;
            color: #73879C;
            background-color: #2A3F54;
        }

        .container-body {
            background: #EDEDED;
            padding: 0;
            margin: 0;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .left_col {
            background: #2A3F54;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 230px;
            z-index: 9999;
            padding: 0;
            box-shadow: rgba(0, 0, 0, 0.2) 0px 16px 24px 0px;
        }

        .sidebar-header {
            background: #34495E;
            padding: 20px;
            text-align: center;
            color: #ECF0F1;
        }

        .sidebar-header h3 {
            margin: 10px 0 0;
            font-size: 22px;
            font-weight: 600;
        }

        .sidebar-header p {
            margin: 0;
            font-size: 11px;
            font-weight: 300;
        }

        /* Navigation Styles */
        .nav.side-menu {
            margin-bottom: 0;
            margin-top: 20px;
        }

        .nav.side-menu li {
            position: relative;
            display: block;
            cursor: pointer;
        }

        .nav.side-menu li a {
            color: #E7E7E7;
            display: block;
            padding: 13px 15px 13px 15px;
            font-size: 14px;
            text-decoration: none;
            border-top: 1px solid #3E5266;
        }

        .nav.side-menu li a:hover {
            background: #425567;
            color: #fff;
        }

        .nav.side-menu li.active a {
            background: #1ABB9C;
            color: #fff;
        }

        .nav.side-menu li a i {
            font-size: 17px;
            margin-right: 6px;
            color: #BDC3C7;
            width: 20px;
            text-align: center;
        }

        .nav.side-menu .fa-chevron-down {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 14px;
            color: #BDC3C7;
        }

        /* Child menu styles */
        .nav.child_menu {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            background: #3E5266;
        }

        .nav.child_menu li a {
            color: #BDC3C7;
            font-size: 12px;
            padding: 7px 10px 7px 35px;
            border-top: 1px solid #425567;
        }

        .nav.child_menu li a:hover {
            background: #425567;
            color: #fff;
        }

        /* Top Navigation */
        .top_nav {
            display: block;
            margin-left: 230px;
            width: calc(100% - 230px);
            background: #EDEDED;
            border-bottom: 1px solid #D9DEE4;
            position: fixed;
            top: 0;
            z-index: 1030;
        }

        .nav_menu {
            background: white;
            border-bottom: 1px solid #D9DEE4;
            padding: 0;
        }

        .nav.toggle {
            background: #EDEDED;
            width: 48px;
            float: left;
            text-align: center;
            height: 48px;
            line-height: 48px;
            cursor: pointer;
        }

        .nav.toggle a {
            color: #73879C;
            text-decoration: none;
        }

        .navbar-nav {
            float: right;
            margin: 0;
            padding: 0;
        }

        .navbar-right {
            list-style: none;
            margin: 0;
            padding: 0 15px;
            display: flex;
            align-items: center;
            height: 48px;
        }

        .navbar-right li {
            display: inline-block;
            position: relative;
        }

        .navbar-right li.dropdown .dropdown-toggle {
            background: transparent;
            border: none;
            color: #73879C;
            padding: 10px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .user-profile img {
            width: 29px;
            height: 29px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .dropdown-menu {
            background: white;
            border: 1px solid #D9DEE4;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
        }

        .dropdown-item {
            color: #73879C;
            padding: 3px 20px;
            text-decoration: none;
            display: block;
        }

        .dropdown-item:hover {
            background: #f5f5f5;
            color: #262626;
        }

        /* Main content */
        .right_col {
            background: #EDEDED;
            margin-left: 230px;
            margin-top: 48px;
            padding: 10px 20px;
            min-height: calc(100vh - 48px);
        }

        /* Alerts */
        .alert {
            margin-bottom: 20px;
        }

        /* Parking specific styles */
        .parking-card {
            transition: transform 0.2s;
            background: white;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        .parking-card:hover {
            transform: translateY(-2px);
        }

        .space-occupied {
            background-color: #dc3545 !important;
            color: white;
        }

        .space-available {
            background-color: #198754 !important;
            color: white;
        }

        .plate-input {
            text-transform: uppercase;
        }

        .badge.bg-red {
            background-color: #E74C3C !important;
        }

        .info-number .badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 9px;
            padding: 2px 5px;
        }

        /* Notification styles */
        .msg_list {
            width: 300px;
            right: 0;
            left: auto;
            max-height: 300px;
            overflow-y: auto;
        }

        .msg_list li {
            border-bottom: 1px solid #eee;
            padding: 8px;
        }

        .msg_list .image img {
            border-radius: 2px 2px 2px 2px;
            height: 40px;
            margin-right: 10px;
            width: 40px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .left_col {
                margin-left: -230px;
            }

            .left_col.active {
                margin-left: 0;
            }

            .top_nav,
            .right_col {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body class="nav-md">
    <div class="container-body">
        <!-- Left Sidebar -->
        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">
                <div class="sidebar-header">
                    <div class="profile_pic">
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="..." class="img-circle profile_img" style="width: 70px; height: 70px;">
                    </div>
                    <h3>Sistema</h3>
                    <p>Parqueadero</p>
                </div>

                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                    <div class="menu_section">
                        <ul class="nav side-menu">
                            <li><a><i class="fa fa-home"></i> Administración </a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('admin.index') ?? '#' }}">Panel Principal</a></li>
                                    <li><a href="{{ route('admin.roles.index') ?? '#' }}">Roles</a></li>
                                    <li><a href="{{ route('marcas.index') ?? '#' }}">Marcas</a></li>

                                    <li><a href="{{ route('admin.usuarios.index') ?? '#' }}">Usuarios</a></li>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-edit"></i> Gestión Documentos</span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('documentos.index') ?? '#' }}">Documentos</a></li>
                                    <li><a href="{{ route('dashboard') ?? '#' }}">Estadísticas</a></li>
                                    <li><a href="{{ route('categorias.index') ?? '#' }}">Categorías</a></li>
                                    <li><a href="{{ route('tipo_documentos.index') ?? '#' }}">Tipo De Documentos</a></li>
                                </ul>
                            </li>
                            <li>
                                <a><i class="fa fa-car"></i> Gestión Parqueadero </a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('parking.dashboard') ?? '#' }}">Dashboard</a></li>
                                    <li><a href="{{ route('parking.history') ?? '#' }}">Historial</a></li>
                                    <li><a href="{{ route('vehicles.index') ?? '#' }}">Vehículos</a></li>
                                    <li><a href="{{ route('tipo_vehiculos.index') ?? '#' }}">Tipos de Vehículo</a></li>
                                </ul>
                            </li>
 
                            
                            <li>
                                <a><i class= "fa fa-minus-square"></i>Disponibilidad Parqueadero </span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('parking.spaces') ?? '#' }}">Espacios libres</a></li>
                                </ul>
                            </li>

                            <li>
                                <a><i class="fa fa-arrows-h"></i> Gestión Espacios</span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('zonas.index') ?? '#' }}">Zonas</a></li>
                                    <li><a href="{{ route('espacios.index') ?? '#' }}">Espacios</a></li>
                                    <li><a href="{{ route('compatibilidades.index') ?? '#' }}">Espacios Compartidos</a></li>
                                </ul>
                            </li>

                            <li>
                                <a><i class= "fa fa-usd"></i> Gestión tarifas </span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('tarifas.index') ?? '#' }}">Tarifas</a></li>
                                </ul>
                            </li>



                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Navigation -->
        <div class="top_nav">
            <div class="nav_menu">
                <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                </div>
                <nav class="nav navbar-nav">
                    <ul class="navbar-right">
                        @auth
                        <li class="nav-item dropdown">
                            <a class="dropdown-toggle info-number" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                @php $count = auth()->user()->unreadNotifications->count(); @endphp
                                @if($count > 0)
                                <span class="badge bg-red">{{ $count }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end list-unstyled msg_list" role="menu">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                <li class="nav-item">
                                    <a class="dropdown-item">
                                        <span class="image">
                                            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Usuario" />
                                        </span>
                                        <span>
                                            <span>{{ Auth::user()->name }}</span>
                                            <span class="time">{{ $notification->created_at->diffForHumans() }}</span>
                                        </span>
                                        <span class="message">
                                            {{ $notification->data['mensaje'] ?? 'Nueva notificación' }}
                                        </span>
                                    </a>
                                </li>
                                @empty
                                <li class="nav-item text-center text-muted py-2">Sin notificaciones</li>
                                @endforelse
                                <li class="nav-item text-center">
                                    <form method="POST" action="{{ route('notificaciones.leer') ?? '#' }}">
                                        @csrf
                                        <button class="btn btn-sm btn-primary w-100">Marcar como leídas</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endauth
                        <li class="nav-item dropdown" style="padding-left: 15px;">
                            <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Usuario">{{ Auth::user()->name ?? 'Usuario' }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript:;"> Perfil</a>
                                <a class="dropdown-item" href="javascript:;">
                                    <span class="badge bg-red float-end">50%</span>
                                    <span>Configuración</span>
                                </a>
                                <a class="dropdown-item" href="javascript:;">Ayuda</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out float-end"></i><span>Salir</span>
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="right_col" role="main">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar menu
            $('#menu_toggle').click(function() {
                if ($('body').hasClass('nav-md')) {
                    $('body').removeClass('nav-md').addClass('nav-sm');
                    $('.left_col').css('margin-left', '-230px');
                    $('.top_nav, .right_col').css('margin-left', '0');
                } else {
                    $('body').removeClass('nav-sm').addClass('nav-md');
                    $('.left_col').css('margin-left', '0');
                    $('.top_nav, .right_col').css('margin-left', '230px');
                }
            });

            // Handle sidebar submenu
            $('.side-menu li a').click(function(e) {
                var $this = $(this);
                var $submenu = $this.next('.child_menu');

                if ($submenu.length > 0) {
                    e.preventDefault();

                    // Close other submenus
                    $('.side-menu .child_menu').not($submenu).slideUp();
                    $('.side-menu li').not($this.parent()).removeClass('active');

                    // Toggle current submenu
                    $submenu.slideToggle();
                    $this.parent().toggleClass('active');
                }
            });

            // Set active menu item based on current URL
            var currentUrl = window.location.href;
            $('.side-menu a[href="' + currentUrl + '"]').parent().addClass('active');
            $('.child_menu a[href="' + currentUrl + '"]').closest('.side-menu > li').addClass('active');
            $('.child_menu a[href="' + currentUrl + '"]').closest('.child_menu').show();
        });
    </script>
    @yield('scripts')
</body>

</html>