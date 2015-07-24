<?php
$cities = array();

foreach ($sr->metadata as $sys_name => $class) {
    if (SEO_RETS_PLUGIN::is_type_hidden($sys_name)) {continue;}
    sort($class->fields->city->values);
    $cities[$sys_name] = $class->fields->city->values;
}

wp_enqueue_script('sr_method_google-map',$this->js_resources_dir.'google-map.js',array( 'jquery' ));
wp_print_scripts(array('sr_method_google-map'));
?>

<script type="text/javascript">
var listings = [];
var cities   = <?php echo json_encode($cities)?>;
function addCommas(nStr) {nStr += '';x = nStr.split('.');x1 = x[0];x2 = x.length > 1 ? '.' + x[1] : '';var rgx = /(\d+)(\d{3})/;while (rgx.test(x1)) {x1 = x1.replace(rgx, '$1' + ',' + '$2');}return x1 + x2;}

var map;
var markers = [];
var bounds;
var geocoder;

jQuery(function($) {

    geocoder = new google.maps.Geocoder();

    $("#property-type").change(function() {
        $("#cities").html("");
        $("#cities").append($("<option /" + ">").val("").text("Any"));
        $.each(cities[$(this).val()], function(index, city) {
            $("#cities").append($("<option /" + ">").val(city).text(city));
        });
    });


    var update_map = function() {
        var form_data = {};

        if ( <?php echo  (isset($params['onlymylistings']) && strtolower($params['onlymylistings']) != "no") ? "true" : "false" ?> ) {
            form_data.onlymylistings = "yes";
        }

        if ( <?php echo  isset($params['limit']) ? "true" : "false" ?> ) {
            form_data.limit = "<?php echo isset($params['limit']) ? $params['limit'] : "false" ?>";
        }

        $("#search-form select, #search-form input[type=text]").each(function() {
            if ( $(this).val() != "" ) form_data[this.name] = $(this).val();
        });

        $.ajax({
            url: "<?php echo get_bloginfo('url')?>/sr-ajax?action=map-search",
            type: "post",
            data: form_data,
            success: function(data) {
                window.listings = data.result;
                listings = data.result;

                for (var x = 0; x < markers.length; x++) {
                    markers[x].remove();
                }

                markers = [];

                bounds = new google.maps.LatLngBounds();
                var bindlisting = function(listing) {
                    return function(results, status) {
                        if ( status == google.maps.GeocoderStatus.OK ) {
                            if (typeof results[0].geometry.location!='undefined'){

                                listing.lat = results[0].geometry.location.lat();
                                listing.lng = results[0].geometry.location.lng();
                                markers.push(new SR_InfoMarker(listing));

                            }
                        } else {
                            //alert(JSON.stringify(status));
                        }
                    };
                };

                var needs_geocoding = [];
                var mylistings = [];
                for (var n = 0; n < listings.length; n++) {
                    if (!isNaN(listings[n].lat) && !isNaN(listings[n].lng) && listings[n].lat != 0 && listings[n].lng != 0) {
//			    		markers.push(new SR_InfoMarker(listings[n]));
                    }
                    else if (!isNaN(listings[n].latitude) && !isNaN(listings[n].longitude) && listings[n].latitude != 0 && listings[n].longitude != 0) {
                        listings[n].lat=listings[n].latitude;
                        listings[n].lng=listings[n].longitude;
                    }
                    else {

                        needs_geocoding.push({
                            index: n,
                            address: listings[n].address + " " + listings[n].city + ", " + listings[n].state
                        });
                    }
                }

                if ( needs_geocoding.length > 0 ) {
                    $.ajax({
                        url: '<?php echo get_bloginfo('url')?>/sr-ajax?action=geocode',
                        type: 'post',
                        data: {
                            geocode: JSON.stringify(needs_geocoding)
                        },
                        success: function(response) {
                            for ( var x in response.geocode ) {
                                if(typeof response.geocode[x].latitude!='undefined' ){
                                    window.listings[response.geocode[x].index].lat = response.geocode[x].latitude;
                                    window.listings[response.geocode[x].index].lng = response.geocode[x].longitude;
                                }
                            }
                            displayListings(window.listings);
                        }
                    });
                }
                else{
                    displayListings(listings);
                }
            }
        });
    };

    function calc_average_distance_row(countResponseStart)
    {
        var distance=new Array();
        var row;
        for (var i in listings) {
            row =0;
            distance[i]=new Array();
            if (typeof listings[i]!='object'){
                continue;
            }
            for (var z in listings) {
                if (typeof listings[z]!='object'){
                    continue;
                }
                if (i != z) {

                    distance[i][z] = (listings[i]['lat'] - listings[z]['lat']) * (listings[i]['lat'] - listings[z]['lat']);
                    distance[i][z] += (listings[i]['lng'] - listings[z]['lng']) * (listings[i]['lng'] - listings[z]['lng']);
                    distance[i][z] = Math.sqrt(distance[i][z]);

                } else {
                    distance[i][z] = 0;
                }

                row += distance[i][z];

            }
            var countResponse =listings.length;
            distance[i][countResponseStart] = row / (countResponse);
        }
        return distance;
    }

    function calc_average_distance_table(distance, countResponseStart, countResponse)
    {
        var averageDistance = 0;
        for (var i=0 in distance) {
            if (typeof listings[i]!='object'){
                continue;
            }
            averageDistance += distance[i][countResponseStart];
        }

        averageDistance = averageDistance / countResponse;
        return averageDistance;
    }

    function get_amount_of_elemnts_array(array){
        var amount=0;
        for (var i in array){
            amount++
        }
        return amount;
    }
    function check_on_zero(distance1, countResponseStart)
    {
        var k = 0;

        for (var i in distance1) {
            if (distance1[i]==0) {
                k++;
            }

        }
        var count_distance = get_amount_of_elemnts_array(distance1)-1;
        var amountOfCut = k + countResponseStart - count_distance;
        if (amountOfCut >= count_distance / 1.5) {
            return true;
        } else {
            return false;
        }
    }

    function displayListings(source){
        var f = true;
        var notFirst = false;
        var countResponseStart =source.length;
        while (f) {
            f = false;
            var distance = calc_average_distance_row(countResponseStart);
            var countResponse = get_amount_of_elemnts_array(source)-1;
            var averageDistance = calc_average_distance_table(distance, countResponseStart, countResponse);
            averageDistance *= 1.5;
            var firstIndex = 0;
            while (firstIndex < distance.length && distance[firstIndex] === undefined) {
                firstIndex++;
            }
            if (check_on_zero(distance[firstIndex], countResponseStart) && notFirst) {
                break;
            }

            for (var i in source ){

                if (typeof source[i]!='object'){
                    continue;
                }
                if(distance[i][countResponseStart] > averageDistance) {
                    f = true;
                    delete(source[i]);
                    notFirst = true;
                }
            }


        }
        for (var i in source ){
            if (typeof source[i]!='object'){
                continue;
            }
            markers.push(new SR_InfoMarker(source[i]));
        }
        // markers[0].open();
    }

//    $("#search-btn").click(update_map);

    $('#sr-zip').change(update_map);

    $("#search-form select").change(update_map);

    var myLatlng = new google.maps.LatLng(39.83, -98.58);
    var myOptions = {
        zoom: 4,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    bounds = new google.maps.LatLngBounds();
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    update_map();
});

function SR_InfoMarker(listing) {

    var infowindow = new google.maps.InfoWindow({
        content: '<table><tr><td><a target="_parent" href="<?php echo get_bloginfo('url')?>' + listing.url + '"><img style="width:130px;height:86px;" src="http://img.seorets.com/<?php echo $sr->feed->server_name?>/' + listing.seo_url + '-' + listing.mls_id + '-1.jpg" /' + '></a></td><td valign="top" style="padding-left:5px;"><strong><a target="_parent" href="<?php echo get_bloginfo('url')?>' + listing.url + '">' + listing.address + '</a></strong><br /' + '>Price: $' + addCommas(listing.price) + '<br /' + '>Bedrooms: ' + listing.bedrooms + '<br /' + '>Baths: ' + listing.baths_full + '</td></tr></table>'
    });


    var myLatLng = new google.maps.LatLng(listing.lat, listing.lng);

    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: listing.address,
        icon: "<?php echo $sr->plugin_dir?>resources/images/marker.png"
    });

    google.maps.event.addListener(marker, 'click', function() {
        for (var x = 0; x < markers.length; x++) {
            markers[x].close();
        }
        infowindow.open(map, marker);
    });

    this.open = function() {
        infowindow.open(map, marker);
    };

    this.close = function() {
        infowindow.close();
    };

    this.remove = function() {
        marker.setMap(null);
    };

    bounds.extend(myLatLng);
    map.fitBounds(bounds);
}

