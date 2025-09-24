<?php $__env->startSection('page-title', 'Edit Invoice'); ?>
<?php $__env->startSection('page-subtitle', 'Modify invoice details and items'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Invoice <?php echo e($invoice->number); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.invoices.update', $invoice)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <!-- Customer Selection -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="customer_id" name="customer_id" required>
                                <option value="">Select a customer</option>
                                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : ''); ?>>
                                        <?php echo e($customer->name); ?>

                                        <?php if($customer->company): ?>
                                            (<?php echo e($customer->company); ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['customer_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="status" name="status" required>
                                <option value="draft" <?php echo e(old('status', $invoice->status) == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                <option value="sent" <?php echo e(old('status', $invoice->status) == 'sent' ? 'selected' : ''); ?>>Sent</option>
                                <option value="paid" <?php echo e(old('status', $invoice->status) == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                <option value="overdue" <?php echo e(old('status', $invoice->status) == 'overdue' ? 'selected' : ''); ?>>Overdue</option>
                                <option value="void" <?php echo e(old('status', $invoice->status) == 'void' ? 'selected' : ''); ?>>Void</option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Invoice Dates -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="issue_date" class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?php $__errorArgs = ['issue_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="issue_date" name="issue_date" value="<?php echo e(old('issue_date', $invoice->issue_date->format('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['issue_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="due_date" name="due_date" value="<?php echo e(old('due_date', $invoice->due_date->format('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Currency -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="currency" name="currency" required>
                                <option value="KES" <?php echo e(old('currency', $invoice->currency) == 'KES' ? 'selected' : ''); ?>>KES</option>
                                <option value="USD" <?php echo e(old('currency', $invoice->currency) == 'USD' ? 'selected' : ''); ?>>USD</option>
                                <option value="EUR" <?php echo e(old('currency', $invoice->currency) == 'EUR' ? 'selected' : ''); ?>>EUR</option>
                            </select>
                            <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['subtotal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="subtotal" name="subtotal" step="0.01" min="0" value="<?php echo e(old('subtotal', $invoice->subtotal)); ?>" required>
                            <?php $__errorArgs = ['subtotal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="tax_total" class="form-label">Tax Total</label>
                            <input type="number" class="form-control <?php $__errorArgs = ['tax_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="tax_total" name="tax_total" step="0.01" min="0" value="<?php echo e(old('tax_total', $invoice->tax_total)); ?>">
                            <?php $__errorArgs = ['tax_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-4">
                            <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="total" name="total" step="0.01" min="0" value="<?php echo e(old('total', $invoice->total)); ?>" required>
                            <?php $__errorArgs = ['total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="notes" name="notes" rows="3"><?php echo e(old('notes', $invoice->notes)); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-list me-1"></i>Back to List
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Invoice
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoice Items (Read-only for now) -->
        <div class="card">
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
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Invoice items cannot be edited here. To modify items, you would need to delete and recreate the invoice.
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Invoice Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Invoice Summary</h5>
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
                    <div class="col-6"><strong>Customer:</strong></div>
                    <div class="col-6"><?php echo e($invoice->customer->name); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Issue Date:</strong></div>
                    <div class="col-6"><?php echo e($invoice->issue_date->format('M d, Y')); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Due Date:</strong></div>
                    <div class="col-6"><?php echo e($invoice->due_date->format('M d, Y')); ?></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Total:</strong></div>
                    <div class="col-6"><strong><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></strong></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Invoice
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate total when tax changes
    document.getElementById('tax_total').addEventListener('input', function() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const taxTotal = parseFloat(this.value) || 0;
        const total = subtotal + taxTotal;
        document.getElementById('total').value = total.toFixed(2);
    });
    
    // Auto-calculate total when subtotal changes
    document.getElementById('subtotal').addEventListener('input', function() {
        const subtotal = parseFloat(this.value) || 0;
        const taxTotal = parseFloat(document.getElementById('tax_total').value) || 0;
        const total = subtotal + taxTotal;
        document.getElementById('total').value = total.toFixed(2);
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/invoices/edit.blade.php ENDPATH**/ ?>