
@extends('layouts.admin.app')

@section('title', 'Product Details')
@section('page-heading', 'Product Details')

@section('content')

@php
    $barcodeGenerator = new \Picqer\Barcode\BarcodeGeneratorSVG();
    $barcodeType = \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128;
@endphp

<div class="container-fluid px-0 product-details-page">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <a href="{{ route('admin.products.index') }}"
               class="text-decoration-none"
               style="color:#4A2616;">
                <i class="bi bi-arrow-left me-2"></i> Back to Products
            </a>

            <h4 class="mt-3 mb-1">{{ $product->name }}</h4>
            <p class="text-muted mb-0">View complete product information.</p>
        </div>

        <a href="{{ route('admin.products.edit', $product->id) }}"
           class="btn text-white px-4"
           style="background:#4A2616;">
            <i class="bi bi-pencil me-2"></i> Edit Product
        </a>
    </div>

    {{-- Product Information --}}
    <div class="row g-4">

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4 text-center">

                    @if($product->image)
                        <img src="{{ (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image)) }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded-4 mb-3"
                             style="width:100%;max-height:350px;object-fit:contain;background:#f9efe3;">
                    @else
                        <div class="rounded-4 d-flex align-items-center justify-content-center mb-3"
                             style="height:300px;background:#f9efe3;">
                            <i class="bi bi-cake2" style="font-size:100px;color:#8d7468;"></i>
                        </div>
                    @endif

                    <h4 class="mb-1">{{ $product->name }}</h4>
                    <p class="text-muted mb-0">SKU: {{ $product->sku }}</p>

                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0">Product Information</h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Product Name</small>
                            <h6>{{ $product->name }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Product SKU</small>
                            <h6>{{ $product->sku }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Category</small>
                            <h6>{{ $product->category?->name ?? 'Uncategorized' }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Base Unit</small>
                            <h6>{{ $product->baseUnit?->name ?? 'N/A' }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Product Type</small>
                            <h6>{{ ucfirst(str_replace('_', ' ', $product->product_type ?? 'N/A')) }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">GST Rate</small>
                            <h6>{{ number_format((float) $product->gst_rate, 2) }}%</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">HSN Code</small>
                            <h6>{{ $product->hsn_code ?: 'N/A' }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Status</small>

                            @if($product->status === 'active')
                                <span class="badge bg-success-subtle text-success px-3 py-2">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-2">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="col-12">
                            <small class="text-muted d-block mb-2">Description</small>
                            <p class="mb-0">
                                {{ $product->description ?: 'No description available.' }}
                            </p>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Created On</small>
                            <h6>{{ $product->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</h6>
                        </div>

                        <div class="col-sm-6">
                            <small class="text-muted d-block mb-1">Last Updated</small>
                            <h6>{{ $product->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</h6>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Barcode Management --}}
    <div class="card border-0 shadow-sm rounded-4 mt-4">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <h5 class="mb-1">
                        <i class="bi bi-upc-scan me-2" style="color:#4A2616;"></i>
                        Barcode Management
                    </h5>

                    <p class="text-muted small mb-0">
                        Generate and print barcode labels with your required quantity.
                    </p>
                </div>

                <span class="badge rounded-pill px-3 py-2"
                      style="background:#f5e9dd;color:#4A2616;">
                    {{ 1 + $product->productVariants->count() }} Barcode Types
                </span>

            </div>
        </div>

        <div class="card-body p-4">

            <div class="row g-3">

                {{-- Main Product Barcode --}}
                <div class="col-xl-6 col-md-6">

                    <div class="barcode-print-card h-100"
                         data-title="{{ $product->name }}"
                         data-sku="{{ $product->sku }}">

                        <div class="barcode-card-top">
                            <div>
                                <span class="barcode-type">MAIN PRODUCT</span>
                                <h6 class="mt-2 mb-1">{{ $product->name }}</h6>
                                <small class="text-muted">SKU: {{ $product->sku }}</small>
                            </div>

                            <i class="bi bi-upc-scan barcode-icon"></i>
                        </div>

                        @if($product->barcode)

                            <div class="barcode-preview">
                                {!! $barcodeGenerator->getBarcode($product->barcode, $barcodeType, 1.4, 42) !!}
                            </div>

                            <div class="barcode-number">
                                {{ $product->barcode }}
                            </div>

                            <template class="barcode-svg-template">
                                {!! $barcodeGenerator->getBarcode($product->barcode, $barcodeType, 1.4, 42) !!}
                            </template>

                            <div class="barcode-actions">

                                <div class="quantity-control">
                                    <label>Print Quantity</label>

                                    <input type="number"
                                           class="form-control barcode-quantity"
                                           value="1"
                                           min="1"
                                           max="500"
                                           step="1">
                                </div>

                                <button type="button"
                                        class="btn barcode-print-btn"
                                        onclick="window.printBarcodeLabel(this)">
                                    <i class="bi bi-printer me-2"></i>
                                    Print Labels
                                </button>

                            </div>

                        @else
                            <div class="barcode-empty">
                                <i class="bi bi-upc fs-2"></i>
                                <p class="mb-0 mt-2">No product barcode available.</p>
                            </div>
                        @endif

                    </div>

                </div>

                {{-- Variant Barcodes --}}
                @forelse($product->productVariants as $variant)

                    <div class="col-xl-6 col-md-6">

                        <div class="barcode-print-card h-100"
                             data-title="{{ $product->name }} - {{ $variant->variant_name }}"
                             data-sku="{{ $variant->sku }}">

                            <div class="barcode-card-top">
                                <div>
                                    <span class="barcode-type">PRODUCT VARIANT</span>

                                    <h6 class="mt-2 mb-1">
                                        {{ $variant->variant_name }}
                                    </h6>

                                    <small class="text-muted">
                                        SKU: {{ $variant->sku }}
                                    </small>

                                    <small class="text-muted d-block">
                                        Unit: {{ $variant->unit?->name ?? 'N/A' }}
                                    </small>

                                </div>

                                <i class="bi bi-upc barcode-icon"></i>
                            </div>

                            @if($variant->barcode)

                                <div class="barcode-preview">
                                    {!! $barcodeGenerator->getBarcode($variant->barcode, $barcodeType, 1.4, 42) !!}
                                </div>

                                <div class="barcode-number">
                                    {{ $variant->barcode }}
                                </div>

                                <template class="barcode-svg-template">
                                    {!! $barcodeGenerator->getBarcode($variant->barcode, $barcodeType, 1.4, 42) !!}
                                </template>

                                <div class="barcode-actions">

                                    <div class="quantity-control">
                                        <label>Print Quantity</label>

                                        <input type="number"
                                               class="form-control barcode-quantity"
                                               value="1"
                                               min="1"
                                               max="500"
                                               step="1">
                                    </div>

                                    <button type="button"
                                            class="btn barcode-print-btn"
                                            onclick="window.printBarcodeLabel(this)">
                                        <i class="bi bi-printer me-2"></i>
                                        Print Labels
                                    </button>

                                </div>

                            @else

                                <div class="barcode-empty">
                                    <i class="bi bi-upc fs-2"></i>
                                    <p class="mb-0 mt-2">
                                        No barcode available for this variant.
                                    </p>
                                </div>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam fs-2 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">
                                No product variants available.
                            </p>
                        </div>
                    </div>

                @endforelse

            </div>
        </div>
    </div>

</div>

<style>
    .product-details-page {
        color: #35251f;
    }

    .barcode-print-card {
        border: 1px solid #eaded3;
        background: #fffefa;
        border-radius: 15px;
        padding: 18px;
        transition: box-shadow .2s ease, border-color .2s ease;
    }

    .barcode-print-card:hover {
        border-color: #c9aa96;
        box-shadow: 0 5px 18px rgba(74, 38, 22, .07);
    }

    .barcode-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        min-height: 72px;
    }

    .barcode-type {
        display: inline-block;
        background: #f5e9dd;
        color: #70432e;
        padding: 4px 9px;
        border-radius: 5px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .7px;
    }

    .barcode-icon {
        color: #a17c66;
        font-size: 25px;
    }

    .barcode-preview {
        background: #fff;
        border: 1px solid #f0e8e0;
        border-radius: 9px;
        padding: 9px 12px;
        margin-top: 12px;
        text-align: center;
        overflow: hidden;
    }

    .barcode-preview svg {
        display: block;
        width: 100%;
        max-width: 260px;
        height: 48px;
        margin: 0 auto;
    }

    .barcode-number {
        text-align: center;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #4A2616;
        margin-top: 7px;
    }

    .barcode-actions {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .quantity-control {
        width: 115px;
    }

    .quantity-control label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #79665d;
        margin-bottom: 5px;
    }

    .barcode-quantity {
        border: 1px solid #dfcfc2;
        border-radius: 8px;
        height: 39px;
    }

    .barcode-print-btn {
        height: 39px;
        background: #4A2616;
        color: white;
        border-radius: 8px;
        padding: 0 17px;
        font-size: 13px;
        font-weight: 600;
        margin-left: auto;
    }

    .barcode-print-btn:hover {
        background: #68402d;
        color: white;
    }

    .barcode-empty {
        background: #faf6f1;
        border-radius: 9px;
        padding: 22px;
        text-align: center;
        color: #8d7468;
        margin-top: 12px;
    }

    @media(max-width: 576px) {
        .barcode-actions {
            align-items: stretch;
        }

        .barcode-print-btn {
            flex: 1;
        }
    }
</style>



@push('scripts')
<script>
window.printBarcodeLabel = function (button) {
    try {
        const card = button.closest('.barcode-print-card');

        if (!card) {
            alert('Barcode information not found.');
            return;
        }

        const quantityInput = card.querySelector('.barcode-quantity');
        const quantity = Number(quantityInput?.value);

        if (!Number.isInteger(quantity) || quantity < 1 || quantity > 500) {
            alert('Please enter a quantity between 1 and 500.');
            quantityInput?.focus();
            return;
        }

        const barcodeElement = card.querySelector('.barcode-number');
        const template = card.querySelector('.barcode-svg-template');

        if (!barcodeElement || !template || !template.innerHTML.trim()) {
            alert('Barcode image is not available.');
            return;
        }

        const title = card.dataset.title || 'Product';
        const sku = card.dataset.sku || '';
        const barcodeNumber = barcodeElement.textContent.trim();
        const barcodeSvg = template.innerHTML.trim();

        // Open immediately during the button click to avoid popup blocking.
        const printWindow = window.open('', '_blank');

        if (!printWindow) {
            alert('Please allow pop-ups for this website and try again.');
            return;
        }

        const escapeHtml = (value) => String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);

        const labels = Array.from({ length: quantity }, () => `
            <div class="label">
                <div class="label-title">${escapeHtml(title)}</div>
                <div class="label-sku">SKU: ${escapeHtml(sku)}</div>
                <div class="label-barcode">${barcodeSvg}</div>
                <div class="label-number">${escapeHtml(barcodeNumber)}</div>
            </div>
        `).join('');

        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Barcode Labels - ${escapeHtml(title)}</title>
                <style>
                    @page { size: A4; margin: 8mm; }
                    * { box-sizing: border-box; }
                    body {
                        margin: 0;
                        font-family: Arial, sans-serif;
                        color: #222;
                    }
                    .print-sheet {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 4mm;
                        align-items: start;
                    }
                    .label {
                        height: 30mm;
                        border: 1px dashed #aaa;
                        border-radius: 2mm;
                        padding: 2mm;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        text-align: center;
                        overflow: hidden;
                        break-inside: avoid;
                    }
                    .label-title {
                        font-size: 9pt;
                        font-weight: bold;
                        max-width: 100%;
                        overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                    }
                    .label-sku {
                        font-size: 7pt;
                        margin-top: 1mm;
                    }
                    .label-barcode {
                        width: 100%;
                        margin-top: 1mm;
                    }
                    .label-barcode svg {
                        display: block;
                        width: 95%;
                        height: 12mm;
                        margin: auto;
                    }
                    .label-number {
                        font-size: 8pt;
                        font-weight: bold;
                        letter-spacing: 1px;
                    }
                </style>
            ${'<' + '/head>'}
            <body>
                <div class="print-sheet">${labels}</div>
                <script>
                    window.onload = function () {
                        setTimeout(function () {
                            window.focus();
                            window.print();
                        }, 400);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();

    } catch (error) {
        console.error('Barcode printing error:', error);
        alert('Unable to print barcode labels: ' + error.message);
    }
};
</script>
@endpush

@endsection