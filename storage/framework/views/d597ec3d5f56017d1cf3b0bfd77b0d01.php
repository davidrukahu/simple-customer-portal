<?php $__env->startSection('page-title', 'Edit Service'); ?>
<?php $__env->startSection('page-subtitle', 'Modify service details and billing information'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Service: <?php echo e($service->name); ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.services.update', $service)); ?>">
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
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id', $service->customer_id) == $customer->id ? 'selected' : ''); ?>>
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
                                <option value="active" <?php echo e(old('status', $service->status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="paused" <?php echo e(old('status', $service->status) == 'paused' ? 'selected' : ''); ?>>Paused</option>
                                <option value="cancelled" <?php echo e(old('status', $service->status) == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
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

                    <!-- Service Details -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="name" name="name" value="<?php echo e(old('name', $service->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
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
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="currency" name="currency" required>
                                <option value="KES" <?php echo e(old('currency', $service->currency) == 'KES' ? 'selected' : ''); ?>>KES</option>
                                <option value="USD" <?php echo e(old('currency', $service->currency) == 'USD' ? 'selected' : ''); ?>>USD</option>
                                <option value="EUR" <?php echo e(old('currency', $service->currency) == 'EUR' ? 'selected' : ''); ?>>EUR</option>
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

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="description" name="description" rows="3"><?php echo e(old('description', $service->description)); ?></textarea>
                        <?php $__errorArgs = ['description'];
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

                    <!-- Billing Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['billing_cycle'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="billing_cycle" name="billing_cycle" required>
                                <option value="one_time" <?php echo e(old('billing_cycle', $service->billing_cycle) == 'one_time' ? 'selected' : ''); ?>>One Time</option>
                                <option value="monthly" <?php echo e(old('billing_cycle', $service->billing_cycle) == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                                <option value="yearly" <?php echo e(old('billing_cycle', $service->billing_cycle) == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                            </select>
                            <?php $__errorArgs = ['billing_cycle'];
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
                            <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="price" name="price" step="0.01" min="0" value="<?php echo e(old('price', $service->price)); ?>" required>
                            <?php $__errorArgs = ['price'];
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

                    <!-- Next Invoice Date -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="next_invoice_on" class="form-label">Next Invoice Date</label>
                            <input type="date" class="form-control <?php $__errorArgs = ['next_invoice_on'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="next_invoice_on" name="next_invoice_on" 
                                   value="<?php echo e(old('next_invoice_on', $service->next_invoice_on ? $service->next_invoice_on->format('Y-m-d') : '')); ?>">
                            <?php $__errorArgs = ['next_invoice_on'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Leave empty for one-time services or if not scheduled yet.
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="<?php echo e(route('admin.services.show', $service)); ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancel
                            </a>
                            <a href="<?php echo e(route('admin.services.index')); ?>" class="btn btn-outline-secondary ms-2">
                                <i class="bi bi-list me-1"></i>Back to List
                            </a>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i>Update Service
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Service Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Current Service</h5>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-6"><strong>Name:</strong></div>
                    <div class="col-6"><?php echo e($service->name); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Status:</strong></div>
                    <div class="col-6">
                        <?php switch($service->status):
                            case ('active'): ?>
                                <span class="badge bg-success">Active</span>
                                <?php break; ?>
                            <?php case ('paused'): ?>
                                <span class="badge bg-warning">Paused</span>
                                <?php break; ?>
                            <?php case ('cancelled'): ?>
                                <span class="badge bg-danger">Cancelled</span>
                                <?php break; ?>
                        <?php endswitch; ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Customer:</strong></div>
                    <div class="col-6"><?php echo e($service->customer->name); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Billing:</strong></div>
                    <div class="col-6"><?php echo e(ucfirst(str_replace('_', ' ', $service->billing_cycle))); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-6"><strong>Price:</strong></div>
                    <div class="col-6"><?php echo e($service->currency); ?> <?php echo e(number_format($service->price, 2)); ?></div>
                </div>
                <?php if($service->next_invoice_on): ?>
                <div class="row mb-2">
                    <div class="col-6"><strong>Next Invoice:</strong></div>
                    <div class="col-6"><?php echo e($service->next_invoice_on->format('M d, Y')); ?></div>
                </div>
                <?php endif; ?>
                <hr>
                <div class="row">
                    <div class="col-6"><strong>Created:</strong></div>
                    <div class="col-6"><?php echo e($service->created_at->format('M d, Y')); ?></div>
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
                    <a href="<?php echo e(route('admin.services.show', $service)); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>View Service
                    </a>
                    <a href="<?php echo e(route('admin.customers.show', $service->customer)); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-person me-2"></i>View Customer
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-set next invoice date based on billing cycle
    document.getElementById('billing_cycle').addEventListener('change', function() {
        const billingCycle = this.value;
        const nextInvoiceInput = document.getElementById('next_invoice_on');
        
        if (billingCycle === 'one_time') {
            nextInvoiceInput.value = '';
            nextInvoiceInput.disabled = true;
        } else {
            nextInvoiceInput.disabled = false;
            if (!nextInvoiceInput.value) {
                const today = new Date();
                let nextDate = new Date(today);
                
                if (billingCycle === 'monthly') {
                    nextDate.setMonth(today.getMonth() + 1);
                } else if (billingCycle === 'yearly') {
                    nextDate.setFullYear(today.getFullYear() + 1);
                }
                
                nextInvoiceInput.value = nextDate.toISOString().split('T')[0];
            }
        }
    });
    
    // Initialize on page load
    document.getElementById('billing_cycle').dispatchEvent(new Event('change'));
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/services/edit.blade.php ENDPATH**/ ?>