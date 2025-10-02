<?php
/**
 * SEO Optimization Features
 * Comprehensive SEO enhancements for Al-Anika theme
 *
 * @package AlamAlAnika
 * @since 6.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * SEO Optimization Class
 */
class Al_Anika_SEO {
    
    public function __construct() {
        add_action( 'init', array( $this, 'init_seo_features' ) );
    }
    
    /**
     * Initialize SEO features
     */
    public function init_seo_features() {
        // Meta tags and Open Graph
        add_action( 'wp_head', array( $this, 'add_meta_tags' ), 1 );
        add_action( 'wp_head', array( $this, 'add_open_graph_tags' ), 2 );
        add_action( 'wp_head', array( $this, 'add_twitter_cards' ), 3 );
        
        // Schema markup
        add_action( 'wp_head', array( $this, 'add_schema_markup' ), 4 );
        
        // Canonical URLs
        add_action( 'wp_head', array( $this, 'add_canonical_url' ), 1 );
        
        // XML Sitemap hints
        add_action( 'wp_head', array( $this, 'add_sitemap_hints' ), 1 );
        
        // Breadcrumb schema
        add_filter( 'woocommerce_structured_data_breadcrumb', array( $this, 'enhance_breadcrumb_schema' ) );
        
        // Product schema enhancement
        if ( class_exists( 'WooCommerce' ) ) {
            add_filter( 'woocommerce_structured_data_product', array( $this, 'enhance_product_schema' ), 10, 2 );
        }
        
        // Clean up WordPress head
        $this->cleanup_wp_head();
        
        // Add robots meta
        add_action( 'wp_head', array( $this, 'add_robots_meta' ), 1 );
        
        // Optimize titles
        add_filter( 'document_title_separator', array( $this, 'title_separator' ) );
        add_filter( 'document_title_parts', array( $this, 'optimize_title_parts' ) );
    }
    
    /**
     * Add essential meta tags
     */
    public function add_meta_tags() {
        // Viewport meta tag (mobile optimization)
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">' . "\n";
        
        // Charset meta tag
        echo '<meta charset="' . get_bloginfo( 'charset' ) . '">' . "\n";
        
        // Description meta tag
        $description = $this->get_meta_description();
        if ( $description ) {
            echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
        }
        
        // Keywords meta tag (if available)
        $keywords = $this->get_meta_keywords();
        if ( $keywords ) {
            echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
        }
        
        // Author meta tag
        if ( is_single() || is_page() ) {
            $author = get_the_author_meta( 'display_name' );
            if ( $author ) {
                echo '<meta name="author" content="' . esc_attr( $author ) . '">' . "\n";
            }
        }
        
        // Language meta tag
        echo '<meta name="language" content="' . get_locale() . '">' . "\n";
        
        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="#e74c3c">' . "\n";
        echo '<meta name="msapplication-navbutton-color" content="#e74c3c">' . "\n";
        echo '<meta name="apple-mobile-web-app-status-bar-style" content="#e74c3c">' . "\n";
    }
    
    /**
     * Add Open Graph tags
     */
    public function add_open_graph_tags() {
        // Basic Open Graph tags
        echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
        echo '<meta property="og:type" content="' . esc_attr( $this->get_og_type() ) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr( $this->get_og_title() ) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr( $this->get_meta_description() ) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_attr( $this->get_canonical_url() ) . '">' . "\n";
        
        // Open Graph image
        $og_image = $this->get_og_image();
        if ( $og_image ) {
            echo '<meta property="og:image" content="' . esc_attr( $og_image ) . '">' . "\n";
            echo '<meta property="og:image:alt" content="' . esc_attr( $this->get_og_image_alt() ) . '">' . "\n";
            
            // Get image dimensions
            $image_size = $this->get_image_dimensions( $og_image );
            if ( $image_size ) {
                echo '<meta property="og:image:width" content="' . esc_attr( $image_size['width'] ) . '">' . "\n";
                echo '<meta property="og:image:height" content="' . esc_attr( $image_size['height'] ) . '">' . "\n";
            }
        }
        
        // Additional product-specific OG tags
        if ( class_exists( 'WooCommerce' ) && is_product() ) {
            global $product;
            if ( $product ) {
                echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '">' . "\n";
                echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '">' . "\n";
                echo '<meta property="product:availability" content="' . esc_attr( $product->is_in_stock() ? 'in stock' : 'out of stock' ) . '">' . "\n";
            }
        }
    }
    
