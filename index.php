<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}

// Include database connection
include('db_connection.php'); // Ensure this file contains database connection logic

// Retrieve user role (admin or customer)
$user_id = $_SESSION['user_id'];
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_role = ($result->num_rows > 0) ? $result->fetch_assoc()['role'] : 'customer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VibeVintage Collection</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1 align="center">VibeVintage Collection</h1>
    </header>
    <div class="container">
        <div class="logo">
            <a href="#"><img src="VVC LOGO.jpeg" alt="VibeVintage Collection"></a>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="women.php">Women</a></li>
                <li><a href="kids.php">Kids</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="account.php">Account</a></li>
                <?php if ($user_role === 'admin'): ?>
                    <li><a href="admin_panel.php">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
            </div>
            <div class="cart-icon">
                <a href="cart.php"><img src="shopping-cart (2).png" alt="Cart"></a>
            </div>
        </div>
    </div>
    <!-- Rest of your content -->

    </header>
    <section class="hero">
        <div class="hero-content">
            <h1>Revive Your Style with VibeVintage</h1>
            <p>Discover unique vintage-inspired fashion for Men, Women, and Kids.</p>
            <div class="hero-buttons">
                <a href="men.php" class="btn">Shop Men’s Collection</a>
                <a href="women.php" class="btn">Shop Women’s Collection</a>
                <a href="kids.php" class="btn">Shop Kids’ Collection</a>
            </div>
        </div>
    </section>

    <section class="featured-collection">
        <div class="container">
            <h2>Featured Collections</h2>
            <div class="collection-grid">
                <div class="collection-item">
                    <img src="One.jpeg" alt="Men's Collection">
                    <div class="collection-info">
                        <h3>Men’s Collection</h3>
                        <p>Explore the latest trends in men’s fashion with a vintage twist.</p>
                        <a href="men.php" class="btn">Shop Now</a>
                    </div>
                </div>
                <div class="collection-item">
                    <img src="two.jpeg" alt="Women's Collection">
                    <div class="collection-info">
                        <h3>Women’s Collection</h3>
                        <p>Discover timeless pieces that bring out your inner vintage queen.</p>
                        <a href="women.php" class="btn">Shop Now</a>
                    </div>
                </div>
                <div class="collection-item">
                    
                    <img src="three.jpeg" alt="Kids' Collection">
                    <div class="collection-info">
                        <h3>Kids’ Collection</h3>
                        <p>Find adorable, stylish outfits for the little ones in your life.</p>
                        <a href="kids.php" class="btn">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="special-offers">
        <div class="container">
            <h2>Special Offers</h2>
            <div class="offers-grid">
                <div class="offer-item">
                    <div class="offer-banner">
                        <img src="specialoffer1.jpeg" height="1379px" width="1170px"alt="Offer 1">
                        <div class="offer-text">
                            <h3>50% Off All Sale Items</h3>
                            <p>Limited time offer! Grab your favorite pieces at half price.</p>
                            <a href="men.php" class="btn">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="offer-item">
                    <div class="offer-banner">
                        <img src="specialoffer2.jpeg"  alt="Offer 2">
                        <div class="offer-text">
                            <h3>Buy One Get One Free</h3>
                            <p>Exclusive deal on select items. Don’t miss out!</p>
                            <a href="women.php" class="btn">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="offer-item">
                    <div class="offer-banner">
                        <img src="specialoffer3.jpeg" alt="Offer 3">
                        <div class="offer-text">
                            <h3>Free Shipping on Orders Over 1000</h3>
                            <p>Enjoy free shipping on all orders over 1000. Shop now!</p>
                            <a href="kids.php" class="btn">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    

    

    <section class="trending-now">
        <div class="container">
            <h2>Trending Now</h2>
            <div class="trending-grid">
                <div class="trending-item">
                    <img src="trendingnow1.jpeg" alt="Trending Item 1">
                    <div class="trending-info">
                        <h3>Vintage Modern Suit for Men</h3>
                        <p>Classic style meets modern fashion.</p>
                    </div>
                </div>
                <div class="trending-item">
                    <img src="trendingnow2.jpeg" alt="Trending Item 2">
                    <div class="trending-info">
                        <h3>Retro Style Top & Denims</h3>
                        <p>Bring vintage charm to your wardrobe.</p>
                    </div>
                </div>
                <div class="trending-item">
                    <img src="trendingnow3.jpeg" alt="Trending Item 3">
                    <div class="trending-info">
                        <h3>Trendy Collection for Kids</h3>
                        <p>Comfort and style in one timeless pair.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="testimonials">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <div class="testimonials-grid">
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <p>"Absolutely love the vintage collection! The quality is unmatched and the style is unique."</p>
                        <h3>Jane Doe</h3>
                        <span>Fashion Enthusiast</span>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <p>"Great customer service and fantastic selection. I always find something special."</p>
                        <h3>John Smith</h3>
                        <span>Regular Customer</span>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <p>"The best place to find vintage-inspired pieces. Highly recommended for fashion lovers."</p>
                        <h3>Emily Johnson</h3>
                        <span>Style Blogger</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="newsletter-signup">
        <div class="container">
            <h2>Stay Updated</h2>
            <p>Sign up for our newsletter and get the latest trends and special offers delivered straight to your inbox.</p>
            <form action="#" method="post" class="signup-form">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </div>
    </section>
   
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>VibeVintage Collection offers a curated selection of vintage-inspired clothing for men, women, and kids. Discover unique pieces that blend classic style with modern trends.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Shop</a></li>
                        <li><a href="#">Special Offers</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact Us</h3>
                    <p>supportvibevintage@gmail.com</p>
                    <p>Phone: +9176210****1</p>
                </div>
                <br>
                <div class="footer-section">
                    <h3>Follow Us</h3>
                    <a href="#" class="social-icon"><img src="facebook.png" alt="Facebook"></a>
                    <a href="#" class="social-icon"><img src="instagram.png" alt="Instagram"></a>
                    <a href="#" class="social-icon"><img src="twitter.png" alt="Twitter"></a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 VibeVintage Collection. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <a href="#top" class="back-to-top" id="backToTop">
        <img src="arrow-up-icon.png" alt="Back to Top">
    </a>

    <script>
        // JavaScript to show/hide the button based on scroll position
        document.addEventListener('scroll', function() {
            const backToTopButton = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });
    </script>
     

</body>
</html>
</body>
</html>
</body>
</html>
