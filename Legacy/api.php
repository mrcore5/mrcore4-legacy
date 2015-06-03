<?php namespace API;

use Auth;
use Config;
use Layout;
use Mrcore;
use Request;
use Mrcore\Modules\Wiki\Models\User;

/**
 * mRcore API v1 for mRcore 4.0 backwards compatibility
 *
 * @author     Matthew Reschke <mail@mreschke.com>
 * @copyright  2013 Matthew Reschke
 * @link       http://mreschke.com
 * @license    http://mreschke.com/topic/317/MITLicense
 * @version    1.0
 * @package    API\v1
 * @since      2013-11-24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
if ( ! function_exists('API\snippet')) {

	/* Old legacy usage is
	eval(API\snippet('mssql'));
	eval(API\snippet('form'));
	$sql = new helper_mssql;
	$form = new helper_form;
	...*/


	function snippet($name) {
		#Usage: eval(API::snippet('iam'));
		$file = base_path()."/vendor/mreschke/mrcore4-legacy/Legacy/$name.php";
		if (!file_exists($file)) {
			$file = base_path()."/../Modules/Mreschke/Mrcore4Legacy/Legacy/$name.php";
		}
		return "require_once '$file';";
	}

	function load($name) {
		#Usage: eval(API::file('34/.sys/index.php'));
		return "require_once '".Config::get('mrcore.wiki.files')."/index/$name';";
	}

	class v1 {

		public $config;
		public $user;
		public $view;
		public $page;
		public $topic;

		function __construct() {
			$this->user = new v1_User;
			$this->config = new v1_Config;
			$this->view = new v1_View;
			$this->page = new v1_Page;
			$this->topic = new v1_Topic;
		}

	}


	class v1_User {

		public function __construct() {
			$this->id = Auth::user()->id;
			$this->email = Auth::user()->email;
			$this->first = Auth::user()->first;
			$this->last = Auth::user()->last;
			$this->alias = Auth::user()->alias;
			$this->is_admin = Auth::admin();
			$this->is_authenticated = Auth::check();
			$this->perm_create = User::hasPermission('create');
			$this->perm_exec = User::hasPermission('write_script');
			$this->perm_html = User::hasPermission('write_html');
			$this->global_topic = Auth::user()->global_post_id;
			$this->user_topic = Auth::user()->home_post_id;
		}

	}


	class v1_Config {

		public function __construct() {

			// Get hostname, so if server_name is
			// mrcore5.lindev.mreschke.com, return just mreschke.net
			$root = $_SERVER['SERVER_NAME'];
			$tmp = explode(".", $root);
			$host = $tmp[count($tmp) -2].'.'.$tmp[count($tmp) -1];

			$this->files_dir = Config::get('mrcore.wiki.files');
			$this->web_base_url = Config::get('app.url');
			$this->web_host = $host;
			$this->abs_base = base_path();
			$this->help_topic = Config::get('mrcore.wiki.help');
			$this->global_topic = Config::get('mrcore.wiki.global');
			$this->userinfo_topic = Config::get('mrcore.wiki.userinfo');
			$this->searchbox_topic = Config::get('mrcore.wiki.searchbox');

			#Unusable to mrcore5 so just set any value
			$this->recent_max_title_len = 0;
			$this->web_base = '/'; #don't offer optional base path anymore

		}

	}


	class v1_View {

		function appmode($value = null) {
			return Layout::mode($value);
		}

		function title($value = null) {
			return Layout::title($value);
		}

		function menu($value) {
			/*GLOBAL $view;
			if (isset($value)) {
				$view->menu = $value;
			} else {
				return $view->menu;
			}
			*/
		}

		function form_attributes($value) {
			/*GLOBAL $view;
			if (isset($value)) {
				$view->form_attributes = $value;
			} else {
				return $view->form_attributes;
			}*/
		}

		function js($value = null, $append = true) {
			return Layout::js($value, $append);
		}

		function remove_js($value) {
			return Layout::removeJs($value);
		}

		function css($value = null, $append = true) {
			return Layout::css($value, $prepend);
		}

		function css_print($value, $append = true) {
			return Layout::printCss($value, $prepend);
		}

		function remove_css($value) {
			return Layout::removeCss($value);
		}


	}


	class v1_Page {

		/*function redirect($page, $vars='') {
			return \Page::redirect($page, $vars);
		}*/

		function redirect_404() {
			#GLOBAL $view;
			#return $view->showNotFound();
		}

		/*function get_page() {
			return \Page::get_page();
		}*/

		/*function get_variables($as_string=false, $uri=null) {
			return \Page::get_variables($as_string, $uri);
		}*/

		/*function get_variable($pos, $uri=null) {
			return \Page::get_variable($pos, $uri);
		}*/

		function get_url($page='', $is_user_image=false) {
			#return \Page::get_url($page, $is_user_image);
			return Request::url();
		}

	}

	Class v1_Topic {

		public function __construct() {
			$post = Mrcore::post()->getModel();
			$this->id = $post->id;
			$this->post_id = $post->id;
			$this->post_uuid = $post->uuid;
			$this->title = $post->title;
		}

	}
}
