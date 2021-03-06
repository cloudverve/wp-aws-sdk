<?php

use Aws\Common\Aws;

class Amazon_Web_Services extends AWS_Plugin_Base {

	/**
	 * @var string
	 */
	private $plugin_title;

	/**
	 * @var string
	 */
	private $sdk_version;

	/**
	 * @var string
	 */
	private $plugin_menu_title;

	/**
	 * @var string
	 */
	private $plugin_permission;

	/**
	 * @var string
	 */
	private $plugin_menu_parent;

	/**
	 * @var
	 */
	private $client;

	const SETTINGS_KEY = 'aws_settings';
	const SETTINGS_CONSTANT = 'AWS_SETTINGS';

	/**
	 * @param string $plugin_file_path
	 */
	function __construct( $plugin_file_path ) {
		$this->plugin_slug = 'amazon-web-services';

		parent::__construct( $plugin_file_path );

		do_action( 'aws_init', $this );

		if ( is_admin() ) {
			do_action( 'aws_admin_init', $this );
		}

		if ( is_multisite() ) {
			add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );
			$this->plugin_permission = 'manage_network_options';
		} else {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			$this->plugin_permission = 'manage_options';
		}

		$this->sdk_version        = $this->get_aws_sdk_version();
		$this->plugin_menu_parent = is_multisite() ? 'settings.php' : 'options-general.php';
		$this->plugin_title       = sprintf( __( 'Amazon Web Services SDK (%s)', 'amazon-web-services' ), $this->sdk_version );
		$this->plugin_menu_title  = __( 'AWS', 'amazon-web-services' );

		add_filter( 'plugin_action_links', array( $this, 'plugin_actions_settings_link' ), 10, 2 );
		add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta_links' ), 10, 2 );
		add_filter( 'network_admin_plugin_action_links', array( $this, 'plugin_actions_settings_link' ), 10, 2 );

