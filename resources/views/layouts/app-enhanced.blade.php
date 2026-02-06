<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false, darkMode: false }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'HifzhCare') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #3b82f6;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Gradient Background */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        /* Smooth Transitions */
        * {
            transition: all 0.3s ease;
        }

        /* Card Hover Effect */
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Pulse Animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50" :class="{ 'dark': darkMode }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-xl transform transition-transform duration-300 lg:translate-x-0 lg:static"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" x-cloak>
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-6 gradient-bg">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-book-quran text-2xl text-blue-600"></i>
                    </div>
                    <span class="text-xl font-bold text-white">HifzhCare</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- User Info -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ ucfirst(auth()->user()->user_type) }}</p>
                    </div>
                </div>
                @if (auth()->user()->pesantren)
                    <div class="mt-3 px-3 py-2 bg-blue-50 rounded-lg">
                        <p class="text-xs text-blue-600 font-medium flex items-center">
                            <i class="fas fa-mosque mr-2"></i>
                            <span class="truncate">{{ auth()->user()->pesantren->name }}</span>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto" style="max-height: calc(100vh - 240px)">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i
                        class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Dashboard
                </a>

                @php
                    $canSeeHafalan = auth()->check() && (
                        auth()->user()->can('view_all_hafalan') ||
                        auth()->user()->can('view_class_hafalan') ||
                        auth()->user()->can('view_own_hafalan') ||
                        auth()->user()->can('create_hafalan')
                    );
                @endphp

                @if($canSeeHafalan)
                    <!-- Hafalan -->
                    <a href="{{ route('hafalan.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('hafalan.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i
                            class="fas fa-book-open mr-3 {{ request()->routeIs('hafalan.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        Hafalan
                        @if (auth()->check() && auth()->user()->isUstadz() && auth()->user()->ustadzProfile && auth()->user()->ustadzProfile->verifiedHafalans()->where('status', 'pending')->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ auth()->user()->ustadzProfile->verifiedHafalans()->where('status', 'pending')->count() }}
                            </span>
                        @endif
                    </a>
                @endif

                @can('manage_classes')
                    <!-- Classes -->
                    <a href="{{ route('classes.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('classes.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i
                            class="fas fa-chalkboard mr-3 {{ request()->routeIs('classes.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        Kelas
                    </a>
                @endcan

                @php
                    $canUserMenu = auth()->check() && (
                        auth()->user()->can('manage_users') ||
                        auth()->user()->can('create_users') ||
                        auth()->user()->can('edit_users') ||
                        auth()->user()->can('delete_users') ||
                        auth()->user()->can('activate_users')
                    );
                @endphp

                @if($canUserMenu)
                    <!-- Users Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                            <div class="flex items-center">
                                <i
                                    class="fas fa-users mr-3 {{ request()->routeIs('users.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                                Pengguna
                            </div>
                            <i class="fas fa-chevron-down text-xs transform" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-cloak class="ml-4 mt-2 space-y-1">
                            @if(auth()->check() && (auth()->user()->can('create_users') || auth()->user()->can('manage_users') || auth()->user()->can('view_own_hafalan')))
                                <a href="{{ route('users.santri.index') }}"
                                    class="block px-4 py-2 text-sm rounded-lg {{ request()->routeIs('users.santri.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-user-graduate mr-2"></i>Santri
                                </a>
                            @endif

                            @if(auth()->check() && (auth()->user()->can('create_users') || auth()->user()->can('manage_users') || auth()->user()->can('view_class_hafalan')))
                                <a href="{{ route('users.ustadz.index') }}"
                                    class="block px-4 py-2 text-sm rounded-lg {{ request()->routeIs('users.ustadz.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-chalkboard-teacher mr-2"></i>Ustadz
                                </a>
                            @endif

                            @if(auth()->check() && (auth()->user()->can('create_users') || auth()->user()->can('manage_users') || auth()->user()->can('view_own_hafalan')))
                                <a href="{{ route('users.wali.index') }}"
                                    class="block px-4 py-2 text-sm rounded-lg {{ request()->routeIs('users.wali.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <i class="fas fa-user-friends mr-2"></i>Wali
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @can('manage_certificates')
                    <!-- Certificates -->
                    {{-- <a href="{{ route('certificates.index') }}" --}}
                    <a href=""
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('certificates.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i
                            class="fas fa-certificate mr-3 {{ request()->routeIs('certificates.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        Sertifikat
                    </a>
                @endcan

                @can('view_reports')
                    <!-- Reports -->
                    {{-- <a href="{{ route('reports.index') }}" --}}
                    <a href=""
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i
                            class="fas fa-chart-bar mr-3 {{ request()->routeIs('reports.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                        Laporan
                    </a>
                @endcan

                <div class="border-t border-gray-200 my-4"></div>

                <!-- Settings -->
                {{-- <a href="{{ route('settings.profile') }}" --}}
                <a href=""
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all {{ request()->routeIs('settings.*') ? 'bg-blue-50 text-blue-700 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i
                        class="fas fa-cog mr-3 {{ request()->routeIs('settings.*') ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    Pengaturan
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center px-4 py-3 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-all">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Keluar
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
                <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Breadcrumb -->
                    <div class="hidden lg:block">
                        <nav class="flex items-center space-x-2 text-sm">
                            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                                <i class="fas fa-home"></i>
                            </a>
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <span class="text-gray-900 font-medium">@yield('breadcrumb', 'Dashboard')</span>
                        </nav>
                    </div>

                    <!-- Right Actions -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#"
                                        class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">Hafalan baru menunggu verifikasi</p>
                                                <p class="text-xs text-gray-500 mt-1">5 menit yang lalu</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="px-4 py-2 text-center border-t border-gray-200">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                        Lihat semua notifikasi
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm shadow">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <i class="fas fa-chevron-down text-xs text-gray-600 hidden lg:block"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                                {{-- <a href="{{ route('settings.profile') }}" --}}
                                <a href=""
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                {{-- <a href="{{ route('settings.password') }}" --}}
                                <a href=""
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-key mr-2"></i>Ubah Password
                                </a>
                                <div class="border-t border-gray-200 my-2"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mx-4 lg:mx-8 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div
                        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-xl"></i>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mx-4 lg:mx-8 mt-4" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                    <div
                        class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 px-4 lg:px-8 py-6">
                <div class="max-w-7xl mx-auto w-full">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-4 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} HifzhCare. All rights reserved.</p>
                    <p class="mt-2 lg:mt-0">Made with <i class="fas fa-heart text-red-500"></i> for Quran memorization
                    </p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" x-cloak></div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>

</html>
