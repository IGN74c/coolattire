@extends('layouts.admin')

@section('title', 'Информация о пользователе')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">
            &larr; Назад к списку пользователей
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Информация о пользователе -->
    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold">Информация о пользователе</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-gray-600 mb-1">ID:</p>
                    <p class="font-medium">{{ $user->id }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Email:</p>
                    <p class="font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 mb-1">Дата регистрации:</p>
                    <p class="font-medium">{{ $user->created_at->format('d.m.Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                    onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">
                    Удалить пользователя
                </button>
            </form>
        </div>
    </div>

    <!-- Заказы пользователя -->
    <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold">Заказы пользователя</h2>
        </div>
        @if($user->orders->isEmpty())
            <div class="p-6 text-center text-gray-500">
                У пользователя нет заказов
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Сумма</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($order->items->sum(function ($item) {
                            return $item->price * $item->quantity; }), 0, '.', ' ') }}
                                        ₽
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">
                                            Подробнее
                                        </a>
                                    </td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Текущая корзина -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold">Текущая корзина</h2>
        </div>
        @if(!$user->cart || $user->cart->items->isEmpty())
            <div class="p-6 text-center text-gray-500">
                Корзина пользователя пуста
            </div>
        @else
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
                        @foreach($user->cart->items as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.products.edit', $item->product_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $item->product->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $item->size }}</td>
                                <td class="px-6 py-4 text-center">{{ number_format($item->product->price, 0, '.', ' ') }} ₽</td>
                                <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right">
                                    {{ number_format($item->product->price * $item->quantity, 0, '.', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right font-medium">Итого:</td>
                            <td class="px-6 py-4 text-right font-medium">
                                {{ number_format($user->cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity; }), 0, '.', ' ') }}
                                ₽
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
@endsection