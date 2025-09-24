<?php $__env->startSection('page-title', 'Customers'); ?>
<?php $__env->startSection('page-subtitle', 'Manage your customer accounts'); ?>

<?php $__env->startSection('content'); ?>
<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.customers.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Add Customer
                        </a>
                        <a href="<?php echo e(route('admin.customers.import-form')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-upload me-2"></i>
                            Import CSV
                        </a>
                        <a href="<?php echo e(route('admin.customers.export')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-download me-2"></i>
                            Export CSV
                        </a>
                    </div>
                    
                    <!-- Search -->
                    <form method="GET" class="d-flex">
                        <div class="input-group" style="width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="Search customers..." value="<?php echo e(request('search')); ?>">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customers Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Customers (<?php echo e($customers->total()); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($customers->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?php echo e($customer->name); ?></div>
                                                    <small class="text-muted">ID: <?php echo e($customer->id); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($customer->company ?: 'N/A'); ?></td>
                                        <td>
                                            <a href="mailto:<?php echo e($customer->email); ?>" class="text-decoration-none">
                                                <?php echo e($customer->email); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e($customer->phone ?: 'N/A'); ?></td>
                                        <td>
                                            <?php if($customer->is_active): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($customer->created_at->format('M d, Y')); ?></td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.customers.show', $customer)); ?>">
                                                            <i class="bi bi-eye me-2"></i>View
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo e(route('admin.customers.edit', $customer)); ?>">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="<?php echo e(route('admin.customers.toggle-status', $customer)); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="dropdown-item">
                                                                <?php if($customer->is_active): ?>
                                                                    <i class="bi bi-pause me-2"></i>Deactivate
                                                                <?php else: ?>
                                                                    <i class="bi bi-play me-2"></i>Activate
                                                                <?php endif; ?>
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="POST" action="<?php echo e(route('admin.customers.destroy', $customer)); ?>" 
                                                              onsubmit="return confirm('Are you sure you want to delete this customer?')" class="d-inline">
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
                            Showing <?php echo e($customers->firstItem()); ?> to <?php echo e($customers->lastItem()); ?> of <?php echo e($customers->total()); ?> results
                        </div>
                        <div>
                            <?php echo e($customers->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No customers found</h4>
                        <p class="text-muted">Get started by adding your first customer.</p>
                        <a href="<?php echo e(route('admin.customers.create')); ?>" class="btn btn-primary">
                            <i class="bi bi-person-plus me-2"></i>
                            Add First Customer
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/customers/index.blade.php ENDPATH**/ ?>