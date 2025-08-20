<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - ParkingAdmin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            height: 100vh;
        }

        /* Lado izquierdo con imagen de fondo */
        .image-side {
            flex: 1.2;
            position: relative;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.7), rgba(25, 135, 84, 0.7)),
                        url('https://bogota.gov.co/sites/default/files/styles/1050px/public/2022-12/parque.jpg') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .image-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 70%, rgba(13, 110, 253, 0.3), transparent 50%),
                        radial-gradient(circle at 70% 30%, rgba(25, 135, 84, 0.3), transparent 50%);
            animation: pulse 4s ease-in-out infinite alternate;
        }

        @keyframes pulse {
            0% { opacity: 0.5; }
            100% { opacity: 0.8; }
        }

        .parking-icon {
            font-size: 120px;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
            position: relative;
            z-index: 2;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .brand-content p {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            text-align: left;
        }

        .features-list li {
            margin: 10px 0;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }

        .features-list li i {
            margin-right: 15px;
            font-size: 1.2rem;
        }

        /* Lado derecho con formulario */
        .login-form {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            position: relative;
            overflow-y: auto;
        }

        .logo-container {
            text-align: center;
            padding: 30px 40px 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-bottom: 1px solid #dee2e6;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .logo-image {
            max-height: 80px;
            max-width: 300px;
            height: auto;
            width: auto;
            object-fit: contain;
        }

        /* Fallback si no hay imagen */
        .logo-fallback {
            display: none;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            background: linear-gradient(135deg, #0d6efd, #198754);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        }

        .logo-text h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #0d6efd, #198754);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-text p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #495057;
        }

        .form-title h3 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-title p {
            color: #6c757d;
            margin: 0;
        }

        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }

        .form-floating input {
            height: 58px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-floating input:focus {
            background-color: white;
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }

        .form-floating label {
            color: #6c757d;
            font-weight: 500;
        }

        .input-group-text {
            background-color: transparent;
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #0d6efd;
        }

        .password-input {
            border-left: none !important;
            border-radius: 0 12px 12px 0 !important;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0;
        }

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-login {
            width: 100%;
            height: 55px;
            background: linear-gradient(135deg, #0d6efd, #198754);
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
        }

        .forgot-password {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
            }
            
            .image-side {
                flex: 0.4;
                min-height: 300px;
            }
            
            .parking-icon {
                font-size: 80px;
            }
            
            .brand-content h1 {
                font-size: 2.2rem;
            }
            
            .features-list {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .form-container {
                padding: 20px;
            }
            
            .logo-container {
                padding: 20px;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            .logo-text h2 {
                font-size: 1.5rem;
            }
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            z-index: 1;
        }

        .floating-shapes::before,
        .floating-shapes::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: floatShapes 6s ease-in-out infinite;
        }

        .floating-shapes::before {
            width: 200px;
            height: 200px;
            top: 20%;
            left: -50px;
            animation-delay: 0s;
        }

        .floating-shapes::after {
            width: 150px;
            height: 150px;
            bottom: 10%;
            right: -30px;
            animation-delay: 3s;
        }

        @keyframes floatShapes {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Lado izquierdo con imagen de fondo -->
        <div class="image-side">
            <div class="floating-shapes"></div>
            <div class="brand-content">
                <div class="parking-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h1>ParkingAdmin</h1>
                <p>Sistema de Gestión Inteligente de Parqueaderos</p>
                
                <ul class="features-list">
                    <li><i class="fas fa-check-circle"></i> Control de entradas y salidas</li>
                    <li><i class="fas fa-chart-bar"></i> Reportes en tiempo real</li>
                    <li><i class="fas fa-mobile-alt"></i> Gestión móvil avanzada</li>
                    <li><i class="fas fa-shield-alt"></i> Seguridad garantizada</li>
                </ul>
            </div>
        </div>

        <!-- Lado derecho con formulario -->
        <div class="login-form">
            <!-- Logo horizontal -->
            <div class="logo-container">
                <div class="logo">
                    <!-- Logo PNG principal -->
                    <img src="https://png.pngtree.com/element_pic/00/16/09/2057e0eecf792fb.jpg" alt="ParkingAdmin Logo" class="logo-image" 
                         onerror="this.style.display='none'; document.querySelector('.logo-fallback').style.display='flex';">
                    
                    <!-- Fallback si no se encuentra la imagen -->
                    <div class="logo-fallback">
                        <div class="logo-icon">
                            <i class="fas fa-parking"></i>
                        </div>
                        <div class="logo-text">
                            <h2>ParkingAdmin</h2>
                            <p>Sistema de Gestión</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="form-container">
                <div class="form-wrapper">
                    <div class="form-title">
                        <h3>¡Bienvenido de vuelta!</h3>
                        <p>Ingresa tus credenciales para continuar</p>
                    </div>

                    @if (session('status'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
<div class="form-floating mb-4 position-relative">
    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
           name="email" value="{{ old('email') }}" placeholder="Correo electrónico" required autofocus>
    <label for="email">
        <i class="fas fa-envelope me-2"></i>Correo electrónico
    </label>
    @error('email')
    <div class="invalid-feedback d-block">
        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
    </div>
    @enderror
</div>

<!-- Password -->
<div class="form-floating mb-4 position-relative">
    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
           name="password" placeholder="Contraseña" required>
    <label for="password">
        <i class="fas fa-lock me-2"></i>Contraseña
    </label>
    @error('password')
    <div class="invalid-feedback d-block">
        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
    </div>
    @enderror
</div>


                        <!-- Remember Me y Forgot Password -->
                        <div class="remember-forgot">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Recuérdame
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-password">
                                ¿Olvidaste tu contraseña?
                            </a>
                            @endif
                        </div>

                        <!-- Botón Login -->
                        <button type="submit" class="btn btn-primary btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>