<?php
/**
 * Product archive and product category layout.
 *
 * @package GridlineClub
 */

defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
do_action( 'woocommerce_before_main_content' );

$is_category = is_product_category();
$title       = $is_category ? single_term_title( '', false ) : __( 'The Radio Grid', 'gridline-club' );
$description = $is_category ? term_description() : __( 'Authorized team radio phrases on apparel, drinkware, desk gear, small gifts, and lightweight trackside essentials—all under $50.', 'gridline-club' );
?>
<header class="gl-shop-hero">
	<p class="gl-eyebrow"><?php echo $is_category ? esc_html__( 'Collection', 'gridline-club' ) : esc_html__( 'Shop all', 'gridline-club' ); ?> / <?php echo esc_html( wp_count_posts( 'product' )->publish ); ?> <?php esc_html_e( 'objects', 'gridline-club' ); ?></p>
	<h1><?php echo esc_html( $title ); ?></h1>
	<?php if ( $description ) : ?><div class="gl-shop-intro"><?php echo wp_kses_post( $description ); ?></div><?php endif; ?>
	<div class="gl-shop-links" aria-label="<?php esc_attr_e( 'Shop categories', 'gridline-club' ); ?>">
		<a<?php echo ! $is_category ? ' class="is-current"' : ''; ?> href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'All products', 'gridline-club' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/product-category/radio-apparel/' ) ); ?>"><?php esc_html_e( 'Apparel', 'gridline-club' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/product-category/drinkware/' ) ); ?>"><?php esc_html_e( 'Drinkware', 'gridline-club' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/product-category/desk-tech/' ) ); ?>"><?php esc_html_e( 'Desk gear', 'gridline-club' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/product-category/race-day-gear/' ) ); ?>"><?php esc_html_e( 'Trackside', 'gridline-club' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/product-category/small-gifts/' ) ); ?>"><?php esc_html_e( 'Accessories', 'gridline-club' ); ?></a>
	</div>
</header>

<?php if ( woocommerce_product_loop() ) : ?>
	<div class="gl-shop-toolbar">
		<?php do_action( 'woocommerce_before_shop_loop' ); ?>
	</div>
	<?php woocommerce_product_loop_start(); ?>
	<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php do_action( 'woocommerce_shop_loop' ); ?>
			<?php wc_get_template_part( 'content', 'product' ); ?>
		<?php endwhile; ?>
	<?php endif; ?>
	<?php woocommerce_product_loop_end(); ?>
	<?php do_action( 'woocommerce_after_shop_loop' ); ?>
<?php else : ?>
	<?php do_action( 'woocommerce_no_products_found' ); ?>
<?php endif; ?>

<?php
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );
