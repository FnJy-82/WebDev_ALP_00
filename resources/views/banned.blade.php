<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
        <div class="max-w-md w-full bg-white shadow-2xl rounded-xl overflow-hidden text-center">

            {{-- Header with Icon --}}
            <div class="bg-red-600 p-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-800 mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Account Suspended</h2>
            </div>

            {{-- Body Content --}}
            <div class="p-8">
                <p class="text-gray-600 text-lg mb-6">
                    We regret to inform you that your access to <strong>TicketPro</strong> has been suspended due to a
                    violation of our terms of service.
                </p>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8 text-sm text-red-800 text-left">
                    <strong>Note:</strong> If you believe this is a mistake, please contact our support team
                    immediately.
                </div>

                {{-- Primary Button: Login --}}
                <a href="{{ route('login') }}"
                    class="w-full block bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-4 rounded-lg transition duration-200 shadow-md transform hover:-translate-y-1 mb-4">
                    Return to Login
                </a>

                {{-- Secondary Button: Continue as Guest --}}
                <a href="{{ route('home') }}"
                    class="inline-block text-sm text-gray-500 hover:text-gray-800 underline decoration-gray-300 hover:decoration-gray-800 transition">
                    Continue as Guest
                </a>
            </div>

            {{-- Footer --}}
            <div class="bg-gray-50 p-4 border-t border-gray-100">
                <p class="text-xs text-gray-400">Copyright &copy; {{ date('Y') }} TicketPro Inc.</p>
            </div>
        </div>
    </div>
</x-guest-layout>
