@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Заголовок -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Корзина</h1>
            <span class="text-gray-600">{{ $cart->items->count() }} {{ trans_choice('товар|товара|товаров', $cart->items->count()) }}</span>
        </div>

        @if($cart->items->isNotEmpty())
        <!-- Таблица товаров -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Товар</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Цена</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Размер</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Количество</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Сумма</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cart->items as $item)
                    <!-- Строка товара -->
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-lg overflow-hidden">
                                    @if($item->product->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $item->product->images[0]->image_url) }}" 
                                             class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                    @else
                                        <img src="https://via.placeholder.com/100" class="w-full h-full object-cover" 
                                             alt="{{ $item->product->name }}">
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-medium">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="hover:underline">
                                            {{ $item->product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600">Артикул: {{ $item->product->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">{{ number_format($item->product->price, 0, '.', ' ') }} ₽</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-block px-3 py-1 bg-gray-100 rounded">{{ $item->size }}</span>
                            @php
                                $currentSize = $item->product->sizes->where('size', $item->size)->first();
                                $availableQuantity = $currentSize ? $currentSize->quantity : 0;
                            @endphp
                            <div class="text-xs text-gray-500 mt-1">
                                В наличии: {{ $availableQuantity }} шт.
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-medium">{{ $item->quantity }} шт.</span>
                        </td>
                        <td class="px-6 py-4 text-center font-medium">
                            {{ number_format($item->product->price * $item->quantity, 0, '.', ' ') }} ₽
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('cart.remove', $item->product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="size" value="{{ $item->size }}">
                                <button type="submit" class="text-black hover:text-gray-600">
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Итого и кнопки действий -->
        <div class="mt-8 flex justify-between items-start">
            <div>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition-colors">
                    Продолжить покупки
                </a>
            </div>
            
            <div class="w-64">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Итого:</span>
                    <span class="text-xl font-bold">{{ number_format($cart->items->sum(function($item) { return $item->product->price * $item->quantity; }), 0, '.', ' ') }} ₽</span>
                </div>
                
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-black text-white py-3 rounded hover:bg-gray-800 transition-colors">
                        Оформить заказ
                    </button>
                </form>
                
                <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition-colors">
                        Очистить корзину
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-xl font-semibold mb-2">Ваша корзина пуста</h2>
            <p class="text-gray-600 mb-6">Добавьте товары в корзину, чтобы оформить заказ</p>
            <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-black text-white rounded hover:bg-gray-800">
                Перейти в каталог
            </a>
        </div>
        @endif
    </div>
</div>
@endsection 