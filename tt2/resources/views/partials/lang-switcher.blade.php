{{-- Language switcher — place anywhere in the layout, e.g. bottom of sidebar --}}
<div style="padding:10px 14px;border-top:1px solid var(--border);margin-top:auto;">
    <div style="font-size:11px;color:var(--tx3);margin-bottom:6px;">{{ __('general.app_name') === 'Kaimiņi palīdz kaimiņiem' ? 'Valoda' : 'Language' }}</div>
    <div style="display:flex;gap:6px;">

        <form action="{{ route('lang.switch') }}" method="POST" style="flex:1;">
            @csrf
            <input type="hidden" name="locale" value="lv">
            <button type="submit"
                    style="width:100%;padding:5px 0;border-radius:5px;font-size:12px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;border:1px solid var(--border);
                    background:{{ app()->getLocale() === 'lv' ? 'var(--green-pressed)' : 'var(--surf)' }};
                    color:{{ app()->getLocale() === 'lv' ? '#fff' : 'var(--tx2)' }};">
                LV
            </button>
        </form>

        <form action="{{ route('lang.switch') }}" method="POST" style="flex:1;">
            @csrf
            <input type="hidden" name="locale" value="en">
            <button type="submit"
                    style="width:100%;padding:5px 0;border-radius:5px;font-size:12px;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;border:1px solid var(--border);
                    background:{{ app()->getLocale() === 'en' ? 'var(--green-pressed)' : 'var(--surf)' }};
                    color:{{ app()->getLocale() === 'en' ? '#fff' : 'var(--tx2)' }};">
                EN
            </button>
        </form>

    </div>
</div>
