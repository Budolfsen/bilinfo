<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://podi.dk
 * @since      1.0.0
 *
 * @package    Bilinfo
 * @subpackage Bilinfo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bilinfo
 * @subpackage Bilinfo/admin
 * @author     Podi <info@podi.dk>
 */
class bilinfo_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $bilinfo    The ID of this plugin.
	 */
	private $bilinfo;

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
	 * @param      string    $bilinfo       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($bilinfo, $version)
	{

		$this->bilinfo = $bilinfo;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in bilinfo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The bilinfo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->bilinfo, plugin_dir_url(__FILE__) . 'css/bilinfo-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in bilinfo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The bilinfo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->bilinfo, plugin_dir_url(__FILE__) . 'js/bilinfo-admin.js', array('jquery'), $this->version, false);
	}


	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */

	public function add_plugin_admin_menu()
	{

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 *
		 */
		add_options_page(
			'Bilinfo Settings',
			'Bilinfo Settings',
			'manage_options',
			$this->bilinfo,
			array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */

	public function add_action_links($links)
	{
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
		$settings_link = array(
			'<a href="' . admin_url('options-general.php?page=' . $this->bilinfo) . '">' . __('Settings', $this->bilinfo) . '</a>',
		);
		return array_merge($settings_link, $links);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */

	public function display_plugin_setup_page()
	{
		include_once('partials/bilinfo-admin-display.php');
	}

	public function options_update()
	{
		register_setting($this->bilinfo, $this->bilinfo, array($this, 'validate'));
	}

	public function validate($input)
	{
		// All checkboxes inputs
		$valid = array();
		$valid['base-url'] = (isset($input['base-url']) && !empty($input['base-url'])) ? sanitize_text_field($input['base-url']) : '';
		$valid['username'] = (isset($input['username']) && !empty($input['username'])) ? sanitize_text_field($input['username']) : '';
		$valid['password'] = (isset($input['password']) && !empty($input['password'])) ? sanitize_text_field($input['password']) : '';

		return $valid;
	}

	public function child_plugin_has_parent_plugin()
	{
		if (is_admin() && current_user_can('activate_plugins') &&  !is_plugin_active('advanced-custom-fields-pro/acf.php')) {
			add_action('admin_notices', array($this, 'child_plugin_notice'));
		}
	}

	public function child_plugin_notice()
	{
?><div class="error">
			<p><?php _e('Sorry, but Podi Bilinfo Integration requires the Advanced Custom Fields PRO to be installed and active.', 'pbweb-flexya'); ?></p>
		</div><?php
				}
			}
