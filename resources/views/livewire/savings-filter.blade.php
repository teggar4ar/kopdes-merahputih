<div class="w-full">
    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
        <!-- Filter Header with Toggle Button -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Filter Transaksi</h3>
            <button
                wire:click="toggleFilters"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200"
            >
                @if($showFilters)
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                    </svg>
                    Sembunyikan Filter
                @else
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                    Tampilkan Filter
                @endif
            </button>
        </div>

        <!-- Active Filters Summary (when collapsed) -->
        @if(!$showFilters && ($quickFilter || $savingsType || $transactionType || $status))
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-800 mr-2">Filter Aktif:</span>
                    <div class="flex flex-wrap gap-1">
                        @if($quickFilter)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{
                                    collect([
                                        'recent_withdrawals' => 'Penarikan 3 Bulan',
                                        'pending_transactions' => 'Transaksi Pending',
                                        'sukarela_deposits' => 'Setoran Sukarela',
                                        'monthly_principal' => 'Setoran Pokok',
                                        'this_month' => 'Bulan Ini',
                                        'last_30_days' => '30 Hari Terakhir',
                                        'all_completed' => 'Semua Selesai'
                                    ])[$quickFilter] ?? $quickFilter
                                }}
                            </span>
                        @else
                            @if($savingsType)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($savingsType) }}
                                </span>
                            @endif
                            @if($transactionType)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $transactionType === 'setor' ? 'Setoran' : 'Penarikan' }}
                                </span>
                            @endif
                            @if($status)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($status) }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Collapsible Filter Content -->
        <div class="@if(!$showFilters) hidden @endif transition-all duration-300">
            <!-- Quick Filter Dropdown -->
            <div class="mb-4">
                <label for="quickFilter" class="block text-sm font-medium text-gray-700 mb-2">
                    Filter Cepat
                    @if($quickFilter)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                            Aktif
                        </span>
                    @endif
                </label>
                <select
                    wire:model.live="quickFilter"
                    id="quickFilter"
                    class="w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm @if($quickFilter) bg-blue-50 border-blue-200 @endif"
                >
                <option value="">🔍 Pilih Filter Cepat...</option>
                <option value="recent_withdrawals">💸 Penarikan 3 Bulan Terakhir</option>
                <option value="pending_transactions">⏳ Transaksi Menunggu Persetujuan</option>
                <option value="sukarela_deposits">💰 Setoran Simpanan Sukarela</option>
                <option value="monthly_principal">🏦 Setoran Simpanan Pokok</option>
                <option value="this_month">📅 Transaksi Bulan Ini</option>
                <option value="last_30_days">📊 30 Hari Terakhir</option>
                <option value="all_completed">✅ Semua Transaksi Selesai</option>
            </select>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-4"></div>

        <!-- Helper Text -->
        <div class="mb-3">
            <p class="text-xs text-gray-500">
                💡 <strong>Tips:</strong> Gunakan Filter Cepat di atas untuk skenario umum, atau atur filter manual di bawah untuk pencarian yang lebih spesifik.
            </p>
        </div>

        <!-- Manual Filters -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-3 sm:gap-4">
            <!-- Jenis Simpanan Filter -->
            <div>
                <label for="savingsType" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                    Jenis Simpanan
                </label>
                <select
                    wire:model.live="savingsType"
                    id="savingsType"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                >
                    <option value="">Semua Jenis</option>
                    <option value="wajib">Simpanan Wajib</option>
                    <option value="pokok">Simpanan Pokok</option>
                    <option value="sukarela">Simpanan Sukarela</option>
                </select>
            </div>

            <!-- Jenis Transaksi Filter -->
            <div>
                <label for="transactionType" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                    Jenis Transaksi
                </label>
                <select
                    wire:model.live="transactionType"
                    id="transactionType"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                >
                    <option value="">Semua Transaksi</option>
                    <option value="setor">Setoran</option>
                    <option value="tarik">Penarikan</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                    Status
                </label>
                <select
                    wire:model.live="status"
                    id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                >
                    <option value="">Semua Status</option>
                    <option value="completed">Selesai</option>
                    <option value="pending">Menunggu Persetujuan</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>

            <!-- Start Date Filter -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                    Tanggal Mulai
                </label>
                <input
                    type="date"
                    wire:model.live="startDate"
                    id="startDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                >
            </div>

            <!-- End Date Filter -->
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                    Tanggal Akhir
                </label>
                <input
                    type="date"
                    wire:model.live="endDate"
                    id="endDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                >
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
                <button
                    wire:click="clearFilters"
                    class="w-full px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200 text-sm font-medium"
                >
                    Clear Filter
                </button>
            </div>
        </div>
        </div> <!-- End of collapsible filter content -->
    </div>

    <!-- Transactions Table -->
    <div class="w-full bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Mobile Card View (visible on small screens) -->
        <div class="block sm:hidden">
            @forelse($transactions as $transaction)
                <div class="p-4 border-b last:border-b-0 hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-medium text-gray-900">{{ $transaction->transaction_date->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $transaction->transaction_date->format('H:i') }} WIB</div>
                        </div>
                        <div class="flex flex-col items-end space-y-1">
                            @if($transaction->transaction_type === 'setor')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Setoran
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Penarikan
                                </span>
                            @endif
                            @if($transaction->status === 'pending')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            @elseif($transaction->status === 'rejected')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-2">
                        <div>
                            @if($transaction->savings_type === 'wajib')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Wajib
                                </span>
                            @elseif($transaction->savings_type === 'pokok')
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Pokok
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Sukarela
                                </span>
                            @endif
                        </div>
                        <div class="font-medium {{ $transaction->transaction_type === 'setor' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->transaction_type === 'setor' ? '+' : '-' }}
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-xs text-gray-500">
                            {{ $transaction->description ?? '-' }}
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            Saldo: Rp {{ number_format($transaction->running_balance ?? 0, 0, ',', '.') }}
                            @if($transaction->status === 'pending')
                                <span class="text-xs text-yellow-600">(perkiraan)</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-base font-medium">Tidak ada transaksi ditemukan</p>
                    <p class="text-sm">Coba ubah filter atau rentang tanggal</p>
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View (visible on larger screens) -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Simpanan
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis Transaksi
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Saldo
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Keterangan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->transaction_date->format('d/m/Y') }}
                                <div class="text-xs text-gray-500">
                                    {{ $transaction->transaction_date->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($transaction->savings_type === 'wajib')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Simpanan Wajib
                                    </span>
                                @elseif($transaction->savings_type === 'pokok')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Simpanan Pokok
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Simpanan Sukarela
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                @if($transaction->transaction_type === 'setor')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Setoran
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Penarikan
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                @if($transaction->status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu Persetujuan
                                    </span>
                                @elseif($transaction->status === 'rejected')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Selesai
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm">
                                <span class="font-medium {{ $transaction->transaction_type === 'setor' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->transaction_type === 'setor' ? '+' : '-' }}
                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                <div class="text-right">
                                    <span>Rp {{ number_format($transaction->running_balance ?? 0, 0, ',', '.') }}</span>
                                    @if($transaction->status === 'pending')
                                        <div class="text-xs text-yellow-600">(perkiraan)</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 text-center text-sm text-gray-500">
                                {{ $transaction->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 sm:px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada transaksi ditemukan</p>
                                    <p class="text-sm">Coba ubah filter atau rentang tanggal</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 sm:p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 text-sm sm:text-base">Memuat data...</span>
        </div>
    </div>
</div>
