@extends('layouts.app')

@section('content')
<html>
    <head>
        <link href="{{ asset('css/map/leaflet.css') }}" rel="stylesheet">
        <link href="{{ asset('css/map/leaflet.draw.css') }}" rel="stylesheet">
        <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/views/map/functions.js')}}"></script>
      <meta charset="utf-8" />

      <title>A Leaflet map!</title>

    </head>

    <body>

    <!-- Title & Add-Button -->
    <div class="row mb-2 mb-xl-3">
        <div class="col-auto d-none d-sm-block">
            <h3><strong>Maps</strong> Management</h3>
        </div>
        <div class="col-auto ml-auto text-right mt-n1">
            @can('map-edit')
            <a href="{{ url('map/1/edit') }}"class="btn btn-primary">Edit Map</a>
            @endcan
        </div>
    </div>
    
    <div style="width:250px;height:400px;line-height:3em;overflow:scroll;padding:5px;display: inline-block;">
        <div id="user-list-holder">
            <input type="text" class="fuzzy-search" placeholder="Search" />
     
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


    <script>
        $( function() {
            var dialog, form,

            //allFields = $( [] ).add( name ).add( mac ).add( location ),
            tips = $( ".validateTips" );
            gatewayZones = <?php echo $gatewayZones; ?>;
            currentFloor = "";
            readers = <?php echo $readers; ?>;
            building = <?php echo $building; ?>;
            floors = <?php echo $floors; ?>;
           
            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: [25,25]
            });

            var set_delay = 5000;
            var dataSet;

            var listSet = false;
            var userList;

            getUserLocation = function(id){
                var redIcon = L.icon({
                    iconUrl: "{{url('/css/images/redmarker.png')}}",
                    iconSize: [50,50],
                    className: 'blinking'
                });
                console.log(id);
                $.ajax({
                    url: "{{ url('user/get') }}",
                    method: 'get',        
                    data: { 
                        id: id 
                    },   
                    success: function(data){
                        console.log(data);
                        removeAll(drawnLayersArray, 'temp');
                        drawUserLocation(data, drawnLayers, gatewayZones, floorIndex, redIcon);
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
            }
            getUserData = function(){
                $.ajax({
                    url: "{{ url('user-position') }}",
                    method: 'get',             
                    success: function(data){
                        console.log(data);
                        dataSet = data;
                        
                        var options = {
                            valueNames: [
                            'name', 
                            'tag',
                            { data: ['id'] }
                            ],
                            page: 10,
                            pagination: true
                        };

                        if (listSet === false){
                            //var 
                            userList = new List('user-list-holder', options);

                            //userList.remove("name", "Name"); 
                            userList.clear();
                    
                            $('#user-list').on('click', 'li', function() {
                                getUserLocation(this.getAttribute("data-id"));    
                            })
                            for (var i = 0; i < data.length; ++i) {
                                userList.add({name: data[i].user.name, tag: data[i].tag_mac, id: data[i].id});
                            }
                            listSet = true;

                        }

                        removeAll(drawnLayersArray, 'tempall');
                        for (var i = 0; i < data.length; ++i) {
                            // userList.add({name: data[i].user.name, tag: data[i].tag_mac, id: data[i].id});

                            var redIcon = L.icon({
                                iconUrl: "{{url('/css/images/redmarker.png')}}",
                                iconSize: [50,50],
                                className: 'blinking'
                                });
                                addTooltip(data[i], drawnLayers, gatewayZones, redIcon);
                            }
                        
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    error: function(xhr, request, error){
                        console.log(xhr.responseText);
                    },
                })
                .always(function () {
                   setTimeout(getUserData, set_delay);
                });
            }
            getUserData();

        var map = L.map("map", {
            crs: L.CRS.Simple,
            minZoom: -4,
        }); //CRS simple referring to normal coordinate value
       
        //Map
        var drawnLayers = new Object();
        var drawnLayersArray = []
        var floorIndex = new Object();
        var baseLayer = new Object();
        var bounds = new Object();
        
        //Add Empty Base Layer to map
        var layercontrol = L.control.layers(baseLayer).addTo(map);

        //Add gatewayzones and floor image to appropriate layer
        for (var i = 0; i < floors.length; i++) {
            alias = floors[i].alias;

            if(floors[i].map === null){
                url = "/storage/greyimage.png"
            }else{
                url = floors[i].map.url;
            }
            if (alias == null){
                alias = "Floor ".concat(floors[i].number.toString());
            };
            drawnLayers[alias] = new L.featureGroup();
            drawnLayersArray.push(drawnLayers[alias]);
            floorIndex[alias] = i;
            var img = new Image();
            img.src = url;
            baseLayer[alias] =  L.imageOverlay("storage/greyimage.png", [[0,0], [36, 35]]);;
            img.onload = (function (alias, url) {
                return function() {
                    bounds[alias] =  [[0,0], [this.height, this.width]];
                    baseLayer[alias] = L.imageOverlay(url, bounds[alias]);
                 
                    // for (var key in drawnLayers){
                    //     map.removeLayer(drawnLayers[key]);
                    // }
                    // map.addLayer(drawnLayers[alias]);
                    currentFloor = alias;
                    map.fitBounds(bounds[alias]);
                    console.log(baseLayer);
                    layercontrol.remove();
                    layercontrol = L.control.layers(baseLayer).addTo(map);
                    $('.leaflet-control-layers input').get(floorIndex[currentFloor]).click();
                }
            }(alias, url));  
        };
  
        drawZones(gatewayZones, drawnLayers, btIcon);

        map.on('baselayerchange', function(e) {
            for (var key in drawnLayers){
                map.removeLayer(drawnLayers[key]);
            }
            map.addLayer(drawnLayers[e.name]);
            currentFloor = e.name;
         
        });

    });
    </script>

@endsection