<?php /** @package GridlineClub */ ?>
<footer class="gl-footer">
	<div class="gl-container">
		<div class="gl-footer-main">
			<div class="gl-footer-brand">
				<a class="gl-brand gl-brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="gl-brand-mark">A</span><span class="gl-brand-type"><span class="gl-brand-apex">APEX</span><span class="gl-brand-ik">IK</span><small>RADIO GOODS</small></span></a>
				<p><?php esc_html_e( 'Team radio phrases turned into apparel, gifts, desk gear, and trackside essentials.', 'gridline-club' ); ?></p>
			</div>
			<div><h3><?php esc_html_e( 'Shop', 'gridline-club' ); ?></h3><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">All products</a><a href="<?php echo esc_url( home_url( '/collections/team-radio/' ) ); ?>">Team radio gifts</a><a href="<?php echo esc_url( home_url( '/products/desk-mats/' ) ); ?>">Racing desk mats</a><a href="<?php echo esc_url( home_url( '/products/drinkware/' ) ); ?>">Drinkware</a><a href="<?php echo esc_url( home_url( '/products/apparel/' ) ); ?>">Radio apparel</a></div>
			<div><h3><?php esc_html_e( 'Guides & trust', 'gridline-club' ); ?></h3><a href="<?php echo esc_url( home_url( '/guides/motorsport-gifts-under-50/' ) ); ?>">Gifts under $50</a><a href="<?php echo esc_url( home_url( '/guides/racing-desk-mat-size-guide/' ) ); ?>">Desk mat size guide</a><a href="<?php echo esc_url( home_url( '/pages/licensing-and-authenticity/' ) ); ?>">Licensing & authenticity</a><a href="<?php echo esc_url( home_url( '/pages/materials-and-production/' ) ); ?>">Materials & production</a><a href="<?php echo esc_url( home_url( '/shipping-returns/' ) ); ?>">Shipping & returns</a><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></div>
			<div class="gl-footer-newsletter"><h3><?php esc_html_e( 'Join the formation lap', 'gridline-club' ); ?></h3><p><?php esc_html_e( 'New drops, race-weekend offers, no inbox clutter.', 'gridline-club' ); ?></p><?php echo gridline_newsletter_capture( 'footer-email', __( 'Join', 'gridline-club' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
		<div class="gl-footer-bottom"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></span><span><?php esc_html_e( 'Independent team radio culture. No team, driver, sponsor, or championship affiliation.', 'gridline-club' ); ?></span></div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
