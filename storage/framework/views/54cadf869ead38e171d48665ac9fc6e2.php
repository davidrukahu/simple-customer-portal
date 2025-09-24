<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end align-items-center mb-4">
                <div>
                    <a href="<?php echo e(route('customer.invoices.download', $invoice)); ?>" class="btn btn-outline-success me-2">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <a href="<?php echo e(route('customer.invoices.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Invoice Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Invoice Number</h6>
                                    <p class="text-muted"><?php echo e($invoice->number); ?></p>
                                    
                                    <h6>Issue Date</h6>
                                    <p class="text-muted"><?php echo e($invoice->issue_date->format('M d, Y')); ?></p>
                                    
                                    <h6>Due Date</h6>
                                    <p class="text-muted">
                                        <?php echo e($invoice->due_date->format('M d, Y')); ?>

                                        <?php if($invoice->due_date->isPast() && $invoice->status !== 'paid'): ?>
                                            <br><span class="badge bg-danger">Overdue</span>
                                        <?php elseif($invoice->days_until_due <= 7 && $invoice->status !== 'paid'): ?>
                                            <br><span class="badge bg-warning">Due Soon</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6>Status</h6>
                                    <p>
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
                                    </p>
                                    
                                    <h6>Total Amount</h6>
                                    <p class="text-primary h4"><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></p>
                                    
                                    <?php if($invoice->notes): ?>
                                        <h6>Notes</h6>
                                        <p class="text-muted"><?php echo e($invoice->notes); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Invoice Items</h5>
                        </div>
                        <div class="card-body">
                            <?php if($invoice->items->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($item->description); ?></td>
                                                    <td><?php echo e(number_format($item->qty, 2)); ?></td>
                                                    <td><?php echo e($invoice->currency); ?> <?php echo e(number_format($item->unit_price, 2)); ?></td>
                                                    <td><?php echo e($invoice->currency); ?> <?php echo e(number_format($item->line_total, 2)); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Subtotal:</th>
                                                <th><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->subtotal, 2)); ?></th>
                                            </tr>
                                            <?php if($invoice->tax_total > 0): ?>
                                                <tr>
                                                    <th colspan="3">Tax:</th>
                                                    <th><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->tax_total, 2)); ?></th>
                                                </tr>
                                            <?php endif; ?>
                                            <tr class="table-primary">
                                                <th colspan="3">Total:</th>
                                                <th><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No items found for this invoice.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <!-- Payment Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Payment Information</h5>
                        </div>
                        <div class="card-body">
                            <?php if($invoice->status === 'paid'): ?>
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i>
                                    <strong>Payment Received</strong><br>
                                    This invoice has been paid.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>Payment Pending</strong><br>
                                    This invoice is awaiting payment.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Payment History -->
                    <?php if($invoice->payments->count() > 0): ?>
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="mb-0">Payment History</h5>
                            </div>
                            <div class="card-body">
                                <?php $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong><?php echo e($payment->currency); ?> <?php echo e(number_format($payment->amount, 2)); ?></strong><br>
                                            <small class="text-muted"><?php echo e($payment->paid_on->format('M d, Y')); ?></small>
                                        </div>
                                        <span class="badge bg-success"><?php echo e(ucfirst($payment->method)); ?></span>
                                    </div>
                                    <?php if($payment->reference): ?>
                                        <small class="text-muted">Reference: <?php echo e($payment->reference); ?></small>
                                    <?php endif; ?>
                                    <?php if(!$loop->last): ?>
                                        <hr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('customer.invoices.download', $invoice)); ?>" class="btn btn-outline-success">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                                <a href="<?php echo e(route('customer.invoices.index')); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> All Invoices
                                </a>
                                <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.customer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/customer/invoices/show.blade.php ENDPATH**/ ?>