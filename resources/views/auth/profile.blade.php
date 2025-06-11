@extends('layouts.app')

@section('title', 'Мой профиль')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6">Мой профиль</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('email') border-red-500 @enderror" 
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-2">Новый пароль</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('password') border-red-500 @enderror">
                <p class="text-gray-600 text-sm mt-1">Оставьте пустым, если не хотите менять пароль</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 mb-2">Подтверждение нового пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black">
            </div>
            
            <div class="flex justify-between items-center">
                <button type="submit" class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800">
                    Сохранить изменения
                </button>
                
                <a href="{{ route('orders.index') }}" class="text-gray-600 hover:underline">
                    Мои заказы
                </a>
            </div>
        </form>
        
        <div class="mt-12 pt-6 border-t border-gray-200">
            <h2 class="text-xl font-bold mb-4 text-red-600">Удаление аккаунта</h2>
            <p class="text-gray-600 mb-4">
                После удаления аккаунта все ваши данные будут безвозвратно удалены из системы.
            </p>
            
            <form action="{{ route('profile.delete') }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить свой аккаунт? Это действие невозможно отменить.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 border border-red-600 px-6 py-2 rounded hover:bg-red-50">
                    Удалить аккаунт
                </button>
            </form>
        </div>
    </div>
</div>
@endsection 