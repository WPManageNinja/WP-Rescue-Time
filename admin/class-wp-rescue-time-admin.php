<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpmanageninja.com
 * @since      1.0.0
 *
 * @package    Wp_Rescue_Time
 * @subpackage Wp_Rescue_Time/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Rescue_Time
 * @subpackage Wp_Rescue_Time/admin
 * @author     wpmanageninja <plugins@wpmanageninja.com>
 */
class Wp_Rescue_Time_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	
	public function add_menu() {
		add_menu_page(__('Rescue Time', 'wp-rescue-time'),
			__('Rescue Time', 'wp-rescue-time'), 'manage_options', 'wp-rescue-time',
			array($this, 'main_page'), 'data:image/svg+xml;base64,' . base64_encode( '
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 66.06 66.41"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 1</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M44.82,2.16A21.27,21.27,0,0,1,63.89,21.23H44.82V2.16M42.76,0V23.3h23.3A23.3,23.3,0,0,0,42.76,0Z"/><path class="cls-1" d="M23.53,2.06H42.7V21.23H23.53V2.06M21.47,0V23.3h23.3V0Z"/><path class="cls-1" d="M44.82,23.71H64V42.87H44.82V23.71m-2.06-2.06v23.3h23.3V21.64Z"/><path class="cls-1" d="M23.53,23.71H42.7V42.87H23.53V23.71m-2.06-2.06v23.3h23.3V21.64Z"/><path class="cls-1" d="M21.23,2.16V21.23H2.16A21.27,21.27,0,0,1,21.23,2.16M23.3,0A23.3,23.3,0,0,0,0,23.3H23.3V0Z"/><path class="cls-1" d="M2.06,23.71H21.23V42.87H2.06V23.71M0,21.64v23.3H23.3V21.64Z"/><path class="cls-1" d="M44.82,45.18H64V64.34H44.82V45.18m-2.06-2.06v23.3h23.3V43.11Z"/><path class="cls-1" d="M23.53,45.18H42.7V64.34H23.53V45.18m-2.06-2.06v23.3h23.3V43.11Z"/><path class="cls-1" d="M2.16,45.18H21.23V64.24A21.27,21.27,0,0,1,2.16,45.18M0,43.11H0a23.3,23.3,0,0,0,23.3,23.3h0V43.11Z"/></g></g></svg>' ), 25);
	}
	
	public function main_page() {
		$this->enqueue_styles();
		$this->enqueue_scripts();
		
		include(plugin_dir_path(__FILE__)
		        . 'partials/wp-rescue-time-admin-display.php');
	}

	public function show_rescue_profile_fields( $user ) { ?>

		<h3>Rescue Time Settings</h3>

		<table class="form-table">

			<tr>
				<th><label for="twitter">API Key</label></th>

				<td>
					<input type="password" name="_rescue_time_api_key" id="_rescue_time_api_key" value="<?php echo esc_attr( get_the_author_meta( '_rescue_time_api_key', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Please enter your Rescue Time API Key.</span>
				</td>
			</tr>

		</table>
	<?php }

	public function save_rescue_profile_key( $user_id ) {
		
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;

		update_user_meta( $user_id, '_rescue_time_api_key', sanitize_text_field($_POST['_rescue_time_api_key']) );
		
	}
	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-rescue-time-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-rescue-time-admin.js', array( 'jquery' ), $this->version, false );
	}

}
