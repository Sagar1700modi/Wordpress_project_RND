<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Games
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site page-header-wrapper">

		<header id="masthead" class="site-header">
			<div class="container">
				<div class="header-wrapper">
					<div class="site-branding">
						<?php
						the_custom_logo();
						$games_description = get_bloginfo('description', 'display');
						if ($games_description || is_customize_preview()) :
						?>
						<p class="site-description"><?php echo $games_description; ?></p>
						<?php endif; ?>
					</div><!-- .site-branding -->

					<div class="nav-search-wrapper">
						<div class="nav-wrapper">
							<div class="menu-toggle" id="menuToggle">
								<span></span>
								<span></span>
								<span></span>
							</div>

							<div class="menu-overlay" id="menuOverlay"></div>
							<nav id="site-navigation" class="main-navigation">

								<?php if (has_nav_menu('menu-1')) : ?>

									<?php
									wp_nav_menu(array(
										'theme_location' => 'menu-1',
										'menu_id'        => 'primary-menu',
										'container'      => false,
										'menu_class'     => 'games-main-menu',
										'walker'         => new Games_Menu_Walker(),
									));
									?>

								<?php endif; ?>
								
							</nav>

						</div>
						<div class="search-wrapper ">
							<!-- Search Box -->
							<button class="search-open-btn" aria-label="Open Search" id="toggleBtn">
								<svg width="22" height="22" viewBox="0 0 20 20" fill="none">
									<circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="2" stroke="#051135" />
									<line x1="14.5" y1="14.5" x2="19" y2="19" stroke="currentColor" stroke-width="2" stroke="#051135" />
								</svg>
								<span><?php esc_html_e('Search', 'gaming-america'); ?></span>
							</button>

							<div class="search-result-form-wrapper live-search-wrapper header-live-search" id="slideBox">
								<div class="form-wrapper">
									<?php get_search_form(); ?>
									<button type="button" class="search-close-btn" aria-label="Close Search"> Close </button>
								</div>
								<div id="live-search-results" class="live-search-results"></div>
							</div>
							
						</div>
						
					</div>

		</header>

	</div>