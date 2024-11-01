<?php
/*
/*******************************************************************************

 * Copyright IBM Corp. 2017
 *

 * Licensed under the Apache License, Version 2.0 (the "License");

 * you may not use this file except in compliance with the License.

 * You may obtain a copy of the License at

 *

 * http://www.apache.org/licenses/LICENSE-2.0

 *

 * Unless required by applicable law or agreed to in writing, software

 * distributed under the License is distributed on an "AS IS" BASIS,

 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

 * See the License for the specific language governing permissions and

 * limitations under the License.

 *******************************************************************************
Plugin Name: IBM Watson Content Hub Asset Palette
Description: This plugin provides an installable plugin for WordPress which integrates with IBM Watson Content Hub. 
Version: 1.0.12
Author: Sascha Schefenacker
Author URI: https://www.linkedin.com/in/sascha-schefenacker-7815b9/
Plugin URI: https://github.com/ibm-wch/sample-wp-wch-asset-palette
*/


/**
 * Define some useful constants
 **/
define('WCH_ASSETPICKER_VERSION', '1.0');
define('WCH_ASSETPICKER_DIR', plugin_dir_path(__FILE__));
define('WCH_ASSETPICKER_URL', plugin_dir_url(__FILE__));

/**
 * Load files
 * 
 **/

function wch_assetpicker_load(){
		
    if(is_admin()) //load admin files only in admin
        require_once(WCH_ASSETPICKER_DIR.'includes/admin.php');
    require_once(WCH_ASSETPICKER_DIR.'includes/core.php');
}

wp_enqueue_script('jquery');
wch_assetpicker_load();

function getApiUrl() {
	return esc_attr( get_option('apiUrl') );
}

function getHostRendering($apiurl) {
	$temp2= substr($apiurl,strpos($apiurl,"my"));
	$temp3= substr($temp2,0,4+strpos($temp2,".com"));
	return esc_attr( $temp3 );
}

function getTenantId($apiurl) {
	$temp2= substr($apiurl,5+strpos($apiurl,"/api/"));
	return esc_attr( $temp2 );
}

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");
    $apiUrlPHP = getApiUrl();
    $hostRendering = getHostRendering($apiUrlPHP);
    $tenantId = getTenantId($apiUrlPHP);
    ?>
	
	<script type="text/javascript">
	var apiUrl="<?php echo "$apiUrlPHP" ?>";
	var typeOptions="";
	
	if ("<?php echo get_option('type-image') ?>"==="1") {
		if (typeOptions==="" ) {
			typeOptions = typeOptions+"assetType:image";
		} else {
			typeOptions = typeOptions+"%20assetType:image";
		}
	}
	if ("<?php echo get_option('type-video') ?>"==="1") {
		if (typeOptions==="" ) {
			typeOptions = typeOptions+"assetType:video";
		} else {
			typeOptions = typeOptions+"%20assetType:video";
		}
	}
	if ("<?php echo get_option('type-file') ?>"==="1") {
		if (typeOptions==="" ) {
			typeOptions = typeOptions+"assetType:file";
		} else {
			typeOptions = typeOptions+"%20assetType:file";
		}
	}
	if ("<?php echo get_option('type-document') ?>"==="1") {
		if (typeOptions==="" ) {
			typeOptions = typeOptions+"assetType:document";
		} else {
			typeOptions = typeOptions+"%20assetType:document";
		}
	}
	if (typeOptions.length>0) {
		typeOptions="&fq="+typeOptions;
	}
	
	var hostRendering="<?php echo "$hostRendering" ?>";
	var tenantId="<?php echo "$tenantId" ?>";
	</script>
			<style type="text/css">
				.wchHidden {
					display: none;
				}
			</style>
			<script type="text/javascript" src="<?php echo WCH_ASSETPICKER_URL?>/pickerResultHandler.js"></script>
			<script type="text/javascript">
				function resultHandler(e) {
					// no need to close the picker as we have it integrated 
					// $('#pickerDialog').dialog('close');
					// console.log(e);
					// alert(e.data);
					var result = JSON.parse(e.data);
					var resourceURL = assembleResourcURL(result);
					console.log("assembled resource url " + resourceURL);
					if (resourceURL) {
						var targetNode = getRteNode();
						if (targetNode) {
							//if image
							if ( resourceURL.toLowerCase().endsWith("jpeg") || resourceURL.toLowerCase().endsWith("jpg") || resourceURL.toLowerCase().endsWith("gif") || resourceURL.toLowerCase().endsWith("png") ) {
								var img = jQuery('<img id="dynamic">');	
								img.attr("src", resourceURL);
								img.appendTo(targetNode);
							} else {
								var link = jQuery('<a id="dynamic">'+resourceURL+'</a>');
								link.attr("href", resourceURL);
								link.appendTo(targetNode);
							}
						}
					}
				}

				// 1. 'addEventListener' is for standards-compliant web browsers and 'attachEvent' is for IE Browsers
				var eventMethod = window.addEventListener ? 'addEventListener' : 'attachEvent';
				var eventer = window[eventMethod];

				// 2. if 'attachEvent', then we need to select 'onmessage' as the event
				// else if 'addEventListener', then we need to select 'message' as the event
				var messageEvent = eventMethod === 'attachEvent' ? 'onmessage' : 'message';

				// Listen to message from child iFrame window
				console.log("pickerResultHandler register event listener");
				eventer(messageEvent, resultHandler, false);

				function search() {
							var nodes = document.querySelectorAll("#wchframe");
							for (var i = nodes.length-1; i >= 0; i--) {
								console.log("Initializing iframe");
   							nodes[i].src = "https://www.digitalexperience.ibm.com/content-picker/picker.html?fq=classification:asset"+typeOptions+"&apiUrl="+encodeURIComponent(apiUrl);
							}
   						jQuery("#wchframe").removeClass("wchHidden");
   						jQuery("#wchframeloader").addClass("wchHidden");
						};
				jQuery( document ).ready(function() {
							search();
				});
				
			</script>
			<div>
				<div id="wchframeloader">Please wait... WCH connection is initializing...</div>
				<iframe id="wchframe" class="wchHidden" height="800" width="100%"></iframe>
			</div>
    <?php  
}
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "IBM Watson Content Hub Asset Palette", "custom_meta_box_markup", "page", "normal", "high", null);
    add_meta_box("demo-meta-box", "IBM Watson Content Hub Asset Palette", "custom_meta_box_markup", "post", "normal", "high", null);
}

//add to right column
add_action("add_meta_boxes", "add_custom_meta_box");

//add to center column
add_action("edit_post", "add_custom_meta_box");

/**
 * Activation, Deactivation and Uninstall Functions
 * 
 **/
register_activation_hook(__FILE__, 'wch_assetpicker_activation');
register_deactivation_hook(__FILE__, 'wch_assetpicker_deactivation');


function wch_assetpicker_activation() {
	//actions to perform once on plugin activation go here
    //register uninstaller
    register_uninstall_hook(__FILE__, 'wch_assetpicker_uninstall');
}

function wch_assetpicker_deactivation() {
	// actions to perform once on plugin deactivation go here
}

function wch_assetpicker_uninstall(){
    //actions to perform once on plugin uninstall go here
}

include "settings.php";
?>