    /**
     * Add Twitter Card tags
     */
    public function add_twitter_cards() {
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        echo '<meta name="twitter:site" content="@' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr( $this->get_og_title() ) . '">' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr( $this->get_meta_description() ) . '">' . "\n";
        
        $twitter_image = $this->get_og_image();
        if ( $twitter_image ) {
            echo '<meta name="twitter:image" content="' . esc_attr( $twitter_image ) . '">' . "\n";
            echo '<meta name="twitter:image:alt" content="' . esc_attr( $this->get_og_image_alt() ) . '">' . "\n";
        }
    }
    
    /**
     * Add Schema.org JSON-LD markup
     */
    public function add_schema_markup() {
        $schema = array();
        
        // Organization schema
        $schema['organization'] = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url' => home_url(),
            'logo' => $this->get_site_logo(),
            'sameAs' => $this->get_social_profiles()
        );
        
        // Website schema
        $schema['website'] = array(
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url' => home_url(),
            'potentialAction' => array(
                '@type' => 'SearchAction',
                'target' => array(
                    '@type' => 'EntryPoint',
                    'urlTemplate' => home_url( '/?s={search_term_string}' )
                ),
                'query-input' => 'required name=search_term_string'
            )
        );
        
        // Page-specific schema
        if ( is_single() || is_page() ) {
            $schema['webpage'] = $this->get_webpage_schema();
        }
        
        if ( is_home() || is_front_page() ) {
            $schema['homepage'] = $this->get_homepage_schema();
        }
        
        // Product schema for WooCommerce
        if ( class_exists( 'WooCommerce' ) && is_product() ) {
            $schema['product'] = $this->get_enhanced_product_schema();
        }
        
