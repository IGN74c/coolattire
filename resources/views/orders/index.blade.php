@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Мои заказы</h1>
        
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h2 class="text-xl font-semibold mb-2">У вас еще нет заказов</h2>
                <p class="text-gray-600 mb-6">Перейдите в каталог, чтобы выбрать товары</p>
                <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-black text-white rounded hover:bg-gray-800">
                    Перейти в каталог
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">№ заказа</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Статус</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Сумма</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium">{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($order->status == 'новый') bg-blue-100 text-blue-800
                                        @elseif($order->status == 'обработан') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'отправлен') bg-indigo-100 text-indigo-800
                                        @elseif($order->status == 'завершен') bg-green-100 text-green-800
                                        @elseif($order->status == 'отменен') bg-red-100 text-red-800
                                        @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    {{ number_format($order->items->sum(function($item) {
                                        return $item->price * $item->quantity;
                                    }), 0, '.', ' ') }} ₽
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('orders.show', $order) }}" class="text-black hover:underline">
                                        Подробнее
                                    </a>
                                    
                                    @if($order->status == 'новый')
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline-block ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Вы уверены, что хотите отменить заказ?')">
                                                Отменить
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection 