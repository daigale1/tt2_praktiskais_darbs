<aside class="sidebar">

    <div class="sb-top">
        {{-- Logged-in user button --}}
        <a href="{{ route('profile.show') }}" class="sb-user-btn">
            <i class="ti ti-user" aria-hidden="true"></i>
            {{ Auth::user()->name }}
        </a>

        {{-- Dark / light mode toggle --}}
        <button class="mode-btn" onclick="toggleDarkMode()" aria-label="Toggle dark mode" id="modeBtn">
            <i class="ti ti-sun" id="modeIconSun" aria-hidden="true"></i>
            <i class="ti ti-moon" id="modeIconMoon" style="display:none;" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="sb-nav" aria-label="Main navigation">

        <a href="{{ route('profile.show') }}"
           class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            My profile
        </a>

        <a href="{{ route('tasks.create') }}"
           class="nav-item {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            Create a new task
        </a>

        <a href="{{ route('tasks.mine') }}"
           class="nav-item {{ request()->routeIs('tasks.mine') ? 'active' : '' }}">
            My tasks
        </a>

        <a href="{{ route('feed.index') }}"
           class="nav-item {{ request()->routeIs('feed.*') ? 'active' : '' }}">
            Available tasks
        </a>

        <a href="{{ route('tasks.completed') }}"
           class="nav-item {{ request()->routeIs('tasks.completed') ? 'active' : '' }}">
            Completed tasks
        </a>

        <a href="{{ route('chat.index') }}"
           class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
            Messages
        </a>

    </nav>

</aside>
