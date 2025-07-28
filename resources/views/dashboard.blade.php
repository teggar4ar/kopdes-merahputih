<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Welcome Message -->
                    <div class="mb-8">
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            Selamat datang, {{ Auth::user()->name }}!
                        </h1>
                        <p class="text-gray-600">Berikut adalah ringkasn aktivitas koperasi Anda.</p>
                    </div>

                    <!-- Savings Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
                        <!-- Principal Savings (One-time) -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 p-3 bg-green-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-sm font-semibold text-green-900">Simpanan Pokok</h3>
                                        @if($savingsSummary['has_paid_principal'])
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                Belum Bayar
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-2xl font-bold text-green-900 mb-1">
                                        Rp {{ number_format($savingsSummary['pokok_savings'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-green-700">
                                        @if($savingsSummary['has_paid_principal'])
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Pembayaran pokok telah selesai
                                            </span>
                                        @else
                                            <span class="flex items-center text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                Target: Rp 100.000 (bayar sekali)
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mandatory Savings (Monthly) -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 p-3 bg-blue-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-sm font-semibold text-blue-900">Simpanan Wajib</h3>
                                        @if($savingsSummary['has_current_month_mandatory_payment'])
                                            @if($savingsSummary['is_current_on_mandatory'])
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Lancar
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Ada Tunggakan
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Bulan Ini Belum
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-2xl font-bold text-blue-900 mb-1">
                                        Rp {{ number_format($savingsSummary['wajib_savings'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-blue-700">
                                        {{ $savingsSummary['mandatory_payments_count'] }} dari {{ $savingsSummary['expected_mandatory_payments'] }} bulan • Rp 50.000/bulan
                                        @if($savingsSummary['missing_mandatory_payments'] > 0)
                                            <br><span class="text-red-600 font-medium">Kurang {{ $savingsSummary['missing_mandatory_payments'] }} bulan</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Voluntary Savings -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 p-3 bg-purple-500 rounded-xl shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-sm font-semibold text-purple-900">Simpanan Sukarela</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8zm1 4a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                            </svg>
                                            Fleksibel
                                        </span>
                                    </div>
                                    <p class="text-2xl font-bold text-purple-900 mb-1">
                                        Rp {{ number_format($savingsSummary['sukarela_savings'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-purple-700">
                                        @if($savingsSummary['sukarela_savings'] > 0)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Menabung sesuai kemampuan
                                            </span>
                                        @else
                                            <span class="flex items-center text-purple-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                                                </svg>
                                                Tambah simpanan kapan saja
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Savings -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-lg">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">Total Simpanan</h3>
                                    <p class="text-2xl font-bold text-gray-900 mb-1">
                                        Rp {{ number_format($savingsSummary['total_savings'], 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500">Akumulasi semua simpanan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status Notifications -->
                    @if($savingsSummary['has_paid_principal'] && $savingsSummary['has_current_month_mandatory_payment'] && $savingsSummary['is_current_on_mandatory'])
                        <!-- All Payments Up to Date -->
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-8">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">
                                        Semua Simpanan Wajib Sudah Terpenuhi
                                    </h3>
                                    <div class="mt-1 text-sm text-green-700">
                                        <p>Selamat! Anda telah memenuhi semua kewajiban simpanan. Simpanan Pokok telah lunas dan Simpanan Wajib bulanan sudah terbayar.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(!$savingsSummary['has_paid_principal'] || !$savingsSummary['has_current_month_mandatory_payment'] || !$savingsSummary['is_current_on_mandatory'])
                        <div class="space-y-4 mb-8">
                            <!-- Principal Savings Alert -->
                            @if(!$savingsSummary['has_paid_principal'])
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">
                                                Simpanan Pokok Belum Dibayar
                                            </h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                <p>Anda belum membayar Simpanan Pokok sebesar <strong>Rp 100.000</strong>. Pembayaran ini bersifat wajib dan hanya dilakukan sekali sebagai syarat keanggotaan koperasi.</p>
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('savings.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-red-800 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Bayar Sekarang
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Current Month Mandatory Payment Alert -->
                            @if(!$savingsSummary['has_current_month_mandatory_payment'] && $savingsSummary['has_paid_principal'])
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">
                                                Simpanan Wajib Bulan {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('F Y') }} Belum Dibayar
                                            </h3>
                                            <div class="mt-2 text-sm text-yellow-700">
                                                <p>Anda belum membayar Simpanan Wajib untuk bulan ini sebesar <strong>Rp 50.000</strong>. Pembayaran ini dilakukan setiap bulan dan penting untuk menjaga status keanggotaan yang baik.</p>
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('savings.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Bayar Simpanan Wajib
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Mandatory Payment Arrears Alert -->
                            @if(!$savingsSummary['is_current_on_mandatory'] && $savingsSummary['missing_mandatory_payments'] > 0)
                                <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-orange-800">
                                                Tunggakan Simpanan Wajib
                                            </h3>
                                            <div class="mt-2 text-sm text-orange-700">
                                                <p>Anda memiliki tunggakan Simpanan Wajib sebanyak <strong>{{ $savingsSummary['missing_mandatory_payments'] }} bulan</strong> dengan total <strong>Rp {{ number_format($savingsSummary['missing_mandatory_payments'] * 50000, 0, ',', '.') }}</strong>.</p>
                                                <p class="mt-1">Keanggotaan: {{ $savingsSummary['months_since_membership'] }} bulan | Dibayar: {{ $savingsSummary['mandatory_payments_count'] }} bulan | Kurang: {{ $savingsSummary['missing_mandatory_payments'] }} bulan</p>
                                            </div>
                                            <div class="mt-3">
                                                <a href="{{ route('savings.index') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-orange-800 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                    </svg>
                                                    Lunasi Tunggakan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Loan Status and Announcements -->
                    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
                        <!-- Loan Status -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mt-4 sm:mt-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex-shrink-0 p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Status Pinjaman</h2>
                            </div>

                            @if($loanSummary['has_active_loan'])
                                <div class="space-y-3 sm:space-y-4">
                                    <!-- Mobile-friendly info grid -->
                                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                                        <div class="bg-gray-50 rounded-lg p-3 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Jumlah Pinjaman:</span>
                                            <span class="font-semibold text-gray-900">{{ $loanSummary['formatted_principal'] }}</span>
                                        </div>
                                        <div class="bg-red-50 rounded-lg p-3 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Sisa Pinjaman:</span>
                                            <span class="font-semibold text-red-600">{{ $loanSummary['formatted_remaining'] }}</span>
                                        </div>
                                        <div class="bg-blue-50 rounded-lg p-3 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Cicilan per Bulan:</span>
                                            <span class="font-semibold text-blue-600">{{ $loanSummary['formatted_monthly_payment'] }}</span>
                                        </div>
                                        <div class="bg-gray-50 rounded-lg p-3 flex justify-between items-center">
                                            <span class="text-sm text-gray-600">Cicilan Ke:</span>
                                            <span class="font-semibold text-gray-900">{{ $loanSummary['next_installment_number'] }} dari {{ $loanSummary['total_installments'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                                            <span>Progress Pembayaran</span>
                                            <span class="font-medium">{{ $loanSummary['progress_percentage'] }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3">
                                            <div class="bg-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $loanSummary['progress_percentage'] }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="pt-4 border-t border-gray-200 space-y-3 sm:space-y-0 sm:flex sm:space-x-3">
                                        <a href="{{ route('loans.show', $loanSummary['loan']) }}"
                                           class="w-full sm:flex-1 inline-flex items-center justify-center px-4 py-3 sm:px-3 sm:py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 touch-manipulation">
                                            <svg class="w-4 h-4 mr-2 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            <span class="hidden sm:inline">Lihat Detail & Cicilan</span>
                                            <span class="sm:hidden">Detail & Cicilan</span>
                                        </a>
                                        <a href="{{ route('loans.index') }}"
                                           class="w-full sm:flex-1 inline-flex items-center justify-center px-4 py-3 sm:px-3 sm:py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 touch-manipulation">
                                            <svg class="w-4 h-4 mr-2 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            <span class="hidden sm:inline">Kelola Pinjaman</span>
                                            <span class="sm:hidden">Kelola</span>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-6 sm:py-8">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">{{ $loanSummary['message'] }}</h3>
                                    <p class="mt-1 text-sm text-gray-500 text-center">Anda dapat mengajukan pinjaman baru kapan saja.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('loans.index') }}"
                                           class="inline-flex items-center px-4 py-3 sm:px-3 sm:py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 touch-manipulation w-full sm:w-auto justify-center">
                                            <svg class="w-4 h-4 mr-2 sm:mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            Ajukan Pinjaman
                                        </a>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Announcements -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex-shrink-0 p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900">Pengumuman Terbaru</h2>
                            </div>

                            @if($announcements->count() > 0)
                                <div class="space-y-4">
                                    @foreach($announcements as $announcement)
                                        <div class="border-l-4 border-blue-500 pl-4 py-3 bg-blue-50/30 rounded-r-lg p-4">
                                            <h3 class="font-medium text-gray-900 mb-1">{{ $announcement->title }}</h3>
                                            <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($announcement->content, 120) }}</p>
                                            <p class="text-xs text-gray-500 mt-2 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $announcement->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengumuman</h3>
                                    <p class="mt-1 text-sm text-gray-500">Pengumuman terbaru akan ditampilkan di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
