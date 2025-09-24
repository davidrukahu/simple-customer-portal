<?php $__env->startSection('page-title', 'Invoices'); ?>
<?php $__env->startSection('page-subtitle', 'Manage invoices and payments'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="<?php echo e(route('admin.invoices.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Create Invoice
                </a>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.invoices.index')); ?>" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="search" 
                                   value="<?php echo e(request('search')); ?>" placeholder="Search invoices or customers...">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">All Statuses</option>
                                <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="sent" <?php echo e(request('status') == 'sent' ? 'selected' : ''); ?>>Sent</option>
                                <option value="paid" <?php echo e(request('status') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                <option value="overdue" <?php echo e(request('status') == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                                <option value="void" <?php echo e(request('status') == 'void' ? 'selected' : ''); ?>>Void</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="sort">
                                <option value="created_at" <?php echo e(request('sort') == 'created_at' ? 'selected' : ''); ?>>Created Date</option>
                                <option value="number" <?php echo e(request('sort') == 'number' ? 'selected' : ''); ?>>Invoice Number</option>
                                <option value="due_date" <?php echo e(request('sort') == 'due_date' ? 'selected' : ''); ?>>Due Date</option>
                                <option value="total" <?php echo e(request('sort') == 'total' ? 'selected' : ''); ?>>Amount</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Invoices Table -->
            <div class="card">
                <div class="card-body">
                    <?php if($invoices->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
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
                                                <strong><?php echo e($invoice->number); ?></strong>
                                                <?php if($invoice->notes): ?>
                                                    <br><small class="text-muted"><?php echo e(Str::limit($invoice->notes, 30)); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('admin.customers.show', $invoice->customer)); ?>">
                                                    <?php echo e($invoice->customer->name); ?>

                                                </a>
                                                <?php if($invoice->customer->company): ?>
                                                    <br><small class="text-muted"><?php echo e($invoice->customer->company); ?></small>
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
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                    <a href="<?php echo e(route('admin.invoices.edit', $invoice)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                    <?php if($invoice->status !== 'paid'): ?>
                                                        <form method="POST" action="<?php echo e(route('admin.invoices.mark-paid', $invoice)); ?>" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                    onclick="return confirm('Mark this invoice as paid?')">
                                                                Mark Paid
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form method="POST" action="<?php echo e(route('admin.invoices.destroy', $invoice)); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            <?php echo e($invoices->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h4>No invoices found</h4>
                            <p class="text-muted">Start by creating your first invoice.</p>
                            <a href="<?php echo e(route('admin.invoices.create')); ?>" class="btn btn-primary">Create First Invoice</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/invoices/index.blade.php ENDPATH**/ ?>