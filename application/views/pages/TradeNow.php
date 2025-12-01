<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradables - DeComponents</title>
    <link rel="stylesheet" href="<?php echo base_url('design.css'); ?>">
</head>

<body>
    <?php $this->load->view('partials/navbar'); ?>

    <main class="decom-main">
        <div class="decom-container">
            <div class="decom-trade-form">
                <div class="decom-trade-section">
                    <div class="decom-trade-image">
                        <img src="<?php echo base_url('Pictures/'); ?> alt=" RTX 4070">
                    </div>
                    <div class="decom-trade-details">
                        <input type="text" value="GeForce RTX 4090" class="decom-form-input" readonly>
                        <input type="text" value="Fairly Used - 6 months" class="decom-form-input" readonly>
                        <input type="text" value="Graphics Card" class="decom-form-input" readonly>
                        <input type="text" value="None" class="decom-form-input" readonly>
                    </div>
                </div>

                <div class="decom-trade-section">
                    <div class="decom-trade-details">
                        <input type="text" placeholder="Product Name:" class="decom-form-input">
                        <input type="text" placeholder="Condition:" class="decom-form-input">
                        <input type="text" placeholder="Category:" class="decom-form-input">
                        <input type="text" placeholder="Specific Conditions:" class="decom-form-input">
                    </div>
                    <div class="decom-trade-upload">
                        <label for="trade-image">Upload Picture Here</label>
                        <input type="file" id="trade-image" accept="image/*">
                    </div>
                </div>

                <div>
                    <a href="<?php echo site_url("tradables"); ?>" class="decom-button decom-button-cancel">Cancel</a>
                    <a href="<?php echo site_url("notification"); ?>" class="decom-button decom-button-submit" target="_blank">Submit</a>
                </div>
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
