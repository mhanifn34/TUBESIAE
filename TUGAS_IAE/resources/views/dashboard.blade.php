<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Commora</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #FFFFFF;
            color: #000000;
            transition: all 0.3s ease;
        }

        body.dark-mode {
            background: #000000;
            color: #FFFFFF;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            padding: 16px 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.03);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .navbar {
            background: rgba(0, 0, 0, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #F5F5F5;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        body.dark-mode .theme-toggle {
            background: #1A1A1A;
        }

        .theme-toggle:hover {
            transform: scale(1.05);
            background: #7A32FF;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(122, 50, 255, 0.3);
        }

        /* Main Content */
        .main-content {
            padding: 32px 0;
        }

        /* Balance Card */
        .balance-card {
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            border-radius: 24px;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(122, 50, 255, 0.3);
            margin-bottom: 32px;
        }

        .balance-card::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .balance-label {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .balance-amount {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 24px;
        }

        .balance-actions {
            display: flex;
            gap: 16px;
        }

        .btn {
            padding: 14px 32px;
            border-radius: 16px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }

        .btn-primary {
            background: white;
            color: #7A32FF;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.3);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        /* Menu Grid */
        .section-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .menu-item {
            background: #F9F9F9;
            border-radius: 20px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        body.dark-mode .menu-item {
            background: #1A1A1A;
        }

        .menu-item:hover {
            transform: translateY(-4px);
            border-color: #7A32FF;
            box-shadow: 0 12px 32px rgba(122, 50, 255, 0.15);
        }

        .menu-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 12px;
            background: linear-gradient(135deg, #7A32FF 0%, #9B4AFF 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .menu-label {
            font-size: 13px;
            font-weight: 600;
        }

        /* Widgets Grid */
        .widgets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        .widget-card {
            background: #F9F9F9;
            border-radius: 20px;
            padding: 24px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        body.dark-mode .widget-card {
            background: #1A1A1A;
        }

        .widget-card:hover {
            border-color: #7A32FF;
            transform: translateY(-2px);
        }

        .widget-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .progress-bar {
            width: 100%;
            height: 12px;
            background: rgba(122, 50, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #7A32FF 0%, #9B4AFF 100%);
            border-radius: 8px;
            transition: width 0.3s ease;
        }

        /* Transaction List */
        .transaction-list {
            background: #F9F9F9;
            border-radius: 20px;
            padding: 24px;
        }

        body.dark-mode .transaction-list {
            background: #1A1A1A;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .transaction-item {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .transaction-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: rgba(122, 50, 255, 0.1);
        }

        .transaction-info h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .transaction-info p {
            font-size: 12px;
            opacity: 0.6;
        }

        .transaction-amount {
            font-weight: 700;
            font-size: 16px;
        }

        .amount-positive {
            color: #00C853;
        }

        .amount-negative {
            color: #FF3D00;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 440px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        body.dark-mode .modal-content {
            background: #1A1A1A;
        }

        .modal-header {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s;
        }

        body.dark-mode .form-group input {
            background: #000;
            border-color: #333;
            color: white;
        }

        .form-group input:focus {
            outline: none;
            border-color: #7A32FF;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-cancel {
            background: #e0e0e0;
            color: #333;
        }

        body.dark-mode .btn-cancel {
            background: #333;
            color: white;
        }

        @media (max-width: 768px) {
            .balance-amount {
                font-size: 36px;
            }
            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            .widgets-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="logo">COMMORA</div>
                <div class="nav-right">
                    <button class="theme-toggle" onclick="toggleTheme()">🌙</button>
                    <div class="profile-avatar">{{ $user->initials }}</div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="main-content">
            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-label">Total Balance</div>
                <div class="balance-amount" id="balance-display">${{ number_format($wallet->balance, 2) }}</div>
                <div class="balance-actions">
                    <button class="btn btn-primary" onclick="showTopUpModal()">💳 Top Up</button>
                    <button class="btn btn-secondary" onclick="showTransferModal()">📤 Send Money</button>
                </div>
            </div>

            <!-- Menu Section -->
            <div class="menu-section">
                <h2 class="section-title">Quick Actions</h2>
                <div class="menu-grid">
                    <div class="menu-item" onclick="showTopUpModal()">
                        <div class="menu-icon">💰</div>
                        <div class="menu-label">Top Up</div>
                    </div>
                    <div class="menu-item">
                        <div class="menu-icon">💳</div>
                        <div class="menu-label">Pay</div>
                    </div>
                    <div class="menu-item" onclick="showTransferModal()">
                        <div class="menu-icon">📤</div>
                        <div class="menu-label">Transfer</div>
                    </div>
                    <div class="menu-item" onclick="window.location.href='{{ route('transactions') }}'">
                        <div class="menu-icon">📊</div>
                        <div class="menu-label">History</div>
                    </div>
                    <div class="menu-item">
                        <div class="menu-icon">🎁</div>
                        <div class="menu-label">Rewards</div>
                    </div>
                    <div class="menu-item">
                        <div class="menu-icon">📄</div>
                        <div class="menu-label">Bills</div>
                    </div>
                    <div class="menu-item">
                        <div class="menu-icon">🔄</div>
                        <div class="menu-label">Subscription</div>
                    </div>
                    <div class="menu-item">
                        <div class="menu-icon">🏦</div>
                        <div class="menu-label">Bank</div>
                    </div>
                </div>
            </div>

            <!-- Widgets Grid -->
            <div class="widgets-grid">
                <div class="widget-card">
                    <div class="widget-title">💎 Points Level</div>
                    <h3 style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $wallet->member_level }} Member</h3>
                    <p style="opacity: 0.6; font-size: 14px;">{{ number_format($levelProgress['points_to_next']) }} points to {{ $levelProgress['next_level'] }}</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $levelProgress['progress_percentage'] }}%"></div>
                    </div>
                </div>

                <div class="widget-card">
                    <div class="widget-title">💸 Cashback Progress</div>
                    <h3 style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">${{ number_format($cashbackThisMonth ?? 0, 2) }}</h3>
                    <p style="opacity: 0.6; font-size: 14px;">Earned this month</p>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 65%"></div>
                    </div>
                </div>

                <div class="widget-card">
                    <div class="widget-title">📊 Your Points</div>
                    <h3 style="font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ number_format($wallet->points) }}</h3>
                    <p style="opacity: 0.6; font-size: 14px;">Total points earned</p>
                </div>
            </div>

            <!-- Transaction History -->
            <h2 class="section-title">Recent Transactions</h2>
            <div class="transaction-list">
                @forelse($transactions as $transaction)
                <div class="transaction-item">
                    <div class="transaction-left">
                        <div class="transaction-icon">{{ $transaction->icon }}</div>
                        <div class="transaction-info">
                            <h4>{{ $transaction->description }}</h4>
                            <p>{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="transaction-amount {{ $transaction->type == 'income' ? 'amount-positive' : 'amount-negative' }}">
                        {{ $transaction->type == 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                    </div>
                </div>
                @empty
                <p style="text-align: center; opacity: 0.6; padding: 32px;">No transactions yet</p>
                @endforelse
            </div>

            <!-- Logout Button -->
            <div style="margin-top: 32px; text-align: center;">
                <form method="POST" action="{{ route('logout') }}">
@csrf
<button type="submit" class="btn btn-primary">Logout</button>
</form>
</div>
</div>
</div>
<!-- Top Up Modal -->
<div class="modal" id="topUpModal">
    <div class="modal-content">
        <div class="modal-header">💳 Top Up Wallet</div>
        <form id="topUpForm">
            <div class="form-group">
                <label for="topup_amount">Amount ($)</label>
                <input type="number" id="topup_amount" name="amount" min="10" max="10000" step="0.01" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeTopUpModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Top Up</button>
            </div>
        </form>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal" id="transferModal">
    <div class="modal-content">
        <div class="modal-header">📤 Send Money</div>
        <form id="transferForm">
            <div class="form-group">
                <label for="recipient_email">Recipient Email</label>
                <input type="email" id="recipient_email" name="recipient_email" required>
            </div>
            <div class="form-group">
                <label for="transfer_amount">Amount ($)</label>
                <input type="number" id="transfer_amount" name="amount" min="1" step="0.01" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-cancel" onclick="closeTransferModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>

<script>
    // CSRF Token for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Load theme preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        document.querySelector('.theme-toggle').textContent = '☀️';
    }

    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const btn = document.querySelector('.theme-toggle');
        const isDark = document.body.classList.contains('dark-mode');
        btn.textContent = isDark ? '☀️' : '🌙';
        localStorage.setItem('darkMode', isDark);
    }

    // Modal Functions
    function showTopUpModal() {
        document.getElementById('topUpModal').classList.add('active');
    }

    function closeTopUpModal() {
        document.getElementById('topUpModal').classList.remove('active');
        document.getElementById('topUpForm').reset();
    }

    function showTransferModal() {
        document.getElementById('transferModal').classList.add('active');
    }

    function closeTransferModal() {
        document.getElementById('transferModal').classList.remove('active');
        document.getElementById('transferForm').reset();
    }

    // Top Up Form Submit
    document.getElementById('topUpForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const amount = document.getElementById('topup_amount').value;

        try {
            const response = await fetch('{{ route("wallet.topup") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ amount: parseFloat(amount) })
            });

            const data = await response.json();

            if (data.success) {
                alert('✅ ' + data.message);
                document.getElementById('balance-display').textContent = '$' + data.new_balance;
                closeTopUpModal();
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Error: ' + error.message);
        }
    });

    // Transfer Form Submit
    document.getElementById('transferForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const email = document.getElementById('recipient_email').value;
        const amount = document.getElementById('transfer_amount').value;

        try {
            const response = await fetch('{{ route("wallet.transfer") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    recipient_email: email,
                    amount: parseFloat(amount)
                })
            });

            const data = await response.json();

            if (data.success) {
                alert('✅ ' + data.message);
                document.getElementById('balance-display').textContent = '$' + data.new_balance;
                closeTransferModal();
                location.reload();
            } else {
                alert('❌ ' + data.message);
            }
        } catch (error) {
            alert('❌ Error: ' + error.message);
        }
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.classList.remove('active');
        }
    }
</script>