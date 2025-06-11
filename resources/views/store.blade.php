@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Левая часть - Фильтры -->
        <aside class="w-full lg:w-64 bg-white p-6 rounded-lg shadow-lg h-fit">
            <h2 class="text-xl font-bold mb-4">Фильтры</h2>

            <form action="{{ route('products.index') }}" method="GET">
                <!-- Фильтр по категориям -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Категории</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                        <li>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                    {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300">
                                <span>{{ $category->name }}</span>
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Фильтр по цене -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Цена</h3>
                    <div class="flex gap-2">
                        <input type="number" name="min_price" placeholder="От" value="{{ request('min_price') }}" class="w-full p-2 border rounded">
                        <input type="number" name="max_price" placeholder="До" value="{{ request('max_price') }}" class="w-full p-2 border rounded">
                    </div>
                </div>

                <!-- Фильтр по размеру -->
                <div class="mb-6">
                    <h3 class="font-semibold mb-3">Размер</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @php $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL']; @endphp
                        @foreach($sizes as $size)
                        <div class="size-filter-item">
                            <input type="checkbox" id="size-{{ $size }}" name="sizes[]" value="{{ $size }}" 
                                {{ in_array($size, request('sizes', [])) ? 'checked' : '' }}
                                class="size-checkbox hidden">
                            <label for="size-{{ $size }}" 
                                class="block w-full p-2 border rounded cursor-pointer text-center transition-colors
                                    {{ in_array($size, request('sizes', [])) ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
                                {{ $size }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Сортировка (скрытое поле) -->
                <input type="hidden" name="sort" value="{{ request('sort', '') }}">

                <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800">
                    Применить фильтры
                </button>
            </form>
        </aside>

        <!-- Правая часть - Товары -->
        <main class="flex-1">
            <!-- Сортировка -->
            <div class="bg-white p-4 rounded-lg shadow-lg mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <span class="text-gray-600 mb-2 md:mb-0">Найдено {{ $products->total() }} товаров</span>
                    <form action="{{ route('products.index') }}" method="GET" id="sort-form">
                        <!-- Передаем текущие параметры фильтрации -->
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $k => $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <select name="sort" class="bg-white border rounded p-2" onchange="document.getElementById('sort-form').submit()">
                            <option value="" {{ request('sort') == '' ? 'selected' : '' }}>По умолчанию</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По возрастанию цены</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По убыванию цены</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Сетка товаров -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <!-- Карточка товара -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <a href="{{ route('products.show', $product->id) }}">
                        <div class="aspect-square bg-gray-200">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images[0]->image_url) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            @else
                                <img src="https://via.placeholder.com/300" class="w-full h-full object-cover" alt="{{ $product->name }}">
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <a href="{{ route('products.show', $product->id) }}">
                            <h3 class="font-semibold mb-2">{{ $product->name }}</h3>
                        </a>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 50) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                            <a href="{{ route('products.show', $product->id) }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                                Подробнее
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Товары не найдены. Попробуйте изменить параметры фильтрации.</p>
                </div>
                @endforelse
            </div>

            <!-- Пагинация -->
            @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $products->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Кликабельные размеры фильтра
        const sizeCheckboxes = document.querySelectorAll('.size-checkbox');
        sizeCheckboxes.forEach(checkbox => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            
            label.addEventListener('click', function() {
                // Обработка клика происходит автоматически благодаря связи label и input
                // Здесь только визуальное оформление
                setTimeout(() => {
                    if (checkbox.checked) {
                        label.classList.add('bg-black', 'text-white');
                        label.classList.remove('hover:bg-gray-100');
                    } else {
                        label.classList.remove('bg-black', 'text-white');
                        label.classList.add('hover:bg-gray-100');
                    }
                }, 0);
            });
        });
    });
</script>
@endsection 