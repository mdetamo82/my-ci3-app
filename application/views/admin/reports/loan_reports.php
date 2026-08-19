<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #e6e9ff;
        --secondary: #3a0ca3;
        --success: rgb(0, 133, 0);
        --danger: rgb(218, 3, 3);
        --warning: #f8961e;
        --dark: #1a1a1a;
        --light: #f8f9fa;
        --gray: #6c757d;
    }
    
    .card-value {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .positive {
        color: var(--success);
    }
    
    .negative {
        color: var(--danger);
    }
    
    .summary-card {
        transition: all 0.2s ease;
    }
    
    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .filter-group {
        margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
        .filter-row {
            display: flex;
            gap: 1rem;
        }
        
        .filter-group {
            flex: 1;
            margin-bottom: 0;
        }
    }
    
    /* Custom badge colors */
    .badge-success-custom {
        background-color: rgba(0, 133, 0, 0.1);
        color: var(--success);
    }
    
    .badge-danger-custom {
        background-color: rgba(218, 3, 3, 0.1);
        color: var(--danger);
    }
    
    .badge-primary-custom {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    /* Export functionality styles */
    .high-quality-export {
        position: fixed;
        top: -9999px;
        left: -9999px;
        width: 1200px;
        padding: 40px;
        background: white;
        z-index: 99999;
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        color: white;
        font-size: 1.2rem;
        flex-direction: column;
    }
    
    .spinner {
        border: 5px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top: 5px solid #fff;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Print styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .printable-area, .printable-area * {
            visibility: visible;
        }
        .printable-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 15px;
        }
        .no-print {
            display: none !important;
        }
    }
    
    /* Modern gradient header */
    .gradient-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
    }
    
    /* Icon wrapper */
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.2);
        margin-right: 8px;
    }

    /* Table styles */
    .table-responsive {
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }
    
    .table th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table td, .table th {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }
    
    .table-hover tbody tr:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0.075);
    }
