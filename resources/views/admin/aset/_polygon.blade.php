{{--
    Editor Polygon Bidang Tanah (GIS) — dipakai di form create & edit aset.
    Variabel yang dibutuhkan:
    - $polygonJson : string JSON GeoJSON Polygon geometry (atau string kosong)
    - $centerLat   : float|null  (center awal peta)
    - $centerLng   : float|null  (center awal peta)
    Input hasil: hidden input name="polygon" berisi GeoJSON geometry Polygon.
--}}
@php
    $polyCenterLat = $centerLat ?: 1.65;   // default: Halmahera Barat
    $polyCenterLng = $centerLng ?: 127.8;
    $bhumiStartUrl = ($centerLat && $centerLng)
        ? 'https://bhumi.atrbpn.go.id/peta?lat=' . $centerLat . '&lng=' . $centerLng . '&z=17'
        : 'https://bhumi.atrbpn.go.id/peta';
@endphp
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <label class="form-label fw-500 mb-0" style="font-size:.85rem;">
            <i class="fas fa-draw-polygon me-1 text-primary"></i>Polygon Bidang Tanah (GIS)
            <span class="badge bg-light text-dark border ms-1" style="font-size:.62rem;">Opsional</span>
        </label>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ $bhumiStartUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success bhumi-link" id="bhumiBtn" title="Buka Bumi ATR/BPN tepat di titik koordinat aset (bukan lokasi device)">
                <i class="fas fa-external-link-alt me-1"></i>Ambil Titik di Bumi ATR/BPN
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="polyUndo"><i class="fas fa-undo me-1"></i>Titik Terakhir</button>
            <button type="button" class="btn btn-sm btn-outline-danger" id="polyClear"><i class="fas fa-trash me-1"></i>Hapus</button>
        </div>
    </div>
    <div id="polyMap" style="height:340px;border-radius:8px;border:1px solid #dee2e6;"></div>
    <input type="hidden" name="polygon" id="polyInput" value="{{ $polygonJson }}">
    <div class="d-flex justify-content-between align-items-center mt-1">
        <small class="text-muted" style="font-size:.7rem;">
            <i class="fas fa-info-circle me-1"></i>Klik peta untuk menambah titik sudut bidang (min. 3 titik agar polygon terbentuk). Geser titik untuk menyesuaikan batas.
            Latitude/Longitude otomatis terisi titik tengah polygon. Tombol <b>Ambil Titik di Bumi ATR/BPN</b> otomatis mengikuti koordinat yang terisi.
        </small>
        <span class="badge bg-secondary ms-2 flex-shrink-0" id="polyInfo">0 titik</span>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    var mapEl = document.getElementById('polyMap');
    var input = document.getElementById('polyInput');
    if (!mapEl || !input) return;

    var map = L.map('polyMap').setView([{{ $polyCenterLat }}, {{ $polyCenterLng }}], 15);

    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap', maxZoom: 19
    });
    var satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Esri World Imagery', maxZoom: 19
    }).addTo(map);
    L.control.layers({ 'Satelit': satelit, 'Peta Jalan': osm }, null, { position: 'topright' }).addTo(map);

    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');
    var info = document.getElementById('polyInfo');
    var group = L.layerGroup().addTo(map);
    var shapeLayer = null;   // polygon/polyline preview
    var vertices = []; // array [lat, lng]

    // === Sinkronkan link Bumi ATR/BPN dengan koordinat yang diinput ===
    // Situs bhumi.atrbpn.go.id hanya pakai geolocate device bila TANPA param ?lat=&lng=,
    // jadi selalu sertakan koordinat agar peta langsung menuju titik aset.
    function syncBhumiLinks() {
        if (!latInput || !lngInput) return;
        var lat = parseFloat(latInput.value);
        var lng = parseFloat(lngInput.value);
        var url = (isNaN(lat) || isNaN(lng))
            ? 'https://bhumi.atrbpn.go.id/peta'
            : 'https://bhumi.atrbpn.go.id/peta?lat=' + lat + '&lng=' + lng + '&z=17';
        document.querySelectorAll('a.bhumi-link').forEach(function (a) { a.href = url; });
    }
    if (latInput && lngInput) {
        ['input', 'change'].forEach(function (ev) {
            latInput.addEventListener(ev, syncBhumiLinks);
            lngInput.addEventListener(ev, syncBhumiLinks);
        });
    }
    syncBhumiLinks();

    function syncInput() {
        if (vertices.length >= 3) {
            var ring = vertices.map(function (v) { return [v[1], v[0]]; }); // GeoJSON: [lng, lat]
            ring.push(ring[0].slice()); // tutup ring (titik awal = titik akhir)
            input.value = JSON.stringify({ type: 'Polygon', coordinates: [ring] });
            info.textContent = vertices.length + ' titik — polygon siap';
            info.className = 'badge bg-success ms-2 flex-shrink-0';

            // Isi Latitude/Longitude otomatis dengan titik tengah polygon bila masih kosong
            if (latInput && lngInput && latInput.value === '' && lngInput.value === '') {
                var n = vertices.length, sLat = 0, sLng = 0;
                vertices.forEach(function (v) { sLat += v[0]; sLng += v[1]; });
                latInput.value = (sLat / n).toFixed(8);
                lngInput.value = (sLng / n).toFixed(8);
                syncBhumiLinks();
            }
        } else {
            input.value = '';
            info.textContent = vertices.length + ' titik' + (vertices.length === 2 ? ' — butuh min. 3' : '');
            info.className = 'badge bg-secondary ms-2 flex-shrink-0';
        }
    }

    function redraw() {
        group.clearLayers();
        if (shapeLayer) { map.removeLayer(shapeLayer); shapeLayer = null; }
        vertices.forEach(function (v, i) {
            var m = L.marker(v, { draggable: true }).addTo(group);
            m.bindTooltip('Titik ' + (i + 1), { permanent: false });
            m.on('drag', function (e) {
                vertices[i] = [e.target.getLatLng().lat, e.target.getLatLng().lng];
                drawShape();
                syncInput();
            });
        });
        drawShape();
    }

    function drawShape() {
        if (shapeLayer) { map.removeLayer(shapeLayer); shapeLayer = null; }
        if (vertices.length === 2) {
            shapeLayer = L.polyline(vertices, { color: '#f39c12', weight: 2, dashArray: '6 6' }).addTo(map);
        } else if (vertices.length >= 3) {
            shapeLayer = L.polygon(vertices, { color: '#f39c12', weight: 2, fillColor: '#f39c12', fillOpacity: 0.25 }).addTo(map);
        }
    }

    map.on('click', function (e) {
        vertices.push([e.latlng.lat, e.latlng.lng]);
        redraw();
        syncInput();
    });

    document.getElementById('polyUndo').addEventListener('click', function () {
        vertices.pop();
        redraw();
        syncInput();
    });

    document.getElementById('polyClear').addEventListener('click', function () {
        vertices = [];
        redraw();
        syncInput();
        input.value = '';
    });

    // Muat polygon yang sudah tersimpan (form edit / old input)
    try {
        var existing = input.value ? JSON.parse(input.value) : null;
        if (existing && existing.type === 'Polygon' && existing.coordinates && existing.coordinates[0]) {
            vertices = existing.coordinates[0]
                .filter(function (c, i, arr) { return i === 0 || c[0] !== arr[0][0] || c[1] !== arr[0][1]; }) // buang titik penutup duplikat
                .map(function (c) { return [c[1], c[0]]; });
            redraw();
            syncInput();
            if (vertices.length) {
                map.fitBounds(L.polygon(vertices).getBounds().pad(0.3));
            }
        } else if (latInput && lngInput && latInput.value !== '' && lngInput.value !== '') {
            map.setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 17);
        }
    } catch (e) { /* polygon rusak — abaikan */ }
})();
</script>
@endpush
