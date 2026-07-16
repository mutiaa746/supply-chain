<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - RiskIntel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
        .login-card .brand { text-align: center; margin-bottom: 30px; }
        .login-card .brand h2 { color: #1a1a2e; font-weight: bold; }
        .login-card .brand h2 span { color: #00d2ff; }
        .login-card .brand p { color: #888; font-size: 14px; }
        .login-card .form-control { border-radius: 10px; padding: 12px 16px; border: 2px solid #e8ecf1; }
        .login-card .form-control:focus { border-color: #e74a3b; box-shadow: 0 0 0 0.2rem rgba(231,74,59,0.25); }
        .login-card .btn-login {
            background: #e74a3b; color: white; border: none; border-radius: 10px; padding: 14px;
            width: 100%; font-weight: bold; font-size: 16px; transition: all 0.3s;
        }
        .login-card .btn-login:hover { background: #c0392b; transform: translateY(-2px); }
        .login-card .role-switch { text-align: center; margin-top: 20px; font-size: 14px; }
        .login-card .role-switch a { color: #00d2ff; text-decoration: none; font-weight: bold; }
        .admin-icon { font-size: 60px; color: #e74a3b; text-align: center; margin-bottom: 15px; }
        .badge-role { background: #e74a3b; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand">
            <div class="admin-icon">🔐</div>
            <h2>🚢 RiskIntel</h2>
            <p>Supply Chain Risk Intelligence</p>
            <span class="badge-role">🛡️ Admin Login</span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.admin.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter admin password" required>
            </div>
            <button type="submit" class="btn-login"><i class="fas fa-user-shield me-2"></i> Login as Admin</button>
        </form>

        <div class="role-switch">
            <p>Login as <a href="{{ route('login.user') }}">User</a> instead?</p>
        </div>
    </div>

</body>
</html>