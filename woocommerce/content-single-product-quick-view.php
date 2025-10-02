<?php
/**
 * Quick View Template for Products
 * Phase 5: Product Customization
 *
 * @package AlamAlAnika
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$product_id = $product->get_id();
$gallery_layout = get_theme_mod('alam_gallery_layout', 'thumbnails_left');
$gallery_zoom = get_theme_mod('alam_gallery_zoom', true);
$gallery_lightbox = get_theme_mod('alam_gallery_lightbox', true);

?>

<div class="quick-view-product" data-product-id="<?php echo esc_attr($product_id); ?>">
    
    <div class="quick-view-gallery">
        
        <!-- Product Gallery -->
        <div class="product-gallery-container gallery-layout-<?php echo esc_attr($gallery_layout); ?>">
            
            <!-- Main Image -->
            <div class="gallery-main <?php echo $gallery_zoom ? 'gallery-zoom-container' : ''; ?>">
                <?php
                $image_id = $product->get_image_id();
                if ($image_id) {
                    echo wp_get_attachment_image(
                        $image_id,
                        'woocommerce_single',
                        false,
                        array(
                            'class' => 'gallery-main-image',
                            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                        )
                    );
                } else {
                    echo wc_placeholder_img('woocommerce_single', array('class' => 'gallery-main-image'));
                }
                ?>
                
                <?php if ($gallery_zoom) : ?>
                    <div class="gallery-zoom-lens"></div>
                    <div class="gallery-zoom-result"></div>
                <?php endif; ?>
                
                <?php if ($gallery_lightbox) : ?>
                    <button class="gallery-lightbox-trigger" title="<?php esc_attr_e('تكبير الصورة', 'alam-al-anika'); ?>">
                        <i class="fas fa-expand"></i>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Gallery Thumbnails -->
            <?php
            $attachment_ids = $product->get_gallery_image_ids();
            if ($attachment_ids && count($attachment_ids) > 0) :
            ?>
                <div class="gallery-thumbnails">
                    <!-- Main image thumbnail -->
                    <?php if ($image_id) : ?>
                        <img src="<?php echo wp_get_attachment_image_url($image_id, 'woocommerce_gallery_thumbnail'); ?>" 
                             class="gallery-thumbnail active" 
                             data-large-image="<?php echo wp_get_attachment_image_url($image_id, 'woocommerce_single'); ?>"
                             alt="<?php echo esc_attr(get_post_meta($image_id, '_wp_attachment_image_alt', true)); ?>">
                    <?php endif; ?>
                    
                    <!-- Gallery image thumbnails -->
                    <?php foreach ($attachment_ids as $attachment_id) : ?>
                        <img src="<?php echo wp_get_attachment_image_url($attachment_id, 'woocommerce_gallery_thumbnail'); ?>" 
                             class="gallery-thumbnail" 
                             data-large-image="<?php echo wp_get_attachment_image_url($attachment_id, 'woocommerce_single'); ?>"
                             alt="<?php echo esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)); ?>">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
        </div>
        
    </div>
    
    <div class="quick-view-details">
        
        <!-- Product Title -->
        <h2 class="product-title">
            <?php the_title(); ?>
        </h2>
        
        <!-- Product Price -->
        <div class="product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
        
        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled()) : ?>
            <div class="product-rating">
                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                <span class="rating-count">
                    (<?php echo $product->get_review_count(); ?> <?php esc_html_e('تقييم', 'alam-al-anika'); ?>)
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Product Short Description -->
        <?php if ($product->get_short_description()) : ?>
            <div class="product-short-description">
                <?php echo $product->get_short_description(); ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Badges -->
        <div class="product-badges-container">
            <?php alam_product_badges(); ?>
        </div>
        
        <!-- Product Variations/Options -->
        <?php if ($product->is_type('variable')) : ?>
            <div class="product-variations">
                <?php
                // Get product variations
                $available_variations = $product->get_available_variations();
                $variation_attributes = $product->get_variation_attributes();
                
                foreach ($variation_attributes as $attribute_name => $options) :
                    $attribute_label = wc_attribute_label($attribute_name);
                ?>
                    <div class="variation-option">
                        <label for="<?php echo esc_attr($attribute_name); ?>">
                            <?php echo esc_html($attribute_label); ?>:
                        </label>
                        
                        <?php if ($attribute_name === 'pa_color' || strpos($attribute_name, 'color') !== false) : ?>
                            <!-- Color Swatches -->
                            <div class="product-swatches color-swatches">
                                <?php foreach ($options as $option) : ?>
                                    <div class="product-swatch color-swatch swatch-circle" 
                                         data-value="<?php echo esc_attr($option); ?>"
                                         data-tooltip="<?php echo esc_attr($option); ?>"
                                         style="--swatch-color: <?php echo esc_attr($this->getColorValue($option)); ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <!-- Regular Select -->
                            <select name="<?php echo esc_attr($attribute_name); ?>" id="<?php echo esc_attr($attribute_name); ?>">
                                <option value=""><?php esc_html_e('اختر', 'alam-al-anika'); ?> <?php echo esc_html($attribute_label); ?></option>
                                <?php foreach ($options as $option) : ?>
                                    <option value="<?php echo esc_attr($option); ?>">
                                        <?php echo esc_html($option); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Quantity and Add to Cart -->
        <div class="product-actions">
            
            <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                <div class="quantity-cart-wrapper">
                    
                    <!-- Quantity Selector -->
                    <?php if ($product->is_sold_individually()) : ?>
                        <input type="hidden" name="quantity" value="1">
                    <?php else : ?>
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn qty-minus">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   class="qty-input" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="<?php echo $product->get_max_purchase_quantity(); ?>"
                                   step="1">
                            <button type="button" class="qty-btn qty-plus">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Add to Cart Button -->
                    <button type="button" 
                            class="btn-add-to-cart btn-hover-glow" 
                            data-product-id="<?php echo esc_attr($product_id); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <?php echo esc_html($product->single_add_to_cart_text()); ?>
                    </button>
                    
                </div>
            <?php else : ?>
                <div class="out-of-stock-message">
                    <span class="stock-status">
                        <?php esc_html_e('نفد من المخزون', 'alam-al-anika'); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Quick Action Buttons -->
            <div class="quick-actions-secondary">
                
                <!-- Wishlist Button -->
                <?php if (get_theme_mod('alam_product_wishlist', true)) : ?>
                    <button type="button" class="btn-secondary wishlist-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
                        <i class="fas fa-heart"></i>
                        <?php esc_html_e('أضف للمفضلة', 'alam-al-anika'); ?>
                    </button>
                <?php endif; ?>
                
                <!-- Compare Button -->
                <?php if (get_theme_mod('alam_product_compare', true)) : ?>
                    <button type="button" class="btn-secondary compare-btn" data-product-id="<?php echo esc_attr($product_id); ?>">
                        <i class="fas fa-balance-scale"></i>
                        <?php esc_html_e('مقارنة', 'alam-al-anika'); ?>
                    </button>
                <?php endif; ?>
                
                <!-- View Full Product -->
                <a href="<?php echo get_permalink($product_id); ?>" class="btn-secondary view-full-btn">
                    <i class="fas fa-external-link-alt"></i>
                    <?php esc_html_e('عرض كامل', 'alam-al-anika'); ?>
                </a>
                
            </div>
            
        </div>
        
        <!-- Product Meta -->
        <div class="product-meta">
            
            <!-- SKU -->
            <?php if ($product->get_sku()) : ?>
                <div class="meta-item">
                    <span class="meta-label"><?php esc_html_e('رقم المنتج:', 'alam-al-anika'); ?></span>
                    <span class="meta-value"><?php echo esc_html($product->get_sku()); ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Categories -->
            <?php
            $categories = get_the_terms($product_id, 'product_cat');
            if ($categories && !is_wp_error($categories)) :
            ?>
                <div class="meta-item">
                    <span class="meta-label"><?php esc_html_e('التصنيف:', 'alam-al-anika'); ?></span>
                    <span class="meta-value">
                        <?php
                        $category_names = array();
                        foreach ($categories as $category) {
                            $category_names[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo implode(', ', $category_names);
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <!-- Tags -->
            <?php
            $tags = get_the_terms($product_id, 'product_tag');
            if ($tags && !is_wp_error($tags)) :
            ?>
                <div class="meta-item">
                    <span class="meta-label"><?php esc_html_e('الوسوم:', 'alam-al-anika'); ?></span>
                    <span class="meta-value">
                        <?php
                        $tag_names = array();
                        foreach ($tags as $tag) {
                            $tag_names[] = '<a href="' . esc_url(get_term_link($tag)) . '">' . esc_html($tag->name) . '</a>';
                        }
                        echo implode(', ', $tag_names);
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            
        </div>
        
        <!-- Social Sharing -->
        <?php if (get_theme_mod('alam_product_social_sharing', true)) : ?>
            <div class="product-social-sharing-compact">
                <span class="share-label"><?php esc_html_e('شارك:', 'alam-al-anika'); ?></span>
                <div class="social-buttons-compact">
                    <a href="#" class="social-btn-compact facebook" data-share="facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn-compact twitter" data-share="twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-btn-compact whatsapp" data-share="whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
    
</div>

<style>
/* Quick View Specific Styles */
.quick-view-product {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.quick-view-gallery {
    position: relative;
}

.quick-view-details {
    padding: 1rem 0;
}

.quick-view-details .product-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    line-height: 1.4;
}

