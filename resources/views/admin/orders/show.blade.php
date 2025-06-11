@extends('layouts.admin')

@section('title', 'Просмотр заказа #' . $order->id)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">
        &larr; Назад к списку заказов
    </a>
</div>

@if(session('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

<!-- Основная информация о заказе -->
<div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold">Информация о заказе #{{ $order->id }}</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 mb-1">Статус:</p>
                <span class="px-3 py-1 text-sm rounded-full inline-block
                    @if($order->status == 'новый') bg-blue-100 text-blue-800
                    @elseif($order->status == 'обработан') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'отправлен') bg-indigo-100 text-indigo-800
                    @elseif($order->status == 'завершен') bg-green-100 text-green-800
                    @elseif($order->status == 'отменен') bg-red-100 text-red-800
                    @endif">
                    {{ $order->status }}
                </span>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Дата заказа:</p>
                <p class="font-medium">{{ $order->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Дата обновления:</p>
                <p class="font-medium">{{ $order->updated_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-600 mb-1">Пользователь:</p>
                <p class="font-medium">
                    <a href="{{ route('admin.users.show', $order->user) }}" class="text-blue-600 hover:underline">
                        {{ $order->user->name }} ({{ $order->user->email }})
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Форма для обновления статуса -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="font-medium mb-3">Изменить статус заказа</h3>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center space-x-4">
                @csrf
                @method('PATCH')
                <select name="status" class="border border-gray-300 rounded px-3 py-2 w-full md:w-64">
                    <option value="новый" {{ $order->status == 'новый' ? 'selected' : '' }}>Новый</option>
                    <option value="обработан" {{ $order->status == 'обработан' ? 'selected' : '' }}>Обработан</option>
                    <option value="отправлен" {{ $order->status == 'отправлен' ? 'selected' : '' }}>Отправлен</option>
                    <option value="завершен" {{ $order->status == 'завершен' ? 'selected' : '' }}>Завершен</option>
                    <option value="отменен" {{ $order->status == 'отменен' ? 'selected' : '' }}>Отменен</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Обновить статус
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Товары в заказе -->
<div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <h2 class="text-xl font-semibold">Товары в заказе</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Товар</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Размер</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Цена</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Количество</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Сумма</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded overflow-hidden">
                                @if($item->product && $item->product->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $item->product->images[0]->image_url) }}" 
                                         class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                @if($item->product)
                                    <a href="{{ route('admin.products.edit', $item->product) }}" class="text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                @else
                                    <span class="text-gray-500">Товар удален</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $item->size }}</td>
                    <td class="px-6 py-4 text-center">{{ number_format($item->price, 0, '.', ' ') }} ₽</td>
                    <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-right">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-right font-medium">Итого:</td>
                    <td class="px-6 py-4 text-right font-medium">
                        {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 0, '.', ' ') }} ₽
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Действия с заказом -->
<div class="flex justify-end">
    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" 
                onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">
            Удалить заказ
        </button>
    </form>
</div>
@endsection 