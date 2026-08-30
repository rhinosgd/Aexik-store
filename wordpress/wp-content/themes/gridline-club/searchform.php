<?php /** @package GridlineClub */ ?>
<form class="gl-search-form gl-search-form--page" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="gridline-page-search"><?php esc_html_e( 'Search products', 'gridline-club' ); ?></label>
	<input id="gridline-page-search" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search products…', 'gridline-club' ); ?>">
	<input type="hidden" name="post_type" value="product">
	<button type="submit"><?php esc_html_e( 'Search', 'gridline-club' ); ?> <span>→</span></button>
</form>

