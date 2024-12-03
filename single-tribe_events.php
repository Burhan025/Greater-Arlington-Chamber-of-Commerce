<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.19
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// $events_label_singular = tribe_get_event_label_singular();
$events_label_plural   = tribe_get_event_label_plural();

$event_id = get_the_ID();

?>

<div id="tribe-events-content" class="tribe-events-single three-fourths first">

	<?php /*?><p class="tribe-events-back">
		<a href="<?php echo esc_url( tribe_get_events_link() ); ?>"> <?php printf( '&laquo; ' . esc_html_x( 'All %s', '%s Events plural label', 'the-events-calendar' ), $events_label_plural ); ?></a>
	</p><?php */?>

	

	<!-- Event header -->
	<div id="tribe-events-header" <?php tribe_events_the_header_attributes() ?>>
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-header -->

	<?php while ( have_posts() ) :  the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
			<!-- Event featured image, but exclude link -->
			<?php echo tribe_event_featured_image( $event_id, 'single-event-full', false ); ?>
			
			<!-- Notices -->
		<?php tribe_the_notices() ?>

		<div class="event-title-reg-btn">
			<div class="event-title">
				<?php the_title( '<h1 class="tribe-events-single-event-title">', '</h1>' ); ?>
			</div>
			<!-- Event-Registration -->
			<div class="event-reg">
			
				<?php 

				$link = get_field('registration');

				if( $link ): ?>

					<a class="fl-button" href="<?php echo $link; ?>" target="_blank">Register Now</a>

				<?php endif; ?>
			</div>
		</div>
			

		<div class="tribe-events-schedule tribe-clearfix">
			<?php echo tribe_events_event_schedule_details( $event_id, '<h2>', '</h2>' ); ?>
			<?php if ( tribe_get_cost() ) : ?>
				<span class="tribe-events-cost"><?php echo tribe_get_cost( null, true ) ?></span>
			<?php endif; ?>
		</div>
		
			

			<!-- Event meta -->
			<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
			<?php tribe_get_template_part( 'modules/meta' ); ?>
			
			
			<!-- Event content -->
			<?php do_action( 'tribe_events_single_event_before_the_content' ) ?>
			<div class="tribe-events-single-event-description tribe-events-content">
				<?php the_content(); ?>
			</div>
			
			<!-- .tribe-events-single-event-description -->
			<?php do_action( 'tribe_events_single_event_after_the_content' ) ?>
			
			<!-- .tribe-events-related-events -->
			<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
			
            
            <!-- Multiple Event Sponsors STARTS -->
			<h2>Event Sponsors</h2>
            <?php if( have_rows('add_new_event_sponsor') ):	?>
			<div class="multiple-event-sponsors">
            
            <?php while ( have_rows('add_new_event_sponsor') ) : the_row(); ?>
            	
                <div class="single-event-sponsor left">               
					<?php if( get_sub_field('multiple_sponsor_title') ): ?> 
                    <div class="single-event-sponsor-title">
                            <h4><?php the_sub_field('multiple_sponsor_title'); ?></h4>
                    </div>
                    <?php endif; ?>
                    
                    <?php if( get_sub_field('multiple_sponsor') ): ?> 
                    <div class="single-event-sponsor-content">
                            <?php the_sub_field('multiple_sponsor'); ?>
                    </div>
                    <?php endif; ?>                   
                </div>

                <div class="single-event-sponsor right"> 
                	<!-- intendent sonpsor -->
				  	<?php if( get_field('superintendent_sponsors_list') ): ?>
					<div class="superintendent-section">				
						<h4><?php the_field('superintendent_sponsors_title'); ?></h4>
						<div class="listing"><?php the_field('superintendent_sponsors_list'); ?></div>
						
					</div>
					<?php endif; ?>
				</div>	
				  	<!--  -->
                
            <?php endwhile; ?>
            
            </div>
            <?php else : endif; ?>
			<!-- Multiple Event Sponsors ENDS -->
            
            
            <!-- Board-sponsor -->
			<div class="other-sponsor">
			<div class="all-sponsor-left">
				<?php /*?><?php if( have_rows('event_sponsor') ):	?>
	        	<div class="board-wrapper">			
					<?php if( get_field('board_sponsor_title') ): ?>
	                    <h4><?php the_field('board_sponsor_title'); ?></h4>
	                    <?php endif; ?>         
					<div class="sponsors-image">
				 	<?php while ( have_rows('board_event_sponsor') ) : the_row(); ?>                               
	              		<?php if( get_sub_field('board_sponsor_image') ): ?>
	                    	<img alt="sponsors" src="<?php the_sub_field('board_sponsor_image'); ?>" />
	                    <?php endif; ?>            
	              	<?php endwhile; ?>
					 </div>         
	          	</div>
		  		<?php else : endif; ?><?php */?>
			
			<!-- Superintendent-Sponsors -->
				
				<?php if( get_field('list_of_sponsors') ): ?>
				<div class="principal-section">					
					<h4><?php the_field('principal_title'); ?></h4>
					<div class="listing"><?php the_field('list_of_sponsors'); ?></div>					
				</div>
				<?php endif; ?>
			</div>

			<!-- Principal-sponsor -->
			<div class="all-sponsor-right">
			<?php /*?><?php if( have_rows('event_sponsor') ):	?>
        	<div class="superintendent-wrapper">			
			<?php if( get_field('superintendent_sponsors_title') ): ?>
                    <h4><?php the_field('superintendent_sponsors_title'); ?></h4>
                    <?php endif; ?>          
			<div class="sponsors-image">
			 <?php while ( have_rows('superintendent_event_sponsor') ) : the_row(); ?>                               
              		<?php if( get_sub_field('superintendent_sponsor_image') ): ?>
                    	<img alt="sponsors" src="<?php the_sub_field('superintendent_sponsor_image'); ?>" />
                    <?php endif; ?>              
              <?php endwhile; ?>
				</div>        
          </div>
		  <?php else : endif; ?><?php */?>
			
			<!-- Teacher-sponsor -->
			<?php if( get_field('teacher_sponsor_list') ): ?>
			<div class="teacher-section">
				
				<h4><?php the_field('teacher_sponsor'); ?></h4>
				<div class="listing"><?php the_field('teacher_sponsor_list'); ?></div>
				
			</div>
			<?php endif; ?>
				</div>
				</div>
			
			<!-- Become-Sponsors-intro -->
			<div class="become-sponsor">
			<h2>Become a Sponsor</h2>	
			<div class="become-sponsor-intro">
			
				<?php if( get_field('intro_text') ): ?>
				
					<?php the_field('intro_text'); ?>
			
				<?php endif; ?>
			</div>
			
			<!-- Become-Sponsors-left -->
			<div class="become-sponsor-left">
			
				<?php if( get_field('sponsor_list_on_left') ): ?>
				
					<?php the_field('sponsor_list_on_left'); ?>
			
				<?php endif; ?>
				</div>
			
			<!-- Become-Sponsors-right -->
			<div class="become-sponsor-right">
			
				<?php if( get_field('sponsor_list_on_right') ): ?>
				
					<?php the_field('sponsor_list_on_right'); ?>
			
				<?php endif; ?>
				</div>
			</div>
			
			[fl_builder_insert_layout slug="sponsor-callout"]
			
		</div> <!-- #post-x -->
		<?php if ( get_post_type() == Tribe__Events__Main::POSTTYPE && tribe_get_option( 'showComments', false ) ) comments_template() ?>
	<?php endwhile; ?>

	<!-- Event footer -->
	<div id="tribe-events-footer">
		<!-- Navigation -->
		<nav class="tribe-events-nav-pagination" aria-label="<?php printf( esc_html__( '%s Navigation', 'the-events-calendar' ), $events_label_singular ); ?>">
			<ul class="tribe-events-sub-nav">
				<li class="tribe-events-nav-previous"><?php tribe_the_prev_event_link( '<span>&laquo;</span> %title%' ) ?></li>
				<li class="tribe-events-nav-next"><?php tribe_the_next_event_link( '%title% <span>&raquo;</span>' ) ?></li>
			</ul>
			<!-- .tribe-events-sub-nav -->
		</nav>
	</div>
	<!-- #tribe-events-footer -->

