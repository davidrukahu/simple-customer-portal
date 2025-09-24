<?php $__env->startSection('page-title', 'Services'); ?>
<?php $__env->startSection('page-subtitle', 'View your active services and subscriptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="text-muted">
                    <i class="bi bi-gear me-1"></i><?php echo e($services->total()); ?> services
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('customer.services.index')); ?>" class="row g-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Search services...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="paused" <?php echo e(request('status') == 'paused' ? 'selected' : ''); ?>>Paused</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Services Table -->
            <div class="card">
                <div class="card-body">
                    <?php if($services->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Billing Cycle</th>
                                        <th>Price</th>
                                        <th>Next Invoice</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo e($service->name); ?></strong>
                                                <?php if($service->description): ?>
                                                    <br><small class="text-muted"><?php echo e(Str::limit($service->description, 50)); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
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
                                            </td>
                                            <td><?php echo e($service->currency); ?> <?php echo e(number_format($service->price, 2)); ?></td>
                                            <td>
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
                                            </td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('customer.services.show', $service)); ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Details
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            <?php echo e($services->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                            <h4>No services found</h4>
                            <p class="text-muted">You don't have any services yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/services/index.blade.php ENDPATH**/ ?>