</script>
<table style="width:100%;margin-bottom:10px;" id="search-form">
    <tr>
        <td colspan="2"><strong>A maximum of 25 listings will be shown at one time. Please use the options below to further refine your search.</strong></td>
    </tr>
    <tr>
        <td>
            <table style="width:100%;">
                <tr>
                    <td>
                        Property Type:
                    </td>
                    <td>
                        <select style="width: 207px;" id="property-type" name="type">
                            <?php foreach ( $sr->metadata as $sys_name => $class ):
                                if (SEO_RETS_PLUGIN::is_type_hidden($sys_name)) {continue;}
                                ?>
                                <?php if ( $params['type'] == $sys_name || $params['type'] == $class->pretty_name ): ?>
                                <option value="<?php echo $sys_name?>" selected><?php echo $class->pretty_name?></option>
                            <?php else: ?>
                                <option value="<?php echo $sys_name?>"><?php echo $class->pretty_name?></option>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        City:
                    </td>
                    <td>
                        <select style="width: 207px;" id="cities" name="city">
                            <option value="" selected="selected">Any</option>
                            <?php foreach ($sr->metadata->res->fields->city->values as $city): ?>
                                <option><?php echo $city?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Price:
                    </td>
                    <td>
                        <select name="price-low">
                            <option value="">Any</option>
                            <option value="100000">$100,000</option>
                            <option value="250000">$250,000</option>
                            <option value="500000">$500,000</option>
                            <option value="750000">$750,000</option>
                            <option value="1000000">$1,000,000</option>
                            <option value="1500000">$1,500,000</option>
                            <option value="2000000">$2,000,000</option>
                            <option value="3000000">$3,000,000</option>
                            <option value="4000000">$4,000,000</option>
                            <option value="5000000">$5,000,000</option>
                        </select>
                        to
                        <select name="price-high">
                            <option value="">Any</option>
                            <option value="100000">$100,000</option>
                            <option value="250000">$250,000</option>
                            <option value="500000">$500,000</option>
                            <option value="750000">$750,000</option>
                            <option value="1000000">$1,000,000</option>
                            <option value="1500000">$1,500,000</option>
                            <option value="2000000">$2,000,000</option>
                            <option value="3000000">$3,000,000</option>
                            <option value="4000000">$4,000,000</option>
                            <option value="5000000">$5,000,000</option>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
        <td>
            <table style="width:100%;">
                <tr>
                    <td>
                        Bedrooms:
                    </td>
                    <td>
                        <select name="bedrooms-low">
                            <option value="">Any</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                        to
                        <select name="bedrooms-high">
                            <option value="">Any</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        Baths:
                    </td>
                    <td>
                        <select name="baths-low">
                            <option value="">Any</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                        to
                        <select name="baths-high">
                            <option value="">Any</option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                            <option>9</option>
                            <option>10</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Zip Code:</td>
                    <td><input id="sr-zip" type="text" name="zip" style="width: 56%;" /></td>
                </tr>
            </table>
            <!--            <input type="submit" value="Search Map" id="search-btn" />-->
        </td>
    </tr>
</table>

<div id="mapHolder">
    <div id="map_canvas" style="width:100%; height:400px; float:left;"></div>
</div>
<br />
<?php
if ($this->feed->powered_by !== ''){
    $powered_by = $this->feed->powered_by;
    $powered_by_link = $this->feed->powered_by_link;
    $powered_by='Powered By <a href="'.$powered_by_link.'" target="_blank">'.$powered_by.'</a>';

} else {
    $powered_by = 'Powered By <a href="http://seorets.com/" target="_blank">SEO RETS</a>';
}
echo '<p>'.$powered_by . '</p>';
?>
