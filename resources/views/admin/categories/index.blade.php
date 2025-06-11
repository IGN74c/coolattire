@extends('layouts.admin')

@section('title', 'Управление категориями')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Категории</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
        Добавить категорию
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Название</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Кол-во товаров</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Действия</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->id }}</td>
                    <td class="px-6 py-4">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-center">{{ $category->products->count() }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">
                            Редактировать
                        </a>
                        
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Вы уверены, что хотите удалить эту категорию?')">
                                Удалить
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            
            @if($categories->isEmpty())
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Категории не найдены
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection 