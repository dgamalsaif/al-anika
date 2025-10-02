<?php
/**
 * Al-Anika Theme Template Functions
 * Essential template functions and helpers
 * 
 * @package Al_Anika_Theme
 * @version 9.0.0 Final
 * @author MiniMax Agent
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Excerpt Length
 */
if (!function_exists('al_anika_excerpt_length')) {
    function al_anika_excerpt_length($length) {
        return 25;
    }
    add_filter('excerpt_length', 'al_anika_excerpt_length', 999);
}

/**
 * Custom Excerpt More
 */
if (!function_exists('al_anika_excerpt_more')) {
    function al_anika_excerpt_more($more) {
        return '...';
    }
    add_filter('excerpt_more', 'al_anika_excerpt_more');
}

/**
 * Custom Search Form
 */
if (!function_exists('al_anika_search_form')) {
    function al_anika_search_form($form) {
        $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">
            <div class="search-form-group">
                <input type="search" class="search-field" placeholder="' . esc_attr__('Search...', 'al-anika') . '" value="' . get_search_query() . '" name="s" />
                <button type="submit" class="search-submit">
                    <i class="fas fa-search"></i>
                    <span class="screen-reader-text">' . esc_html__('Search', 'al-anika') . '</span>
                </button>
            </div>
        </form>';
        return $form;
    }
    add_filter('get_search_form', 'al_anika_search_form');
}

/**
 * Custom Comment Form
 */
if (!function_exists('al_anika_comment_form_defaults')) {
    function al_anika_comment_form_defaults($defaults) {
        $defaults['comment_field'] = '<div class="comment-form-comment form-group">
            <label for="comment">' . esc_html__('Comment', 'al-anika') . ' <span class="required">*</span></label>
            <textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea>
        </div>';

        $defaults['fields']['author'] = '<div class="comment-form-author form-group">
            <label for="author">' . esc_html__('Name', 'al-anika') . ' <span class="required">*</span></label>
            <input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245" required />
        </div>';

        $defaults['fields']['email'] = '<div class="comment-form-email form-group">
            <label for="email">' . esc_html__('Email', 'al-anika') . ' <span class="required">*</span></label>
            <input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes" required />
        </div>';

        $defaults['fields']['url'] = '<div class="comment-form-url form-group">
            <label for="url">' . esc_html__('Website', 'al-anika') . '</label>
            <input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" />
        </div>';

        $defaults['class_submit'] = 'btn btn-primary';
        $defaults['submit_button'] = '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />';

        return $defaults;
    }
    add_filter('comment_form_defaults', 'al_anika_comment_form_defaults');
}

/**
 * Custom Comment Template
 */
if (!function_exists('al_anika_comment')) {
    function al_anika_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        extract($args, EXTR_SKIP);

        if ('div' == $args['style']) {
            $tag = 'div';
            $add_below = 'comment';
        } else {
            $tag = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
        <?php endif; ?>
        
        <div class="comment-author vcard d-flex">
            <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'rounded-full mr-3')); ?>
            <div class="comment-meta">
                <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
                <div class="comment-metadata">
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                        <?php printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?>
                    </a>
                    <?php edit_comment_link(__('(Edit)'), '  ', '') ?>
                </div>
            </div>
        </div>

        <?php if ($comment->comment_approved == '0') : ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-content mt-3">
            <?php comment_text() ?>
        </div>

        <div class="reply mt-3">
            <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        </div>
        
        <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
}

/**
 * Custom Post Navigation
 */
if (!function_exists('al_anika_post_navigation')) {
    function al_anika_post_navigation() {
        $previous = get_previous_post();
        $next = get_next_post();
        
        if (!$previous && !$next) {
            return;
        }
        ?>
        <nav class="post-navigation d-flex justify-content-between mt-5 mb-5">
            <div class="nav-previous">
                <?php if ($previous) : ?>
                    <a href="<?php echo get_permalink($previous->ID); ?>" class="nav-link">
                        <i class="fas fa-chevron-left"></i>
                        <div class="nav-content">
                            <span class="nav-subtitle"><?php esc_html_e('Previous Post', 'al-anika'); ?></span>
                            <span class="nav-title"><?php echo get_the_title($previous->ID); ?></span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="nav-next">
                <?php if ($next) : ?>
                    <a href="<?php echo get_permalink($next->ID); ?>" class="nav-link">
                        <div class="nav-content text-right">
                            <span class="nav-subtitle"><?php esc_html_e('Next Post', 'al-anika'); ?></span>
                            <span class="nav-title"><?php echo get_the_title($next->ID); ?></span>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        <?php
    }
}

