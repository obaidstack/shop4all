<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Details - Shop4all')]
class OrderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id) {
        $this->order_id = $order_id;
    }
    public function render()
    {
        $order = Order::where('id', $this->order_id)->with(['items.product', 'address'])->first();
        return view('livewire.order-detail-page', [
            'order' => $order,
        ]);
    }
}
