<?php
/**
 * Mreschke Mrcore4 to Mrcore5 Upgrade Artisan Script
 *
 * @author     Matthew Reschke <mail@mreschke.com>
 * @copyright  2014 Matthew Reschke
 * @link       http://mreschke.com
 * @license    http://mreschke.com/topic/317/MITLicense
 * @package    Mreschke\Mrcore
 * @since      2014-02-20
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mreschke\Mrcore4Legacy;

use \Illuminate\Console\Command;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Input\InputArgument;

class Mrcore4Upgrade extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'mrcore:mrcore4upgrade';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Translate a mrcore4 database to a mrcore5 database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		/*
		|--------------------------------------------------------------------------
		| Configure the upgrade application
		|--------------------------------------------------------------------------
		*/

		// mrcore4 database connection
		$m4db = \DB::connection('mrcore4');

		// path to mrcore4 images (location of badges, tags, avatars)
		$m4images = '/var/www/mrcore4/src/web/image';

		/*
		|--------------------------------------------------------------------------
		| End configuration - do not edit below this line
		|--------------------------------------------------------------------------
		*/

		if (!$this->confirm('This will WIPE ALL MRCORE5 Data from your database, continue? [yes|no]', false)) exit();

		// Internal variables
		$images = base_path().'/public/uploads';

		// Drop from all mrcore5 tables
		$this->comment('Deleting all existing mrcore5 database data');
		$this->call('migrate:refresh');

		// Seed a few tables
		$this->comment('Seeding a few mrcore5 tables');
		$this->call('db:seed', array('--class' => 'PostItemsSeeder'));
		$this->call('db:seed', array('--class' => 'PermissionSeeder'));


		// Users
		$this->comment('Translating Users');
		$m4users = $m4db->table('tbl_user')->get();
		foreach ($m4users as $m4user) {
			$this->info('  Translating user '.$m4user->email);
			$user = new \User;
			$user->id = $m4user->user_id;
			$user->uuid = \Mreschke\Helpers\Str::getGuid();
			$user->email = $m4user->email;
			$user->password = \Hash::make($m4user->password);
			$user->first = $m4user->first_name;
			$user->last = $m4user->last_name;
			$user->alias = $m4user->alias;
			$user->avatar = $m4user->avatar;
			$user->login_at = $m4user->last_login_on;
			$user->global_post_id = $m4user->global_topic_id;
			$user->home_post_id = $m4user->user_topic_id;
			$user->disabled = $m4user->disabled;
			$user->created_by = $m4user->created_by;
			$user->updated_by = $m4user->created_by; #mrcore4 had no updated_by
			$user->created_at = $m4user->created_on;
			$user->updated_at = $m4user->updated_on;
			$user->save();

			// User Permissions
			if ($m4user->perm_create) {
				$userPermission = new \UserPermission;
				$userPermission->user_id = $m4user->user_id;
				$userPermission->permission_id = 1;
				$userPermission->save();
			}
			if ($m4user->perm_admin) {
				$userPermission = new \UserPermission;
				$userPermission->user_id = $m4user->user_id;
				$userPermission->permission_id = 4;
				$userPermission->save();
			} else {
				// All users but admins can comment (admins can already comment)
				$userPermission = new \UserPermission;
				$userPermission->user_id = $m4user->user_id;
				$userPermission->permission_id = 3; #comment
				$userPermission->save();
			}
			if ($m4user->perm_exec) {
				$userPermission = new \UserPermission;
				$userPermission->user_id = $m4user->user_id;
				$userPermission->permission_id = 5;
				$userPermission->save();
			}
			if ($m4user->perm_html) {
				$userPermission = new \UserPermission;
				$userPermission->user_id = $m4user->user_id;
				$userPermission->permission_id = 6;
				$userPermission->save();
			}
		}
		exec("rm -rf $images/avatar*");
		exec("cp -rf $m4images/avatar* $images/");


		// Roles and UserRoles
		$this->comment('Translating Roles');
		$m4groups = $m4db->table('tbl_perm_group_item')->get();
		foreach ($m4groups as $m4group) {
			$this->info('  Translating role '.$m4group->group);
			$role = new \Role;
			$role->id = $m4group->perm_group_id;
			$role->name = $m4group->group;
			$role->constant = strtolower($m4group->group);
			$role->save();
		}
		$this->comment('Translating User Role Linkage');
		$m4groupLinks = $m4db->table('tbl_perm_group_link')->get();
		foreach ($m4groupLinks as $m4groupLink) {
			$userRole = new \UserRole;
			$userRole->user_id = $m4groupLink->user_id;
			$userRole->role_id = $m4groupLink->perm_group_id;
			$userRole->save();
		}


		// Posts
		$this->comment('Translating Posts');
		$m4topics = $m4db->table('tbl_topic')->get();
		foreach ($m4topics as $m4topic) {
			$m4post = $m4db->table('tbl_post')->where('topic_id', $m4topic->topic_id)->where('is_comment', false)->first();
			$m4stat = $m4db->table('tbl_topic_stat')->where('topic_id', $m4topic->topic_id)->first();

			$this->info(' Translating post '.$m4post->topic_id.' - '.$m4post->title);

			$post = new \Post;
			$post->id = $m4post->topic_id;
			$post->uuid = \Mreschke\Helpers\Str::uuidToGuid($m4post->post_uuid);
			$post->title = $m4post->title;
			$post->slug = \Mreschke\Helpers\Str::slugify($m4post->title);
			$post->content = \Mrcore\Crypt::encrypt($m4post->body);
			$post->teaser = \Mrcore\Crypt::encrypt($m4topic->teaser);
			$post->contains_script = $m4post->has_exec;
			$post->contains_html = $m4post->has_html;
			$post->format_id = 1;
			$post->type_id = 1;
			$post->framework_id = null;
			$post->mode_id = 1;
			$post->symlink = false;
			$post->shared = $m4post->uuid_enabled;
			$post->hidden = $m4topic->hidden;
			$post->deleted = $m4post->deleted;
			$post->password = null;
			if (isset($m4stat)) {
				$post->clicks = $m4stat->view_count;
			} else {
				$post->clicks = 0;
			}
			$post->indexed_at = '1900-01-01 00:00:00';
			$post->created_by = $m4post->created_by;
			$post->updated_by = $m4post->updated_by;
			$post->created_at = $m4post->created_on;
			$post->updated_at = $m4post->updated_on;
			$post->save();

			// Post Revision
			$revision = new \Revision;
			$revision->post_id = $post->id;
			$revision->revision = 1;
			$revision->title = $post->title;
			$revision->content = $post->content;
			$revision->comment = "Initial commit from mrcore4 upgrade";
			$revision->created_by = $post->created_by;
			$revision->created_at = $post->updated_at;
			$revision->save();

			// Post Router
			$router = new \Router;
			$router->slug = $post->slug;
			$router->post_id = $post->id;
			$router->save();
		}


		// Post Permissions
		$this->comment('Translating Post Permissions');
		$m4permLinks = $m4db->table('tbl_perm_link')->get();
		foreach ($m4permLinks as $m4permLink) {
			$postPermission = new \PostPermission;
			$postPermission->post_id = $m4permLink->topic_id;
			// mrcore4 perms are 1,2,3 (read,write,comment) mrcore5 are 7,8,9 (so +6)
			$postPermission->permission_id = ($m4permLink->perm_id + 6);
			$postPermission->role_id = $m4permLink->perm_group_id;
			$postPermission->save();
		}


		// Badges and PostBadges
		$this->comment('Translating Badges');
		$m4badges = $m4db->table('tbl_badge_item')->get();
		foreach ($m4badges as $m4badge) {
			$this->info('  Translating badge '.$m4badge->badge);
			$badge = new \Badge;
			$badge->id = $m4badge->badge_id;
			$badge->name = $m4badge->badge;
			$badge->image = $m4badge->image;
			$badge->post_id = $m4badge->default_topic_id;
			$badge->save();
		}
		$this->comment('Translating Post Badge Linkage');
		$m4badgeLinks = $m4db->table('tbl_badge_link')->get();
		foreach ($m4badgeLinks as $m4badgeLink) {
			$postBadge = new \PostBadge;
			$postBadge->post_id = $m4badgeLink->topic_id;
			$postBadge->badge_id = $m4badgeLink->badge_id;
			$postBadge->save();
		}
		exec("rm -rf $images/badge*");
		exec("cp -rf $m4images/badge* $images/");


		// Tags and PostTags
		$this->comment('Translating Tags');
		$m4tags = $m4db->table('tbl_tag_item')->get();
		foreach ($m4tags as $m4tag) {
			$this->info('  Translating tag '.$m4tag->tag);
			$tag = new \Tag;
			$tag->id = $m4tag->tag_id;
			$tag->name = $m4tag->tag;
			$tag->image = $m4tag->image;
			$tag->post_id = $m4tag->default_topic_id;
			$tag->save();
		}
		$this->comment('Translating Post Tag Linkage');
		$m4tagLinks = $m4db->table('tbl_tag_link')->get();
		foreach ($m4tagLinks as $m4tagLink) {
			$postTag = new \PostTag;
			$postTag->post_id = $m4tagLink->topic_id;
			$postTag->tag_id = $m4tagLink->tag_id;
			$postTag->save();
		}
		exec("rm -rf $images/tag*");
		exec("cp -rf $m4images/tag* $images/");

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			#array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			#array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
