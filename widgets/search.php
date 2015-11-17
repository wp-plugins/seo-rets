<?php


class SEO_Rets_Search_Widget extends WP_Widget {

	function SEO_Rets_Search_Widget() {
		global $seo_rets_plugin;
		
		$this->sr = $seo_rets_plugin;
		
		$widget_ops = array('classname' => 'sr-search-widget', 'description' => 'Displays a SEO RETS search form in your sidebar.');
		$control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'seo-rets-search-widget');
		$this->WP_Widget('seo-rets-search-widget', 'SEO Rets Search Form', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance) {
		echo $args['before_widget'];
		if ( isset($instance['city_list']) && is_array($instance['city_list']) && count($instance['city_list']) > 0 ) {
			$cities = $instance['city_list'];
		} else {
			$cities = $this->sr->metadata->res->fields->city->values;
		}
		sort($cities);
		
		
   ?>
		<h3 class="widget-title"><?php echo isset($instance['title'])?$instance['title']:""?></h3>
		  <?php  wp_enqueue_script('sr_seorets-min');
    		     wp_print_scripts(array('sr_seorets-min'));
    ?>
		<script type="text/javascript">
		(function(){
			var m = (function(){var s=document.getElementsByTagName('script');return s[s.length-1];})();
			jQuery(function(){
			    seorets.options = {blogurl: "<?php echo get_bloginfo('url')?>"};
				seorets.startForm(jQuery(m).nextUntil('.sr-formsection + *','.sr-formsection'));
			});
		})();</script>
		<div class="sr-formsection sr-content" sroperator="AND" srtype="<?php echo $instance['type']?>">
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">City:</label>
</div>
<div class="col-md-8 col-sm-8">
<select  class="sr-formelement form-control" srfield="city" sroperator="=">
							<option value=''>All</option>
							<?php
								foreach ($cities as $city) {
									echo "\t\t\t\t\t\t\t<option>{$city}</option>\n";
								}
							?>
						</select>
</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">Min Beds:</label>
</div>
<div class="col-md-8 col-sm-8">
					<input type="text" class="sr-formelement form-control" srtype="numeric" srfield="bedrooms" sroperator=">=" value="<?php echo $instance['defaults_minbeds']?>" />

</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">Min Baths:</label>
</div>
<div class="col-md-8 col-sm-8">
					<input type="text" class="sr-formelement form-control" srtype="numeric" srfield="baths" sroperator=">=" value="<?php echo $instance['defaults_minbaths']?>" />

</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">Min Price:</label>
</div>
<div class="col-md8 col-sm-8">
<input type="text" class="sr-formelement form-control" srtype="numeric" srfield="price" sroperator=">=" value="<?php echo $instance['defaults_minprice']?>" />

</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">Max Price:</label>
</div>
<div class="col-md-8 col-sm-8">
<input type="text" class="sr-formelement form-control" srtype="numeric" srfield="price" sroperator="<=" value="<?php echo $instance['defaults_maxprice']?>" />

</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for="">MLS #:</label>
</div>
<div class="col-md-8 col-sm-8">
					<input type="text" class="sr-formelement form-control" srfield="mls_id" sroperator="=" value="<?php echo $instance['defaults_mls']?>" onchange="this.value=jQuery.trim(this.value);" />

</div>
</div>
		<div class="row margin-top-10">
		<div class="col-md-4 col-sm-4">
		<label for=""></label>
</div>
<div class="col-md-8 col-sm-8">
					<input type="submit" class="sr-submit" value="Search" />

</div>
</div>

			<?php if (isset($instance['sorting']) && !empty($instance['sorting'])):?>
			<input type="hidden" class="sr-order" srfield="price" srdirection="<?php echo $instance['sorting']?>" />
			<?php endif;?>
			<input type="hidden" class="sr-limit" value="10">
		</div>
		<?php		
		 echo $args['after_widget'];
	}
	
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
	
	function form($instance) {
		$cities = $this->sr->metadata->res->fields->city->values;
	?>
		<h3 style="margin-top:0;">Settings</h3>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" 
			value="<?php echo  isset($instance['title']) ? $instance['title'] : "" ?>" style="width:95%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>">Listing Type:</label>
			
			<select id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>"><?php
						
			foreach ($this->sr->metadata as $type) {
				$type = $type->system_name;
				if ($this->sr->is_type_hidden($type)){continue;}
				if ( $type == $instance['type'] ) {
					echo "<option value='$type' selected>$type</option>";
				} else {
					echo "<option value='$type'>$type</option>";
				}
			}
			
			?></select>
			
		</p>
		
		<h3>Cities</h3>
		<p>Select which cities you would like to display in your quicksearch dropdown. Use control + click to select multiple cities.</p>
		<p>
			<select style="width: 100%;height:150px;" multiple="multiple" name="<?php echo $this->get_field_name('city_list'); ?>[]">
				<?php sort($cities); ?>
				<?php foreach ( $cities as $city ): ?>
					<?php if ( $instance['city_list'] && in_array($city, $instance['city_list']) ): ?>
						<option selected><?php echo $city?></option>
					<?php else: ?>
						<option><?php echo $city?></option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</p>
		
		<h3>Defaults</h3>
		<p> 
			<table>
				
				<tr>
					<td>Min Beds: </td>
					<td><input type="text" name="<?php echo $this->get_field_name('defaults_minbeds'); ?>" value="<?php echo isset($instance['defaults_minbeds']) ? $instance['defaults_minbeds'] : "" ?>" /></td>
				</tr>
				<tr>
					<td>Min Baths: </td>
					<td><input type="text" name="<?php echo $this->get_field_name('defaults_minbaths'); ?>" value="<?php echo isset($instance['defaults_minbaths']) ? $instance['defaults_minbaths'] : "" ?>" /></td>
				</tr>
				<tr>
					<td>Min Price: </td>
					<td><input type="text" name="<?php echo $this->get_field_name('defaults_minprice'); ?>" value="<?php echo isset($instance['defaults_minprice']) ? $instance['defaults_minprice'] : "" ?>" /></td>
				</tr>
				<tr>
					<td>Max Price: </td>
					<td><input type="text" name="<?php echo $this->get_field_name('defaults_maxprice'); ?>" value="<?php echo isset($instance['defaults_maxprice']) ? $instance['defaults_maxprice'] : "" ?>" /></td>
				</tr>
				<tr>
					<td>MLS #: </td>
					<td><input type="text" name="<?php echo $this->get_field_name('defaults_mls'); ?>" value="<?php echo isset($instance['defaults_mls']) ? $instance['defaults_mls'] : "" ?>" /></td>
				</tr>
			</table>
		</p>
		
		<h3>Result Sorting</h3>
		<p>
			<select name="<?php echo $this->get_field_name('sorting'); ?>">
				<option <?php echo ($instance['sorting']=="")?"selected=\"selected\" ":""?>value="">None</option>
				<option <?php echo ($instance['sorting']=="DESC")?"selected=\"selected\" ":""?>value="DESC">Price highest to lowest</option>
				<option <?php echo ($instance['sorting']=="ASC")?"selected=\"selected\" ":""?>value="ASC">Price lowest to highest</option>
			</select>
		</p>
	<?php
	}
}
