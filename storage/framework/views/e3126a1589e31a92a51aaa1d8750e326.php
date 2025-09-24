<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle'); ?>
Welcome back, <?php echo e(Auth::user()->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-people"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['total_customers']); ?></h3>
            <p class="stats-label">Total Customers</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['active_domains']); ?></h3>
            <p class="stats-label">Active Domains</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['outstanding_invoices']); ?></h3>
            <p class="stats-label">Outstanding Invoices</p>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['overdue_invoices']); ?></h3>
            <p class="stats-label">Overdue Invoices</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="<?php echo e(route('admin.customers.create')); ?>" class="quick-action-btn">
                        <i class="bi bi-person-plus"></i>
                        Add Customer
                    </a>
                    <a href="<?php echo e(route('admin.domains.create')); ?>" class="quick-action-btn">
                        <i class="bi bi-globe"></i>
                        Add Domain
                    </a>
                    <a href="<?php echo e(route('admin.services.create')); ?>" class="quick-action-btn">
                        <i class="bi bi-gear"></i>
                        Add Service
                    </a>
                    <a href="<?php echo e(route('admin.invoices.create')); ?>" class="quick-action-btn">
                        <i class="bi bi-receipt"></i>
                        Create Invoice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Invoices</h5>
            </div>
            <div class="card-body">
                <?php if($recentInvoices->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="text-decoration-none fw-medium">
                                                <?php echo e($invoice->number); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($invoice->customer->name); ?></td>
                                        <td><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></td>
                                        <td>
                                            <?php
                                                $statusColors = [
                                                    'draft' => 'secondary',
                                                    'sent' => 'primary',
                                                    'paid' => 'success',
                                                    'overdue' => 'danger',
                                                    'void' => 'dark'
                                                ];
                                            ?>
                                            <span class="badge bg-<?php echo e($statusColors[$invoice->status] ?? 'secondary'); ?>">
                                                <?php echo e(ucfirst($invoice->status)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php echo e($invoice->due_date->format('M d, Y')); ?>

                                            <?php if($invoice->due_date->isPast() && $invoice->status !== 'paid'): ?>
                                                <br><span class="badge bg-danger">Overdue</span>
                                            <?php elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid'): ?>
                                                <br><span class="badge bg-warning">Due Soon</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-outline-primary">View All Invoices</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No invoices found</p>
                        <a href="<?php echo e(route('admin.invoices.create')); ?>" class="btn btn-primary">Create First Invoice</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Expiring Domains</h5>
            </div>
            <div class="card-body">
                <?php if($expiringDomains->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $expiringDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?php echo e($domain->fqdn); ?></h6>
                                        <p class="mb-1 text-muted"><?php echo e($domain->customer->name); ?></p>
                                        <small class="text-muted"><?php echo e($domain->expires_at->format('M d, Y')); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <?php if($domain->expires_at->isPast()): ?>
                                            <span class="badge bg-danger">Expired</span>
                                        <?php elseif($domain->days_until_expiry <= 30): ?>
                                            <span class="badge bg-warning"><?php echo e($domain->days_until_expiry); ?> days</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('admin.domains.index')); ?>" class="btn btn-outline-primary">View All Domains</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-globe text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No expiring domains</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>