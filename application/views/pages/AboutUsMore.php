<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Uploaded - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/design.css'); ?>">
</head>
<body>
    <?php $this->load->view('partials/navbar'); ?>
   <main class="decom-main">
        <div class="decom-container">
            <div class="decom-about-section">
                <div class="decom-about-logo-container">
                    <img src="<?php echo base_url('Pictures/DeComponents.jpeg'); ?>" alt="DeComponents Logo" class="decom-about-main-logo">
                    <h1>DECOMPONENTS</h1>
                    <p class="decom-subtitle">COMPUTER PARTS</p>
                </div>
                
                <div class="decom-about-description">
                    <p>Decomponents is a next-generation e-commerce platform designed to empower both buyers and sellers in an easy-to-navigate, secure online environment. Our goal is to bridge the gap between product discovery, trade, and purchase by providing a seamless, user-friendly marketplace where you can access a wide range of items—from cutting-edge technology and electronics to everyday essentials. At Decomponents, we focus on giving our users access to both popular global brands and emerging products, ensuring there's something for everyone.</p>
                    <br>      
                    <p>Beyond just buying and selling, Decomponents also allows users to trade products, helping foster a dynamic and vibrant marketplace. Our platform is built for convenience, offering robust search and browsing features, secure transactions, and easy management of your buying or selling activities. Whether you’re looking to upgrade your tech, sell old devices, or find unique items at competitive prices, Decomponents has got you covered.</p>
                    <br>  
                    <p>As we continue to grow, we’re committed to creating a platform that not only serves as a marketplace but also as a community where users can engage, share feedback, and help shape the future of e-commerce. We believe in the power of innovation and collaboration, and with your help, we can make Decomponents the ultimate destination for shopping, trading, and discovering new products.</p>
                </div>
            </main>

 <footer class="decom-footer">
        <nav class="decom-footer-nav">
            <a href="<?php echo site_url("home"); ?>">HOME</a>
            <a href="<?php echo site_url("products"); ?>">PRODUCTS</a>
            <a href="<?php echo site_url("about"); ?>">ABOUT US</a>
            <a href="<?php echo site_url("tradables"); ?>">TRADE</a>
            <a href="<?php echo site_url("news"); ?>">NEWS</a>
            <a href="<?php echo site_url("contact"); ?>">CONTACT</a>
        </nav>
        <div class="decom-social-links">
            <a href="https://www.youtube.com/@DeComponents"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href="https://www.tiktok.com/@decomponents"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href="#"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
        </div>
        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>
</html>
