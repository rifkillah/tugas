{{-- Watermark global: teks dibaca dari tabel pengaturans (MySQL) --}}
<style>
    .watermark-layer {
        position: fixed;
        inset: 0;
        z-index: 0;
        width: 100%;
        height: 100%;
        opacity: .06;
        pointer-events: none;
        user-select: none;
    }

    .wm-credit {
        position: relative;
        z-index: 1;
        margin: 16px 0 0;
        text-align: center;
        font-size: 11px;
        color: #94a3b8;
        letter-spacing: .2px;
    }
</style>

<svg class="watermark-layer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <pattern id="watermark-pattern" width="560" height="160" patternUnits="userSpaceOnUse" patternTransform="rotate(-28)">
            <text x="0" y="70" font-family="Segoe UI, Tahoma, sans-serif" font-size="22" font-weight="800" letter-spacing="5" fill="#2c3e50">
                {{ strtoupper($watermark) }}
            </text>
        </pattern>
    </defs>
    <rect width="100%" height="100%" fill="url(#watermark-pattern)" />
</svg>
