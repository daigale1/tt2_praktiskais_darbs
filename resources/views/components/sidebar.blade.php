<aside class="w-56 bg-white border-r border-gray-200 flex flex-col min-h-screen p-4">
    <div class="mb-6">
        <p class="font-medium text-gray-900">{{ auth()->user()->name }}</p>
        <p class="text-sm text-gray-500">{{ auth()->user()->location ?? 'No location set' }}</p>
    </div>

    <nav class="flex flex-col gap-1 flex-1">
        <a href="{{ route('feed.index') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('feed.index') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Available tasks
        </a>
        <a href="{{ route('tasks.mine') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('tasks.mine') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            My tasks
        </a>
        <a href="{{ route('tasks.create') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('tasks.create') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Create task
        </a>
        <a href="{{ route('tasks.completed') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('tasks.completed') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Completed tasks
        </a>
        <a href="{{ route('chat.index') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('chat.*') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            Messages
        </a>
        <a href="{{ route('profile.show') }}" class="px-3 py-2 rounded text-sm {{ request()->routeIs('profile.show') ? 'bg-gray-100 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
            My profile
        </a>
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full text-left px-3 py-2 rounded text-sm text-red-600 hover:bg-red-50">
            Logout
        </button>
    </form>
</aside>