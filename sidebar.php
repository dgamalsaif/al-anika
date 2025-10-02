<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Al_Anika_Theme
 * @since 9.0.0
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="sidebar widget-area" role="complementary" aria-label="<?php esc_attr_e('Sidebar', 'al-anika'); ?>">
    
    <?php if (is_shop() || is_product_category() || is_product_tag() || is_product()) : ?>
        <?php if (is_active_sidebar('sidebar-shop')) : ?>
            <?php dynamic_sidebar('sidebar-shop'); ?>
        <?php else : ?>
            <?php dynamic_sidebar('sidebar-1'); ?>
        <?php endif; ?>
    <?php else : ?>
        <?php dynamic_sidebar('sidebar-1'); ?>
    <?php endif; ?>
    
    <?php if (!is_active_sidebar('sidebar-1') && !is_active_sidebar('sidebar-shop')) : ?>
        
        <div class="widget">
            <h3 class="widget-title"><?php esc_html_e('Search', 'al-anika'); ?></h3>
            <?php get_search_form(); ?>
        </div>
        
        <div class="widget">
            <h3 class="widget-title"><?php esc_html_e('Recent Posts', 'al-anika'); ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                foreach ($recent_posts as $post) : ?>
                    <li>
                        <a href="<?php echo get_permalink($post['ID']); ?>" title="<?php echo esc_attr($post['post_title']); ?>">
                            <?php echo $post['post_title']; ?>
                        </a>
                        <span class="post-date"><?php echo get_the_date('', $post['ID']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <?php if (has_nav_menu('primary')) : ?>
            <div class="widget">
                <h3 class="widget-title"><?php esc_html_e('Navigation', 'al-anika'); ?></h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'sidebar-menu',
                    'container'      => false,
                    'depth'          => 2,
                ));
                ?>
            </div>
        <?php endif; ?>
        
        <div class="widget">
            <h3 class="widget-title"><?php esc_html_e('Archives', 'al-anika'); ?></h3>
            <ul>
                <?php wp_get_archives('type=monthly&limit=12'); ?>
            </ul>
        </div>
        
        <div class="widget">
            <h3 class="widget-title"><?php esc_html_e('Categories', 'al-anika'); ?></h3>
            <ul>
                <?php wp_list_categories(array(
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'show_count' => 1,
                    'title_li'   => '',
                    'number'     => 10,
                )); ?>
            </ul>
        </div>
        
        <?php if (is_home() || is_archive() || is_single()) : ?>
            <div class="widget">
                <h3 class="widget-title"><?php esc_html_e('Tag Cloud', 'al-anika'); ?></h3>
                <?php wp_tag_cloud(array(
                    'smallest' => 12,
                    'largest'  => 18,
                    'unit'     => 'px',
                    'number'   => 20,
                )); ?>
            </div>
        <?php endif; ?>
        
    <?php endif; ?>
    
</aside><!-- #secondary -->