@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6 text-center">Вход в аккаунт</h1>
        
        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('email') border-red-500 @enderror" 
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-gray-700 mb-2">Пароль</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:border-black @error('password') border-red-500 @enderror" 
                       required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span class="ml-2 text-gray-700">Запомнить меня</span>
                </label>
            </div>
            
            <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800">
                Войти
            </button>
        </form>
        
        <div class="mt-6 text-center">
            <p class="text-gray-600">
                Еще нет аккаунта? 
                <a href="{{ route('register') }}" class="text-black hover:underline">
                    Зарегистрироваться
                </a>
            </p>
        </div>
    </div>
</div>
@endsection 