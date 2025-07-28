<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight text-center sm:text-left">
                Detail Pinjaman
            </h2>
            <a href="{{ route('loans.index') }}"
               class="inline-flex items-center justify-center px-4 py-2.5 sm:px-4 sm:py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-sm sm:text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    {{-- Loan Header --}}
                    <div class="border-b border-gray-200 pb-4 sm:pb-6 mb-4 sm:mb-6">
                        <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                            <div class="text-center sm:text-left">
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                                    Pinjaman #{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}
                                </h1>
                                <p class="text-sm text-gray-600 mt-1">
                                    Diajukan pada {{ $loan->application_date->format('d F Y') }}
                                </p>
                            </div>
                            <div class="flex justify-center sm:justify-end">
                                <span class="inline-flex items-center px-3 py-1.5 sm:px-3 sm:py-1 rounded-full text-sm font-medium
                                    @if($loan->status === 'approved') bg-green-100 text-green-800
                                    @elseif($loan->status === 'disbursed') bg-blue-100 text-blue-800
                                    @elseif($loan->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($loan->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($loan->status === 'completed') bg-gray-100 text-gray-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    @if($loan->status === 'approved') ✓ Disetujui
                                    @elseif($loan->status === 'disbursed') 💰 Dicairkan
                                    @elseif($loan->status === 'pending') ⏳ Menunggu Persetujuan
                                    @elseif($loan->status === 'rejected') ❌ Ditolak
                                    @elseif($loan->status === 'completed') ✅ Selesai
                                    @else {{ ucfirst($loan->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile-first responsive layout -->
                    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-3">
                        {{-- Loan Information --}}
                        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                            {{-- Basic Information --}}
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
                                <h3 class="text-lg sm:text-xl font-medium text-gray-900 mb-4 text-center sm:text-left">Informasi Pinjaman</h3>

                                <!-- Mobile-first responsive grid -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4">
                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Jumlah Pinjaman</label>
                                        <div class="text-xl sm:text-lg font-bold text-gray-900">
                                            Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Jangka Waktu</label>
                                        <div class="text-xl sm:text-lg font-bold text-gray-900">
                                            {{ $loan->loan_term_months }} Bulan
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 shadow-sm">
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Suku Bunga</label>
                                        <div class="text-xl sm:text-lg font-bold text-gray-900">
                                            {{ $loan->interest_rate }}% per tahun
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-lg p-4 shadow-sm border-2 border-blue-200">
                                        <label class="block text-sm font-medium text-gray-500 mb-2">Cicilan per Bulan</label>
                                        <div class="text-xl sm:text-lg font-bold text-blue-600">
                                            Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Loan Purpose --}}
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Tujuan Pinjaman</h3>
                                <p class="text-gray-700">{{ $loan->loan_purpose }}</p>
                            </div>

                            {{-- Payment Progress (for active loans) --}}
                            @if (in_array($loan->status, ['disbursed', 'completed']))
                                <div class="bg-gray-50 rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Pembayaran</h3>

                                    {{-- Progress Bar --}}
                                    @php
                                        $progressPercentage = $loan->loan_amount > 0 ? (($loan->total_paid / $loan->loan_amount) * 100) : 0;
                                    @endphp
                                    <div class="mb-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progress Pembayaran</span>
                                            <span>{{ number_format($progressPercentage, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                 style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Total Dibayar</label>
                                            <div class="mt-1 text-lg font-semibold text-green-600">
                                                Rp {{ number_format($loan->total_paid, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Sisa Pembayaran</label>
                                            <div class="mt-1 text-lg font-semibold text-orange-600">
                                                Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Cicilan Tersisa</label>
                                            @php
                                                $remainingPayments = $loan->monthly_installment > 0 ? ceil($loan->remaining_balance / $loan->monthly_installment) : 0;
                                            @endphp
                                            <div class="mt-1 text-lg font-semibold text-gray-900">
                                                {{ $remainingPayments }} Bulan
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Installment Payment Schedule --}}
                                @if ($loan->status === 'disbursed')
                                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                                        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                                            <h3 class="text-lg sm:text-xl font-medium text-gray-900 text-center sm:text-left">Jadwal Pembayaran Cicilan</h3>
                                            <p class="text-sm text-gray-600 mt-1 text-center sm:text-left">Rencana pembayaran per bulan dengan interval 30 hari</p>
                                        </div>

                                        @php
                                            $paidInstallments = $loan->loanInstallments->keyBy(function($installment) {
                                                return $installment->payment_date->format('Y-m');
                                            });

                                            $currentDate = now();
                                            $startDate = $loan->first_installment_date ?: $loan->created_at->addDays(30);

                                            // Find the next unpaid installment
                                            $nextUnpaidMonth = null;
                                            for ($i = 1; $i <= $loan->loan_term_months; $i++) {
                                                $checkDate = $startDate->copy()->addDays(($i - 1) * 30);
                                                $checkKey = $checkDate->format('Y-m');
                                                if (!$paidInstallments->has($checkKey)) {
                                                    $nextUnpaidMonth = $i;
                                                    break;
                                                }
                                            }
                                        @endphp

                                        <!-- Mobile Card View -->
                                        <div class="block sm:hidden">
                                            <div class="divide-y divide-gray-200">
                                                @for ($month = 1; $month <= $loan->loan_term_months; $month++)
                                                    @php
                                                        $dueDate = $startDate->copy()->addDays(($month - 1) * 30);
                                                        $monthKey = $dueDate->format('Y-m');
                                                        $paidInstallment = $paidInstallments->get($monthKey);
                                                        $isPaid = $paidInstallment !== null;
                                                        $isOverdue = !$isPaid && $dueDate->lt($currentDate);
                                                        $isDue = !$isPaid && $dueDate->isSameMonth($currentDate);

                                                        // Check if this installment can be paid
                                                        $canPay = !$isPaid && ($month == $nextUnpaidMonth);
                                                        $isBlocked = !$isPaid && ($month > $nextUnpaidMonth);
                                                    @endphp
                                                    <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                                        <div class="flex items-start justify-between mb-3">
                                                            <div class="flex-1">
                                                                <div class="text-sm font-medium text-gray-900 mb-1">
                                                                    Cicilan {{ $month }}
                                                                </div>
                                                                <div class="text-xs text-gray-500">
                                                                    Jatuh Tempo: {{ $dueDate->format('d M Y') }}
                                                                </div>
                                                                <div class="text-sm font-medium text-gray-900 mt-1">
                                                                    Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}
                                                                </div>
                                                            </div>
                                                            <div class="ml-3">
                                                                @if ($isPaid)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        ✓ Lunas
                                                                    </span>
                                                                @elseif ($isBlocked)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                        🔒 Terkunci
                                                                    </span>
                                                                @elseif ($canPay && $isOverdue)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        ⚠ Terlambat
                                                                    </span>
                                                                @elseif ($canPay && $isDue)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                        📅 Jatuh Tempo
                                                                    </span>
                                                                @elseif ($canPay)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                        💳 Siap Bayar
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                        ⏳ Belum Jatuh Tempo
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        @if ($isPaid)
                                                            <div class="text-xs text-gray-600 mb-3">
                                                                <strong>Dibayar:</strong> {{ $paidInstallment->payment_date->format('d M Y') }} -
                                                                Rp {{ number_format($paidInstallment->amount, 0, ',', '.') }}
                                                            </div>
                                                        @endif

                                                        <div class="flex justify-end">
                                                            @if ($canPay)
                                                                <button
                                                                    type="button"
                                                                    onclick="openPaymentModal({{ $loan->id }}, {{ $month }}, '{{ $dueDate->format('Y-m-d') }}', {{ $loan->monthly_installment }})"
                                                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 touch-manipulation w-full justify-center"
                                                                >
                                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                                    </svg>
                                                                    Bayar Cicilan
                                                                </button>
                                                            @elseif ($isPaid)
                                                                <span class="text-green-600 text-sm font-medium">✓ Sudah Dibayar</span>
                                                            @elseif ($isBlocked)
                                                                <span class="text-gray-500 text-sm">🔒 Bayar cicilan sebelumnya dulu</span>
                                                            @else
                                                                <span class="text-gray-400 text-sm">Belum bisa dibayar</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <!-- Desktop Table View -->
                                        <div class="hidden sm:block overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Bulan
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Jatuh Tempo
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Jumlah Cicilan
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Dibayar
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Status
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Aksi
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @for ($month = 1; $month <= $loan->loan_term_months; $month++)
                                                        @php
                                                            $dueDate = $startDate->copy()->addDays(($month - 1) * 30);
                                                            $monthKey = $dueDate->format('Y-m');
                                                            $paidInstallment = $paidInstallments->get($monthKey);
                                                            $isPaid = $paidInstallment !== null;
                                                            $isOverdue = !$isPaid && $dueDate->lt($currentDate);
                                                            $isDue = !$isPaid && $dueDate->isSameMonth($currentDate);

                                                            // Check if this installment can be paid
                                                            $canPay = !$isPaid && ($month == $nextUnpaidMonth);
                                                            $isBlocked = !$isPaid && ($month > $nextUnpaidMonth);
                                                        @endphp
                                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                Cicilan {{ $month }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $dueDate->format('d M Y') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                Rp {{ number_format($loan->monthly_installment, 0, ',', '.') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                @if ($isPaid)
                                                                    <span class="text-green-600 font-medium">
                                                                        Rp {{ number_format($paidInstallment->amount, 0, ',', '.') }}
                                                                    </span>
                                                                    <div class="text-xs text-gray-500">
                                                                        {{ $paidInstallment->payment_date->format('d M Y') }}
                                                                    </div>
                                                                @else
                                                                    <span class="text-gray-400">-</span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if ($isPaid)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Lunas
                                                                    </span>
                                                                @elseif ($isBlocked)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Terkunci
                                                                    </span>
                                                                @elseif ($canPay && $isOverdue)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Terlambat
                                                                    </span>
                                                                @elseif ($canPay && $isDue)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Jatuh Tempo
                                                                    </span>
                                                                @elseif ($canPay)
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Siap Bayar
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Belum Jatuh Tempo
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                @if ($canPay)
                                                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Bayar Cicilan
                                                                    </button>
                                                                @elseif ($isBlocked)
                                                                    <span class="text-xs text-gray-500 px-2 py-1">
                                                                        🔒 Bayar cicilan sebelumnya dulu
                                                                    </span>
                                                                @else
                                                                    <span class="text-xs text-gray-400 px-2 py-1">
                                                                        Belum bisa dibayar
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Summary Footer --}}
                                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                            @php
                                                $totalPaid = $loan->loanInstallments->sum('amount');
                                                $remainingPayments = $loan->loan_term_months - $loan->loanInstallments->count();
                                            @endphp
                                            <div class="flex justify-between items-center text-sm">
                                                <div class="text-gray-600">
                                                    <span class="font-medium">{{ $loan->loanInstallments->count() }}</span> dari
                                                    <span class="font-medium">{{ $loan->loan_term_months }}</span> cicilan telah dibayar
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-green-600 font-medium">
                                                        Total Dibayar: Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-orange-600 font-medium">
                                                        Sisa: Rp {{ number_format($loan->remaining_balance, 0, ',', '.') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Payment History --}}
                                @if ($loan->loanInstallments->count() > 0)
                                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h3 class="text-lg font-medium text-gray-900">Riwayat Pembayaran</h3>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Tanggal Bayar
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Jumlah
                                                        </th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Diproses Oleh
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($loan->loanInstallments->sortBy('payment_date') as $installment)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                {{ $installment->payment_date->format('d M Y') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                                                Rp {{ number_format($installment->amount, 0, ',', '.') }}
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $installment->processedByAdmin->name ?? 'Admin' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- Approval/Rejection Information --}}
                            @if ($loan->status === 'approved' && $loan->approval_date)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800">Pinjaman Disetujui</h3>
                                            <div class="mt-2 text-sm text-green-700">
                                                <p>Disetujui pada {{ $loan->approval_date->format('d F Y') }}</p>
                                                @if ($loan->approval_notes)
                                                    <p class="mt-1"><strong>Catatan:</strong> {{ $loan->approval_notes }}</p>
                                                @endif
                                                @if ($loan->approvedByAdmin)
                                                    <p class="mt-1">Disetujui oleh: {{ $loan->approvedByAdmin->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($loan->status === 'rejected')
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Pinjaman Ditolak</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                @if ($loan->approval_notes)
                                                    <p><strong>Alasan:</strong> {{ $loan->approval_notes }}</p>
                                                @endif
                                                @if ($loan->approvedByAdmin)
                                                    <p class="mt-1">Diproses oleh: {{ $loan->approvedByAdmin->name }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Sidebar --}}
                        <div class="space-y-6">
                            {{-- Status Timeline --}}
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Status Timeline</h3>
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        {{-- Application Submitted --}}
                                        <li>
                                            <div class="relative pb-8">
                                                <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5">
                                                        <div>
                                                            <p class="text-sm text-gray-500">Pengajuan disubmit</p>
                                                            <p class="text-sm text-gray-900 font-medium">{{ $loan->application_date->format('d M Y H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        {{-- Approval/Rejection --}}
                                        @if (in_array($loan->status, ['approved', 'rejected', 'disbursed', 'completed']))
                                            <li>
                                                <div class="relative pb-8">
                                                    @if (!in_array($loan->status, ['disbursed', 'completed']))
                                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                                @if($loan->status === 'approved' || $loan->status === 'disbursed' || $loan->status === 'completed') bg-green-500
                                                                @else bg-red-500
                                                                @endif
                                                            ">
                                                                @if($loan->status === 'approved' || $loan->status === 'disbursed' || $loan->status === 'completed')
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                @else
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                    </svg>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5">
                                                            <div>
                                                                @if($loan->status === 'approved' || $loan->status === 'disbursed' || $loan->status === 'completed')
                                                                    <p class="text-sm text-gray-500">Pinjaman disetujui</p>
                                                                @else
                                                                    <p class="text-sm text-gray-500">Pinjaman ditolak</p>
                                                                @endif
                                                                @if ($loan->approval_date)
                                                                    <p class="text-sm text-gray-900 font-medium">{{ $loan->approval_date->format('d M Y H:i') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif

                                        {{-- Disbursement --}}
                                        @if (in_array($loan->status, ['disbursed', 'completed']))
                                            <li>
                                                <div class="relative {{ $loan->status === 'completed' ? 'pb-8' : '' }}">
                                                    @if ($loan->status === 'completed')
                                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                                    @endif
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5">
                                                            <div>
                                                                <p class="text-sm text-gray-500">Dana dicairkan</p>
                                                                @if ($loan->first_installment_date)
                                                                    <p class="text-sm text-gray-900 font-medium">{{ $loan->first_installment_date->format('d M Y') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif

                                        {{-- Completion --}}
                                        @if ($loan->status === 'completed')
                                            <li>
                                                <div class="relative">
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0 flex-1 pt-1.5">
                                                            <div>
                                                                <p class="text-sm text-gray-500">Pinjaman lunas</p>
                                                                <p class="text-sm text-gray-900 font-medium">Selesai</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            {{-- Loan Summary --}}
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan</h3>
                                <div class="space-y-3">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Pokok Pinjaman:</span>
                                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($loan->loan_amount, 0, ',', '.') }}</span>
                                    </div>
                                    @php
                                        $totalInterest = ($loan->monthly_installment * $loan->loan_term_months) - $loan->loan_amount;
                                    @endphp
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Total Bunga:</span>
                                        <span class="text-sm font-medium text-orange-600">Rp {{ number_format($totalInterest, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2">
                                        <span class="text-sm font-medium text-gray-900">Total Pembayaran:</span>
                                        <span class="text-sm font-bold text-red-600">Rp {{ number_format($loan->monthly_installment * $loan->loan_term_months, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal (Placeholder for T3.4.9) --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Pembayaran Cicilan</h3>
                </div>
                <div class="px-6 py-4">
                    <div id="paymentDetails" class="space-y-4">
                        <!-- Payment details will be populated by JavaScript -->
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                    <button onclick="closePaymentModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </button>
                    <button onclick="processPayment()"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPaymentData = {};

        function openPaymentModal(loanId, installmentNumber, dueDate, amount) {
            currentPaymentData = {
                loanId: loanId,
                installmentNumber: installmentNumber,
                dueDate: dueDate,
                amount: amount
            };

            const paymentDetails = document.getElementById('paymentDetails');
            const formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);

            const formattedDate = new Date(dueDate).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            paymentDetails.innerHTML = `
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Detail Pembayaran</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Cicilan:</span>
                            <span class="font-medium">${installmentNumber} dari {{ $loan->loan_term_months }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jatuh Tempo:</span>
                            <span class="font-medium">${formattedDate}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-medium text-blue-600">${formattedAmount}</span>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    <p><strong>Catatan:</strong> Fitur pembayaran online akan tersedia dalam pembaruan selanjutnya.
                    Saat ini, silakan lakukan pembayaran melalui admin koperasi.</p>
                </div>
            `;

            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            currentPaymentData = {};
        }

        function processPayment() {
            // T3.4.9 implementation will go here
            alert('Fitur pembayaran online akan tersedia dalam pembaruan selanjutnya. Silakan hubungi admin koperasi untuk melakukan pembayaran.');
            closePaymentModal();
        }

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
</x-app-layout>
