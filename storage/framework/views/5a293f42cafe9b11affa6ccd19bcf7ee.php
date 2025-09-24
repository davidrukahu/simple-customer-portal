<?php $__env->startSection('title', $customer->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3"><?php echo e($customer->name); ?></h1>
            <p class="text-muted mb-0">
                Customer since <?php echo e($customer->created_at->format('M d, Y')); ?>

                <?php if($customer->is_active): ?>
                    <span class="badge bg-success ms-2">Active</span>
                <?php else: ?>
                    <span class="badge bg-secondary ms-2">Inactive</span>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.customers.edit', $customer)); ?>" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
            <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Customers
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($stats['total_domains']); ?></h4>
                            <p class="mb-0">Total Domains</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-globe fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($stats['active_domains']); ?></h4>
                            <p class="mb-0">Active Domains</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($customer->currency); ?> <?php echo e(number_format($stats['outstanding_amount'], 2)); ?></h4>
                            <p class="mb-0">Outstanding</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0"><?php echo e($stats['overdue_count']); ?></h4>
                            <p class="mb-0">Overdue Invoices</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customer Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold">Name:</td>
                            <td><?php echo e($customer->name); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td><?php echo e($customer->email); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Company:</td>
                            <td><?php echo e($customer->company ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Phone:</td>
                            <td><?php echo e($customer->phone ?? 'N/A'); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Currency:</td>
                            <td><?php echo e($customer->currency); ?></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Status:</td>
                            <td>
                                <?php if($customer->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Billing Address -->
            <?php if($customer->billing_address_json): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Billing Address</h6>
                    </div>
                    <div class="card-body">
                        <?php $address = $customer->billing_address_json; ?>
                        <address class="mb-0">
                            <?php if(!empty($address['street'])): ?>
                                <?php echo e($address['street']); ?><br>
                            <?php endif; ?>
                            <?php if(!empty($address['city']) || !empty($address['state'])): ?>
                                <?php echo e($address['city']); ?><?php echo e(!empty($address['city']) && !empty($address['state']) ? ', ' : ''); ?><?php echo e($address['state']); ?><br>
                            <?php endif; ?>
                            <?php if(!empty($address['postal_code'])): ?>
                                <?php echo e($address['postal_code']); ?><br>
                            <?php endif; ?>
                            <?php if(!empty($address['country'])): ?>
                                <?php echo e($address['country']); ?>

                            <?php endif; ?>
                        </address>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Notes -->
            <?php if($customer->notes): ?>
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Notes</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo e($customer->notes); ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Domains and Invoices -->
        <div class="col-md-8">
            <!-- Recent Domains -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Domains (<?php echo e($customer->domains->count()); ?>)</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if($customer->domains->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Domain</th>
                                        <th>Expires</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $customer->domains->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($domain->fqdn); ?></td>
                                            <td><?php echo e($domain->expires_at->format('M d, Y')); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($domain->status === 'active' ? 'success' : 'warning'); ?>">
                                                    <?php echo e(ucfirst($domain->status)); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($domain->currency); ?> <?php echo e(number_format($domain->price, 2)); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-globe fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No domains registered yet.</p>
                            <a href="#" class="btn btn-primary btn-sm">Add Domain</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Invoices -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">Recent Invoices (<?php echo e($customer->invoices->count()); ?>)</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if($customer->invoices->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $customer->invoices->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($invoice->number); ?></td>
                                            <td><?php echo e($invoice->issue_date->format('M d, Y')); ?></td>
                                            <td><?php echo e($invoice->due_date->format('M d, Y')); ?></td>
                                            <td><?php echo e($invoice->currency); ?> <?php echo e(number_format($invoice->total, 2)); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($invoice->status === 'paid' ? 'success' : 
                                                    ($invoice->status === 'overdue' ? 'danger' : 'warning')); ?>">
                                                    <?php echo e(ucfirst($invoice->status)); ?>

                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-file-invoice fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No invoices created yet.</p>
                            <a href="#" class="btn btn-primary btn-sm">Create Invoice</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/david/Desktop/onechamber-webadmin/resources/views/admin/customers/show.blade.php ENDPATH**/ ?>