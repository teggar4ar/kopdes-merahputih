<div>
    <!-- Modal Overlay -->
    @if($showModal)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-start justify-center p-4 py-6 transition-opacity duration-300 ease-out"
            wire:click="closeModal"
        >
            <div
                class="relative mx-auto border w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl rounded-xl bg-white transform transition-all duration-300 ease-out scale-100"
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
                                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-gray-900">Setoran Simpanan</h3>
                                    <p class="text-sm text-gray-500">Ajukan permintaan setoran simpanan</p>
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

                        <!-- Info Box -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">Informasi Simpanan</span>
                            </div>
                            <div class="mt-2 text-sm text-blue-700">
                                <div class="space-y-1">
                                    <div><strong>Pokok:</strong> Rp 100.000 (sekali)</div>
                                    <div><strong>Wajib:</strong> Rp 50.000 (bulanan)</div>
                                    <div><strong>Sukarela:</strong> Min. Rp 50.000</div>
                                </div>
                            </div>
                        </div>

                        <!-- Form -->
                        <form wire:submit.prevent="showConfirmationStep" class="mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div class="space-y-6">
                                    <!-- Savings Type Selection -->
                                    <div>
                                        <label for="savingsType" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jenis Simpanan <span class="text-red-500">*</span>
                                        </label>
                                        <select
                                            wire:model.live="savingsType"
                                            id="savingsType"
                                            class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('savingsType') border-red-500 ring-2 ring-red-200 @enderror"
                                        >
                                            <option value="">Pilih Jenis Simpanan</option>
                                            <option value="pokok">Simpanan Pokok (Rp 100.000 - bayar sekali)</option>
                                            <option value="wajib">Simpanan Wajib (Rp 50.000 - bulanan)</option>
                                            <option value="sukarela">Simpanan Sukarela (Min. Rp 50.000)</option>
                                        </select>
                                        @error('savingsType')
                                            <div class="mt-2 flex items-center text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Month Selection (only for Mandatory Savings) -->
                                    @if($savingsType === 'wajib')
                                        <div>
                                            <label for="paymentMonth" class="block text-sm font-medium text-gray-700 mb-2">
                                                Bulan Pembayaran <span class="text-red-500">*</span>
                                            </label>
                                            <select
                                                wire:model.live="paymentMonth"
                                                id="paymentMonth"
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('paymentMonth') border-red-500 ring-2 ring-red-200 @enderror"
                                            >
                                                <option value="">Pilih Bulan</option>
                                                @php
                                                    $user = Auth::user();
                                                    $memberSince = $user->created_at;
                                                    $currentDate = now();
                                                    $startMonth = $memberSince->copy()->startOfMonth();
                                                    // Allow up to 3 months in advance
                                                    $endMonth = $currentDate->copy()->addMonths(3)->startOfMonth();

                                                    // Get all months from membership start to 3 months ahead
                                                    $months = [];
                                                    $tempMonth = $startMonth->copy();
                                                    while ($tempMonth <= $endMonth) {
                                                        $months[] = $tempMonth->copy();
                                                        $tempMonth->addMonth();
                                                    }

                                                    // Get paid months to show which are already paid
                                                    $paidMonths = $user->savingsTransactions()
                                                        ->where('savings_type', 'wajib')
                                                        ->where('transaction_type', 'setor')
                                                        ->where('amount', 50000)
                                                        ->whereIn('status', ['completed', 'pending'])
                                                        ->get()
                                                        ->map(function($transaction) {
                                                            return $transaction->transaction_date->format('Y-m');
                                                        })
                                                        ->toArray();

                                                    // Find the first unpaid month (earliest gap in sequence)
                                                    $firstUnpaidMonth = null;
                                                    foreach ($months as $month) {
                                                        if (!in_array($month->format('Y-m'), $paidMonths)) {
                                                            $firstUnpaidMonth = $month->format('Y-m');
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                @foreach($months as $month)
                                                    @php
                                                        $monthKey = $month->format('Y-m');
                                                        $monthLabel = $month->format('F Y');
                                                        $isPaid = in_array($monthKey, $paidMonths);
                                                        $isCurrentMonth = $month->format('Y-m') == $currentDate->format('Y-m');
                                                        $isPastMonth = $month->format('Y-m') < $currentDate->format('Y-m');
                                                        $isFutureMonth = $month->format('Y-m') > $currentDate->format('Y-m');

                                                        // Can only pay if:
                                                        // 1. Not already paid
                                                        // 2. It's the first unpaid month in sequence (no skipping)
                                                        $canPay = !$isPaid && ($firstUnpaidMonth === $monthKey);
                                                    @endphp
                                                    <option value="{{ $monthKey }}" @if(!$canPay) disabled @endif>
                                                        {{ $monthLabel }}
                                                        @if($isPaid)
                                                            (Sudah Dibayar)
                                                        @elseif(!$canPay && !$isPaid)
                                                            (Harus bayar bulan sebelumnya dulu)
                                                        @elseif($isPastMonth)
                                                            (Tunggakan)
                                                        @elseif($isCurrentMonth)
                                                            (Bulan Ini)
                                                        @elseif($isFutureMonth)
                                                            (Bayar Dimuka)
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('paymentMonth')
                                                <div class="mt-2 flex items-center text-red-600">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-sm">{{ $message }}</span>
                                                </div>
                                            @enderror
                                            @if($savingsType === 'wajib')
                                                <div class="mt-2 text-xs text-gray-500">
                                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0118 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <strong>Aturan Pembayaran:</strong><br>
                                                    • Hanya bisa bayar 1 bulan per transaksi<br>
                                                    • Harus bayar berurutan (tidak boleh melompati bulan)<br>
                                                    • Bisa bayar maksimal 3 bulan ke depan<br>
                                                    • Tunggakan harus dilunasi terlebih dahulu
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Amount Input -->
                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                            Jumlah Setoran <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex rounded-lg border border-gray-300 focus-within:ring-2 focus-within:ring-green-500 focus-within:border-transparent transition-all duration-200 @error('amount') border-red-500 ring-2 ring-red-200 @enderror">
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
                                                    },
                                                    updateAmount() {
                                                        const savingsType = $wire.get('savingsType');
                                                        if (savingsType === 'pokok') {
                                                            $event.target.value = '100.000';
                                                            $wire.set('amount', '100000');
                                                        } else if (savingsType === 'wajib') {
                                                            $event.target.value = '50.000';
                                                            $wire.set('amount', '50000');
                                                        }
                                                    }
                                                }"
                                                x-on:input="
                                                    const formatted = formatNumber($event.target.value);
                                                    $event.target.value = formatted;
                                                    $wire.set('amount', $event.target.value.replace(/[^\d]/g, ''));
                                                "
                                                x-on:blur="$event.target.value = formatNumber($event.target.value)"
                                                x-init="
                                                    $watch('$wire.savingsType', value => {
                                                        if (value === 'pokok') {
                                                            $el.value = '100.000';
                                                            $el.readOnly = true;
                                                            $wire.set('amount', '100000');
                                                        } else if (value === 'wajib') {
                                                            $el.value = '50.000';
                                                            $el.readOnly = true;
                                                            $wire.set('amount', '50000');
                                                        } else {
                                                            $el.value = '';
                                                            $el.readOnly = false;
                                                            $wire.set('amount', '');
                                                        }
                                                    });
                                                "
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
                                    </div>

                                    <!-- Notes Input -->
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan <span class="text-gray-400">(Opsional)</span>
                                        </label>
                                        <textarea
                                            id="notes"
                                            wire:model="notes"
                                            rows="4"
                                            class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 @error('notes') border-red-500 ring-2 ring-red-200 @enderror resize-none"
                                            placeholder="Catatan tambahan untuk setoran..."
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
                                </div>

                                <!-- Right Column -->
                                <div class="space-y-6">
                                    <!-- Proof of Transfer Upload -->
                                    <div>
                                        <label for="proofOfTransfer" class="block text-sm font-medium text-gray-700 mb-2">
                                            Bukti Transfer <span class="text-red-500">*</span>
                                        </label>
                                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200 @error('proofOfTransfer') border-red-500 @enderror">
                                            <div class="space-y-1 text-center">
                                                @if($proofOfTransfer)
                                                    <div class="flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-sm text-green-600 font-medium">{{ $proofOfTransfer->getClientOriginalName() }}</p>
                                                    <p class="text-xs text-gray-500">{{ round($proofOfTransfer->getSize() / 1024) }} KB</p>
                                                @else
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    <div class="flex text-sm text-gray-600">
                                                        <label for="proofOfTransfer" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                            <span>Upload file</span>
                                                        </label>
                                                        <p class="pl-1">atau drag and drop</p>
                                                    </div>
                                                    <p class="text-xs text-gray-500">JPG, PNG hingga 2MB</p>
                                                @endif
                                                <input
                                                    id="proofOfTransfer"
                                                    name="proofOfTransfer"
                                                    type="file"
                                                    wire:model="proofOfTransfer"
                                                    accept="image/*"
                                                    class="sr-only"
                                                >
                                            </div>
                                        </div>
                                        @error('proofOfTransfer')
                                            <div class="mt-2 flex items-center text-red-600">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm">{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Additional Info Panel -->
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div>
                                                <h4 class="text-sm font-medium text-yellow-800">Catatan Penting</h4>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <ul class="list-disc list-inside space-y-1">
                                                        <li>Pastikan transfer sudah berhasil sebelum mengajukan setoran</li>
                                                        <li>Upload bukti transfer yang jelas dan lengkap</li>
                                                        <li>Setoran akan diproses setelah verifikasi admin</li>
                                                        <li>Simpanan Pokok: sekali Rp 100.000</li>
                                                        <li>Simpanan Wajib: bulanan Rp 50.000</li>
                                                        <li>Simpanan Sukarela: minimal Rp 50.000</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3 mt-8 pt-6 border-t border-gray-200">
                                <button
                                    type="button"
                                    wire:click="closeModal"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-all duration-200"
                                >
                                    Batal
                                </button>
                                <button
                                    type="submit"
                                    class="flex-1 px-4 py-3 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                                    @if(empty($savingsType) || empty($amount) || !$proofOfTransfer) disabled @endif
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
                            <div class="mx-auto flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Setoran</h3>
                            <p class="text-sm text-gray-500 mt-1">Pastikan informasi berikut sudah benar</p>
                        </div>

                        <!-- Confirmation Details -->
                        <div class="mt-6 space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-600">Jenis Simpanan:</dt>
                                        <dd class="text-sm text-gray-900 font-semibold">{{ $this->getSavingsTypeName() }}</dd>
                                    </div>
                                    @if($savingsType === 'wajib' && $paymentMonth)
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Bulan Pembayaran:</dt>
                                            <dd class="text-sm text-gray-900 font-semibold">
                                                @php
                                                    $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $paymentMonth);
                                                @endphp
                                                {{ $monthDate->format('F Y') }}
                                                @if($monthDate->format('Y-m') < now()->format('Y-m'))
                                                    <span class="text-xs text-orange-600 font-medium">(Tunggakan)</span>
                                                @elseif($monthDate->format('Y-m') == now()->format('Y-m'))
                                                    <span class="text-xs text-blue-600 font-medium">(Bulan Ini)</span>
                                                @endif
                                            </dd>
                                        </div>
                                    @endif
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-600">Jumlah Setoran:</dt>
                                        <dd class="text-lg font-bold text-green-600">Rp {{ number_format($amount, 0, ',', '.') }}</dd>
                                    </div>
                                    @if($proofOfTransfer)
                                        <div class="flex justify-between">
                                            <dt class="text-sm font-medium text-gray-600">Bukti Transfer:</dt>
                                            <dd class="text-sm text-gray-900">{{ $proofOfTransfer->getClientOriginalName() }}</dd>
                                        </div>
                                    @endif
                                    @if($notes)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-600 mb-1">Catatan:</dt>
                                            <dd class="text-sm text-gray-900 bg-white p-2 rounded border">{{ $notes }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>

                            <!-- Warning Message -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Informasi!</p>
                                        <p class="text-sm text-blue-700 mt-1">
                                            Permintaan setoran akan diproses oleh admin setelah verifikasi bukti transfer. Pastikan transfer sudah berhasil dilakukan ke rekening koperasi.
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
                                wire:click="submitDeposit"
                                class="flex-1 px-4 py-3 text-sm font-medium bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center justify-center"
                                wire:loading.attr="disabled"
                                wire:target="submitDeposit"
                            >
                                <span wire:loading.remove wire:target="submitDeposit">Konfirmasi Setoran</span>
                                <span wire:loading wire:target="submitDeposit" class="flex items-center">
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

    <!-- Loading Indicator -->
    <div wire:loading wire:target="proofOfTransfer" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 sm:p-6 flex items-center space-x-3">
            <svg class="animate-spin h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-gray-700 text-sm sm:text-base">Mengupload file...</span>
        </div>
    </div>
</div>
