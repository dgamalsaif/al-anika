 <?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AlamAlAnika
 */

get_header();
?>

	<div id="primary" class="content-area container" style="padding: 40px 20px;">
		<main id="main" class="site-main">

			<?php
			while ( have_posts() ) :
				the_post();

				// This reuses the content.php template part we already created.
				get_template_part( 'template-parts/content/content', get_post_type() );

				// Displays "Next Post" and "Previous Post" links.
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'alam-al-anika' ) . '</span> <span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'alam-al-anika' ) . '</span> <span class="nav-title">%title</span>',
					)
				);

				// If comments are open or there is at least one comment, load the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
		<?php get_sidebar(); ?>
	</div><!-- #primary -->

<?php
get_footer();
