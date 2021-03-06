<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://podi.dk
 * @since      1.0.0
 *
 * @package    bilinfo
 * @subpackage bilinfo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    bilinfo
 * @subpackage bilinfo/public
 * @author     Your Name <email@example.com>
 */
class bilinfo_Public
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
	 * @param      string    $bilinfo       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($bilinfo, $version)
	{

		$this->bilinfo = $bilinfo;
		$this->version = $version;
	}

	public function init()
	{

		if (isset($_GET['bilinfo_update']) && $_GET['key'] == 'sd5d2rf16') {
			$force = (isset($_GET['force'])) ?: false;
			$debug = (isset($_GET['debug'])) ?: false;
			$import = new Bilinfo_Import();
			$import->import_cases($force, $debug);
			die();
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->bilinfo, plugin_dir_url(__FILE__) . 'css/bilinfo-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->bilinfo, plugin_dir_url(__FILE__) . 'js/bilinfo-public.js', array('jquery'), $this->version, false);
	}
}
