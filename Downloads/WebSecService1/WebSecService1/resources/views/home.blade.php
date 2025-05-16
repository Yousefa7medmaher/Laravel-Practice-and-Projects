@extends('layouts.app')

@section('title', 'Web Service - Premium Shopping Experience')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content animate-on-scroll" data-animation="animate-slide-right">
                <h1 class="hero-title">Discover Amazing Products</h1>
                <p class="hero-subtitle">Experience premium shopping with our curated collection of high-quality products.</p>
                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i> Shop Now
                    </a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-user me-2"></i> Sign In
                        </a>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6 hero-image animate-on-scroll" data-animation="animate-slide-left">
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Hero Image" class="img-fluid rounded-3">
                <div class="hero-badge">
                    <span>New Collection</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="section-title text-center animate-on-scroll" data-animation="animate-slide-up">
            <h2>Shop by Category</h2>
            <p>Browse our wide range of product categories</p>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-4 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Electronics">
                        <div class="category-overlay">
                            <a href="#" class="btn btn-light">Shop Now</a>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3>Electronics</h3>
                        <p>Latest gadgets and devices</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2071&q=80" alt="Fashion">
                        <div class="category-overlay">
                            <a href="#" class="btn btn-light">Shop Now</a>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3>Fashion</h3>
                        <p>Trendy clothing and accessories</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="category-card">
                    <div class="category-image">
                        <img src="https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2032&q=80" alt="Home & Garden">
                        <div class="category-overlay">
                            <a href="#" class="btn btn-light">Shop Now</a>
                        </div>
                    </div>
                    <div class="category-content">
                        <h3>Home & Garden</h3>
                        <p>Beautiful items for your home</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="section-title text-center animate-on-scroll" data-animation="animate-slide-up">
            <h2>Why Choose Us</h2>
            <p>Discover the advantages of shopping with us</p>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-slide-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="feature-title">Fast Delivery</h3>
                    <p class="feature-text">Quick and reliable shipping to your doorstep</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-slide-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Secure Payments</h3>
                    <p class="feature-text">Safe and protected payment methods</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-slide-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3 class="feature-title">Easy Returns</h3>
                    <p class="feature-text">Hassle-free return policy for your peace of mind</p>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-slide-up">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-title">24/7 Support</h3>
                    <p class="feature-text">Customer service available around the clock</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="products-section py-5">
    <div class="container">
        <div class="section-title text-center animate-on-scroll" data-animation="animate-slide-up">
            <h2>Featured Products</h2>
            <p>Explore our most popular items</p>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Product 1">
                        <div class="product-badge new">New</div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Wireless Headphones</h3>
                        <div class="product-price">$129.99</div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="product-actions">
                            <a href="#" class="btn btn-sm btn-primary">Add to Cart</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2099&q=80" alt="Product 2">
                        <div class="product-badge sale">Sale</div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Smart Watch</h3>
                        <div class="product-price">
                            <span class="old-price">$199.99</span>
                            $149.99
                        </div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <div class="product-actions">
                            <a href="#" class="btn btn-sm btn-primary">Add to Cart</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1546868871-7041f2a55e12?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1964&q=80" alt="Product 3">
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Smart Speaker</h3>
                        <div class="product-price">$79.99</div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <div class="product-actions">
                            <a href="#" class="btn btn-sm btn-primary">Add to Cart</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 animate-on-scroll" data-animation="animate-fade-in">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1978&q=80" alt="Product 4">
                        <div class="product-badge out-of-stock">Sold Out</div>
                    </div>
                    <div class="product-details">
                        <h3 class="product-title">Wireless Earbuds</h3>
                        <div class="product-price">$89.99</div>
                        <div class="product-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="product-actions">
                            <a href="#" class="btn btn-sm btn-secondary disabled">Sold Out</a>
                            <a href="#" class="btn btn-sm btn-outline-secondary"><i class="far fa-heart"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 animate-on-scroll" data-animation="animate-slide-up">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="section-title text-center animate-on-scroll" data-animation="animate-slide-up">
            <h2>What Our Customers Say</h2>
            <p>Read testimonials from our satisfied customers</p>
        </div>

        <div class="row g-4 mt-4">
            <div class="col-lg-4 animate-on-scroll" data-animation="animate-fade-in">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>I've been shopping here for years and the quality of products and service is consistently excellent. Highly recommended!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Johnson">
                        </div>
                        <div class="testimonial-author-info">
                            <h5>Sarah Johnson</h5>
                            <p>Loyal Customer</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 animate-on-scroll" data-animation="animate-fade-in">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>The customer service is outstanding. When I had an issue with my order, they resolved it immediately. Great experience!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="https://randomuser.me/api/portraits/men/46.jpg" alt="Michael Brown">
                        </div>
                        <div class="testimonial-author-info">
                            <h5>Michael Brown</h5>
                            <p>Happy Shopper</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 animate-on-scroll" data-animation="animate-fade-in">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <p>Fast delivery and high-quality products. The website is also very easy to navigate. Will definitely shop here again!</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image">
                            <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Emily Davis">
                        </div>
                        <div class="testimonial-author-info">
                            <h5>Emily Davis</h5>
                            <p>New Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content animate-on-scroll" data-animation="animate-slide-up">
            <h2 class="newsletter-title">Subscribe to Our Newsletter</h2>
            <p class="newsletter-text">Stay updated with our latest products and exclusive offers</p>
            <form class="newsletter-form">
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Enter your email address" required>
                    <button class="btn" type="submit">Subscribe</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
/* Category Cards */
.categories-section {
    padding: 80px 0;
    background-color: var(--light);
}

.dark-theme .categories-section {
    background-color: var(--body-bg);
}

.category-card {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    height: 100%;
    background-color: white;
}

.dark-theme .category-card {
    background-color: var(--gray-light);
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

.category-image {
    height: 250px;
    position: relative;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--transition);
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-content {
    padding: 1.5rem;
    text-align: center;
}

.category-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.category-content p {
    color: var(--gray);
    margin-bottom: 0;
}

/* Hero Badge */
.hero-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(45deg, var(--accent), #f038a8);
    color: white;
    padding: 10px 20px;
    border-radius: 30px;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(247, 37, 133, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}
</style>
@endsection