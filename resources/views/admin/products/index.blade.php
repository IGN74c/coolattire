@extends('layouts.admin')

@section('title', 'Управление товарами')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Товары</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
        Добавить товар
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Изображение</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Цена</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Категории</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $product->id }}</td>
                    <td class="px-6 py-4">
                        <div class="h-16 w-16 bg-gray-200 rounded-lg overflow-hidden">
                            @if($product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $product->images[0]->image_url) }}" 
                                     class="w-full h-full object-cover" alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    Нет фото
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">
                            {{ $product->name }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-center">{{ number_format($product->price, 0, '.', ' ') }} ₽</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-wrap gap-1 justify-center">
                            @foreach($product->categories as $category)
                                <span class="inline-block px-2 py-1 text-xs bg-gray-200 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">
                            Редактировать
                        </a>
                        
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Вы уверены, что хотите удалить этот товар?')">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            
            @if($products->isEmpty())
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Товары не найдены
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection 