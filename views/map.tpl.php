<div id="wp_osm_<?php (int)$map->id ?>" class="wp_osm" style="width: <?php echo esc_attr($map->width) ?>; height: <?php echo esc_attr($map->height) ?>" data-lon="<?php echo (int)$map->longitude ?>" data-lat="<?php echo (int)$map->latitude ?>" data-zoom="<?php echo (int)$map->zoom ?>">

	<div class="ol-popup">

	    <a href="#" class="ol-popup-closer"></a>

	    <div class="popup-content"></div>

	</div>

	<?php



		foreach($map->markers as $marker)

		{

			echo '<div class="marker" data-icon="'.esc_url($marker->icon).'" data-lon="'.(int)$marker->longitude.'" data-lat="'.(int)$marker->latitude.'" data-name="'.esc_attr($marker->name).'" data-description="'.wp_kses_post(nl2br($marker->description)).'"></div>';

		}

	?>

</div>