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
			array($this, 'main_page'), 'data:image/svg+xml;base64,' . base64_encode( '<svg enable-background="new 0 0 64 64" height="64px" id="Layer_1" version="1.1" viewBox="0 0 64 64" width="64px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><g><path d="M52.419,15.975c0,0,1.013,1.019,1.727,0.002l1.363-1.953c0.476-0.687-0.139-1.162-0.202-1.209    l-8.265-5.775H47.04c-0.509-0.354-0.847-0.139-1.024,0.06l-0.148,0.213l-1.259,1.802c-0.006,0.007-0.71,1.119,0.416,1.707v0.001    c1.61,0.792,4.563,2.462,7.392,5.158L52.419,15.975z" fill="#241F20"/></g><g><path d="M38.512,0.071H25.488c-1.011,0-1.839,0.812-1.839,1.839v1.518c0,1.026,0.828,1.854,1.839,1.854h0.644    v1.072c0.001,1.541,0.974,1.669,1.462,1.636c0.083-0.012,0.169-0.025,0.26-0.037c0.001,0,0.013-0.003,0.013-0.003L27.866,7.95    c1.734-0.237,4.605-0.464,7.898-0.045l0.002-0.003c0,0,2.109,0.391,2.103-1.549V5.281h0.644c1.012,0,1.839-0.827,1.839-1.854V1.91    C40.351,0.884,39.523,0.071,38.512,0.071z" fill="#241F20"/></g><path d="M32,10.301c-14.808,0-26.812,12.005-26.812,26.815c0,14.807,12.004,26.812,26.812,26.812   c14.809,0,26.812-12.006,26.812-26.812C58.812,22.306,46.809,10.301,32,10.301z M33.717,37.108   c-1.575,0.002-1.709-1.094-1.717-1.41V17.155c0.046-0.645,0.381-1.86,2.248-1.546c0.037,0.005,0.072,0.009,0.111,0.014   c0.12,0.02,0.233,0.036,0.32,0.043c5.44,0.764,17.373,4.302,18.864,20.343c-0.042,0.446-0.295,1.096-1.412,1.103   C42.529,37.085,36.454,37.097,33.717,37.108z" fill="#241F20"/></g></svg>' ), 25);
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
