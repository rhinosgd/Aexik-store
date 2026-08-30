<?php
/**
 * Product search results.
 *
 * @package GridlineClub
 */
get_header();
?>
<main id="main" class="site-main gl-content-shell gl-search-results"><div class="gl-container">
	<header class="gl-search-results-header">
		<p class="gl-eyebrow"><?php esc_html_e( 'Product search', 'gridline-club' ); ?></p>
		<h1><?php printf( esc_html__( 'Results for “%s”', 'gridline-club' ), esc_html( get_search_query() ) ); ?></h1>
		<?php get_search_form(); ?>
	</header>
	<?php if ( have_posts() ) : ?>
		<?php if ( function_exists( 'woocommerce_product_loop_start' ) ) : ?>
			<?php woocommerce_product_loop_start(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
			<?php woocommerce_product_loop_end(); ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<div class="gl-prose"><?php while ( have_posts() ) : the_post(); ?><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php endwhile; ?></div>
		<?php endif; ?>
	<?php else : ?>
		<section class="gl-empty-state"><span>0</span><h2><?php esc_html_e( 'No result on the timing screen.', 'gridline-club' ); ?></h2><p><?php esc_html_e( 'Try a shorter term, browse all products, or look through the gift collection.', 'gridline-club' ); ?></p><div><a class="gl-button gl-button--dark" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop all', 'gridline-club' ); ?> →</a><a class="gl-text-link" href="<?php echo esc_url( home_url( '/product-category/gifts/' ) ); ?>"><?php esc_html_e( 'Browse gifts', 'gridline-club' ); ?> →</a></div></section>
	<?php endif; ?>
</div></main>
<?php get_footer(); ?>

