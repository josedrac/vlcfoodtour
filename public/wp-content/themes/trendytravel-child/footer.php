            <!-- footer starts here -->
            <?php global $dt_allowed_html_tags; ?>
            <footer id="footer">
				<?php if(dt_theme_option('general', 'show-footer')): ?>
                    <div class="footer-widgets-wrapper type2">
                        <div class="container"><?php dt_theme_show_footer_widgetarea(dt_theme_option('general','footer-columns')); ?></div>
                    </div>
                <?php endif; ?>

                <?php if(dt_theme_option('general','bottom-content') != '' && dt_theme_option('general', 'show-footer-bottom')): ?>
                    <div class="footer-row2">
                        <div class="container">
                            <?php echo wp_kses(do_shortcode(stripslashes(dt_theme_option('general','bottom-content'))), $dt_allowed_html_tags); ?>
                        </div>
                    </div>
                <?php endif; ?>

            	<div class="copyright type2">
                	<div class="container"><?php
						if(dt_theme_option('general','community-status') != ''): ?>
                            <div class="foot-site-status">
                            	<?php echo wp_kses(do_shortcode(stripslashes(dt_theme_option('general','community-status'))), $dt_allowed_html_tags); ?>
                            </div><?php
						endif; ?>
                        <?php if(dt_theme_option('general','show-copyrighttext') == "on"): ?>
                            <div class="copyright-content">
                                <p><?php echo wp_kses(stripslashes(dt_theme_option('general','copyright-text')), $dt_allowed_html_tags); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </footer>
            <!-- footer ends here -->
		</div>
    </div>
<?php if(dt_theme_option('integration', 'enable-body-code') != '') echo '<script type="text/javascript">'.wp_kses(stripslashes(dt_theme_option('integration', 'body-code')), $dt_allowed_html_tags).'</script>';
wp_footer(); ?>
</body>
</html>