        // Output schemas
        foreach ( $schema as $type => $data ) {
            if ( ! empty( $data ) ) {
                echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
            }
        }
    }
    
    /**
     * Add canonical URL
     */
    public function add_canonical_url() {
        $canonical = $this->get_canonical_url();
        if ( $canonical ) {
            echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
        }
    }
    
    /**
     * Add sitemap hints
     */
    public function add_sitemap_hints() {
        // XML sitemap link
        echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url( home_url( '/sitemap.xml' ) ) . '">' . "\n";
    }
    
    /**
     * Add robots meta tag
     */
    public function add_robots_meta() {
        $robots = $this->get_robots_content();
        if ( $robots ) {
            echo '<meta name="robots" content="' . esc_attr( $robots ) . '">' . "\n";
        }
    }
    
    /**
     * Get meta description
     */
    private function get_meta_description() {
        $description = '';
        
        if ( is_single() || is_page() ) {
            // Try to get excerpt first
            $description = get_the_excerpt();
            
            // If no excerpt, get content snippet
            if ( empty( $description ) ) {
                $content = get_the_content();
                $description = wp_trim_words( strip_tags( $content ), 25, '...' );
            }
        } elseif ( is_category() || is_tag() || is_tax() ) {
            $description = term_description();
            if ( empty( $description ) ) {
                $term = get_queried_object();
                $description = sprintf( __( 'Browse our %s collection', 'alam-al-anika' ), $term->name );
            }
        } elseif ( is_home() || is_front_page() ) {
            $description = get_bloginfo( 'description' );
        } elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
            $description = __( 'Shop our amazing collection of products with fast shipping and easy returns.', 'alam-al-anika' );
        }
        
        return wp_trim_words( strip_tags( $description ), 25, '...' );
    }
    
    /**
     * Get meta keywords
     */
    private function get_meta_keywords() {
        $keywords = array();
        
        if ( is_single() || is_page() ) {
            // Get tags as keywords
            $tags = get_the_tags();
            if ( $tags ) {
                foreach ( $tags as $tag ) {
                    $keywords[] = $tag->name;
                }
            }
            
            // Get categories as keywords
            $categories = get_the_category();
            if ( $categories ) {
                foreach ( $categories as $category ) {
                    $keywords[] = $category->name;
                }
            }
        }
        
        return ! empty( $keywords ) ? implode( ', ', $keywords ) : '';
    }
    
    /**
     * Get Open Graph type
     */
    private function get_og_type() {
        if ( is_single() ) {
            return 'article';
        } elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
            return 'product';
        } else {
            return 'website';
        }
    }
    
    /**
     * Get Open Graph title
     */
    private function get_og_title() {
        if ( is_single() || is_page() ) {
            return get_the_title();
        } elseif ( is_category() || is_tag() || is_tax() ) {
            return single_term_title( '', false );
        } else {
            return get_bloginfo( 'name' );
        }
    }
    
    /**
     * Get Open Graph image
     */
    private function get_og_image() {
        $image = '';
        
        if ( is_single() || is_page() ) {
            // Try featured image first
            if ( has_post_thumbnail() ) {
                $image = get_the_post_thumbnail_url( get_the_ID(), 'al-anika-hero-banner' );
            }
        }
        
        // Fallback to site logo or default image
        if ( empty( $image ) ) {
            $image = $this->get_site_logo();
        }
        
        return $image;
    }
    
    /**
     * Get Open Graph image alt text
     */
    private function get_og_image_alt() {
        if ( is_single() || is_page() ) {
            if ( has_post_thumbnail() ) {
                return get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
            }
        }
        
        return get_bloginfo( 'name' ) . ' logo';
    }
    
    /**
     * Get canonical URL
     */
    private function get_canonical_url() {
        global $wp;
        
        if ( is_single() || is_page() ) {
            return get_permalink();
        } elseif ( is_category() || is_tag() || is_tax() ) {
            return get_term_link( get_queried_object() );
        } else {
            return home_url( $wp->request );
        }
    }
    
    /**
     * Get robots content
     */
    private function get_robots_content() {
        $robots = array();
        
        if ( is_404() || is_search() ) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } elseif ( is_admin() || is_login() ) {
            $robots[] = 'noindex';
            $robots[] = 'nofollow';
        } else {
            $robots[] = 'index';
            $robots[] = 'follow';
            $robots[] = 'max-snippet:-1';
            $robots[] = 'max-image-preview:large';
            $robots[] = 'max-video-preview:-1';
        }
        
        return implode( ', ', $robots );
    }
    
    /**
     * Get site logo URL
     */
    private function get_site_logo() {
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            return wp_get_attachment_image_url( $custom_logo_id, 'full' );
        }
        
        // Fallback to theme default logo
        return get_template_directory_uri() . '/assets/images/logo.png';
    }
    
    /**
     * Get social media profiles
     */
    private function get_social_profiles() {
        $profiles = array();
        
        // You can add customizer options for social media URLs
        $social_networks = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube' );
        
        foreach ( $social_networks as $network ) {
            $url = get_theme_mod( 'social_' . $network . '_url' );
            if ( $url ) {
                $profiles[] = $url;
            }
        }
        
        return $profiles;
    }
    
    /**
     * Get webpage schema
     */
    private function get_webpage_schema() {
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'description' => $this->get_meta_description(),
            'url' => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified' => get_the_modified_date( 'c' ),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo( 'name' ),
                'logo' => $this->get_site_logo()
            )
        );
    }
    
    /**
     * Get homepage schema
     */
    private function get_homepage_schema() {
        return array(
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => home_url( '/#webpage' ),
            'name' => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url' => home_url(),
            'about' => array(
                '@type' => 'Thing',
                'name' => get_bloginfo( 'name' )
            ),
            'isPartOf' => array(
                '@type' => 'WebSite',
                '@id' => home_url( '/#website' )
            )
        );
    }
    
    /**
     * Get enhanced product schema for WooCommerce
     */
    private function get_enhanced_product_schema() {
        if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
            return array();
        }
        
        global $product;
        if ( ! $product ) {
            return array();
        }
        
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags( $product->get_description() ),
            'sku' => $product->get_sku(),
            'image' => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),
            'offers' => array(
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo( 'name' )
                )
            )
        );
        
        // Add review data if available
        if ( $product->get_review_count() > 0 ) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count()
            );
        }
        
        // Add brand if available
        $brand = get_post_meta( $product->get_id(), '_product_brand', true );
        if ( $brand ) {
            $schema['brand'] = array(
                '@type' => 'Brand',
                'name' => $brand
            );
        }
        
        return $schema;
    }
    
    /**
     * Get image dimensions
     */
    private function get_image_dimensions( $image_url ) {
        $attachment_id = attachment_url_to_postid( $image_url );
        if ( $attachment_id ) {
            $metadata = wp_get_attachment_metadata( $attachment_id );
            if ( $metadata && isset( $metadata['width'], $metadata['height'] ) ) {
                return array(
                    'width' => $metadata['width'],
                    'height' => $metadata['height']
                );
            }
        }
        
        return false;
    }
    
    /**
     * Clean up WordPress head
     */
    private function cleanup_wp_head() {
        // Remove unnecessary tags
        remove_action( 'wp_head', 'wp_generator' );
        remove_action( 'wp_head', 'wlwmanifest_link' );
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wp_shortlink_wp_head' );
        remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
        
        // Remove emoji scripts
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        
        // Remove REST API links
        remove_action( 'wp_head', 'rest_output_link_wp_head' );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    }
    
    /**
     * Title separator
     */
    public function title_separator( $sep ) {
        return '|';
    }
    
    /**
     * Optimize title parts
     */
    public function optimize_title_parts( $title ) {
        // Limit title length for SEO
        if ( isset( $title['title'] ) && strlen( $title['title'] ) > 60 ) {
            $title['title'] = wp_trim_words( $title['title'], 8, '...' );
        }
        
        return $title;
    }
    
    /**
     * Enhance breadcrumb schema
     */
    public function enhance_breadcrumb_schema( $markup ) {
        // Add additional breadcrumb properties
        return $markup;
    }
    
    /**
     * Enhance product schema
     */
    public function enhance_product_schema( $markup, $product ) {
        // Add additional product properties
        if ( isset( $markup['offers'] ) && is_array( $markup['offers'] ) ) {
            $markup['offers']['priceValidUntil'] = date( 'Y-m-d', strtotime( '+1 year' ) );
            $markup['offers']['itemCondition'] = 'https://schema.org/NewCondition';
        }
        
        return $markup;
    }
}

