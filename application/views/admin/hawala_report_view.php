
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #046e0a;
            --danger-color: #af0d0d;
            --warning-color: #f8961e;
        }
        
        .card-value {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .positive {
            color: var(--success-color);
        }
        
        .negative {
            color: var(--danger-color);
        }
        
        /* Custom badge colors */
        .badge-success-custom {
            background-color: rgba(4, 110, 10, 0.1);
            color: var(--success-color);
        }
        
        .badge-danger-custom {
            background-color: rgba(175, 13, 13, 0.1);
            color: var(--danger-color);
        }
        
        .badge-primary-custom {
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
        }
        
        .income-row {
            background-color: rgba(4, 110, 10, 0.05) !important;
        }
        
        .expense-row {
            background-color: rgba(175, 13, 13, 0.05) !important;
        }
        
        .currency-display {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 20px;
            font-size: 14px;
            color: var(--primary-color);
            margin-left: 10px;
        }
        
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
        
        .rates-display {
            font-size: 12px;
            color: #666;
            white-space: nowrap;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rates-display:hover {
            white-space: normal;
            overflow: visible;
            position: absolute;
            background: white;
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        /* Modern gradient header */
        .gradient-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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

      
    </style>
</head>
<body class="hold-transition sidebar-mini">  
           <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><i class="fas fa-exchange-alt mr-2"></i>Hawala Transaction Report</h1>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-sm-right no-print">
                                <button class="btn btn-secondary mr-2" id="printTable">
                                    <i class="fas fa-print mr-1"></i> Print
                                </button>
                                <button class="btn btn-info mr-2" id="exportImage">
                                    <i class="fas fa-camera mr-1"></i> Save as Image
                                </button>
                                <button class="btn btn-danger" id="exportPDF">
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
                            <form method="POST">
                                <div class="row">

                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hawala_id"><i class="fas fa-user-tie mr-1"></i>Select Hawala</label>
                                        <select name="hawala_id" class="form-control" id="hawala_id">
                                            <option value="">All Hawalas</option>
                                            <?php foreach ($hawalas as $hawala) : ?>
                                                <option value="<?= $hawala->hawala_id ?>" <?= (isset($_POST['hawala_id']) && $_POST['hawala_id'] == $hawala->hawala_id) ? 'selected' : '' ?>>
                                                    <?= $hawala->mark ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                   </div>
                                   
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date"><i class="fas fa-calendar-alt mr-1"></i>Start Date</label>
                                            <input type="text" name="start_date" class="form-control flatpickr-input" id="start_date" placeholder="Select start date" value="<?= isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d') ?>" readonly>
                                        </div>
                                    </div>
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="end_date"><i class="fas fa-calendar-alt mr-1"></i>End Date</label>
                                            <input type="text" name="end_date" class="form-control flatpickr-input" id="end_date" placeholder="Select end date" value="<?= isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d') ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-chart-pie mr-1"></i> Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <?php if (isset($report_data)) : ?>
                    <div class="report-results printable-area">
                        <?php if (!empty($hawala_details)) : ?>
                            <div class="card card-info mb-4">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Hawala Details</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-4">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Hawala Name</span>
                                                    <span class="info-box-number"><?= $hawala_details->mark ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-4">
                                            <div class="info-box">
                                                <span class="info-box-icon" style="background-color: <?= $hawala_details->balance >= 0 ? 'var(--success-color)' : 'var(--danger-color)' ?>">
                                                    <i class="fas fa-calculator"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Net Balance</span>
                                                    <span class="info-box-number"><?= number_format($hawala_details->balance, 2) ?> <?= $hawala_details->currency ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-4">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-calendar"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Report Period</span>
                                                    <span class="info-box-number"><?= date('M d, Y', strtotime($start_date)) ?> to <?= date('M d, Y', strtotime($end_date)) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-4">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-money-bill-wave"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Currency</span>
                                                    <span class="info-box-number"><?= $hawala_details->currency ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="callout callout-info">
                                <h5><i class="fas fa-chart-pie mr-1"></i>Transaction Report</h5>
                                <p>Showing results from <?= date('M d, Y', strtotime($start_date)) ?> to <?= date('M d, Y', strtotime($end_date)) ?></p>
                            </div>
                        <?php endif; ?>
                           
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="individualTransactionsTable">
                                        <thead class="gradient-header">
                                            <tr>
                                                <th><i class="fas fa-list-ol mr-1"></i>NO</th>
                                                <th><i class="fas fa-calendar mr-1"></i>Date</th>
                                                <th><i class="fas fa-sticky-note mr-1"></i>Notes</th>
                                                <th><i class="fas fa-money-bill-wave mr-1"></i>Birr</th>
                                                <th><i class="fas fa-exchange-alt mr-1"></i>Rate</th>
                                                <th><i class="fas fa-arrow-up mr-1"></i>OUT</th>
                                                <th><i class="fas fa-arrow-down mr-1"></i>IN</th>
                                                <th><i class="fas fa-money-bill-wave mr-1"></i>BALANCE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $counter = 1;
                                            foreach ($report_data as $txn):
                                                $isIn = $txn['debit'] > 0;   // Money going IN to hawala (staff takes money)
                                                $isOut = $txn['credit'] > 0; // Money going OUT of hawala (staff gives money)

                                                $display = function($val) {
                                                    if ($val == 0 || $val == 1) return '-';
                                                    return is_int($val) ? $val : number_format($val, 2);
                                                };

                                                $balanceColor = $txn['balance'] >= 0 ? 'var(--success-color)' : 'var(--danger-color)';
                                            ?>
                                                <tr class="<?= $isOut ? 'out-row' : 'in-row' ?>">
                                                    <td class="text-muted"><?= $counter++ ?></td>
                                                    <td><?= date('M d, Y', strtotime($txn['date'])) ?></td>
                                                    <td><?= htmlspecialchars($txn['notes']) ?></td>
                                                    <td class="text-nowrap"><?= $display($txn['birr']) ?></td>
                                                    <td class="text-nowrap"><?= $display($txn['rate']) ?></td>
                                                    <td class="text-success font-monospace text-nowrap">  <!-- In (debit) is positive/green -->
                                                        <?= $isIn ? $txn['currency'].' '.$display($txn['debit']) : '-' ?>
                                                    </td>
                                                    <td class="text-danger font-monospace text-nowrap">   <!-- Out (credit) is negative/red -->
                                                        <?= $isOut ? $txn['currency'].' '.$display($txn['credit']) : '-' ?>
                                                    </td>
                                                    <td class="font-monospace text-nowrap" style="color: <?= $balanceColor ?>">
                                                        <?= $txn['currency'].' '.$display($txn['balance']) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
     
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <script>
        // Make jsPDF available globally
        window.jspdf = window.jspdf || jsPDF;
        
        $(document).ready(function() {
            // Initialize date pickers
            flatpickr("#start_date", {
                dateFormat: "Y-m-d",
                defaultDate: "<?= isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-d') ?>"
            });
            
            flatpickr("#end_date", {
                dateFormat: "Y-m-d",
                defaultDate: "<?= isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d') ?>"
            });
            
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
                    link.download = `Hawala_Report_${timestamp}.png`;
                    
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
                    pdf.save(`Hawala_Report_${timestamp}.pdf`);
                    
                    // Clean up
                    document.body.removeChild(reportClone);
                    document.body.removeChild(overlay);
                    
                } catch (err) {
                    console.error('Error generating PDF:', err);
                    document.body.removeChild(overlay);
                    alert('Error generating PDF. Please make sure jsPDF is loaded and try again.');
                }
            });
        });
    </script>
</body>