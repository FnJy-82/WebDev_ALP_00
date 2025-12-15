<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Report User / Request Ban') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-2">Report User: <span class="text-red-600">{{ $user->name }}</span>
                    </h3>
                    <p class="text-gray-500 text-sm mb-6">
                        Email: {{ $user->email }} <br>
                        Identity Number: {{ $user->identity_number ?? 'N/A' }}
                    </p>

                    <form action="{{ route('organizer.ban-request.store') }}" method="POST">
                        @csrf

                        {{-- Hidden Input for Target User ID --}}
                        <input type="hidden" name="target_user_id" value="{{ $user->id }}">

                        <div class="mb-6">
                            <x-input-label for="reason" :value="__('Reason for Ban Request')" />
                            <p class="text-xs text-gray-500 mb-2">Please describe the incident in detail. This will be
                                reviewed by the Admin.</p>

                            <textarea id="reason" name="reason" rows="5"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required
                                placeholder="Example: This user caused a disturbance during the concert and refused to follow security protocols..."></textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ url()->previous() }}"
                                class="text-gray-600 hover:text-gray-900 text-sm underline">
                                Cancel
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Submit Report') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
