<?php
$sr = $seo_rets_plugin;

if ( !$sr->api_key ) return '<p class="sr-error">You must activate the SEO RETS plugin before using shortcodes.</p>';
?><script type="text/javascript">

function validateForm(form) {
	if (!validateEmail(form["email"].value)) {
		alert("Invalid email address");
		return false;
	}
	return true;
}

function validateEmail(email) { 
    var re = /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Za-z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b$/;
    return re.test(email);
}
</script>
<form action="<?php echo get_bloginfo('url')?>/sr-subscribe" method="get" onsubmit="return validateForm(this)">
	Email: <input type="text" name="email" /><br />
	Name: <input type="text" name="sr-name" /><br /><br />
	<table border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td class="form-title" valign="top" width="110px" style=
				"border-right: 1px solid #BFBDAB;">Location &amp; Home Type</td>

				<td class="as-right" width="420px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td width="19%" valign="top" style="padding-left: 10px;">
									<p><strong>City:</strong><br />
									Use control + click to select multiple</p>
								</td>

								<td width="81%" style="padding-left: 10px;"><select name="conditions[3][value][]" multiple=
								"multiple" style="width:275px" size="8">
									<option value="">All</option>
									<?php $cities = $sr->metadata->res->fields->city->values; sort($cities); foreach ( $cities as $city ): ?><option><?php echo $city?></option><?php endforeach; ?>
								</select></td>
							</tr>

							<!--<tr>
								<td style="padding-left: 10px;"><strong>Address:</strong></td>

								<td style="padding-left: 10px;"><input style="width:275px" type="text" name=
								"conditions[0][value]" /></td>
							</tr>

							<tr>
								<td style="padding-left: 10px;"><strong>Subdivision:</strong></td>

								<td style="padding-left: 10px;"><input style="width:275px" type="text" name=
								"conditions[4][value]2" /></td>
							</tr>

							<tr>
								<td style="padding-left: 10px;"><strong>MLS#:</strong></td>

								<td style="padding-left: 10px;"><input style="width:275px" type="text" name=
								"conditions[4][value]" /></td>
							</tr>-->
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>

	<table border="0" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td class="form-title" valign="top" width="110px" style=
				"border-right: 1px solid #BFBDAB;">Price &amp; Size</td>

				<td class="as-right" width="420px">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tbody>
							<tr>
								<td width="16%" style="padding-left: 10px;"><strong>Price:</strong></td>

								<td width="3%">&nbsp;</td>

								<td width="6%">Min:</td>

								<td width="33%"><input type="text" name="conditions[1][value]" style=
								"width:75px;" /></td>

								<td width="7%">Max:</td>

								<td width="35%"><input type="text" name="conditions[2][value]" style=
								"width:75px;" /></td>
							</tr>

							<tr>
								<td style="padding-left: 10px;"><strong>Bedrooms:</strong></td>

								<td>&nbsp;</td>

								<td>Min:</td>

								<td><input type="text" name="conditions[5][value]" style=
								"width:75px;" /></td>

								<td>Max:</td>

								<td><input type="text" name="conditions[6][value]" style=
								"width:75px;" /></td>
							</tr>

							<tr>
								<td style="padding-left: 10px;"><strong>Bathrooms:</strong></td>

								<td>&nbsp;</td>

								<td>Min:</td>

								<td><input type="text" name="conditions[7][value]" style=
								"width:75px;" /></td>

								<td>Max:</td>

								<td><input type="text" name="conditions[8][value]" style=
								"width:75px;" /></td>
							</tr>

							<tr>
								<td style="padding-left: 10px;"><strong>Sqft:</strong></td>

								<td>&nbsp;</td>

								<td>Min:</td>

								<td><input type="text" name="conditions[31][value]" style=
								"width:75px;" /></td>

								<td>Max:</td>

								<td><input type="text" name="conditions[32][value]" style=
								"width:75px;" /></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	
	<input type="hidden" name="type" value="Homes" />
	<input type="hidden" name="perpage" value="20" />
	<input type="hidden" name="conditions[3][operator]" value="LIKE" />
	<input type="hidden" name="conditions[3][loose]" value="1" /> 
	<input type="hidden" name="conditions[3][field]" value="city" />
	<!--<input type="hidden" name="conditions[0][operator]" value="LIKE" />
	<input type="hidden" name="conditions[0][loose]" value="1" />
	<input type="hidden" name="conditions[0][field]" value="address" />-->
	<input type="hidden" name="conditions[1][operator]" value="&gt;=" />
	<input type="hidden" name="conditions[1][field]" value="price" />
	<input type="hidden" name="conditions[2][operator]" value="&lt;=" />
	<input type="hidden" name="conditions[2][field]" value="price" />
	<!--<input type="hidden" name="conditions[4][operator]" value="LIKE" />
	<input type="hidden" name="conditions[4][loose]" value="1" />
	<input type="hidden" name="conditions[4][field]" value="subdivision" />
	<input type="hidden" name="conditions[4][operator]" value="=" />
	<input type="hidden" name="conditions[4][field]" value="mls_id" />-->
	<input type="hidden" name="conditions[5][operator]" value="&gt;=" />
	<input type="hidden" name="conditions[5][field]" value="bedrooms" />
	<input type="hidden" name="conditions[6][operator]" value="&lt;=" />
	<input type="hidden" name="conditions[6][field]" value="bedrooms" />
	<input type="hidden" name="conditions[7][operator]" value="&gt;=" />
	<input type="hidden" name="conditions[7][field]" value="baths_full" />
	<input type="hidden" name="conditions[8][operator]" value="&lt;=" />
	<input type="hidden" name="conditions[8][field]" value="baths_full" />
	<input type="hidden" name="conditions[31][operator]" value="&gt;=" />
	<input type="hidden" name="conditions[31][field]" value="sqft" />
	<input type="hidden" name="conditions[32][operator]" value="&lt;=" />
	<input type="hidden" name="conditions[32][field]" value="sqft" />
        <!-- Commented by David Pope - Broken needs rewrite
	<!--<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6Lfki9ESAAAAAPsFuGq1nSfRWQgO8nZItbl5Q6ML"></script>
        <noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=6Lfki9ESAAAAANZ3ZaQPg6l7W6v2hV3TrayhR9_j" height="300" width="500" frameborder="0"></iframe><br/>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
	</noscript>-->
	<br />
	<input type="submit" value="Subscribe" />
</form>