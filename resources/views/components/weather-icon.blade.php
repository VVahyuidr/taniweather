@props(['code' => 0, 'class' => 'w-6 h-6'])

@php
    $c = (int) $code;
    if ($c === 0) {
        $type = 'cerah';
    } elseif ($c === 1 || $c === 2) {
        $type = 'cerah-berawan';
    } elseif ($c === 3) {
        $type = 'berawan';
    } elseif ($c === 45 || $c === 48) {
        $type = 'kabut';
    } elseif (($c >= 51 && $c <= 61) || $c === 80) {
        $type = 'hujan-ringan';
    } elseif (($c >= 63 && $c <= 67) || $c === 81 || $c === 82) {
        $type = 'hujan-lebat';
    } elseif ($c >= 95) {
        $type = 'badai-petir';
    } else {
        $type = 'cerah-berawan';
    }
@endphp

<span class="inline-flex items-center justify-center {{ $class }}" aria-hidden="true">
    @if($type === 'cerah')
        <svg viewBox="0 0 24 24" class="w-full h-full text-amber-500" fill="currentColor">
            <circle cx="12" cy="12" r="5" />
            <g stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="12" y1="1" x2="12" y2="3" />
                <line x1="12" y1="21" x2="12" y2="23" />
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                <line x1="1" y1="12" x2="3" y2="12" />
                <line x1="21" y1="12" x2="23" y2="12" />
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            </g>
        </svg>
    @elseif($type === 'cerah-berawan')
        <svg viewBox="0 0 24 24" class="w-full h-full" fill="none">
            <circle cx="14" cy="8" r="4" fill="#F59E0B" />
            <path d="M6 18h11a4 4 0 0 0 .5-7.96 5.5 5.5 0 0 0-10.4-1.2A3.5 3.5 0 0 0 6 18z" fill="#94A3B8" />
            <path d="M5.5 17.5h10.5a3.5 3.5 0 0 0 .5-6.96 5 5 0 0 0-9.4-1.2A3 3 0 0 0 5.5 17.5z" fill="#E2E8F0" />
        </svg>
    @elseif($type === 'berawan')
        <svg viewBox="0 0 24 24" class="w-full h-full text-slate-400" fill="currentColor">
            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" />
        </svg>
    @elseif($type === 'kabut')
        <svg viewBox="0 0 24 24" class="w-full h-full text-slate-400" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="3" y1="8" x2="21" y2="8" />
            <line x1="5" y1="12" x2="19" y2="12" />
            <line x1="2" y1="16" x2="22" y2="16" />
            <line x1="6" y1="20" x2="18" y2="20" />
        </svg>
    @elseif($type === 'hujan-ringan')
        <svg viewBox="0 0 24 24" class="w-full h-full text-sky-500" fill="none">
            <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" fill="#94A3B8" />
            <line x1="8" y1="19" x2="6" y2="23" stroke="#0284C7" stroke-width="2" stroke-linecap="round" />
            <line x1="12" y1="19" x2="10" y2="23" stroke="#0284C7" stroke-width="2" stroke-linecap="round" />
            <line x1="16" y1="19" x2="14" y2="23" stroke="#0284C7" stroke-width="2" stroke-linecap="round" />
        </svg>
    @elseif($type === 'hujan-lebat')
        <svg viewBox="0 0 24 24" class="w-full h-full text-blue-600" fill="none">
            <path d="M19.35 9.04C18.67 5.59 15.64 3 12 3 9.11 3 6.6 4.64 5.35 7.04 2.34 7.36 0 9.91 0 13c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" fill="#475569" />
            <line x1="7" y1="18" x2="5" y2="23" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" />
            <line x1="11" y1="18" x2="9" y2="23" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" />
            <line x1="15" y1="18" x2="13" y2="23" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" />
            <line x1="19" y1="18" x2="17" y2="23" stroke="#2563EB" stroke-width="2.2" stroke-linecap="round" />
        </svg>
    @elseif($type === 'badai-petir')
        <svg viewBox="0 0 24 24" class="w-full h-full text-amber-500" fill="none">
            <path d="M19.35 8.04C18.67 4.59 15.64 2 12 2 9.11 2 6.6 3.64 5.35 6.04 2.34 6.36 0 8.91 0 12c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" fill="#334155" />
            <path d="M13 11l-3 5h3l-2 6 5-7h-3l2-4h-2z" fill="#FACC15" />
        </svg>
    @endif
</span>

