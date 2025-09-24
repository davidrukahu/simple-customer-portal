<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .company-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        
        .invoice-info p {
            margin: 5px 0;
            color: #666;
        }
        
        .customer-info {
            margin-bottom: 30px;
        }
        
        .customer-info h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        
        .customer-info p {
            margin: 3px 0;
            color: #666;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .totals {
            width: 100%;
            margin-top: 20px;
        }
        
        .totals table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        
        .totals td {
            padding: 5px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .totals .total-row {
            font-weight: bold;
            font-size: 14px;
            background-color: #f5f5f5;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-sent { background-color: #cce5ff; color: #004085; }
        .status-overdue { background-color: #f8d7da; color: #721c24; }
        .status-draft { background-color: #e2e3e5; color: #383d41; }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>OneChamber LTD</h1>
            <p>Worldwide Printing Center</p>
            <p>4th Floor, Mushebi Road</p>
            <p>Parklands, Nairobi, Kenya</p>
            <p>Email: info@onechamber.co.ke</p>
        </div>
        
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p><strong>Invoice #:</strong> {{ $invoice->number }}</p>
            <p><strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}</p>
            <p><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $invoice->status }}">
                    {{ ucfirst($invoice->status) }}
                </span>
            </p>
        </div>
    </div>
    
    <div class="customer-info">
        <h3>Bill To:</h3>
        <p><strong>{{ $invoice->customer->name }}</strong></p>
        @if($invoice->customer->company)
            <p>{{ $invoice->customer->company }}</p>
        @endif
        <p>{{ $invoice->customer->email }}</p>
        @if($invoice->customer->phone)
            <p>{{ $invoice->customer->phone }}</p>
        @endif
        @if($invoice->customer->billing_address_json)
            @php
                $address = is_string($invoice->customer->billing_address_json) 
                    ? json_decode($invoice->customer->billing_address_json, true) 
                    : $invoice->customer->billing_address_json;
            @endphp
            @if($address && is_array($address))
                @foreach($address as $line)
                    @if($line)
                        <p>{{ $line }}</p>
                    @endif
                @endforeach
            @endif
        @endif
    </div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">{{ number_format($item->qty, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right"><strong>{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</strong></td>
            </tr>
            @if($invoice->tax_total > 0)
                <tr>
                    <td colspan="3" class="text-right"><strong>Tax:</strong></td>
                    <td class="text-right"><strong>{{ $invoice->currency }} {{ number_format($invoice->tax_total, 2) }}</strong></td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong>{{ $invoice->currency }} {{ number_format($invoice->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    @if($invoice->notes)
        <div style="margin-top: 20px;">
            <h3>Notes:</h3>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>For any questions regarding this invoice, please contact us at info@onechamber.co.ke</p>
    </div>
</body>
</html>
