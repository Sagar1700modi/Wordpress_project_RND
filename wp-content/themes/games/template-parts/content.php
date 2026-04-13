<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Games
 */
$fields = get_fields();
$show_sidebar = get_field('show_sidebar_posts', 'option');
$custom_excerpt =  $fields['page_top_intro_content'] ?? '';
?>
<div class="page-wrapper">
	<div class="main-content-wrapper">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php
				if (function_exists('yoast_breadcrumb')) {
					yoast_breadcrumb('<div id="breadcrumbs" class="breadcrumbs">', '</div>');
				}
				if ( is_singular() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				endif;
				if ($custom_excerpt) : ?>
				<div class="post-exerpt">
					<div class="desktop-open">
						<div class="excerpt-toggle-wrapper">
							<!-- EXCERPT (initially collapsed) -->
							<div class="post-excerpt" data-full-text="<?php
																		preg_match('/<p[^>]*>(.*?)<\/p>/s', $custom_excerpt, $matches);
																		$first_paragraph = isset($matches[1]) ? $matches[1] : '';
																		echo esc_attr(wp_strip_all_tags($first_paragraph)); ?>">
								<?php echo wp_kses_post($custom_excerpt); ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif;?>
				<div class="author-published-wrapper">
					<div class="published-last-update-wrapper">
						<div class="published-date-wrapper">
							<span class="label">
								<?php esc_html_e('Published on', 'games'); ?>
							</span>
							<span class="date">
								<?php echo esc_html(get_the_date('F j, Y')); ?>
							</span>
						</div>
						<div class="last-update-wrapper">
							<span class="label">
								<?php esc_html_e('Last updated on', 'games'); ?>
							</span>
							<span class="date">
								<?php echo esc_html(get_the_modified_date('F j, Y')); ?>
							</span>
						</div>
					</div>
					<?php
					$author_id   = get_post_field('post_author', get_the_ID());
					$author_name = get_the_author_meta('display_name', $author_id);
					$author_url  = get_author_posts_url($author_id);
					$author_avatar = get_avatar_url($author_id, ['size' => 80, 'default' => 'mystery']);
					$user = get_userdata($author_id);
					$author_role = '';

					if ( $user && ! empty($user->roles) ) {
						$author_role = ucfirst($user->roles[0]); // Author, Editor, etc.
					}
					?>
					<div class="author-wrapper">
						<div class="author-img">
							<a href="<?php echo esc_url($author_url); ?>">
								<img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">
							</a>
						</div>
						<div class="author-name">
							<a href="<?php echo esc_url($author_url); ?>" class="author-name">
								<?php echo esc_html($author_name); ?>
							</a>
							<div class="author-role">
								<?php echo $author_role; ?>
							</div>
						</div>
					</div>
					<?php
					$fact_author_id = $fields['page_fact_author'] ?? '';
					if ($fact_author_id) :
						$fact_author_name   = get_the_author_meta('display_name', $fact_author_id);
						$fact_author_url    = get_author_posts_url($fact_author_id);
						$fact_author_avatar = get_avatar_url($fact_author_id, ['size' => 80, 'default' => 'mystery']);
					?>
					<div class="fact-checker-wrapper"> 
						<div class="fact-checker-img">
							<a href="<?php echo esc_url($fact_author_url); ?>">
								<img src="<?php echo esc_url($fact_author_avatar); ?>" alt="<?php echo esc_attr($fact_author_name); ?>">
							</a>
						</div>
						<div class="fact-checker-name-wrapper">
							<div class="fact-checker-name">
								<a href="<?php echo esc_url($fact_author_url); ?>" class="author-name">
									<?php echo esc_html($fact_author_name); ?>
								</a>
							</div>
							<div class="author-checked">
								<span class="author-checked"><?php esc_html_e('Fact Checked', 'games'); ?><i></i></span>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>

			</header><!-- .entry-header -->

			<?php games_post_thumbnail(); ?>

			<div class="entry-content single-post-left">
				<?php
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'games' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'games' ),
						'after'  => '</div>',
					)
				);
				?>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> --> 
	</div>
	<?php
    if ($show_sidebar) {?>
    <div class="sidebar-wrapper">
        <?php dynamic_sidebar('post-sidebar');?>
    </div>
    <?php    
    }
    ?>
</div>