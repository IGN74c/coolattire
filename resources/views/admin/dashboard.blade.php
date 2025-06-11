@extends('layouts.admin')

@section('title', 'Дашборд')

@section('content')
<!-- Статистика -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <!-- Общее количество товаров -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Товары</p>
                <h3 class="text-3xl font-bold">{{ $totalProducts }}</h3>
            </div>
            <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.products.index') }}" class="text-sm text-blue-600 hover:underline">Управление товарами →</a>
        </div>
    </div>

    <!-- Общее количество пользователей -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Пользователи</p>
                <h3 class="text-3xl font-bold">{{ $totalUsers }}</h3>
            </div>
            <div class="p-3 bg-green-100 text-green-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:underline">Управление пользователями →</a>
        </div>
    </div>

    <!-- Общее количество заказов -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Заказы</p>
                <h3 class="text-3xl font-bold">{{ $totalOrders }}</h3>
            </div>
            <div class="p-3 bg-purple-100 text-purple-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-purple-600 hover:underline">Управление заказами →</a>
        </div>
    </div>

    <!-- Общая выручка -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 text-sm">Выручка</p>
                <h3 class="text-3xl font-bold">{{ number_format($revenueTotal, 0, '.', ' ') }} ₽</h3>
            </div>
            <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-4 text-sm text-gray-600">
            От завершенных заказов
        </div>
    </div>
</div>

<!-- Блоки с данными -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Последние заказы -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg">Последние заказы</h2>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">№</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Пользователь</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Статус</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Дата</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($latestOrders as $order)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $order->user->email }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center">
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
                                <td class="px-4 py-2 whitespace-nowrap text-right">
                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">
                    Все заказы →
                </a>
            </div>
        </div>
    </div>

    <!-- Новые пользователи -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="font-semibold text-lg">Новые пользователи</h2>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Дата регистрации</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($newUsers as $user)
                            <tr>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">
                                        #{{ $user->id }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-right">
                                    {{ $user->created_at->format('d.m.Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-right">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">
                    Все пользователи →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 