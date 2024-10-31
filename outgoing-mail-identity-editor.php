<?php 

/*
 * Plugin Name: Outgoing Mail Identity Editor
 * Description: Change the default name and email address on outgoing WordPress emails (e.g. Password Reset).
 * Version: 1.0
 * Author: Adam Bradford
 * Author URI: https://www.adambradford.com
 */


if ( ! defined( 'ABSPATH' ) ) { 
    exit; 
}

// Show a link to the settings in the WordPress backend (as a submenu of 'Settings')
function omie_outgoing_mail_menu() {
	add_options_page(
		'Outgoing Mail Identity Editor Options', 
		'Outgoing Mail Identity Editor', 
		'manage_options', 
		'omie_outgoing_mail', 
		'omie_outgoing_mail_output'
	);
}

add_action('admin_menu', 'omie_outgoing_mail_menu');



// Register the settings
function omie_outgoing_mail_register() {
	add_settings_section(
		'omie_outgoing_mail_section', 
		'Outgoing Mail Identity Editor Options', 
		'omie_outgoing_mail_intro', 
		'omie_outgoing_mail');

	// Name field
	add_settings_field(
		'omie_outgoing_mail_name_id',
		'Outgoing Mail Sender‘s Name',
		'omie_outgoing_mail_sendername',
		'omie_outgoing_mail',
		'omie_outgoing_mail_section');

	register_setting(
		'omie_outgoing_mail_section',
		'omie_outgoing_mail_name_id');

	// Email address field
	add_settings_field(
		'omie_outgoing_mail_email_id', 
		'Outgoing Mail Email Address', 
		'omie_outgoing_mail_address', 
		'omie_outgoing_mail',  
		'omie_outgoing_mail_section');

	register_setting(
		'omie_outgoing_mail_section', 
		'omie_outgoing_mail_email_id');
}

add_action('admin_init', 'omie_outgoing_mail_register');


// Settings page content
function omie_outgoing_mail_intro() {
	echo '<p>Use these settings to change the WordPress default email sender\'s name and email address.</p>';
}

function omie_outgoing_mail_sendername(){ // Name field
	echo '<input name="omie_outgoing_mail_name_id" type="text" class="regular-text" value="'.get_option('omie_outgoing_mail_name_id').'" placeholder="Enter your preferred name"/>';
	echo '<p class="description">Enter your sender\'s name </p>';
}
function omie_outgoing_mail_address() { // Email address field
	echo '<input name="omie_outgoing_mail_email_id" type="email" class="regular-text" value="'.get_option('omie_outgoing_mail_email_id').'" placeholder="Enter your preferred email address"/>';
	echo '<p class="description">Enter your sender\'s email address</p>';
}	

function omie_outgoing_mail_output(){
?>	
	<?php settings_errors();?>
	<form action="options.php" method="POST">
		<?php do_settings_sections('omie_outgoing_mail');?>
		<?php settings_fields('omie_outgoing_mail_section');?>
		<?php submit_button();?>
	</form>
<?php }







// Add plugin action links
function omie_plugin_action_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php?page=omie_outgoing_mail' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
	), $links );
	return $links;
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'omie_plugin_action_links' );



// The bit that does the actual work...
// Change sender email address for WordPress system generated emails 
function omie_mail_from_address( $original_email_address ) {
return get_option('omie_outgoing_mail_email_id');
}
// Change sender name for WordPress system generated emails
function omie_mail_from_name( $original_email_from ) {
return get_option('omie_outgoing_mail_name_id');
}

// Hook functions to WordPress filters 
add_filter('wp_mail_from', 'omie_mail_from_address');
add_filter('wp_mail_from_name', 'omie_mail_from_name');



?>