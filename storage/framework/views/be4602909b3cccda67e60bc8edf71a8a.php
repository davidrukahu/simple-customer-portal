<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle'); ?>
Welcome back, <?php echo e(Auth::user()->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="<?php echo e(route('customer.domains.index')); ?>" class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['total_domains']); ?></h3>
            <p class="stats-label">Total Domains</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="<?php echo e(route('customer.domains.index')); ?>" class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['active_domains']); ?></h3>
            <p class="stats-label">Active Domains</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="<?php echo e(route('customer.invoices.index')); ?>" class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-receipt"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['total_invoices']); ?></h3>
            <p class="stats-label">Total Invoices</p>
        </a>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="<?php echo e(route('customer.invoices.index')); ?>?status=overdue" class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($stats['overdue_count']); ?></h3>
            <p class="stats-label">Overdue Invoices</p>
        </a>
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
                    <a href="<?php echo e(route('customer.domains.index')); ?>" class="quick-action-btn">
                        <i class="bi bi-globe"></i>
                        View Domains
                    </a>
                    <a href="<?php echo e(route('customer.services.index')); ?>" class="quick-action-btn">
                        <i class="bi bi-gear"></i>
                        View Services
                    </a>
                    <a href="<?php echo e(route('customer.invoices.index')); ?>" class="quick-action-btn">
                        <i class="bi bi-receipt"></i>
                        View Invoices
                    </a>
                    <a href="<?php echo e(route('customer.invoices.index')); ?>?status=overdue" class="quick-action-btn">
                        <i class="bi bi-exclamation-triangle"></i>
                        Overdue Invoices
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
                <h5 class="mb-0">Recent Domains</h5>
            </div>
            <div class="card-body">
                <?php if($recentDomains->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Registrar</th>
                                    <th>Expires</th>
                                    <th>Status</th>
                                    <th>Auto Renew</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('customer.domains.show', $domain)); ?>" class="text-decoration-none fw-medium">
                                                <?php echo e($domain->fqdn); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($domain->registrar ?: 'N/A'); ?></td>
                                        <td>
                                            <?php echo e($domain->expires_at->format('M d, Y')); ?>

                                            <?php if($domain->expires_at->isPast()): ?>
                                                <br><span class="badge bg-danger">Expired</span>
                                            <?php elseif($domain->days_until_expiry <= 30): ?>
                                                <br><span class="badge bg-warning"><?php echo e($domain->days_until_expiry); ?> days</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>
                                            <?php if($domain->auto_renew): ?>
                                                <span class="badge bg-success">Yes</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('customer.domains.index')); ?>" class="btn btn-outline-primary">View All Domains</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-globe text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No domains found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Invoices</h5>
            </div>
            <div class="card-body">
                <?php if($recentInvoices->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="<?php echo e(route('customer.invoices.show', $invoice)); ?>" class="text-decoration-none">
                                                <?php echo e($invoice->number); ?>

                                            </a>
                                        </h6>
                                        <p class="mb-1 text-muted"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></p>
                                        <small class="text-muted"><?php echo e($invoice->due_date->format('M d, Y')); ?></small>
                                    </div>
                                    <div class="text-end">
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
                                        <?php if($invoice->due_date->isPast() && $invoice->status !== 'paid'): ?>
                                            <br><span class="badge bg-danger mt-1">Overdue</span>
                                        <?php elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid'): ?>
                                            <br><span class="badge bg-warning mt-1">Due Soon</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('customer.invoices.index')); ?>" class="btn btn-outline-primary">View All Invoices</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No invoices found</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/dashboard.blade.php ENDPATH**/ ?>