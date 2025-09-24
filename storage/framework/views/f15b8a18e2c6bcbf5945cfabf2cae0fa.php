<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <a href="<?php echo e(route('customer.services.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Services
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Service Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Service Name</h6>
                                    <p class="text-primary h4"><?php echo e($service->name); ?></p>
                                    
                                    <h6>Billing Cycle</h6>
                                    <p>
                                        <?php
                                            $cycleColors = [
                                                'one_time' => 'secondary',
                                                'monthly' => 'primary',
                                                'yearly' => 'success'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($cycleColors[$service->billing_cycle] ?? 'secondary'); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $service->billing_cycle))); ?>

                                        </span>
                                    </p>
                                    
                                    <h6>Status</h6>
                                    <p>
                                        <?php
                                            $statusColors = [
                                                'active' => 'success',
                                                'paused' => 'warning',
                                                'cancelled' => 'danger'
                                            ];
                                        ?>
                                        <span class="badge bg-<?php echo e($statusColors[$service->status] ?? 'secondary'); ?>">
                                            <?php echo e(ucfirst($service->status)); ?>

                                        </span>
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Price</h6>
                                    <p class="text-success h4"><?php echo e($service->currency); ?> <?php echo e(number_format($service->price, 2)); ?></p>
                                    
                                    <h6>Next Invoice Date</h6>
                                    <p class="text-muted">
                                        <?php if($service->next_invoice_on): ?>
                                            <?php echo e($service->next_invoice_on->format('M d, Y')); ?>

                                            <?php if($service->next_invoice_on->isPast()): ?>
                                                <br><span class="badge bg-danger">Overdue</span>
                                            <?php elseif($service->days_until_next_invoice <= 7): ?>
                                                <br><span class="badge bg-warning">Due Soon</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Not scheduled</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <h6>Created</h6>
                                    <p class="text-muted"><?php echo e($service->created_at->format('M d, Y')); ?></p>
                                </div>
                            </div>
                            
                            <?php if($service->description): ?>
                                <hr>
                                <h6>Description</h6>
                                <div class="bg-light p-3 rounded">
                                    <p class="text-muted mb-0"><?php echo e($service->description); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Service Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary"><?php echo e($service->currency); ?></h4>
                                    <small class="text-muted">Currency</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success"><?php echo e(number_format($service->price, 0)); ?></h4>
                                    <small class="text-muted">Price</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('customer.services.index')); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> All Services
                                </a>
                                <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if($service->status === 'active' && $service->next_invoice_on && $service->days_until_next_invoice <= 7): ?>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Billing Notice</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Invoice Due Soon</strong><br>
                                    Your next invoice for this service is due on <?php echo e($service->next_invoice_on->format('M d, Y')); ?>.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($service->status === 'paused'): ?>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Service Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <i class="fas fa-pause"></i>
                                    <strong>Service Paused</strong><br>
                                    This service is currently paused. Contact support to reactivate.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($service->status === 'cancelled'): ?>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Service Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <i class="fas fa-times"></i>
                                    <strong>Service Cancelled</strong><br>
                                    This service has been cancelled.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/services/show.blade.php ENDPATH**/ ?>