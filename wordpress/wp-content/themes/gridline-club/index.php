<?php get_header(); ?>
<main id="main" class="site-main gl-container gl-content-shell">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'gl-article' ); ?>>
			<p class="gl-eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php the_excerpt(); ?>
		</article>
	<?php endwhile; the_posts_pagination(); else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'gridline-club' ); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>

