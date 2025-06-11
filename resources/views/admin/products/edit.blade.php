@extends('layouts.admin')

@section('title', 'Редактировать товар')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">
            &larr; Назад к списку товаров
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-2xl font-bold mb-6">Редактировать товар</h1>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-gray-700 mb-2">Название товара</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-gray-700 mb-2">Цена (₽)</label>
                    <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('price') border-red-500 @enderror"
                        required>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 mb-2">Описание</label>
                <textarea id="description" name="description" rows="5"
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('description') border-red-500 @enderror"
                    required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Категории</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                class="rounded border-gray-300" {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Размеры и количество</label>
                <div id="sizes-container">
                    @foreach($product->sizes as $index => $size)
                        <div class="size-item grid grid-cols-2 md:grid-cols-4 gap-4 mb-2">
                            <div>
                                <input type="text" name="sizes[{{ $index }}][size]" value="{{ $size->size }}"
                                    placeholder="Размер (напр. M)"
                                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                            <div>
                                <input type="number" name="sizes[{{ $index }}][quantity]" value="{{ $size->quantity }}"
                                    placeholder="Количество" min="0"
                                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                            @if(!$loop->first)
                                <div>
                                    <button type="button"
                                        class="remove-size bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200">
                                        Удалить
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    @if($product->sizes->isEmpty())
                        <div class="size-item grid grid-cols-2 md:grid-cols-4 gap-4 mb-2">
                            <div>
                                <input type="text" name="sizes[0][size]" placeholder="Размер (напр. M)"
                                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                            <div>
                                <input type="number" name="sizes[0][quantity]" placeholder="Количество" min="0"
                                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" id="add-size" class="mt-2 bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">
                    + Добавить размер
                </button>

                @error('sizes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="images" class="block text-gray-700 mb-2">Добавить изображения</label>
                <input type="file" id="images" name="images[]" accept="image/*" multiple
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('images') border-red-500 @enderror">
                <p class="text-gray-600 text-sm mt-1">Вы можете выбрать несколько изображений</p>
                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>

    <!-- Текущие изображения -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold mb-4">Текущие изображения</h2>

        @if($product->images->isEmpty())
            <p class="text-gray-500">У товара нет изображений</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group">
                        <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover"
                                alt="{{ $product->name }}">
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sizesContainer = document.getElementById('sizes-container');
            const addSizeButton = document.getElementById('add-size');
            let sizeIndex = {{ $product->sizes->count() ? $product->sizes->count() : 1 }};

            document.querySelectorAll('.remove-size').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('.size-item').remove();
                });
            });

            addSizeButton.addEventListener('click', function () {
                const sizeItem = document.createElement('div');
                sizeItem.className = 'size-item grid grid-cols-2 md:grid-cols-4 gap-4 mb-2';
                sizeItem.innerHTML = `
                            <div>
                                <input type="text" name="sizes[${sizeIndex}][size]" placeholder="Размер (напр. M)" 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                            <div>
                                <input type="number" name="sizes[${sizeIndex}][quantity]" placeholder="Количество" min="0" 
                                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black" required>
                            </div>
                            <div>
                                <button type="button" class="remove-size bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200">
                                    Удалить
                                </button>
                            </div>
                        `;

                sizesContainer.appendChild(sizeItem);
                sizeIndex++;

                sizeItem.querySelector('.remove-size').addEventListener('click', function () {
                    sizeItem.remove();
                });
            });
        });
    </script>
@endsection