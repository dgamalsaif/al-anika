<?php
/**
 * Smart Category Navigation - Phase 3 Enhancement
 * Horizontal scrolling category bar with mega menu and product previews
 *
 * @package AlamAlAnika
 */

// Get customizer values
$enable_mega_menu = get_theme_mod('smart_category_enable_mega_menu', true);
$category_display_count = get_theme_mod('smart_category_display_count', 8);
$mega_menu_columns = get_theme_mod('smart_category_mega_menu_columns', 4);
$show_product_count = get_theme_mod('smart_category_show_product_count', true);
$enable_category_icons = get_theme_mod('smart_category_enable_icons', true);
$scroll_animation_speed = get_theme_mod('smart_category_scroll_speed', 'medium');

// Get WooCommerce product categories
$categories = get_terms(array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'number' => $category_display_count,
    'orderby' => 'count',
    'order' => 'DESC',
    'exclude' => array(15) // Exclude uncategorized if needed
));

if (empty($categories) || is_wp_error($categories)) {
    return;
}

// Category icon mapping
$category_icons = array(
    'women' => 'fas fa-female',
    'رجالي' => 'fas fa-male',
    'men' => 'fas fa-male',
    'نسائي' => 'fas fa-female',
    'kids' => 'fas fa-child',
    'أطفال' => 'fas fa-child',
    'dresses' => 'fas fa-tshirt',
    'فساتين' => 'fas fa-tshirt',
    'jewelry' => 'fas fa-gem',
    'مجوهرات' => 'fas fa-gem',
    'bags' => 'fas fa-shopping-bag',
    'حقائب' => 'fas fa-shopping-bag',
    'shoes' => 'fas fa-shoe-prints',
    'أحذية' => 'fas fa-shoe-prints',
    'accessories' => 'fas fa-glasses',
    'إكسسوارات' => 'fas fa-glasses',
    'electronics' => 'fas fa-laptop',
    'إلكترونيات' => 'fas fa-laptop',
    'beauty' => 'fas fa-heart',
    'جمال' => 'fas fa-heart',
    'sports' => 'fas fa-running',
    'رياضة' => 'fas fa-running',
    'home' => 'fas fa-home',
    'منزل' => 'fas fa-home'
);

// Get icon for category
function get_category_icon($category_name, $category_slug, $category_icons) {
    $name_lower = strtolower($category_name);
    $slug_lower = strtolower($category_slug);
    
    if (isset($category_icons[$slug_lower])) {
        return $category_icons[$slug_lower];
    } elseif (isset($category_icons[$name_lower])) {
        return $category_icons[$name_lower];
    }
    return 'fas fa-th-large'; // Default icon
}
?>

