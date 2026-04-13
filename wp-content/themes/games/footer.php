<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Games
 */
if (
    is_active_sidebar('footer-widget-1') ||
    is_active_sidebar('footer-widget-2') ||
    is_active_sidebar('footer-widget-3') ||
    is_active_sidebar('footer-widget-4')
) : ?>

    <div class="footer-widgets">
        <div class="footer-widgets-inner">
            <div class="container">
                <div class="row">
                    <div class="footer-column col-12 col-xl-3 col-lg-6 col-md-6">
                        <?php if (is_active_sidebar('footer-widget-1')) : ?>
                            <?php dynamic_sidebar('footer-widget-1'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="footer-column col-12 col-xl-3 col-lg-6 col-md-6">
                        <?php if (is_active_sidebar('footer-widget-2')) : ?>
                            <?php dynamic_sidebar('footer-widget-2'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="footer-column col-12 col-xl-3 col-lg-6 col-md-6">
                        <?php if (is_active_sidebar('footer-widget-3')) : ?>
                            <?php dynamic_sidebar('footer-widget-3'); ?>
                        <?php endif; ?>
                    </div>
                    <div class="footer-column col-12 col-xl-3 col-lg-6 col-md-6">
                        <?php if (is_active_sidebar('footer-widget-4')) : ?>
                            <?php dynamic_sidebar('footer-widget-4'); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-xl-12 col-lg-12 col-md-12  d-flex justify-content-center">
                        <?php if (is_active_sidebar('footer-copyright')) : ?>
                            <?php dynamic_sidebar('footer-copyright'); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <button id="scrollTopBtn" aria-label="Go to top"></button>

<?php endif; ?>
</div><!-- #page -->
<?php wp_footer(); ?>

</body>

</html>