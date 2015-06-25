<?php get_header(); ?>

<?php  $speakerIMG = wp_get_attachment_url( get_post_thumbnail_id(472) );   ?>
<div id="featured-img" class="education webinar archive" style="background-image:url(<?php echo $speakerIMG ?>);">
	<div class="container">
		<div id="featured-intro">
				<h3><span>EDUCATION</span><br/>Presentations</h3>
		</div>
	</div>
</div>
<div id="contentWrap" class="education webinar archive">
	<a id="prevbtn" title="Show previous"> </a>
	<a id="nextbtn" title="Show more"> </a>
	<div class="gutter">
		<div class="container">
			<?php
				if(has_nav_menu('primary-menu')){
					$defaults = array(
						'theme_location'  => 'primary-menu',
						'menu'            => 'primary-menu',
						'container'       => 'div',
						'container_class' => '',
						'container_id'    => 'pageNav',
						'menu_class'      => 'quality',
						'menu_id'         => '',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => ''
					); wp_nav_menu( $defaults );
				}
			?>
			<div id="breadcrumbs">
				<ul>
					<li><a href="<?php echo home_url(); ?>">Home</a>
						<?php
						$defaults = array(
						'theme_location'  => 'primary-menu',
						'menu'            => 'primary-menu',
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'menu',
						'menu_id'         => '',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 0,
						'walker'          => ''
					); wp_nav_menu( $defaults ); ?>
					</li>
				</ul>

				<a href="<?php echo site_url('/feed/?post_type=presentation'); ?>" target="_blank">
					<div id="rssFeedIcon" class="education">
						Subscribe
					</div>
				</a>

			</div>

			<div id="postFeatured">
				<div class="eightteen columns filters-presentations">
					<!-- begin Category Filters !-->
	 				<span> FILTER BY ››</span>
	 				<div id="red_btn" data-filter="action" class="filter_btn-presentations ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/policy.png">
	 					<span>Action</span>
	 				</div>
	 				<div id="green_btn" data-filter="quality" class="filter_btn-presentations ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/quality.png">
	 					<span>Quality</span>
	 				</div>
	 				<div id="gray_btn" data-filter="education" class="filter_btn-presentations ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/edu.png">
	 					<span>Education</span>
	 				</div>
	 				<div id="blue_btn" data-filter="institute" class="filter_btn-presentations ">
	 					<img src="http://essentialhospitals.org/wp-content/themes/EssentialHospitals/images/inst.png">
	 					<span>Institute</span>
	 				</div>
					<div id="blue_btn" data-filter="*" class="filter_btn-presentations ">
						<span>All Sections</span>
					</div>
	 				<!-- end Category Filters !-->

	 				
	 			</div>
			</div>


			<div id="contentPrimary">
				<div class="graybar"></div>
				<div class="gutter clearfix">


					

					<span class="filterby">Filter By Event >></span>
					<select name="pres-eventFilter" id="pres-eventFilter">
						<option value="*">Select Event</option>
						<?php 
						$args = array( 'posts_per_page' => -1, 'post_type'=> 'events', 'orderby' => 'title' );

						$myposts = get_posts( $args );
						foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
							<option value="<?php echo $post->ID;?>"><?php the_title(); ?></option>
	 
						<?php endforeach; 

						wp_reset_postdata(); ?>
						<option value="*">Show All</option>
					</select>

					<!--
					<span class="filterby">Filter By Month >></span>
					<select name="presMonth" id="pres-monthFilter">
						<option value="*">Select Month</option>
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
						<option value="*">Show all Months</option>
					</select>
					-->



					<span class="filterby">Search Presentations >></span> 
					<form id="presentationSearch" class="eventsSearch">
						<input type="text" id="psearch" placeholder="search" />
						<input type="submit" id="psubmit" value="Search" />
					</form>


				</div>
			</div>

			<div id="contentSecondary">
				<div class="graybar"></div>
				<div class="gutter clearfix">
						<div id="postBox" class="clearfix">
								<div id="fader" class="clearfix scrollable events">
									<div id="loader-gif"> Loading more presentations</div>
									<div class="items">
										<?php AEH_Presentations::get_presentations(); ?>
									</div>
								</div>
						</div>
				</div>
			</div>


		</div>
	</div>
</div>

<?php get_footer(); ?>
