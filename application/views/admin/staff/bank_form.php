<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Add New Bank</h3>
    </div>

    <?php echo form_open('admin/staff/create_bank', ['id' => 'BankForm']); ?>
        <div class="card-body">
            <div class="row">
                <!-- Account Number -->
            
                    <label for="number">Account Number</label>
                    <div class="input-group form-group">
                        <div class="input-group-prepend"><div class="input-group-text"><i class="fas fa-tag"></i></div></div>
                        <input type="text" class="form-control" id="number" name="number">
                        <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staff_id) ?>">
                        </div>

                <!-- Currency -->
             
                    <label for="name">Currency</label>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-coins"></i></div>
                        </div>
                        <select class="form-control" id="name" name="name">
                            <option value="">-- Select Currency --</option>
                            <option value="CBE">Commercial Bank of Ethiopia</option>
                <option value="AWASH">Awash Bank</option>
                <option value="DASHEN">Dashen Bank</option>
                <option value="BOA">Bank of Abyssinia</option>
                <option value="COOP">Cooperative Bank of Oromia</option>
                <option value="NIB">Nib International Bank</option>
                <option value="HIBRET">Hibret Bank</option>
                <option value="ABAY">Abay Bank</option>
                <option value="Addis International Bank">Addis International Bank</option>
                <option value="Amhara Bank">Amhara Bank</option>
                <option value="Ahadu Bank">Ahadu Bank</option>
                <option value="Berhan International Bank">Berhan International Bank</option>
                <option value="Bunna International Bank">Bunna International Bank</option>
                <option value="Debub Global Bank">Debub Global Bank</option>
                <option value="Enat Bank">Enat Bank</option>
                <option value="Gadaa Bank">Gadaa Bank</option>
                <option value="Development Bank of Ethiopia">Development Bank of Ethiopia</option>
                <option value="Lion International Bank">Lion International Bank</option>
                <option value="Oromia International Bank">Oromia International Bank</option>
                <option value="Shabelle Bank">Shabelle Bank</option>
                <option value="Tsehay Bank">Tsehay Bank</option>
                <option value="Wegagen Bank">Wegagen Bank</option>
                <option value="Zemen Bank">Zemen Bank</option>
                <option value="ZamZam Bank">ZamZam Bank</option>
                <option value="Sidama International Bank">Sidama International Bank</option>
                <option value="Selam Bank">Selam Bank</option>
                <option value="Shabelle Bank">Shabelle Bank</option>
                <option value="Siinqee Bank">Siinqee Bank</option>
                <option value="Tsehay Bank">Tsehay Bank</option>
                <option value="Tir International Bank">Tir International Bank</option>
                <option value="Global Bank Ethiopia">Global Bank Ethiopia</option>
                <option value="Hijra Bank">Hijra Bank</option>
                <option value="Reality Bank">Reality Bank</option>
                <option value="National Bank of Ethiopia">National Bank of Ethiopia</option>
                <option value="Construction and Business Bank">Construction and Business Bank</option>
                <option value="Housing and Savings Bank">Housing and Savings Bank</option>
                        </select>
                    </div>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Bank</button>
            <a href="<?= site_url('admin/staff/view_staff/'. htmlspecialchars($staff_id)  ); ?>" class="btn btn-secondary">Cancel</a>
        </div>
    <?= form_close(); ?>
</div>


<!-- Scripts -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jquery-validation/additional-methods.min.js') ?>"></script>

<script>


$(function () {
    setupValidation('#BankForm', {
       
        number: {
            required: true,
            minlength: 4
        },
       
        name: {
            required: true
        }

    }, {
       
        number: {
            required: "Please enter account number",
            minlength: "Account Number must be at least 4 characters"
        },
        
        name: {
            required: "Please enter name"
        }

    });
});
</script>



