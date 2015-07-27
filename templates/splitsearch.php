<?php
wp_enqueue_script('sr_method_google-map',$this->js_resources_dir.'google-map.js',array( 'jquery' ));
wp_print_scripts(array('sr_method_google-map'));
?>
<script type="text/javascript">

	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}


	var zoom_to;

	jQuery(function ($) {

		var map = new google.maps.Map(document.getElementById('map-canvas'), {
			zoom: 4,
			center: new google.maps.LatLng(30.375393, -86.358401),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var inbounds = false;
		var updating = false;
		var bounds = new google.maps.LatLngBounds();
		var markers = [];
		var infos = [];

		var close_infos = function () {
			for (var n = 0; n < infos.length; n++) {
				infos[n].close();
			}
		};

		zoom_to = function (index) {
			updating = true;
			$(".listing").css("background-color", "#FFF");
			close_infos();
			infos[index].open(map, markers[index]);
			var listings_el = $("#listings");
			var listing_el = $(".listing:eq(" + index + ")");
			listings_el.animate({
				scrollTop: (listings_el.scrollTop() + listing_el.position().top) - ((listings_el.height() / 2) - (listing_el.height() / 2))
			}, 1000, function () {
				listing_el.css("background-color", "#EEE");
				setTimeout(function () {
					updating = false;
				}, 1000);
			});
		};

		var update_map = function () {

			var get_form_data = function () {

				var b = map.getBounds();

				var form_data = {
					"limit": 100
				};

				var truncate_coord = function (coord, percision) {
					percision = typeof percision !== 'undefined' ? percision : 6;

					var length = coord.toString().split(".")[0].length;

					return coord.toPrecision(length + percision);
				};

				if (typeof b != 'undefined' && inbounds) {
					form_data['ne-lat'] = Math.ceil(b.getNorthEast().lat() * 1000000) / 1000000;
					form_data['ne-lng'] = Math.ceil(b.getNorthEast().lng() * 1000000) / 1000000;
					form_data['sw-lat'] = Math.floor(b.getSouthWest().lat() * 1000000) / 1000000;
					form_data['sw-lng'] = Math.floor(b.getSouthWest().lng() * 1000000) / 1000000;
				}

				$("#search-form select").each(function () {
					if ($(this).val() != "") form_data[this.name] = $(this).val();
				});

				return form_data;
			};

			var map_listings = function (listings) {

				var add_listings_to_map = function () {
					for (var n = 0; n < markers.length; n++) {
						markers[n].setMap(null);
					}
					markers = [];
					bounds = new google.maps.LatLngBounds();


					$("#listings").html("");

					for (var n = 0; n < listings.length; n++) {

						var listing = listings[n];

						if (typeof listing.waterview != 'undefined' && listing.waterview.length>5){
							listing.waterview=listing.waterview.replace(",", ", ");
						}
						$("#listings").html($("#listings").html() + '<div class="listing" onclick="zoom_to(' + n + ')" style="margin-bottom:15px;"><table><tbody><tr><td><a href="<?php bloginfo('url') ?>' + listing.url + '"><img src="' + "http://img.seorets.com/<?php echo $seo_rets_plugin->feed->server_name?>/" + listing.seo_url + "-" + listing.mls_id + "-1.jpg" + '"><div class="sr-listing-price">$' + addCommas(listing.price) +'</div></td><td><p class="listing-address" style="font-size:18px;"><a href="<?php bloginfo('url') ?>' + listing.url + '">' + listing.address + '</a></p><p class="listing-city" style="font-size:18px;">'+ '' + listing.city + ', ' + listing.state + '</p><table style="font-size:16px;line-height:20px;"><tbody>' + '<tr><td>Beds:</td><td>' + listing.bedrooms + '</td></tr><tr><td>Baths:</td><td>' + listing.baths + '</td></tr>' + ((typeof listing.waterview != 'undefined') ? '<tr><td>Waterview:</td><td>' + listing.waterview + '</td></tr>' : '') + '</tbody></table></td></tr></tbody></table></div>');


						var position = new google.maps.LatLng(listing.lat, listing.lng);

						infos[n] = new google.maps.InfoWindow({
							content: '<table><tr><td><a target="_parent" href="<?php bloginfo('url') ?>' + listing.url + '"><img style="width:130px;height:86px;" src="http://img.seorets.com/<?php echo $seo_rets_plugin->feed->server_name?>/' + listing.seo_url + '-' + listing.mls_id + '-1.jpg" /' + '></a></td><td valign="top" style="padding-left:5px;"><strong><a target="_parent" href="<?php bloginfo('url') ?>' + listing.url + '">' + listing.address + '</a></strong><br /' + '>Price: $' + addCommas(listing.price) + '<br /' + '>Bedrooms: ' + listing.bedrooms + '<br /' + '>Baths: ' + listing.baths_full + '</td></tr></table>'
						});

						markers[n] = new google.maps.Marker({
							position: position,
							map: map,
							title: listing.address,
							icon: "<?php echo $seo_rets_plugin->plugin_dir; ?>resources/images/marker.png"
						});

						var clicked_index = n;

						google.maps.event.addListener(markers[n], 'click', (function (x) {
							return function () {
								updating = true;
								$(".listing").css("background-color", "#FFF");
								close_infos();
								infos[x].open(map, markers[x]);
								var listings_el = $("#listings");
								var listing_el = $(".listing:eq(" + x + ")");
								listings_el.animate({
									scrollTop: (listings_el.scrollTop() + listing_el.position().top) - ((listings_el.height() / 2) - (listing_el.height() / 2))
								}, 1000, function () {
									listing_el.css("background-color", "#EEE");
									setTimeout(function () {
										updating = false;
									}, 1000);
								});
							};
						})(n));


						bounds.extend(position);
					}

					if (!inbounds) map.fitBounds(bounds);
					inbounds = false;
					$("#ajax-loader, #ajax-loader2").toggle();
					setTimeout(function () {
						updating = false;
					}, 1000);

				};

				var needs_geocoding = [];


				for (var n = 0; n < listings.length; n++) {
					if (((typeof listings[n].lat) == "undefined") || isNaN(listings[n].lat) || isNaN(listings[n].lng) || listings[n].lat == 0 || listings[n].lng == 0) {
						needs_geocoding.push({
							index: n,
							address: listings[n].address + " " + listings[n].city + ", " + listings[n].state
						});
					}
				}

				if (needs_geocoding.length > 0) {
					$.ajax({
						url: '<?php bloginfo('url') ?>/sr-ajax?action=geocode',
						type: 'post',
						data: {
							geocode: JSON.stringify(needs_geocoding)
						},
						success: function (response) {

							if (response !== null) {
								for (var n = 0; n < response.geocode.length; n++) {
									listings[response.geocode[n].index].lat = response.geocode[n].latitude;
									listings[response.geocode[n].index].lng = response.geocode[n].longitude;
								}
							} else {

								for (var n = 0; n < needs_geocoding.length; n++) {
									delete listings[needs_geocoding[n].index];
								}

								listings = Object.keys(listings).map(function (v) {
									return listings[v];
								});
							}

							add_listings_to_map();
						}
					});
				} else {
					add_listings_to_map();
				}
			};

			updating = true;
			$("#ajax-loader, #ajax-loader2").toggle();
			$.ajax({
				url: "<?php bloginfo('url') ?>/sr-ajax?action=map-search",
				type: "post",
				data: get_form_data(),
				success: function (data) {
					map_listings(data.result);
				}
			});
		};


		$("#search-area select").change(update_map);


		google.maps.event.addListener(map, 'idle', function () {
			if (!updating) {
				//alert(map.getBounds());
				inbounds = true;
				update_map();
			}
		});


		update_map();


	});


</script>
<?php
wp_enqueue_style('sr_templates_splitsearch',$this->css_resources_dir.'templates/splitsearch.css');
wp_print_styles(array('sr_templates_splitsearch'));
?>



<!--<div class="ribbon">-->
<!--	<h1 class="entry-title">Map Search</h1>-->
<!--</div>-->

<div id="search-area">
	<table id="search-form">
		<tbody>
		<tr>
			<td>
				<table>
					<tbody>
					<tr>
						<td>
							Type:
						</td>
						<td>
							<select id="property-type" name="type" class="form-el">
								<option value="res">Homes</option>
								<option value="cms">Commercial Sale</option>
								<option value="cnd">Condos</option>
								<option value="rens">Rentals</option>
								<option value="cml">Commercial Lease</option>
								<option value="lnds">Land</option>
								<option value="frm">Farm Land</option>
								<option value="cre">Combined Residential</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							City:
						</td>
						<td>
							<select id="cities" name="city" class="form-el">
								<option value="" selected="selected">Any</option>
								<option>Crestview</option>
								<option>Defuniak Springs</option>
								<option>Destin</option>
								<option>Fort Walton Beach</option>
								<option>Inlet Beach</option>
								<option>Mary Esther</option>
								<option>Miramar Beach</option>
								<option>Navarre</option>
								<option>Niceville</option>
								<option>Panama City Beach</option>
								<option>Pensacola</option>
								<option>Sandestin</option>
								<option>Santa Rosa Beach</option>
								<option>Seacrest</option>
								<option>Shalimar</option>
								<option>Valparaiso</option>
								<option>West Panama City Beach</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Price:
						</td>
						<td>
							<select name="price-low" class="price_select">
								<option value="">Any</option>
								<option value="100000">$100,000</option>
								<option value="125000">$125,000</option>
								<option value="150000">$150,000</option>
								<option value="175000">$175,000</option>
								<option value="200000">$200,000</option>
								<option value="225000">$225,000</option>
								<option value="250000">$250,000</option>
								<option value="275000">$275,000</option>
								<option value="300000">$300,000</option>
								<option value="325000">$325,000</option>
								<option value="350000">$350,000</option>
								<option value="375000">$375,000</option>
								<option value="400000">$400,000</option>
								<option value="425000">$425,000</option>
								<option value="450000">$450,000</option>
								<option value="475000">$475,000</option>
								<option value="500000">$500,000</option>
								<option value="600000">$600,000</option>
								<option value="700000">$700,000</option>
								<option value="800000">$800,000</option>
								<option value="900000">$900,000</option>
								<option value="1000000">$1,000,000</option>
								<option value="1500000">$1,500,000</option>
								<option value="2000000">$2,000,000</option>
								<option value="2500000">$2,500,000</option>
								<option value="3000000">$3,000,000</option>
								<option value="3500000">$3,500,000</option>
								<option value="4000000">$4,000,000</option>
								<option value="4500000">$4,500,000</option>
								<option value="5000000">$5,000,000</option>
							</select>
							to
							<select name="price-high" class="price_select">
								<option value="">Any</option>
								<option value="100000">$100,000</option>
								<option value="125000">$125,000</option>
								<option value="150000">$150,000</option>
								<option value="175000">$175,000</option>
								<option value="200000">$200,000</option>
								<option value="225000">$225,000</option>
								<option value="250000">$250,000</option>
								<option value="275000">$275,000</option>
								<option value="300000">$300,000</option>
								<option value="325000">$325,000</option>
								<option value="350000">$350,000</option>
								<option value="375000">$375,000</option>
								<option value="400000">$400,000</option>
								<option value="425000">$425,000</option>
								<option value="450000">$450,000</option>
								<option value="475000">$475,000</option>
								<option value="500000">$500,000</option>
								<option value="600000">$600,000</option>
								<option value="700000">$700,000</option>
								<option value="800000">$800,000</option>
								<option value="900000">$900,000</option>
								<option value="1000000">$1,000,000</option>
								<option value="1500000">$1,500,000</option>
								<option value="2000000">$2,000,000</option>
								<option value="2500000">$2,500,000</option>
								<option value="3000000">$3,000,000</option>
								<option value="3500000">$3,500,000</option>
								<option value="4000000">$4,000,000</option>
								<option value="4500000">$4,500,000</option>
								<option value="5000000">$5,000,000</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							Order:
						</td>
						<td>
							<select name="order" style="width:100%;">
								<option value="price:DESC">Price High to Low</option>
								<option value="price:ASC">Price Low to High</option>
							</select>
						</td>
					</tr>
					</tbody>
				</table>
			</td>
			<td>
				<table>
					<tbody>
					<tr>
						<td>
							Bedrooms:
						</td>
						<td>
							<select name="bedrooms-low" class="bed_select">
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
							<select name="bedrooms-high" class="bed_select">
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
							<select name="baths-low" class="bath_select">
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
							<select name="baths-high" class="bath_select">
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
						<td>Waterview:</td>
						<td>
							<select name="waterview" style="width:100%;height:52px;" id="water_select" multiple>
								<option value="">Any</option>
								<option>Gulf</option>
								<option>Bay</option>
								<option>Sound</option>
								<option>Bayou</option>
								<option>Lake</option>
								<option>Harbor</option>
								<option>Gulf/Pass</option>
							</select>
						</td>
					</tr>
					<tr>

					</tr>
					</tbody>
				</table>
			</td>
			<td>
				<table>
					<tbody>
					<tr>
						<td>Waterfront:</td>
						<td>
							<select name="waterfront" style="width:100%;height:52px;" multiple>
								<option value="">Any</option>
								<option>Bay</option>
								<option>Bayou</option>
								<option>Canal</option>
								<option>Creek</option>
								<option>Gulf</option>
								<option>Gulf/Pass</option>
								<option>Harbor</option>
								<option>Intracoastal Waterway</option>
								<option>Lagoon</option>
								<option>Lake</option>
								<option>Pond</option>
								<option>River</option>
								<option>Shore: Beach</option>
								<option>Shore: Natural</option>
								<option>Shore: Rip Rap</option>
								<option>Shore: Seawall</option>
								<option>Sound</option>
								<option>Stream</option>
								<option>Unit Waterfront</option>
							</select>
						</td>
					</tr>
					<tr>

					</tr>
					</tbody>
				</table>
			</td>
		</tr>
		</tbody>
	</table>

	<div id="listings-container" class="col-2 first">
		<div id="listings"></div>
		<img id="ajax-loader2" src="<?php echo $this->plugin_dir ?>resources/images/ajax2.gif" style="display:none;"/>
	</div>

	<div class="col-2">
		<div id="map-container">
			<div id="map-canvas"></div>
			<div id="ajax-loader" style="display:none;">Retrieving Most Recent MLS Data<br/><br/><img
					src="<?php echo $this->plugin_dir ?>resources/images/ajax.gif"/></div>
		</div>
	</div>
	<div style="clear:both;"></div>
	<p>Powered By <a href="http://seorets.com/" target="_blank">SEO RETS</a></p>

</div>