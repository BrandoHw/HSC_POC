@extends('layouts.app')

@section('style')
    <link href="{{ asset('css/map/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/leaflet.draw.css') }}" rel="stylesheet">
    <link href="{{ asset('css/map/map.css') }}" rel="stylesheet">

    <script src="{{ asset('js/mix/leaflet.js') }}"></script>
    <script src="{{ asset('js/mix/moment.js') }}"></script>
    <script src="{{ asset('js/mix/dialog.js') }}"></script>
    
    <script src="{{ asset('js/views/map/functions.js')}}"></script>
    <script src="{{ asset('js/views/map/edit_functions.js')}}"></script>

@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="iq-card">
                <div class="iq-card-body">
                    <div style='display: flex; justify-content: flex-end'>
                        <div class="custom-control custom-switch mr-1">
                            <input type="checkbox" class="custom-control-input" id="gateway-switch">
                            <label class="custom-control-label" for="gateway-switch">Hide Gateways</label>
                          </div>
                          <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="gateway-zone-switch">
                            <label class="custom-control-label" for="gateway-zone-switch">Hide boxes</label>
                          </div>
                    </div>
                    <div style='display: flex; height: 72vh;'>
                        <div class ="scroller" style="width:25%; line-height:3em;overflow:scroll;padding:5px;background-color: rgb(255, 255, 255);display: inline-block;">
                            <div id="user-list-holder">
                                <input type="text" class="search form-control round" placeholder="Search" />
                        
                            <ul id="user-list" class="list iq-chat-ui nav flex-column nav-pills" style="display: inline-block">
                                <li id = "first-item">
                                    <h3 class="name">Name</h3>
                                    {{-- <h5 class="location">Location</h5> --}}
                                    <p class="tag">Tag</p>
                                </li>
                            </ul> 
                            <ul class="pagination">
                            </ul>
                            
                            </div>
                        </div>
                        <div id="map" style="width: 75%; display: inline-block"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
    <script>
        var imageUrl = "{{url('/css/images/')}}";
        
        $( function() {

            // ii = 0;
            // $('#test_button').click(function() {
            //     if ( ii === 0){
            //         console.log("delete");
            //         removeAllGatewayInfo(drawnLayersArray, 'gateway');
            //         removeAllGatewayInfo(drawnLayersArray, 'gateway_zone');
            //         ii = 1;
            //     }else{
            //         console.log("reinsert");
            //         drawZones(gatewayZones, drawnLayers, btIcon);
            //         ii = 0;
            //     }
            // });
            // $('#gateway-switch').on('switchChange.bootstrapSwitch', function (event, state) {
            //     console.log('working');
            // }); 
            $('#gateway-switch').prop('checked', false);
            $('#gateway-zone-switch').prop('checked', false);
            $('#gateway-switch').click(function(){
               console.log('working');
               if($(this).is(':checked')){
                    removeAllGatewayInfo(drawnLayersArray, 'gateway');
                } else if ($('#gateway-zone-switch').is(':checked')) {
                    drawZones(gatewayZones, drawnLayers, btIcon);
                    removeAllGatewayInfo(drawnLayersArray, 'gateway_zone');
                } else {
                    drawZones(gatewayZones, drawnLayers, btIcon);
                }
            });

            $('#gateway-zone-switch').click(function(){
               console.log('working');
               if($(this).is(':checked')){
                    removeAllGatewayInfo(drawnLayersArray, 'gateway_zone');
                } else if ($('#gateway-switch').is(':checked')) {
                    drawZones(gatewayZones, drawnLayers, btIcon);
                    removeAllGatewayInfo(drawnLayersArray, 'gateway');
                } else {
                    drawZones(gatewayZones, drawnLayers, btIcon);
                }
            });
            var dialog, form,

            dialog = $( "#dialog-form").dialog({
                autoOpen: false,
                height: 400,
                width: 350,
                modal: true,
                dialogClass: 'dialogTitleClass',
                close: function() {
                }
            });
            //allFields = $( [] ).add( name ).add( mac ).add( location ),
            tips = $( ".validateTips" );
            gatewayZones = <?php echo $gatewayZones; ?>;
            currentFloor = "";
            building = <?php echo $building; ?>;
            floors = <?php echo $floors; ?>;
           
            var btIcon = L.icon({
                iconUrl: "{{url('/css/images/bt.png')}}",
                iconSize: ["{{Config::get('constants.icons.bt')}}", "{{Config::get('constants.icons.bt')}}"]
            });

            var set_delay = 30000;
            var dataSet;

            var listSet = false;
            var userList;

            getUserLocation = function(id){
                var redIcon = L.icon({
                    iconUrl: "{{url('/css/images/redmarker.png')}}",
                    iconSize: [50,50],
                    className: 'blinking'
                });
                // console.log(id);
                $.ajax({
                    url: "{{ url('user/get') }}",
                    method: 'get',        
                    data: { 
                        id: id 
                    },   
                    success: function(data){
                        //Id 'temp' is given to markers that are generated when clicking the user list
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
                    url: "{{ url('user-positions') }}",
                    method: 'get',             
                    success: function(data){
                        var users = data['beacons'];
                        var userCount = data['userCount'];
                        var userRunningCount = data['userRunningCount'];
                        console.log(data['beacons'])
                        console.log("running");
                        var options = {
                            valueNames: [
                            'name', 
                            'location',
                            'tag',
                            { data: ['id'] }
                            ],
                            page: 10,
                            pagination: true
                        };

                        //ListSet is not neccesary if the list needs to be redrawn every refresh
                        if (listSet === false){
                            //var 
                            userList = new List('user-list-holder', options);

                            //userList.remove("name", "Name"); 
                            userList.clear();
                    
                            $('#user-list').on('click', 'li', function() {
                                getUserLocation(this.getAttribute("data-id"));     
                            })

                            $('#user-list-marker').on('click', 'li', function() {
                                //alert(this.getAttribute("data-id")); 
                                window.location.href = "residents/".concat(this.getAttribute("data-id"), '/edit');   
                            })
                            for (var i = 0; i < users.length; ++i) {
                                var full_name;
                                if (users[i].hasOwnProperty('resident')){
                                    full_name = users[i].resident.resident_fName.concat(" ", users[i].resident.resident_lName)
                                }
                                else if (users[i].hasOwnProperty('staff')){
                                    full_name = users[i].staff.fName.concat(" ", users[i].staff.lName)
                                    if (users[i].staff.user_type != null){
                                        if (users[i].staff.user_type.type === "Nurse")
                                            full_name = '<h3 class="name" style="color:var(--nurse-color)">' + full_name + '</h3>';
                                        else
                                            full_name = '<h3 class="name" style="color:var(--staff-color)">' + full_name + '</h3>';
                                    }else{
                                        full_name = '<h3 class="name" style="color:var(--staff-color)">' + full_name + '</h3>';
                                    }
                                }
                                var location = "N/A"
                                if (users[i].gateway !== null){
                                    if (users[i].gateway.location !== null){
                                        location = users[i].gateway.location.location_description
                                    }else{
                                        location = "Gateway: ".concat(users[i].gateway.mac_addr);
                                    }
                                }
                               
                                userList.add({name: full_name, 
                                    location : location,
                                    tag: users[i].beacon_mac,
                                    id: users[i].beacon_id});
                            }
                            listSet = true;

                        }
                        //ID of tempall is given to markers that are generated for all users
                        removeAll(drawnLayersArray, 'tempall');
                        for (var i = 0; i < users.length; ++i) {
                            // userList.add({name: data[i].user.name, tag: data[i].tag_mac, id: data[i].id});
                            var reader_mac = users[i].gateway.mac_addr
                            var redIcon = L.icon({
                                iconUrl: "{{url('/css/images/redmarker.png')}}",
                                iconSize: [50,50],
                                className: 'blinking'
                                });
                            addTooltip(users[i], drawnLayers, gatewayZones, userCount[reader_mac], userRunningCount[reader_mac], dialog);

                            if (users[i].draw){
                                userRunningCount[reader_mac] = userRunningCount[reader_mac] + 1;
                            }
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
            minZoom: -3,
            maxBoundsViscosity: 1.0,
        }); //CRS simple referring to normal coordinate value

        //Map
        var drawnLayers = new Object(); //Each property of drawnLayer contains all the markers and zones of one floor
        var drawnLayersArray = [] //Array of drawnLayers, essentially an array of every floor (i.e all layers in the map can be found here)
        var floorIndex = new Object();
        var baseLayer = new Object(); //This contains the image overlays for a given floor
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

                    if (this.height === 0 || this.width === 0){
                        bounds[alias] =  [[0,0], [2339, 3309]];
                    }else{
                        bounds[alias] =  [[0,0], [this.height, this.width]];
                    }
                    baseLayer[alias] = L.imageOverlay(url, bounds[alias]);
               
                    currentFloor = alias;
                    map.fitBounds(bounds[alias]);
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

            map.setMaxBounds(bounds[currentFloor]);
         
        });

    });
    </script>

    <body>
        <div id="dialog-form" title="Residents in Location: ">
      
            <div id="user-list-holder-marker">
                <input type="text" class="fuzzy-search" placeholder="Search" />
        
            <ul id="user-list-marker" class="list" style="display: inline-block">
                <li id = "first-item-marker"><h3 class="name">Name</h3>
                    <p class="last_seen">Last Seen</p>
                    <p class="tag">Tag</p>
                </li>
            </ul> 
            <ul class="pagination"></ul>
            </div>
        </div>
    </body>
@endsection