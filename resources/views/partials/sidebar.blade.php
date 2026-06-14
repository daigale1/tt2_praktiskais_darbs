
<aside class="sidebar">

    {{-- Top bar: user identity + dark mode toggle --}}
    <div class="sb-top">
        {{-- Clicking the user's name navigates to the profile/edit page --}}
        <a href="{{ route('profile.edit') }}" class="sb-user-btn">
            <i class="ti ti-user" aria-hidden="true"></i>
            {{ Auth::user()->name }}
        </a>

        {{-- Calls toggleDarkMode() defined in app.js --}}
        <button class="mode-btn" onclick="toggleDarkMode()" aria-label="Toggle dark mode">
            <i class="ti ti-sun"  id="modeIconSun"  aria-hidden="true"></i>
            <i class="ti ti-moon" id="modeIconMoon" style="display:none;" aria-hidden="true"></i>
        </button>
    </div>

    {{-- Main navigation --}}
    <nav class="sb-nav" aria-label="Main navigation">

        {{-- Each nav-item gets the 'active' class when the current route matches --}}
        {{-- Active only when on the profile page without a tab param (Profile tab) --}}
        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile.*') && !request('tab') ? 'active' : '' }}">
            My profile
        </a>

        <a href="{{ route('tasks.create') }}"
           class="nav-item {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            Create a new task
        </a>

        {{-- Opens the profile page with the My tasks tab pre-selected --}}
        <a href="{{ route('profile.edit') }}?tab=mytasks"
           class="nav-item {{ request()->routeIs('profile.*') && request('tab') === 'mytasks' ? 'active' : '' }}">
            My tasks
        </a>

        <a href="{{ route('feed.index') }}"
           class="nav-item {{ request()->routeIs('feed.*') ? 'active' : '' }}">
            Available tasks
        </a>

        {{-- Opens the profile page with the Completed tab pre-selected --}}
        <a href="{{ route('profile.edit') }}?tab=completed"
           class="nav-item {{ request()->routeIs('profile.*') && request('tab') === 'completed' ? 'active' : '' }}">
            Completed tasks
        </a>

        <a href="{{ route('chat.index') }}"
           class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}">
            Messages
        </a>

        {{-- Admin panel link — only visible to users with the 'admin' role --}}
        @if(Auth::user()->hasRole('admin'))
        <a href="{{ route('admin.index') }}"
           class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            Admin panel
        </a>
        @endif

    </nav>

    {{-- Sign out — pinned to the bottom of the sidebar --}}
    <div style="padding:10px 14px; border-top:1px solid var(--border);">
        {{-- POST is required by Laravel's logout route (CSRF protection) --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    style="width:100%; display:flex; align-items:center; gap:6px; padding:7px 10px; border-radius:6px; border:1px solid var(--border); background:transparent; color:var(--tx3); font-size:13px; font-weight:500; cursor:pointer; font-family:'DM Sans',sans-serif; transition:background .12s, color .12s;"
                    onmouseover="this.style.background='var(--rose-lightest)';this.style.color='var(--rose-text)';this.style.borderColor='var(--rose-deep)'"
                    onmouseout="this.style.background='transparent';this.style.color='var(--tx3)';this.style.borderColor='var(--border)'">
                <i class="ti ti-logout" aria-hidden="true"></i>
                Sign out
            </button>
        </form>
    </div>

</aside>
