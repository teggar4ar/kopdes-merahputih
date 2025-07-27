<div>
    <!-- Modal Overlay -->
    @if($showModal)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 transition-opacity duration-300 ease-out"
            wire:click="closeModal"
        >
            <div
                class="relative mx-auto border w-full max-w-md shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100"
                wire:click.stop
                @if($showConfirmation)
                    x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 50)"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                @endif
            >
                @if(!$showConfirmation)
                    <!-- Form Step -->
                    <div class="p-6">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Penarikan Simpanan Sukarela</h3>
                                    <p class="text-sm text-gray-500">Ajukan permintaan penarikan dana</p>
                                </div>
                            </div>
                            <button
                                wire:click="closeModal"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200 p-1 rounded-full hover:bg-gray-100"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Balance Info -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">Saldo Tersedia</span>
                            </div>
                            <p class="text-xl font-bold text-blue-900 mt-1">Rp {{ number_format($maxAmount, 0, ',', '.') }}</p>
                        </div>

                        <!-- Minimum Amount Info -->
                        <div class="mt-3 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="text-sm font-medium text-yellow-800">Penarikan minimal Rp 50.000</span>
                            </div>
                        </div>

                        <!-- Savings Obligations Status -->
                        <div class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Persyaratan Penarikan:</h4>
                            <div class="space-y-2">
                                @php
                                    $user = Auth::user();
                                    $hasPaidPrincipal = $user->hasPaidPrincipalSavings();
                                    $isCurrentOnMandatory = $user->isCurrentOnMandatorySavings();
                                    $hasCurrentMonthPayment = $user->hasCurrentMonthMandatoryPayment();
                                @endphp

                                <!-- Principal Savings Status -->
                                <div class="flex items-center text-xs">
                                    @if($hasPaidPrincipal)
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-700">Simpanan Pokok sudah dibayar</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-red-700">Simpanan Pokok belum dibayar (Rp 100.000)</span>
                                    @endif
                                </div>

                                <!-- Mandatory Savings Overall Status -->
                                <div class="flex items-center text-xs">
                                    @if($isCurrentOnMandatory)
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-700">Simpanan Wajib up-to-date</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-red-700">Ada tunggakan Simpanan Wajib</span>
                                    @endif
                                </div>

                                <!-- Current Month Mandatory Payment Status -->
                                <div class="flex items-center text-xs">
                                    @if($hasCurrentMonthPayment)
                                        <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-700">Simpanan Wajib bulan ini sudah dibayar (Rp 50.000)</span>
                                    @else
                                        <svg class="w-4 h-4 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-red-700">Simpanan Wajib bulan ini belum dibayar (Rp 50.000)</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form wire:submit.prevent="showConfirmationStep" class="mt-6">
                            <!-- Amount Input -->
                            <div class="mb-6">
                                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Penarikan <span class="text-red-500">*</span>
                                </label>
                                <div class="flex rounded-lg border border-gray-300 focus-within:ring-2 focus-within:ring-red-500 focus-within:border-transparent transition-all duration-200 @error('amount') border-red-500 ring-2 ring-red-200 @enderror">
                                    <div class="flex items-center px-3 bg-gray-50 border-r border-gray-300 rounded-l-lg">
                                        <span class="text-gray-600 text-sm font-medium">Rp</span>
                                    </div>
                                    <input
                                        type="text"
                                        id="amount"
                                        wire:model.blur="amount"
                                        class="flex-1 px-3 py-3 border-0 rounded-r-lg focus:outline-none focus:ring-0"
                                        placeholder="Masukkan jumlah"
                                        x-data="{
                                            formatNumber(value) {
                                                const numericValue = value.replace(/[^\d]/g, '');
                                                if (numericValue === '' || numericValue === '0') return '';
                                                return new Intl.NumberFormat('id-ID').format(parseInt(numericValue));
                                            }
                                        }"
                                        x-on:input="
                                            const formatted = formatNumber($event.target.value);
                                            $event.target.value = formatted;
                                            $wire.set('amount', $event.target.value.replace(/[^\d]/g, ''));
                                        "
                                        x-on:blur="$event.target.value = formatNumber($event.target.value)"
                                    >
                                </div>
                                @error('amount')
                                    <div class="mt-2 flex items-center text-red-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm">{{ $message }}</span>
                                    </div>
                                @enderror
                                @if($maxAmount <= 0)
                                    <div class="mt-2 flex items-center text-yellow-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm">Saldo simpanan sukarela tidak mencukupi untuk penarikan</span>
                                    </div>
                                @elseif($maxAmount > 0 && $maxAmount < 50000)
                                    <div class="mt-2 flex items-center text-yellow-600">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm">Saldo Anda kurang dari minimal penarikan (Rp 50.000)</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Notes Input -->
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <textarea
                                    id="notes"
                                    wire:model="notes"
                                    rows="3"
                                    class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 @error('notes') border-red-500 ring-2 ring-red-200 @enderror resize-none"
                                    placeholder="Alasan atau keperluan penarikan..."
                                    maxlength="255"
                                ></textarea>
                                <div class="flex justify-between mt-1">
                                    @error('notes')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @else
                                        <span></span>
                                    @enderror
                                    <span class="text-xs text-gray-400">{{ strlen($notes ?? '') }}/255</span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button
                                    type="button"
                                    wire:click="closeModal"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                    @if($maxAmount < 50000 || empty($amount) || (is_numeric($amount) && $amount < 50000) || !$hasPaidPrincipal || !$isCurrentOnMandatory || !$hasCurrentMonthPayment) disabled @endif
                                >
                                    Lanjutkan
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Confirmation Step -->
                    <div class="p-6">
                        <!-- Confirmation Header -->
                        <div class="text-center pb-4 border-b border-gray-200">
                            <div class="mx-auto flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Penarikan</h3>
                            <p class="text-sm text-gray-500 mt-1">Pastikan informasi berikut sudah benar</p>
                        </div>

                        <!-- Confirmation Details -->
                        <div class="mt-6 space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-600">Jenis Simpanan:</dt>
                                        <dd class="text-sm text-gray-900 font-semibold">Simpanan Sukarela</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-600">Jumlah Penarikan:</dt>
                                        <dd class="text-lg font-bold text-red-600">Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-600">Saldo Setelah Penarikan:</dt>
                                        <dd class="text-sm text-gray-900 font-semibold">Rp {{ number_format($maxAmount - $amount, 0, ',', '.') }}</dd>
                                    </div>
                                    @if($notes)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-600 mb-1">Catatan:</dt>
                                            <dd class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $notes }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Warning Message -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-800">Perhatian!</p>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            Permintaan penarikan akan diproses oleh admin. Dana akan ditransfer setelah permintaan disetujui.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Buttons -->
                        <div class="flex space-x-3 mt-6">
                            <button
                                type="button"
                                wire:click="backToForm"
                                class="flex-1 px-4 py-3 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200"
                                wire:loading.attr="disabled"
                            >
                                Kembali
                            </button>
                            <button
                                type="button"
                                wire:click="submitWithdrawal"
                                class="flex-1 px-4 py-3 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
                                wire:loading.attr="disabled"
                                wire:target="submitWithdrawal"
                            >
                                <span wire:loading.remove wire:target="submitWithdrawal">Konfirmasi Penarikan</span>
                                <span wire:loading wire:target="submitWithdrawal" class="flex items-center">
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
    @endif
</div>