</div><!-- #tribe-events-content -->


<div class="one-fourth single-event-sidebar"><?php //dynamic_sidebar( 'single-event-sidebar' ); ?>
<div class="title">
	<h3>Upcoming Events</h3>
</div>
	<?php
// $cats = get_the_terms( $post_id, 'tribe_events_cat' );
// $term = get_term( $cats[1], $taxonomy ); // Getting the 2nd term item
// $name = $term->slug;
// $upcomings =  tribe_get_events(
// 	array(
// 	'eventDisplay'=>'upcoming',
// 	'posts_per_page'=>7,
// 		'tax_query'=> array(
// 			array(
// 			'taxonomy' => 'tribe_events_cat',
// 			'field' => 'slug',
// 			'terms' => $name
// 			)
// 		)
// 	)
// );
$cats = get_the_terms( $post_id, 'tribe_events_cat' );
$categories = array();
foreach ($cats as $cat) {
 $categories[] = $cat->slug;
}

$upcomings = tribe_get_events(
	array(
		'eventDisplay' => 'upcoming',
		'posts_per_page' => 7,
		'tax_query' => array(
			array(
				'taxonomy' => 'tribe_events_cat',
				'field' => 'slug',
				'terms' => $categories
			)
		)
	)
);


?>
	<ul>
	<?php
foreach($upcomings as $upcoming){
	?>
	<li>
		<?php
			$date = $upcoming->event_date;
			$newDate = date("M d, Y", strtotime($date));
		?>
		<p><?php echo $newDate;?></p>
		<h3><a href="/event/<?php echo $upcoming->post_name;?>"><?php echo $upcoming->post_title;?></a></h3>

	</li>
<?php
}?>
	</ul>
</div>