<section class="smart-category-navigation" data-scroll-speed="<?php echo esc_attr($scroll_animation_speed); ?>">
    
    <!-- Category Bar Header -->
    <div class="category-nav-header">
        <h2 class="category-nav-title">
            <?php echo esc_html(get_theme_mod('smart_category_section_title', __('تسوق حسب الفئة', 'alam-al-anika'))); ?>
        </h2>
        <div class="category-nav-controls">
            <button class="category-scroll-btn category-scroll-prev" aria-label="<?php esc_attr_e('السابق', 'alam-al-anika'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="category-scroll-btn category-scroll-next" aria-label="<?php esc_attr_e('التالي', 'alam-al-anika'); ?>">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Horizontal Scrolling Category Bar -->
    <div class="category-bar-container">
        <div class="category-bar-wrapper">
            <div class="category-bar" data-mega-menu="<?php echo $enable_mega_menu ? 'true' : 'false'; ?>">
                
                <?php foreach ($categories as $index => $category): 
                    $category_url = get_term_link($category);
                    if (is_wp_error($category_url)) {
                        $category_url = wc_get_page_permalink('shop');
                    }
                    
                    $category_icon = get_category_icon($category->name, $category->slug, $category_icons);
                    $has_children = get_term_children($category->term_id, 'product_cat');
                    $is_featured = in_array($category->slug, array('women', 'نسائي', 'dresses', 'فساتين'));
                ?>
                
                <div class="category-item <?php echo $is_featured ? 'featured' : ''; ?>" 
                     data-category-id="<?php echo esc_attr($category->term_id); ?>"
                     data-category-slug="<?php echo esc_attr($category->slug); ?>"
                     data-has-children="<?php echo !empty($has_children) ? 'true' : 'false'; ?>">
                    
                    <a href="<?php echo esc_url($category_url); ?>" class="category-link">
                        
                        <?php if ($enable_category_icons): ?>
                        <div class="category-icon-wrapper">
                            <div class="category-icon">
                                <i class="<?php echo esc_attr($category_icon); ?>"></i>
                            </div>
                            <?php if ($is_featured): ?>
                            <div class="category-badge">
                                <span><?php esc_html_e('مميز', 'alam-al-anika'); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="category-info">
                            <h3 class="category-name"><?php echo esc_html($category->name); ?></h3>
                            <?php if ($show_product_count): ?>
                            <span class="category-count">
                                <?php printf(
                                    esc_html(_n('%d منتج', '%d منتجات', $category->count, 'alam-al-anika')),
                                    $category->count
                                ); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($enable_mega_menu && !empty($has_children)): ?>
                        <div class="category-menu-indicator">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                        
                    </a>

                    <?php if ($enable_mega_menu): ?>
                    <!-- Mega Menu for this category -->
                    <div class="category-mega-menu" data-category="<?php echo esc_attr($category->term_id); ?>">
                        <div class="mega-menu-container">
                            <div class="mega-menu-header">
                                <h4 class="mega-menu-title"><?php echo esc_html($category->name); ?></h4>
                                <a href="<?php echo esc_url($category_url); ?>" class="mega-menu-view-all">
                                    <?php esc_html_e('عرض الكل', 'alam-al-anika'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                            
                            <div class="mega-menu-content" data-columns="<?php echo esc_attr($mega_menu_columns); ?>">
                                
                                <!-- Subcategories Section -->
                                <?php if (!empty($has_children)): ?>
                                <div class="mega-menu-section subcategories-section">
                                    <h5 class="mega-menu-section-title"><?php esc_html_e('الأقسام الفرعية', 'alam-al-anika'); ?></h5>
                                    <div class="subcategories-grid">
                                        <?php 
                                        $subcategories = get_terms(array(
                                            'taxonomy' => 'product_cat',
                                            'parent' => $category->term_id,
                                            'hide_empty' => true,
                                            'number' => 6
                                        ));
                                        
                                        foreach ($subcategories as $subcategory):
                                            $subcat_url = get_term_link($subcategory);
                                            if (is_wp_error($subcat_url)) continue;
                                        ?>
                                        <a href="<?php echo esc_url($subcat_url); ?>" class="subcategory-item">
                                            <span class="subcategory-name"><?php echo esc_html($subcategory->name); ?></span>
                                            <span class="subcategory-count">(<?php echo $subcategory->count; ?>)</span>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Featured Products Section -->
                                <div class="mega-menu-section featured-products-section">
                                    <h5 class="mega-menu-section-title"><?php esc_html_e('منتجات مميزة', 'alam-al-anika'); ?></h5>
                                    <div class="featured-products-grid" data-category-id="<?php echo esc_attr($category->term_id); ?>">
                                        <!-- Products will be loaded via AJAX -->
                                        <div class="products-loading">
                                            <div class="loading-spinner"></div>
                                            <span><?php esc_html_e('جاري التحميل...', 'alam-al-anika'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Category Image/Banner -->
                                <div class="mega-menu-section category-banner-section">
                                    <?php 
                                    $category_image_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                    if ($category_image_id): 
                                        $category_image = wp_get_attachment_image($category_image_id, 'medium', false, array('class' => 'category-banner-image'));
                                    ?>
                                    <div class="category-banner">
                                        <?php echo wp_kses_post($category_image); ?>
                                        <div class="category-banner-overlay">
                                            <h6 class="banner-title"><?php echo esc_html($category->name); ?></h6>
                                            <p class="banner-description"><?php echo esc_html($category->description); ?></p>
                                            <a href="<?php echo esc_url($category_url); ?>" class="banner-cta">
                                                <?php esc_html_e('استكشف المجموعة', 'alam-al-anika'); ?>
                                            </a>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <div class="category-banner fallback-banner">
                                        <div class="fallback-icon">
                                            <i class="<?php echo esc_attr($category_icon); ?>"></i>
                                        </div>
                                        <h6 class="banner-title"><?php echo esc_html($category->name); ?></h6>
                                        <p class="banner-description">
                                            <?php printf(
                                                esc_html__('اكتشف %d منتجاً في هذه الفئة', 'alam-al-anika'),
                                                $category->count
                                            ); ?>
                                        </p>
                                        <a href="<?php echo esc_url($category_url); ?>" class="banner-cta">
                                            <?php esc_html_e('تسوق الآن', 'alam-al-anika'); ?>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
                <?php endforeach; ?>
                
                <!-- View All Categories Button -->
                <div class="category-item view-all-item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="category-link">
                        <div class="category-icon-wrapper">
                            <div class="category-icon">
                                <i class="fas fa-th"></i>
                            </div>
                        </div>
                        <div class="category-info">
                            <h3 class="category-name"><?php esc_html_e('عرض الكل', 'alam-al-anika'); ?></h3>
                            <span class="category-count"><?php esc_html_e('جميع الفئات', 'alam-al-anika'); ?></span>
                        </div>
                    </a>
                </div>
                
            </div>
        </div>
        
        <!-- Scroll Indicators -->
        <div class="scroll-indicators">
            <div class="scroll-indicator-track">
                <div class="scroll-indicator-thumb"></div>
            </div>
        </div>
        
    </div>

    <!-- Quick Filter Bar -->
    <div class="quick-filter-bar">
        <div class="filter-options">
            <button class="filter-btn active" data-filter="all">
                <?php esc_html_e('الكل', 'alam-al-anika'); ?>
            </button>
            <button class="filter-btn" data-filter="featured">
                <?php esc_html_e('مميز', 'alam-al-anika'); ?>
            </button>
            <button class="filter-btn" data-filter="sale">
                <?php esc_html_e('تخفيضات', 'alam-al-anika'); ?>
            </button>
            <button class="filter-btn" data-filter="new">
                <?php esc_html_e('جديد', 'alam-al-anika'); ?>
            </button>
        </div>
        
        <div class="filter-search">
            <input type="search" 
                   class="category-search-input" 
                   placeholder="<?php esc_attr_e('البحث في الفئات...', 'alam-al-anika'); ?>"
                   data-search-endpoint="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <button class="search-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>

</section>

<!-- AJAX Loading Overlay -->
<div class="category-ajax-overlay" style="display: none;">
    <div class="ajax-loading-container">
        <div class="ajax-spinner"></div>
        <p><?php esc_html_e('جاري تحميل المنتجات...', 'alam-al-anika'); ?></p>
    </div>
</div>
