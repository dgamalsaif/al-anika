<?php
/**
 * Alam Al Anika Hashtag Campaigns Component
 * Promotional campaigns with hashtags like #DarkRomance, #MillennialPink
 *
 * @package AlamAlAnika
 */

// Alam Al Anika hashtag campaigns
$hashtag_campaigns = array(
    array(
        'hashtag' => '#DarkRomance',
        'title' => __( 'Dark Romance', 'alam-al-anika' ),
        'description' => __( 'Embrace mysterious elegance with gothic-inspired fashion', 'alam-al-anika' ),
        'background_color' => '#1a1a1a',
        'text_color' => '#fff',
        'accent_color' => '#8b5a5a',
        'icon' => 'fas fa-moon',
        'products_count' => 156,
        'link' => '#'
    ),
    array(
        'hashtag' => '#MillennialPink',
        'title' => __( 'Millennial Pink', 'alam-al-anika' ),
        'description' => __( 'Soft, dreamy pink tones for the modern trendsetter', 'alam-al-anika' ),
        'background_color' => '#f7c6d8',
        'text_color' => '#333',
        'accent_color' => '#e91e63',
        'icon' => 'fas fa-heart',
        'products_count' => 89,
        'link' => '#'
    ),
    array(
        'hashtag' => '#NeonVibes',
        'title' => __( 'Neon Vibes', 'alam-al-anika' ),
        'description' => __( 'Electric colors that light up your style', 'alam-al-anika' ),
        'background_color' => '#00ff88',
        'text_color' => '#000',
        'accent_color' => '#ff0066',
        'icon' => 'fas fa-bolt',
        'products_count' => 234,
        'link' => '#'
    ),
    array(
        'hashtag' => '#MinimalChic',
        'title' => __( 'Minimal Chic', 'alam-al-anika' ),
        'description' => __( 'Less is more - clean lines and timeless style', 'alam-al-anika' ),
        'background_color' => '#f5f5f5',
        'text_color' => '#333',
        'accent_color' => '#000',
        'icon' => 'fas fa-circle',
        'products_count' => 178,
        'link' => '#'
    )
);
?>

<!-- Alam Al Anika Hashtag Campaigns Section -->
<section class="hashtag-campaigns-section" aria-labelledby="campaigns-title">
    <div class="container">
        
        <!-- Section Header -->
        <div class="section-header text-center">
            <h2 id="campaigns-title" class="section-title">
                <?php esc_html_e( 'Trending Now', 'alam-al-anika' ); ?>
            </h2>
            <p class="section-subtitle">
                <?php esc_html_e( 'Join the hottest fashion movements and express your unique style', 'alam-al-anika' ); ?>
            </p>
        </div>

        <!-- Hashtag Campaigns Grid -->
        <div class="hashtag-grid">
            
            <?php foreach ( $hashtag_campaigns as $index => $campaign ) : ?>
                <div class="hashtag-campaign" 
                     data-campaign="<?php echo esc_attr( strtolower( str_replace( '#', '', $campaign['hashtag'] ) ) ); ?>"
                     style="--bg-color: <?php echo esc_attr( $campaign['background_color'] ); ?>; --text-color: <?php echo esc_attr( $campaign['text_color'] ); ?>; --accent-color: <?php echo esc_attr( $campaign['accent_color'] ); ?>;">
                    
                    <a href="<?php echo esc_url( $campaign['link'] ); ?>" class="campaign-link" aria-describedby="campaign-<?php echo esc_attr( $index ); ?>-desc">
                        
                        <!-- Campaign Background -->
                        <div class="campaign-background">
                            <div class="campaign-pattern"></div>
                            <div class="campaign-gradient"></div>
                        </div>

                        <!-- Campaign Content -->
                        <div class="campaign-content">
                            
                            <!-- Icon -->
                            <div class="campaign-icon">
                                <i class="<?php echo esc_attr( $campaign['icon'] ); ?>" aria-hidden="true"></i>
                            </div>

                            <!-- Hashtag -->
                            <div class="campaign-hashtag">
                                <?php echo esc_html( $campaign['hashtag'] ); ?>
                            </div>

                            <!-- Title -->
                            <h3 class="campaign-title">
                                <?php echo esc_html( $campaign['title'] ); ?>
                            </h3>

                            <!-- Description -->
                            <p class="campaign-description" id="campaign-<?php echo esc_attr( $index ); ?>-desc">
                                <?php echo esc_html( $campaign['description'] ); ?>
                            </p>

                            <!-- Products Count -->
                            <div class="campaign-stats">
                                <span class="products-count">
                                    <?php printf( _n( '%d product', '%d products', $campaign['products_count'], 'alam-al-anika' ), $campaign['products_count'] ); ?>
                                </span>
                            </div>

                            <!-- CTA -->
                            <div class="campaign-cta">
                                <span class="cta-text"><?php esc_html_e( 'Explore Collection', 'alam-al-anika' ); ?></span>
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </div>

                        </div>

                        <!-- Hover Effect Overlay -->
                        <div class="campaign-hover-overlay">
                            <div class="hover-icon">
                                <i class="fas fa-search-plus" aria-hidden="true"></i>
                            </div>
                        </div>

                    </a>
                </div>
            <?php endforeach; ?>

        </div>

        <!-- Social Media Integration -->
        <div class="hashtag-social">
            <div class="social-header">
                <h3 class="social-title"><?php esc_html_e( 'Share Your Style', 'alam-al-anika' ); ?></h3>
                <p class="social-subtitle"><?php esc_html_e( 'Tag us in your posts and join the community', 'alam-al-anika' ); ?></p>
            </div>
            <div class="social-hashtags">
                <?php foreach ( $hashtag_campaigns as $campaign ) : ?>
                    <span class="social-hashtag"><?php echo esc_html( $campaign['hashtag'] ); ?></span>
                <?php endforeach; ?>
                <span class="brand-hashtag">#AlAnikaStyle</span>
            </div>
        </div>

    </div>