/**
 * Related Posts Function
 */
if (!function_exists('al_anika_related_posts')) {
    function al_anika_related_posts($post_id = null, $limit = 3) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        $categories = get_the_category($post_id);
        if (!$categories) return;
        
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args = array(
            'category__in' => $category_ids,
            'post__not_in' => array($post_id),
            'posts_per_page' => $limit,
            'ignore_sticky_posts' => true
        );
        
        $related_posts = new WP_Query($args);
        
        if ($related_posts->have_posts()) :
            ?>
            <div class="related-posts mt-5">
                <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'al-anika'); ?></h3>
                <div class="grid grid-cols-3">
                    <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                        <article class="related-post card">
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
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        endif;
    }
}

/**
 * Get Reading Time
 */
if (!function_exists('al_anika_reading_time')) {
    function al_anika_reading_time($post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
        
        if ($reading_time == 1) {
            return '1 ' . esc_html__('minute read', 'al-anika');
        } else {
            return $reading_time . ' ' . esc_html__('minutes read', 'al-anika');
        }
    }
}

/**
 * Social Share Buttons
 */
if (!function_exists('al_anika_social_share')) {
    function al_anika_social_share($post_id = null) {
        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }
        
        $url = get_permalink($post_id);
        $title = get_the_title($post_id);
        $excerpt = wp_trim_words(get_the_excerpt(), 20);
        
        ?>
        <div class="social-share">
            <h4 class="social-share-title"><?php esc_html_e('Share this post', 'al-anika'); ?></h4>
            <div class="social-share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url); ?>" 
                   target="_blank" rel="noopener" class="share-button facebook">
                    <i class="fab fa-facebook-f"></i>
                    <span><?php esc_html_e('Facebook', 'al-anika'); ?></span>
                </a>
                
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($url); ?>&text=<?php echo urlencode($title); ?>" 
                   target="_blank" rel="noopener" class="share-button twitter">
                    <i class="fab fa-twitter"></i>
                    <span><?php esc_html_e('Twitter', 'al-anika'); ?></span>
                </a>
                
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($url); ?>" 
                   target="_blank" rel="noopener" class="share-button linkedin">
                    <i class="fab fa-linkedin-in"></i>
                    <span><?php esc_html_e('LinkedIn', 'al-anika'); ?></span>
                </a>
                
                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($url); ?>&description=<?php echo urlencode($excerpt); ?>" 
                   target="_blank" rel="noopener" class="share-button pinterest">
                    <i class="fab fa-pinterest-p"></i>
                    <span><?php esc_html_e('Pinterest', 'al-anika'); ?></span>
                </a>
                
                <a href="mailto:?subject=<?php echo urlencode($title); ?>&body=<?php echo urlencode($url); ?>" 
                   class="share-button email">
                    <i class="fas fa-envelope"></i>
                    <span><?php esc_html_e('Email', 'al-anika'); ?></span>
                </a>
            </div>
        </div>
        <?php
    }
}

/**
 * Custom Logo Function
 */
if (!function_exists('al_anika_custom_logo')) {
    function al_anika_custom_logo() {
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo();
        } else {
            ?>
            <div class="site-branding-text">
                <h1 class="site-title">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <?php bloginfo('name'); ?>
                    </a>
                </h1>
                <?php
                $description = get_bloginfo('description', 'display');
                if ($description || is_customize_preview()) :
                    ?>
                    <p class="site-description"><?php echo $description; ?></p>
                    <?php
                endif;
                ?>
            </div>
            <?php
        }
    }
}

/**
 * Custom Archive Title
 */
