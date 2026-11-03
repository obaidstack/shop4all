<?php

namespace App\Livewire;

use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Laravel\Mcp\Server\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement as Cm;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Product Details - Shop4all')]
class ProductDetailPage extends Component
{
    public $slug;
    public $quantity = 1;

    public function mount($slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();
        return view('livewire.product-detail-page', [
            'product' => $product,
        ]); 
    }

    public function incrementQty() {
        $this->quantity++;
    }

    public function decrementQty() {
        if($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart($product_id) {
       $total_count = Cm::addCartItemsToCart($product_id, $this->quantity);
       $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
       LivewireAlert::success()
        ->text('Product added to cart successfully!')
        ->asToast()
        ->show();
    }
}
