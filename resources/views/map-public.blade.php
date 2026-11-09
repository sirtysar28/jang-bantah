@extends('layouts.app')

@section('title', 'Peta Aset Publik - JANG BANTAH')

@section('content')
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-jb fixed-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="{{ route('home') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="height:35px;">
                <div>
                    <div style="font-size:.85rem;line-height:1;">JANG BANTAH</div>
                    <div style="font-size:.6rem;opacity:.7;">Jaringan Bank Tanah</div>
                </div>
            </a>
            <div class="ms-auto d-flex gap-2">
                <a href="{{ route('aset.publik') }}" class="btn btn-outline-light btn-sm px-3"><i class="fas fa-layer-group me-1"></i>Data Aset</a>
                <a href="{{ route('berita.publik') }}" class="btn btn-outline-light btn-sm px-3"><i class="fas fa-newspaper me-1"></i>Berita</a>
                <a href="{{ route('video.publik') }}" class="btn btn-outline-light btn-sm px-3"><i class="fab fa-youtube me-1"></i>Video</a>
                <a href="{{ route('home') }}" class="btn btn-gold btn-sm px-3"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
            </div>
        </div>
    </nav>

    <div style="padding-top:70px;">
        <!-- Stats Bar -->
        <div style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 100%);padding:10px 0;">
            <div class="container d-flex flex-wrap justify-content-center gap-3" style="font-size:.8rem;">
                <div class="d-flex align-items-center gap-2">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#e74c3c;color:#fff;font-size:.7rem;"><i class="fas fa-map-marker-alt"></i></span>
                    <span class="text-white">Total: <strong>{{ $total }}</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="filterMarkers('bersertifikat')">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#27ae60;color:#fff;font-size:.7rem;"><i class="fas fa-certificate"></i></span>
                    <span class="text-white">Bersertifikat: <strong>{{ $bersertifikat }}</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="filterMarkers('proses')">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#f39c12;color:#fff;font-size:.7rem;"><i class="fas fa-clock"></i></span>
                    <span class="text-white">Proses Sertifikasi: <strong>{{ $prosesSertifikasi }}</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="filterMarkers('belum')">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#3498db;color:#fff;font-size:.7rem;"><i class="fas fa-file-circle-xmark"></i></span>
                    <span class="text-white">Belum Sertifikasi: <strong>{{ $belumSertifikasi }}</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="filterMarkers('sengketa')">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#e74c3c;color:#fff;font-size:.7rem;"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="text-white">Sengketa: <strong>{{ $sengketa }}</strong></span>
                </div>
                <div class="d-flex align-items-center gap-2 cursor-pointer" onclick="filterMarkers('semua')">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:50%;background:#6c757d;color:#fff;font-size:.7rem;"><i class="fas fa-layer-group"></i></span>
                    <span class="text-white"><u>Semua</u></span>
                </div>
            </div>
        </div>

        <div id="mapPublic" style="height:calc(100vh - 120px);"></div>
    </div>

    <!-- Legend -->
    <div style="position:fixed;bottom:20px;right:20px;z-index:1000;">
        <div class="card p-3 shadow" style="font-size:.75rem;border:none;">
            <strong class="d-block mb-2"><i class="fas fa-list me-1"></i>Legenda</strong>
            <span class="d-flex align-items-center gap-1 mb-1 cursor-pointer" onclick="filterMarkers('bersertifikat')"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#27ae60;box-shadow:0 0 4px rgba(39,174,96,.5);"></span> Bersertifikat</span>
            <span class="d-flex align-items-center gap-1 mb-1 cursor-pointer" onclick="filterMarkers('proses')"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#f39c12;box-shadow:0 0 4px rgba(243,156,18,.5);"></span> Proses Sertifikasi</span>
            <span class="d-flex align-items-center gap-1 mb-1 cursor-pointer" onclick="filterMarkers('belum')"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#3498db;box-shadow:0 0 4px rgba(52,152,219,.5);"></span> Belum Sertifikasi</span>
            <span class="d-flex align-items-center gap-1 cursor-pointer" onclick="filterMarkers('sengketa')"><span style="display:inline-block;width:14px;height:14px;border-radius:50%;background:#e74c3c;box-shadow:0 0 4px rgba(231,76,60,.5);"></span> Sengketa</span>
        </div>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var mapAsets = {{ Illuminate\Support\Js::from($mapData) }};

