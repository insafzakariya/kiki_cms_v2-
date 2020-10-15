jQuery(document).ready(function ($) {

    $('.gmap_input').on('keyup keypress', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('.gmap').each(function () {
        var $this = $(this);
        var longitude = $this.find('.longitude').val();
        var latitude = $this.find('.latitude').val();
        var empty_search_location = classifieds_data.empty_search_location.split(',');

        if (longitude !== '' && latitude !== '') {
            var location = new google.maps.LatLng(latitude, longitude);
        }
        else {
            var location = new google.maps.LatLng(empty_search_location[0], empty_search_location[1]);
        }


        var mapOptions = {
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: location,
            zoom: 12
        };

        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        var input = $this.find('.gmap_input');
        var searchBox = new google.maps.places.SearchBox(input[0]);
        var markers = [];

        if (longitude !== '' && latitude !== '') {
            var marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });
            google.maps.event.addListener(
                marker,
                'drag',
                function () {
                    $this.find('.longitude').val(marker.position.lng());
                    $this.find('.latitude').val(marker.position.lat());
                }
            );
            markers.push(marker);
        }

        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];


            var marker = new google.maps.Marker({
                map: map,
                title: places[0].name,
                position: places[0].geometry.location,
                draggable: true
            });
            $this.find('.longitude').val(marker.position.lng());
            $this.find('.latitude').val(marker.position.lat());
            markers.push(marker);
            google.maps.event.addListener(
                marker,
                'drag',
                function () {
                    $this.find('.longitude').val(marker.position.lng());
                    $this.find('.latitude').val(marker.position.lat());
                }
            );


            map.panTo(places[0].geometry.location);
        });
    });
});	