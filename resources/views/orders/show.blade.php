@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Заказ #{{ $order->id }}</h1>
                <p class="text-gray-600">Оформлен {{ $order->created_at->format('d.m.Y в H:i') }}</p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <span class="pl-3 pr-2 py-1 rounded-full text-sm
                    @if($order->status == 'новый') bg-blue-100 text-blue-800
                    @elseif($order->status == 'обработан') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'отправлен') bg-indigo-100 text-indigo-800
                    @elseif($order->status == 'завершен') bg-green-100 text-green-800
                    @elseif($order->status == 'отменен') bg-red-100 text-red-800
                    @endif">
                    {{ $order->status }}
                </span>
                
                @if($order->status == 'новый')
                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline-block ml-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Вы уверены, что хотите отменить заказ?')">
                            Отменить заказ
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Таблица товаров -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Товар</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Цена</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Размер</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Количество</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Сумма</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-lg overflow-hidden">
                                        @if($item->product && $item->product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $item->product->images[0]->image_url) }}" 
                                                class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                        @else
                                            <img src="https://via.placeholder.com/100" class="w-full h-full object-cover" 
                                                alt="{{ $item->product ? $item->product->name : 'Товар' }}">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="font-medium">
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product->id) }}" class="hover:underline">
                                                    {{ $item->product->name }}
                                                </a>
                                            @else
                                                Товар удален
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600">Артикул: {{ $item->product_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                            <td class="px-6 py-4 text-center">{{ $item->size }}</td>
                            <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-right font-medium">
                                {{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Итоговая информация -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="max-w-xl ml-auto space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Промежуточный итог:</span>
                    <span class="font-medium">
                        {{ number_format($order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        }), 0, '.', ' ') }} ₽
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Доставка:</span>
                    <span class="font-medium">Бесплатно</span>
                </div>
                <div class="flex justify-between text-xl font-bold">
                    <span>Итого:</span>
                    <span>
                        {{ number_format($order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        }), 0, '.', ' ') }} ₽
                    </span>
                </div>
            </div>

            <div class="mt-8 flex flex-col md:flex-row gap-4 justify-between">
                <a href="{{ route('orders.index') }}" class="px-6 py-3 border rounded hover:bg-gray-100 text-center">
                    Вернуться к списку заказов
                </a>
                <a href="{{ route('products.index') }}" class="px-6 py-3 bg-black text-white rounded hover:bg-gray-800 text-center">
                    Продолжить покупки
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 