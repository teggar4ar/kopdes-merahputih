<div>
    {{-- Success/Error Messages --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2" x-init="setTimeout(() => show = false, 10000)"
            class="fixed top-4 right-4 z-50 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2" x-init="setTimeout(() => show = false, 10000)"
            class="fixed top-4 right-4 z-50 max-w-sm w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- Main Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                {{-- Modal panel --}}
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    @if (!$showConfirmation)
                        {{-- Loan Application Form --}}
                        <form wire:submit.prevent="submit">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                {{-- Header --}}
                                <div class="sm:flex sm:items-start mb-6">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Ajukan Pinjaman
                                        </h3>
                                        <p class="text-sm text-gray-500 mt-1">Isi form di bawah untuk mengajukan pinjaman</p>
                                    </div>
                                </div>

                                {{-- Two Column Layout --}}
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    {{-- Left Column: Form Fields --}}
                                    <div class="space-y-4">
                                        {{-- Loan Amount --}}
                                        <div>
                                            <label for="loanAmount" class="block text-sm font-medium text-gray-700 mb-1">
                                                Jumlah Pinjaman
                                            </label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                                </div>
                                                <input
                                                    wire:model.live="loanAmount"
                                                    type="text"
                                                    id="loanAmount"
                                                    class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('loanAmount') border-red-300 @enderror"
                                                    placeholder="0"
                                                    x-mask:dynamic="
                                                        $money($input, '.')
                                                    "
                                                >
                                            </div>
                                            @error('loanAmount')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-xs text-gray-500">Minimal Rp 100.000 - Maksimal Rp 50.000.000</p>
                                        </div>

                                        {{-- Loan Term --}}
                                        <div>
                                            <label for="loanTerm" class="block text-sm font-medium text-gray-700 mb-1">
                                                Jangka Waktu
                                            </label>
                                            <select
                                                wire:model.live="loanTerm"
                                                id="loanTerm"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('loanTerm') border-red-300 @enderror"
                                            >
                                                <option value="">Pilih jangka waktu</option>
                                                <option value="3">3 Bulan</option>
                                                <option value="6">6 Bulan</option>
                                                <option value="12">12 Bulan</option>
                                            </select>
                                            @error('loanTerm')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Loan Purpose --}}
                                        <div>
                                            <label for="loanPurpose" class="block text-sm font-medium text-gray-700 mb-1">
                                                Tujuan Pinjaman
                                            </label>
                                            <textarea
                                                wire:model.live="loanPurpose"
                                                id="loanPurpose"
                                                rows="4"
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('loanPurpose') border-red-300 @enderror"
                                                placeholder="Jelaskan tujuan penggunaan pinjaman..."
                                            ></textarea>
                                            @error('loanPurpose')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-xs text-gray-500">{{ strlen($loanPurpose) }}/500 karakter</p>
                                        </div>
                                    </div>

                                    {{-- Right Column: Calculator & Information --}}
                                    <div class="space-y-4">
                                        {{-- Loan Calculator --}}
                                        @if ($loanAmount && $loanTerm)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <h4 class="text-sm font-medium text-blue-900 mb-3 flex items-center">
                                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    Simulasi Pinjaman
                                                </h4>
                                                <div class="space-y-3 text-sm">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-600">Suku Bunga:</span>
                                                        <span class="font-semibold text-blue-900">{{ $interestRate }}% per tahun</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-600">Cicilan per Bulan:</span>
                                                        <span class="font-semibold text-blue-900">Rp {{ number_format($monthlyPayment, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-600">Total Bunga:</span>
                                                        <span class="font-semibold text-orange-600">Rp {{ number_format($totalInterest, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center pt-2 border-t border-blue-200">
                                                        <span class="text-gray-600 font-medium">Total Pembayaran:</span>
                                                        <span class="font-bold text-red-600">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                    Simulasi Pinjaman
                                                </h4>
                                                <p class="text-sm text-gray-500">Isi jumlah pinjaman dan jangka waktu untuk melihat simulasi</p>
                                            </div>
                                        @endif

                                        {{-- Payment Information --}}
                                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-yellow-800">
                                                        Informasi Pembayaran Cicilan
                                                    </h3>
                                                    <div class="mt-2 text-sm text-yellow-700">
                                                        <p class="mb-2">
                                                            <strong>Transfer Bank:</strong> Sertakan nomor rekening, nama pemilik, dan nama bank dalam bukti pembayaran.
                                                        </p>
                                                        <p>
                                                            <strong>Tunai:</strong> Dapat dibayar langsung di kantor koperasi.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                                    :disabled="$wire.isProcessing"
                                >
                                    <span wire:loading.remove>Ajukan Pinjaman</span>
                                    <span wire:loading>Memproses...</span>
                                </button>
                                <button type="button"
                                    wire:click="closeModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                >
                                    Batal
                                </button>
                            </div>
                        </form>
                    @else
                        {{-- Confirmation Step --}}
                        <div class="p-6">
                            {{-- Confirmation Header --}}
                            <div class="text-center pb-4 border-b border-gray-200">
                                <div class="mx-auto flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Pengajuan Pinjaman</h3>
                                <p class="text-sm text-gray-500 mt-1">Pastikan informasi berikut sudah benar</p>
                            </div>

                            {{-- Confirmation Details --}}
                            <div class="mt-6 space-y-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <dl class="space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Jumlah Pinjaman:</dt>
                                            <dd class="text-lg font-bold text-blue-600">Rp {{ number_format($loanAmount, 0, ',', '.') }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Jangka Waktu:</dt>
                                            <dd class="text-sm text-gray-900 font-semibold">{{ $loanTerm }} Bulan</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Suku Bunga:</dt>
                                            <dd class="text-sm text-gray-900 font-semibold">{{ $interestRate }}% per tahun</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Cicilan per Bulan:</dt>
                                            <dd class="text-sm text-gray-900 font-semibold">Rp {{ number_format($monthlyPayment, 0, ',', '.') }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Total Bunga:</dt>
                                            <dd class="text-sm font-semibold text-orange-600">Rp {{ number_format($totalInterest, 0, ',', '.') }}</dd>
                                        </div>
                                        <div class="flex justify-between border-t border-gray-200 pt-3">
                                            <dt class="text-sm font-medium text-gray-600">Total Pembayaran:</dt>
                                            <dd class="text-lg font-bold text-red-600">Rp {{ number_format($totalPayment, 0, ',', '.') }}</dd>
                                        </div>
                                        @if($loanPurpose)
                                            <div>
                                                <dt class="text-sm font-medium text-gray-600 mb-1">Tujuan Pinjaman:</dt>
                                                <dd class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $loanPurpose }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>

                                {{-- Warning Message --}}
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800">Informasi!</p>
                                            <p class="text-sm text-blue-700 mt-1">
                                                Pengajuan pinjaman akan diproses oleh admin dalam 1-3 hari kerja. Pastikan data yang Anda masukkan sudah benar dan lengkap.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Confirmation Buttons --}}
                            <div class="flex space-x-3 mt-6">
                                <button
                                    type="button"
                                    wire:click="cancelConfirmation"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200"
                                    wire:loading.attr="disabled"
                                >
                                    Kembali
                                </button>
                                <button
                                    type="button"
                                    wire:click="confirmSubmission"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
                                    wire:loading.attr="disabled"
                                    wire:target="confirmSubmission"
                                >
                                    <span wire:loading.remove wire:target="confirmSubmission">Ya, Ajukan Pinjaman</span>
                                    <span wire:loading wire:target="confirmSubmission" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@script
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.directive('mask', (el, { expression }, { evaluateLater, cleanup }) => {
            let evaluator = evaluateLater(expression)
            let mask = null

            evaluator(value => {
                if (value === 'money') {
                    mask = (value) => {
                        return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                    }
                }
            })

            let listener = () => {
                if (mask) {
                    el.value = mask(el.value)
                }
            }

            el.addEventListener('input', listener)
            cleanup(() => el.removeEventListener('input', listener))
        })
    })
</script>
@endscript
