<!DOCTYPE html>
<!--[if IE 7 ]>    <html class="isie ie7 oldie no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="isie ie8 oldie no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="isie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php is_dt_theme_moible_view(); ?>
	<meta name="author" content="delicious valencia"/>
    <meta name="p:domain_verify" content="1bab5b0c08ffad3010ba048226ddcb3e"/>

	<title><?php dt_theme_public_title(); ?></title>

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php

	global $dt_allowed_html_tags;
	#Load Theme Styles...
	if(dt_theme_option('integration', 'enable-header-code') != '') echo '<script type="text/javascript">'.wp_kses(stripslashes(dt_theme_option('integration', 'header-code')), $dt_allowed_html_tags).'</script>';
	wp_head();

	?>
	<script src="//code.tidio.co/1vej91zjynd8z2ppc4bpagbwmin5cjie.js"></script>

</head>

<body <?php if(dt_theme_option("appearance","layout") == "boxed") body_class('boxed'); else body_class(); ?>>
	<?php if(dt_theme_option('general','loading-bar') != "true") echo '<div class="cover"></div>'; ?>
	<div class="wrapper">
    	<div class="inner-wrapper">
        	<!-- header-wrapper starts here -->
        	<div id="header-wrapper">
	            <?php $htype = dt_theme_option('appearance','header_type'); $htype = !empty($htype) ? $htype : 'header1'; ?>
            	<header id="header" class="<?php echo esc_attr($htype); ?>">
                	<?php if(dt_theme_option('general','header-top-bar') != "true"): ?>
                        <!-- Top bar starts here -->
                        <div class="top-bar">
                            <div class="container">
                                <div class="float-left"><?php
									 echo wp_kses(do_shortcode(stripslashes(dt_theme_option('general', 'top-bar-left-content'))), $dt_allowed_html_tags); ?>
                                </div>
                                <div class="top-right">
                                    <ul><?php
                                    if(!is_user_logged_in()): ?>
                                        <li><a title="<?php _e('Login', 'iamd_text_domain'); ?>" href="<?php echo wp_login_url(get_permalink()); ?>">
                                                <span class="fa fa-sign-in"></span><?php _e('Login', 'iamd_text_domain'); ?>
                                            </a></li>
                                        <li><a title="<?php _e('Register Now', 'iamd_text_domain'); ?>" href="<?php echo wp_registration_url(); ?>">
                                                <span class="fa fa-user"></span> <?php _e('Register Now', 'iamd_text_domain'); ?>
                                            </a></li><?php
                                    else: ?>
                                        <li><a title="<?php _e('Logout', 'iamd_text_domain'); ?>" href="<?php echo wp_logout_url(get_permalink()); ?>">
                                                <span class="fa fa-sign-out"></span> <?php _e('Logout', 'iamd_text_domain'); ?>
                                            </a></li><?php
                                    endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- Top bar ends here -->
                    <?php endif; ?>
                    <div class="container">
                    	<div id="logo"><?php
							if( dt_theme_option('general', 'logo') ):
								$template_uri = get_template_directory_uri();
								$url = dt_theme_option('general', 'logo-url');
								$url = !empty( $url ) ? $url : $template_uri."/images/logo.png";

								$retina_url = dt_theme_option('general','retina-logo-url');
								$retina_url = !empty($retina_url) ? $retina_url : $template_uri."/images/logo@2x.png";

								$width = dt_theme_option('general','retina-logo-width');
								$width = !empty($width) ? $width."px;" : "234px";

								$height = dt_theme_option('general','retina-logo-height');
								$height = !empty($height) ? $height."px;" : "88px";?>
								<a href="<?php echo home_url();?>" title="<?php bloginfo('title'); ?>">
									<img class="normal_logo visible-lg" src="<?php echo esc_url($url);?>" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" />
									<img class="visible-xs" src="<?php echo esc_url($retina_url);?>" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" style="width:<?php echo esc_attr($width);?>; "/>
								</a><?php
							else: ?>
								<div class="logo-title">
									<h1 id="site-title"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('title'); ?>"><?php bloginfo('title'); ?></a></h1>
									<h2 id="site-description"><?php bloginfo('description'); ?></h2>
								</div><?php
							endif; ?>
						</div>
                        <div id="primary-menu">
                            <div class="dt-menu-toggle" id="dt-menu-toggle">
                                <?php //_e('Menu','iamd_text_domain');?>
								<?php
									if( dt_theme_option('general', 'logo') ):
										$template_uri = get_template_directory_uri();
										$url = dt_theme_option('general', 'logo-url');
										$url = !empty( $url ) ? $url : $template_uri."/images/logo.png";

										$retina_url = dt_theme_option('general','retina-logo-url');
										$retina_url = !empty($retina_url) ? $retina_url : $template_uri."/images/logo@2x.png";

										$width = dt_theme_option('general','retina-logo-width');
										$width = !empty($width) ? $width."px;" : "234px";

										$height = dt_theme_option('general','retina-logo-height');
										$height = !empty($height) ? $height."px;" : "88px";?>
										<a href="<?php echo home_url();?>" title="<?php bloginfo('title'); ?>">
											<img class="normal_logo visible-lg" src="<?php echo esc_url($url);?>" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" />
											<img class="visible-xs" src="<?php echo esc_url($retina_url);?>" alt="<?php bloginfo('title'); ?>" title="<?php bloginfo('title'); ?>" style="width:<?php echo esc_attr($width);?>; ?>;"/>
										</a><?php
									else: ?>
										<div class="logo-title">
											<h1 id="site-title"><a href="<?php echo home_url(); ?>" title="<?php bloginfo('title'); ?>"><?php bloginfo('title'); ?></a></h1>
											<h2 id="site-description"><?php bloginfo('description'); ?></h2>
										</div><?php
									endif; ?>
								<span class="dt-menu-toggle-icon"></span>
                            </div>
							<ul class="menu-idioma">
                                <li class="qts_lang_item">
                                    <a href="https://kiosk.eztix.co/kiosk-gift/269824" class="qts_both qtrans_flag" target="_blank"><?php _e('GIFT VOUCHER'); ?></a>
                                    &nbsp;&nbsp;
                                </li>
                                <li class="qts_lang_item">
                                    <?php _e('<a href="http://www.valenciafoodtourspain.com/en/about-me/" class="qts_both qtrans_flag">ABOUT ME</a>'); ?>&nbsp;&nbsp;
                                </li>
                                <li class="qts_lang_item"><a href="http://www.valenciafoodtourspain.com/en/" lang="en" hreflang="en" class="qts_both qtrans_flag qtrans_flag_en"><img width="18" height="12" src="http://www.valenciafoodtourspain.com/wp-content/plugins/qtranslate-x/flags/gb.png" alt="English">English</a></li>
								<li class="qts_lang_item last-child"><a href="http://www.valenciafoodtourspain.com/ru/" lang="ru" hreflang="ru" class="qts_both qtrans_flag qtrans_flag_ru"><img width="18" height="12" src="http://www.valenciafoodtourspain.com/wp-content/plugins/qtranslate-x/flags/ru.png" alt="Русский">Русский</a></li>
							</ul>
                        	<nav id="main-menu">

								<?php

                                if( is_page_template('tpl-onepage.php') ):
                                    $meta = get_post_meta($post->ID, '_tpl_default_settings', true);
                                    $cmenu = "<li class='menu-item menu-item-type-post_type menu-item-object-page'><a href='".home_url()."/#".$post->post_name."'>".__('Home', 'iamd_text_domain')."</a></li>";
									//wp_nav_menu( array('menu' => $meta['onepage_menu'], 'container'  => false, 'menu_id' => 'menu-main-menu', 'menu_class' => 'onepage_menu menu', 'fallback_cb' => 'dt_theme_default_navigation', 'walker' => new DTOnePageMenuWalker(), 'items_wrap' => '<ul id="%1$s" class="%2$s">'.$cmenu.'%3$</ul>'));
									wp_nav_menu( array('menu' => $meta['onepage_menu'], 'container'  => false, 'menu_id' => 'menu-main-menu', 'menu_class' => 'onepage_menu menu', 'fallback_cb' => 'dt_theme_default_navigation', 'walker' => new DTOnePageMenuWalker(), 'items_wrap' => '<ul id="%1$s" class="%2$s">'.$cmenu.'%3$</ul>'));
                                else:
									//wp_nav_menu( array('theme_location' => 'primary-menu', 'container'  => false, 'menu_id' => 'menu-main-menu', 'menu_class' => 'menu', 'fallback_cb' => 'dt_theme_default_navigation', 'walker' => new DTFrontEndMenuWalker()));
									//wp_nav_menu( 'theme_location' => 'primary-menu', 'container'  => false, 'menu_id' => 'menu-main-menu', 'menu_class' => 'menu', 'fallback_cb' => 'dt_theme_default_navigation', 'walker' => new DTFrontEndMenuWalker()));
									//wp_nav_menu( array( 'items_wrap' => '<ul id="%1$s" class="%2$s"><li><a href="http://www.google.com">go to google</a></li>%3$s</ul>' ) );
									wp_nav_menu( array('theme_location' => 'primary-menu', 'container'  => false, 'menu_id' => 'menu-main-menu', 'menu_class' => 'menu', 'fallback_cb' => 'dt_theme_default_navigation', 'walker' => new DTFrontEndMenuWalker(), 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s<li class="%2$s hide-desktop"><a href="https://kiosk.eztix.co/kiosk-gift/269824" class="qts_both qtrans_flag" target="_blank">'.__("GIFT VOUCHER").'</a></li><li class="%2$s hide-desktop">'.__('<a href="http://www.valenciafoodtourspain.com/en/about-me/" class="qts_both qtrans_flag">ABOUT ME</a>').'</li><li class="%2$s hide-desktop"><a href="http://www.valenciafoodtourspain.com/en/" lang="en" hreflang="en" class="qts_both-mobile qtrans_flag qtrans_flag_en">English <img width="18" height="12" src="http://www.valenciafoodtourspain.com/wp-content/plugins/qtranslate-x/flags/gb.png" alt="English"></a></li><li class="%2$s hide-desktop"><a href="http://www.valenciafoodtourspain.com/ru/" lang="ru" hreflang="ru" class="qts_both-mobile qtrans_flag qtrans_flag_ru">Русский <img width="18" height="12" src="http://www.valenciafoodtourspain.com/wp-content/plugins/qtranslate-x/flags/ru.png" alt="Русский"></a></li></ul>' ));

								endif; ?>

                            </nav>
                        </div>
                    </div>
				</header>
			</div>
			<!-- header-wrapper ends here -->
