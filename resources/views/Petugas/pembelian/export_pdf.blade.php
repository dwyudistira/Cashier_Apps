<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>INVOICE</title>
    <style>
        /* Reset dan base styling */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        /* Container utama */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Header */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #2d3748;
        }
        
        .invoice-number {
            background: #ebf8ff;
            color: #2b6cb0;
            font-size: 14px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 9999px;
            display: inline-block;
        }
        
        .invoice-date {
            color: #718096;
            margin-left: 15px;
        }
        
        /* Garis pemisah */
        .divider {
            border-top: 2px solid #e2e8f0;
            border-bottom: 2px solid #e2e8f0;
            height: 4px;
            margin: 20px 0;
        }
        
        /* Tabel produk */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .product-table th {
            text-align: left;
            padding-bottom: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .product-table td {
            padding: 12px 0;
            border-bottom: 1px solid #edf2f7;
        }
        
        .product-table tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Info box */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #ebf8ff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #bee3f8;
        }
        
        .info-box-gray {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
        }
        
        .info-label {
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 20px;
            font-weight: bold;
            color: #2b6cb0;
        }
        
        .info-value-gray {
            color: #2d3748;
        }
        
        /* Total box */
        .total-box {
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .total-label {
            font-size: 18px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #2b6cb0;
        }
        
        /* Footer */
        .invoice-footer {
            text-align: center;
            margin-top: 30px;
            color: #718096;
            font-size: 14px;
        }
        
        /* Utility classes */
        .font-bold {
            font-weight: bold;
        }
        
        .text-blue-600 {
            color: #3182ce;
        }
        
        .text-gray-800 {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div>
                <h1 class="invoice-title">INVOICE</h1>
                <div style="margin-top: 10px;">
                    <span class="invoice-number">#{{ $sales['invoice_number'] }}</span>
                    <span class="invoice-date">{{ $sales['date'] }}</span>
                </div>
            </div>
        </div>
        
        <!-- Divider -->
        <div class="divider"></div>
        
        <!-- Product Table -->
        <table class="product-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartData as $item)
                <tr>
                    <td class="font-bold text-gray-800">{{ $item['nama'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ $item['jumlah'] }}</td>
                    <td class="text-right text-blue-600 font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Info Grid -->
        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">POIN DIGUNAKAN</div>
                <div class="info-value">{{ number_format($points, 0, ',', '.') }}</div>
            </div>
            <div class="info-box info-box-gray">
                <div class="info-label">KASIR</div>
                <div class="info-value info-value-gray">{{ $user['name'] }}</div>
            </div>
        </div>
        
        <!-- Total Box -->
        <div class="total-box">
            <div class="total-row">
                <span class="total-label">REMBALLAN</span>
                <span class="total-label">Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
            </div>
            <div class="total-row" style="padding-top: 15px; border-top: 1px solid #e2e8f0;">
                <span class="total-label">TOTAL</span>
                <span class="total-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="invoice-footer">
            <p>Terima kasih telah berbelanja bersama kami</p>
            <p style="margin-top: 5px;">Invoice ini sah dan diproses oleh sistem</p>
        </div>
    </div>
</body>
</html>