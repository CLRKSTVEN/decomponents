<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Submission - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
</head>

<body>
    <header class="decom-header">
        <div class="decom-container">
            <img src="<?php echo base_url('Pictures/DeComponents.jpeg'); ?>" alt="DeComponents Logo" class="decom-logo">
            <nav class="decom-nav">
                <div class="decom-container">
                    <a href="<?php echo site_url("home"); ?>" class="decom-nav-link">HOME</a>
                    <a href="<?php echo site_url("products"); ?>" class="decom-nav-link">PRODUCTS</a>
                    <a href="<?php echo site_url("about"); ?>" class="decom-nav-link">ABOUT US</a>
                    <a href="<?php echo site_url("tradables"); ?>" class="decom-nav-link">TRADABLES</a>
                    <a href="<?php echo site_url("news"); ?>" class="decom-nav-link">NEWS</a>
                    <a href="<?php echo site_url("contact"); ?>" class="decom-nav-link">CONTACT</a>
                    <a href="<?php echo site_url("contact"); ?>" class="decom-nav-link">lOGIN / SIGN UP </a>
                    <a href="<?php echo site_url("products"); ?>" class="decom-nav-link">
                        <img src="<?php echo base_url('Pictures/'); ?> alt=" Cart" class="decom-cart-icon">
                    </a>
                </div>
            </nav>
        </div>
    </header>

    <main class="decom-main">
        <div class="decom-container">
            <div class="decom-product-submission">
                <form class="decom-product-form">
                    <div class="decom-image-upload">
                        <label for="product-image">Upload Picture Here</label>
                        <input type="file" id="product-image" accept="image/*" class="decom-file-input">
                    </div>

                    <div class="decom-form-group">
                        <input type="text" placeholder="Product Name:" class="decom-form-input">
                    </div>

                    <div class="decom-form-group">
                        <input type="text" placeholder="Price:" class="decom-form-input">
                    </div>

                    <div class="decom-form-group">
                        <input type="text" placeholder="Condition:" class="decom-form-input">
                    </div>

                    <div class="decom-form-group">
                        <input type="text" placeholder="Category:" class="decom-form-input">
                    </div>

                    <div class="decom-form-group">
                        <textarea placeholder="Description:" class="decom-form-textarea"></textarea>
                    </div>
                    <a href="<?php echo site_url("tradables"); ?>" class="decom-button decom-button-cancel">Cancel</a>
                    <a href="<?php echo site_url("notification"); ?>" class="decom-button decom-button-submit" target="_blank">Submit</a>
            </div>
            </form>
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