</style>
</head>
<body class="hold-transition sidebar-mini">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><i class="fas fa-dollar-sign mr-2"></i>Loan Transaction Report</h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right no-print">
                                <button class="btn btn-secondary mr-2" id="printTable">
                                    <i class="fas fa-print mr-1"></i> Print
                                </button>
                                <button class="btn btn-info mr-2" id="exportImage">
                                    <i class="fas fa-camera mr-1"></i> Save as Image
                                </button>
                                <button class="btn btn-danger mr-2" id="exportPDF">
                                    <i class="fas fa-file-pdf mr-1"></i> Export as PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Filter Card -->
                    <div class="card card-primary no-print">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter mr-1"></i>Report Filters</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="" id="reportForm">
                                <div class="filter-row">
                                    <div class="filter-group">
                                        <label><i class="fas fa-user-tie mr-1"></i>Loan Account</label>
                                        <select name="loan_id" id="loanSelect" class="form-control">
                                            <option value="">All Loans</option>
                                            <?php foreach($loan_list as $loan): ?>
                                            <option value="<?php echo $loan->loan_id; ?>" <?php echo $selected_loan == $loan->loan_id ? 'selected' : ''; ?>>
                                                <?php echo $loan->name; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>"> 
                                    <div class="filter-group">
                                        <label><i class="fas fa-calendar-alt mr-1"></i>Date From</label>
                                        <input type="text" name="date_from" id="dateFrom" class="form-control flatpickr-input" value="<?php echo $date_from; ?>" placeholder="Select start date">
                                    </div>
                                    
                                    <div class="filter-group">
                                        <label><i class="fas fa-calendar-alt mr-1"></i>Date To</label>
                                        <input type="text" name="date_to" id="dateTo" class="form-control flatpickr-input" value="<?php echo $date_to; ?>" placeholder="Select end date">
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-end mt-3">
                                    <a href="<?php echo current_url(); ?>" class="btn btn-outline-secondary mr-2">
                                        <i class="fas fa-times mr-1"></i> Clear
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter mr-1"></i> Apply Filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Summary Cards -->
                    <div class="report-results printable-area">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box shadow summary-card">
                                <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Payments</span>
                                    <span class="info-box-number positive"><?php echo number_format($total_income, 2); ?> Birr</span>
                                    <span class="info-box-text"><?php echo count($transactions); ?> transactions</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box shadow summary-card">
                                <span class="info-box-icon bg-danger"><i class="fas fa-money-bill-wave"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Disbursements</span>
                                    <span class="info-box-number negative"><?php echo number_format($total_expense, 2); ?> Birr</span>
                                    <span class="info-box-text"><?php echo count($transactions); ?> transactions</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="info-box shadow summary-card">
                                <span class="info-box-icon" style="background-color: <?php echo $net_total >= 0 ? 'var(--success)' : 'var(--danger)'; ?>">
                                    <i class="fas fa-chart-line"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Net Balance</span>
                                    <span class="info-box-number" style="color: <?php echo $net_total >= 0 ? 'var(--success)' : 'var(--danger)'; ?>">
                                        <?php echo number_format($net_total, 2); ?> Birr
                                    </span>
                                    <span class="info-box-text"><?php echo date('M d, Y', strtotime($date_from)); ?> to <?php echo date('M d, Y', strtotime($date_to)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transactions Table -->
                    <div class="card">
                        <div class="card-body p-0">
                            <?php if(!empty($transactions)): ?>
                           
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th><i class="fas fa-calendar mr-1"></i>Date</th>
                                                <th><i class="fas fa-user-tie mr-1"></i>Loan Account</th>
                                                <th><i class="fas fa-sticky-note mr-1"></i>Description</th>
                                                <th><i class="fas fa-exchange-alt mr-1"></i>Type</th>
                                                <th><i class="fas fa-money-bill-wave mr-1"></i>Amount</th>
                                                <th><i class="fas fa-wallet mr-1"></i>Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($transactions as $t): ?>
                                            <tr>
                                                <td><?php echo date('M d, Y', strtotime($t->date)); ?></td>
                                                <td><?php echo $t->loan_name; ?></td>
                                                <td><?php echo $t->description; ?></td>
                                                <td>
                                                    <span class="badge <?php echo $t->type == 'Income' ? 'badge-success-custom' : 'badge-danger-custom'; ?>">
                                                        <?php echo $t->type; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo number_format($t->birr, 2); ?> Birr</td>
                                                <td><?php echo number_format($t->balance, 2); ?> Birr</td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h3>No transactions found</h3>
                                <p class="text-muted">Try adjusting your filters to see results</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
    
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        // Make jsPDF available globally
        window.jspdf = window.jspdf || jsPDF;

        // Initialize date pickers
        flatpickr("#dateFrom", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: "<?php echo $date_from; ?>"
        });
        
        flatpickr("#dateTo", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            defaultDate: "<?php echo $date_to; ?>"
        });

        // Form validation
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            if (dateFrom && dateTo && new Date(dateFrom) > new Date(dateTo)) {
                e.preventDefault();
                alert('End date must be after start date');
                document.getElementById('dateTo').focus();
            }
        });

        // Export function
        function exportTo(format) {
            const form = document.getElementById('reportForm');
            form.action = '<?php echo site_url("loan_transaction/export"); ?>/' + format;
            form.submit();
            form.action = ''; // Reset form action
        }

        // Print functionality
        document.getElementById('printTable').addEventListener('click', function() {
            window.print();
        });
        
        // Ultra HD Screenshot functionality with icon fixes
        document.getElementById('exportImage').addEventListener('click', async function() {
            // Show loading overlay
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <div class="spinner"></div>
                <div>Generating Ultra HD Image (Please wait)...</div>
            `;
            document.body.appendChild(overlay);
            
            try {
                // Create a clone of the report for perfect rendering
                const reportClone = document.querySelector('.report-results').cloneNode(true);
                reportClone.id = 'high-quality-export';
                reportClone.classList.add('high-quality-export');
                document.body.appendChild(reportClone);
                
                // Configuration for ultra HD capture
                const config = {
                    scale: 3, // 3x resolution
                    logging: true,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: reportClone.scrollWidth,
                    windowHeight: reportClone.scrollHeight + 100,
                    letterRendering: true,
                };
                
                // Generate the image
                const canvas = await html2canvas(reportClone, config);
                
                // Create download link
                const link = document.createElement('a');
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                link.download = `Loan_Report_${timestamp}.png`;
                
                // Convert to PNG with maximum quality
                canvas.toBlob(function(blob) {
                    link.href = URL.createObjectURL(blob);
                    link.click();
                    
                    // Clean up
                    setTimeout(() => {
                        URL.revokeObjectURL(link.href);
                        document.body.removeChild(reportClone);
                        document.body.removeChild(overlay);
                    }, 100);
                    
                }, 'image/png', 1.0); // Maximum quality
                
            } catch (err) {
                console.error('Error generating image:', err);
                document.body.removeChild(overlay);
                alert('Error generating image. Please try again in Chrome browser.');
            }
        });
        
        // PDF Export functionality
        document.getElementById('exportPDF').addEventListener('click', async function() {
            // Show loading overlay
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = `
                <div class="spinner"></div>
                <div>Generating PDF Document (Please wait)...</div>
            `;
            document.body.appendChild(overlay);
            
            try {
                // First create an image
                const reportClone = document.querySelector('.report-results').cloneNode(true);
                reportClone.id = 'pdf-export-clone';
                reportClone.classList.add('high-quality-export');
                document.body.appendChild(reportClone);
                
                // Force icon styles for PDF export
                const icons = reportClone.querySelectorAll('.icon-wrapper');
                icons.forEach(icon => {
                    icon.style.display = 'inline-flex';
                    icon.style.alignItems = 'center';
                    icon.style.justifyContent = 'center';
                    icon.style.width = '24px';
                    icon.style.height = '24px';
                    icon.style.borderRadius = '50%';
                    icon.style.backgroundColor = 'rgba(255,255,255,0.2)';
                    icon.style.marginRight = '8px';
                });
                
                const canvas = await html2canvas(reportClone, {
                    scale: 2,
                    logging: true,
                    useCORS: true,
                    letterRendering: true,
                    backgroundColor: '#ffffff'
                });
                
                // Create PDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png', 1.0);
                const imgWidth = 210; // A4 width in mm
                const pageHeight = 295; // A4 height in mm
                const imgHeight = canvas.height * imgWidth / canvas.width;
                let heightLeft = imgHeight;
                let position = 0;
                
                pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
                
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }
                
                // Download PDF
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                pdf.save(`Loan_Report_${timestamp}.pdf`);
                
                // Clean up
                document.body.removeChild(reportClone);
                document.body.removeChild(overlay);
                
            } catch (err) {
                console.error('Error generating PDF:', err);
                document.body.removeChild(overlay);
                alert('Error generating PDF. Please make sure jsPDF is loaded and try again.');
            }
        });

        // Initialize AdminLTE components
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>