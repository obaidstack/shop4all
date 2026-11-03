<div class="w-full max-w-6xl py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>

  <div class="flex flex-col lg:flex-row gap-6">
    <!-- Cart Items -->
    <div class="lg:w-3/4 w-full">
      
      <!-- Desktop Table -->
      <div class="hidden lg:block bg-white rounded-lg shadow-md p-6 overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-100">
            <tr>
              <th class="text-left font-semibold px-2 py-2">Product</th>
              <th class="text-left font-semibold px-2 py-2">Price</th>
              <th class="text-left font-semibold px-2 py-2">Quantity</th>
              <th class="text-left font-semibold px-2 py-2">Total</th>
              <th class="text-left font-semibold px-2 py-2">Remove</th>
            </tr>
          </thead>
          <tbody>
            @forelse($cart_items as $item)
              <tr wire:key="{{ $item['product_id'] }}" class="border-b">
                <td class="py-3 px-2">
                  <div class="flex items-center">
                    <img class="h-14 w-14 mr-3 object-cover rounded-md" 
                         src="{{ Storage::disk('public')->url($item['image']) }}" 
                         alt="{{ $item['name'] }}">
                    <span class="font-semibold">{{ $item['name'] }}</span>
                  </div>
                </td>
                <td class="py-3 px-2">{{ Number::currency($item['unit_amount'], 'PKR') }}</td>
                <td class="py-3 px-2">
                  <div class="flex items-center">
                    <button wire:click="decrementQty({{ $item['product_id'] }})" class="border rounded-md py-1 px-3 mr-2">-</button>
                    <span class="text-center w-8">{{ $item['quantity'] }}</span>
                    <button wire:click="incrementQty({{ $item['product_id'] }})" class="border rounded-md py-1 px-3 ml-2">+</button>
                  </div>
                </td>
                <td class="py-3 px-2">{{ Number::currency($item['total_amount'], 'PKR') }}</td>
                <td class="py-3 px-2">
                  <button wire:click="removeItem({{ (int) $item['product_id'] }})"
                          wire:loading.attr="disabled"
                          wire:target="removeItem({{ (int) $item['product_id'] }})"
                          class="w-full md:w-24 bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700 transition">
                    <span wire:loading.remove wire:target="removeItem({{ (int) $item['product_id'] }})">Remove</span>
                    <span wire:loading wire:target="removeItem({{ (int) $item['product_id'] }})">Removing...</span>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-lg font-semibold text-slate-500">
                  Your cart is empty. Please add some items to cart first.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Mobile & Tablet Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:hidden">
        @forelse($cart_items as $item)
          <div wire:key="{{ $item['product_id'] }}" class="bg-white rounded-lg shadow-md p-4">
            <div class="flex items-center mb-3">
              <img class="h-16 w-16 mr-4 object-cover rounded-md" 
                   src="{{ Storage::disk('public')->url($item['image']) }}" 
                   alt="{{ $item['name'] }}">
              <span class="font-semibold">{{ $item['name'] }}</span>
            </div>
            <div class="mb-2">Price: {{ Number::currency($item['unit_amount'], 'PKR') }}</div>
            <div class="flex items-center mb-2">
              <button wire:click="decrementQty({{ $item['product_id'] }})" class="border rounded-md py-1 px-3 mr-2">-</button>
              <span class="text-center w-8">{{ $item['quantity'] }}</span>
              <button wire:click="incrementQty({{ $item['product_id'] }})" class="border rounded-md py-1 px-3 ml-2">+</button>
            </div>
            <div class="mb-2">Total: {{ Number::currency($item['total_amount'], 'PKR') }}</div>
            <div class="flex justify-center">
              <button wire:click="removeItem({{ (int) $item['product_id'] }})" wire:loading.attr="disabled"
                wire:target="removeItem({{ (int) $item['product_id'] }})" class="w-1/2 bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-2 
                       hover:bg-red-500 hover:text-white hover:border-red-700 transition text-center">

                <span wire:loading.remove wire:target="removeItem({{ (int) $item['product_id'] }})">Remove</span>
                <span wire:loading wire:target="removeItem({{ (int) $item['product_id'] }})">Removing...</span>
              </button>
            </div>
            </div>
        @empty
          <div class="text-center py-4 text-lg font-semibold text-slate-500">
            Your cart is empty. Please add some items to cart first.
          </div>
        @endforelse
      </div>
    </div>

    <!-- Summary -->
    <div class="lg:w-1/4 w-full">
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4">Summary</h2>
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span>{{ Number::currency($grand_total, 'PKR') }}</span>
        </div>
        <div class="flex justify-between mb-2">
          <span>Taxes</span>
          <span>{{ Number::currency(0, 'PKR') }}</span>
        </div>
        <div class="flex justify-between mb-2">
          <span>Shipping</span>
          <span>{{ Number::currency(0, 'PKR') }}</span>
        </div>
        <hr class="my-2">
        <div class="flex justify-between mb-2">
          <span class="font-semibold">Grand Total</span>
          <span class="font-semibold">{{ Number::currency($grand_total, 'PKR') }}</span>
        </div>
        @if ($cart_items)
          <a wire:navigate href="/checkout" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-lg mt-4">Checkout</a>
        @endif
      </div>
    </div>
  </div>
</div>
