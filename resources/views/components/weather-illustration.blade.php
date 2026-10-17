@props(['code' => 0, 'class' => 'w-52 h-52 sm:w-60 sm:h-60 lg:w-72 lg:h-72'])

@php
    $c = (int) $code;
    // Determine condition type
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

<div class="relative flex items-center justify-center {{ $class }}" aria-hidden="true">
    @if($type === 'cerah')
        <!-- CERAH (MATAHARI BESAR BERSINAR) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_12px_24px_rgba(245,158,11,0.35)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="sunGlow" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#FEF08A" stop-opacity="0.8" />
                    <stop offset="60%" stop-color="#F59E0B" stop-opacity="0.3" />
                    <stop offset="100%" stop-color="#D97706" stop-opacity="0" />
                </radialGradient>
                <linearGradient id="sunCore" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FEF08A" />
                    <stop offset="45%" stop-color="#FBBF24" />
                    <stop offset="100%" stop-color="#EA580C" />
                </linearGradient>
                <linearGradient id="rayGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FDE047" />
                    <stop offset="100%" stop-color="#F59E0B" />
                </linearGradient>
            </defs>

            <!-- Outer ambient glow ring -->
            <circle cx="100" cy="100" r="88" fill="url(#sunGlow)" />

            <!-- Sun rays -->
            <g stroke="url(#rayGrad)" stroke-width="6" stroke-linecap="round" opacity="0.95">
                <line x1="100" y1="20" x2="100" y2="38" />
                <line x1="100" y1="162" x2="100" y2="180" />
                <line x1="20" y1="100" x2="38" y2="100" />
                <line x1="162" y1="100" x2="180" y2="100" />
                <line x1="43.4" y1="43.4" x2="56.2" y2="56.2" />
                <line x1="143.8" y1="143.8" x2="156.6" y2="156.6" />
                <line x1="43.4" y1="156.6" x2="56.2" y2="143.8" />
                <line x1="143.8" y1="56.2" x2="156.6" y2="43.4" />
            </g>

            <!-- Sun body -->
            <circle cx="100" cy="100" r="50" fill="url(#sunCore)" />
            <circle cx="85" cy="85" r="14" fill="#FFFFFF" opacity="0.3" />
        </svg>

    @elseif($type === 'cerah-berawan')
        <!-- CERAH BERAWAN (MATAHARI DI BELAKANG AWAN PUTIH - SESUAI REFERENSI) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_16px_32px_rgba(30,58,138,0.3)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <radialGradient id="sunGlowCB" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#FEF08A" stop-opacity="0.9" />
                    <stop offset="70%" stop-color="#F59E0B" stop-opacity="0.3" />
                    <stop offset="100%" stop-color="#EA580C" stop-opacity="0" />
                </radialGradient>
                <linearGradient id="sunGradCB" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFFBEB" />
                    <stop offset="35%" stop-color="#FBBF24" />
                    <stop offset="100%" stop-color="#EA580C" />
                </linearGradient>
                <linearGradient id="cloudFrontCB" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF" />
                    <stop offset="75%" stop-color="#F1F5F9" />
                    <stop offset="100%" stop-color="#CBD5E1" />
                </linearGradient>
                <linearGradient id="cloudBackCB" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#E2E8F0" />
                    <stop offset="100%" stop-color="#94A3B8" />
                </linearGradient>
            </defs>

            <!-- Sun Behind Cloud (Top Right) -->
            <g transform="translate(15, -10)">
                <circle cx="120" cy="75" r="46" fill="url(#sunGlowCB)" />
                <g stroke="#FDE047" stroke-width="4.5" stroke-linecap="round" opacity="0.9">
                    <line x1="120" y1="20" x2="120" y2="34" />
                    <line x1="165" y1="42" x2="155" y2="52" />
                    <line x1="175" y1="75" x2="161" y2="75" />
                    <line x1="75" y1="42" x2="85" y2="52" />
                    <line x1="65" y1="75" x2="79" y2="75" />
                </g>
                <circle cx="120" cy="75" r="32" fill="url(#sunGradCB)" />
                <circle cx="110" cy="65" r="9" fill="#FFFFFF" opacity="0.35" />
            </g>

            <!-- Back Cloud shadow layer -->
            <path d="M56 128h92c14 0 26-11 26-25s-11-25-24-25c-2-16-16-29-33-29-14 0-26 8-30 20-3-1-6-2-9-2-13 0-24 10-24 23 0 1 0 2 .2 3-12 3-20 15-20 28 0 16 13 29 29 29z"
                  fill="url(#cloudBackCB)" opacity="0.45" />

            <!-- Main White Front Cloud -->
            <path d="M48 144h104c15.5 0 28-12.5 28-28s-12.5-28-28-28c-1.5 0-3 .1-4.5.4-4-19-21-33.4-41.5-33.4-18 0-33.5 11.2-39.5 27.2-3.5-1.4-7.2-2.2-11-2.2-16.5 0-30 13.5-30 30 0 1.2.1 2.4.2 3.6C12.5 116 2 128.5 2 144c0 17.7 14.3 32 32 32h14z"
                  transform="translate(10, -8)"
                  fill="url(#cloudFrontCB)" />

            <!-- Soft cloud highlight -->
            <ellipse cx="98" cy="98" rx="28" ry="16" fill="#FFFFFF" opacity="0.6" />
        </svg>

    @elseif($type === 'berawan')
        <!-- BERAWAN / MENDUNG -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_16px_32px_rgba(15,23,42,0.35)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="cloudDarkBack" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#94A3B8" />
                    <stop offset="100%" stop-color="#64748B" />
                </linearGradient>
                <linearGradient id="cloudDarkFront" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF" />
                    <stop offset="60%" stop-color="#E2E8F0" />
                    <stop offset="100%" stop-color="#CBD5E1" />
                </linearGradient>
            </defs>

            <!-- Back Darker Cloud -->
            <path d="M70 110h85c14 0 25-11 25-25s-11-25-25-25c-2-18-17-32-36-32-16 0-30 10-34 24-4-2-8-3-13-3-15 0-27 12-27 27 0 2 .2 3 .5 5C32 83 22 95 22 110c0 17 13 30 30 30h18z"
                  fill="url(#cloudDarkBack)" />

            <!-- Front Cloud -->
            <path d="M46 150h108c16.5 0 30-13.5 30-30s-13.5-30-30-30c-2 0-3.5.2-5.2.6C144 71 127 57 106 57c-19 0-35 12-41 29-3.5-1.5-7.5-2.5-11.5-2.5-17 0-31 14-31 31 0 1.5.1 3 .4 4.5C9 122 0 135 0 150c0 19 15 34 34 34h12z"
                  transform="translate(10, -5)"
                  fill="url(#cloudDarkFront)" />
            <ellipse cx="106" cy="100" rx="30" ry="18" fill="#FFFFFF" opacity="0.6" />
        </svg>

    @elseif($type === 'kabut')
        <!-- KABUT (MIST / FOG) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_12px_24px_rgba(100,116,139,0.3)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="fogCloud" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#F8FAFC" />
                    <stop offset="100%" stop-color="#CBD5E1" />
                </linearGradient>
                <linearGradient id="fogLine" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#E2E8F0" stop-opacity="0.2" />
                    <stop offset="25%" stop-color="#FFFFFF" stop-opacity="0.9" />
                    <stop offset="75%" stop-color="#FFFFFF" stop-opacity="0.9" />
                    <stop offset="100%" stop-color="#E2E8F0" stop-opacity="0.2" />
                </linearGradient>
            </defs>

            <!-- Dim cloud base -->
            <path d="M50 115h100c14 0 25-11 25-25s-11-25-25-25c-2-16-16-28-34-28-15 0-28 9-32 22-3-1-6-2-9-2-13 0-24 11-24 24 0 2 .2 3 .5 5C38 88 28 99 28 115c0 15 12 27 27 27h5z"
                  fill="url(#fogCloud)" opacity="0.85" />

            <!-- Horizontal Fog streams -->
            <rect x="25" y="128" width="150" height="8" rx="4" fill="url(#fogLine)" />
            <rect x="40" y="144" width="120" height="8" rx="4" fill="url(#fogLine)" />
            <rect x="15" y="160" width="170" height="8" rx="4" fill="url(#fogLine)" />
            <rect x="55" y="176" width="90" height="7" rx="3.5" fill="url(#fogLine)" opacity="0.75" />
        </svg>

    @elseif($type === 'hujan-ringan')
        <!-- HUJAN RINGAN (AWAN DENGAN TETESAN AIR BIRU - SESUAI REFERENSI) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_16px_32px_rgba(29,78,216,0.35)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="rainCloudLight" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#FFFFFF" />
                    <stop offset="65%" stop-color="#E2E8F0" />
                    <stop offset="100%" stop-color="#94A3B8" />
                </linearGradient>
                <linearGradient id="dropGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#38BDF8" />
                    <stop offset="100%" stop-color="#2563EB" />
                </linearGradient>
            </defs>

            <!-- Main Rain Cloud -->
            <path d="M48 122h104c15 0 27-12 27-27s-12-27-27-27c-2 0-3.5.2-5 .6C143 51 127 38 107 38c-18 0-33 11-39 27-3-1-6.5-2-10-2-16 0-29 13-29 29 0 1.5.1 3 .3 4.5C14 99 5 111 5 125c0 17 14 31 31 31h12z"
                  fill="url(#rainCloudLight)" />
            <ellipse cx="106" cy="74" rx="28" ry="16" fill="#FFFFFF" opacity="0.6" />

            <!-- Diagonal Falling Raindrops -->
            <g stroke="url(#dropGrad)" stroke-width="4.5" stroke-linecap="round">
                <line x1="60" y1="140" x2="50" y2="166" />
                <line x1="85" y1="144" x2="75" y2="170" />
                <line x1="110" y1="140" x2="100" y2="166" />
                <line x1="135" y1="144" x2="125" y2="170" />

                <line x1="72" y1="172" x2="64" y2="192" opacity="0.75" />
                <line x1="98" y1="174" x2="90" y2="194" opacity="0.75" />
                <line x1="122" y1="172" x2="114" y2="192" opacity="0.75" />
            </g>
        </svg>

    @elseif($type === 'hujan-lebat')
        <!-- HUJAN LEBAT (AWAN GELAP DENGAN CURAH AIR INTENSIF) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_16px_36px_rgba(15,23,42,0.45)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="stormCloudHeavy" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#64748B" />
                    <stop offset="60%" stop-color="#334155" />
                    <stop offset="100%" stop-color="#1E293B" />
                </linearGradient>
                <linearGradient id="heavyDrop" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#60A5FA" />
                    <stop offset="100%" stop-color="#1D4ED8" />
                </linearGradient>
            </defs>

            <!-- Storm Cloud -->
            <path d="M48 116h104c15 0 27-12 27-27s-12-27-27-27c-2 0-3.5.2-5 .6C143 45 127 32 107 32c-18 0-33 11-39 27-3-1-6.5-2-10-2-16 0-29 13-29 29 0 1.5.1 3 .3 4.5C14 93 5 105 5 119c0 17 14 31 31 31h12z"
                  fill="url(#stormCloudHeavy)" />
            <ellipse cx="106" cy="68" rx="28" ry="14" fill="#94A3B8" opacity="0.4" />

            <!-- Dense Heavy Raindrops -->
            <g stroke="url(#heavyDrop)" stroke-width="5" stroke-linecap="round">
                <line x1="52" y1="134" x2="40" y2="164" />
                <line x1="76" y1="136" x2="64" y2="166" />
                <line x1="100" y1="134" x2="88" y2="164" />
                <line x1="124" y1="136" x2="112" y2="166" />
                <line x1="148" y1="134" x2="136" y2="164" />

                <line x1="44" y1="172" x2="34" y2="196" opacity="0.85" />
                <line x1="68" y1="174" x2="58" y2="198" opacity="0.85" />
                <line x1="92" y1="172" x2="82" y2="196" opacity="0.85" />
                <line x1="116" y1="174" x2="106" y2="198" opacity="0.85" />
                <line x1="140" y1="172" x2="130" y2="196" opacity="0.85" />
            </g>
        </svg>

    @elseif($type === 'badai-petir')
        <!-- BADAI PETIR (AWAN BADAI + KILATAN PETIR KUNING ELEKTRIK) -->
        <svg viewBox="0 0 200 200" class="w-full h-full drop-shadow-[0_20px_40px_rgba(245,158,11,0.4)]" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="thunderCloud" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#475569" />
                    <stop offset="60%" stop-color="#1E293B" />
                    <stop offset="100%" stop-color="#0F172A" />
                </linearGradient>
                <linearGradient id="boltGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#FFFBEB" />
                    <stop offset="50%" stop-color="#FACC15" />
                    <stop offset="100%" stop-color="#F59E0B" />
                </linearGradient>
                <filter id="boltGlow" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="4" result="blur" />
                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
            </defs>

            <!-- Storm Cloud -->
            <path d="M46 112h104c15 0 27-12 27-27s-12-27-27-27c-2 0-3.5.2-5 .6C141 41 125 28 105 28c-18 0-33 11-39 27-3-1-6.5-2-10-2-16 0-29 13-29 29 0 1.5.1 3 .3 4.5C12 89 3 101 3 115c0 17 14 31 31 31h12z"
                  fill="url(#thunderCloud)" />

            <!-- Electric Yellow Lightning Bolt -->
            <path d="M102 96l-18 36h20l-10 44 38-48h-22l14-32h-22z"
                  fill="url(#boltGrad)"
                  filter="url(#boltGlow)" />

            <!-- Raindrops flanking the lightning -->
            <g stroke="#38BDF8" stroke-width="4" stroke-linecap="round" opacity="0.8">
                <line x1="48" y1="130" x2="40" y2="154" />
                <line x1="68" y1="134" x2="60" y2="158" />
                <line x1="140" y1="130" x2="132" y2="154" />
                <line x1="160" y1="134" x2="152" y2="158" />
            </g>
        </svg>
    @endif
</div>

