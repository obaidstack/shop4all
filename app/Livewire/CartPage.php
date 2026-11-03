<?php

namespace App\Livewire;

use App\Helpers\CartManagement as Cm;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - Shop4all')]
class CartPage extends Component
{
    public $cart_items = [];
    public $grand_total;
    
    public function mount() {
        $this->cart_items = Cm::getCookieCartItems();
        $this->grand_total = Cm::calculateGrandTotal($this->cart_items);
    }

    public function removeItem($product_id) {
        $this->cart_items = Cm::removeCartItem($product_id);
        $this->grand_total = Cm::calculateGrandTotal($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(Navbar::class);
    }

    public function incrementQty($product_id) {
        $this->cart_items = Cm::incrementCookieCartItem($product_id);
        $this->grand_total = Cm::calculateGrandTotal($this->cart_items);
    }

    public function decrementQty($product_id) {
        $this->cart_items = Cm::decrementCookieCartItem($product_id);
        $this->grand_total = Cm::calculateGrandTotal($this->cart_items);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
