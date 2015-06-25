<?php
class AEH_Presentations{
	const VERSION = '1.0.0';
	protected $plugin_slug = 'aeh_presentations';
	protected static $instance = null;
	public function __construct(){
		//filters


		//actions
		add_action( 'init', array($this, 'cpt_tax') );
		add_action( 'wp_ajax_getpresentations', array($this, 'getpresentations') );
		add_action( 'wp_ajax_nopriv_getpresentations', array($this, 'getpresentations') );
		add_action( 'wp_ajax_getpresentationsbyevent', array($this, 'getpresentationsbyevent') );
		add_action( 'wp_ajax_nopriv_getpresentationsbyevent', array($this, 'getpresentationsbyevent') );
		add_action( 'wp_ajax_searchpresentations', array($this, 'searchpresentations') );
		add_action( 'wp_ajax_nopriv_searchpresentations', array($this, 'searchpresentations') );
 



	}
	public function get_plugin_slug(){
		return $this->plugin_slug;
	}
	public static function get_instance(){
		if(null == self::$instance){
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	*
	* Post Types and Taxonomies (init)
	*
	*/
	public function cpt_tax(){
		$labels = array(
			'name'               => _x( 'Presentations', 'post type general name', $plugin_slug ),
			'singular_name'      => _x( 'Presentation', 'post type singular name', $plugin_slug ),
			'menu_name'          => _x( 'Presentations', 'admin menu', $plugin_slug ),
			'name_admin_bar'     => _x( 'Presentation', 'add new on admin bar', $plugin_slug ),
			'add_new'            => _x( 'Add New', 'presentation', $plugin_slug ),
			'add_new_item'       => __( 'Add New Presentation', $plugin_slug ),
			'new_item'           => __( 'New Presentation', $plugin_slug ),
			'edit_item'          => __( 'Edit Presentation', $plugin_slug ),
			'view_item'          => __( 'View Presentation', $plugin_slug ),
			'all_items'          => __( 'All Presentations', $plugin_slug ),
			'search_items'       => __( 'Search Presentations', $plugin_slug ),
			'parent_item_colon'  => __( 'Parent Presentations:', $plugin_slug ),
			'not_found'          => __( 'No presentations found.', $plugin_slug ),
			'not_found_in_trash' => __( 'No presentations found in Trash.', $plugin_slug )
		);
		$args = array(
			'labels'             => $labels,
			'menu_icon'			 => 'dashicons-megaphone',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'presentations' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'taxonomies'          => array('post_tag' ),
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); register_post_type( 'presentation', $args );
	}


	/**
	*
	* Ajax Request
	*
	*/
	public function getpresentations(){
		$section = $_POST['section'];
 		echo $section;
		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
			'meta_query' => array(
				'meta_key'     => 'section',
				'meta_value'   => $section
			),
			'orderby'   => 'meta_value',
			'meta_key'  => 'date',

 			
		);
		if($section == '*')
		{
			$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1
			);
		}
		$posts = get_posts($args);
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_presentation($post->ID);
				$i++;
			}
		}else{
			$output .= '<h3>No presentations found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

	}


	/**
	*
	* Ajax Request
	*
	*/
	public function getpresentationsbyevent(){
		$eventid = $_POST['eventid'];
		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
 			'meta_key'     => 'event',
			'meta_value'   => $eventid
		);
		if($eventid == '*')
		{
			$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1
			);
		}
		$posts = get_posts($args);
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_presentation($post->ID);
				$i++;
			}
		}else{
			$output .= '<h3>No presentations found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

	}

	public function getpresentationsbymonth(){
		$month = $_POST['month'];
		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
 			'meta_key'     => 'month',
			'meta_value'   => $month
		);
		if($month == '*')
		{
			$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1
			);
		}
 
		$posts = get_posts($args);
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_presentation($post->ID);
				$i++;
			}
		}else{
			$output .= '<h3>No presentations found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

	}

	public static function get_presentations(){
 
		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
			'orderby'   => 'meta_value',
			'meta_key'  => 'date'
 			
		);
		$posts = get_posts($args);
		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_presentation($post->ID);
				$i++;
			}
		}else{
			$output .= '<h3>No presentations found</h3>';
		}
		$output .= '</div>' ;
		// Gimme the output!
		echo $output;
	}


	public function searchpresentations(){
		$search = $_POST['search'];

		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
			's' => $search
		);
		$search_title_posts = get_posts($args);

		$args = array(
			'post_type' => 'presentation',
			'posts_per_page' => -1,
			'meta_key' => 'search_terms',
			'meta_value' => $search,
			'meta_compare' => 'LIKE'
		);
		$search_term_posts = get_posts($args);

		$posts = array_merge($search_term_posts, $search_title_posts);
		$posts = array_unique($posts);



		// Render Markup
		$output = '<div class="item">';
		$i = 0;
		if(count($posts > 0)){
			foreach($posts as $post){
				if($i%6 == 0 && $i != 0){
					$output .= '</div><div class="item">';
				}
				$output .= self::render_presentation($post->ID);
				$i++;
			}
		}else{
			$output .= '<h3>No presentations found</h3>';
		}
		$output .= '</div>';
		// Gimme the output!
		echo $output;

		die();
	}


	private static function render_presentation($presentation){
		$post = get_post($presentation);
		$title = $post->post_title;
		$link = get_post_meta($presentation,'file',true);
		$intro = get_post_meta($presentation,'description',true);
		$speaker = get_post_meta($presentation,'speaker',true);
		$event = get_post_meta($presentation,'event',true);

		$section = get_post_meta($event,'section',true);
		$date = get_post_meta($event,'date',true);
		$audience = get_post_meta($event,'audience',true);
		$pres_date = get_post_meta($presentation,'date',true);
		$date = date( 'F j, Y', $date );
		$pres_date = date( 'F j, Y | h:i a T', $pres_date );

		$member_access = false;

		if(is_user_logged_in()){
			 
			$uid = get_current_user_id(); 
			$member = get_user_meta($uid,'aeh_member_type',true);
			if($member == 'hospital'){
				$member_access = true;
			}
			else{
				$member_access = false;
			}
			

		}



		if($audience != NULL || $audience != ''){
			$audience = get_term_by('ID', $audience, 'audience')->name;
		}
		if($audience == 'Members Only'){
			$lockicon = "<span style='vertical-align:middle;border:none;' class='lock-icon'>
										<img src='".get_template_directory_uri()."/images/lockwebinar.png'>
									</span>";
		}else{
			$lockicon = '';
		}



		
		if($section == 'action'){
			$theme = 'redd';
		}elseif($section == 'quality'){
			$theme = 'greenn';
		}elseif($section == 'institute'){
			$theme = 'bluee';
		}else{
			$theme = 'grayy';
		}
		if($section == ''){
			$section = 'education';
		}
		$event_title = get_the_title( $event ); 

 




		$output = '<div class="post long columns '.$theme.' '.$post->post_type.' wide">
									<div class="graybarright"></div>
									<div class="item-bar">
									<div class="item-icon" style="padding-top: 15px;">
										<img src="http://mlinson.staging.wpengine.com/wp-content/themes/EssentialHospitals/images/icon-'.$section.'.png">
									</div>
									<div class="item-content">
										<div class="item-header">
											';
											if($member_access == false && $lockicon != ''){
												$output.='<h2>'.$lockicon.' '.$title .'</h2>';
											}
											else{
												$output.='<h2><a target="_blank" href="'.wp_get_attachment_url($link).'">'.$lockicon.' '.$title .'</a></h2>';
												
											}
											
											

								$output .=	'<span class="item-date">'.$pres_date.'</span><br>
											<span class=" "><a href="'. get_permalink($event) .'">'.$event_title.'</a></span>
										</div>
										'.$intro.'
									</div>
									<div class="item-tags">';
				    				$tags = get_the_terms($presentation,'post_tag');
					    					if($tags){
					    						$cnt = 0;
					    						$tag_output = '';
					    						foreach($tags as $tag)
					    						{
						    						$tagLink = get_term_link($tag->term_id,'post_tag');
						    						$tagSlug = $tag->slug;
						    						$tagSlug = str_replace('-',' ', $tagSlug);
						    						 

							    					$tag_output .= "<a href='".$tagLink."'>".$tagSlug."</a>, ";
							    					$cnt++;
							    				}
						    				} 



						    				$tag_output = rtrim($tag_output,', ');
 
						    				$output .= $tag_output;

				    			$output .= '</div>
									<div class="bot-border"></div>
								</div>
							</div>';
		return $output;
	}


	public static function related_presentations(){
		$id = get_the_ID();
		$args = array(
			'post_type' 		 => 'presentation',
			'meta_key'  		 => 'event',
			'meta_value'		 => $id,
			'posts_per_page' => -1
		);
		$posts = get_posts($args);
		if(count($posts) > 0){
			$output = '<div class="panel description">
									<h2 class="heading">Related Presentations</h2>
									<div class="gutter"><ul>';
			foreach($posts as $post){
				$link = get_post_meta($post->ID,'file',true);
					$output .= '<li><a target="_blank" href="'.wp_get_attachment_url($link).'">'.$post->post_title.'</a></li>';
			}
			$output .= '</ul></div>
								</div>';
		}else{
			$output = '';
		}
		echo $output;
	}

 



}
global $aeh_presentations;
$aeh_presentations = new AEH_Presentations;
