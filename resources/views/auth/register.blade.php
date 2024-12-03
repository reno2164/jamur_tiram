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
    <form method="POST" action="{{ route('register') }}">
        <h3>Register Form</h3>
        @csrf

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                    name="username" value="{{ old('name') }}" required autocomplete="name" autofocus>

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email') }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>  
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm"
                class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password">
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Register') }}
                </button>
            </div>
        </div>
        <!-- Register Link -->
        <div class="register-link">
            <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang!</a></p>
        </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    </div>
</body>
