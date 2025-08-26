@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Shopping Cart</h1>
    
    @if(count($cart) > 0)
        <div class="space-y-4 mb-6">
            @foreach($cart as $item)
                <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $item['report_name'] }}</h3>
                        <p class="text-gray-600">Company: {{ $item['company_name'] }}</p>
                        <p class="text-gray-600">Country: {{ $item['country_code'] }}</p>
                        @if($item['period'])
                            <p class="text-gray-600">Period: {{ $item['period'] }}</p>
                        @endif
                        <p class="text-gray-800 font-medium">${{ number_format($item['price'], 2) }}</p>
                    </div>
                    <button 
                        onclick="removeFromCart('{{ $item['cart_key'] }}')"
                        class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition duration-200"
                    >
                        Remove
                    </button>
                </div>
            @endforeach
        </div>

        <div class="border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-6">
                <div class="text-2xl font-bold text-gray-900">
                    Total: ${{ number_format($total, 2) }}
                </div>
                <div class="space-x-4">
                    <form method="POST" action="{{ route('cart.clear') }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit"
                            onclick="return confirm('Are you sure you want to clear the cart?')"
                            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition duration-200"
                        >
                            Clear Cart
                        </button>
                    </form>
                    <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition duration-200">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg mb-4">Your cart is empty</p>
            <a 
                href="{{ route('companies.search') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200"
            >
                Start Shopping
            </a>
        </div>
    @endif

    <div class="mt-8">
        <a 
            href="{{ route('companies.search') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition duration-200"
        >
            ← Continue Shopping
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
function removeFromCart(cartKey) {
    axios.delete('/cart/remove', {
        data: { cart_key: cartKey },
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(function (response) {
        if (response.data.success) {
            // Reload the page to update the cart
            window.location.reload();
        }
    })
    .catch(function (error) {
        console.error('Error removing from cart:', error);
        alert('Error removing item from cart. Please try again.');
    });
}
</script>
@endsection