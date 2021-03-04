/* Stepper */
var stepper = new Stepper($('.bs-stepper')[0]);
  
function showZoneModal(){
    $('#createZoneModal').modal('show');
    stepper.to(1);
    //Uncheck
    //Unfilter
};

function createLocation(){
    console.log("createLocation")
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //     // $('#name').css('border', '');
    //     // $('#detail').css('border', '');
    //     // $('#floor_num').css('border', '');
    //     // $('#address').css('border', '');
    //     // $('#createBuildingNameAlert').remove();
    //     // $('#createBuildingDetailAlert').remove();
    //     // $('#createBuildingFloorAlert').remove();
    //     // $('#createBuildingAddressAlert').remove();
    //     // $('#createBuildingOtherAlert').remove();
        
    var data = {
        location_description: $('#name-location-form').val(),
        location_type_id: $('#selType-location-form').select2('data')[0].type_id,
        floor: $('#selFloor-location-form').select2('data')[0].id,
    };

    console.log(data.location_description);
    console.log(data.location_type_id);
    console.log(data.floor);
    $.ajax({
        url: '/location',
        type: "POST",
        data: data,
        success:function(response){
            if($.isEmptyObject(response['success'])){
                var errors = response['errors'];

                Object.keys(errors).forEach(function(key){
                    switch(key) {
                        case 'name':
                            // $('#createBuildingNameField')
                            //     .append('<div class="alert-danger" id="createBuildingNameAlert">' + errors[key][0] + '</div>');
                            // $("#name").css("border", "1px solid red");
                            break;

                        case 'detail':
                            // $('#createBuildingDetailField')
                            //     .append('<div class="alert-danger" id="createBuildingDetailAlert">' + errors[key][0] + '</div>');
                            // $("#detail").css("border", "1px solid red");
                            break;

                        case 'floor_num':
                            // $('#createBuildingFloorField')
                            //     .append('<div class="alert-danger" id="createBuildingFloorAlert">' + errors[key][0] + '</div>');
                            // $("#floor_num").css("border", "1px solid red");
                            break;
                        case 'address':
                            // $('#createBuildingAddressField')
                            //     .append('<div class="alert-danger" id="createBuildingAddressAlert">' + errors[key][0] + '</div>');
                            // $("#address").css("border", "1px solid red");
                            break;
                        default:
                            // $('#createBuildingModalBody')
                            //     .prepend('<div class="alert-danger" id="createBuildingOtherAlert">' + errors[key][0] + '</div>');
                    }
                 })
                
            } else {
                notyf.success(response['success']);
                console.log("RESPONSE")
                // var building = response['building'];
                // var id = building['id'];

                // companyBuildingTable.row.add([
                //     id,
                //     building['name'],
                //     building['address'],
                //     building['floor_num'],
                //     '<a href="#" id="'+ id + '" onClick="deleteBuilding(this.id)">@svg("trash", "feather-trash align-middle")</a>'
                // ]).node().id = 'trBuilding-' + id;
                // companyBuildingTable.draw(false);

                // $('#trBuilding-' + id +' td:eq(1)').attr('id', 'tdBuildingName-'+id);
                // $('#trBuilding-' + id +' td:eq(2)').attr('id', 'tdBuildingAddress-'+id);
                // $('#trBuilding-' + id +' td:eq(3)').attr('id', 'tdBuildingFloor-'+id); 
                // $('#trBuilding-' + id +' td:eq(4)').attr('id', 'tdBuildingAction-'+id); 
                // $('#trBuilding-' + id +' td:eq(4)').attr('class', 'table-action'); 
                // $('#trBuilding-' + id +' td:eq(4)').css('margin', '0px'); 

                locationTable.ajax.reload();
                $('#locationTableCollapsible').collapse('show');
                $('#locationForm').collapse('hide');

                // buildingsId.push(id);
            }
            
        },
        error:function(error){
            console.log(error)
        }
    })
}

function drawMarkers(data){
    readerZone = data;
    console.log(readerZone);
    var mac_addr_s = readerZone.mac_addr;
    var location_s = readerZone.gateway.location.location_description;
    var string = "<b>Mac</b>:".concat(mac_addr_s,"<br> <b>Location</b>: ",location_s);
    var floor = readerZone.gateway.location.floor_level.alias;

    if (floor == null){
        floor = "Floor ".concat( readerZone.gateway.location.floor_level.number.toString());
    };
  
    if (readerZone.geoJson.type == "Polygon"){
    //   var corner1 = readerZone.geoJson.coordinates[0][0].reverse().map(Number);
    //   var corner2 = readerZone.geoJson.coordinates[0][2].reverse().map(Number);

    //   //Array is passed by reference so undo the reversal
    //   readerZone.geoJson.coordinates[0][0].reverse();
    //   readerZone.geoJson.coordinates[0][2].reverse();

    //   var rectangle = L.rectangle([corner1, corner2]);
    //   rectangle.id = readerZone.id;
    //   rectangle.addTo(drawnLayers[floor]);

        polygon = readerZone.geoJson.coordinates[0];
        polygon.forEach( function (item, index){
            item.reverse();
        })
        console.log(polygon);
        L.polygon(polygon).addTo(drawnLayers[floor]);
        var marker = L.marker(readerZone.geoJson.marker, {icon: btIcon}).bindTooltip(
            string
        );
        marker.id = readerZone.id;
        marker.addTo(drawnLayers[floor]);
    }else{
        var center = readerZone.geoJson.coordinates;
        var radius = readerZone.geoJson.radius;
        var circle = L.circle({lng: center[0],lat: center[1]}, {radius: radius});
        circle.id = readerZone.id;
        circle.addTo(drawnLayers[floor]);
        var marker = L.marker(readerZone.geoJson.marker, {icon: btIcon}).bindTooltip(
            string
        );
        marker.id = readerZone.id;
        marker.addTo(drawnLayers[floor]);
    }

};

