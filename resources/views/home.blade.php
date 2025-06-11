@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Hero секция -->
    <section class="bg-gray-900 text-white rounded-lg overflow-hidden mb-12">
        <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl font-bold mb-4">Стильная одежда для стильных людей</h1>
                <p class="text-xl mb-6">Откройте для себя последние тренды и найдите свой уникальный стиль с Cool Attire</p>
                <a href="{{ route('products.index') }}" class="inline-block bg-white text-gray-900 font-bold py-3 px-6 rounded-lg hover:bg-gray-200 transition-colors">
                    Смотреть каталог
                </a>
            </div>
        </div>
    </section>

    <!-- Категории -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Категории</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['categories[]' => $category->id]) }}" class="group">
                <div class="bg-gray-200 rounded-lg overflow-hidden aspect-square relative">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                        <h3 class="text-white text-xl font-bold">{{ $category->name }}</h3>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <!-- Новые поступления -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold mb-6">Новые поступления</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="aspect-square bg-gray-200">
                    @if($product->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $product->images[0]->image_url) }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300" class="w-full h-full object-cover" alt="{{ $product->name }}">
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold mb-2">{{ $product->name }}</h3>
                    <div class="flex justify-between items-center">
                        <span class="font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                        <a href="{{ route('products.show', $product->id) }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Преимущества -->
    <section>
        <h2 class="text-3xl font-bold mb-6">Наши преимущества</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Качественные материалы</h3>
                <p class="text-gray-600">Мы используем только лучшие материалы для создания нашей одежды, обеспечивая комфорт и долговечность.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Быстрая доставка</h3>
                <p class="text-gray-600">Доставляем заказы в кратчайшие сроки, чтобы вы могли наслаждаться покупками без долгого ожидания.</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Безопасные платежи</h3>
                <p class="text-gray-600">Мы обеспечиваем безопасность ваших данных и используем надежные системы оплаты для вашего спокойствия.</p>
            </div>
        </div>
    </section>
</div>
@endsection 