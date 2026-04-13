<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Games
 */

?>

		<section class="no-results not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'games' ); ?></h1>
			</header>

			<div class="page-content">
				<?php
				if ( is_home() && current_user_can( 'publish_posts' ) ) :

					printf(
						'<p>' . wp_kses(
							__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'games' ),
							array(
								'a' => array(
									'href' => array(),
								),
							)
						) . '</p>',
						esc_url( admin_url( 'post-new.php' ) )
					);

				elseif ( is_search() ) :
					?>

					<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'games' ); ?></p>
					
					<div class="search-result-page-wrapper live-search-wrapper">
						<?php get_search_form();?>
						<div id="live-search-results" class="live-search-results"></div>
					</div>
					<?php
				else :
					?>

					<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'games' ); ?></p>
					<div class="search-result-page-wrapper live-search-wrapper">
						<?php get_search_form();?>
						<div id="live-search-results" class="live-search-results"></div>
					</div>
					<?php

				endif;
				?>
			</div><!-- .page-content -->
		</section><!-- .no-results -->
	