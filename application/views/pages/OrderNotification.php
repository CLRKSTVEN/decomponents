<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Uploaded - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main-notification">
        <div class="decom-container">
            <div class="decom-notification">
                Product has been ordered, expected delivery: December 25, 2024
            </div>
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
            <a href="https://www.youtube.com/@DeComponents" target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.facebook.com/profile.php?id=61568617385907"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.tiktok.com/@decomponents"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" https://www.linkedin.com/in/de-components-934ba3337/"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
            <a href=" #"target="_blank"><img src="<?php echo base_url('Pictures/'); ?>></a>
        </div>
        <div class=" decom-contact-email">
                <a href="mailto:decomponents.24@gmail.com">DeComponents.24@Gmail.com</a>
        </div>
        <p class="decom-copyright">© 2024 by DeComponents. All Rights Reserved.</p>
    </footer>
</body>

</html>
