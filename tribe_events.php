<?php
/*
Template Name: Events
*/

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'custom_genesis_standard_loop' );

remove_action( 'genesis_after_content', 'genesis_get_sidebar' );


function custom_genesis_standard_loop() {

	printf( '<section class="subpage-container"><div class="wrap">' );

		echo '<div class="three-fourths first">';

		//* Use old loop hook structure if not supporting HTML5
		if ( ! genesis_html5() ) {
			genesis_legacy_loop();
			return;
		}
		if (is_tax('tribe_events_cat')) {
		    $current_category = get_queried_object();
		    $category_slug = $current_category->slug;
		    
		    $events = tribe_get_events([
		        'start_date' => '2011-01-01',
		        'posts_per_page' => -1,
		        'tax_query' => [
		            [
		                'taxonomy' => 'tribe_events_cat',
		                'field' => 'slug',
		                'terms' => $category_slug,
		            ],
		        ],
		    ]);
		}
		else {
		    // If no category slug is present in the URL
		    $events = tribe_get_events([
		        'start_date' => '2011-01-01',
		        'posts_per_page' => -1,
		    ]);
		}
		?>
		<div class="listing">
		<?php
		foreach ($events as $event) {
			?>
			<div class="card">
				<figure><?php ?></figure>
				<span><?php echo $event->event_date; ?></span>
				<h3><?php echo $event->post_title; ?></h3>
				<?php
			    // $event_title = $event->post_title;
			    ?>
			</div>
		    <?php
		}
		?></div><?php
		echo '</div>';

		echo '<div class="one-fourth sidebar sidebar-alt widget-area">'; 
			dynamic_sidebar( 'primary-sidebar' );
		echo '</div>';

	echo '</div></section>';

}
//* Remove edit link
add_filter( 'genesis_edit_post_link' , '__return_false' );
genesis();