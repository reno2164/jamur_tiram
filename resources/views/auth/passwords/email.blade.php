<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

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
            top: -80px;
            left: -80px;
        }

        .shape:last-child {
            background: linear-gradient(to right, #2c2c2c, #A0522D);
            bottom: -80px;
            right: -50px;
        }

        form {
            position: relative;
            background: rgba(245, 239, 223, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            color: #2c2c2c;
        }

        form h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        .alert {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            color: #fff;
            background: #28a745;
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
            border-radius: 5px;
            background-color: #F5F5DC;
            color: #2c2c2c;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            border-color: #D2B48C;
        }

        button {
            margin-top: 20px;
            width: 100%;
            background: linear-gradient(135deg, #2c2c2c, #A0522D);
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
        }
    </style>
</head>

<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    <form method="POST" action="{{ route('password.email') }}">
        <h3>Reset Password</h3>
        @csrf

        @if (session('status'))
            <div class="alert">
                {{ session('status') }}
            </div>
        @endif

        <label for="email">{{ __('Email Address') }}</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
            class="@error('email') is-invalid @enderror">

        @error('email')
            <span class="invalid-feedback" role="alert" style="color: red; font-size: 14px;">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <button type="submit">{{ __('Send Password Reset Link') }}</button>
    </form>
</body>

</html>
