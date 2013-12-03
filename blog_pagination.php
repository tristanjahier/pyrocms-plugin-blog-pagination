<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author  Tristan Jahier
 * @package PyroCMS\Addons\Plugins
 * @copyright	Copyright (c) 2013, Tristan Jahier
 */
class Plugin_blog_pagination extends Plugin
{
	public $version = '1.0.0';
	public $name = array(
		'en' => 'Blog pagination',
		'fr' => 'Pagination du blog',
	);
	public $description = array(
		'en' => 'Retrieve pagination information of a blog post.',
		'fr' => 'Récupérer des informations sur la pagination d\'un article de blog.',
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'page_number' => array(
				'description' => array(
					'en' => 'Retrieves the number of the page where the blog post is. Starts at 1.',
					'fr' => 'Récupère le numéro de la page où se situe l\'article de blog. Commence à 1.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'post_id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => true,
					)
				),
			),
			'page_offset' => array(
				'description' => array(
					'en' => 'Retrieves the offset of the page where the blog post is. This offset equals the total number of posts in the previous pages.',
					'fr' => 'Récupère l\'offset de la page où se situe l\'article de blog. Cet offset correspond au nombre total d\'articles dans les pages précédentes.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'post_id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => true,
					)
				),
			),
			'page_uri' => array(
				'description' => array(
					'en' => 'Generates an URI that refers to the page where the blog post is, based on the page_offset method (ex: /page/20). Returns nothing if it is the first page.',
					'fr' => 'Génère une URI qui désigne la page où se situe l\'article de blog, basée sur la méthode page_offset (ex : /page/20). Ne renvoie rien si c\'est la première page.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'post_id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => true,
					)
				),
			),
			'post_index' => array(
				'description' => array(
					'en' => 'Retrieves the position of the blog post among all paginated posts.',
					'fr' => 'Récupère la position de l\'article parmi tous les articles paginés.'
				),
				'single' => true,
				'double' => false,
				'variables' => '',
				'attributes' => array(
					'post_id' => array(
						'type' => 'number',
						'flags' => '',
						'default' => '',
						'required' => true,
					)
				),
			)
		);

		return $info;
	}

	/**
	 * Retrieves the number of the page where the blog post is.
	 * 
	 * Usage:
	 * {{ blog_pagination:page_number post_id="6" }}
	 * 
	 * @param id : post id
	 * @return integer
	 */
	public function page_number($id)
	{
		$post_id = $this->attribute('post_id', $id);
		if(isset($post_id))
		{
			$records_per_page = Settings::get('records_per_page');
			if($pos = $this->post_index($post_id))
				return ceil($pos / $records_per_page);
			else
				return false;
		}
		return false;
	}

	/**
	 * Retrieves the offset of the page where the blog post is.
	 * This offset equals the total number of posts in the previous pages.
	 * 
	 * Usage:
	 * {{ blog_pagination:page_offset post_id="7" }}
	 * 
	 * @param id : post id
	 * @return integer
	 */
	public function page_offset($id)
	{
		$post_id = $this->attribute('post_id', $id);
		if(isset($post_id))
		{
			$records_per_page = Settings::get('records_per_page');
			if($pos = $this->post_index($post_id))
				return (ceil($pos / $records_per_page) - 1) * $records_per_page;
			else
				return false;
		}
		return false;
	}

	/**
	 * Generates an URI that refers to the page where the blog post is,
	 * based on the page_offset method (ex: /page/20).
	 * Returns nothing if it is the first page.
	 * 
	 * Usage:
	 * {{ blog_pagination:page_uri post_id="8" }}
	 * 
	 * @param id : post id
	 * @return string
	 */
	public function page_uri($id)
	{
		$post_id = $this->attribute('post_id', $id);
		if(isset($post_id))
		{
			$index = $this->page_offset($post_id);
			if($index > 0)
					return "/page/$index";
			else
				return false;
		}
		return false;
	}

	/**
	 * Retrieves the position of the blog post among all paginated posts.
	 * 
	 * Usage:
	 * {{ blog_pagination:post_index post_id="9" }}
	 * 
	 * @param id : post id
	 * @return integer
	 */
	public function post_index($id)
	{
		$post_id = $this->attribute('post_id', $id);
		if(isset($post_id))
		{
			$posts_count = $this->db->where('status', 'live')->count_all_results('blog');

			// Get the latest blog posts
			$this->load->driver('Streams');
			$params = array(
				'stream'		=> 'blog',
				'namespace'	=> 'blogs',
				'where'			=> "`status` = 'live'",
				'order'			=> "desc"
			);
			$posts = $this->streams->entries->get_entries($params);

			$pos = 1;
			foreach($posts['entries'] as $post)
			{
				if($post['id'] === $post_id)
					return $pos;
				$pos++;
			}
			return false;
		}
		return false;
	}
	
}
