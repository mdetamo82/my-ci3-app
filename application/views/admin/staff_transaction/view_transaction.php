<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --secondary-gradient: linear-gradient(135deg, #f72585 0%, #7209b7 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --card-shadow: 0 10px 20px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--card-shadow);
        }

        .content-header {
            background: var(--primary-gradient);
            padding: 2rem 0 4rem;
            position: relative;
            overflow: hidden;
        }

        .content-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
            opacity: 0.3;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .header-content {
            position: relative;
            z-index: 1;
            color: white;
        }

        .transaction-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .badge-success {
            background: var(--success-color);
            color: white;
        }

        .badge-danger {
            background: var(--danger-color);
            color: white;
        }

        .amount-display {
            font-size: 3rem;
            font-weight: 700;
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .amount-currency {
            font-size: 1.5rem;
            vertical-align: top;
            opacity: 0.8;
        }

        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 2rem;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow);
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .detail-item {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .detail-value {
            font-weight: 500;
        }

        .staff-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            border: 3px solid #fff;
            box-shadow: var(--card-shadow);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .transaction-title {
                font-size: 1.8rem;
            }

            .amount-display {
                font-size: 2.5rem;
            }
        }

        /* Print Styles */
        @media print {
            .content-header {
                background: none !important;
                color: #000;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
  <!-- Enhanced Content Header with Gradient -->
<section class="content-header bg-gradient-primary pb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-8">
                <h1 class="text-white mb-2">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> 
                    Transaction #<?= $transaction->id ?>
                </h1>
                <div class="d-flex align-items-center">
                    <span class="badge badge-<?= $transaction->type == 'Income' ? 'success' : 'danger' ?> mr-2">
                        <?= $transaction->type ?>
                    </span>
                    <span class="badge badge-light">
                        <?= ucfirst(str_replace('_', ' ', $transaction->transaction_type)) ?>
                    </span>
                </div>
            </div>
            <div class="col-sm-4 text-right">
                <a href="<?= site_url('admin/staff_transaction') ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <button onclick="window.print()" class="btn btn-light btn-sm ml-2">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Left Column - Transaction Details -->
            <div class="col-lg-8">
                <!-- Primary Transaction Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title">
                            <i class="fas fa-receipt text-primary mr-2"></i>
                            Transaction Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Amount Display -->
                        <div class="text-center mb-4">
                            <div class="amount-display" style="color: <?= $transaction->type == 'Income' ? '#28a745' : '#dc3545' ?>">
                                <span class="amount-currency"><?= $transaction->currency ?: 'ETB' ?></span>
                                <span class="amount-value"><?= number_format($transaction->amount, 2) ?></span>
                            </div>
                            <div class="text-muted">
                                Equivalent to <?= number_format($transaction->birr, 2) ?> Birr
                                <?php if($transaction->rate > 0): ?>
                                    <span class="text-xs">(Rate: <?= $transaction->rate ?>)</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Key Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-calendar-day mr-1 text-primary"></i> Transaction Date
                                    </h6>
                                    <p><?= date('F j, Y', strtotime($transaction->date)) ?></p>
                                </div>
                                
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-university mr-1 text-info"></i> Bank Name
                                    </h6>
                                    <p><?= !empty($transaction->bank_name) ? safe_html($transaction->bank_name) : '-' ?></p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-wallet mr-1 text-warning"></i> Bank Balance
                                    </h6>
                                    <p><?= isset($transaction->bank_balance) ? number_format($transaction->bank_balance, 2) . ' ብር' : '-' ?></p>
                                </div>
                                
                                <div class="detail-item mb-3">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-credit-card mr-1 text-info"></i> Account Number
                                    </h6>
                                    <p><?= !empty($transaction->bank_number) ? safe_html($transaction->bank_number) : '-' ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Description & Notes -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-align-left mr-1 text-primary"></i> Description
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        <?= !empty($transaction->description) ? safe_html($transaction->description) : '<span class="text-muted">No description provided</span>' ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <h6 class="text-muted mb-1">
                                        <i class="fas fa-sticky-note mr-1 text-warning"></i> Notes
                                    </h6>
                                    <div class="bg-light p-3 rounded">
                                        <?= !empty($transaction->notes) ? safe_html($transaction->notes) : '<span class="text-muted">No notes available</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Related Information -->
            <div class="col-lg-4">
                <!-- Staff Information Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title">
                            <i class="fas fa-user-tie text-primary mr-2"></i>
                            Staff Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($transaction->staff_name ?? 'Unknown') ?>&background=random&size=100" 
                                 class="img-thumbnail rounded-circle" alt="Staff Image">
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Name</span>
                                    <strong><?= safe_html($transaction->staff_name ?? 'Unknown') ?></strong>
                                </div>
                            </li>
                            <li class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Mobile</span>
                                    <span><?= $transaction->staff_mobile ?? '-' ?></span>
                                </div>
                            </li>
                            <li class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Address</span>
                                    <span class="text-right"><?= $transaction->staff_address ?? '-' ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Linked Entity Section (Hawala/Loan) -->
                <?php if (in_array($transaction->transaction_type, ['hawala', 'hawala_staff', 'loan'])): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h3 class="card-title">
                            <i class="<?= $transaction->transaction_type == 'loan' ? 'fas fa-hand-holding-usd' : 'fas fa-users' ?> text-primary mr-2"></i>
                            <?= ucfirst(str_replace('_', ' ', $transaction->transaction_type)) ?> Details
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($transaction->transaction_type, ['hawala', 'hawala_staff']) && !empty($transaction->hawala_name)): ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Name</span>
                                        <strong><?= safe_html($transaction->hawala_name) ?></strong>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Mobile</span>
                                        <span><?= $transaction->hawala_mobile ?? '-' ?></span>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Balance</span>
                                        <span><?= isset($transaction->hawala_balance) ? number_format($transaction->hawala_balance, 2) . ' ' . $transaction->hawala_currency : '-' ?></span>
                                    </div>
                                </li>
                            </ul>
                        <?php elseif ($transaction->transaction_type == 'loan' && !empty($transaction->loan_name)): ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Loan Holder</span>
                                        <strong><?= safe_html($transaction->loan_name) ?></strong>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Mobile</span>
                                        <span><?= $transaction->loan_mobile ?? '-' ?></span>
                                    </div>
                                </li>
                                <li class="list-group-item px-0 border-0">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Balance</span>
                                        <span><?= isset($transaction->loan_balance) ? number_format($transaction->loan_balance, 2) . ' Birr' : '-' ?></span>
                                    </div>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Audit Information -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-bottom">
                <h3 class="card-title">
                    <i class="fas fa-history text-primary mr-2"></i>
                    System Information
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-user-plus text-primary mr-1"></i> Created By
                            </h6>
                            <p>User #<?= $transaction->created_by ?> on <?= date('M d, Y H:i', strtotime($transaction->created_at)) ?></p>
                        </div>
                    </div>
                    <?php if($transaction->updated_by): ?>
                    <div class="col-md-6">
                        <div class="detail-item">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-user-edit text-warning mr-1"></i> Last Updated
                            </h6>
                            <p>User #<?= $transaction->updated_by ?> on <?= date('M d, Y H:i', strtotime($transaction->updated_at)) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

</body>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        offset: 100,
        once: true
    });

    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>
</html>