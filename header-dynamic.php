<?php
/**
 * Dynamic Header Template - Alam Al Anika Theme
 * Loads different header layouts based on customizer settings
 *
 * @package AlamAlAnika
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'alam_primary_color', '#FF6B6B' ) ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Preload Critical Resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    
    <!-- Google Fonts - Load based on customizer settings -->
    <?php
    $body_font = get_theme_mod( 'alam_body_font_family', 'Inter' );
    $heading_font = get_theme_mod( 'alam_heading_font_family', 'Poppins' );
    $fonts_to_load = array();
    
    if ( $body_font !== 'inherit' ) {
        $fonts_to_load[] = str_replace( ' ', '+', $body_font ) . ':300,400,500,600,700';
    }
    if ( $heading_font !== 'inherit' && $heading_font !== $body_font ) {
        $fonts_to_load[] = str_replace( ' ', '+', $heading_font ) . ':300,400,500,600,700,800';
    }
    
    if ( ! empty( $fonts_to_load ) ) {
        $font_url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $fonts_to_load ) . '&display=swap';
        echo '<link rel="stylesheet" href="' . esc_url( $font_url ) . '">';
    }
    ?>
    
    <!-- Critical CSS Inline -->
    <style>
        /* Critical above-the-fold styles for instant loading */
        .loading-screen { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: #fff; 
            z-index: 9999; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            opacity: 1;
            transition: opacity 0.5s ease;
        }
        .loading-screen.fade-out { 
            opacity: 0; 
            pointer-events: none; 
        }
        .loading-spinner { 
            width: 40px; 
            height: 40px; 
            border: 4px solid #f3f3f3; 
            border-top: 4px solid <?php echo esc_attr( get_theme_mod( 'alam_primary_color', '#FF6B6B' ) ); ?>; 
            border-radius: 50%; 
            animation: spin 1s linear infinite; 
        }
        @keyframes spin { 
            0% { transform: rotate(0deg); } 
            100% { transform: rotate(360deg); } 
        }
        
        /* Base header styles */
        .site-header { 
            background: <?php echo esc_attr( get_theme_mod( 'alam_header_bg_color', '#FFFFFF' ) ); ?>; 
            color: <?php echo esc_attr( get_theme_mod( 'alam_header_text_color', '#333333' ) ); ?>; 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            height: <?php echo esc_attr( get_theme_mod( 'alam_header_height', '80' ) ); ?>px;
        }
        .container { 
            max-width: <?php echo esc_attr( get_theme_mod( 'alam_container_width', '1200' ) ); ?>px; 
            margin: 0 auto; 
            padding: 0 1rem; 
        }
        
        /* Mobile menu button */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 10px;
        }
        
        @media (max-width: <?php echo esc_attr( get_theme_mod( 'alam_mobile_breakpoint', '768' ) ); ?>px) {
            .mobile-menu-toggle {
                display: block;
            }
            .main-navigation {
                display: none;
            }
            .main-navigation.mobile-menu-open {
                display: block;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: <?php echo esc_attr( get_theme_mod( 'alam_header_bg_color', '#FFFFFF' ) ); ?>;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                padding: 20px;
            }
        }
    </style>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-spinner"></div>
</div>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'alam-al-anika' ); ?></a>

    <?php
    // Get header layout from customizer
    $header_layout = get_theme_mod( 'alam_header_layout', 'modern' );
    
    // Load the appropriate header template
    $header_file = get_template_directory() . '/template-parts/headers/header-' . $header_layout . '.php';
    
    if ( file_exists( $header_file ) ) {
        include $header_file;
    } else {
        // Fallback to modern header if the selected layout doesn't exist
        include get_template_directory() . '/template-parts/headers/header-modern.php';
    }
    ?>

    <!-- Mobile Menu Toggle Button -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'alam-al-anika' ); ?>">
        <i class="fa fa-bars"></i>
    </button>

    <script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const navigation = document.querySelector('.main-navigation');
        const loadingScreen = document.getElementById('loadingScreen');
        
        // Mobile menu toggle
        if (mobileToggle && navigation) {
            mobileToggle.addEventListener('click', function() {
                navigation.classList.toggle('mobile-menu-open');
                this.classList.toggle('active');
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (navigation.classList.contains('mobile-menu-open')) {
                    icon.className = 'fa fa-times';
                } else {
                    icon.className = 'fa fa-bars';
                }
            });
        }
        
        // Hide loading screen
        setTimeout(function() {
            if (loadingScreen) {
                loadingScreen.classList.add('fade-out');
                setTimeout(function() {
                    loadingScreen.style.display = 'none';
                }, 500);
            }
        }, 500);
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (navigation && navigation.classList.contains('mobile-menu-open')) {
                if (!navigation.contains(e.target) && !mobileToggle.contains(e.target)) {
                    navigation.classList.remove('mobile-menu-open');
                    mobileToggle.classList.remove('active');
                    mobileToggle.querySelector('i').className = 'fa fa-bars';
                }
            }
        });
    });
    </script>
