jQuery(document).ready(function(){

	jQuery('.wp_osm').each(function(){

		/*var map = new OpenLayers.Map({
		      div: jQuery(this).get(0),
		      projection: new OpenLayers.Projection('EPSG:900913'),
		      'displayProjection': new OpenLayers.Projection('EPSG:4326')
		});
		map.addLayer(new OpenLayers.Layer.OSM("New Layer"));

		var lonLat = new OpenLayers.LonLat( jQuery(this).attr('data-lon'), jQuery(this).attr('data-lat') );
		          
	    zoom = jQuery(this).attr('data-zoom');

	    var layerStyle = OpenLayers.Util.extend({}, OpenLayers.Feature.Vector.style['default']);

    	var pointLayer = new OpenLayers.Layer.Vector("Layer Name", {style: layerStyle});

    	map.addLayer(pointLayer);*/

    	var map = new ol.Map({
	        target:  jQuery(this).get(0),
	        layers: [
	          new ol.layer.Tile({
	            source: new ol.source.OSM()
	          })
	        ],
	        view: new ol.View({
	        	projection: 'EPSG:3857',
	          	center: [  jQuery(this).attr('data-lat'), jQuery(this).attr('data-lon')],
	          	zoom: jQuery(this).attr('data-zoom')
	        })
	    });

    	//marker styles
	    /*var markerStyle = OpenLayers.Util.extend({}, layerStyle);	    
	    markerStyle.pointRadius = 18;
	    markerStyle.fil/*lOpacity = 1;

	    map.setCenter (lonLat, zoom);*/

	    var popup_div = jQuery(this).find('.ol-popup').get(0);

	    var popup_overlay = new ol.Overlay({
		  element: popup_div,
		  autoPan: true,
		  autoPanAnimation: {
		    duration: 250,
		  },
		});
		map.addOverlay(popup_overlay);

		jQuery(this).find('.ol-popup .ol-popup-closer').click(function () {
		  popup_overlay.setPosition(undefined);
		  //closer.blur();
		  return false;
		});

	    var points = [], current_line = [];
	    var markerFeatures =[];

	    jQuery(this).find('.marker').each(function(){

	    	//on ajoute le marker avec l'icone
	    	/*var lon = jQuery(this).attr('data-lon');
	    	var lat = jQuery(this).attr('data-lat');
			var myPoint = new OpenLayers.Geometry.Point(lon, lat);

			var icon_url = jQuery(this).attr('data-icon');
			var currentmarkerStyle = OpenLayers.Util.extend({}, markerStyle);	  
		    currentmarkerStyle.externalGraphic = icon_url;
			var myPointFeature = new OpenLayers.Feature.Vector(myPoint, null, currentmarkerStyle);

			myPointFeature.attributes = {
				name: jQuery(this).attr('data-name'),
				description: jQuery(this).attr('data-description'),
				wikiPage: "http://www.windsorpubliclibrary.com/?page_id=45"
			};
			pointLayer.addFeatures( [ myPointFeature ] );*/

			//on ajoute le marker avec l'icone
	    	var lon = jQuery(this).attr('data-lon');
	    	var lat = jQuery(this).attr('data-lat');
			//var myPoint = new OpenLayers.Geometry.Point(lon, lat);

			var icon_url = jQuery(this).attr('data-icon');
			
			var iconFeature = new ol.Feature({
			  geometry: new ol.geom.Point([lon,lat]),
			  name: jQuery(this).attr('data-name'),
			  description:  jQuery(this).attr('data-description')
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
			console.log('click');
			var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature) {
			    return feature;
			});
		  	if (feature) {
		  		console.log('marker click');
			    var description = feature.get('description');
			    if(description != '')
			    {
			    	console.log('popup');
				    var coordinate = evt.coordinate;
				    //console.log(feature.get('name'));
					jQuery(popup_div).find('.popup-content').html(description);
					popup_overlay.setPosition(coordinate);
				}
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

	    /*selectControl = new OpenLayers.Control.SelectFeature( pointLayer );
		map.addControl(selectControl);
		selectControl.activate();

	    pointLayer.events.on({
		    'featureselected': onFeatureSelect,
		    'featureunselected': onFeatureUnselect
		});

		function onFeatureSelect(clickInfo) {
		    clickedFeature = clickInfo.feature;
		    popup = new OpenLayers.Popup.FramedCloud(
		        "featurePopup",
		        clickedFeature.geometry.getBounds().getCenterLonLat(),
		        new OpenLayers.Size(120,250),
		        clickedFeature.attributes.description,
		        null,
		        true,
		        onPopupClose
		    );
		    clickedFeature.popup = popup;
		    popup.feature = clickedFeature;
		    map.addPopup(popup);
		}
		function onFeatureUnselect(clickInfo) {
		    feature = clickInfo.feature;
		    if (feature.popup) {
		        popup.feature = null;
		        map.removePopup(feature.popup);
		        feature.popup.destroy();
		        feature.popup = null;
		    }
		}
		function hoverFeatureCallback(clickInfo) {

		}
		function onPopupClose(closeInfo) {
		    selectControl.unselect(this.feature);
		}*/

	});

});