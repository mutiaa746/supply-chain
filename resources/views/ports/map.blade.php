@extends('layouts.app')

@section('content')

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>

#map{
height:700px;
border-radius:12px;
}

.info-card{

border-radius:12px;

box-shadow:0 2px 10px rgba(0,0,0,.1);

padding:20px;

background:#fff;

}

.leaflet-popup-content{

font-size:14px;

}

</style>

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h1 class="fw-bold">

Port Map

</h1>

<p class="text-muted">

Supply Chain Port Monitoring - Global Ports Distribution

</p>

</div>

<div>

<span class="badge bg-primary p-3">

Last Update :

{{ $lastUpdate }}

</span>

</div>

</div>

<div class="card shadow mb-4">

<div class="card-body p-0">

<div id="map"></div>

</div>

</div>

<div class="row">

<div class="col-md-3">

<div class="info-card">

<h5>Total Ports</h5>

<h2 class="text-danger">

{{ $totalPorts }}

</h2>

</div>

</div>

<div class="col-md-3">

<div class="info-card">

<h5>Countries</h5>

<h2 class="text-success">

{{ $totalCountries }}

</h2>

</div>

</div>

<div class="col-md-3">

<div class="info-card">

<h5>Active Ports</h5>

<h2 class="text-primary">

{{ $activePorts }}

</h2>

</div>

</div>

<div class="col-md-3">

<div class="info-card">

<h5>Updated</h5>

<h6>

{{ $lastUpdate }}

</h6>

</div>

</div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

var street=L.tileLayer(

'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

{

maxZoom:19

}

);

var satellite=L.tileLayer(

'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',

{

maxZoom:19

}

);

var map=L.map('map',{

center:[20,110],

zoom:2,

layers:[street]

});

var baseMaps={

"Street Map":street,

"Satellite":satellite

};

L.control.layers(baseMaps).addTo(map);

var redIcon=L.icon({

iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',

shadowUrl:'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',

iconSize:[25,41],

iconAnchor:[12,41],

popupAnchor:[1,-34],

shadowSize:[41,41]

});

@foreach($ports as $port)

@if($port->latitude && $port->longitude)

L.marker(

[

{{ $port->latitude }},

{{ $port->longitude }}

],

{

icon:redIcon

}

)

.addTo(map)

.bindPopup(`

<h5>

{{ $port->port_name }}

</h5>

<hr>

<b>Country :</b>

{{ $port->country_name ?? ($port->country->country_name ?? '-') }}

<br>

<b>Status :</b>

{{ $port->status }}

<br>

<b>Harbor Type :</b>

{{ $port->harbor_type }}

<br>

<b>Harbor Size :</b>

{{ $port->harbor_size }}

`);

@endif

@endforeach

</script>

@endsection