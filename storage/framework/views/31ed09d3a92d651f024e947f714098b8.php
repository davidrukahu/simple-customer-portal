<?php $__env->startSection('title', 'Domain Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Domain Details</h1>
                <div>
                    <a href="<?php echo e(route('admin.domains.edit', $domain)); ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-edit"></i> Edit Domain
                    </a>
                    <a href="<?php echo e(route('admin.domains.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Domains
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Domain Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Domain Name</h6>
                                    <p class="text-muted"><?php echo e($domain->fqdn); ?></p>
                                    
                                    <h6>Customer</h6>
                                    <p>
                                        <a href="<?php echo e(route('admin.customers.show', $domain->customer)); ?>">
                                            <?php echo e($domain->customer->name); ?>

                                        </a>
                                        <?php if($domain->customer->company): ?>
                                            <br><small class="text-muted"><?php echo e($domain->customer->company); ?></small>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <h6>Registrar</h6>
                                    <p class="text-muted"><?php echo e($domain->registrar ?: 'Not specified'); ?></p>
                                    
                                    <h6>Status</h6>
                                    <p>
                                        <?php
                                            $statusColors = [
                                                'active' => 'success',
                                                'expired' => 'danger',
                                                'grace' => 'warning',
                                                'redemption' => 'danger',
                                                'transfer-pending' => 'info'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$domain->status] ?? 'secondary'); ?>">
                                            <?php echo e(ucfirst(str_replace('-', ' ', $domain->status))); ?>

                                        </span>
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Registration Date</h6>
                                    <p class="text-muted"><?php echo e($domain->registered_at->format('M d, Y')); ?></p>
                                    
                                    <h6>Expiry Date</h6>
                                    <p class="text-muted">
                                        <?php echo e($domain->expires_at->format('M d, Y')); ?>

                                        <?php if($domain->expires_at->isPast()): ?>
                                            <br><span class="badge bg-danger">Expired <?php echo e($domain->expires_at->diffForHumans()); ?></span>
                                        <?php elseif($domain->days_until_expiry <= 30): ?>
                                            <br><span class="badge bg-warning">Expires in <?php echo e($domain->days_until_expiry); ?> days</span>
                                        <?php else: ?>
                                            <br><span class="text-success">Expires in <?php echo e($domain->days_until_expiry); ?> days</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <h6>Term</h6>
                                    <p class="text-muted"><?php echo e($domain->term_years); ?> year(s)</p>
                                    
                                    <h6>Price</h6>
                                    <p class="text-muted"><?php echo e($domain->currency); ?> <?php echo e(number_format($domain->price, 2)); ?></p>
                                    
                                    <h6>Auto Renew</h6>
                                    <p>
                                        <?php if($domain->auto_renew): ?>
                                            <span class="badge bg-success">Yes</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <?php if($domain->service_notes): ?>
                                <hr>
                                <h6>Service Notes</h6>
                                <p class="text-muted"><?php echo e($domain->service_notes); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('admin.domains.edit', $domain)); ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Domain
                                </a>
                                <a href="<?php echo e(route('admin.invoices.create')); ?>?domain=<?php echo e($domain->id); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-file-invoice"></i> Create Invoice
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.domains.destroy', $domain)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger w-100" 
                                            onclick="return confirm('Are you sure you want to delete this domain?')">
                                        <i class="fas fa-trash"></i> Delete Domain
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Domain Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary"><?php echo e($domain->term_years); ?></h4>
                                    <small class="text-muted">Years</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo e($domain->currency); ?> <?php echo e(number_format($domain->price, 0)); ?></h4>
                                    <small class="text-muted">Price</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/domains/show.blade.php ENDPATH**/ ?>