@extends('layouts.admin')

@section('title', 'Создать категорию')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:underline">
        &larr; Назад к списку категорий
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Создать категорию</h1>
    
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        
        <div class="mb-6">
            <label for="name" class="block text-gray-700 mb-2">Название категории</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('name') border-red-500 @enderror" 
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                Создать
            </button>
        </div>
    </form>
</div>
@endsection 