// Initialize SEO optimization
new Al_Anika_SEO();

/**
 * SEO utility functions
 */

/**
 * Generate XML sitemap (basic implementation)
 */
function al_anika_generate_sitemap() {
    if ( get_query_var( 'sitemap' ) ) {
        header( 'Content-Type: application/xml; charset=utf-8' );
        
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        echo '<url>';
        echo '<loc>' . esc_url( home_url() ) . '</loc>';
        echo '<lastmod>' . date( 'Y-m-d\TH:i:s+00:00' ) . '</lastmod>';
        echo '<changefreq>daily</changefreq>';
        echo '<priority>1.0</priority>';
        echo '</url>' . "\n";
        
        // Pages
        $pages = get_pages();
        foreach ( $pages as $page ) {
            echo '<url>';
            echo '<loc>' . esc_url( get_permalink( $page->ID ) ) . '</loc>';
            echo '<lastmod>' . date( 'Y-m-d\TH:i:s+00:00', strtotime( $page->post_modified ) ) . '</lastmod>';
            echo '<changefreq>weekly</changefreq>';
            echo '<priority>0.8</priority>';
            echo '</url>' . "\n";
        }
        
        // Posts
        $posts = get_posts( array( 'numberposts' => 1000 ) );
        foreach ( $posts as $post ) {
            echo '<url>';
            echo '<loc>' . esc_url( get_permalink( $post->ID ) ) . '</loc>';
            echo '<lastmod>' . date( 'Y-m-d\TH:i:s+00:00', strtotime( $post->post_modified ) ) . '</lastmod>';
            echo '<changefreq>monthly</changefreq>';
            echo '<priority>0.6</priority>';
            echo '</url>' . "\n";
        }
        
        echo '</urlset>';
        exit;
    }
}
add_action( 'template_redirect', 'al_anika_generate_sitemap' );

/**
 * Add sitemap rewrite rule
 */
function al_anika_sitemap_rewrite() {
    add_rewrite_rule( '^sitemap\.xml$', 'index.php?sitemap=1', 'top' );
}
add_action( 'init', 'al_anika_sitemap_rewrite' );

/**
 * Add sitemap query var
 */
function al_anika_sitemap_query_vars( $vars ) {
    $vars[] = 'sitemap';
    return $vars;
}
add_filter( 'query_vars', 'al_anika_sitemap_query_vars' );
