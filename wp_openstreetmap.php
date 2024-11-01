<?php

/*

Plugin Name: WP Open Street Map

Plugin URI: 

Version: 1.31

Description: Create map with marker on Open Street Map 

Author: InfoD74

Author URI: https://www.info-d-74.com/en/shop/

Network: false

Text Domain: wp-open-street-map

Domain Path: /languages

*/


register_activation_hook( __FILE__, 'wp_openstreetmap_install' );

register_uninstall_hook(__FILE__, 'wp_openstreetmap_desinstall');


function wp_openstreetmap_install() {

	global $wpdb;

	$maps_table = $wpdb->prefix . "wp_openstreetmap";

	$maps_markers_table = $wpdb->prefix . "wp_openstreetmap_markers";


	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$sql = "

        CREATE TABLE `".$maps_table."` (

          id int(11) NOT NULL AUTO_INCREMENT,          

          name varchar(50) NOT NULL,

          width varchar(10) NOT NULL,

          height varchar(10) NOT NULL,

          zoom int(3) NOT NULL,

          latitude float(11) NOT NULL,

          longitude float(11) NOT NULL,

          PRIMARY KEY  (id)

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

    ";



    dbDelta($sql);



    $sql = "

        CREATE TABLE `".$maps_markers_table."` (

          id int(11) NOT NULL AUTO_INCREMENT,          

          name varchar(50) NOT NULL,

          description varchar(255) NOT NULL,

          icon varchar(500) NOT NULL,

          latitude float(10) NOT NULL,

          longitude float(10) NOT NULL,

          id_map int(11),

          PRIMARY KEY (id)

        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

    ";   



    dbDelta($sql);



}



function wp_openstreetmap_desinstall() {



	global $wpdb;

	$maps_table = $wpdb->prefix . "wp_openstreetmap";

	$maps_markers_table = $wpdb->prefix . "wp_openstreetmap_markers";



	//suppression des tables

	$sql = "DROP TABLE ".$maps_table.";";

	$wpdb->query($sql);



    $sql = "DROP TABLE ".$maps_markers_table.";";   

	$wpdb->query($sql);



}



add_action( 'admin_menu', 'register_wp_openstreetmap_menu' );



function register_wp_openstreetmap_menu() {

	add_menu_page('WP OpenStreetMap', 'WP OpenStreetMap', 'edit_pages', 'wp_openstreetmaps', 'wp_openstreetmaps', plugins_url( 'images/icon.png', __FILE__ ), 38);

}



add_action('admin_print_styles', 'wp_openstreetmap_css' );



function wp_openstreetmap_css() {

    wp_enqueue_style( 'WPOpenStreetMapStylesheet', plugins_url('css/admin.css', __FILE__) );

}



add_action( 'admin_enqueue_scripts', 'load_script_wp_openstreetmap' );

function load_script_wp_openstreetmap() {

	wp_enqueue_media();

}



function wp_openstreetmaps() {



	global $wpdb;



	$maps_table = $wpdb->prefix . "wp_openstreetmap";

	$maps_markers_table = $wpdb->prefix . "wp_openstreetmap_markers";



	if(current_user_can('edit_pages'))

	{

		if(isset($_GET['task']))

		{

			switch($_GET['task'])

			{



				case 'new':

				case 'edit':



					if(wp_verify_nonce($_REQUEST['_wpnonce'], 'edit_wposm'))

					{

						if(sizeof($_POST))

						{



							$query = "REPLACE INTO ".$maps_table." (`id`, `name`, `width`, `height`, `zoom`, `latitude`, `longitude`)

							VALUES (%d, %s, %s, %s, %d, %f, %f)";



							$query = $wpdb->prepare( $query, $_POST['id'], stripslashes_deep(sanitize_text_field($_POST['name'])), sanitize_text_field($_POST['width']), sanitize_text_field($_POST['height']), intval($_POST['zoom']), floatval($_POST['latitude']), floatval($_POST['longitude']) );



							$wpdb->query( $query );



							if(is_numeric($_POST['id']))

								$id = $_POST['id'];

							else

								$id = $wpdb->insert_id;



							//on affiche tous les graphs

							$maps = $wpdb->get_results("SELECT * FROM ".$maps_table." ORDER BY name");

							include(plugin_dir_path( __FILE__ ) . 'views/maps.php');



						}

						else

						{

							//édition d'une map existante ?

							if(is_numeric($_GET['id']))

							{

								$q = "SELECT * FROM ".$maps_table." WHERE id = %d";

								$query = $wpdb->prepare( $q, $_GET['id']);

								$map = $wpdb->get_row( $query );

							}



							if(empty($map))

								$map = (object)'';



							include(plugin_dir_path( __FILE__ ) . 'views/edit.php');



						}

					}

					else

						echo '<p class="notice notice-error">Error nonce incorrect! <a href="'.esc_url(admin_url('admin.php?page=wp_openstreetmaps')).'">Go back to maps list</a></p>';



				break;



				case 'manage':



					if(is_numeric($_GET['id']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'edit_wposm'))

					{

						$q = "SELECT * FROM ".$maps_table." WHERE id = %d";

						$query = $wpdb->prepare( $q, $_GET['id']);

						$map = $wpdb->get_row( $query );



						if($map)

						{

							//update markers ?

							if(sizeof($_POST) > 0)

							{

								$q = "DELETE FROM ".$maps_markers_table." WHERE id_map = %d";

								$query = $wpdb->prepare( $q, $_GET['id']);

								$wpdb->query( $query );



								foreach($_POST['icon_url'] as $i => $icon_url)

								{

									$q = "REPLACE INTO ".$maps_markers_table." VALUES ('', %s, %s, %s, %f, %f, %d)";

									$coords = explode(',', $_POST['icon_coords'][$i]);

									$query = $wpdb->prepare( $q, stripslashes_deep(sanitize_text_field($_POST['icon_name'][$i])), stripslashes_deep(sanitize_textarea_field($_POST['icon_description'][$i])), sanitize_text_field($_POST['icon_url'][$i]), floatval($coords[1]), floatval($coords[0]), intval($_GET['id']));

									$wpdb->query( $query );

								}



							}



							$q = "SELECT * FROM ".$maps_markers_table." WHERE id_map = %d";

							$query = $wpdb->prepare( $q, intval($_GET['id']));

							$markers = $wpdb->get_results( $query );



							if(is_numeric($_GET['id_marker']))

							{

								foreach ($icons as $icon) {

									if($icon->id == $_GET['id_marker'])

										break;

								}

							}



							include(plugin_dir_path( __FILE__ ) . 'views/manage.php');



						}

					}

					else

						echo '<p class="notice notice-error">Error nonce incorrect! <a href="'.esc_url(admin_url('admin.php?page=wp_openstreetmaps')).'">Go back to maps list</a></p>';



				break;



				case 'remove':



					if(is_numeric($_GET['id']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'remove_wposm'))

					{

						//on supprime les markers et la carte

						$q = "DELETE FROM ".$maps_markers_table." WHERE id_map = %d";

						$query = $wpdb->prepare( $q, intval($_GET['id']));

						$wpdb->query( $query );



						$q = "DELETE FROM ".$maps_table." WHERE id = %d";

						$query = $wpdb->prepare( $q, intval($_GET['id']));

						$wpdb->query( $query );

					}



					//on affiche tous les graphs

					$maps = $wpdb->get_results("SELECT * FROM ".$maps_table." ORDER BY name");

					include(plugin_dir_path( __FILE__ ) . 'views/maps.php');



				break;



			}



		}

		else

		{



			if(!is_numeric($_GET['id']))

			{

				//on affiche toutes les cartes

				$maps = $wpdb->get_results("SELECT * FROM ".$maps_table." ORDER BY name");

				include(plugin_dir_path( __FILE__ ) . 'views/maps.php');



			}



		}



	}



}





add_shortcode('wp-osm', 'display_wp_openstreetmap');



function display_wp_openstreetmap($atts) {



	if(is_numeric($atts['id']))

	{

		global $wpdb;



		$maps_table = $wpdb->prefix . "wp_openstreetmap";

		$maps_markers_table = $wpdb->prefix . "wp_openstreetmap_markers";

		$q = "SELECT * FROM ".$maps_table." WHERE id = %d";

		$id_map = intval($atts['id']);

		$query = $wpdb->prepare( $q, $id_map);

		$map = $wpdb->get_row( $query );



		if($map)

		{

			$q = "SELECT * FROM ".$maps_table." WHERE id = %d";

			$query = $wpdb->prepare( $q, $id_map );

			$map = $wpdb->get_row( $query );

			$q = "SELECT * FROM ".$maps_markers_table." WHERE id_map = %d";

			$query = $wpdb->prepare( $q, $id_map );

			$map->markers = $wpdb->get_results( $query );



			//on inclut jquery

			wp_enqueue_script( 'jquery' );

			wp_enqueue_script( 'wp-openstreetmap-api',  plugins_url('js/OpenLayers/v6.5.0/build/ol.js', __FILE__)) ;

			wp_enqueue_script( 'wp-openstreetmap-js', plugins_url( 'js/front.js', __FILE__ ));

			wp_enqueue_style( 'wp-openstreetmapFrontStylesheet', plugins_url('css/front.css', __FILE__) );

			wp_enqueue_style( 'wp-openstreetmapPro-OL-CSS', plugins_url('js/OpenLayers/v6.5.0/css/ol.css', __FILE__));



			$html = '';



			ob_start();

			include(plugin_dir_path( __FILE__ ) . 'views/map.tpl.php');

			$html .= ob_get_clean();



			return $html;

		}	

		else

			return 'Map ID '.$id_map.' not found !';	



	}



}



function wp_openstreetmap_textdomain() {

    load_plugin_textdomain( 'wp-open-street-map', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

}

add_action( 'plugins_loaded', 'wp_openstreetmap_textdomain' );



?>