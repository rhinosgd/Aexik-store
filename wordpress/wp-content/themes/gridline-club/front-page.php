<?php
/**
 * Front page.
 *
 * @package GridlineClub
 */
get_header();
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$theme    = get_template_directory_uri();
$home     = function_exists( 'gridline_home_settings' ) ? gridline_home_settings() : array();
$home     = wp_parse_args( $home, array( 'hero_channel' => 'Radio drop 01', 'hero_title' => 'Heard it. Wear it.', 'hero_button' => 'Shop radio drop', 'hero_button_url' => '', 'categories_eyebrow' => '01 / Choose your channel', 'categories_title' => 'Radio moments, made useful.', 'products_eyebrow' => '02 / Radio drop 01', 'products_title' => 'Shop the radio drop.', 'story_eyebrow' => '03 / Our point of view', 'story_title' => "Not team merch.\nRadio culture.", 'story_text' => 'We take authorized team radio phrases and rebuild them through Apexik waveforms, channel codes, signal blocks, and products fans can use every day.', 'newsletter_title' => 'Get the next quote before lights out.' ) );
$hero_url = $home['hero_button_url'] ? $home['hero_button_url'] : $shop_url;
?>
<main id="main" class="site-main">
	<section class="gl-hero">
		<img class="gl-hero-scene" src="<?php echo esc_url( $theme . '/assets/images/apexik-hero-racing-red-v2.png' ); ?>" alt="<?php esc_attr_e( 'Designed red team radio polo with an anonymous red open-wheel racing car sweeping past', 'gridline-club' ); ?>">
		<div class="gl-container gl-hero-inner">
			<div class="gl-hero-copy">
				<p class="gl-hero-channel"><?php echo esc_html( $home['hero_channel'] ); ?></p>
				<div class="gl-hero-wordmark" aria-label="APEXIK"><span>APEX</span><em>IK</em></div>
				<h1><?php echo esc_html( $home['hero_title'] ); ?></h1>
				<div class="gl-button-row"><a class="gl-button gl-button--light" href="<?php echo esc_url( $hero_url ); ?>"><?php echo esc_html( $home['hero_button'] ); ?><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
			</div>
		</div>
	</section>

	<div class="gl-marquee" aria-label="Radio quote collection"><div><span>BOX BOX</span><i>◆</i><span>STAY OUT</span><i>◆</i><span>GP2 ENGINE</span><i>◆</i><span>SIMPLY LOVELY</span><i>◆</i><span>MUST BE THE WATER</span><i>◆</i><span>RADIO CHECK</span></div></div>

	<nav class="gl-category-rail" aria-label="<?php esc_attr_e( 'Shop categories', 'gridline-club' ); ?>">
		<div class="gl-container"><a href="<?php echo esc_url( home_url( '/product-category/radio-apparel/' ) ); ?>"><i class="fa-solid fa-shirt" aria-hidden="true"></i><?php esc_html_e( 'Apparel', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/drinkware/' ) ); ?>"><i class="fa-solid fa-mug-hot" aria-hidden="true"></i><?php esc_html_e( 'Drinkware', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/desk-tech/' ) ); ?>"><i class="fa-solid fa-laptop" aria-hidden="true"></i><?php esc_html_e( 'Desk gear', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/race-day-gear/' ) ); ?>"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><?php esc_html_e( 'Trackside', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/small-gifts/' ) ); ?>"><i class="fa-solid fa-key" aria-hidden="true"></i><?php esc_html_e( 'Accessories', 'gridline-club' ); ?></a></div>
	</nav>

	<section class="gl-section gl-categories">
		<div class="gl-container">
			<div class="gl-section-heading"><div><p class="gl-eyebrow"><?php echo esc_html( $home['categories_eyebrow'] ); ?></p><h2><?php echo esc_html( $home['categories_title'] ); ?></h2></div><a class="gl-text-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Shop everything', 'gridline-club' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
			<div class="gl-category-grid">
				<a class="gl-category-card gl-category-card--large" href="<?php echo esc_url( home_url( '/product-category/radio-apparel/' ) ); ?>"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-gp2-hoodie.jpg' ); ?>" alt=""><span>01</span><div><p><?php esc_html_e( 'Radio apparel', 'gridline-club' ); ?></p><strong><?php esc_html_e( 'Wear the channel.', 'gridline-club' ); ?></strong></div></a>
				<a class="gl-category-card" href="<?php echo esc_url( home_url( '/product-category/drinkware/' ) ); ?>"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-simply-tumbler.jpg' ); ?>" alt=""><span>02</span><div><p><?php esc_html_e( 'Drinkware', 'gridline-club' ); ?></p><strong><?php esc_html_e( 'For early starts and long races.', 'gridline-club' ); ?></strong></div></a>
				<a class="gl-category-card" href="<?php echo esc_url( home_url( '/product-category/race-day-gear/' ) ); ?>"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-viewing-kit.jpg' ); ?>" alt=""><span>03</span><div><p><?php esc_html_e( 'Race day gear', 'gridline-club' ); ?></p><strong><?php esc_html_e( 'Carry less. Watch longer.', 'gridline-club' ); ?></strong></div></a>
			</div>
		</div>
	</section>

	<section class="gl-section gl-products-section">
		<div class="gl-container">
			<div class="gl-section-heading"><div><p class="gl-eyebrow"><?php echo esc_html( $home['products_eyebrow'] ); ?></p><h2><?php echo esc_html( $home['products_title'] ); ?></h2></div><a class="gl-text-link" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'View all', 'gridline-club' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<?php echo do_shortcode( '[products limit="4" columns="4" orderby="date" order="DESC" visibility="visible"]' ); ?>
			<?php else : ?>
				<div class="gl-product-placeholder"><p><?php esc_html_e( 'Activate WooCommerce and the APEXIK Starter plugin to display the product collection.', 'gridline-club' ); ?></p></div>
			<?php endif; ?>
		</div>
	</section>

	<section id="story" class="gl-story-split">
		<div class="gl-story-media"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-water-polo-v2.png' ); ?>" alt="<?php esc_attr_e( 'MUST BE THE WATER red radio polo with racing speed graphics', 'gridline-club' ); ?>"><div class="gl-story-stamp"><span><?php esc_html_e( 'Built from', 'gridline-club' ); ?></span><strong><?php esc_html_e( 'THE RADIO', 'gridline-club' ); ?></strong></div></div>
		<div class="gl-story-copy"><p class="gl-eyebrow"><?php echo esc_html( $home['story_eyebrow'] ); ?></p><h2><?php echo nl2br( esc_html( $home['story_title'] ) ); ?></h2><p class="gl-lead"><?php echo esc_html( $home['story_text'] ); ?></p><div class="gl-principles"><div><span>01</span><p><strong><?php esc_html_e( 'The phrase is the hero', 'gridline-club' ); ?></strong><?php esc_html_e( 'The moment stays recognizable without a team crest or driver name.', 'gridline-club' ); ?></p></div><div><span>02</span><p><strong><?php esc_html_e( 'Everything under $50', 'gridline-club' ); ?></strong><?php esc_html_e( 'Easy gifts, wearable references, and practical trackside gear.', 'gridline-club' ); ?></p></div><div><span>03</span><p><strong><?php esc_html_e( 'Independent by design', 'gridline-club' ); ?></strong><?php esc_html_e( 'No copied liveries, sponsor graphics, or official series branding.', 'gridline-club' ); ?></p></div></div><a class="gl-button gl-button--dark" href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'Read our story', 'gridline-club' ); ?> <span>→</span></a></div>
	</section>

	<section class="gl-section gl-gift-guide">
		<div class="gl-container">
			<div class="gl-section-heading gl-section-heading--center"><div><p class="gl-eyebrow">04 / <?php esc_html_e( 'Gift guide', 'gridline-club' ); ?></p><h2><?php esc_html_e( 'Radio-ready gifts.', 'gridline-club' ); ?></h2></div></div>
			<div class="gl-gift-grid"><a href="<?php echo esc_url( add_query_arg( 'max_price', '25', $shop_url ) ); ?>"><span><?php esc_html_e( 'Under', 'gridline-club' ); ?></span><strong>$25</strong><small><?php esc_html_e( 'Mugs & small gifts', 'gridline-club' ); ?> →</small></a><a href="<?php echo esc_url( add_query_arg( 'max_price', '40', $shop_url ) ); ?>"><span><?php esc_html_e( 'Under', 'gridline-club' ); ?></span><strong>$40</strong><small><?php esc_html_e( 'Desk & travel', 'gridline-club' ); ?> →</small></a><a href="<?php echo esc_url( home_url( '/product-category/race-day-gear/' ) ); ?>"><span><?php esc_html_e( 'For the', 'gridline-club' ); ?></span><strong><?php esc_html_e( 'TRACKSIDE', 'gridline-club' ); ?></strong><small><?php esc_html_e( 'Viewing essentials', 'gridline-club' ); ?> →</small></a></div>
		</div>
	</section>

	<section class="gl-section gl-testimonials">
		<div class="gl-container"><div class="gl-section-heading"><div><p class="gl-eyebrow">05 / <?php esc_html_e( 'The radio archive', 'gridline-club' ); ?></p><h2><?php esc_html_e( 'You heard every word.', 'gridline-club' ); ?></h2></div><p class="gl-heading-note"><?php esc_html_e( 'No names needed. If you know, you know.', 'gridline-club' ); ?></p></div><div class="gl-review-grid"><blockquote><div>RADIO // 01</div><p>“MUST BE<br>THE WATER.”</p><footer>PACE EXPLAINED <span>00:02</span></footer></blockquote><blockquote><div>RADIO // 02</div><p>“BOX BOX.”<br>“STAY OUT, STAY OUT.”</p><footer>STRATEGY REVERSAL <span>00:03</span></footer></blockquote><blockquote><div>RADIO // 03</div><p>“CHANGE YOUR<br>FXXKING CAR.”</p><footer>RADIO UNFILTERED <span>00:02</span></footer></blockquote></div></div>
	</section>

	<section class="gl-newsletter-panel">
		<div class="gl-container gl-newsletter-panel-inner"><div><p class="gl-eyebrow">RADIO CHECK</p><h2><?php echo esc_html( $home['newsletter_title'] ); ?></h2></div><?php echo gridline_newsletter_capture( 'home-email', __( 'Join the channel', 'gridline-club' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</section>
</main>
<?php get_footer(); ?>
