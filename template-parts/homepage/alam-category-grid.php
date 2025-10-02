<?php
/**
 * Alam Al Anika Category Grid Component
 * Large image-based category display with modern grid layout
 *
 * @package AlamAlAnika
 */

// Get WooCommerce product categories for the grid
$featured_categories = get_terms( array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'parent'     => 0,
    'number'     => 12,
    'orderby'    => 'count',
    'order'      => 'DESC'
) );

// Alam Al Anika category icons mapping
$category_icons = array(
    'women' => 'fas fa-female',
    'men' => 'fas fa-male',
    'kids' => 'fas fa-child',
    'shoes' => 'fas fa-shoe-prints',
    'bags' => 'fas fa-shopping-bag',
    'jewelry' => 'fas fa-gem',
    'beauty' => 'fas fa-palette',
    'home' => 'fas fa-home',
    'sports' => 'fas fa-running',
    'electronics' => 'fas fa-mobile-alt',
    'accessories' => 'fas fa-glasses',
    'clothing' => 'fas fa-tshirt'
);

// Mock category data if no WooCommerce categories exist
if ( empty( $featured_categories ) ) {
    $mock_categories = array(
        array( 'name' => 'Women\'s Fashion', 'slug' => 'women', 'count' => 120, 'image' => 'women-fashion.jpg' ),
        array( 'name' => 'Men\'s Style', 'slug' => 'men', 'count' => 89, 'image' => 'men-style.jpg' ),
        array( 'name' => 'Kids & Baby', 'slug' => 'kids', 'count' => 67, 'image' => 'kids-baby.jpg' ),
        array( 'name' => 'Shoes & Footwear', 'slug' => 'shoes', 'count' => 154, 'image' => 'shoes.jpg' ),
        array( 'name' => 'Bags & Accessories', 'slug' => 'bags', 'count' => 98, 'image' => 'bags.jpg' ),
        array( 'name' => 'Beauty & Health', 'slug' => 'beauty', 'count' => 76, 'image' => 'beauty.jpg' ),
        array( 'name' => 'Home & Living', 'slug' => 'home', 'count' => 134, 'image' => 'home.jpg' ),
        array( 'name' => 'Sports & Outdoor', 'slug' => 'sports', 'count' => 87, 'image' => 'sports.jpg' )
    );
}
?>

<!-- Alam Al Anika Category Grid Section -->
<section class="alam-category-grid-section" aria-labelledby="category-grid-title">
    <div class="container">
        
        <!-- Section Header -->
        <div class="section-header text-center">
            <h2 id="category-grid-title" class="section-title">
                <?php esc_html_e( 'Shop by Category', 'alam-al-anika' ); ?>
            </h2>
            <p class="section-subtitle">
                <?php esc_html_e( 'Discover our wide range of products across different categories', 'alam-al-anika' ); ?>
            </p>
        </div>

        <!-- Alam Al Anika Category Grid -->
        <div class="alam-category-grid">
            
            <?php if ( ! empty( $featured_categories ) ) : ?>
                <?php foreach ( $featured_categories as $category ) : ?>
                    <div class="alam-category-item" data-category="<?php echo esc_attr( $category->slug ); ?>">
                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="category-link" aria-describedby="cat-<?php echo esc_attr( $category->term_id ); ?>-desc">
                            
                            <!-- Category Image Container -->
                            <div class="category-image-container">
                                <?php
                                $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                                if ( $thumbnail_id ) {
                                    echo wp_get_attachment_image( $thumbnail_id, 'full', false, array( 
                                        'class' => 'category-image',
                                        'loading' => 'lazy',
                                        'alt' => sprintf( __( '%s category', 'alam-al-anika' ), $category->name )
                                    ) );
                                } else {
                                    // Fallback with icon and gradient background
                                    $icon = isset( $category_icons[$category->slug] ) ? $category_icons[$category->slug] : 'fas fa-shopping-bag';
                                    echo '<div class="category-placeholder">';
                                    echo '<div class="category-icon-wrapper">';
                                    echo '<i class="' . esc_attr( $icon ) . '" aria-hidden="true"></i>';
                                    echo '</div>';
                                    echo '<div class="category-gradient"></div>';
                                    echo '</div>';
                                }
                                ?>
                                
                                <!-- Category Overlay -->
                                <div class="category-overlay">
                                    <div class="category-info">
                                        <h3 class="category-title"><?php echo esc_html( $category->name ); ?></h3>
                                        <span class="category-count">
                                            <?php printf( _n( '%d item', '%d items', $category->count, 'alam-al-anika' ), $category->count ); ?>
                                        </span>
                                    </div>
                                    <div class="category-cta">
                                        <span class="cta-text"><?php esc_html_e( 'Shop Now', 'alam-al-anika' ); ?></span>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </a>
                    </div>
                <?php endforeach; ?>
                
            <?php else : ?>
                <!-- Fallback mock categories for demo -->
                <?php foreach ( $mock_categories as $category ) : ?>
                    <div class="alam-category-item" data-category="<?php echo esc_attr( $category['slug'] ); ?>">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="category-link">
                            
                            <div class="category-image-container">
                                <div class="category-placeholder">
                                    <div class="category-icon-wrapper">
                                        <?php 
                                        $icon = isset( $category_icons[$category['slug']] ) ? $category_icons[$category['slug']] : 'fas fa-shopping-bag';
                                        ?>
                                        <i class="<?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i>
                                    </div>
                                    <div class="category-gradient"></div>
                                </div>
                                
                                <div class="category-overlay">
                                    <div class="category-info">
                                        <h3 class="category-title"><?php echo esc_html( $category['name'] ); ?></h3>
                                        <span class="category-count">
                                            <?php printf( _n( '%d item', '%d items', $category['count'], 'alam-al-anika' ), $category['count'] ); ?>
                                        </span>
                                    </div>
                                    <div class="category-cta">
                                        <span class="cta-text"><?php esc_html_e( 'Shop Now', 'alam-al-anika' ); ?></span>
                                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </div>

        <!-- View All Categories CTA -->
        <div class="section-footer text-center">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline btn-lg">
                <?php esc_html_e( 'View All Categories', 'alam-al-anika' ); ?>
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

    </div>
