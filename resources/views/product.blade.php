@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Основной контент -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Сетка с фото и информацией -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Галерея -->
            <div class="space-y-4">
                <!-- Основное фото -->
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    <img id="mainImage" src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images[0]->image_url) : 'https://via.placeholder.com/800' }}" 
                         class="w-full h-full object-cover" alt="{{ $product->name }}">
                </div>
                
                <!-- Миниатюры -->
                @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                    <div class="aspect-square bg-gray-200 rounded cursor-pointer" 
                         onclick="changeImage('{{ asset('storage/' . $image->image_url) }}')">
                        <img src="{{ asset('storage/' . $image->image_url) }}" 
                             class="w-full h-full object-cover" alt="{{ $product->name }} - изображение {{ $loop->iteration }}">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Информация о товаре -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
                    <p class="text-gray-600">Артикул: {{ $product->id }}</p>
                </div>

                <div class="space-y-4">
                    <p class="text-2xl font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                    
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="border-t border-b border-gray-200 py-4">
                            <h3 class="text-lg font-semibold mb-2">Размеры:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->sizes as $productSize)
                                <button type="button" 
                                        class="size-btn px-4 py-2 border rounded hover:bg-black hover:text-white transition-colors {{ $loop->first ? 'bg-black text-white' : '' }} {{ $productSize->quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        data-size="{{ $productSize->size }}"
                                        data-available="{{ $productSize->quantity > 0 ? 'true' : 'false' }}"
                                        data-quantity="{{ $productSize->quantity }}"
                                        {{ $productSize->quantity <= 0 ? 'disabled' : '' }}>
                                    {{ $productSize->size }}
                                    @if($productSize->quantity <= 0)
                                        <span class="text-xs">(нет в наличии)</span>
                                    @endif
                                </button>
                                @endforeach
                            </div>
                            <input type="hidden" name="size" id="selectedSize" value="{{ $product->sizes->first()->size ?? '' }}">
                            
                            <div class="mt-2 text-sm">
                                <span class="text-gray-600">В наличии: </span>
                                <span id="availableQuantity" class="font-medium">
                                    {{ $product->sizes->first() ? $product->sizes->first()->quantity : 0 }} шт.
                                </span>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <h3 class="text-lg font-semibold">Количество:</h3>
                            <div class="flex w-1/3">
                                <button type="button" id="decrement" class="px-3 py-1 border rounded-l">-</button>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full text-center border-t border-b">
                                <button type="button" id="increment" class="px-3 py-1 border rounded-r">+</button>
                            </div>
                        </div>

                        <div class="space-y-2 mb-4">
                            <h3 class="text-lg font-semibold">Описание:</h3>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                        <button type="submit" class="w-full bg-black text-white py-3 rounded hover:bg-gray-800 transition-colors">
                            Добавить в корзину
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Категории -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-bold mb-4">Категории</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($product->categories as $category)
                <a href="{{ route('products.index', ['categories[]' => $category->id]) }}" 
                   class="px-3 py-1 bg-gray-200 rounded-full text-sm text-gray-700 hover:bg-gray-300">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Похожие товары -->
        @if($relatedProducts->isNotEmpty())
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-bold mb-4">Похожие товары</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <a href="{{ route('products.show', $relatedProduct->id) }}">
                        <div class="aspect-square bg-gray-200">
                            @if($relatedProduct->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $relatedProduct->images[0]->image_url) }}" 
                                     class="w-full h-full object-cover" alt="{{ $relatedProduct->name }}">
                            @else
                                <img src="https://via.placeholder.com/300" 
                                     class="w-full h-full object-cover" alt="{{ $relatedProduct->name }}">
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-medium text-sm mb-1">{{ $relatedProduct->name }}</h3>
                            <p class="font-bold text-sm">{{ number_format($relatedProduct->price, 0, '.', ' ') }} ₽</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // Смена основного изображения
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
    }

    // Обработка выбора размера
    document.addEventListener('DOMContentLoaded', function() {
        const sizeButtons = document.querySelectorAll('.size-btn');
        const selectedSizeInput = document.getElementById('selectedSize');
        const availableQuantityElement = document.getElementById('availableQuantity');
        const quantityInput = document.getElementById('quantity');
        
        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.dataset.available === 'true') {
                    // Убираем выделение со всех кнопок
                    sizeButtons.forEach(btn => {
                        btn.classList.remove('bg-black', 'text-white');
                    });
                    
                    // Выделяем выбранную кнопку
                    this.classList.add('bg-black', 'text-white');
                    
                    // Обновляем скрытое поле
                    selectedSizeInput.value = this.dataset.size;
                    
                    // Обновляем отображение доступного количества
                    availableQuantityElement.textContent = this.dataset.quantity + ' шт.';
                    
                    // Сбрасываем количество в форме до 1, если выбрали больше чем есть
                    const maxAvailable = parseInt(this.dataset.quantity);
                    if (parseInt(quantityInput.value) > maxAvailable) {
                        quantityInput.value = 1;
                    }
                    
                    // Обновляем максимально доступное количество
                    quantityInput.setAttribute('max', maxAvailable);
                }
            });
        });
        
        // Установка начального max количества
        if (sizeButtons.length > 0) {
            const firstButton = sizeButtons[0];
            const initialQuantity = firstButton.dataset.quantity;
            quantityInput.setAttribute('max', initialQuantity);
        }
        
        // Обработка изменения количества
        const decrementBtn = document.getElementById('decrement');
        const incrementBtn = document.getElementById('increment');
        
        decrementBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        
        incrementBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            
            if (currentValue < maxValue) {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            } else {
                // Если пытаемся добавить больше чем есть, показываем сообщение
                alert('Доступно только ' + maxValue + ' шт.');
            }
        });
    });
</script>
@endsection