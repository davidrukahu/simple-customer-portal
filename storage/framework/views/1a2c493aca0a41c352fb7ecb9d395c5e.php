<?php $__env->startSection('page-title', 'Domains'); ?>
<?php $__env->startSection('page-subtitle', 'Manage and monitor your domain registrations'); ?>

<?php $__env->startSection('content'); ?>
<!-- Domain Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon primary">
                <i class="bi bi-globe"></i>
            </div>
            <h3 class="stats-number"><?php echo e($domains->where('status', 'active')->count()); ?></h3>
            <p class="stats-label">Active Domains</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($domains->where('expires_at', '<=', now()->addDays(30))->where('expires_at', '>', now())->count()); ?></h3>
            <p class="stats-label">Expiring Soon</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($domains->where('auto_renew', true)->count()); ?></h3>
            <p class="stats-label">Auto Renewal</p>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-icon danger">
                <i class="bi bi-x-circle"></i>
            </div>
            <h3 class="stats-number"><?php echo e($domains->where('status', 'expired')->count()); ?></h3>
            <p class="stats-label">Expired</p>
        </div>
    </div>
</div>

<!-- Search and Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search domains..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="expired" <?php echo e(request('status') === 'expired' ? 'selected' : ''); ?>>Expired</option>
                            <option value="grace" <?php echo e(request('status') === 'grace' ? 'selected' : ''); ?>>Grace</option>
                            <option value="redemption" <?php echo e(request('status') === 'redemption' ? 'selected' : ''); ?>>Redemption</option>
                            <option value="transfer-pending" <?php echo e(request('status') === 'transfer-pending' ? 'selected' : ''); ?>>Transfer Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date Added</option>
                            <option value="expires_at" <?php echo e(request('sort') === 'expires_at' ? 'selected' : ''); ?>>Expiry Date</option>
                            <option value="fqdn" <?php echo e(request('sort') === 'fqdn' ? 'selected' : ''); ?>>Domain Name</option>
                            <option value="status" <?php echo e(request('sort') === 'status' ? 'selected' : ''); ?>>Status</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="direction" class="form-label">Direction</label>
                        <select name="direction" id="direction" class="form-select">
                            <option value="desc" <?php echo e(request('direction') === 'desc' ? 'selected' : ''); ?>>Descending</option>
                            <option value="asc" <?php echo e(request('direction') === 'asc' ? 'selected' : ''); ?>>Ascending</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-2"></i>
                            Filter
                        </button>
                        <a href="<?php echo e(route('customer.domains.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Domains Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Domains (<?php echo e($domains->total()); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($domains->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Domain</th>
                                    <th>Registrar</th>
                                    <th>Registered</th>
                                    <th>Expires</th>
                                    <th>Term</th>
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
                                        <td><?php echo e($domain->term_years); ?> year(s)</td>
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
                                            <a href="<?php echo e(route('customer.domains.show', $domain)); ?>" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
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
                        <p class="text-muted">You don't have any domains registered yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/domains/index.blade.php ENDPATH**/ ?>