</section>

<style>
/* Alam Al Anika Category Grid Styles */
.alam-category-grid-section {
    padding: 4rem 0;
    background: #fff;
}

.alam-category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin: 3rem 0;
}

.alam-category-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 4/5;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.alam-category-item:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.category-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.category-image-container {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.category-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.alam-category-item:hover .category-image {
    transform: scale(1.05);
}

.category-placeholder {
    width: 100%;
    height: 100%;
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-icon-wrapper {
    font-size: 4rem;
    color: rgba(255,255,255,0.9);
    z-index: 2;
    position: relative;
}

.category-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.4) 100%);
    z-index: 1;
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(transparent 50%, rgba(0,0,0,0.8) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 2rem;
    color: #fff;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.alam-category-item:hover .category-overlay {
    opacity: 1;
}

.category-info {
    margin-bottom: 1rem;
}

.category-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.category-count {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

.category-cta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
}

.cta-text {
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-cta i {
    transition: transform 0.3s ease;
}

.alam-category-item:hover .category-cta i {
    transform: translateX(5px);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .alam-category-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .alam-category-item {
        aspect-ratio: 3/4;
    }
    
    .category-title {
        font-size: 1.2rem;
    }
    
    .category-overlay {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .alam-category-grid {
        grid-template-columns: 1fr;
    }
    
    .alam-category-item {
        aspect-ratio: 16/9;
    }
}

/* Animation on scroll */
.alam-category-item {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeInUp 0.6s ease forwards;
}

.alam-category-item:nth-child(1) { animation-delay: 0.1s; }
.alam-category-item:nth-child(2) { animation-delay: 0.2s; }
.alam-category-item:nth-child(3) { animation-delay: 0.3s; }
.alam-category-item:nth-child(4) { animation-delay: 0.4s; }
.alam-category-item:nth-child(5) { animation-delay: 0.5s; }
.alam-category-item:nth-child(6) { animation-delay: 0.6s; }
.alam-category-item:nth-child(7) { animation-delay: 0.7s; }
.alam-category-item:nth-child(8) { animation-delay: 0.8s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Alam Al Anika category interactions
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.alam-category-item');
    
    // Add click tracking for analytics
    categoryItems.forEach(item => {
        item.addEventListener('click', function() {
            const categoryName = this.dataset.category;
            
            // Track category click (integrate with analytics)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Category Grid',
                    'event_label': categoryName
                });
            }
        });
    });

    // Intersection Observer for scroll animations
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        categoryItems.forEach(item => {
            item.style.animationPlayState = 'paused';
            observer.observe(item);
        });
    }
});
</script>