var groupColors = {
    bersertifikat: '#27ae60',
    proses:        '#f39c12',
    belum:         '#3498db',
    sengketa:      '#e74c3c'
};

function createIcon(color) {
    return L.divIcon({
        className: '',
        html: '<svg viewBox="0 0 30 42" width="30" height="42" xmlns="http://www.w3.org/2000/svg"><path d="M15 0C6.7 0 0 6.7 0 15c0 11.3 15 27 15 27s15-15.7 15-27C30 6.7 23.3 0 15 0z" fill="' + color + '" stroke="#fff" stroke-width="1.5"/><circle cx="15" cy="14" r="6" fill="#fff" opacity=".9"/></svg>',
        iconSize: [30, 42],
        iconAnchor: [15, 42],
        popupAnchor: [0, -42]
    });
}

var map = L.map('mapPublic').setView([1.65, 127.8], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OSM | JANG BANTAH - Pemkab. Halbar',
    maxZoom: 18
}).addTo(map);

var allMarkers = [];
var allPolygons = [];
var markerGroups = { bersertifikat: [], proses: [], belum: [], sengketa: [] };

mapAsets.forEach(function(a) {
    var icon = createIcon(groupColors[a.group] || '#95a5a6');

    // Polygon batas bidang tanah (bila sudah digambar di GIS)
    if (a.polygon && a.polygon.type === 'Polygon' && a.polygon.coordinates && a.polygon.coordinates.length) {
        var poly = L.geoJSON(a.polygon, {
            style: {
                color: groupColors[a.group] || '#95a5a6',
                weight: 2,
                fillColor: groupColors[a.group] || '#95a5a6',
                fillOpacity: 0.3
            }
        }).addTo(map).bindPopup(a.popup, { maxWidth: 320 });
        poly.nr = a.nr;
        poly.grp = a.group;
        allPolygons.push(poly);
    }

    if (a.lat && a.lng) {
        var marker = L.marker([a.lat, a.lng], { icon: icon })
            .addTo(map)
            .bindPopup(a.popup, { maxWidth: 320 });

        marker.nr = a.nr; // nomor register — dipakai fokus QR Code
        allMarkers.push(marker);
        if (markerGroups[a.group]) {
            markerGroups[a.group].push(marker);
        }
    }
});

// Fokus bidang tanah tertentu (hasil scan QR Code ?aset=NOMOR_REGISTER)
var focusNr = {{ Illuminate\Support\Js::from($focusRegister ?? null) }};
if (focusNr) {
    var focusMarker = allMarkers.find(function(m) { return m.nr === focusNr; });
    var focusPoly = allPolygons.find(function(pg) { return pg.nr === focusNr; });
    if (focusMarker || focusPoly) {
        var focusBounds = [];
        if (focusMarker) focusBounds.push(focusMarker);
        if (focusPoly) focusBounds.push(focusPoly);
        map.fitBounds(L.featureGroup(focusBounds).getBounds().pad(0.2));
        if (focusMarker) focusMarker.openPopup();
        else if (focusPoly) focusPoly.openPopup();
    } else {
        if (allMarkers.length > 0 || allPolygons.length > 0) {
            map.fitBounds(L.featureGroup(allMarkers.concat(allPolygons)).getBounds().pad(0.1));
        } else {
            map.setView([1.65, 127.8], 11);
        }
    }
} else if (allMarkers.length > 0 || allPolygons.length > 0) {
    map.fitBounds(L.featureGroup(allMarkers.concat(allPolygons)).getBounds().pad(0.1));
} else {
    map.setView([1.65, 127.8], 11);
}

function filterMarkers(type) {
    allMarkers.forEach(function(m) { map.addLayer(m); });
    allPolygons.forEach(function(pg) { map.addLayer(pg); });
    if (type === 'semua') return;

    allMarkers.forEach(function(m) {
        var show = false;
        if (markerGroups[type] && markerGroups[type].indexOf(m) >= 0) show = true;
        if (!show) map.removeLayer(m);
    });
    allPolygons.forEach(function(pg) {
        if (pg.grp !== type) map.removeLayer(pg);
    });

    var visible = allMarkers.filter(function(m) { return map.hasLayer(m); })
        .concat(allPolygons.filter(function(pg) { return map.hasLayer(pg); }));
    if (visible.length > 0) {
        map.fitBounds(L.featureGroup(visible).getBounds().pad(0.1));
    }
}
</script>
@endpush