.quick-view-details .product-price {
    font-size: 1.25rem;
    font-weight: bold;
    color: var(--primary-color, #007cba);
    margin-bottom: 1rem;
}

.quick-view-details .product-rating {
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quick-view-details .product-short-description {
    margin-bottom: 1.5rem;
    color: #666;
    line-height: 1.6;
}

.quantity-cart-wrapper {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    align-items: center;
}

.quantity-selector {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: #f8f8f8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.qty-btn:hover {
    background: var(--primary-color, #007cba);
    color: white;
}

.qty-input {
    width: 60px;
    height: 40px;
    border: none;
    text-align: center;
    font-weight: 600;
}

.btn-add-to-cart {
    flex: 1;
    padding: 0.75rem 1.5rem;
    background: var(--primary-color, #007cba);
    color: white;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-add-to-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,123,186,0.3);
}

.quick-actions-secondary {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.btn-secondary {
    padding: 0.5rem 1rem;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    border-color: var(--primary-color, #007cba);
    color: var(--primary-color, #007cba);
}

.product-meta {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

.meta-item {
    display: flex;
    margin-bottom: 0.5rem;
    gap: 0.5rem;
}

.meta-label {
    font-weight: 600;
    color: #666;
    min-width: 80px;
}

.meta-value a {
    color: var(--primary-color, #007cba);
    text-decoration: none;
}

.meta-value a:hover {
    text-decoration: underline;
}

.product-social-sharing-compact {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
}

.social-buttons-compact {
    display: flex;
    gap: 0.25rem;
}

.social-btn-compact {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.social-btn-compact:hover {
    transform: scale(1.1);
}

.social-btn-compact.facebook { background: #1877f2; }
.social-btn-compact.twitter { background: #1da1f2; }
.social-btn-compact.whatsapp { background: #25d366; }

/* Responsive */
@media (max-width: 768px) {
    .quick-view-product {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .quantity-cart-wrapper {
        flex-direction: column;
        align-items: stretch;
    }
    
    .quick-actions-secondary {
        justify-content: center;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Quantity controls
    $('.qty-plus').on('click', function() {
        var $input = $(this).siblings('.qty-input');
        var val = parseInt($input.val()) || 1;
        var max = parseInt($input.attr('max')) || 999;
        if (val < max) {
            $input.val(val + 1);
        }
    });
    
    $('.qty-minus').on('click', function() {
        var $input = $(this).siblings('.qty-input');
        var val = parseInt($input.val()) || 1;
        var min = parseInt($input.attr('min')) || 1;
        if (val > min) {
            $input.val(val - 1);
        }
    });
    
    // Social sharing
    $('.social-btn-compact').on('click', function(e) {
        e.preventDefault();
        var shareType = $(this).data('share');
        var url = window.location.href;
        var title = $('.product-title').text();
        
        var shareUrl = '';
        switch(shareType) {
            case 'facebook':
                shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
                break;
            case 'twitter':
                shareUrl = 'https://twitter.com/intent/tweet?url=' + encodeURIComponent(url) + '&text=' + encodeURIComponent(title);
                break;
            case 'whatsapp':
                shareUrl = 'https://wa.me/?text=' + encodeURIComponent(title + ' ' + url);
                break;
        }
        
        if (shareUrl) {
            window.open(shareUrl, '_blank', 'width=600,height=400');
        }
    });
});
</script>