function addGatewayZone() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
            url: "/zones",
            method: 'post',             
            data: {location: locationTable.rows( { selected: true } ).data()[0].location_master_id,
                mac_addr: selectedData.mac_addr,
                geoJson: geoJson
            },
            success: function(data){
                console.log(data);
                gatewayZones.push(data['gatewayZoneEager']);
                drawMarkers(data['gatewayZoneEager']);
                selectData = selectData.filter(reader => reader.mac_addr != selectedData.mac_addr);
                $("#selReader").empty();
                $("#selReader").select2({
                    data: selectData.filter(gateway => gateway.alias == currentFloor || gateway.alias == null)
                });

                selectedData = $('#selReader').select2('data')[0];
                setupList(data['gatewayZones'], data['readers']);

                stepper.next();
                // notyf.success(response['success']);

            },
            error: function(xhr, request, error){
                console.log('error');
                console.log(xhr.responseText);
            },
    });
};


$(function() {
  

    var floors;
    var types;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/map/form/data',
        type: "GET",
        success:function(response){
            floors = response.floors;
            types = response.types;
            selectFloor = $.map(floors, function (obj) {
                obj.text = obj.text || obj.alias; // replace name with the property used for the text
                return obj;
            });
        
            selectType= $.map(types, function (obj) {
                obj.text = obj.text || obj.location_type; // replace name with the property used for the text
                obj.id = obj.type_id;
                return obj;
            });
        
            $("#selFloor-location-form").select2({
                data:selectFloor
            });
        
            $("#selType-location-form").select2({
                data:selectType
            });
        },
        error:function(error){
            console.log(error.responseJSON)
        }
    });

    locationTable = $('#locationTable').DataTable({
        dom: "<'row'<'col-sm-3'f><'col-sm-6 text-center pull-right'B>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        ajax: {
            url: '../../map/form/location',
        },
        columns:[
            {data: null, defaultContent: '' },
            {data: 'location_description'},
            {data: 'floor_level.alias'},
            {data: 'type.location_type'},
        ],
        select: {
            style: 'single',
        },
        buttons: {
            buttons: [{
                text: '<span class="align-right">Add Location</span>',
                className: 'btn-primary',
                id: 'addNewLocationBtn',
                action: function ( e, dt, node, config ) {
                    $('#addLocationMessage').removeClass("text-danger font-weight-bold");

                    $('#name').val("");

                    $('#locationTableCollapsible').collapse('hide');
                    $('#locationForm').collapse('show'); 
                }
            }],
            dom: {
                button: {
                    className: 'btn'
                }
            }
        },
        columnDefs: [{
            targets: 0,
            checkboxes: {
                'selectRow': true
            },
            orderable: false,
        }],
        order: [[ 0, 'asc' ]],
        paging: true,
        pageLength: 5,
        autoWidth: true,
        info: false
    })

    //Filter Location Table
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
                var floor_name =( data[2] )
                if ( floor_name == currentFloor)
                {
                    return true;
                }
                return false;
            }
    );


    $('.quitLocationCreate').on('click', function(){

        //Show 
        $('#locationTableCollapsible').collapse('show');
        $('#locationForm').collapse('hide');

        //Reset Form Values
        $('#name').val("");
        $('#detail').val("");
        $('#floor_num').val("");
        $('#address').val("");
        $('#address_latitude').val("");
        $('#address_longitude').val("");
        $('#createBuildingNameAlert').remove();
        $('#createBuildingDetailAlert').remove();
        $('#createBuildingFloorAlert').remove();
        $('#createBuildingAddressAlert').remove();
        $('#createBuildingOtherAlert').remove();

    })


    $('#nextBtn-1').on('click', function(){
        stepper.next();
    })
    $('#nextBtn-2').on('click', function(){
        addGatewayZone();
        // stepper.next();
    });

    $('#previousBtn-2').on('click', function(){
        stepper.previous();
        $('#locationForm').collapse('hide');
    });

    $('#previousBtn-3').on('click', function(){
        stepper.previous();
    });
   
})