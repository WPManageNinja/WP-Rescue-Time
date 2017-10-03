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
			array($this, 'main_page'), 'data:image/svg+xml;base64,' . base64_encode( '<svg enable-background="new 0 0 100 100" height="100px" id="Layer_1" version="1.1" viewBox="0 0 100 100" width="100px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g><g><g/></g><g/></g></g><g><path d="M52,51.055V35.5c0-1.104-0.896-2-2-2s-2,0.896-2,2v15.555c-1,0.693-2,1.969-2,3.445c0,2.209,1.791,4,4,4   s4-1.791,4-4C54,53.023,53,51.748,52,51.055z" fill="#231F20"/><path d="M50,24.5c-16.542,0-30,13.458-30,30s13.458,30,30,30s30-13.458,30-30S66.542,24.5,50,24.5z M50,82.5   c-15.439,0-28-12.561-28-28s12.561-28,28-28s28,12.561,28,28S65.439,82.5,50,82.5z" fill="#231F20"/><path d="M82.795,37.4l1.465-0.845c0.407-0.235,0.547-0.76,0.312-1.167l-3.646-6.315   c-0.235-0.407-0.76-0.547-1.167-0.312l-1.949,1.125c-0.049,0.029-0.082,0.073-0.124,0.109C71.95,23.519,64,19.072,55,17.851V15h-3   v-2h2V9h-8v4h2v2h-3v2.851c-9,1.238-17.161,5.789-22.915,12.405c-0.072-0.148-0.172-0.283-0.325-0.371l-1.949-1.125   c-0.406-0.235-0.932-0.094-1.167,0.312l-3.646,6.315c-0.235,0.407-0.094,0.932,0.312,1.167l1.795,1.036   C14.489,42.663,13,48.409,13,54.5c0,20.402,16.598,37,37,37s37-16.598,37-37C87,48.333,85.475,42.519,82.795,37.4z M50,87.5   c-18.196,0-33-14.804-33-33s14.804-33,33-33s33,14.804,33,33S68.196,87.5,50,87.5z" fill="#231F20"/></g></svg>' ), 25);
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

		$metaValue = false;
		if($_POST['_rescue_time_api_key']) {
			$metaValue = sanitize_text_field($_POST['_rescue_time_api_key']);
			try {
				$client = new RescueTime\Client($metaValue);
				$activities = $client->getActivities(new RescueTime\RequestQueryParameters(['perspective' => 'rank']));
				if($activities) {
					update_user_meta( $user_id, '_rescue_time_api_key',  $metaValue);
                }
            } catch ( Exception $e ) {
				add_action( 'user_profile_update_errors', function(&$errors, $update = null, &$user  = null) {
					$errors->add('_rescue_time_api_key', "<strong>ERROR</strong>: Rescue Time API key is not correct");
                } );
            }
		} else {
			update_user_meta( $user_id, '_rescue_time_api_key',  $metaValue);
        }
        
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
