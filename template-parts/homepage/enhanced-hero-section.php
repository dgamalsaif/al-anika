<?php
/**
 * Enhanced Hero Section with E-commerce Focus
 * World-class banner with calls to action
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<section class="hero-section enhanced-hero" id="hero-section">
    <div class="hero-container">
        <!-- Main Hero Banner -->
        <div class="hero-main-banner">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="hero-main-text">عالم الأناقة</span>
                        <span class="hero-sub-text">أفضل المنتجات بأرقى الأسعار</span>
                    </h1>
                    <p class="hero-description">
                        اكتشف مجموعة حصرية من المنتجات العالمية بجودة استثنائية وأسعار منافسة
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-primary hero-btn">
                            <i class="fas fa-shopping-bag"></i>
                            تسوق الآن
                        </a>
                        <a href="#flash-sale" class="btn btn-outline hero-btn-secondary">
                            <i class="fas fa-bolt"></i>
                            العروض الخاطفة
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="<?php echo esc_url( ALAM_AL_ANIKA_THEME_URL . '/assets/images/hero-main.jpg' ); ?>" 
                         alt="عالم الأناقة" class="hero-main-img">
                </div>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="hero-quick-access">
            <div class="quick-access-grid">
                <div class="quick-card">
                    <div class="quick-icon">
                        <i class="fas fa-percent"></i>
                    </div>
                    <h3>خصومات تصل إلى 70%</h3>
                    <p>على مجموعة مختارة من المنتجات</p>
                    <a href="#super-sale" class="quick-link">استكشف</a>
                </div>
                
                <div class="quick-card">
                    <div class="quick-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>شحن مجاني</h3>
                    <p>للطلبات فوق 200 ريال</p>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="quick-link">تسوق</a>
                </div>
                
                <div class="quick-card">
                    <div class="quick-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>منتجات حصرية</h3>
                    <p>مختارة خصيصاً لك</p>
                    <a href="#picks-for-you" class="quick-link">اكتشف</a>
                </div>
                
                <div class="quick-card">
                    <div class="quick-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>عروض محدودة الوقت</h3>
                    <p>انتهز الفرصة قبل انتهاء الوقت</p>
                    <a href="#flash-sale" class="quick-link">عجل</a>
                </div>
            </div>
        </div>

        <!-- Floating Promotions -->
        <div class="hero-floating-promos">
            <div class="promo-badge badge-new">
                <span class="badge-text">جديد</span>
            </div>
            <div class="promo-badge badge-sale">
                <span class="badge-text">خصم 50%</span>
            </div>
            <div class="promo-badge badge-free-shipping">
                <span class="badge-text">شحن مجاني</span>
            </div>
        </div>
    </div>
</section>

<style>
.enhanced-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 80vh;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    margin-bottom: 60px;
}

.hero-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    width: 100%;
}

.hero-main-banner {
    position: relative;
    z-index: 2;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
    margin-bottom: 40px;
}

.hero-text {
    color: white;
}

.hero-title {
    margin-bottom: 20px;
}

.hero-main-text {
    display: block;
    font-size: 3.5em;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 10px;
}

.hero-sub-text {
    display: block;
    font-size: 1.8em;
    font-weight: 400;
    opacity: 0.9;
}

.hero-description {
    font-size: 1.2em;
    line-height: 1.6;
    margin-bottom: 30px;
    opacity: 0.8;
}

.hero-actions {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.hero-btn {
    padding: 15px 30px;
    font-size: 1.1em;
    border-radius: 30px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    font-weight: 600;
}

.btn-primary {
    background: #ff6b6b;
    color: white;
    border: 2px solid #ff6b6b;
}

.btn-primary:hover {
    background: #ff5252;
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
}

.btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-2px);
}

.hero-image {
    text-align: center;
}

.hero-main-img {
    max-width: 100%;
    height: auto;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.hero-quick-access {
    margin-top: 40px;
}

.quick-access-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.quick-card {
    background: rgba(255,255,255,0.1);
    border-radius: 15px;
    padding: 30px 20px;
    text-align: center;
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
}

.quick-card:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.2);
}

.quick-icon {
    font-size: 2.5em;
    margin-bottom: 15px;
    color: #ffd700;
}

.quick-card h3 {
    font-size: 1.3em;
    margin-bottom: 10px;
    font-weight: 600;
}

.quick-card p {
    opacity: 0.8;
    margin-bottom: 15px;
    line-height: 1.5;
}

.quick-link {
    color: #ffd700;
    text-decoration: none;
    font-weight: 600;
    padding: 8px 20px;
    border: 2px solid #ffd700;
    border-radius: 20px;
    display: inline-block;
    transition: all 0.3s ease;
}

.quick-link:hover {
    background: #ffd700;
    color: #667eea;
}

.hero-floating-promos {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 3;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.promo-badge {
    padding: 10px 15px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9em;
    animation: float 3s ease-in-out infinite;
}

.badge-new {
    background: #4CAF50;
    color: white;
}

.badge-sale {
    background: #FF5722;
    color: white;
    animation-delay: 0.5s;
}

.badge-free-shipping {
    background: #2196F3;
    color: white;
    animation-delay: 1s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 30px;
        text-align: center;
    }
    
    .hero-main-text {
        font-size: 2.5em;
    }
    
    .hero-sub-text {
        font-size: 1.4em;
    }
    
    .hero-actions {
        justify-content: center;
    }
    
    .quick-access-grid {
        grid-template-columns: 1fr;
    }
    
    .hero-floating-promos {
        position: static;
        flex-direction: row;
        justify-content: center;
        margin-top: 20px;
    }
}
</style>