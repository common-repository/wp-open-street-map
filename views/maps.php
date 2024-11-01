<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>



<h2><?php esc_html_e('All WP Open Street Maps', 'wp-open-street-map') ?></h2>







<a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=wp_openstreetmaps&task=new'), 'edit_wposm')) ?>" class="button button-primary"><?php esc_html_e('Add a new map', 'wp-open-street-map') ?></a>

<?php



	if(sizeof($maps) > 0)

	{

		foreach($maps as $map)

		{

			echo '<div class="wp_osm"><h3>'.esc_html($map->name).'</h3>

			<a href="'.esc_url(wp_nonce_url(admin_url('admin.php?page=wp_openstreetmaps&task=manage&id='.$map->id), 'edit_wposm')).'" title="Manage markers"><img src="'.esc_url(plugins_url( 'images/manage.png', dirname(__FILE__))).'" /></a>

			<a href="'.esc_url(wp_nonce_url(admin_url('admin.php?page=wp_openstreetmaps&task=remove&id='.$map->id), 'remove_wposm' )).'" title="Remove map" class="remove_wp_osm"><img src="'.esc_url(plugins_url( 'images/remove.png', dirname(__FILE__))).'" /></a>

			<br />

			<b>Shortcode : </b>

			<input type="text" readonly value="[wp-osm id='.(int)$map->id.']" onClick="this.select()" />

			<br />

			</div>';

		}

	}

	else

		echo '<p>No WP Open street maps created yet!</p>';



?>



<h3>If you enjoy this plugin, <a href="https://wordpress.org/support/plugin/wp-open-street-map/reviews/#new-post" target="_blank">leave a review!</a></h3>

<h3><?php esc_html_e('Need more options? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a> <a href="https://www.facebook.com/infod74/" target="_blank"><img src="<?php echo esc_url(plugins_url( 'images/fb.png', dirname(__FILE__))) ?>" alt="" /></a></h3>

<script>



	jQuery('.remove_wp_osm').click(function(){

		return confirm('Are you sure to delete this map and all his markers?');

	})



</script>