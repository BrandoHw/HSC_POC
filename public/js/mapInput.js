var map;
var service;
var infoWindow;
var pos;
var currentLocation;
var placeSearch, address;
var place;

// //The variables you want to recieve from Google
// //when the address is selected
// var componentForm = {
//   street_number: 'short_name',
//   route: 'long_name',
//   locality: 'long_name',
//   administrative_area_level_1: 'long_name',
//   postal_code: 'short_name'
// };


function initMap() {
  
  infoWindow = new google.maps.InfoWindow();
  //Finds your current location and displays it on the map
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        currentLocation = new google.maps.LatLng(pos.lat, pos.lng);
        map = new google.maps.Map(document.getElementById('map'), {
          center: currentLocation,
          zoom: 15
        });
    },
    function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  } 
  else {
    // Browser doesn't support Geolocation
    handleLocationError(false, infoWindow, map.getCenter());
  }

  //Calls function that autocompletes form
  initAutocomplete();
}

  
function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: The Geolocation service failed."
      : "Error: Your browser doesn't support geolocation."
  );
  infoWindow.open(map);
}

function initAutocomplete() {
  // Create the address object, restricting the search predictions to
  // geographical location types.
  address = new google.maps.places.Autocomplete(document.getElementById('address'));

  // Avoid paying for data that you don't need by restricting the set of
  // place fields that are returned to just the address components.
  address.setFields(['formatted_address', 'geometry']);
  // address.setFields(['address_component', 'formatted_address', 'geometry', 'name', 'type', 'icon']);
  

  // When the user selects an address from the drop-down, populate the
  // address fields in the form.
  address.addListener('place_changed', fillInAddress);
  }

function fillInAddress() {
  // Get the place details from the address object.
  place = address.getPlace();

  if (!place.geometry) {
    // User entered the name of a Place that was not suggested and
    // pressed the Enter key, or the Place Details request failed.
    window.alert("No details available for input: '" + place.name + "'");
    return;
  }
  
  // for (var component in componentForm) {
  //   document.getElementById(component).value = '';
  //   document.getElementById(component).disabled = false;
  // }

  // // Get each component of the address from the place details,
  // // and then fill-in the corresponding field on the form.
  // for (var i = 0; i < place.address_components.length; i++) {
  //   var addressType = place.address_components[i].types[0];
  //   if (componentForm[addressType]) {
  //     var val = place.address_components[i][componentForm[addressType]];
  //     document.getElementById(addressType).value = val;
  //   }
  // }
  
  lat = place.geometry.location.lat();
  lng = place.geometry.location.lng();

  document.getElementById('address_latitude').value = lat;
  document.getElementById('address_longitude').value = lng;

  //Map zooms in to the location given
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: +lat, lng: +lng},
    zoom: 15
  });
  
  //Map marker is created and displays address
  var marker = new google.maps.Marker({
    map: map,
    position: {lat: +lat, lng: +lng},
    animation: google.maps.Animation.DROP
  });

  google.maps.event.addListener(marker, "click", function() {
    var content = '<div><strong>'+ name + '</strong></div>'
      + place.formatted_address;
    infoWindow.setContent(content);
    infoWindow.open(map, this);
  });
}

// Bias the address object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle(
          {center: geolocation, radius: position.coords.accuracy});
      address.setBounds(circle.getBounds());
    });
  }
}

 