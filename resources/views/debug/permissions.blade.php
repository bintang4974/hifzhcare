@extends('layouts.app-enhanced')

@section('title', 'Debug - User Permissions')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">🔍 Debug User Permissions</h1>

    <div class="grid grid-cols-2 gap-6">
        <!-- User Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">👤 User Info</h2>
            <dl class="space-y-2">
                <div>
                    <dt class="font-semibold text-gray-600">ID:</dt>
                    <dd>{{ auth()->id() }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">Name:</dt>
                    <dd>{{ auth()->user()->name }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">Email:</dt>
                    <dd>{{ auth()->user()->email }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-gray-600">Status:</dt>
                    <dd>
                        @if (auth()->user()->status === 'active')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded">✅ Active</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded">❌ {{ auth()->user()->status }}</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Roles -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">👨‍💼 Roles</h2>
            @if (auth()->user()->getRoleNames()->isNotEmpty())
                <ul class="space-y-2">
                    @foreach (auth()->user()->getRoleNames() as $role)
                        <li class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm">{{ $role }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-red-600">❌ User has no roles assigned!</div>
            @endif
        </div>

        <!-- Key Permissions -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-xl font-bold mb-4">🔐 Key Permissions</h2>
            <div class="grid grid-cols-3 gap-4">
                @php
                    $keyPermissions = [
                        'manage_classes',
                        'create_classes',
                        'edit_classes',
                        'delete_classes',
                        'manage_users',
                        'manage_pesantren',
                    ];
                @endphp

                @foreach ($keyPermissions as $perm)
                    <div class="flex items-center gap-2 p-3 border rounded">
                        @if (auth()->user()->can($perm))
                            <span class="text-green-600">✅</span>
                            <span class="font-semibold">{{ $perm }}</span>
                        @else
                            <span class="text-red-600">❌</span>
                            <span class="font-semibold text-gray-400">{{ $perm }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- All Permissions -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-xl font-bold mb-4">📋 All Permissions</h2>
            @if (auth()->user()->getAllPermissions()->isNotEmpty())
                <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto">
                    @foreach (auth()->user()->getAllPermissions()->pluck('name') as $perm)
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $perm }}</span>
                    @endforeach
                </div>
            @else
                <div class="text-red-600">❌ User has no permissions assigned!</div>
            @endif
        </div>

        <!-- Action Links -->
        <div class="bg-white rounded-lg shadow p-6 col-span-2">
            <h2 class="text-xl font-bold mb-4">🔗 Test Links</h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('classes.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    📚 Classes Index
                </a>
                @if (auth()->user()->can('manage_classes'))
                    <a href="{{ route('classes.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        ➕ Create Class
                    </a>
                @else
                    <button disabled class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                        ➕ Create Class (No Permission)
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Debug Info -->
    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
        <p class="text-sm text-yellow-800">
            <strong>💡 Tips:</strong> If permissions aren't showing correctly, try:
            <ol class="list-decimal list-inside mt-2 space-y-1">
                <li>Logout and login again</li>
                <li>Clear browser cache (Ctrl+Shift+Delete)</li>
                <li>Run: <code class="bg-yellow-100 px-2 py-1 rounded">php artisan cache:clear</code></li>
            </ol>
        </p>
    </div>
</div>
@endsection
