<?php $__env->startSection('page-title', 'Domains'); ?>
<?php $__env->startSection('page-subtitle', 'Manage domain registrations and renewals'); ?>

<?php $__env->startSection('content'); ?>
<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.domains.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-globe me-2"></i>
                            Add Domain
                        </a>
                        <a href="<?php echo e(route('admin.domains.import-form')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-upload me-2"></i>
                            Import CSV
                        </a>
                        <a href="<?php echo e(route('admin.domains.export')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-download me-2"></i>
                            Export CSV
                        </a>
                    </div>
                    
                    <!-- Search and Filters -->
                    <form method="GET" class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Search domains..." value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <select name="status" class="form-select" style="width: 150px;">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="expired" <?php echo e(request('status') === 'expired' ? 'selected' : ''); ?>>Expired</option>
                            <option value="grace" <?php echo e(request('status') === 'grace' ? 'selected' : ''); ?>>Grace</option>
                            <option value="redemption" <?php echo e(request('status') === 'redemption' ? 'selected' : ''); ?>>Redemption</option>
                            <option value="transfer-pending" <?php echo e(request('status') === 'transfer-pending' ? 'selected' : ''); ?>>Transfer Pending</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Domains Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Domains (<?php echo e($domains->total()); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($domains->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Customer</th>
                                    <th>Registrar</th>
                                    <th>Registered</th>
                                    <th>Expires</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Auto Renew</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="fw-medium"><?php echo e($domain->fqdn); ?></div>
                                            <?php if($domain->service_notes): ?>
                                                <small class="text-muted"><?php echo e(Str::limit($domain->service_notes, 30)); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.customers.show', $domain->customer)); ?>" class="text-decoration-none">
                                                <?php echo e($domain->customer->name); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($domain->registrar ?: 'N/A'); ?></td>
                                        <td><?php echo e($domain->registered_at->format('M d, Y')); ?></td>
                                        <td>
                                            <?php echo e($domain->expires_at->format('M d, Y')); ?>

                                            <?php if($domain->expires_at->isPast()): ?>
                                                <br><span class="badge bg-danger">Expired</span>
                                            <?php elseif($domain->days_until_expiry <= 30): ?>
                                                <br><span class="badge bg-warning"><?php echo e($domain->days_until_expiry); ?> days</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($domain->currency); ?> <?php echo e(number_format($domain->price, 2)); ?></td>
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
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.domains.show', $domain)); ?>">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.domains.edit', $domain)); ?>">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="<?php echo e(route('admin.domains.destroy', $domain)); ?>" 
                                                              onsubmit="return confirm('Are you sure you want to delete this domain?')" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing <?php echo e($domains->firstItem()); ?> to <?php echo e($domains->lastItem()); ?> of <?php echo e($domains->total()); ?> results
                        </div>
                        <div>
                            <?php echo e($domains->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-globe text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No domains found</h4>
                        <p class="text-muted">Get started by adding your first domain.</p>
                        <a href="<?php echo e(route('admin.domains.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-globe me-2"></i>
                            Add First Domain
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/domains/index.blade.php ENDPATH**/ ?>