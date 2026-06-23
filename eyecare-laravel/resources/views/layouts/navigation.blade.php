<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center lg:hidden">
                <button @click="document.querySelector('aside').classList.toggle('-translate-x-0'); document.querySelector('aside').classList.toggle('-translate-x-full')" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <div class="flex items-center space-x-4 ms-auto">
                <span class="text-sm text-gray-500 dark:text-gray-400 hidden sm:inline">
                    {{ auth()->user()->name }}
                    <span class="px-2 py-0.5 text-xs rounded-full {{ auth()->user()->role === 'Admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' }}">
                        {{ auth()->user()->role }}
                    </span>
                </span>

                <a href="{{ route('profile.edit') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    Profile
                </a>
            </div>
        </div>
    </div>
</nav>
