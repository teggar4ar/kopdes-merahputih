<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pinjaman') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">

                    {{-- Header Section --}}
                    <div class="mb-5 sm:mb-6">
                        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                            <div class="text-center sm:text-left">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Pinjaman Saya</h1>
                                <p class="text-sm sm:text-base text-gray-600">Kelola pengajuan dan status pinjaman Anda dengan mudah.</p>
                            </div>
                            <div class="flex justify-center sm:justify-end">
                                @if ($activeLoan)
                                    {{-- User has active loan - show detail button instead --}}
                                    <a href="{{ route('loans.show', $activeLoan) }}"
                                       class="inline-flex items-center justify-center px-4 py-3 sm:px-4 sm:py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-sm sm:text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full sm:w-auto">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Lihat Detail Pinjaman</span>
                                        <span class="sm:hidden">Detail Pinjaman</span>
                                    </a>
                                @else
                                    {{-- No active loan - show apply button --}}
                                    <button
                                        onclick="Livewire.dispatch('openLoanModal')"
                                        class="inline-flex items-center justify-center px-4 py-3 sm:px-4 sm:py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm sm:text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full sm:w-auto"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        <span class="hidden sm:inline">Ajukan Pinjaman</span>
                                        <span class="sm:hidden">Ajukan</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Active Loan Summary --}}
                    @if ($activeLoan)
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 lg:p-6 mb-6 sm:mb-8 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex flex-col space-y-4 sm:flex-row sm:items-start sm:justify-between sm:space-y-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center mb-3 sm:mb-4">
                                        <div class="p-2 lg:p-3 bg-blue-500 rounded-xl mr-3 sm:mr-4 flex-shrink-0 shadow-sm">
                                            <svg class="w-5 h-5 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg lg:text-xl font-semibold text-blue-900">Pinjaman Aktif</h3>
                                    </div>

                                    <!-- Mobile-first responsive grid -->
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 sm:gap-4">
                                        <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                            <span class="text-xs lg:text-sm text-blue-700 block mb-1">Jumlah Pinjaman:</span>
                                            <div class="text-lg lg:text-xl font-bold text-blue-900">Rp {{ number_format($activeLoan->loan_amount, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                            <span class="text-xs lg:text-sm text-blue-700 block mb-1">Cicilan per Bulan:</span>
                                            <div class="text-lg lg:text-xl font-bold text-blue-900">Rp {{ number_format($activeLoan->monthly_installment, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="bg-white bg-opacity-50 rounded-lg p-3 sm:col-span-2 lg:col-span-1">
                                            <span class="text-xs lg:text-sm text-blue-700 block mb-1">Sisa Pembayaran:</span>
                                            <div class="text-lg lg:text-xl font-bold text-orange-600">Rp {{ number_format($activeLoan->remaining_balance, 0, ',', '.') }}</div>
                                        </div>
                                    </div>

                                    @if ($activeLoan->status === 'disbursed')
                                        <div class="mt-4 pt-4 border-t border-blue-200">
                                            <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                                                <div class="text-sm lg:text-base text-blue-700 text-center sm:text-left">
                                                    <strong>Jatuh Tempo Berikutnya:</strong><br class="sm:hidden">
                                                    <span class="sm:ml-2">{{ $activeLoan->first_installment_date ? $activeLoan->first_installment_date->addMonth()->format('d M Y') : 'Belum ditentukan' }}</span>
                                                </div>
                                                <a href="{{ route('loans.show', $activeLoan) }}"
                                                   class="inline-flex items-center justify-center px-4 py-2.5 sm:px-3 sm:py-1.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm sm:text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full sm:w-auto">
                                                    <svg class="w-4 h-4 sm:w-3 sm:h-3 mr-2 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Lihat Detail & Cicilan</span>
                                                    <span class="sm:hidden">Detail & Cicilan</span>
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-4 pt-4 border-t border-blue-200 flex justify-center sm:justify-end">
                                            <a href="{{ route('loans.show', $activeLoan) }}"
                                               class="inline-flex items-center justify-center px-4 py-2.5 sm:px-3 sm:py-1.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm sm:text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full sm:w-auto">
                                                <svg class="w-4 h-4 sm:w-3 sm:h-3 mr-2 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span class="hidden sm:inline">Lihat Detail</span>
                                                <span class="sm:hidden">Detail</span>
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <!-- Status badge - mobile centered, desktop right aligned -->
                                <div class="flex justify-center sm:justify-end sm:ml-4 sm:flex-shrink-0">
                                    <span class="inline-flex items-center px-3 py-1.5 sm:px-2.5 sm:py-0.5 rounded-full text-sm sm:text-xs font-medium
                                        @if($activeLoan->status === 'approved') bg-green-100 text-green-800
                                        @elseif($activeLoan->status === 'disbursed') bg-blue-100 text-blue-800
                                        @elseif($activeLoan->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif
                                    ">
                                        @if($activeLoan->status === 'approved') Disetujui
                                        @elseif($activeLoan->status === 'disbursed') Dicairkan
                                        @elseif($activeLoan->status === 'pending') Menunggu Persetujuan
                                        @else {{ ucfirst($activeLoan->status) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Loan History --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Riwayat Pinjaman</h3>
                            <p class="text-sm text-gray-600 mt-1">Semua pengajuan dan status pinjaman Anda</p>
                        </div>

                        @if ($loans->count() > 0)
                            <!-- Mobile Card View -->
                            <div class="block sm:hidden">
                                <div class="divide-y divide-gray-200">
                                    @foreach ($loans as $loan)
                                        <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900 mb-1">
                                                        Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $loan->application_date->format('d M Y') }} • {{ $loan->loan_term_months }} Bulan
                                                    </div>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                                                    @if($loan->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                                    @elseif($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif($loan->status === 'completed') bg-gray-100 text-gray-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif
                                                ">
                                                    @if($loan->status === 'approved') Disetujui
                                                    @elseif($loan->status === 'disbursed') Dicairkan
                                                    @elseif($loan->status === 'pending') Menunggu
                                                    @elseif($loan->status === 'rejected') Ditolak
                                                    @elseif($loan->status === 'completed') Selesai
                                                    @else {{ ucfirst($loan->status) }}
                                                    @endif
                                                </span>
                                            </div>

                                            <div class="text-xs text-gray-600 mb-3 line-clamp-2">
                                                <strong>Tujuan:</strong> {{ $loan->loan_purpose }}
                                            </div>

                                            <div class="flex justify-end">
                                                <a href="{{ route('loans.show', $loan) }}"
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Desktop Table View -->
                            <div class="hidden sm:block overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal Pengajuan
                                            </th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah
                                            </th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                                Jangka Waktu
                                            </th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                                Tujuan
                                            </th>
                                            <th scope="col" class="relative px-4 sm:px-6 py-3">
                                                <span class="sr-only">Aksi</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($loans as $loan)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $loan->application_date->format('d M Y') }}
                                                </td>
                                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                                                    {{ $loan->loan_term_months }} Bulan
                                                </td>
                                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($loan->status === 'approved') bg-green-100 text-green-800
                                                        @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                                        @elseif($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                                                        @elseif($loan->status === 'completed') bg-gray-100 text-gray-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif
                                                    ">
                                                        @if($loan->status === 'approved') Disetujui
                                                        @elseif($loan->status === 'disbursed') Dicairkan
                                                        @elseif($loan->status === 'pending') Menunggu
                                                        @elseif($loan->status === 'rejected') Ditolak
                                                        @elseif($loan->status === 'completed') Selesai
                                                        @else {{ ucfirst($loan->status) }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-4 sm:px-6 py-4 text-sm text-gray-900 hidden lg:table-cell">
                                                    <div class="max-w-xs truncate">{{ $loan->loan_purpose }}</div>
                                                </td>
                                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('loans.show', $loan) }}"
                                                       class="text-blue-600 hover:text-blue-900 font-medium transition-colors duration-150">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if ($loans->hasPages())
                                <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                                    {{ $loans->links() }}
                                </div>
                            @endif
                        @else
                            <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pinjaman</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Anda belum pernah mengajukan pinjaman. Mulai dengan mengajukan pinjaman pertama Anda.
                                </p>
                                @if (!$activeLoan)
                                    {{-- Only show apply button if no active loan --}}
                                    <div class="mt-6">
                                        <button
                                            onclick="Livewire.dispatch('openLoanModal')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Ajukan Pinjaman
                                        </button>
                                    </div>
                                @else
                                    {{-- Show explanation if user has active loan --}}
                                    <div class="mt-6">
                                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-200 rounded-md text-sm text-yellow-800">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Anda memiliki pinjaman aktif. Selesaikan pembayaran terlebih dahulu sebelum mengajukan pinjaman baru.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Interest Rate Information --}}
                    <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-xl p-4 lg:p-6 shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Informasi Suku Bunga</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Suku bunga pinjaman saat ini: <strong>{{ $interestRate }}% per tahun</strong></p>
                                    <p class="mt-1">Perhitungan bunga menggunakan metode anuitas dengan pembayaran bulanan tetap.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Loan Application Modal --}}
    <livewire:loan-application-modal />
</x-app-layout>
