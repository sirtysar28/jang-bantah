@extends('layouts.admin')
@section('page-title', 'GIS / Peta Digital')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-globe me-2"></i>Peta Digital Aset Tanah</span>
        <div class="d-flex gap-2">
            <select id="filterKecamatan" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Kecamatan</option>
                @foreach($kecamatans as $k)
                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                @endforeach
            </select>
            <select id="filterOpd" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua OPD</option>
                @foreach($opds as $o)
                <option value="{{ $o->id }}">{{ $o->singkatan ?? $o->nama }}</option>
                @endforeach
            </select>
            <select id="filterSertifikat" class="form-select form-select-sm" style="width:auto;">
                <option value="">Semua Status</option>
                <option value="ada">Sudah Sertifikat</option>
                <option value="belum">Belum Sertifikat</option>
            </select>
            <button class="btn btn-sm btn-primary" onclick="loadMap()"><i class="fas fa-sync"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="mapAdmin" style="height:calc(100vh - 150px);"></div>
    </div>
</div>

<!-- Legend -->
<div style="position:fixed;bottom:20px;left:280px;z-index:1000;">
    <div class="card p-3" style="font-size:.75rem;min-width:200px;">
        <strong class="d-block mb-2">Legenda Warna</strong>
        <span class="d-block mb-1"><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#27ae60;"></span> Aktif + Sertifikat</span>
        <span class="d-block mb-1"><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#3498db;"></span> Aktif - Belum Sertifikat</span>
        <span class="d-block mb-1"><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#e74c3c;"></span> Sengketa</span>
        <span class="d-block"><span style="display:inline-block;width:12px;height:12px;border-radius:50%;background:#95a5a6;"></span> Tidak Aktif</span>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
var map = L.map('mapAdmin').setView([1.65, 127.8], 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap | JANG BANTAH',
    maxZoom: 19
}).addTo(map);

var markers = [];

function getMarkerColor(status, sertifikat) {
    if (status === 'sengketa') return '#e74c3c';
    if (sertifikat && (sertifikat === 'terbit_sertifikat' || sertifikat === 'selesai')) return '#27ae60';
    if (status === 'aktif') return '#3498db';
    return '#95a5a6';
}

function loadMap() {
    markers.forEach(m => map.removeLayer(m));
    markers = [];

    var kec = document.getElementById('filterKecamatan').value;
    var opd = document.getElementById('filterOpd').value;
    var sert = document.getElementById('filterSertifikat').value;

    var url = '{{ route("admin.gis.data") }}?';
    if (kec) url += '&kecamatan_id=' + kec;
    if (opd) url += '&opd_id=' + opd;
    if (sert) url += '&status_sertifikat=' + sert;

    fetch(url)
        .then(r => r.json())
        .then(data => {
            data.features.forEach(f => {
                var p = f.properties;
                // GeoJSON Point: coordinates = [longitude, latitude]
                var coords = (f.geometry && f.geometry.type === 'Point' && f.geometry.coordinates)
                    ? [f.geometry.coordinates[1], f.geometry.coordinates[0]]
                    : null;
                if (!coords) return;

                var color = getMarkerColor(p.status, p.sertifikat);
                var icon = L.divIcon({
                    className: '',
                    html: '<div style="width:14px;height:14px;border-radius:50%;background:' + color + ';border:2px solid #fff;box-shadow:0 2px 5px rgba(0,0,0,.3);"></div>',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                var popup = '<div style="min-width:200px;font-size:13px;">' +
                    '<h6 style="margin:0 0 8px;">' + p.nama_aset + '</h6>' +
                    '<p style="margin:2px 0;"><b>Reg:</b> ' + p.nomor_register + '</p>' +
                    '<p style="margin:2px 0;"><b>Kec:</b> ' + (p.kecamatan || '-') + '</p>' +
                    '<p style="margin:2px 0;"><b>Desa:</b> ' + (p.desa || '-') + '</p>' +
                    '<p style="margin:2px 0;"><b>OPD:</b> ' + (p.opd || '-') + '</p>' +
                    '<p style="margin:2px 0;"><b>Luas:</b> ' + p.luas + ' m²</p>' +
                    '<p style="margin:2px 0;"><b>Nilai:</b> Rp ' + Number(p.nilai_aset).toLocaleString('id-ID') + '</p>' +
                    '<p style="margin:2px 0;"><b>Status:</b> ' + p.status + '</p>' +
                    '<p style="margin:2px 0;"><b>Sertifikat:</b> ' + (p.sertifikat || '-') + '</p>' +
                    (p.foto ? '<img src="' + p.foto + '" style="width:100%;height:120px;object-fit:cover;border-radius:8px;margin-top:8px;">' : '') +
                    '</div>';

                var marker = L.marker(coords, {icon: icon}).addTo(map).bindPopup(popup);
                markers.push(marker);
            });

            if (markers.length > 0) {
                var group = L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
        });
}

loadMap();
</script>
@endpush
