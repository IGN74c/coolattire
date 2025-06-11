@extends('layouts.admin')

@section('title', 'Управление заказами')

@section('content')
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Заказы</h1>
        <div class="text-gray-600">Всего: {{ $orders->total() }}</div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">№</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Статус</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Товаров</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Сумма</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.users.show', $order->user) }}" class="text-blue-600 hover:underline">
                            {{ $order->user->email }}
                        </a>
                    </td>
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
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        {{ $order->items->sum('quantity') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        {{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 0, '.', ' ') }} ₽
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $order->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                            Детали
                        </a>
                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                    onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection 