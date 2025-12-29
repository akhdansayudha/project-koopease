<footer class="bg-white border-t border-gray-200 mt-auto">
    <div class="max-w-7xl mx-auto px-6 md:px-8 py-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="text-center lg:text-left">
                <p class="text-gray-600 text-sm">
                    &copy; 2025 <span class="font-bold text-blue-600">KoopEase</span>. All rights
                    reserved.
                </p>
            </div>

            <div class="text-center">
                <p class="text-gray-500 text-sm font-medium bg-gray-100 px-4 py-2 rounded-full inline-block">
                    <i class="bi bi-people-fill me-2"></i>
                    Dibuat oleh Kelompok 3 PPL IS-06-03
                </p>
            </div>

            <div class="flex justify-center lg:justify-end gap-6 text-sm">
                <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    Kebijakan Privasi
                </a>
                <a href="{{ route('terms') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    Syarat & Ketentuan
                </a>
                <a href="{{ route('faq') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
                    FAQ
                </a>
            </div>
        </div>
    </div>
</footer>
