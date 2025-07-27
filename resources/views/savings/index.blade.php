<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Simpanan') }}
        </h2>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <!-- Page Header -->
                    <div class="mb-5 sm:mb-6">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 sm:mb-2">Riwayat Transaksi Simpanan</h1>
                        <p class="text-sm sm:text-base text-gray-600">Lihat semua transaksi simpanan Anda dengan detail lengkap.</p>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
                        @php
                            // Calculate current balances (only completed transactions)
                            $user = Auth::user();
                            $wajibBalance = $user->savingsTransactions()->completed()->where('savings_type', 'wajib')->where('transaction_type', 'setor')->sum('amount');
                            $pokokBalance = $user->savingsTransactions()->completed()->where('savings_type', 'pokok')->where('transaction_type', 'setor')->sum('amount');
                            $sukarelaBalance = $user->savingsTransactions()->completed()->where('savings_type', 'sukarela')->where('transaction_type', 'setor')->sum('amount') -
                                              $user->savingsTransactions()->completed()->where('savings_type', 'sukarela')->where('transaction_type', 'tarik')->sum('amount');
                        @endphp

                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-4 lg:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="p-3 lg:p-4 bg-green-500 rounded-xl mr-4 flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm lg:text-base font-semibold text-green-900 truncate mb-1">Simpanan Pokok</h3>
                                    <p class="text-lg lg:text-2xl font-bold text-green-900 mb-1">Rp {{ number_format($pokokBalance, 0, ',', '.') }}</p>
                                    <p class="text-xs lg:text-sm text-green-700">Rp 100.000 sekali</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 lg:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="p-3 lg:p-4 bg-blue-500 rounded-xl mr-4 flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm lg:text-base font-semibold text-blue-900 truncate mb-1">Simpanan Wajib</h3>
                                    <p class="text-lg lg:text-2xl font-bold text-blue-900 mb-1">Rp {{ number_format($wajibBalance, 0, ',', '.') }}</p>
                                    <p class="text-xs lg:text-sm text-blue-700">Rp 50.000/bulan</p>
                                </div>
                            </div>
                        </div>



                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 lg:p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center">
                                <div class="p-3 lg:p-4 bg-purple-500 rounded-xl mr-4 flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6 lg:w-8 lg:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm lg:text-base font-semibold text-purple-900 truncate mb-1">Simpanan Sukarela</h3>
                                    <p class="text-lg lg:text-2xl font-bold text-purple-900 mb-1">Rp {{ number_format($sukarelaBalance, 0, ',', '.') }}</p>
                                    <p class="text-xs lg:text-sm text-purple-700">Bebas nominal</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 mb-6 sm:mb-8">
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button onclick="Livewire.dispatch('openDepositModal')" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs sm:text-sm text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span>Setor Simpanan</span>
                            </button>
                            <button onclick="window.print()" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs sm:text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                <span>Cetak Laporan</span>
                            </button>
                        </div>

                        @if($sukarelaBalance > 0)
                            <button onclick="Livewire.dispatch('openWithdrawalModal')" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs sm:text-sm text-white uppercase tracking-widest hover:bg-red-800 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                </svg>
                                <span>Tarik Simpanan Sukarela</span>
                            </button>
                        @endif
                    </div>

                    <!-- Filters Section -->
                    <div class="w-full">
                        @livewire('savings-filter')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    @livewire('withdrawal-request-modal')

    <!-- Deposit Modal -->
    @livewire('deposit-request-modal')
</x-app-layout>

