<!-- Stakeholder Report Sidebar Navigation -->
<div class="bg-white rounded-2xl shadow-lg p-6 h-fit sticky top-6">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Laporan
        </h3>
        <div class="w-full h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
    </div>

    <!-- Navigation Items -->
    <nav class="space-y-3">
        <!-- Dashboard -->
        <a href="{{ route('stakeholder.dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('stakeholder.dashboard') ? 'bg-blue-50 text-blue-700 shadow-md border-l-4 border-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i
                class="fas fa-home mr-3 text-lg {{ request()->routeIs('stakeholder.dashboard') ? 'text-blue-600' : 'text-gray-400' }}"></i>
            <span>Dashboard</span>
            @if (request()->routeIs('stakeholder.dashboard'))
                <i class="fas fa-check-circle ml-auto text-blue-600"></i>
            @endif
        </a>

        <!-- Financial Summary -->
        <a href="{{ route('stakeholder.financial-summary') }}"
            class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('stakeholder.financial-summary') ? 'bg-green-50 text-green-700 shadow-md border-l-4 border-green-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i
                class="fas fa-wallet mr-3 text-lg {{ request()->routeIs('stakeholder.financial-summary') ? 'text-green-600' : 'text-gray-400' }}"></i>
            <span>Financial Summary</span>
            @if (request()->routeIs('stakeholder.financial-summary'))
                <i class="fas fa-check-circle ml-auto text-green-600"></i>
            @endif
        </a>

        <!-- Performance Analysis -->
        <a href="{{ route('stakeholder.performance-analysis') }}"
            class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('stakeholder.performance-analysis') ? 'bg-purple-50 text-purple-700 shadow-md border-l-4 border-purple-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i
                class="fas fa-chart-bar mr-3 text-lg {{ request()->routeIs('stakeholder.performance-analysis') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Performance Analysis</span>
            @if (request()->routeIs('stakeholder.performance-analysis'))
                <i class="fas fa-check-circle ml-auto text-purple-600"></i>
            @endif
        </a>

        <!-- Trend Analysis -->
        <a href="{{ route('stakeholder.trend-analysis') }}"
            class="flex items-center px-4 py-3 rounded-xl font-medium text-sm transition-all {{ request()->routeIs('stakeholder.trend-analysis') ? 'bg-orange-50 text-orange-700 shadow-md border-l-4 border-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
            <i
                class="fas fa-chart-line mr-3 text-lg {{ request()->routeIs('stakeholder.trend-analysis') ? 'text-orange-600' : 'text-gray-400' }}"></i>
            <span>Trend Analysis</span>
            @if (request()->routeIs('stakeholder.trend-analysis'))
                <i class="fas fa-check-circle ml-auto text-orange-600"></i>
            @endif
        </a>
    </nav>

    <!-- Divider -->
    <div class="my-6 border-t border-gray-200"></div>

    <!-- Info Box -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
        <div class="flex items-start">
            <i class="fas fa-lightbulb text-blue-600 mr-3 mt-1 flex-shrink-0"></i>
            <div>
                <p class="text-xs font-semibold text-blue-900 mb-1">Tips:</p>
                <p class="text-xs text-blue-700">
                    Gunakan filter tanggal untuk membandingkan data periode berbeda. Tekan tombol cetak untuk mengunduh laporan.
                </p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 space-y-2">
        <button onclick="window.print()" 
            class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium text-sm transition-all">
            <i class="fas fa-print mr-2"></i>
            <span>Cetak Laporan</span>
        </button>
        <a href="{{ route('stakeholder.export') }}" 
            class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium text-sm transition-all">
            <i class="fas fa-download mr-2"></i>
            <span>Export PDF</span>
        </a>
    </div>
</div>
