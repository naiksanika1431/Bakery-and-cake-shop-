<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Invoice {{ $order->order_number }}</title>

    <style>
        :root {
            --green: #294b3b;
            --green-light: #eaf0eb;
            --gold: #b58b50;
            --dark: #252d28;
            --muted: #788078;
            --line: #e4e8e2;
            --cream: #faf9f5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 15px;
            background: #edf0ec;
            color: var(--dark);
            font-family: Arial, Helvetica, sans-serif;
        }

        .toolbar {
            max-width: 900px;
            margin: 0 auto 22px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .toolbar button {
            border: 0;
            padding: 12px 21px;
            border-radius: 7px;
            background: var(--green);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .toolbar button:hover {
            background: #193528;
        }

        .toolbar button.secondary {
            background: #68766c;
        }

        .toolbar button.active {
            background: #172f23;
            outline: 2px solid var(--gold);
        }

        /* MAIN INVOICE */

        .invoice {
            position: relative;
            width: 210mm;
            min-height: 180mm;
            margin: auto;
            padding: 16mm;
            background: #fff;
            box-shadow: 0 8px 35px rgba(25, 45, 32, 0.10);
            overflow: hidden;
        }

        .top-accent {
            height: 7px;
            background: var(--green);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }

        /* HEADER */

        .invoice-header {
            text-align: center;
            padding: 12px 0 23px;
            border-bottom: 1px solid var(--line);
        }

        .brand-placeholder {
            color: var(--green);
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .tagline {
            margin-top: 7px;
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .invoice-label {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 24px;
            background: var(--green-light);
            color: var(--green);
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 3px;
        }

        /* INVOICE INFORMATION */

        .invoice-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin: 24px 0;
            padding: 16px 19px;
            background: var(--cream);
            border-left: 4px solid var(--gold);
        }

        .info-label {
            display: block;
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .info-value {
            color: var(--dark);
            font-size: 13px;
            font-weight: 700;
        }

        .info-right {
            text-align: right;
        }

        /* SECTION TITLE */

        .section-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 28px 0 12px;
            color: var(--green);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .section-heading::after {
            content: "";
            height: 1px;
            flex: 1;
            background: var(--line);
        }

        /* ITEMS TABLE */

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .items-table thead th {
            padding: 13px 10px;
            background: var(--green);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            text-align: left;
        }

        .items-table thead th:first-child {
            border-radius: 5px 0 0 5px;
        }

        .items-table thead th:last-child {
            border-radius: 0 5px 5px 0;
        }

        .items-table tbody td {
            padding: 15px 10px;
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fbfcfa;
        }

        .product-name {
            color: var(--dark);
            font-size: 12px;
            font-weight: 700;
        }

        .product-sku {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
        }

        .product-gst {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
        }

        .center {
            text-align: center !important;
        }

        .right {
            text-align: right !important;
        }

        /* TOTALS */

        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .totals {
            width: 310px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 8px 2px;
            color: #626c64;
            font-size: 12px;
        }

        .total-row strong {
            color: var(--dark);
            font-weight: 700;
        }

        .grand-total {
            margin-top: 12px;
            padding: 17px 15px;
            background: var(--green);
            color: #fff;
            border-radius: 5px;
            font-size: 14px;
            font-weight: 800;
        }

        .grand-total strong {
            color: #fff;
            font-size: 19px;
        }

        /* FOOTER */

        .invoice-footer {
            margin-top: 55px;
            padding-top: 22px;
            border-top: 1px solid var(--line);
            text-align: center;
        }

        .thank-you {
            color: var(--green);
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 2px;
        }

        .footer-message {
            margin-top: 8px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .footer-decoration {
            margin-top: 15px;
            color: var(--gold);
            font-size: 11px;
            letter-spacing: 3px;
        }

        /* 58MM RECEIPT */

        body.mode-58 .invoice {
            width: 58mm;
            min-height: auto;
            padding: 5mm 4mm;
            box-shadow: none;
        }

        body.mode-58 .top-accent {
            height: 4px;
        }

        body.mode-58 .invoice-header {
            padding: 8px 0 12px;
        }

        body.mode-58 .brand-placeholder {
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        body.mode-58 .tagline {
            font-size: 8px;
            letter-spacing: 1px;
        }

        body.mode-58 .invoice-label {
            margin-top: 12px;
            padding: 6px 12px;
            font-size: 10px;
            letter-spacing: 2px;
        }

        body.mode-58 .invoice-info {
            margin: 14px 0;
            padding: 10px;
            gap: 8px;
        }

        body.mode-58 .info-label {
            font-size: 8px;
        }

        body.mode-58 .info-value {
            font-size: 9px;
            overflow-wrap: anywhere;
        }

        body.mode-58 .section-heading {
            margin: 16px 0 8px;
            font-size: 9px;
        }

        body.mode-58 .items-table {
            font-size: 9px;
        }

        body.mode-58 .items-table thead th {
            padding: 7px 3px;
            font-size: 8px;
            letter-spacing: 0;
        }

        body.mode-58 .items-table tbody td {
            padding: 8px 3px;
            overflow-wrap: anywhere;
        }

        body.mode-58 .product-name {
            font-size: 9px;
        }

        body.mode-58 .product-sku,
        body.mode-58 .product-gst {
            font-size: 8px;
        }

        body.mode-58 .totals-wrapper {
            margin-top: 15px;
        }

        body.mode-58 .totals {
            width: 100%;
        }

        body.mode-58 .total-row {
            font-size: 9px;
            padding: 5px 0;
        }

        body.mode-58 .grand-total {
            padding: 11px 8px;
            font-size: 10px;
        }

        body.mode-58 .grand-total strong {
            font-size: 13px;
        }

        body.mode-58 .invoice-footer {
            margin-top: 25px;
            padding-top: 14px;
        }

        body.mode-58 .thank-you {
            font-size: 13px;
        }

        body.mode-58 .footer-message {
            font-size: 8px;
        }

        body.mode-58 .footer-decoration {
            font-size: 8px;
        }

        /* PRINT */

        @media print {
            body {
                margin: 0;
                padding: 0;
                background: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .toolbar {
                display: none !important;
            }

            .invoice {
                margin: 0;
                box-shadow: none;
                min-height: auto;
            }

            body.mode-a4 .invoice {
                width: 210mm;
                padding: 12mm;
            }

            body.mode-58 .invoice {
                width: 58mm;
                padding: 3mm;
            }

            .top-accent,
            .grand-total,
            .items-table thead th,
            .invoice-label {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 0;
            }
        }

        /* MOBILE PREVIEW */

        @media screen and (max-width: 750px) {
            body {
                padding: 10px;
            }

            .invoice {
                width: 100%;
                min-height: auto;
                padding: 20px 15px;
            }

            .brand-placeholder {
                font-size: 21px;
            }

            .items-table {
                font-size: 10px;
            }

            .items-table th,
            .items-table td {
                padding: 8px 4px;
            }

            .totals {
                width: 100%;
            }

            .invoice-info {
                gap: 10px;
            }
        }
    </style>
</head>

<body class="mode-a4">

    <div class="toolbar">
        <button type="button" id="a4Button" class="active"
            onclick="setPrintMode('a4')">
            A4 Invoice
        </button>

        <button type="button" id="receiptButton" class="secondary"
            onclick="setPrintMode('58')">
            58mm Receipt
        </button>

        <button type="button" onclick="window.print()">
            Print Invoice
        </button>

        <button type="button" class="secondary"
            onclick="window.history.back()">
            Back
        </button>
    </div>

    @php
        $money = fn ($value) => '₹' . number_format((float) $value, 2);

        $invoiceDate = optional($order->placed_at)->format('d-m-Y')
            ?? $order->created_at->format('d-m-Y');

        $invoiceTime = optional($order->placed_at)->format('h:i A')
            ?? $order->created_at->format('h:i A');
    @endphp

    <main class="invoice">

        <div class="top-accent"></div>

        <!-- HEADER -->

        <header class="invoice-header">

            <div class="brand-placeholder">
                BAKERY &amp; CAKE SHOP
            </div>

            <div class="tagline">
                Freshly Baked With Love
            </div>

            <div class="invoice-label">
                SALES INVOICE
            </div>

        </header>

        <!-- INVOICE NUMBER AND DATE -->

        <section class="invoice-info">

            <div>
                <span class="info-label">Invoice Number</span>

                <div class="info-value">
                    {{ $order->order_number }}
                </div>
            </div>

            <div class="info-right">
                <span class="info-label">Invoice Date</span>

                <div class="info-value">
                    {{ $invoiceDate }}
                </div>

                <div class="info-value" style="margin-top:5px;">
                    {{ $invoiceTime }}
                </div>
            </div>

        </section>

        <!-- ORDER ITEMS -->

        <div class="section-heading">
            Order Details
        </div>

        <table class="items-table">

            <thead>
                <tr>
                    <th>Product</th>
                    <th class="center">Qty</th>
                    <th class="right">Rate</th>
                    <th class="right">Amount</th>
                </tr>
            </thead>

            <tbody>

                @forelse($order->items as $item)

                    <tr>
                        <td>
                            <span class="product-name">
                                {{ $item->product_name }}
                            </span>

                            @if($item->product_sku)
                                <span class="product-sku">
                                    SKU: {{ $item->product_sku }}
                                </span>
                            @endif

                            @if((float) $item->tax_rate > 0)
                                <span class="product-gst">
                                    GST: {{ number_format((float) $item->tax_rate, 2) }}%
                                </span>
                            @endif
                        </td>

                        <td class="center">
                            {{ rtrim(rtrim(number_format((float) $item->quantity, 3), '0'), '.') }}
                        </td>

                        <td class="right">
                            {{ $money($item->unit_price) }}
                        </td>

                        <td class="right">
                            <strong>
                                {{ $money($item->line_total) }}
                            </strong>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="center">
                            No order items found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

        <!-- BILL TOTALS -->

        <div class="totals-wrapper">

            <section class="totals">

                <div class="total-row">
                    <span>Subtotal</span>

                    <strong>
                        {{ $money($order->subtotal) }}
                    </strong>
                </div>

                <div class="total-row">
                    <span>Discount</span>

                    <strong>
                        - {{ $money($order->discount) }}
                    </strong>
                </div>

                <div class="total-row">
                    <span>GST / Tax</span>

                    <strong>
                        {{ $money($order->tax_amount) }}
                    </strong>
                </div>

                <div class="total-row">
                    <span>Delivery Charge</span>

                    <strong>
                        {{ $money($order->delivery_charge) }}
                    </strong>
                </div>

                <div class="total-row grand-total">
                    <span>GRAND TOTAL</span>

                    <strong>
                        {{ $money($order->grand_total) }}
                    </strong>
                </div>

            </section>

        </div>

        <!-- FOOTER -->

        <footer class="invoice-footer">

            <div class="thank-you">
                THANK YOU!
            </div>

            <div class="footer-message">
                Thank you for choosing us.<br>
                We hope to serve you again.
            </div>

            <div class="footer-decoration">
                ✦ &nbsp; FRESHLY BAKED &nbsp; ✦
            </div>

        </footer>

    </main>

    <script>
        function setPrintMode(mode) {

            document.body.classList.remove('mode-a4', 'mode-58');

            document.body.classList.add(
                mode === '58' ? 'mode-58' : 'mode-a4'
            );

            document.getElementById('a4Button')
                .classList.toggle('active', mode === 'a4');

            document.getElementById('receiptButton')
                .classList.toggle('active', mode === '58');

            document.getElementById('a4Button')
                .classList.toggle('secondary', mode !== 'a4');

            document.getElementById('receiptButton')
                .classList.toggle('secondary', mode !== '58');
        }
    </script>

</body>
</html>