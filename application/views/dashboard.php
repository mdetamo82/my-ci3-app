<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($this->ion_auth->in_group('admin')): ?>
<style>
    /* Dark Mode Variables */
    .dark-mode {
        --primary: #3c8dbc;
        --secondary: #222d32;
        --success: #00a65a;
        --danger: #dd4b39;
        --warning: #f39c12;
        --info: #00c0ef;
        --light: #f5f5f5;
        --dark: #111;
        --white: #222d32;
        --gray: #b8c7ce;
        --card-bg: #1a2226;
        --card-header-bg: #1e282c;
        --text-color: #b8c7ce;
        --text-muted: #6c757d;
    }

    /* Light Mode Variables */
    :root {
        --primary: #3c8dbc;
       
        --success: #00a65a;
        --danger: #dd4b39;
        --warning: #f39c12;
        --info: #00c0ef;
        --light: #f5f5f5;
        --dark: #111;
        --white: #fff;
        --gray: #6c757d;
        --card-bg: #fff;
        --card-header-bg: #fff;
        --text-color: #495057;
        --text-muted: #6c757d;
    }

    /* Base Styles */
    .dashboard-container {
        font-family: 'Source Sans Pro', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        padding: 20px;
        color: var(--text-color);
    }

    /* KPI Cards - Enhanced for Dark Mode */
    .kpi-card {
        border-radius: 5px;
        padding: 15px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        background: var(--card-bg);
        height: 100%;
        border-left: 3px solid var(--primary);
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .kpi-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        float: left;
        margin-right: 12px;
        color: white;
    }

    .bg-success-light { background: rgba(0, 166, 90, 0.2); }
    .bg-danger-light { background: rgba(221, 75, 57, 0.2); }
    .bg-warning-light { background: rgba(243, 156, 18, 0.2); }
    .bg-info-light { background: rgba(0, 192, 239, 0.2); }
    .bg-primary-light { background: rgba(60, 141, 188, 0.2); }

    .kpi-content { overflow: hidden; }
    .kpi-title { 
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    .kpi-value { 
        font-size: 1.4rem; 
        margin-bottom: 5px; 
        font-weight: 700;
        color: var(--text-color);
    }
    .kpi-change { 
        font-size: 0.75rem; 
        font-weight: 600;
    }

    /* Cards - AdminLTE Style */
    .card {
        border: none;
        border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: var(--card-bg);
        color: var(--text-color);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        background-color: var(--card-header-bg);
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 3px 3px 0 0 !important;
        padding: 10px 15px;
        display: flex;
        align-items: center;
    }

    .card-title {
        font-weight: 600;
        color: var(--text-color);
        margin: 0;
        font-size: 1.1rem;
    }

    .card-icon {
        font-size: 1.1rem;
        margin-right: 10px;
        color: var(--primary);
    }

    /* Bank Cards - Professional Style */
    .bank-card {
        border-radius: 4px;
        padding: 12px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        background: var(--card-bg);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border-left: 3px solid var(--primary);
        cursor: pointer;
    }

    .bank-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .bank-logo {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(60, 141, 188, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .bank-logo img {
        max-width: 20px;
        max-height: 20px;
    }

    .bank-info {
        flex-grow: 1;
    }

    .bank-name {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 2px;
        font-size: 0.9rem;
    }

    .bank-balance {
        font-weight: 700;
        color: var(--success);
        font-size: 1rem;
    }

    /* Currency Cards */
    .currency-card {
        border-radius: 4px;
        padding: 12px;
        margin-bottom: 12px;
        background: var(--card-bg);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        cursor: pointer;
        border-left: 3px solid var(--info);
    }

    .currency-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .currency-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(0, 192, 239, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: var(--info);
        font-size: 1.1rem;
    }

    .currency-name {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 2px;
        font-size: 0.9rem;
    }

    .currency-count {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    /* Tables - AdminLTE Style */
    .table {
        color: var(--text-color);
        width: 100%;
    }

    .table th {
        background-color: var(--card-header-bg);
        color: var(--text-color);
        font-weight: 600;
        border-top: none;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table td {
        vertical-align: middle;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .badge-income {
        background-color: rgba(0, 166, 90, 0.2);
        color: var(--success);
    }

    .badge-expense {
        background-color: rgba(221, 75, 57, 0.2);
        color: var(--danger);
    }

    /* Chart Tabs */
    .chart-tabs .nav-link {
        color: var(--text-muted);
        font-weight: 500;
        border: none;
        padding: 6px 12px;
        font-size: 0.8rem;
    }

    .chart-tabs .nav-link.active {
        color: var(--primary);
        background: transparent;
        border-bottom: 2px solid var(--primary);
    }

    /* Timeline - Professional Style */
    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 2px;
        background: rgba(0, 0, 0, 0.1);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 15px;
    }

    .timeline-item:last-child { padding-bottom: 0; }

    .timeline-icon {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        font-size: 0.8rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .timeline-content {
        padding: 12px;
        background: var(--card-header-bg);
        border-radius: 4px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        font-size: 0.85rem;
    }

    .timeline-content::before {
        content: '';
        position: absolute;
        left: -10px;
        top: 12px;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 8px solid var(--card-header-bg);
    }

    /* Summary Cards - Professional Gradient */
    .summary-card {
        border-radius: 5px;
        color: white;
        padding: 15px;
        position: relative;
        overflow: hidden;
        min-height: 100px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: -40px;
        right: -40px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }

    .summary-card .icon {
        font-size: 2rem;
        opacity: 0.3;
        position: absolute;
        right: 15px;
        top: 15px;
    }

    .summary-card .title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
        opacity: 0.9;
        font-weight: 600;
    }

    .summary-card .value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary), #367fa9);
    }

    .bg-gradient-secondary {
        background: linear-gradient(135deg, var(--secondary), #1a2226);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, var(--info), #00a7d0);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, var(--success), #008d4c);
    }

    /* List Group */
    .list-group-item {
        background-color: var(--card-bg);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--text-color);
        padding: 0.75rem 1.25rem;
    }

    /* Form Controls */
    .form-control {
        background-color: var(--card-header-bg);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--text-color);
    }

    .form-control:focus {
        background-color: var(--card-header-bg);
        color: var(--text-color);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .timeline { padding-left: 30px; }
        .timeline-icon { left: -30px; width: 25px; height: 25px; }
        .kpi-icon { width: 35px; height: 35px; font-size: 1rem; }
        .summary-card .value { font-size: 1.3rem; }
    }

    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animated-card {
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    /* Scrollbar for dark mode */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: var(--card-header-bg);
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #367fa9;
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="font-weight-bold mb-0">Staff Financial Dashboard</h2>
                        <p class="text-muted mb-0">Overview of staff banking and financial activities</p>
                    </div>
                </div>
                <hr class="mt-2" style="border-color: rgba(0,0,0,0.1);">
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <!-- Total Balance -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card">
                <div class="summary-card bg-gradient-primary">
                    <i class="fas fa-wallet icon"></i>
                    <p class="title">Total Balance</p>
                    <h3 class="value"><?= number_format($total_balance, 2) ?> Birr</h3>
                </div>
            </div>

            <!-- Total Staff -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-1">
                <div class="summary-card bg-gradient-secondary">
                    <i class="fas fa-arrow-down icon"></i>
                    <p class="title">Today's Income</p>
                    <h3 class="value"><?= number_format(($today_summary->total_income ?? 0), 2) ?> Birr</h3>
                </div>
            </div>

            <!-- Bank Accounts -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-2">
                <div class="summary-card bg-gradient-info">
                    <i class="fas fa-arrow-up icon"></i>
                    <p class="title">Today's Expense</p>
                    <h3 class="value"><?= number_format(($today_summary->total_expense ?? 0), 2) ?> Birr</h3>
                </div>
            </div>

            <!-- Net Today -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-3">
                <div class="summary-card bg-gradient-success">
                    <i class="fas fa-exchange-alt icon"></i>
                    <p class="title">Today's Net</p>
                    <h3 class="value"><?= number_format(($today_summary->total_income ?? 0) - ($today_summary->total_expense ?? 0), 2) ?> Birr</h3>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="row">
            <!-- Net Profit/Loss Card -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card">
                <div class="kpi-card">
                    <div class="kpi-icon bg-danger-light">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="kpi-content">
                        <span class="kpi-title">Net This Month</span>
                        <h3 class="kpi-value">
                            <?= number_format(($monthly_comparison[0]->total_income ?? 0) - ($monthly_comparison[0]->total_expense ?? 0), 2) ?> Birr
                        </h3>
                        <div class="kpi-change <?= (isset($monthly_comparison[1]) && (($monthly_comparison[0]->total_income - $monthly_comparison[0]->total_expense) >= ($monthly_comparison[1]->total_income - $monthly_comparison[1]->total_expense))) ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= (isset($monthly_comparison[1]) && (($monthly_comparison[0]->total_income - $monthly_comparison[0]->total_expense) >= ($monthly_comparison[1]->total_income - $monthly_comparison[1]->total_expense))) ? 'up' : 'down' ?>"></i>
                            <?= isset($monthly_comparison[1]) ? abs(round($monthly_comparison[0]->net_change, 1)) : '0' ?>%
                            vs last month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Growth Card -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-1">
                <div class="kpi-card">
                    <div class="kpi-icon bg-success-light">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="kpi-content">
                        <span class="kpi-title">Income Growth</span>
                        <h3 class="kpi-value">
                            <?= number_format($monthly_comparison[0]->total_income ?? 0, 2) ?> Birr
                        </h3>
                        <div class="kpi-change <?= (isset($monthly_comparison[1]) && ($monthly_comparison[0]->total_income >= $monthly_comparison[1]->total_income)) ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= (isset($monthly_comparison[1]) && ($monthly_comparison[0]->total_income >= $monthly_comparison[1]->total_income)) ? 'up' : 'down' ?>"></i>
                            <?= isset($monthly_comparison[1]) ? abs(round($monthly_comparison[0]->income_change, 1)) : '0' ?>%
                            vs last month
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expense Growth Card -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-2">
                <div class="kpi-card">
                    <div class="kpi-icon bg-warning-light">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="kpi-content">
                        <span class="kpi-title">Expense Growth</span>
                        <h3 class="kpi-value">
                            <?= number_format($monthly_comparison[0]->total_expense ?? 0, 2) ?> Birr
                        </h3>
                        <div class="kpi-change <?= (isset($monthly_comparison[1]) && ($monthly_comparison[0]->total_expense <= $monthly_comparison[1]->total_expense)) ? 'text-success' : 'text-danger' ?>">
                            <i class="fas fa-arrow-<?= (isset($monthly_comparison[1]) && ($monthly_comparison[0]->total_expense <= $monthly_comparison[1]->total_expense)) ? 'down' : 'up' ?>"></i>
                            <?= isset($monthly_comparison[1]) ? abs(round($monthly_comparison[0]->expense_change, 1)) : '0' ?>%
                            vs last month
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Staff Productivity Card -->
            <div class="col-md-3 col-sm-6 mb-4 animated-card delay-3">
                <div class="kpi-card">
                    <div class="kpi-icon bg-info-light">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="kpi-content">
                        <span class="kpi-title">Avg. Staff Balance</span>
                        <h3 class="kpi-value">
                            <?= number_format($average_balance, 2) ?> Birr
                        </h3>
                        <div class="kpi-change">
                            <i class="fas fa-exchange-alt"></i>
                            <?= number_format($total_banks / $total_staff, 1) ?> accounts/staff
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Bank Summary -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-university card-icon"></i>Bank Accounts Summary</h3>
                        <small class="text-muted">Click to view details</small>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <?php foreach($bank_summary as $bank): ?>
                            <div class="col-md-6">
                                <div class="bank-card" onclick="showBankDetails('<?php echo $bank->bank_name; ?>')">
                                    <div class="bank-logo">
                                        <img src="<?php echo base_url('uploads/banks/' . strtolower($bank->bank_name) . '.png') ?>" alt="<?php echo $bank->bank_name; ?>">
                                    </div>
                                    <div class="bank-info">
                                        <div class="bank-name"><?php echo $bank->bank_name; ?></div>
                                        <div class="bank-balance"><?php echo number_format($bank->total_balance, 2); ?> Birr</div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-trophy card-icon text-warning"></i>Top 5 Staff by Balance</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach($top_staff as $staff): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge badge-primary mr-2">#<?= $staff->rank ?></span>
                                    <?= $staff->staff_name ?>
                                </div>
                                <span class="font-weight-bold text-success"><?= number_format($staff->balance, 2) ?> Birr</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hawala Summary -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-globe-africa card-icon"></i>Hawala Currency Summary</h3>
                        <small class="text-muted">Click to view members</small>
                    </div>
                    <div class="card-body p-2">
                        <div class="row">
                            <?php foreach ($currency_summary as $item): ?>
                            <div class="col-md-6">
                                <div class="currency-card" data-currency="<?= $item->currency ?>">
                                    <div class="d-flex align-items-center">
                                        <div class="currency-icon">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div>
                                            <div class="currency-name"><?= htmlspecialchars($item->currency) ?></div>
                                            <div class="currency-count"><?= $item->count ?> Hawalas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Bank Distribution -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-pie-chart card-icon text-info"></i>Bank Balance Distribution</h3>
                        <div class="card-tools">
                            <select class="form-control form-control-sm" id="bankChartType">
                                <option value="balance">By Balance</option>
                                <option value="accounts">By Account Count</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="bankDistributionChart" height="250"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Bank</th>
                                                <th class="text-right">Balance</th>
                                                <th class="text-right">%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bankDistributionTable">
                                            <!-- Filled by JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-line card-icon"></i>Financial Analytics</h3>
                        <ul class="nav chart-tabs float-right" id="chartTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="daily-tab" data-toggle="tab" href="#daily" role="tab">Daily</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab">Weekly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab">Monthly</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="chartTabContent">
                            <div class="tab-pane fade show active" id="daily" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <canvas id="dailyChart" height="250"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th colspan="2" class="text-center">Today's Summary</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Income</td>
                                                        <td class="text-success font-weight-bold text-right"><?= number_format($today_summary->total_income ?? 0, 2) ?> Birr</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Expense</td>
                                                        <td class="text-danger font-weight-bold text-right"><?= number_format($today_summary->total_expense ?? 0, 2) ?> Birr</td>
                                                    </tr>
                                                    <tr class="bg-light">
                                                        <td class="font-weight-bold">Net</td>
                                                        <td class="font-weight-bold text-primary text-right"><?= number_format(($today_summary->total_income ?? 0) - ($today_summary->total_expense ?? 0), 2) ?> Birr</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="weekly" role="tabpanel">
                                <canvas id="weeklyChart" height="250"></canvas>
                            </div>
                            <div class="tab-pane fade" id="monthly" role="tabpanel">
                                <canvas id="monthlyChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Transactions -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history card-icon"></i>Recent Activity Timeline</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <?php foreach($recent_activities as $activity): ?>
                            <div class="timeline-item">
                                <div class="timeline-icon <?= $activity->type === 'Income' ? 'bg-success' : 'bg-danger' ?>">
                                    <i class="fas fa-<?= $activity->type === 'Income' ? 'plus' : 'minus' ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <span class="font-weight-bold"><?= $activity->staff_name ?></span>
                                        <small class="text-muted"><?= $activity->time_ago ?></small>
                                    </div>
                                    <p class="mb-1"><?= $activity->notes ?></p>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge badge-light"><?= $activity->bank_name ?></span>
                                        <span class="font-weight-bold <?= $activity->type === 'Income' ? 'text-success' : 'text-danger' ?>">
                                            <?= number_format($activity->birr, 2) ?> Birr
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list card-icon"></i>Recent Transactions</h3>
                        <div class="card-tools">
                            <span class="badge badge-primary">Last 5</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table transactions-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Staff</th>
                                        <th>Type</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_transactions)) : ?>
                                        <?php foreach ($recent_transactions as $txn) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($txn->date) ?></td>
                                                <td><?= htmlspecialchars($txn->staff_name ?? '-') ?></td>
                                                <td>
                                                    <span class="badge <?= $txn->type === 'Income' ? 'badge-income' : 'badge-expense' ?>">
                                                        <?= htmlspecialchars($txn->type) ?>
                                                    </span>
                                                </td>
                                                <td class="text-right font-weight-bold <?= $txn->type === 'Income' ? 'text-success' : 'text-danger' ?>">
                                                    <?= number_format($txn->birr, 2) ?> Birr
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle mr-2"></i>No transactions found
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Largest Transactions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-money-bill-wave card-icon text-success"></i>Largest Transactions</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach($largest_transactions as $txn): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge <?= $txn->type === 'Income' ? 'badge-income' : 'badge-expense' ?> mr-2">
                                        <?= $txn->type ?>
                                    </span>
                                    <?= $txn->staff_name ?>
                                </div>
                                <span class="font-weight-bold <?= $txn->type === 'Income' ? 'text-success' : 'text-danger' ?>">
                                    <?= number_format($txn->birr, 2) ?> Birr
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Bank Details Modal -->
<div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalTitle">Bank Staff Balances</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Staff Name</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody id="bankDetailsBody"></tbody>
                        <tfoot id="bankDetailsFooter" class="font-weight-bold"></tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hawala Modal -->
<div class="modal fade" id="hawalaModal" tabindex="-1" role="dialog" aria-labelledby="hawalaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="hawalaModalLabel">Hawala Members</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Mark</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody id="hawalaList"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div class="text-muted">Total Members: <span id="totalMembers">0</span></div>
                    <div class="font-weight-bold">Total Balance: <span id="totalBalance" class="text-primary">0</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>

    // Check for saved dark mode preference
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }

    // Update charts for dark mode
    function updateChartsForDarkMode(isDarkMode) {
        const textColor = isDarkMode ? '#b8c7ce' : '#495057';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        
        // Update all charts
        [bankDistributionChart, dailyChart, weeklyChart, monthlyChart].forEach(chart => {
            if (chart) {
                chart.options.scales.x.grid.color = gridColor;
                chart.options.scales.y.grid.color = gridColor;
                chart.options.scales.x.ticks.color = textColor;
                chart.options.scales.y.ticks.color = textColor;
                chart.update();
            }
        });
    }

    // Bank Distribution Chart
    const bankDistributionChart = new Chart(
        document.getElementById('bankDistributionChart').getContext('2d'), 
        {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($bank_distribution, 'bank_name')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($bank_distribution, 'total_balance')) ?>,
                    backgroundColor: [
                        '#3c8dbc', '#00a65a', '#f39c12', '#dd4b39', '#605ca8',
                        '#00c0ef', '#f012be', '#39cccc', '#ff851b', '#01ff70'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            usePointStyle: true,
                            color: document.body.classList.contains('dark-mode') ? '#b8c7ce' : '#495057'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.raw;
                                const percentage = Math.round((value / total) * 100);
                                return `${context.label}: ${value.toLocaleString()} Birr (${percentage}%)`;
                            }
                        },
                        backgroundColor: document.body.classList.contains('dark-mode') ? '#1a2226' : '#fff',
                        titleColor: document.body.classList.contains('dark-mode') ? '#b8c7ce' : '#495057',
                        bodyColor: document.body.classList.contains('dark-mode') ? '#b8c7ce' : '#495057',
                        borderColor: 'rgba(0, 0, 0, 0.1)'
                    },
                    datalabels: {
                        display: false
                    }
                }
            }
        }
    );

    // Update bank distribution table
    function updateBankDistributionTable() {
        const tableBody = document.getElementById('bankDistributionTable');
        const banks = <?= json_encode($bank_distribution) ?>;
        const total = banks.reduce((sum, bank) => sum + parseFloat(bank.total_balance), 0);
        
        tableBody.innerHTML = banks.map(bank => {
            const percentage = (bank.total_balance / total * 100).toFixed(1);
            return `
                <tr>
                    <td>${bank.bank_name}</td>
                    <td class="text-right">${parseFloat(bank.total_balance).toLocaleString(undefined, {minimumFractionDigits: 2})}</td>
                    <td class="text-right">${percentage}%</td>
                </tr>
            `;
        }).join('');
    }
    updateBankDistributionTable();

    // Toggle between balance and accounts view
    document.getElementById('bankChartType').addEventListener('change', function() {
        if (this.value === 'accounts') {
            bankDistributionChart.data.datasets[0].data = <?= json_encode(array_column($bank_distribution, 'account_count')) ?>;
            bankDistributionChart.update();
        } else {
            bankDistributionChart.data.datasets[0].data = <?= json_encode(array_column($bank_distribution, 'total_balance')) ?>;
            bankDistributionChart.update();
        }
    });

    // Generate financial charts with dark mode support
    function generateChart(canvasId, labels, incomeData, expenseData, labelType) {
        const isDarkMode = document.body.classList.contains('dark-mode');
        const textColor = isDarkMode ? '#b8c7ce' : '#495057';
        const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';
        
        const ctx = document.getElementById(canvasId).getContext('2d');
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Income',
                        backgroundColor: 'rgba(0, 166, 90, 0.8)',
                        borderColor: 'rgba(0, 166, 90, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        data: incomeData
                    },
                    {
                        label: 'Expense',
                        backgroundColor: 'rgba(221, 75, 57, 0.8)',
                        borderColor: 'rgba(221, 75, 57, 1)',
                        borderWidth: 1,
                        borderRadius: 4,
                        data: expenseData
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20,
                            usePointStyle: true,
                            color: textColor
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDarkMode ? '#1a2226' : '#fff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        padding: 12,
                        cornerRadius: 4,
                        borderColor: 'rgba(0, 0, 0, 0.1)'
                    },
                    datalabels: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: textColor
                        },
                        title: {
                            display: true,
                            text: labelType,
                            color: textColor
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor
                        },
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        },
                        title: {
                            display: true,
                            text: 'Amount (Birr)',
                            color: textColor
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // Initialize financial charts
    const dailyChart = generateChart('dailyChart',
        <?= json_encode(array_column($daily_summary, 'date')) ?>,
        <?= json_encode(array_column($daily_summary, 'total_income')) ?>,
        <?= json_encode(array_column($daily_summary, 'total_expense')) ?>,
        'Date'
    );

    const weeklyChart = generateChart('weeklyChart',
        <?= json_encode(array_column($weekly_summary, 'week')) ?>,
        <?= json_encode(array_column($weekly_summary, 'total_income')) ?>,
        <?= json_encode(array_column($weekly_summary, 'total_expense')) ?>,
        'Week'
    );

    const monthlyChart = generateChart('monthlyChart',
        <?= json_encode(array_column($monthly_summary, 'month')) ?>,
        <?= json_encode(array_column($monthly_summary, 'total_income')) ?>,
        <?= json_encode(array_column($monthly_summary, 'total_expense')) ?>,
        'Month'
    );

    // Tab switching - destroy and recreate charts to prevent resizing issues
    $('#chartTabs a').on('shown.bs.tab', function(e) {
        const target = $(e.target).attr('href');
        
        if (target === '#daily' && dailyChart) {
            dailyChart.destroy();
            generateChart('dailyChart',
                <?= json_encode(array_column($daily_summary, 'date')) ?>,
                <?= json_encode(array_column($daily_summary, 'total_income')) ?>,
                <?= json_encode(array_column($daily_summary, 'total_expense')) ?>,
                'Date'
            );
        } else if (target === '#weekly' && weeklyChart) {
            weeklyChart.destroy();
            generateChart('weeklyChart',
                <?= json_encode(array_column($weekly_summary, 'week')) ?>,
                <?= json_encode(array_column($weekly_summary, 'total_income')) ?>,
                <?= json_encode(array_column($weekly_summary, 'total_expense')) ?>,
                'Week'
            );
        } else if (target === '#monthly' && monthlyChart) {
            monthlyChart.destroy();
            generateChart('monthlyChart',
                <?= json_encode(array_column($monthly_summary, 'month')) ?>,
                <?= json_encode(array_column($monthly_summary, 'total_income')) ?>,
                <?= json_encode(array_column($monthly_summary, 'total_expense')) ?>,
                'Month'
            );
        }
    });

    // Show bank details modal
    function showBankDetails(bankName) {
        const staffBanks = <?= json_encode($staff_banks) ?>;
        const filtered = staffBanks.filter(item => item.bank_name === bankName);
        const body = document.getElementById('bankDetailsBody');
        const footer = document.getElementById('bankDetailsFooter');
        body.innerHTML = '';

        if (filtered.length === 0) {
            body.innerHTML = `<tr><td colspan="2" class="text-center py-4 text-muted">No staff data found for this bank</td></tr>`;
            footer.innerHTML = '';
        } else {
            let totalBalance = 0;
            filtered.forEach(item => {
                const balance = parseFloat(item.balance);
                totalBalance += balance;
                const row = `
                    <tr>
                        <td>${item.staff_name}</td>
                        <td class="text-right">${balance.toLocaleString(undefined, {minimumFractionDigits: 2})} Birr</td>
                    </tr>`;
                body.innerHTML += row;
            });

            footer.innerHTML = `
                <tr>
                    <td>Total Balance</td>
                    <td class="text-right text-primary">${totalBalance.toLocaleString(undefined, {minimumFractionDigits: 2})} Birr</td>
                </tr>`;
        }

        document.getElementById('modalTitle').textContent = `${bankName} - Staff Balances`;
        $('#bankModal').modal('show');
    }

    // Hawala Modal
    const hawalas = <?= json_encode($this->Dashboard_model->get_all_hawalas()) ?>;

    document.querySelectorAll('.currency-card').forEach(box => {
        box.addEventListener('click', function () {
            const currency = this.getAttribute('data-currency');
            const filtered = hawalas.filter(h => h.currency === currency);

            let total = 0;
            let html = '';

            filtered.forEach(h => {
                const balance = parseFloat(h.balance);
                total += balance;
                html += `<tr>
                    <td>${h.name}</td>
                    <td>${h.mark}</td>
                    <td class="text-right font-weight-bold">${balance.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
                </tr>`;
            });

            document.getElementById('hawalaList').innerHTML = html || 
                `<tr><td colspan="3" class="text-center py-4 text-muted">No members found for this currency</td></tr>`;
            
            document.getElementById('totalBalance').innerText = total.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('totalMembers').innerText = filtered.length;
            document.getElementById('hawalaModalLabel').innerText = `${currency} Hawala Members`;
            $('#hawalaModal').modal('show');
        });
    });
    
    // Animation trigger
    document.addEventListener('DOMContentLoaded', function() {
        const animatedCards = document.querySelectorAll('.animated-card');
        animatedCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
        
        // Initialize dark mode charts if needed
        if (document.body.classList.contains('dark-mode')) {
            updateChartsForDarkMode(true);
        }
    });
</script>
<?php endif; ?>
