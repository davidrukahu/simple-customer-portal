<?php $__env->startSection('title', 'Import Customers'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Import Customers from CSV</h1>
                <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Customers
                </a>
            </div>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upload CSV File</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.customers.import')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="mb-4">
                            <label for="csv_file" class="form-label">CSV File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="csv_file" name="csv_file" accept=".csv,.txt" required>
                            <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Maximum file size: 2MB. Accepted formats: .csv, .txt
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                            <ul class="mb-0">
                                <li>All customers will be created with a default password: <strong>password123</strong></li>
                                <li>Customers should change their password after first login</li>
                                <li>Duplicate emails will be skipped</li>
                                <li>Invalid rows will be reported after import</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Customers
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">CSV Format Requirements</h6>
                </div>
                <div class="card-body">
                    <p>Your CSV file must include the following columns in this exact order:</p>
                    <ol class="list-unstyled">
                        <li><strong>1. Name</strong> <span class="text-danger">*</span></li>
                        <li><strong>2. Email</strong> <span class="text-danger">*</span></li>
                        <li><strong>3. Company</strong></li>
                        <li><strong>4. Phone</strong></li>
                        <li><strong>5. Currency</strong></li>
                        <li><strong>6. Street</strong></li>
                        <li><strong>7. City</strong></li>
                        <li><strong>8. State</strong></li>
                        <li><strong>9. Postal Code</strong></li>
                        <li><strong>10. Country</strong></li>
                        <li><strong>11. Notes</strong></li>
                    </ol>
                    <small class="text-muted">* Required fields</small>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Sample CSV Template</h6>
                </div>
                <div class="card-body">
                    <p>Download a sample CSV template to ensure proper formatting:</p>
                    <a href="<?php echo e(route('admin.customers.sample-csv')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download"></i> Download Sample CSV
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.file-upload-area:hover {
    border-color: #6c757d;
    background-color: #e9ecef;
}

.file-upload-area.dragover {
    border-color: #0d6efd;
    background-color: #e7f1ff;
}
</style>

<script>
// Enhanced file upload with drag and drop
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('csv_file');
    const uploadArea = document.querySelector('.file-upload-area');
    
    if (uploadArea) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        uploadArea.addEventListener('drop', handleDrop, false);
        uploadArea.addEventListener('click', () => fileInput.click());
    }
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function highlight() {
        uploadArea.classList.add('dragover');
    }
    
    function unhighlight() {
        uploadArea.classList.remove('dragover');
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        
        if (files.length > 0) {
            document.querySelector('.file-name').textContent = files[0].name;
        }
    }
    
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            document.querySelector('.file-name').textContent = this.files[0].name;
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/customers/import.blade.php ENDPATH**/ ?>