		load_plugin_textdomain( 'amazon-web-services', false, dirname( plugin_basename( $plugin_file_path ) ) . '/languages/' );
	}

	/**
	 * Add the AWS menu item and sub pages
	 */
	function admin_menu() {
		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) ) {
			$icon_url = plugins_url( 'assets/img/icon16.png', $this->plugin_file_path );
		} else {
			$icon_url = false;
		}

		$hook_suffixes   = array();
		$hook_suffixes[] = add_submenu_page( $this->plugin_menu_parent, $this->plugin_title, $this->plugin_menu_title, $this->plugin_permission, $this->plugin_slug, array(
			$this,
			'render_page',
		), $icon_url );

		global $submenu;
		if ( isset( $submenu[ $this->plugin_slug ][0][0] ) ) {
			$submenu[ $this->plugin_slug ][0][0] = __( 'Access Keys', 'amazon-web-services' );
		}

		do_action( 'aws_admin_menu', $this );

		foreach ( $hook_suffixes as $hook_suffix ) {
			add_action( 'load-' . $hook_suffix, array( $this, 'plugin_load' ) );
		}

		if ( $icon_url === false ) {
			add_action( 'admin_print_styles', array( $this, 'enqueue_menu_styles' ) );
		}
	}

	/**
	 * Add sub page to the AWS menu item
	 *
	 * @param string       $page_title
	 * @param string       $menu_title
	 * @param string       $capability
	 * @param string       $menu_slug
	 * @param string|array $function
	 *
	 * @return string|false
	 */
	function add_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
		return add_submenu_page( $this->plugin_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	/**
	 * Load styles for the AWS menu item
	 */
	public function enqueue_menu_styles() {
		$this->enqueue_style( 'aws-global-styles', 'assets/css/global' );
	}

	/**
	 * Plugin loading enqueue scripts and styles
	 */
	public function plugin_load() {
		$this->enqueue_style( 'aws-styles', 'assets/css/styles' );
		$this->enqueue_script( 'aws-script', 'assets/js/script', array( 'jquery' ) );

		$this->handle_post_request();

		do_action( 'aws_plugin_load', $this );
	}

	/**
	 * Process the saving of the settings form
	 */
	function handle_post_request() {
		if ( empty( $_POST['action'] ) || 'save' != sanitize_key( $_POST['action'] ) ) { // input var okay
			return;
		}

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['_wpnonce'] ), 'aws-save-settings' ) ) { // input var okay
			die( __( "Cheatin' eh?", 'amazon-web-services' ) );
		}

		// Make sure $this->settings has been loaded
		$this->get_settings();

		$post_vars = array( 'access_key_id', 'secret_access_key' );
		foreach ( $post_vars as $var ) {
			if ( ! isset( $_POST[ $var ] ) ) { // input var okay
				continue;
			}

			$value = sanitize_text_field( $_POST[ $var ] ); // input var okay

			if ( 'secret_access_key' == $var && '-- not shown --' == $value ) {
				continue;
			}

			$this->set_setting( $var, $value );
		}

		$this->save_settings();
	}

	/**
	 * Adds a class to admin page to style thickbox the same as the plugin directory pages.
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	function admin_plugin_body_class( $classes ) {
		$classes .= 'plugin-install-php';

		return $classes;
	}

	/**
	 * Render the output of a page
	 */
	function render_page() {
		$view       = 'settings';
		$page_title = $this->plugin_title;

		if ( empty( $_GET['page'] ) ) { // input var okay
			// Not sure why we'd ever end up here, but just in case
			wp_die( 'What the heck are we doin here?' );
		}

		$this->render_view( 'header', array( 'page' => $view, 'page_title' => $page_title ) );
		$this->render_view( $view );
		$this->render_view( 'footer' );
	}

	/**
	 * Check if we are using constants for the AWS access credentials
	 *
	 * @return bool
	 */
	function are_key_constants_set() {
		return defined( 'AWS_ACCESS_KEY_ID' ) || defined( 'AWS_SECRET_ACCESS_KEY' );
	}

	/**
	 * Check if we are using the prefixed constants for the AWS access credentials
	 *
	 * @return bool
	 */
	function are_prefixed_key_constants_set() {
		return defined( 'DBI_AWS_ACCESS_KEY_ID' ) || defined( 'DBI_AWS_SECRET_ACCESS_KEY' );
	}

	/**
	 * Check if we are using environmental variables for the AWS access credentials
	 *
	 * @return bool
	 */
	function are_env_key_constants_set() {
		return getenv( 'AWS_ACCESS_KEY_ID' ) || getenv( 'AWS_SECRET_ACCESS_KEY' );
	}

	/**
	 * Whether or not IAM access keys are needed.
	 *
	 * Keys are needed if we are not using EC2 roles or not defined/set yet.
	 *
	 * @return bool
	 */
	public function needs_access_keys() {
		if ( $this->use_ec2_iam_roles() ) {
			return false;
		}

		return ! $this->are_access_keys_set();
	}

	/**
	 * Check if access keys are defined either by constants or database
	 *
	 * @return bool
	 */
	function are_access_keys_set() {
		return $this->get_access_key_id() && $this->get_secret_access_key();
	}

	/**
	 * Get the AWS key from a constant or the settings
	 *
	 * Falls back to settings only if neither constant is defined.
	 *
	 * @return string
	 */
	function get_access_key_id() {
		if ( $this->are_prefixed_key_constants_set() || $this->are_key_constants_set() || $this->are_env_key_constants_set() ) {
			$aws_access_key_id = getenv( 'AWS_ACCESS_KEY_ID' );
			if ( defined( 'AWS_ACCESS_KEY_ID' ) ) {
				$aws_access_key_id = AWS_ACCESS_KEY_ID;
			} else if( defined( 'DBI_AWS_ACCESS_KEY_ID' ) ) {
				$aws_access_key_id = DBI_AWS_ACCESS_KEY_ID; // Deprecated
			}
			if( !getenv( 'AWS_ACCESS_KEY_ID' ) ) putenv( 'AWS_ACCESS_KEY_ID=' . $aws_access_key_id );
			return $aws_access_key_id;
		} else {
			return $this->get_setting( 'access_key_id' );
		}

		return '';
	}

	/**
	 * Get the AWS secret from a constant or the settings
	 *
	 * Falls back to settings only if neither constant is defined.
	 *
	 * @return string
	 */
	function get_secret_access_key() {
		if ( $this->are_prefixed_key_constants_set() || $this->are_key_constants_set() || $this->are_env_key_constants_set() ) {
			$aws_secret_access_key = getenv( 'AWS_SECRET_ACCESS_KEY' );
			if ( defined( 'AWS_SECRET_ACCESS_KEY' ) ) {
				$aws_secret_access_key = AWS_SECRET_ACCESS_KEY;
			} elseif ( defined( 'DBI_AWS_SECRET_ACCESS_KEY' ) ) {
				$aws_secret_access_key = DBI_AWS_SECRET_ACCESS_KEY; // Deprecated
			}
			if( !getenv( 'AWS_SECRET_ACCESS_KEY' ) ) putenv( 'AWS_SECRET_ACCESS_KEY=' . $aws_secret_access_key );
			return $aws_secret_access_key;
		} else {
			return $this->get_setting( 'secret_access_key' );
		}

		return '';
	}

	/**
	 * Allows the AWS client factory to use the IAM role for EC2 instances
	 * instead of key/secret for credentials
	 * http://docs.aws.amazon.com/aws-sdk-php/guide/latest/credentials.html#instance-profile-credentials
	 *
	 * @return bool
	 */
	function use_ec2_iam_roles() {
		if ( defined( 'AWS_USE_EC2_IAM_ROLE' ) && AWS_USE_EC2_IAM_ROLE ) {
			return true;
		}

		return false;
	}

	/**
	 * Instantiate a new AWS service client for the AWS SDK
	 * using the defined AWS key and secret
	 *
	 * @return Aws
	 * @throws Exception
	 */
	function get_client() {
		if ( $this->needs_access_keys() ) {
			throw new Exception( sprintf( __( 'You must first <a href="%s">set your AWS access keys</a> to use this addon.', 'amazon-web-services' ), 'admin.php?page=' . $this->plugin_slug ) );
		}

		if ( is_null( $this->client ) ) {
			$args = array();

			if ( ! $this->use_ec2_iam_roles() ) {
				$args = array(
					'key'    => $this->get_access_key_id(),
					'secret' => $this->get_secret_access_key(),
				);
			}

			// Use proxy if defined by WordPress
			if ( defined( 'WP_PROXY_HOST' ) && defined( 'WP_PROXY_PORT' ) ) {
				$args['request.options'] = [
					'proxy' => WP_PROXY_HOST . ':' . WP_PROXY_PORT
				];
			}

			$args         = apply_filters( 'aws_get_client_args', $args );
			$this->client = Aws::factory( $args );
		}

		return $this->client;
	}


	/**
	 * Get a nonced, network safe install URL for a plugin
	 *
	 * @param string $slug Plugin slug
	 *
	 * @return string
	 */
	function get_plugin_install_url( $slug ) {
		return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
	}

	/**
	 * Get a nonced, network safe activation URL for a plugin
	 *
	 * @param string $slug Plugin slug
	 *
	 * @return string
	 */
	function get_plugin_activate_url( $slug ) {
		$plugin_path = $this->get_plugin_path( $slug );

		return wp_nonce_url( self_admin_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_path ), 'activate-plugin_' . $plugin_path );
	}

	/**
	 * Customize the link text on the plugins page
	 *
	 * @return string
	 */
	function get_plugin_action_settings_text() {
		return __( 'Access Keys', 'amazon-web-services' );
	}

	/**
	 * Check if plugin is activated
	 *
	 * @param string $slug
	 *
	 * @return bool
	 */
	function is_plugin_activated( $slug ) {
		$path = $this->get_plugin_path( $slug );

		return is_plugin_active( $path );
	}

	/**
	 * Get plugin path relative to plugins directory
	 *
	 * @param string $slug
	 *
	 * @return string
	 */
	function get_plugin_path( $slug ) {
		$path = $slug . '/' . $slug . '.php';

		// Workaround for dodgy AS3CF naming convention
		if ( 'amazon-s3-and-cloudfront' === $slug ) {
			$path = $slug . '/wordpress-s3.php';
		}

		return $path;
	}
}
