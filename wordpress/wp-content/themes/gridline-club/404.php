<?php
/**
 * Not found page.
 *
 * @package GridlineClub
 */
get_header();
?>
<main id="main" class="site-main gl-error-page">
	<div class="gl-error-grid" aria-hidden="true"></div>
	<div class="gl-container gl-error-inner">
		<div><p class="gl-kicker"><span></span><?php esc_html_e( 'Wrong turn', 'gridline-club' ); ?></p><strong>404</strong></div>
		<div><h1><?php esc_html_e( 'Back to the racing line.', 'gridline-club' ); ?></h1><p><?php esc_html_e( 'That page has left the circuit. Head back to the grid or search the current collection.', 'gridline-club' ); ?></p><div class="gl-button-row"><a class="gl-button gl-button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return home', 'gridline-club' ); ?> →</a><a class="gl-text-link gl-text-link--light" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"><?php esc_html_e( 'Shop all products', 'gridline-club' ); ?> →</a></div></div>
	</div>
</main>
<?php get_footer(); ?>
