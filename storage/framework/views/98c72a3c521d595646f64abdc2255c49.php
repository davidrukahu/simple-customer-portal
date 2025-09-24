<?php $__env->startSection('page-title', 'Create Invoice'); ?>
<?php $__env->startSection('page-subtitle', 'Create a new invoice for a customer'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Invoice Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.invoices.store')); ?>">
                    <?php echo csrf_field(); ?>
                    
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
                                    <option value="<?php echo e($customer->id); ?>" <?php echo e(old('customer_id') == $customer->id ? 'selected' : ''); ?>>
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
                            <label for="currency" class="form-label">Currency <span class="text-danger">*</span></label>
                            <select class="form-select <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="currency" name="currency" required>
                                <option value="KES" <?php echo e(old('currency', 'KES') == 'KES' ? 'selected' : ''); ?>>KES</option>
                                <option value="USD" <?php echo e(old('currency') == 'USD' ? 'selected' : ''); ?>>USD</option>
                                <option value="EUR" <?php echo e(old('currency') == 'EUR' ? 'selected' : ''); ?>>EUR</option>
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
                                   id="issue_date" name="issue_date" value="<?php echo e(old('issue_date', date('Y-m-d'))); ?>" required>
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
                                   id="due_date" name="due_date" value="<?php echo e(old('due_date', date('Y-m-d', strtotime('+30 days')))); ?>" required>
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

                    <!-- Invoice Items -->
                    <div class="mb-3">
                        <label class="form-label">Invoice Items <span class="text-danger">*</span></label>
                        <div id="invoice-items">
                            <div class="invoice-item row mb-2">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="items[0][description]" placeholder="Description" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control qty-input" name="items[0][qty]" placeholder="Qty" step="0.01" min="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control unit-price-input" name="items[0][unit_price]" placeholder="Unit Price" step="0.01" min="0" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control line-total-input" name="items[0][line_total]" placeholder="Total" step="0.01" min="0" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-item" style="display: none;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">
                            <i class="bi bi-plus me-1"></i>Add Item
                        </button>
                    </div>

                    <!-- Totals -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="subtotal" class="form-label">Subtotal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['subtotal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="subtotal" name="subtotal" step="0.01" min="0" readonly>
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
                        <div class="col-md-6">
                            <label for="tax_total" class="form-label">Tax Total</label>
                            <input type="number" class="form-control <?php $__errorArgs = ['tax_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="tax_total" name="tax_total" step="0.01" min="0" value="<?php echo e(old('tax_total', 0)); ?>">
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
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total" class="form-label">Total <span class="text-danger">*</span></label>
                            <input type="number" class="form-control <?php $__errorArgs = ['total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="total" name="total" step="0.01" min="0" readonly>
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
                                  id="notes" name="notes" rows="3"><?php echo e(old('notes')); ?></textarea>
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
                        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Create Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Add from Domains/Services -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Add Items</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Add Domain Renewal</label>
                    <select class="form-select" id="domain-select">
                        <option value="">Select a domain</option>
                        <?php $__currentLoopData = $domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($domain->id); ?>" data-price="<?php echo e($domain->price); ?>" data-description="Domain renewal: <?php echo e($domain->fqdn); ?>">
                                <?php echo e($domain->fqdn); ?> - <?php echo e($domain->customer->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-domain">
                        <i class="bi bi-plus me-1"></i>Add Domain
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Add Service</label>
                    <select class="form-select" id="service-select">
                        <option value="">Select a service</option>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>" data-price="<?php echo e($service->price); ?>" data-description="<?php echo e($service->name); ?>">
                                <?php echo e($service->name); ?> - <?php echo e($service->customer->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-service">
                        <i class="bi bi-plus me-1"></i>Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Help -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Help</h5>
            </div>
            <div class="card-body">
                <h6>Creating Invoices</h6>
                <ul class="small text-muted">
                    <li>Select a customer from the dropdown</li>
                    <li>Set issue and due dates</li>
                    <li>Add items manually or use quick add</li>
                    <li>Tax is optional and will be added to subtotal</li>
                    <li>Invoice number will be generated automatically</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = 1;
    
    // Add item functionality
    document.getElementById('add-item').addEventListener('click', function() {
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.querySelector('.invoice-item').cloneNode(true);
        
        // Update input names and clear values
        newItem.querySelectorAll('input').forEach(input => {
            const name = input.name.replace('[0]', '[' + itemIndex + ']');
            input.name = name;
            input.value = '';
        });
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
            calculateTotals();
        });
        
        // Add calculation functionality
        addCalculationListeners(newItem);
    });
    
    // Add calculation listeners to existing items
    document.querySelectorAll('.invoice-item').forEach(item => {
        addCalculationListeners(item);
    });
    
    // Tax calculation
    document.getElementById('tax_total').addEventListener('input', calculateTotals);
    
    function addCalculationListeners(item) {
        const qtyInput = item.querySelector('.qty-input');
        const unitPriceInput = item.querySelector('.unit-price-input');
        const lineTotalInput = item.querySelector('.line-total-input');
        
        [qtyInput, unitPriceInput].forEach(input => {
            input.addEventListener('input', function() {
                const qty = parseFloat(qtyInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                lineTotalInput.value = (qty * unitPrice).toFixed(2);
                calculateTotals();
            });
        });
    }
    
    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.line-total-input').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        
        const taxTotal = parseFloat(document.getElementById('tax_total').value) || 0;
        const total = subtotal + taxTotal;
        
        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('total').value = total.toFixed(2);
    }
    
    // Quick add functionality
    document.getElementById('add-domain').addEventListener('click', function() {
        const select = document.getElementById('domain-select');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            addQuickItem(option.dataset.description, option.dataset.price);
            select.selectedIndex = 0;
        }
    });
    
    document.getElementById('add-service').addEventListener('click', function() {
        const select = document.getElementById('service-select');
        const option = select.options[select.selectedIndex];
        if (option.value) {
            addQuickItem(option.dataset.description, option.dataset.price);
            select.selectedIndex = 0;
        }
    });
    
    function addQuickItem(description, price) {
        const itemsContainer = document.getElementById('invoice-items');
        const newItem = document.querySelector('.invoice-item').cloneNode(true);
        
        // Update input names and set values
        newItem.querySelectorAll('input').forEach(input => {
            const name = input.name.replace('[0]', '[' + itemIndex + ']');
            input.name = name;
        });
        
        newItem.querySelector('input[name*="[description]"]').value = description;
        newItem.querySelector('input[name*="[qty]"]').value = '1';
        newItem.querySelector('input[name*="[unit_price]"]').value = price;
        newItem.querySelector('input[name*="[line_total]"]').value = price;
        
        // Show remove button
        newItem.querySelector('.remove-item').style.display = 'block';
        
        itemsContainer.appendChild(newItem);
        itemIndex++;
        
        // Add remove functionality
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
            calculateTotals();
        });
        
        // Add calculation functionality
        addCalculationListeners(newItem);
        
        calculateTotals();
    }
    
    // Initial calculation
    calculateTotals();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/invoices/create.blade.php ENDPATH**/ ?>