if (!function_exists('al_anika_archive_title')) {
    function al_anika_archive_title($title) {
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        } elseif (is_year()) {
            $title = get_the_date(_x('Y', 'yearly archives date format'));
        } elseif (is_month()) {
            $title = get_the_date(_x('F Y', 'monthly archives date format'));
        } elseif (is_day()) {
            $title = get_the_date(_x('F j, Y', 'daily archives date format'));
        } elseif (is_tax('post_format')) {
            if (is_tax('post_format', 'post-format-aside')) {
                $title = _x('Asides', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-gallery')) {
                $title = _x('Galleries', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-image')) {
                $title = _x('Images', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-video')) {
                $title = _x('Videos', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-quote')) {
                $title = _x('Quotes', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-link')) {
                $title = _x('Links', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-status')) {
                $title = _x('Statuses', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-audio')) {
                $title = _x('Audio', 'post format archive title');
            } elseif (is_tax('post_format', 'post-format-chat')) {
                $title = _x('Chats', 'post format archive title');
            }
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        } elseif (is_tax()) {
            $title = single_term_title('', false);
        }
        
        return $title;
    }
    add_filter('get_the_archive_title', 'al_anika_archive_title');
}

/**
 * Custom Body Classes
 */
if (!function_exists('al_anika_custom_body_classes')) {
    function al_anika_custom_body_classes($classes) {
        // Add singular class for singular pages
        if (is_singular()) {
            $classes[] = 'singular';
        }
        
        // Add class if sidebar is active
        if (is_active_sidebar('sidebar-1')) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }
        
        // Add class for page templates
        if (is_page_template()) {
            $template = get_page_template_slug();
            $template_class = 'page-template-' . str_replace('.php', '', basename($template));
            $classes[] = sanitize_html_class($template_class);
        }
        
        return $classes;
    }
    add_filter('body_class', 'al_anika_custom_body_classes');
}

/**
 * Custom Post Classes
 */
if (!function_exists('al_anika_custom_post_classes')) {
    function al_anika_custom_post_classes($classes) {
        // Add class if post has thumbnail
        if (has_post_thumbnail()) {
            $classes[] = 'has-thumbnail';
        } else {
            $classes[] = 'no-thumbnail';
        }
        
        // Add class for posts with excerpts
        if (has_excerpt()) {
            $classes[] = 'has-excerpt';
        }
        
        return $classes;
    }
    add_filter('post_class', 'al_anika_custom_post_classes');
}

/**
 * Clean up wp_head
 */
if (!function_exists('al_anika_head_cleanup')) {
    function al_anika_head_cleanup() {
        // Remove unnecessary links
        remove_action('wp_head', 'feed_links', 2);
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
        
        // Remove REST API links
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        
        // Remove DNS prefetch
        remove_action('wp_head', 'wp_resource_hints', 2);
    }
    add_action('init', 'al_anika_head_cleanup');
}



/**
 * AJAX Load More Posts
 */
if (!function_exists('al_anika_load_more_posts')) {
    function al_anika_load_more_posts() {
        check_ajax_referer('al_anika_ajax_nonce', 'nonce');
        
        $page = intval($_POST['page']);
        $posts_per_page = intval($_POST['posts_per_page']) ?: get_option('posts_per_page');
        
        $args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                get_template_part('template-parts/content/content', get_post_format());
            }
        }
        
        wp_reset_postdata();
        wp_die();
    }
    add_action('wp_ajax_al_anika_load_more_posts', 'al_anika_load_more_posts');
    add_action('wp_ajax_nopriv_al_anika_load_more_posts', 'al_anika_load_more_posts');
}

/**
 * Add schema.org markup for posts
 */
if (!function_exists('al_anika_add_schema_markup')) {
    function al_anika_add_schema_markup() {
        if (is_singular('post')) {
            global $post;
            ?>
            <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BlogPosting",
                "headline": "<?php echo esc_js(get_the_title()); ?>",
                "description": "<?php echo esc_js(wp_trim_words(get_the_excerpt(), 25)); ?>",
                "author": {
                    "@type": "Person",
                    "name": "<?php echo esc_js(get_the_author()); ?>"
                },
                "datePublished": "<?php echo esc_js(get_the_date('c')); ?>",
                "dateModified": "<?php echo esc_js(get_the_modified_date('c')); ?>",
                "publisher": {
                    "@type": "Organization",
                    "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
                    "logo": {
                        "@type": "ImageObject",
                        "url": "<?php echo esc_js(wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full')); ?>"
                    }
                },
                <?php if (has_post_thumbnail()) : ?>
                "image": "<?php echo esc_js(get_the_post_thumbnail_url(null, 'large')); ?>",
                <?php endif; ?>
                "mainEntityOfPage": {
                    "@type": "WebPage",
                    "@id": "<?php echo esc_js(get_permalink()); ?>"
                }
            }
            </script>
            <?php
        }
    }
    add_action('wp_head', 'al_anika_add_schema_markup');
}