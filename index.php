<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Al_Anika_Theme
 * @since 9.0.0
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div class="row">
            <main class="site-main content-area">
                
                <?php if (have_posts()) : ?>
                
                    <?php if (is_home() && !is_front_page()) : ?>
                        <header class="page-header">
                            <h1 class="page-title"><?php single_post_title(); ?></h1>
                        </header>
                    <?php endif; ?>
                    
                    <div class="posts-grid grid grid-auto-fit">
                        <?php while (have_posts()) : the_post(); ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
                                
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="card-header p-0">
                                        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                                            <?php the_post_thumbnail('medium', array('class' => 'w-100 rounded-top')); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    
                                    <header class="entry-header">
                                        <?php if (is_singular()) : ?>
                                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                                        <?php else : ?>
                                            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                                        <?php endif; ?>
                                        
                                        <?php if ('post' === get_post_type()) : ?>
                                            <div class="entry-meta mb-3">
                                                <span class="posted-on">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                </span>
                                                
                                                <span class="byline">
                                                    <i class="far fa-user"></i>
                                                    <span class="author vcard">
                                                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                            <?php echo esc_html(get_the_author()); ?>
                                                        </a>
                                                    </span>
                                                </span>
                                                
                                                <?php if (has_category()) : ?>
                                                    <span class="cat-links">
                                                        <i class="far fa-folder"></i>
                                                        <?php echo get_the_category_list(esc_html__(', ', 'al-anika')); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (comments_open() || get_comments_number()) : ?>
                                                    <span class="comments-link">
                                                        <i class="far fa-comments"></i>
                                                        <?php comments_popup_link(
                                                            esc_html__('Leave a Comment', 'al-anika'),
                                                            esc_html__('1 Comment', 'al-anika'),
                                                            esc_html__('% Comments', 'al-anika')
                                                        ); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </header>
                                    
                                    <div class="entry-content">
                                        <?php
                                        if (is_singular()) {
                                            the_content(sprintf(
                                                wp_kses(
                                                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'al-anika'),
                                                    array('span' => array('class' => array()))
                                                ),
                                                get_the_title()
                                            ));
                                        } else {
                                            the_excerpt();
                                        }
                                        ?>
                                    </div>
                                    
                                </div>
                                
                                <?php if (!is_singular()) : ?>
                                    <div class="card-footer">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-small">
                                            <?php esc_html_e('Read More', 'al-anika'); ?>
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                        
                                        <?php if (has_tag()) : ?>
                                            <div class="entry-tags mt-3">
                                                <i class="fas fa-tags"></i>
                                                <?php the_tags('', ' '); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                            </article>
                            
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    // Pagination
                    the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Previous', 'al-anika'),
                        'next_text' => esc_html__('Next', 'al-anika') . ' <i class="fas fa-chevron-right"></i>',
                        'class'     => 'pagination-wrapper mt-5 text-center'
                    ));
                    ?>
                    
                <?php else : ?>
                    
                    <section class="no-results not-found">
                        <header class="page-header text-center">
                            <h1 class="page-title"><?php esc_html_e('Nothing here', 'al-anika'); ?></h1>
                        </header>
                        
                        <div class="page-content text-center">
                            <?php if (is_home() && current_user_can('publish_posts')) : ?>
                                
                                <p><?php
                                    printf(
                                        wp_kses(
                                            __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'al-anika'),
                                            array('a' => array('href' => array()))
                                        ),
                                        esc_url(admin_url('post-new.php'))
                                    );
                                ?></p>
                                
                            <?php elseif (is_search()) : ?>
                                
                                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'al-anika'); ?></p>
                                <?php get_search_form(); ?>
                                
                            <?php else : ?>
                                
                                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'al-anika'); ?></p>
                                <?php get_search_form(); ?>
                                
                            <?php endif; ?>
                        </div>
                    </section>
                    
                <?php endif; ?>
                
            </main>
            
            <?php get_sidebar(); ?>
            
        </div>
    </div>
</div>

<?php get_footer();