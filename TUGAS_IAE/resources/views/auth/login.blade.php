<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - COMMO-Pay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 48px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
        }
        .logo {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-align: center;
            margin-bottom: 32px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #7A32FF;
        }
        .error {
            color: #ff3d00;
            font-size: 12px;
            margin-top: 4px;
        }
        .btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: transform 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .register-link {
            text-align: center;
            margin-top: 24px;
            color: #666;
        }
        .register-link a {
            color: #7A32FF;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo">COMMO-Pay</div>
        <h2 style="text-align: center; margin-bottom: 32px; color: #333;">Welcome Back</h2>

        @if ($errors->any())
            <div style="background: #ffebee; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p class="error">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</body>
</html>