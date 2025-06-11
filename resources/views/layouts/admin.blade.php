<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Cool Attire') }} - Админ-панель</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Боковое меню -->
        <aside id="sidebar"
            class="w-64 bg-black text-white fixed h-screen transition-all duration-300 ease-in-out z-30">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-8">Админ-панель</h1>

                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                        Дашборд
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                        Товары
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                        Категории
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                        Заказы
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="block py-2.5 px-4 rounded hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                        Пользователи
                    </a>
                </nav>
            </div>

            <div class="absolute bottom-0 w-full p-6">
                <a href="{{ route('home') }}" class="block text-gray-400 hover:text-white mb-4">Вернуться на сайт</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-400 hover:text-white">Выйти</button>
                </form>
            </div>
        </aside>

        <!-- Основной контент -->
        <div id="main-content" class="flex-1 ml-64 transition-all duration-300 ease-in-out">
            <!-- Верхняя навигация -->
            <header class="bg-white shadow-md py-4 px-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="mr-4 text-gray-600 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h2 class="text-xl font-semibold">@yield('title', 'Дашборд')</h2>
                    </div>
                    <div class="text-gray-600">
                        Вы вошли как: <span class="font-semibold">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </header>

            <!-- Основной контент -->
            <main class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebar-toggle');

            // Проверяем сохраненное состояние сайдбара
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Применяем сохраненное состояние
            if (sidebarCollapsed) {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-0');
            }

            sidebarToggle.addEventListener('click', function () {
                // Переключаем состояние сайдбара
                sidebar.classList.toggle('-translate-x-full');

                // Изменяем отступ для основного контента
                if (sidebar.classList.contains('-translate-x-full')) {
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-0');
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    mainContent.classList.remove('ml-0');
                    mainContent.classList.add('ml-64');
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });
        });
    </script>
</body>

</html>