</section>

<style>
/* Alam Al Anika Hashtag Campaigns Styles */
.hashtag-campaigns-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.hashtag-campaigns-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.02'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
    z-index: 1;
}

.hashtag-campaigns-section > .container {
    position: relative;
    z-index: 2;
}

.hashtag-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.hashtag-campaign {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    aspect-ratio: 1.2;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    background: var(--bg-color);
    color: var(--text-color);
}

.hashtag-campaign:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.2);
}

.campaign-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    color: inherit;
    position: relative;
}

.campaign-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.campaign-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    opacity: 0.1;
    background: radial-gradient(circle at 50% 50%, transparent 40%, var(--accent-color) 70%);
}

.campaign-gradient {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(0,0,0,0.1) 100%);
}

.campaign-content {
    position: relative;
    z-index: 3;
    padding: 2.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
}

.campaign-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--accent-color);
    animation: float 3s ease-in-out infinite;
}

.campaign-hashtag {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--accent-color);
}

.campaign-title {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.campaign-description {
    font-size: 1rem;
    line-height: 1.5;
    margin-bottom: 1.5rem;
    opacity: 0.9;
    max-width: 250px;
}

.campaign-stats {
    margin-bottom: 1.5rem;
}

.products-count {
    background: var(--accent-color);
    color: #fff;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.campaign-cta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    font-size: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--accent-color);
}

.campaign-cta i {
    transition: transform 0.3s ease;
}

.hashtag-campaign:hover .campaign-cta i {
    transform: translateX(8px);
}

.campaign-hover-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 4;
}

.hashtag-campaign:hover .campaign-hover-overlay {
    opacity: 1;
}

.hover-icon {
    font-size: 4rem;
    color: #fff;
    animation: pulse 2s infinite;
}

/* Social Media Section */
.hashtag-social {
    text-align: center;
    margin-top: 4rem;
    padding: 3rem 2rem;
    background: rgba(255,255,255,0.8);
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

.social-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #333;
}

.social-subtitle {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 2rem;
}

.social-hashtags {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.social-hashtag,
.brand-hashtag {
    background: #000;
    color: #fff;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.brand-hashtag {
    background: var(--accent-color, #ff4444);
}

.social-hashtag:hover,
.brand-hashtag:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .hashtag-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .hashtag-campaign {
        aspect-ratio: 1.5;
    }
    
    .campaign-content {
        padding: 2rem;
    }
    
    .campaign-title {
        font-size: 1.5rem;
    }
    
    .campaign-hashtag {
        font-size: 1.2rem;
    }
    
    .social-hashtags {
        flex-direction: column;
        align-items: center;
    }
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Scroll animations */
.hashtag-campaign {
    opacity: 0;
    transform: translateY(50px) scale(0.9);
    animation: campaignFadeIn 0.8s ease forwards;
}

.hashtag-campaign:nth-child(1) { animation-delay: 0.1s; }
.hashtag-campaign:nth-child(2) { animation-delay: 0.3s; }
.hashtag-campaign:nth-child(3) { animation-delay: 0.5s; }
.hashtag-campaign:nth-child(4) { animation-delay: 0.7s; }

@keyframes campaignFadeIn {
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
</style>

<script>
// Alam Al Anika hashtag campaign interactions
document.addEventListener('DOMContentLoaded', function() {
    const campaigns = document.querySelectorAll('.hashtag-campaign');
    
    // Add click tracking for analytics
    campaigns.forEach(campaign => {
        campaign.addEventListener('click', function() {
            const campaignName = this.dataset.campaign;
            
            // Track campaign click
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'Hashtag Campaign',
                    'event_label': campaignName
                });
            }
            
            // Add ripple effect
            const ripple = document.createElement('div');
            ripple.className = 'ripple-effect';
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Social hashtag interactions
    const socialHashtags = document.querySelectorAll('.social-hashtag, .brand-hashtag');
    socialHashtags.forEach(hashtag => {
        hashtag.addEventListener('click', function() {
            const hashtagText = this.textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(hashtagText).then(() => {
                // Show feedback
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                this.style.background = '#28a745';
                
                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = '';
                }, 1500);
            });
        });
    });

    // Intersection Observer for animations
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

        campaigns.forEach(campaign => {
            campaign.style.animationPlayState = 'paused';
            observer.observe(campaign);
        });
    }
});
</script>

<style>
/* Ripple effect for clicks */
.ripple-effect {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.6);
    transform: scale(0);
    animation: ripple 0.6s linear;
    pointer-events: none;
    width: 100px;
    height: 100px;
    margin-left: -50px;
    margin-top: -50px;
}

@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}
</style>
