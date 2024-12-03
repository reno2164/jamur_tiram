<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        /* Global Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2c2c2c, #A0522D);
            /* Warna cokelat tua dan cokelat medium */
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        /* Background Styling */
        .background {
            position: absolute;
            width: 430px;
            height: 520px;
        }

        .background .shape {
            width: 200px;
            height: 200px;
            position: absolute;
            border-radius: 50%;
        }

        .shape:first-child {
            background: linear-gradient(135deg, #C5A880, #E4CDA1);
            /* Gradien krem terang */
            top: -80px;
            left: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right, #2c2c2c, #A0522D);
            /* Gradien cokelat tua */
            bottom: -80px;
            right: -50px;
        }

        /* Form Styling */
        form {
            position: relative;
            background: rgba(245, 239, 223, 0.9);
            /* Warna krem/beige transparan */
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            color: #2c2c2c;
            /* Teks cokelat tua */
        }

        form h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-top: 20px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #A0522D;
            /* Warna border cokelat medium */
            border-radius: 5px;
            background-color: #F5F5DC;
            /* Warna latar beige */
            color: #2c2c2c;
            /* Teks cokelat tua */
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #D2B48C;
            /* Border krem terang saat fokus */
        }

        button {
            margin-top: 20px;
            width: 100%;
            background: linear-gradient(135deg, #2c2c2c, #A0522D);
            /* Gradien cokelat tua */
            color: #fff;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #A0522D, #2c2c2c);
            /* Gradien terbalik */
        }

        /* Register Link */
        .register-link {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
        }

        .register-link a {
            color: #2c2c2c;
            /* Warna teks cokelat tua */
            text-decoration: none;
            font-weight: 500;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        /* Media Query for Responsiveness */
        @media (max-width: 768px) {
            .background {
                display: none;
            }

            form {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="{{ route('login') }}">
        <h3>Login Form</h3>
        @csrf
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required autofocus>
        @error('email')
            <span class="error">{{ $message }}</span>
        @enderror

        <label for="password">Password</label>
        <div style="position: relative;">
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <i class="far fa-eye" id="togglePassword"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #A0522D;"></i>
        </div>
        @error('password')
            <span class="error">{{ $message }}</span>
        @enderror

        <div class="register-link">
            <a href="{{ route('password.request') }}" style="float: right; text-decoration: none;">Forgot Password?</a>
        </div>

        <button type="submit">Login</button>

        <!-- Register Link -->
        <div class="register-link">
            <p>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
        </div>
    </form>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>
