<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
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
                    <a href="<?php echo site_url('about-more'); ?>" class="decom-read-more">Read More</a>
                </div>

                <h2 class="decom-team-header">MEET OUR TEAM</h2>

                <div class="decom-team-members">
                    <div class="decom-team-member">
                        <div class="decom-member-image">
                            <img src="<?php echo base_url('Pictures/Oxillo.jpg'); ?>" alt="Team Member">
                        </div>
                        <div class="decom-member-info">
                            <p>Izz Aldin Oxillo is the visionary behind Decomponents, bringing a wealth of experience and passion for innovation to the world of e-commerce. With a background in Electrionic Industry, ge founded Decomponents with the mission to revolutionize the online marketplace by offering users an intuitive, secure, and dynamic platform. Oxillo’s leadership and forward-thinking approach have been key in shaping the direction of the company, ensuring it remains a go-to destination for a seamless shopping experience. He is committed to creating a community-driven platform where innovation and customer satisfaction are at the core of every decision.</p>
                        </div>
                    </div>

                    <div class="decom-team-member">
                        <div class="decom-member-image">
                            <img src="<?php echo base_url('Pictures/Epe.png'); ?>" alt="Team Member">
                        </div>
                        <div class="decom-member-info">
                            <p>Rolando Epe, the Co-Founder of Decomponents, brings complementary expertise in product development, working alongside Oxillo to turn the vision of Decomponents into a reality. With a deep understanding of Technology Engineering, Epe plays a crucial role in the day-to-day operations, overseeing strategic initiatives and fostering a culture of innovation. His focus on operational efficiency and user experience helps drive Decomponents’ success in building a platform that exceeds the expectations of customers and partners alike. Passionate about growth and collaboration, Epe continues to push the boundaries of what Decomponents can achieve in the future.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="decom-footer">
        <nav class="decom-footer-nav">
            <a href="<?php echo site_url('home'); ?>">HOME</a>
            <a href="<?php echo site_url('products'); ?>">PRODUCTS</a>
            <a href="<?php echo site_url('about'); ?>">ABOUT US</a>
            <a href="<?php echo site_url('tradables'); ?>">TRADE</a>
            <a href="<?php echo site_url('news'); ?>">NEWS</a>
            <a href="<?php echo site_url('contact'); ?>">CONTACT</a>
        </nav>
        <div class="decom-social-links">
            <a href="https://www.youtube.com/@DeComponents" target="_blank"><img src="<?php echo base_url('Pictures/Yutob.webp'); ?>" alt="YouTube"></a>
            <a href="https://www.facebook.com/profile.php?id=61568617385907" target="_blank"><img src="<?php echo base_url('Pictures/Fishbuk.webp'); ?>" alt="Facebook"></a>
            <a href="https://www.tiktok.com/@decomponents" target="_blank"><img src="<?php echo base_url('Pictures/Tiktook.png'); ?>" alt="TikTok"></a>
            <a href="https://www.linkedin.com/in/de-components-934ba3337/" target="_blank"><img src="<?php echo base_url('Pictures/linkedin.png'); ?>" alt="LinkedIn"></a>
            <a href="#" target="_blank"><img src="<?php echo base_url('Pictures/Instagram.png'); ?>" alt="Instagram"></a>
        </div>
        <div class="decom-contact-email">
            <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>
</html>
