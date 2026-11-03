<?php

namespace App\Livewire;

use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use App\Helpers\CartManagement as Cm;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

#[Title('Products - Shop4all')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url(as: 'selected_categories')]
    public $selected_categories = [];

    #[Url(as: 'selected_brands')]
    public $selected_brands = [];

    #[Url(as: 'featured')]
    public $featured;

    #[Url(as: 'on_sale')]
    public $on_sale;

    #[Url(as: 'price_range')]
    public $price_range = 0;

    #[Url(as: 'sort')]
    public $sort = 'latest';
    public $min_price = 0;
    public $max_price = 0;
    public function render()
    {
        $price_limit = Product::where('is_active', 1)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();
        $this->min_price = $price_limit->min_price;
        $this->max_price = $price_limit->max_price;

        $products = Product::query()->where('is_active', 1);

        if(!empty($this->selected_categories)) {
            $products->whereIn('category_id', $this->selected_categories);
        }
        if(!empty($this->selected_brands)) {
            $products->whereIn('brand_id', $this->selected_brands);
        }
        if($this->featured) {
            $products->where('is_featured', 1);
        }
        if($this->on_sale) {
            $products->where('on_sale', 1);
        }
        if($this->price_range) {
            $products->whereBetween('price', [0, $this->price_range]);
        }
        if($this->sort == 'latest') {
            $products->latest();
        }
        if($this->sort == 'price') {
            $products->orderBy('price');
        }

        $brands = Brand::where('is_active', 1)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', 1)->get(['id', 'name', 'slug']);

        return view('livewire.products-page',[
            'products' => $products->paginate(10),
            'brands' => $brands,
            'categories' => $categories,
            'max_price' => $this->max_price,
            'min_price' => $this->min_price,
        ]);
    }

    public function addToCart($product_id) {
       $total_count = Cm::addCartItemsToCart($product_id);
       $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);
       LivewireAlert::success()
        ->text('Product added to cart successfully!')
        ->asToast()
        ->show();
    }
}
