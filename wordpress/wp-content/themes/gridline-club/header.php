<?php /** @package GridlineClub */ ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="gl-skip" href="#main"><?php esc_html_e( 'Skip to content', 'gridline-club' ); ?></a>
<div class="gl-announcement">
	<span><?php echo esc_html( get_theme_mod( 'gridline_announcement', 'FREE US SHIPPING OVER $75 · EASY 30-DAY RETURNS' ) ); ?></span>
</div>
<header class="gl-header" data-header>
	<div class="gl-container gl-header-inner">
		<button class="gl-menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
			<span></span><span></span><span></span><span class="screen-reader-text"><?php esc_html_e( 'Menu', 'gridline-club' ); ?></span>
		</button>
		<?php if ( has_custom_logo() ) : ?>
			<div class="gl-brand gl-brand--custom"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a class="gl-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<span class="gl-brand-mark" aria-hidden="true">A</span>
				<span class="gl-brand-type"><span class="gl-brand-apex">APEX</span><span class="gl-brand-ik">IK</span><small>RADIO GOODS</small></span>
			</a>
		<?php endif; ?>
		<nav id="primary-navigation" class="gl-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'gridline-club' ); ?>" data-menu>
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'gridline_default_menu', 'menu_class' => 'gl-nav-list' ) ); ?>
		</nav>
		<div class="gl-header-actions">
			<button type="button" class="gl-icon-link gl-search-trigger" aria-label="<?php esc_attr_e( 'Open product search', 'gridline-club' ); ?>" aria-expanded="false" aria-controls="gridline-search" data-search-open>
				<i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
			</button>
			<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="gl-icon-link gl-cart-link" aria-label="<?php esc_attr_e( 'Cart', 'gridline-club' ); ?>">
				<i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
				<span class="gl-cart-count"><?php echo esc_html( gridline_cart_count() ); ?></span>
			</a>
			<?php endif; ?>
		</div>
	</div>
</header>
<div id="gridline-search" class="gl-search-drawer" aria-hidden="true" data-search-drawer>
	<div class="gl-search-backdrop" data-search-close></div>
	<div class="gl-search-panel" role="dialog" aria-modal="true" aria-labelledby="gridline-search-title">
		<div class="gl-search-panel-top"><p id="gridline-search-title"><?php esc_html_e( 'Find your next grid essential', 'gridline-club' ); ?></p><button type="button" aria-label="<?php esc_attr_e( 'Close product search', 'gridline-club' ); ?>" data-search-close>×</button></div>
		<form class="gl-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="gridline-search-input"><?php esc_html_e( 'Search products', 'gridline-club' ); ?></label>
			<input id="gridline-search-input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Quote, hoodie, trackside kit…', 'gridline-club' ); ?>" autocomplete="off">
			<input type="hidden" name="post_type" value="product">
			<button type="submit"><?php esc_html_e( 'Search', 'gridline-club' ); ?> <span>→</span></button>
		</form>
		<div class="gl-search-suggestions"><span><?php esc_html_e( 'Popular:', 'gridline-club' ); ?></span><a href="<?php echo esc_url( home_url( '/?s=radio&post_type=product' ) ); ?>"><?php esc_html_e( 'Radio quotes', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/radio-apparel/' ) ); ?>"><?php esc_html_e( 'Apparel', 'gridline-club' ); ?></a><a href="<?php echo esc_url( home_url( '/product-category/race-day-gear/' ) ); ?>"><?php esc_html_e( 'Race day gear', 'gridline-club' ); ?></a></div>
	</div>
</div>
