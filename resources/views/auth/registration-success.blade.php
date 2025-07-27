<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-green-50 to-white">
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg border-t-4 border-green-500">

            <!-- Success Icon -->
            <div class="text-center mb-6">
                <div class="mx-auto flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h1 class="text-2xl font-bold text-gray-900 mb-2">Pendaftaran Berhasil!</h1>
                <p class="text-green-600 font-medium">Koperasi Merah Putih</p>
            </div>

            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">
                            Akun Anda berhasil didaftarkan
                        </h3>
                        <div class="mt-2 text-sm text-green-700">
                            <p>Terima kasih telah mendaftar sebagai anggota Koperasi Merah Putih. Data Anda sedang dalam proses verifikasi oleh tim admin kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="space-y-4 mb-6">
                <h3 class="font-semibold text-gray-900 text-center">Langkah Selanjutnya:</h3>

                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold mt-0.5">
                            1
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">
                                <strong>Verifikasi Admin:</strong> Tim admin akan memverifikasi data dan foto KTP Anda dalam 1-2 hari kerja.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold mt-0.5">
                            2
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">
                                <strong>Notifikasi Email:</strong> Anda akan menerima email konfirmasi setelah akun disetujui.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-sm font-bold mt-0.5">
                            3
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">
                                <strong>Login Akun:</strong> Setelah disetujui, Anda dapat login menggunakan email dan password yang telah didaftarkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800">Penting untuk Diingat</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan email Anda aktif untuk menerima notifikasi</li>
                                <li>Jika dalam 3 hari kerja belum ada konfirmasi, hubungi admin koperasi</li>
                                <li>Data yang Anda berikan harus sesuai dengan KTP yang valid</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="text-center text-sm text-gray-600 mb-6">
                <p class="font-medium">Butuh bantuan?</p>
                <p>Hubungi admin Koperasi Merah Putih</p>
                <p class="text-red-600 font-medium">WhatsApp: +62 812-xxxx-xxxx</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col space-y-3">
                <a href="{{ route('login') }}"
                   class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 text-center">
                    Kembali ke Halaman Login
                </a>

                <a href="{{ route('register') }}"
                   class="w-full border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors duration-200 text-center">
                    Daftar Anggota Lain
                </a>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Koperasi Merah Putih - Desa Tajurhalang</p>
            </div>
        </div>
    </div>
</x-guest-layout>
