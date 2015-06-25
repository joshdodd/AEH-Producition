<div class="panel currdiscussions">
	<div class="gutter">
	<?php  ?>
<?php
	if (!empty($_POST)){

		$mod = get_post_meta(get_the_id(), 'mod', true);
	 	$newTitle = $_POST['title'];
	 	$newDesc  = $_POST['description'];
	 	$catPost   = $_POST['category'];
	 	$newID    = $_POST['userID'];
	 	global $post;
	    $parentID = $post->ID;

	 	$postType = get_post_type();

	 	// Create post object
		$newpost = array(
		  'comment_status' => 'open',
		  'post_author'    => $userID,
		  'post_content'   => $newDesc,
		  'post_status'    => 'publish',
		  'post_title'     => $newTitle,
		  'post_type'      => 'discussion',
		  'tax_input'      => array( 'discussions' => array( $catPost ) ),
		);

		// Insert the post into the database
		$newID = wp_insert_post( $newpost, true );
		add_post_meta($newID, 'mod', $mod, true);
		add_post_meta($newID, 'parentID', $parentID, true);
		wp_set_object_terms( $newID, $catPost, 'discussions' );

	 	echo "Thank you for your submission! You can access it below.";

	 	email_group($newID);



	 }

	$currentPostID = get_the_ID();
	$newCat = $postType.'-'.$currentPostID;
	$args = array(
		'post_type' => 'discussion',
		'posts_per_page' => 10,
		'tax_query' => array(
			array(
				'operator' => 'IN',
				'taxonomy' => 'discussions',
				'field' => 'slug',
				'terms' => $currentPostID
			)
		)
	);
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
		$postid = get_the_ID();?>

		<div class="group-discussion">
			<div class="membersonly"></div>
			<div class="item-header">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta">
					<p>Discussion Began: <?php the_time('M j, g:i a'); ?> <i class="orange"><em>(<?php comments_number( 'no replies', 'one reply', '% replies' ); ?>)</em></i></p>
				</div>
			</div>
			<div class="group-comments">
				<?php the_content();?>

				<?php
				//Show two recent comments
				/*
					$args = array(
						'post_id' => $postid,
					    'number'  => 2,
					);
					$comments = get_comments($args);
					foreach($comments as $comment) : ?>
						<p><?php echo $comment->comment_content; ?> | <?php comment_date('M j, g:i a'); ?></p>
				<?php endforeach; */?>
			</div>
		</div>

<?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>

<div class="panel newdiscussion">
	<div class="gutter">
		<h2 class="heading">Create Discussion</h2>
				 <form id="addPost" method="post">
					<input name="title" type="text" placeholder="Discussion Title" />
<?php 
$content = 'Start Discussion here';
$editor_id = 'kv_frontend_editor';
$settings =   array(
    'wpautop' => true, // use wpautop?
    'media_buttons' => false, // show insert/upload button(s)
    'textarea_name' => "description", // set the textarea name to something different, square brackets [] can be used here
    'textarea_rows' => 10,
    'tabindex' => false,
    'editor_css' => '', //  extra styles for both visual and HTML editors buttons, 
    'editor_class' => '', // add extra class(es) to the editor textarea
    'teeny' => true, // output the minimal editor config used in Press This
    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
    'quicktags' => false // load Quicktags, can be used to pass settings directly to Quicktags using an array()
);
?>
<?php wp_editor( $content, "description", $settings); ?>

					<!--<textarea name="description" type="textarea" placeholder="Start Discussion here"></textarea> -->
					<?php
						 echo '<input name="category" type="hidden" value="'.$currentPostID.'" />';
						 $user_ID = get_current_user_id();
						 echo '<input name="userID" type="hidden" value="'.$user_ID.'" />';
					?>
					<input name="submit" type="submit" value="Submit" />
				</form>
	</div>
</div>