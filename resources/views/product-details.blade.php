@include('layouts.header')

<div class="page-header">
    <div class="page-title">
        <h4>Product Details</h4>
        <h6>Full details of a product</h6>
    </div>
    <div class="page-btn mt-0">
        <a href="{{ route('products.index') }}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back to Product List</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="productdetails">
                    <ul class="product-bar">
                        <li>
                            <h4>Product Name</h4>
                            <h6>{{ $product->name }}</h6>
                        </li>
                        <li>
                            <h4>Category</h4>
                            <h6>{{ $product->category->name ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Sub Category</h4>
                            <h6>{{ $product->subcategory->name ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Brand</h4>
                            <h6>{{ $product->brand->name ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Unit of Measure</h4>
                            <h6>{{ ucfirst($product->unit_of_measure) }}</h6>
                        </li>
                        <li>
                            <h4>Quantity</h4>
                            <h6>{{ rtrim(rtrim(number_format($product->quantity, 2, '.', ''), '0'), '.') }}</h6>
                        </li>
                        <li>
                            <h4>Unit Price</h4>
                            <h6>₹{{ number_format($product->unit_price, 2) }}</h6>
                        </li>
                        <li>
                            <h4>Tax Type</h4>
                            <h6>{{ ucfirst($product->tax_type) }}</h6>
                        </li>
                        <li>
                            <h4>Tax Category</h4>
                            <h6>{{ strtoupper($product->tax_category) }}</h6>
                        </li>
                        <li>
                            <h4>Tax Percentage</h4>
                            <h6>{{ $product->tax_percentage }}%</h6>
                        </li>
                        <li>
                            <h4>HSN/SAC Code</h4>
                            <h6>{{ $product->hsn_sac_code ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Manufactured Date</h4>
                            <h6>{{ $product->manufactured_date ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Expiry Date</h4>
                            <h6>{{ $product->expiry_date ?? 'N/A' }}</h6>
                        </li>
                        <li>
                            <h4>Description</h4>
                            <h6>{{ $product->description ?? 'N/A' }}</h6>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')