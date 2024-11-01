<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>



<h2><?php esc_html_e('Manage markers for map', 'wp-open-street-map') ?> "<?php echo esc_html($map->name) ?>"</h2>



<form action="<?php echo esc_url(admin_url('admin.php?page=wp_openstreetmaps&task=edit')); ?>" method="post" class="form_wp_osm">



	<?php wp_nonce_field('edit_wposm'); ?>



	<input type="hidden" name="id" value="<?php echo (int)$map->id ?>" />



	<label for=""><?php esc_html_e('Name:', 'wp-open-street-map') ?></label> <input type="text" name="name" value="<?php echo $map->name ? esc_attr($map->name) : 'WP OSM' ?>" /><br />



	<label for=""><?php esc_html_e('Width:', 'wp-open-street-map') ?></label> <input type="text" name="width" id="wp_osm_width" value="<?php echo $map->width ? esc_attr($map->width) : '100%' ?>" /><br />



	<label for=""><?php esc_html_e('Height:', 'wp-open-street-map') ?></label> <input type="text" name="height" id="wp_osm_height" value="<?php echo $map->height ? esc_attr($map->height) : '500px' ?>" /><br />



	<input type="text" name="zoom" id="wp_osm_zoom" value="<?php echo (int)$map->zoom ?>" style="display: none" />



	<input type="text" name="latitude" id="wp_osm_latitude" value="<?php echo (int)$map->latitude ?>" style="display: none" />



	<input type="text" name="longitude" id="wp_osm_longitude" value="<?php echo (int)$map->longitude ?>" style="display: none" />



	<p><strong class="notice notice-warning"><?php esc_html_e('Need to center your map automatically? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a></strong></p>



	<input type="submit" value="Save map settings" class="button button-primary" /> <a href="<?php echo esc_url(admin_url('admin.php?page=wp_openstreetmaps')); ?>" class="button button-secondary"><?php esc_html_e('Back to maps list', 'wp-open-street-map') ?></a>

</form>

<form action="" method="post" class="form_wp_osm">

	<strong><?php esc_html_e('Add a new marker', 'wp-open-street-map') ?></strong>

	<?php $icons = array('marker', 'airport', 'bus', 'market', 'restaurant', 'parking', 'hotel', 'gazstation', 'highway', 'warning'); ?>



	<div class="name_line">

		<label for=""><?php esc_html_e('Icon:', 'wp-open-street-map') ?></label>

		<?php 

		foreach($icons as $icon)

		{ 

			$icon_url = plugins_url( 'images/markers/'.$icon.'.png', dirname(__FILE__) );

			echo '<input type="radio" name="wp_osm_icon" id="wp_osm_icon_'.esc_attr($icon).'" value="'.esc_url($icon_url).'" /><label for="wp_osm_icon_'.esc_attr($icon).'" class="small_label"><img src="'.esc_url($icon_url).'" /></label>';

		}

		?>

		<strong class="notice notice-warning"><?php esc_html_e('Need more icons or custom icons? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a></strong>

	</div>



	<label for=""><?php esc_html_e('Name:', 'wp-open-street-map') ?></label> <input type="text" name="name" id="wp_osm_name" value="<?php echo esc_attr($marker->name) ?>" /><br />



	<label for=""><?php esc_html_e('Description:', 'wp-open-street-map') ?></label> <textarea name="description" id="wp_osm_description"><?php echo esc_html($marker->description) ?></textarea><br />

	<p><strong class="notice notice-warning"><?php esc_html_e('Need advanced editor? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a></strong></p>



	<p><strong class="important"><?php esc_html_e('Click on the map where you want to put the marker', 'wp-open-street-map') ?></strong></p>



</form>



<?php if(isset($_GET['saved'])) : ?>

	<h3><?php esc_html_e('Markers saved!', 'wp-open-street-map') ?></h3>

<?php endif; ?>

<h3><?php esc_html_e('If you change zoom or position don\'t forget to save map settings up!', 'wp-open-street-map') ?></h3>

<p><b>Shortcode of your map: </b><input type="text" value="[wp-osm id=<?php echo (int)$map->id ?>]" readonly onClick="this.select()" /></p>

<div id="wp_openstreetmaps_search">

	<label>Search an address:</label> <input type="text" name="q" placeholder="Avenue du petit port, Annecy-le-Vieux" autocomplete="off" />

	<div class="results"></div>

</div>

<div id="wp_osm_container">

	<div id="wp_osm" style="width: <?php echo esc_attr($map->width); ?>; height: <?php echo esc_attr($map->height); ?>; float: left;">

		<div id="wp_osm_popup" class="ol-popup">

	      <a href="#" class="ol-popup-closer"></a>

	      <div class="popup-content"></div>

	    </div>

	</div>

</div>

<div id="wp_osm_markers">

	<h2><?php esc_html_e('Markers', 'wp-open-street-map') ?></h2>

	<form class="form_wp_osm" action="" method="post">

		<div class="markers">

		<?php



		if(sizeof($markers) > 0)

		{

			foreach( $markers as $i => $marker )

			{

				echo '<div class="marker">';

				echo '<img src="'.esc_url($marker->icon).'" /> '.esc_html($marker->name).'			

					<a href="#" rel="'.(int)$marker->id.'" class="remove" title="Remove marker"><img src="'.esc_url(plugins_url( 'images/remove.png', dirname(__FILE__))).'" /></a>

					<div class="marker_edit">

						<input type="hidden" name="icon_coords[]" value="'.(int)$marker->longitude.','.(int)$marker->latitude.'" />'; 

						foreach($icons as $icon)

						{ 

							$time = time();

							$icon_url = plugins_url( 'images/markers/'.$icon.'.png', dirname(__FILE__) );

							echo '<input type="radio" name="icon_url['.(int)$i.']" id="wp_osm_icon_'.esc_attr($icon).'_'.(int)$time.'" value="'.esc_url($icon_url).'" '.($icon_url == $marker->icon ? 'checked' : '').' /><label class="small_label" for="wp_osm_icon_'.esc_attr($icon).'_'.(int)$time.'"><img src="'.esc_url($icon_url).'" /></label>';

						}

						echo '<br /><label>'.esc_html__('Name:', 'wp-open-street-map').'</label><input type="text" name="icon_name[]" value="'.esc_attr($marker->name).'" /><br />

						<label>'.esc_html__('Description:', 'wp-open-street-map').' </label><textarea name="icon_description[]">'.esc_html($marker->description).'</textarea><br />

						<input type="submit" class="button button-primary" value="'.esc_html__('Save marker', 'wp-open-street-map').'" />

					</div>

				</div>';



			}

		}



		?>

		</div>

		<input type="submit" class="button button-primary" value="<?php esc_html_e('Save all markers', 'wp-open-street-map') ?>" /> <a class="button button-secondary" href="<?php echo esc_url(admin_url('admin.php?page=wp_openstreetmaps')); ?>"><?php esc_html_e('Back to maps', 'wp-open-street-map') ?></a>



		<p><strong class="notice notice-warning"><?php esc_html_e('Need a polyline between your markers? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a></strong></p>



		<h3>If you enjoy this plugin, <a href="https://wordpress.org/support/plugin/wp-open-street-map/reviews/#new-post" target="_blank">leave a review!</a></h3>

		<h3><?php esc_html_e('Need more options? Look at', 'wp-open-street-map') ?> <a href="https://www.info-d-74.com/en/produit/wp-openstreetmap-pro-plugin-wordpress/" target="_blank">WP OpenStreetMap Pro</a></h3>

	</form>

</div>



<link rel="stylesheet" href="<?php echo esc_url(plugins_url('js/OpenLayers/v6.5.0/css/ol.css', dirname(__FILE__))) ?>" type="text/css">

<script src="<?php echo esc_url(plugins_url('js/OpenLayers/v6.5.0/build/ol.js', dirname(__FILE__))) ?>"></script>

<script>



	var submitted = false;



	window.onload = function(){



		jQuery("form").submit(function() {

	    	submitted = true;

	    });



		var marker_click = false;



	    var map = new ol.Map({

	        target:  jQuery('#wp_osm').get(0),

	        layers: [

	          new ol.layer.Tile({

	            source: new ol.source.OSM()

	          })

	        ],

	        view: new ol.View({

	        	projection: 'EPSG:3857',

	          	center: [ <?php echo (int)$map->latitude; ?>,  <?php echo (int)$map->longitude; ?>],

	          	zoom: <?php echo (int)$map->zoom ?>

	        })

	    });



	    var popup_div = jQuery('#wp_osm_popup').get(0);



	    var popup_overlay = new ol.Overlay({

		  element: popup_div,

		  autoPan: true,

		  autoPanAnimation: {

		    duration: 250,

		  },

		});

		map.addOverlay(popup_overlay);



		jQuery('#wp_osm_popup .ol-popup-closer').click(function () {

		  popup_overlay.setPosition(undefined);

		  //closer.blur();

		  return false;

		});



		var points = [], current_line = [];

		var markerFeatures =[];



	    //on ajoute les markers déjà enregistrés

	    jQuery('#wp_osm_markers .markers .marker ').each(function(i){



	    	//on ajoute le marker avec l'icone

	    	var coords = jQuery(this).find('input[name="icon_coords[]"]').val().split(',');



			var icon_url = jQuery(this).find('input[name="icon_url['+i+']"]:checked').val();



			var iconFeature = new ol.Feature({

			  geometry: new ol.geom.Point([coords[0],coords[1]]),

			  name: jQuery(this).find('input[name="icon_name[]"]').val(),

			  description: jQuery(this).find('textarea[name="icon_description[]"]').val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2')

			});



			var icon_img = new Image();

			icon_img.src = icon_url;



			var icon = new ol.style.Icon({

			    anchor: [0.5, 0],

			    anchorOrigin: 'bottom-left',

			    anchorXUnits: 'fraction',

			    anchorYUnits: 'pixels',

			    src: icon_url,

			    scale: 0,

			    /*img: icon_img,

			    imgSize: [30, 30]*/

			});



			var iconStyle = new ol.style.Style({

			  image: icon

			});



			icon_img.onload = function() {



				console.log(icon_img.width);

				icon.setScale(30/icon_img.width);

				iconFeature.changed();

			}



			iconFeature.setStyle(iconStyle);



			markerFeatures.push(iconFeature);



	    });



	    var vectorSource = new ol.source.Vector({

		  features: markerFeatures,

		});



		var vectorLayer = new ol.layer.Vector({

		  source: vectorSource,

		  zIndex: 2

		});



		map.addLayer(vectorLayer);



		map.on('click', function (evt) {

			  var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {

			    return feature;

			  });

			  if (feature) {

			    var description = feature.get('description');

			    if(description != '')

			    {

				    var coordinate = evt.coordinate;

				    //console.log(feature.get('name'));

					jQuery(popup_div).find('.popup-content').html(description);

					popup_overlay.setPosition(coordinate);

				}



			} else {

				var coordinate = evt.coordinate;



				//on enlève les résultats de recherche

				jQuery('#wp_openstreetmaps_search .results').html('');



			  	//on ferme la popup

			    popup_overlay.setPosition(undefined);



			    //on enlève les résultats de recherche

				jQuery('#wp_openstreetmaps_pro_search .results').html('');



				//icon custom ?

				var icon_custom = jQuery('#wp_osm_new_marker input.input_icon_preview').val();

				console.log(icon_custom);

				if(icon_custom)

				{

					var icon_url = icon_custom;						

				}

				//icon classique

				else

					var icon_url = jQuery('input[name="wp_osm_icon"]:checked').val();



				if(icon_url != undefined)

				{

					//save TinyMCE

		    		//tinyMCE.triggerSave();



					var iconFeature = new ol.Feature({

					  geometry: new ol.geom.Point([coordinate[0],coordinate[1]]),

					   name: jQuery('#wp_osm_name').val(),

					   description: jQuery('#wp_osm_description').val().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br />$2'),

					});



					var icon_img = new Image();

					icon_img.src = icon_url;



					var icon = new ol.style.Icon({

					    anchor: [0.5, 0],

					    anchorOrigin: 'bottom-left',

					    anchorXUnits: 'fraction',

					    anchorYUnits: 'pixels',

					    src: icon_url,

					    scale: 0,

					    /*img: icon_img,

					    imgSize: [30, 30]*/

					});



					var iconStyle = new ol.style.Style({

					  image: icon

					});



					icon_img.onload = function() {



						console.log(icon_img.width);

						icon.setScale(30/icon_img.width);

						iconFeature.changed();

					}



					iconFeature.setStyle(iconStyle);



					//markerFeatures.push(iconFeature);

					vectorSource.addFeature(iconFeature);					



					//on ajoute le marker à la liste

				    var icons_list = [];

					<?php 

					foreach($icons as $icon)

					{ 

						$time = time();

						$icon_url = plugins_url( 'images/markers/'.$icon.'.png', dirname(__FILE__) );

						echo 'icons_list.push("'.esc_url($icon_url).'");';

					}

					?>

					var nb = jQuery('#wp_osm_markers .markers .marker').length;

					var form = '<div class="marker_edit">';

					form += '<input type="hidden" name="icon_coords[]" value="'+coordinate[0]+','+coordinate[1]+'" />';

					form += '<label>Icon:</label>';

					for(var i in icons_list)

						form += '<input type="radio" name="icon_url['+nb+']" id="wp_osm_icon_'+i+'_'+nb+'" value="'+icons_list[i]+'" '+(icon_url == icons_list[i] ? 'checked' : '')+' /><label class="small_label" for="wp_osm_icon_'+i+'_'+nb+'"><img src="'+icons_list[i]+'" /></label>';

					form += '<br /><label>Name: </label><input type="text" name="icon_name[]" value="'+jQuery('#wp_osm_name').val()+'" /><br />';

					form += '<label>Description: </label><textarea name="icon_description[]">'+jQuery('#wp_osm_description').val()+'</textarea><br />';

					form += '<input type=\"submit\" value=\"Save marker\" class=\"button button-primary\" /></div>';

				    jQuery('#wp_osm_markers .markers').append('<div class="marker"><img src="'+icon_url+'" /> '+jQuery('#wp_osm_name').val()+'<a href="#" class="remove"><img src="<?php echo esc_url( plugins_url( 'images/remove.png', dirname(__FILE__))) ?>" /></a>'+form+'</div>');



				    //édition d'un marker

				    jQuery('.marker:last-child > img').click(function(){



			        	jQuery(this).parent().find('.marker_edit').toggle();



			        });



					//suppression d'un marker

			        jQuery('.marker:last-child .remove').click(function(){



			        	jQuery(this).parent().remove();

			        	return false;



			        });

				}

				else

					alert('Please choose an icon first!');

			}

		});



		map.on('pointermove', function (evt) {





		  var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {

		    return feature;

		  });



		  if (feature)

		  	//hover marker

		  	jQuery(map.getTargetElement()).find('canvas').css('cursor', 'pointer');



		  else

		  	//not hover marker

		  	jQuery(map.getTargetElement()).find('canvas').css('cursor', 'default');



		});



		window.onbeforeunload = function(e) {

			if(!submitted)

		  		return "Are you sure to leave this page (if you haven't save your markers, they will be lost...)?";

		};



        //édition d'un marker

        jQuery('.marker > img').click(function(){



        	jQuery(this).parent().find('.marker_edit').toggle();



        });



        //suppression d'un marker

        jQuery('.marker .remove').click(function(){



        	jQuery(this).parent().remove();

        	return false;



        });



        //Mette à jour les coordonnées et le zoom

	    setInterval(function(){ 



	    	var position = map.getView().getCenter();

	    	//console.log(position);

	    	jQuery('#wp_osm_latitude').val(position[0]);

	    	jQuery('#wp_osm_longitude').val(position[1]);



	    	var zoom = map.getView().getZoom();

	    	jQuery('#wp_osm_zoom').val(zoom);



	    }, 1000);



	    jQuery('#wp_osm_width').change(function(){



	    	jQuery('#wp_osm').width(jQuery(this).val());



	    	//on met à jour la taille de la carte

	    	map.updateSize();



	    });



	    jQuery('#wp_osm_height').change(function(){



	    	jQuery('#wp_osm').height(jQuery(this).val());



	    	//on met à jour la taille de la carte

	    	map.updateSize();



	    });



	    //search

		var old_search_query = jQuery('#wp_openstreetmaps_search input[name="q"]').val();



		setInterval(function(){



			var search_query = jQuery('#wp_openstreetmaps_search input[name="q"]').val();



			if(old_search_query != search_query)

			{

				old_search_query = search_query;

				//var _this = this;

				var q = jQuery('#wp_openstreetmaps_search input[name="q"]').val();

				if(q != "")

				{ 

					jQuery.getJSON( "https://nominatim.openstreetmap.org/search.php?q="+q+"&polygon_geojson=1&format=jsonv2", function( data ) {



						console.log(data);

						if(data.length > 0)

						{

							var results = '<ul>';

							for(var i in data)

							{

								console.log(data[i]);

								results += '<li data-lat="'+data[i].lat+'" data-lon="'+data[i].lon+'">'+data[i].display_name+'</li>';

							}

							results += '</ul>';



							jQuery('#wp_openstreetmaps_search .results').html(results);



							jQuery('#wp_openstreetmaps_search .results li').click(function(){



								var coords = new ol.geom.Point([jQuery(this).data('lon'),jQuery(this).data('lat')]).transform('EPSG:4326', 'EPSG:3857');

			    				var view = map.getView();

			    				view.setCenter(coords.getCoordinates());

			    				view.setZoom(14);

			    				map.setView(view);



			    				jQuery(this).parent().html('');



							});

						}

						else

							jQuery('#wp_openstreetmaps_search .results').html('<p>No result found!</p>');

					});

				}

				else

					jQuery('#wp_openstreetmaps_search .results').html('');

			}



		}, 1000);



	};



</script>

