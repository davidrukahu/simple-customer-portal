<?php $__env->startSection('page-title', 'Invoice Details'); ?>
<?php $__env->startSection('page-subtitle', 'View and manage invoice information'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <!-- Invoice Details -->
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Invoice <?php echo e($invoice->number); ?></h5>
                    <div class="d-flex gap-2">
                        <?php if($invoice->status !== 'paid'): ?>
                            <form method="POST" action="<?php echo e(route('admin.invoices.mark-paid', $invoice)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle me-1"></i>Mark as Paid
                                </button>
                            </form>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.invoices.download', $invoice)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i>Download PDF
                        </a>
                        <a href="<?php echo e(route('admin.invoices.edit', $invoice)); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Invoice Status -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <?php switch($invoice->status):
                            case ('draft'): ?>
                                <span class="badge bg-secondary">Draft</span>
                                <?php break; ?>
                            <?php case ('sent'): ?>
                                <span class="badge bg-primary">Sent</span>
                                <?php break; ?>
                            <?php case ('paid'): ?>
                                <span class="badge bg-success">Paid</span>
                                <?php break; ?>
                            <?php case ('overdue'): ?>
                                <span class="badge bg-danger">Overdue</span>
                                <?php break; ?>
                            <?php case ('void'): ?>
                                <span class="badge bg-dark">Void</span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Total Amount:</strong>
                        <span class="h5 text-primary"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></span>
                    </div>
                </div>

                <!-- Invoice Dates -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Issue Date:</strong> <?php echo e($invoice->issue_date->format('M d, Y')); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Due Date:</strong> 
                        <?php echo e($invoice->due_date->format('M d, Y')); ?>

                        <?php if($invoice->status === 'overdue'): ?>
                            <span class="text-danger">(<?php echo e($invoice->days_overdue); ?> days overdue)</span>
                        <?php elseif($invoice->status !== 'paid' && $invoice->days_until_due <= 7): ?>
                            <span class="text-warning">(Due in <?php echo e($invoice->days_until_due); ?> days)</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Customer:</strong>
                        <a href="<?php echo e(route('admin.customers.show', $invoice->customer)); ?>" class="text-decoration-none">
                            <?php echo e($invoice->customer->name); ?>

                            <?php if($invoice->customer->company): ?>
                                (<?php echo e($invoice->customer->company); ?>)
                            <?php endif; ?>
                        </a>
                        <br>
                        <small class="text-muted"><?php echo e($invoice->customer->email); ?></small>
                    </div>
                </div>

                <!-- Notes -->
                <?php if($invoice->notes): ?>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Notes:</strong>
                        <p class="text-muted"><?php echo e($invoice->notes); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Invoice Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Invoice Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->description); ?></td>
                                <td class="text-center"><?php echo e(number_format($item->qty, 2)); ?></td>
                                <td class="text-end"><?php echo e($invoice->currency); ?> <?php echo e(number_format($item->unit_price, 2)); ?></td>
                                <td class="text-end"><?php echo e($invoice->currency); ?> <?php echo e(number_format($item->line_total, 2)); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">No items found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Subtotal:</th>
                                <th class="text-end"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->subtotal, 2)); ?></th>
                            </tr>
                            <?php if($invoice->tax_total > 0): ?>
                            <tr>
                                <th colspan="3">Tax:</th>
                                <th class="text-end"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->tax_total, 2)); ?></th>
                            </tr>
                            <?php endif; ?>
                            <tr class="table-primary">
                                <th colspan="3">Total:</th>
                                <th class="text-end"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payments -->
        <?php if($invoice->payments->count() > 0): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Payments</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Reference</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($payment->paid_on->format('M d, Y')); ?></td>
                                <td><?php echo e($payment->currency); ?> <?php echo e(number_format($payment->amount, 2)); ?></td>
                                <td>
                                    <span class="badge bg-info"><?php echo e(ucfirst($payment->method)); ?></span>
                                </td>
                                <td><?php echo e($payment->reference ?? '-'); ?></td>
                                <td><?php echo e($payment->notes ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.invoices.edit', $invoice)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-pencil me-2"></i>Edit Invoice
                    </a>
                    <a href="<?php echo e(route('admin.invoices.download', $invoice)); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>
                    <?php if($invoice->status !== 'paid'): ?>
                        <form method="POST" action="<?php echo e(route('admin.invoices.mark-paid', $invoice)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-2"></i>Mark as Paid
                            </button>
                        </form>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Back to Invoices
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Summary</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Invoice Number:</strong></div>
                    <div class="col-6"><?php echo e($invoice->number); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6">
                        <?php switch($invoice->status):
                            case ('draft'): ?>
                                <span class="badge bg-secondary">Draft</span>
                                <?php break; ?>
                            <?php case ('sent'): ?>
                                <span class="badge bg-primary">Sent</span>
                                <?php break; ?>
                            <?php case ('paid'): ?>
                                <span class="badge bg-success">Paid</span>
                                <?php break; ?>
                            <?php case ('overdue'): ?>
                                <span class="badge bg-danger">Overdue</span>
                                <?php break; ?>
                            <?php case ('void'): ?>
                                <span class="badge bg-dark">Void</span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Issue Date:</strong></div>
                    <div class="col-6"><?php echo e($invoice->issue_date->format('M d, Y')); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Due Date:</strong></div>
                    <div class="col-6"><?php echo e($invoice->due_date->format('M d, Y')); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Subtotal:</strong></div>
                    <div class="col-6"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->subtotal, 2)); ?></div>
                </div>
                <?php if($invoice->tax_total > 0): ?>
                <div class="row mb-2">
                    <div class="col-6"><strong>Tax:</strong></div>
                    <div class="col-6"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->tax_total, 2)); ?></div>
                </div>
                <?php endif; ?>
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Total:</strong></div>
                    <div class="col-6"><strong><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/invoices/show.blade.php ENDPATH**/ ?>