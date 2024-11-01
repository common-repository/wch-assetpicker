<?php
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

 *******************************************************************************/
// create custom plugin settings menu
add_action('admin_menu', 'wch_assetpicker_plugin_create_menu');

function wch_assetpicker_plugin_create_menu() {

	//create new top-level menu
//	add_menu_page('Asset Palette Settings', 'Asset Palette Settings', 'administrator', __FILE__, 'my_cool_plugin_settings_page' , plugins_url('/icon/wchIcon-icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_my_cool_plugin_settings' );
	
}

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_options_page( 'Asset Palette Settings Options', 'Asset Palette', 'manage_options', 'my-unique-identifier', 'my_cool_plugin_settings_page' );
}

function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}

function register_my_cool_plugin_settings() {
	//register our settings
	register_setting( 'wch-assetpicker-settings-group', 'apiUrl' );
	register_setting( 'wch-assetpicker-settings-group', 'type-image' );
	register_setting( 'wch-assetpicker-settings-group', 'type-video' );
	register_setting( 'wch-assetpicker-settings-group', 'type-file' );
	register_setting( 'wch-assetpicker-settings-group', 'type-document' );
}

function my_cool_plugin_settings_page() {
?>
<div class="wrap">
<h1>Settings: IBM Watson Content Hub Asset Palette</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'wch-assetpicker-settings-group' ); ?>
    <?php do_settings_sections( 'wch-assetpicker-settings-group' ); ?>
    <table class="apiurl-table">
		<tr valign="top"><td colspan="2">Obtain the API URL from the "Hub Information" dialog available off the top navigation bar of the content hub authoring UI. <br>
		The API URL is of the form: https://{tenant-host}/api/{tenant-id}</td></tr>
        <tr valign="top"><th scope="row">APIurl</th><td><input type="url" name="apiUrl" size="80" value="<?php echo esc_attr( get_option('apiUrl') ); ?>" /></td></tr>
		<tr valign="top"><td></td></tr>
		<tr valign="top"><td>Show asset types:</td></tr>
        <tr valign="top"><th scope="row">image</th><td><input name="type-image" type="checkbox" value="1" <?php checked( '1', get_option( 'type-image' ) ); ?> /></td></tr>
        <tr valign="top"><th scope="row">video</th><td><input name="type-video" type="checkbox" value="1" <?php checked( '1', get_option( 'type-video' ) ); ?> /></td></tr>
        <tr valign="top"><th scope="row">file</th><td><input name="type-file" type="checkbox" value="1" <?php checked( '1', get_option( 'type-file' ) ); ?> /></td></tr>
        <tr valign="top"><th scope="row">document</th><td><input name="type-document" type="checkbox" value="1" <?php checked( '1', get_option( 'type-document' ) ); ?> /></td></tr>
    </table>    
    <?php submit_button(); ?>
</form>
<table align="right" width=100%><tr align="right" width=100%><td align="right" width=100% style="display:none;">
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="NWNW5TXBRVJNQ">
<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="WordPress Plugin donation for IBM Watson Content Hub Asset Palette.">
<img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
</form>
</td></tr></table>
</div>
<?php } ?>