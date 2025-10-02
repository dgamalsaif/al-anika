<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Al_Anika_Theme
 * @since 9.0.0
 */

get_header();
?>

<div class="site-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col text-center">
                
                <section class="error-404 not-found">
                    
                    <div class="error-404-content">
                        
                        <div class="error-number">
                            <span class="number-4">4</span>
                            <span class="number-0">
                                <i class="far fa-frown"></i>
                            </span>
                            <span class="number-4">4</span>
                        </div>
                        
                        <header class="page-header">
                            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'al-anika'); ?></h1>
                        </header><!-- .page-header -->
                        
                        <div class="page-content">
                            <p class="error-message">
                                <?php esc_html_e('It looks like nothing was found at this location. Maybe try searching for what you were looking for?', 'al-anika'); ?>
                            </p>
                            
                            <div class="error-search mt-4 mb-4">
                                <?php get_search_form(); ?>
                            </div>
                            
                            <div class="error-suggestions">
                                <h3><?php esc_html_e('Here are some helpful links instead:', 'al-anika'); ?></h3>
                                
                                <div class="suggestions-grid grid grid-cols-2 mt-4">
                                    
                                    <div class="suggestion-item card">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <i class="fas fa-home"></i>
                                                <?php esc_html_e('Go Home', 'al-anika'); ?>
                                            </h4>
                                            <p><?php esc_html_e('Start over from the homepage', 'al-anika'); ?></p>
                                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                                <?php esc_html_e('Homepage', 'al-anika'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <?php if (get_option('page_for_posts')) : ?>
                                        <div class="suggestion-item card">
                                            <div class="card-body">
                                                <h4 class="card-title">
                                                    <i class="fas fa-blog"></i>
                                                    <?php esc_html_e('Blog Posts', 'al-anika'); ?>
                                                </h4>
                                                <p><?php esc_html_e('Check out our latest articles', 'al-anika'); ?></p>
                                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-primary">
                                                    <?php esc_html_e('View Blog', 'al-anika'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (class_exists('WooCommerce') && get_option('woocommerce_shop_page_id')) : ?>
                                        <div class="suggestion-item card">
                                            <div class="card-body">
                                                <h4 class="card-title">
                                                    <i class="fas fa-shopping-bag"></i>
                                                    <?php esc_html_e('Shop Products', 'al-anika'); ?>
                                                </h4>
                                                <p><?php esc_html_e('Browse our product catalog', 'al-anika'); ?></p>
                                                <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_shop_page_id'))); ?>" class="btn btn-outline-primary">
                                                    <?php esc_html_e('Shop Now', 'al-anika'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="suggestion-item card">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <i class="fas fa-envelope"></i>
                                                <?php esc_html_e('Contact Us', 'al-anika'); ?>
                                            </h4>
                                            <p><?php esc_html_e('Get in touch if you need help', 'al-anika'); ?></p>
                                            <?php if (get_theme_mod('header_email')) : ?>
                                                <a href="mailto:<?php echo esc_attr(get_theme_mod('header_email')); ?>" class="btn btn-outline-primary">
                                                    <?php esc_html_e('Email Us', 'al-anika'); ?>
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-outline-primary" disabled>
                                                    <?php esc_html_e('Contact', 'al-anika'); ?>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <?php if (has_nav_menu('primary')) : ?>
                                <div class="site-navigation mt-5">
                                    <h3><?php esc_html_e('Site Navigation', 'al-anika'); ?></h3>
                                    <?php
                                    wp_nav_menu(array(
                                        'theme_location' => 'primary',
                                        'menu_class'     => 'error-nav-menu d-flex justify-content-center flex-wrap',
                                        'container'      => false,
                                        'depth'          => 1,
                                    ));
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="recent-posts mt-5">
                                <h3><?php esc_html_e('Recent Posts', 'al-anika'); ?></h3>
                                
                                <?php
                                $recent_posts = new WP_Query(array(
                                    'posts_per_page' => 3,
                                    'post_status'    => 'publish',
                                    'ignore_sticky_posts' => true,
                                ));
                                
                                if ($recent_posts->have_posts()) : ?>
                                    <div class="recent-posts-grid grid grid-cols-3">
                                        <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                                            <article class="recent-post card">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <div class="card-header p-0">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_post_thumbnail('medium', array('class' => 'w-100 rounded-top')); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <h4 class="card-title">
                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h4>
                                                    <div class="card-meta">
                                                        <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                                    </div>
                                                    <p class="card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-small btn-outline-primary">
                                                        <?php esc_html_e('Read More', 'al-anika'); ?>
                                                    </a>
                                                </div>
                                            </article>
                                        <?php endwhile; ?>
                                    </div>
                                    <?php wp_reset_postdata(); ?>
                                <?php endif; ?>
                            </div>
                            
                            <div class="error-report mt-5">
                                <h4><?php esc_html_e('Report this Error', 'al-anika'); ?></h4>
                                <p class="text-muted">
                                    <?php
                                    printf(
                                        esc_html__('If you believe this is an error, please report it to us. Include the URL you were trying to visit: %s', 'al-anika'),
                                        '<code>' . esc_html($_SERVER['REQUEST_URI']) . '</code>'
                                    );
                                    ?>
                                </p>
                                <?php if (get_theme_mod('header_email')) : ?>
                                    <a href="mailto:<?php echo esc_attr(get_theme_mod('header_email')); ?>?subject=<?php echo urlencode('404 Error Report'); ?>&body=<?php echo urlencode('I found a broken link at: ' . home_url($_SERVER['REQUEST_URI'])); ?>" class="btn btn-small btn-secondary">
                                        <?php esc_html_e('Report Error', 'al-anika'); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                        </div><!-- .page-content -->
                        
                    </div><!-- .error-404-content -->
                    
                </section><!-- .error-404 -->
                
            </div>
        </div>
    </div>
</div>

<style>
.error-404 {
    padding: var(--spacing-3xl) 0;
}

.error-number {
    font-size: 8rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: var(--spacing-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
}

.error-number .number-0 {
    color: var(--secondary-color);
    animation: bounce 2s infinite;
}

.error-message {
    font-size: var(--font-size-lg);
    color: var(--gray-600);
    margin-bottom: var(--spacing-xl);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.suggestions-grid {
    max-width: 800px;
    margin: 0 auto;
}

.suggestion-item {
    transition: all var(--transition-base);
}

.suggestion-item:hover {
    transform: translateY(-5px);
}

.suggestion-item .card-title {
    color: var(--secondary-color);
    margin-bottom: var(--spacing-sm);
}

.suggestion-item .card-title i {
    color: var(--primary-color);
    margin-right: var(--spacing-sm);
}

.error-nav-menu {
    gap: var(--spacing-lg);
}

.error-nav-menu a {
    color: var(--secondary-color);
    text-decoration: none;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--border-radius);
    transition: all var(--transition-base);
}

.error-nav-menu a:hover {
    background: var(--primary-color);
    color: var(--white);
}

.recent-posts-grid {
    max-width: 900px;
    margin: 0 auto;
}

.error-report {
    background: var(--gray-100);
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    max-width: 600px;
    margin: 0 auto;
}

.error-search .search-form {
    max-width: 400px;
    margin: 0 auto;
}

@media (max-width: 767.98px) {
    .error-number {
        font-size: 4rem;
    }
    
    .suggestions-grid,
    .recent-posts-grid {
        grid-template-columns: 1fr;
    }
    
    .error-nav-menu {
        flex-direction: column;
        align-items: center;
    }
}
</style>

<?php
get_footer();
?>