@extends('layouts.app')

@section('content')
<html>
    <head>
        <link href="{{ asset('css/leaflet.css') }}" rel="stylesheet">
        <link href="{{ asset('css/leaflet.draw.css') }}" rel="stylesheet">
        <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
      <meta charset="utf-8" />

      <title>A Leaflet map!</title>

  </head>
  
  <body>
    <div style="width:250px;height:400px;line-height:3em;overflow:scroll;padding:5px;display: inline-block;">
      <div id="user-list-holder">
          <input type="text" class="fuzzy-search" />
        <ul id="user-list" class="list" style="display: inline-block">
          <li id = "first-item"><h3 class="name">Name</h3>
              <p class="tag">Tag</p>
          </li>
        </ul> 
        <ul class="pagination"></ul>
      </div>
    </div>
      <div id="map" style="width: 600px; height: 400px; display: inline-block"></div>
  </body>
 

    <script src="{{ mix('js/app.js') }}"></script>
    <script>
          
        var map = L.map("map", {
        crs: L.CRS.Simple,
        minZoom: -4,
        }); //CRS simple referring to normal coordinate value
        var bounds = [
            [0, 0],
            [505, 598]
        ]; // height and width of image is set
        var bounds2 = [
            [0, 0],
            [190, 286]
        ]; // height and width of image is set
        map.fitBounds(bounds);

        //TODO: Programmatically find image bounds
        var image = L.imageOverlay("{{url('/images/floorplan.png')}}", bounds).addTo(map);
        var image2 = L.imageOverlay("{{url('/images/floorplan2.png')}}", bounds2);

        var baseLayers = {
          "drawnItems1": image,
          "drawnItems2": image2
        };
        L.control.layers(baseLayers).addTo(map);


        var test = {layer: "drawnItems1"};

        //TODO Allow for multiple floors/buildings
        var drawnItems = new L.FeatureGroup();
        var drawnItems1 = new L.LayerGroup();
        var drawnItems2 = new L.LayerGroup();
        var drawnItems3 = new L.LayerGroup();
        var drawnItems4 = new L.LayerGroup();

        var drawnLayers = {
        "drawnItems1": drawnItems1,
        "drawnItems2": drawnItems2,
        "drawnItems3": drawnItems3,
        "drawnItems4": drawnItems4
        };

        map.addLayer(drawnItems);
        map.addLayer(drawnItems1);

        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems
            }
        });
        map.addControl(drawControl);

        L.marker({lng: 0,lat: 0}).bindTooltip(
                "string"
            ).addTo(drawnLayers["drawnItems1"]);
            
        L.circle({lng: 20,lat: 20}, {radius: 100}).addTo(drawnLayers["drawnItems1"]);
    
        drawnLayers["drawnItems1"].addLayer(L.marker({lat: 100, lng: 100}));
        drawnLayers["drawnItems2"].addLayer(L.marker({lat: 200, lng: 200}));
        drawnLayers["drawnItems3"].addLayer(L.marker({lat: 300, lng: 300}));
        drawnLayers["drawnItems4"].addLayer(L.marker({lat: 400, lng: 400}));
        map.on('draw:created', function (e) {
            var type = e.layerType,
                layer = e.layer;
            console.log(layer);
           
            drawnItems1.addLayer(layer);
            $('.leaflet-control-layers input').get("drawnItems1").click();
          //dialog.dialog('open');

          console.log('On draw:created', e.target);
          console.log(e.type, e);
          console.log(e.layerType);
          console.log(e.layer.toGeoJSON().geometry.coordinates);
       
        });

        map.on('baselayerchange', function(e) {
          //alert('Changed to ' + e.name);
          map.removeLayer(drawnItems1);
          map.removeLayer(drawnItems2);
          map.removeLayer(drawnItems3);
          map.removeLayer(drawnItems4);
          map.addLayer(drawnLayers[e.name]);
          // if (e.name == "image1"){
          //   map.addLayer(drawnItems1);
          // }else if (e.name == "image2"){
          //   map.addLayer(drawnItems2);
          // }
        });

      </script>
    </body>
    </html>
@endsection