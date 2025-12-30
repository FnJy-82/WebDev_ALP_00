<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Menjadi Event Organizer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <p class="mb-6 text-gray-600">
                        Lengkapi data bisnis Anda untuk dapat membuat Event. Data Anda akan diverifikasi oleh Admin dalam 1x24 jam.
                    </p>

                    <form method="POST" action="{{ route('organizer.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <x-input-label for="company_name" :value="__('Nama Organizer / Perusahaan')" />
                            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required autofocus />
                            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bank_name" :value="__('Nama Bank')" />
                            <x-text-input id="bank_name" class="block mt-1 w-full" type="text" name="bank_name" :value="old('bank_name')" placeholder="Contoh: BCA, Mandiri" required />
                            <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="bank_account_number" :value="__('Nomor Rekening')" />
                            <x-text-input id="bank_account_number" class="block mt-1 w-full" type="number" name="bank_account_number" :value="old('bank_account_number')" required />
                            <x-input-error :messages="$errors->get('bank_account_number')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="document_path" :value="__('Upload Legalitas (KTP/NPWP) - PDF/JPG')" />
                            <input id="document_path" name="document_path" type="file" class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                            <x-input-error :messages="$errors->get('document_path')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Ajukan Verifikasi EO') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
