<?php
/*---------------------------CUSTOM POST TYPES----------------------------- **/

$themeDIR = get_bloginfo('template_directory');
  //Stories CPT
  function register_stories_posttype() {
    $labels = array(
      'name'        => _x( 'Stories', 'post type general name' ),
      'singular_name'   => _x( 'Story', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Story' ),
      'edit_item'     => __( 'Edit Story' ),
      'new_item'      => __( 'New Story' ),
      'view_item'     => __( 'View Story' ),
      'search_items'    => __( 'Search Stories' ),
      'not_found'     => __( 'No Stories Found' ),
      'not_found_in_trash'=> __( 'No Stories in Trash' ),
      'parent_item_colon' => __( 'Story' ),
      'menu_name'     => __( 'Stories' )
    );

    $taxonomies = array();

    $supports = array('title');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Story'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'stories', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('story',$post_type_args);
  }
  add_action('init', 'register_stories_posttype');


  // registration code for general post type
  function register_general_posttype() {
    $labels = array(
      'name'        => _x( 'General', 'post type general name' ),
      'singular_name'   => _x( 'General', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new General' ),
      'edit_item'     => __( 'Edit General' ),
      'new_item'      => __( 'New General' ),
      'view_item'     => __( 'View General article' ),
      'search_items'    => __( 'Search General' ),
      'not_found'     => __( 'No General articles found' ),
      'not_found_in_trash'=> __( 'No General articles found' ),
      'parent_item_colon' => __( 'General' ),
      'menu_name'     => __( 'General' )
    );

    $taxonomies = array('series','category','post_tag');

    $supports = array('title','editor','thumbnail','excerpt','comments','revisions','author');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('General'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'general', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('general',$post_type_args);
  }
  add_action('init', 'register_general_posttype');


  // registration code for webinars post type
  function register_webinar_posttype() {
    $labels = array(
      'name'        => _x( 'Webinars', 'post type general name' ),
      'singular_name'   => _x( 'Webinar', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Webinar' ),
      'edit_item'     => __( 'Edit Webinar' ),
      'new_item'      => __( 'New Webinar' ),
      'view_item'     => __( 'View Webinar' ),
      'search_items'    => __( 'Search Webinars' ),
      'not_found'     => __( 'No Webinars found' ),
      'not_found_in_trash'=> __( 'No Webinars found' ),
      'parent_item_colon' => __( 'Webinar' ),
      'menu_name'     => __( 'Webinars' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics','webinartopics');

    $supports = array('title','editor','thumbnail','excerpt','comments','revisions','author');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Webinar'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'webinar', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'menu_icon'     => get_bloginfo('template_directory').'/images/education-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('webinar',$post_type_args);
  }
  add_action('init', 'register_webinar_posttype');

  // registration code for alerts post type
  function register_alerts_posttype() {
    $labels = array(
      'name'        => _x( 'Alerts', 'post type general name' ),
      'singular_name'   => _x( 'Alert', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Alert' ),
      'edit_item'     => __( 'Edit Alert' ),
      'new_item'      => __( 'New Alert' ),
      'view_item'     => __( 'View Alert' ),
      'search_items'    => __( 'Search Alerts' ),
      'not_found'     => __( 'No Alerts found' ),
      'not_found_in_trash'=> __( 'No Alerts found' ),
      'parent_item_colon' => __( 'No Alerts found' ),
      'menu_name'     => __( 'Announcements' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics','category');

    $supports = array('title','excerpt');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Alert'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'alert', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('alert',$post_type_args);
  }
  add_action('init', 'register_alerts_posttype');

  // registration code for policy post type
  function register_policy_posttype() {
    $labels = array(
      'name'        => _x( 'Action', 'post type general name' ),
      'singular_name'   => _x( 'Action', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Action article' ),
      'edit_item'     => __( 'Edit Action' ),
      'new_item'      => __( 'New Action article' ),
      'view_item'     => __( 'View Action article' ),
      'search_items'    => __( 'Search Action articles' ),
      'not_found'     => __( 'No Action articles found' ),
      'not_found_in_trash'=> __( 'No Action articles found' ),
      'parent_item_colon' => __( 'Action' ),
      'menu_name'     => __( 'Action' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Policy'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'policy', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 2,
      'menu_icon'     => get_bloginfo('template_directory').'/images/policy-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('policy',$post_type_args);
  }
  add_action('init', 'register_policy_posttype');

  // registration code for quality post type
  function register_quality_posttype() {
    $labels = array(
      'name'        => _x( 'Quality', 'post type general name' ),
      'singular_name'   => _x( 'Quality', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Quality article' ),
      'edit_item'     => __( 'Edit Quality' ),
      'new_item'      => __( 'New Quality article' ),
      'view_item'     => __( 'View Quality articles' ),
      'search_items'    => __( 'Search Quality articles' ),
      'not_found'     => __( 'No Quality articles found' ),
      'not_found_in_trash'=> __( 'No Quality articles found' ),
      'parent_item_colon' => __( 'Quality' ),
      'menu_name'     => __( 'Quality' )
    );

     $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Quality'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => false,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'quality', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 3,
      'menu_icon'     => get_bloginfo('template_directory').'/images/quality-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('quality',$post_type_args);
  }
  add_action('init', 'register_quality_posttype');

  // registration code for institute post type
  function register_institute_posttype() {
    $labels = array(
      'name'        => _x( 'Institute', 'post type general name' ),
      'singular_name'   => _x( 'Institute', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Institute article' ),
      'edit_item'     => __( 'Edit Institute' ),
      'new_item'      => __( 'New Institute article' ),
      'view_item'     => __( 'View Institute article' ),
      'search_items'    => __( 'Search Institute articles' ),
      'not_found'     => __( 'No Institute articles found' ),
      'not_found_in_trash'=> __( 'No Institute articles found' ),
      'parent_item_colon' => __( 'Institute' ),
      'menu_name'     => __( 'Institute' )
    );

    $taxonomies = array('post_tag', 'policytopics', 'educationtopics', 'qualitytopics', 'institutetopics');

    $supports = array('title','editor','author','thumbnail','excerpt','comments','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Institute'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => false,
      'hierarchical'    => false,
      'rewrite'       => array('slug' => 'institute', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 4,
      'menu_icon'     => get_bloginfo('template_directory').'/images/institute-menu.png',
      'taxonomies'    => $taxonomies
     );
     register_post_type('institute',$post_type_args);
  }
  add_action('init', 'register_institute_posttype');

  // registration code for discussion post type
  function register_discussion_posttype() {
    $labels = array(
      'name'        => _x( 'Discussions', 'post type general name' ),
      'singular_name'   => _x( 'Discussion', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Discussion' ),
      'edit_item'     => __( 'Edit Discussion' ),
      'new_item'      => __( 'New Discussion' ),
      'view_item'     => __( 'View Discussion' ),
      'search_items'    => __( 'Search Discussions' ),
      'not_found'     => __( 'No Discussions found' ),
      'not_found_in_trash'=> __( 'No Discussions found' ),
      'parent_item_colon' => __( 'Discussion' ),
      'menu_name'     => __( 'Discussions' )
    );

     $taxonomies = array('discussions','discussion_tags');

    $supports = array('title','editor','author','comments');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Discussion'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'exclude_from_search'=> false,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'rewrite'       => array('slug' => 'discussions', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      'taxonomies'    => $taxonomies
     );
     register_post_type('discussion',$post_type_args);
  }
  add_action('init', 'register_discussion_posttype');


  // registration code for discussion post type
  function register_group_posttype() {
    $labels = array(
      'name'        => _x( 'Groups', 'post type general name' ),
      'singular_name'   => _x( 'Group', 'post type singular name' ),
      'add_new'       => __( 'Add New' ),
      'add_new_item'    => __( 'Add new Group' ),
      'edit_item'     => __( 'Edit Group' ),
      'new_item'      => __( 'New Group' ),
      'view_item'     => __( 'View Group' ),
      'search_items'    => __( 'Search Groups' ),
      'not_found'     => __( 'No Groups found' ),
      'not_found_in_trash'=> __( 'No Group found' ),
      'parent_item_colon' => __( 'Group' ),
      'menu_name'     => __( 'Groups' )
    );

     $taxonomies = array('post_tag', 'groups');

    $supports = array('title','editor','author','comments','page-attributes','revisions');

    $post_type_args = array(
      'labels'      => $labels,
      'singular_label'  => __('Group'),
      'public'      => true,
      'show_ui'       => true,
      'publicly_queryable'=> true,
      'query_var'     => true,
      'exclude_from_search'=> false,
      'show_in_nav_menus' => true,
      'capability_type'   => 'post',
      'has_archive'     => true,
      'hierarchical'    => true,
      'rewrite'       => array('slug' => 'groups', 'with_front' => false ),
      'supports'      => $supports,
      'menu_position'   => 5,
      //'menu_icon'     => get_bloginfo('template_directory').'//images/quality-menu.png',
      'taxonomies'    => $taxonomies,
      'exclude_from_search' => true
     );
     register_post_type('group',$post_type_args);
  }
  add_action('init', 'register_group_posttype');