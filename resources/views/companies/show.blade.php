@extends('layouts.app')

@section('title', $companyDetails['company']['name'])

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <!-- Company Information -->
    <div class="border-b border-gray-200 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    {{ $companyDetails['company']['name'] }}
                </h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600 mb-1"><span class="font-medium">Country:</span> {{ $companyDetails['company']['country'] }}</p>
                        <p class="text-gray-600 mb-1"><span class="font-medium">Identifier:</span> {{ $companyDetails['company']['identifier'] }}</p>
                        @if(isset($companyDetails['company']['state']))
                            <p class="text-gray-600 mb-1"><span class="font-medium">State:</span> {{ $companyDetails['company']['state'] }}</p>
                        @endif
                        @if(isset($companyDetails['company']['company_type']))
                            <p class="text-gray-600 mb-1"><span class="font-medium">Company Type:</span> {{ $companyDetails['company']['company_type'] }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-600"><span class="font-medium">Address:</span> {{ $companyDetails['company']['address'] }}</p>
                    </div>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                {{ $companyDetails['company']['country_code'] }}
            </span>
        </div>
    </div>

    <!-- Available Reports -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Available Reports</h2>
        
        @if($companyDetails['reports']->isNotEmpty())
            <div class="grid gap-4">
                @if($companyDetails['company']['country_code'] === 'PH')
                    <!-- Philippines - Grouped by type with periods -->
                    @foreach($companyDetails['reports'] as $report)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $report['name'] }}</h3>
                                    <p class="text-gray-600">Price: ${{ number_format($report['price'], 2) }}</p>
                                </div>
                            </div>
                            
                            @if(isset($report['periods']) && count($report['periods']) > 0)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Available Periods:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($report['periods'] as $period)
                                            <button 
                                                onclick="addToCart({{ $report['id'] }}, '{{ $report['name'] }}', '{{ $companyDetails['company']['name'] }}', '{{ $report['country_code'] }}', {{ $report['price'] }}, '{{ $period }}')"
                                                class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition duration-200"
                                            >
                                                Add {{ $period }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <button 
                                    onclick="addToCart({{ $report['id'] }}, '{{ $report['name'] }}', '{{ $companyDetails['company']['name'] }}', '{{ $report['country_code'] }}', {{ $report['price'] }})"
                                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-200"
                                >
                                    Add to Cart - ${{ number_format($report['price'], 2) }}
                                </button>
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- Other countries - Standard list -->
                    @foreach($companyDetails['reports'] as $report)
                        <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $report['name'] }}</h3>
                                <p class="text-gray-600">Type: {{ $report['type'] }}</p>
                                <p class="text-gray-600">Price: ${{ number_format($report['price'], 2) }}</p>
                            </div>
                            <button 
                                onclick="addToCart({{ $report['id'] }}, '{{ $report['name'] }}', '{{ $companyDetails['company']['name'] }}', '{{ $report['country_code'] }}', {{ $report['price'] }})"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition duration-200"
                            >
                                Add to Cart
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">No reports available for this company</p>
            </div>
        @endif
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center">
        <a 
            href="{{ route('companies.search') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200"
        >
            ← Back to Search
        </a>
        <a 
            href="{{ route('cart.view') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
        >
            View Cart (<span id="cart-count-link">{{ count(Session::get('cart', [])) }}</span>)
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCart(reportId, reportName, companyName, countryCode, price, period = null) {
    axios.post('/cart/add', {
        report_id: reportId,
        report_name: reportName,
        company_name: companyName,
        country_code: countryCode,
        price: price,
        period: period
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(function (response) {
        if (response.data.success) {
            // Update cart count
            document.getElementById('cart-count').textContent = response.data.cart_count;
            document.getElementById('cart-count-link').textContent = response.data.cart_count;
            
            // Show success message
            alert('Report added to cart successfully!');
        }
    })
    .catch(function (error) {
        console.error('Error adding to cart:', error);
        alert('Error adding report to cart. Please try again.');
    });
}
</script>
@endsection