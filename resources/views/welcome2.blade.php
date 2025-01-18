<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OE & DRTU</title>
    <link rel="icon" href="{{asset('img/logo.png')}}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.css') }}">
    <style>
        body {
            background-image: url('{{asset('img/bg.jpg')}}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .content-wrapper {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px 20px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .btn {
            margin: 10px;
            font-size: 1.5rem;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-success:hover, .btn-danger:hover, .btn-warning:hover {
            transform: scale(1.05);
            box-shadow: 0px 8px 15px rgba(255, 255, 255, 0.2);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        /* Footer styling */
        footer {
            margin-top: 20px;
            font-size: 1.2rem;
        }

        footer a {
            color: lightblue;
            margin: 0 10px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer a:hover {
            color: wheat;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            .btn {
                font-size: 1.2rem;
                padding: 10px 15px;
            }
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 2rem;
            }

            .btn {
                font-size: 1rem;
                padding: 8px 12px;
            }

            .content-wrapper {
                padding: 30px 10px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content-wrapper">
        <h1 class="text-center">⚓ OE & DRTU ⚓</h1>

        <!-- Tampilkan tombol login dan register -->
        <div class="text-center">
            @guest
                <!-- Jika pengguna belum login, tampilkan tombol Login dan Register -->
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
            @else
                <!-- Jika pengguna sudah login, tampilkan tombol akses aplikasi -->
                <a href="{{ route('purchase_orders.index') }}" class="btn btn-success">OE</a>
                <a href="{{ route('drtus.index') }}" class="btn btn-warning">DRTU</a>
                <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
            @endguest
        </div>

        <!-- Footer dengan link Website -->
        <footer>
            <p>⨈</p>
            <a href="https://kjscompany.co.id/" target="_blank">Website</a>
        </footer>
    </div>
</div>
</body>
</html>
