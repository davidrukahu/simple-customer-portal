<?php $__env->startSection('page-title', 'Invoices'); ?>
<?php $__env->startSection('page-subtitle', 'View and manage your invoices'); ?>

<?php $__env->startSection('content'); ?>
<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Search by invoice number..." value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="sent" <?php echo e(request('status') === 'sent' ? 'selected' : ''); ?>>Sent</option>
                            <option value="paid" <?php echo e(request('status') === 'paid' ? 'selected' : ''); ?>>Paid</option>
                            <option value="overdue" <?php echo e(request('status') === 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                            <option value="void" <?php echo e(request('status') === 'void' ? 'selected' : ''); ?>>Void</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sort" class="form-label">Sort By</label>
                        <select name="sort" id="sort" class="form-select">
                            <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date Created</option>
                            <option value="due_date" <?php echo e(request('sort') === 'due_date' ? 'selected' : ''); ?>>Due Date</option>
                            <option value="total" <?php echo e(request('sort') === 'total' ? 'selected' : ''); ?>>Amount</option>
                            <option value="number" <?php echo e(request('sort') === 'number' ? 'selected' : ''); ?>>Invoice Number</option>
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
                        <a href="<?php echo e(route('customer.invoices.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Invoices -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Your Invoices (<?php echo e($invoices->total()); ?>)</h5>
            </div>
            <div class="card-body">
                <?php if($invoices->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Issue Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <div class="fw-medium"><?php echo e($invoice->number); ?></div>
                                            <?php if($invoice->notes): ?>
                                                <small class="text-muted"><?php echo e(Str::limit($invoice->notes, 50)); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($invoice->issue_date->format('M d, Y')); ?></td>
                                        <td>
                                            <?php echo e($invoice->due_date->format('M d, Y')); ?>

                                            <?php if($invoice->due_date->isPast() && $invoice->status !== 'paid'): ?>
                                                <br><span class="badge bg-danger">Overdue</span>
                                            <?php elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid'): ?>
                                                <br><span class="badge bg-warning">Due Soon</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-medium"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></div>
                                            <?php if($invoice->status === 'paid'): ?>
                                                <small class="text-success">Paid</small>
                                            <?php elseif($invoice->status === 'overdue'): ?>
                                                <small class="text-danger">Overdue</small>
                                            <?php endif; ?>
                                        </td>
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
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo e(route('customer.invoices.show', $invoice)); ?>" class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('customer.invoices.download', $invoice)); ?>" class="btn btn-outline-success btn-sm">
                                                    <i class="bi bi-download"></i>
                                                </a>
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
                            Showing <?php echo e($invoices->firstItem()); ?> to <?php echo e($invoices->lastItem()); ?> of <?php echo e($invoices->total()); ?> results
                        </div>
                        <div>
                            <?php echo e($invoices->links()); ?>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">No invoices found</h4>
                        <p class="text-muted">You don't have any invoices yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/invoices/index.blade.php ENDPATH**/ ?>