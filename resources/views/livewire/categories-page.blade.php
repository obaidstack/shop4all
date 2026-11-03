<div class="w-full max-w-340 py-10 px-4 sm:px-6 lg:px-8 mx-auto">
  <div class="max-w-340 px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6">
      @foreach ($categories as $cat)
        <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="/products?selected_categories[0]={{ $cat->id }}" wire:key="{{ $cat->id }}">
          <div class="p-4 md:p-5">
            <div class="flex justify-between items-center">
              <div class="flex items-center">
                @if ($cat->image && Storage::disk('public')->exists($cat->image))
                  <img class="h-20 w-20" src="{{ Storage::disk('public')->url($cat->image) }}" alt="{{ $cat->name }}">
                @else
                  <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded bg-gray-100 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                    No image
                  </div>
                @endif
                <div class="ms-3">
                  <h3 class="group-hover:text-blue-600 text-2xl font-semibold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                    {{ $cat->name }}
                  </h3>
                </div>
              </div>
              <div class="ps-3">
                <svg class="shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="m9 18 6-6-6-6" />
                </svg>
              </div>
            </div>
          </div>
        </a>
      @endforeach

    </div>
  </div>
</div>