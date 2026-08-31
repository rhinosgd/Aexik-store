<?php
/**
 * Race Control Archive homepage.
 *
 * @package GridlineClub
 */
get_header();
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$theme    = get_template_directory_uri();
?>
<main id="main" class="site-main apexik-archive-home">
	<section class="apexik-archive-hero">
		<div class="apexik-archive-hero__media"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-unfiltered-desk-mat.jpg' ); ?>" alt="Formula 1-inspired desk mat with an archive-style radio graphic"></div>
		<div class="apexik-archive-hero__trace" aria-hidden="true"><svg viewBox="0 0 1440 260" preserveAspectRatio="none"><path d="M-30 198 C134 204 154 54 323 77 S524 244 695 132 S872 18 1008 102 S1238 190 1470 38"></path><circle cx="323" cy="77" r="7"></circle><circle cx="695" cy="132" r="7"></circle><circle cx="1008" cy="102" r="7"></circle></svg></div>
		<div class="gl-container apexik-archive-hero__inner">
			<div class="apexik-archive-hero__copy">
				<p class="apexik-archive-code">Race Control Archive / Drop 01</p>
				<h1>Formula 1 moments,<br>built for your desk.</h1>
				<p>Collectible desk mats, mouse pads and keychains that turn strategy calls, team radio and defining incidents into original race records.</p>
				<div class="gl-button-row"><a class="gl-button gl-button--archive" href="<?php echo esc_url( $shop_url ); ?>">Shop desk mats <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a><a class="apexik-archive-link" href="#archive">Explore the archive</a></div>
			</div>
			<div class="apexik-archive-hero__record"><span>Current collection</span><strong>Race Control</strong><small>Strategy / Radio / Incident</small></div>
		</div>
	</section>

	<nav class="apexik-archive-collections" aria-label="Shop collections"><div class="gl-container">
		<a href="<?php echo esc_url( home_url( '/collections/team-radio/' ) ); ?>"><span>Team Radio</span><strong>Calls worth replaying.</strong></a>
		<a href="<?php echo esc_url( home_url( '/products/desk-mats/' ) ); ?>"><span>Pit Wall Strategy</span><strong>Every decision leaves a line.</strong></a>
		<a href="<?php echo esc_url( home_url( '/collections/team-radio/' ) ); ?>"><span>Defining Moments</span><strong>The whole incident, not one word.</strong></a>
	</div></nav>

	<section class="apexik-archive-products"><div class="gl-container">
		<div class="apexik-archive-heading"><h2>Choose the surface.<br>Keep the story.</h2><p>Every desk mat and mouse pad is built around a complete visual system: the sequence, the call, the shift and the aftermath. Choose Size and Style before production.</p></div>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?><div class="apexik-archive-products__grid"><?php echo do_shortcode( '[products limit="3" columns="3" category="desk-tech" orderby="date" order="DESC"]' ); ?></div><?php endif; ?>
	</div></section>

	<section id="archive" class="apexik-archive-story"><div class="gl-container apexik-archive-story__grid">
		<div><p class="apexik-archive-code">The design record</p><h2>Not a quote.<br>A whole moment.</h2><p>APEXIK rebuilds Formula 1 history as designed evidence: track position, strategy window, radio exchange and the detail that changed the outcome. The result is made for your everyday desk, not a shelf.</p><a class="gl-button gl-button--light" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Read the archive <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div>
		<aside class="apexik-incident-file"><span>Illustrative incident file</span><dl><div><dt>Lap</dt><dd>42 / decision window</dd></div><div><dt>Call</dt><dd>Box this lap</dd></div><div><dt>Shift</dt><dd>Undercut activated</dd></div><div><dt>Design</dt><dd>Red route / sector break</dd></div></dl></aside>
	</div></section>

	<section class="apexik-archive-addons"><div class="gl-container apexik-archive-addons__grid"><img src="<?php echo esc_url( $theme . '/assets/images/products/apexik-radio-accessory-kit.jpg' ); ?>" alt="Formula 1-inspired desk and key accessories"><div><h2>Carry the signal.</h2><p>Add a keychain to keep the same race record with you beyond the desk. Each add-on is designed to belong to its collection, not sit beside it by accident.</p><a class="gl-button gl-button--dark" href="<?php echo esc_url( home_url( '/product-category/small-gifts/' ) ); ?>">Shop keychains <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div></div></section>

	<section class="apexik-archive-guides"><div class="gl-container"><h2>Built to answer before checkout.</h2><div><a href="<?php echo esc_url( home_url( '/guides/racing-desk-mat-size-guide/' ) ); ?>">Desk mat size guide <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a><a href="<?php echo esc_url( home_url( '/guides/motorsport-gifts-under-50/' ) ); ?>">Motorsport gift guide <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a><a href="<?php echo esc_url( home_url( '/collections/team-radio/' ) ); ?>">Team radio collection <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></div></div></section>
</main>
<?php get_footer(); ?>
