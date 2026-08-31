<?php
/**
 * Root Science-inspired single product layout.
 *
 * @package GridlineClub
 */

defined( 'ABSPATH' ) || exit;
global $product;

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$id          = $product->get_id();
$theme       = get_template_directory_uri();
$subtitle    = gridline_product_meta( $id, 'subtitle', wp_strip_all_tags( $product->get_short_description() ) );
$eyebrow     = gridline_product_meta( $id, 'eyebrow', __( 'Radio drop 01', 'gridline-club' ) );
$what        = gridline_product_meta( $id, 'what', wp_strip_all_tags( $product->get_description() ) );
$materials   = gridline_product_meta( $id, 'materials', __( 'See product description', 'gridline-club' ) );
$dimensions  = gridline_product_meta( $id, 'dimensions', __( 'See options above', 'gridline-club' ) );
$care        = gridline_product_meta( $id, 'care', __( 'Cold wash inside-out; line dry', 'gridline-club' ) );
$dispatch    = gridline_product_meta( $id, 'dispatch', __( 'Usually dispatches in 2–4 business days', 'gridline-club' ) );
$faq_lines   = gridline_lines( gridline_product_meta( $id, 'faq', "Is this official team merchandise? | No. APEXIK is an independent team radio culture brand.\nWhen will my order ship? | Most orders dispatch within 2–4 business days. Tracking is emailed when your parcel leaves.\nCan I return it? | Yes. Unused products can be returned within 30 days of delivery." ) );
$is_generic  = '1' === gridline_product_meta( $id, 'generic_product', '' );
$is_coming_soon = '1' === gridline_product_meta( $id, 'coming_soon', '' );
$is_water    = 'must-be-the-water-radio-polo' === $product->get_slug();
$product_img = $is_water ? $theme . '/assets/images/products/apexik-water-polo.png' : wp_get_attachment_image_url( $product->get_image_id(), 'full' );
$product_img = $product_img ?: wc_placeholder_img_src( 'woocommerce_single' );
$thumbs      = $is_water ? array(
	$theme . '/assets/images/products/apexik-water-polo-detail-front.png',
	$theme . '/assets/images/products/apexik-water-polo-detail-wave.png',
	$theme . '/assets/images/products/apexik-water-polo-detail-back.png',
) : array();
?>
<?php do_action( 'woocommerce_before_single_product' ); ?>
<article id="product-<?php the_ID(); ?>" <?php wc_product_class( 'gl-product-detail gl-rs-product', $product ); ?>>
	<div class="gl-product-top gl-rs-product-top">
		<div class="gl-product-gallery-wrap gl-rs-gallery" data-product-gallery>
			<?php if ( $is_water ) : ?>
				<div class="gl-rs-main-image"><img data-gallery-main src="<?php echo esc_url( $product_img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"></div>
				<div class="gl-rs-thumbnails" aria-label="<?php esc_attr_e( 'Product images', 'gridline-club' ); ?>">
					<button type="button" class="is-active" data-gallery-src="<?php echo esc_url( $product_img ); ?>" aria-label="<?php esc_attr_e( 'Full product view', 'gridline-club' ); ?>"><img src="<?php echo esc_url( $product_img ); ?>" alt=""></button>
					<?php foreach ( $thumbs as $index => $thumb ) : ?><button type="button" data-gallery-src="<?php echo esc_url( $thumb ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Product detail %d', 'gridline-club' ), $index + 1 ) ); ?>"><img src="<?php echo esc_url( $thumb ); ?>" alt=""></button><?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
			<?php endif; ?>
		</div>

		<div class="gl-product-summary gl-rs-summary">
			<p class="gl-kicker"><?php echo esc_html( $eyebrow ); ?></p>
			<h1 class="product_title entry-title"><?php the_title(); ?></h1>
			<?php woocommerce_template_single_rating(); ?>
			<div class="gl-rs-price"><?php woocommerce_template_single_price(); ?></div>
			<?php if ( $subtitle ) : ?><p class="gl-product-subtitle"><?php echo esc_html( $subtitle ); ?></p><?php endif; ?>
			<div class="gl-rs-rule"></div>
			<div class="gl-add-to-cart-wrap"><?php if ( $is_coming_soon ) : ?><div class="stock out-of-stock"><?php esc_html_e( 'Coming soon — join the launch list for release details.', 'gridline-club' ); ?></div><a class="single_add_to_cart_button button alt" href="<?php echo esc_url( home_url( '/#newsletter' ) ); ?>"><?php esc_html_e( 'Get launch updates', 'gridline-club' ); ?></a><?php else : ?><?php woocommerce_template_single_add_to_cart(); ?><?php endif; ?><?php if ( $product->is_type( 'variable' ) ) : ?><a class="gl-size-guide-link" href="<?php echo esc_url( home_url( '/size-guide/' ) ); ?>"><i class="fa-solid fa-shirt" aria-hidden="true"></i> <?php esc_html_e( 'Size guide', 'gridline-club' ); ?></a><?php endif; ?></div>
			<div class="gl-purchase-assurance"><div><i class="fa-solid fa-truck-fast" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Free US shipping over $75', 'gridline-club' ); ?></strong><?php echo esc_html( $dispatch ); ?></p></div><div><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Easy 30-day returns', 'gridline-club' ); ?></strong><?php esc_html_e( 'Unused items can be sent back within 30 days.', 'gridline-club' ); ?></p></div></div>
			<div class="gl-product-meta"><?php woocommerce_template_single_meta(); ?></div>
		</div>
	</div>

	<section class="gl-product-editorial gl-rs-story">
		<div class="gl-container gl-rs-story-inner">
			<div class="gl-editorial-copy"><p class="gl-eyebrow">01 / <?php echo esc_html( $is_generic ? __( 'The everyday moment', 'gridline-club' ) : __( 'The radio moment', 'gridline-club' ) ); ?></p><h2><?php echo esc_html( $is_generic ? __( 'The object that', 'gridline-club' ) : __( 'The call that', 'gridline-club' ) ); ?><br><em><?php echo esc_html( $is_generic ? __( 'solves the small stuff.', 'gridline-club' ) : __( 'changed the lap.', 'gridline-club' ) ); ?></em></h2><p><?php echo esc_html( $what ); ?></p></div>
			<div class="gl-editorial-media"><img src="<?php echo esc_url( $product_img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"></div>
		</div>
	</section>

	<section class="gl-how-it-works gl-rs-features"><div class="gl-container gl-feature-grid"><?php if ( $is_generic ) : ?><div><i class="fa-solid fa-wand-magic-sparkles gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'One clear action', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'Designed around a small transformation or tactile moment that is easy to understand.', 'gridline-club' ); ?></p></div><div><i class="fa-solid fa-cube gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'One integrated object', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'No complicated kit to pick, pack, or keep together.', 'gridline-club' ); ?></p></div><div><i class="fa-solid fa-gift gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'Made to give', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'Compact, useful, and designed to feel considered from the first use.', 'gridline-club' ); ?></p></div><?php else : ?><div><i class="fa-solid fa-wave-square gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'Radio wave detail', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'A quiet reference for people who know the moment without needing a team crest.', 'gridline-club' ); ?></p></div><div><i class="fa-solid fa-layer-group gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'Technical pique', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'Selected for breathability, shape retention, and race-weekend comfort.', 'gridline-club' ); ?></p></div><div><i class="fa-solid fa-gauge-high gl-feature-icon" aria-hidden="true"></i><h3><?php esc_html_e( 'Built for the full weekend', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'Easy from the garage to the stands and everywhere between sessions.', 'gridline-club' ); ?></p></div><?php endif; ?></div></section>

	<div class="gl-rs-info gl-container">
		<section class="gl-spec-section gl-rs-info-card"><h2><i class="fa-solid fa-shirt" aria-hidden="true"></i> <?php esc_html_e( 'Fit & care', 'gridline-club' ); ?></h2><div class="gl-accordion" data-accordion><div class="gl-accordion-item"><button type="button" aria-expanded="true"><span><?php esc_html_e( 'Fit', 'gridline-club' ); ?></span><i>+</i></button><div class="gl-accordion-panel"><p><?php echo esc_html( $dimensions ); ?></p></div></div><div class="gl-accordion-item"><button type="button" aria-expanded="false"><span><?php esc_html_e( 'Materials', 'gridline-club' ); ?></span><i>+</i></button><div class="gl-accordion-panel"><p><?php echo esc_html( $materials ); ?></p></div></div><div class="gl-accordion-item"><button type="button" aria-expanded="false"><span><?php esc_html_e( 'Care instructions', 'gridline-club' ); ?></span><i>+</i></button><div class="gl-accordion-panel"><p><?php echo esc_html( $care ); ?></p></div></div></div></section>
		<section class="gl-faq-section gl-rs-info-card"><h2><i class="fa-solid fa-truck-fast" aria-hidden="true"></i> <?php esc_html_e( 'Delivery & returns', 'gridline-club' ); ?></h2><div class="gl-accordion" data-accordion><?php foreach ( $faq_lines as $index => $line ) : $parts = array_map( 'trim', explode( '|', $line, 2 ) ); if ( 2 !== count( $parts ) ) { continue; } ?><div class="gl-accordion-item"><button type="button" aria-expanded="<?php echo 0 === $index ? 'true' : 'false'; ?>"><span><?php echo esc_html( $parts[0] ); ?></span><i>+</i></button><div class="gl-accordion-panel"><p><?php echo esc_html( $parts[1] ); ?></p></div></div><?php endforeach; ?></div></section>
	</div>

	<section class="gl-related gl-container"><div class="gl-section-heading"><div><p class="gl-eyebrow">02 / <?php echo esc_html( $is_generic ? __( 'Everyday carry', 'gridline-club' ) : __( 'Radio drop 01', 'gridline-club' ) ); ?></p><h2><?php echo esc_html( $is_generic ? __( 'More useful objects.', 'gridline-club' ) : __( 'More from the channel.', 'gridline-club' ) ); ?></h2></div></div><?php woocommerce_output_related_products( array( 'posts_per_page' => 4, 'columns' => 4 ) ); ?></section>

	<section class="gl-trust-ribbon gl-rs-bottom-trust"><div class="gl-container"><div><i class="fa-solid fa-gauge-high" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Everything under $50', 'gridline-club' ); ?></strong><?php esc_html_e( 'Radio-quote goods that do not miss.', 'gridline-club' ); ?></p></div><div><i class="fa-regular fa-paper-plane" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Independent design', 'gridline-club' ); ?></strong><?php esc_html_e( 'Original artwork. No sponsors, no noise.', 'gridline-club' ); ?></p></div><div><i class="fa-regular fa-clock" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Built for race weekends', 'gridline-club' ); ?></strong><?php esc_html_e( 'For the garage, stands, and beyond.', 'gridline-club' ); ?></p></div><div><i class="fa-solid fa-arrow-rotate-left" aria-hidden="true"></i><p><strong><?php esc_html_e( 'Easy 30-day returns', 'gridline-club' ); ?></strong><?php esc_html_e( 'No hassle on unused items.', 'gridline-club' ); ?></p></div></div></section>
</article>
<?php do_action( 'woocommerce_after_single_product' ); ?>
