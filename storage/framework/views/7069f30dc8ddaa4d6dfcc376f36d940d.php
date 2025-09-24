<?php $__env->startSection('page-title', 'Import Domains'); ?>
<?php $__env->startSection('page-subtitle', 'Import domains from CSV file'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Import Domains from CSV</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.domains.import')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-4">
                        <label for="csv_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                        <div class="form-text">Select a CSV file containing domain data. Maximum file size: 2MB</div>
                        <?php $__errorArgs = ['csv_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload me-2"></i>
                            Import Domains
                        </button>
                        <a href="<?php echo e(route('admin.domains.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Domains
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">CSV Format</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Your CSV file should contain the following columns:</p>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Column</th>
                                <th>Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Customer Email</td>
                                <td><span class="badge bg-danger">Required</span></td>
                            </tr>
                            <tr>
                                <td>Domain</td>
                                <td><span class="badge bg-danger">Required</span></td>
                            </tr>
                            <tr>
                                <td>Expiry Date</td>
                                <td><span class="badge bg-danger">Required</span></td>
                            </tr>
                            <tr>
                                <td>Registrar</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Registered Date</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Term Years</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Currency</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Auto Renew</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td><span class="badge bg-secondary">Optional</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <a href="<?php echo e(route('admin.domains.sample-csv')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-download me-2"></i>
                        Download Sample CSV
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Import Notes</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Customer must exist in the system
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Dates should be in YYYY-MM-DD format
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Duplicate domains will be skipped
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Auto Renew should be "Yes" or "No"
                    </li>
                    <li>
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Status should be: active, expired, grace, redemption, transfer-pending
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if(session('import_errors')): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="alert alert-warning">
            <h6 class="alert-heading">Import Errors</h6>
            <ul class="mb-0">
                <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/domains/import.blade.php ENDPATH**/ ?>