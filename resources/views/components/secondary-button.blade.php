<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-blue-600 border border-gray-200 rounded-lg font-medium text-sm text-white shadow-sm hover:shadow-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-300 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150']) }}>
     {{ $slot }}
 </button>
