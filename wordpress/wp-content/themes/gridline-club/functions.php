<?php
/**
 * APEXIK theme functions.
 *
 * @package GridlineClub
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GRIDLINE_VERSION', '1.9.0' );

function gridline_setup() {
	load_theme_textdomain( 'gridline-club', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 72, 'width' => 260, 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'gridline-club' ),
			'footer'  => __( 'Footer Navigation', 'gridline-club' ),
		)
	);
	add_image_size( 'gridline-card', 900, 900, true );
	add_image_size( 'gridline-hero', 1600, 1300, true );
}
add_action( 'after_setup_theme', 'gridline_setup' );

function gridline_assets() {
	wp_enqueue_style( 'gridline-main', get_template_directory_uri() . '/assets/css/main.css', array(), GRIDLINE_VERSION );
	wp_enqueue_style( 'gridline-icons', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css', array(), '6.7.2' );
	wp_enqueue_script( 'gridline-main', get_template_directory_uri() . '/assets/js/main.js', array(), GRIDLINE_VERSION, true );
	$accent = sanitize_hex_color( get_theme_mod( 'gridline_accent', '#c51d24' ) );
	$ink    = sanitize_hex_color( get_theme_mod( 'gridline_ink', '#111111' ) );
	wp_add_inline_style( 'gridline-main', ':root{--gl-accent:' . ( $accent ?: '#c51d24' ) . ';--gl-ink:' . ( $ink ?: '#111111' ) . ';}' );
}
add_action( 'wp_enqueue_scripts', 'gridline_assets' );

function gridline_customize( $customizer ) {
	$customizer->add_section( 'gridline_brand', array( 'title' => __( 'APEXIK Brand', 'gridline-club' ), 'priority' => 30 ) );
	$settings = array(
		'gridline_accent' => array( 'label' => __( 'Accent color', 'gridline-club' ), 'default' => '#c51d24' ),
		'gridline_ink'    => array( 'label' => __( 'Ink color', 'gridline-club' ), 'default' => '#111111' ),
	);
	foreach ( $settings as $id => $args ) {
		$customizer->add_setting( $id, array( 'default' => $args['default'], 'sanitize_callback' => 'sanitize_hex_color' ) );
		$customizer->add_control( new WP_Customize_Color_Control( $customizer, $id, array( 'label' => $args['label'], 'section' => 'gridline_brand' ) ) );
	}
	$customizer->add_setting( 'gridline_announcement', array( 'default' => 'FREE US SHIPPING OVER $75 · EASY 30-DAY RETURNS', 'sanitize_callback' => 'sanitize_text_field' ) );
	$customizer->add_control( 'gridline_announcement', array( 'label' => __( 'Announcement text', 'gridline-club' ), 'section' => 'gridline_brand', 'type' => 'text' ) );
	$customizer->add_setting( 'gridline_newsletter_action', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$customizer->add_control( 'gridline_newsletter_action', array( 'label' => __( 'Newsletter form action URL', 'gridline-club' ), 'description' => __( 'Paste the hosted signup form URL from your email provider. Until configured, the site shows a contact link instead of collecting email addresses.', 'gridline-club' ), 'section' => 'gridline_brand', 'type' => 'url' ) );
}
add_action( 'customize_register', 'gridline_customize' );

function gridline_woocommerce_layout() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_before_main_content', function () { echo '<main id="main" class="site-main gl-shop-shell"><div class="gl-container">'; }, 10 );
	add_action( 'woocommerce_after_main_content', function () { echo '</div></main>'; }, 10 );
}
add_action( 'wp', 'gridline_woocommerce_layout' );

function gridline_cart_count() {
	return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function gridline_cart_count_fragment( $fragments ) {
	ob_start();
	?>
	<span class="gl-cart-count"><?php echo esc_html( gridline_cart_count() ); ?></span>
	<?php
	$fragments['.gl-cart-count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'gridline_cart_count_fragment' );

function gridline_meta_description() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'The_SEO_Framework\Load' ) ) {
		return;
	}
	$description = is_singular() ? get_post_meta( get_queried_object_id(), '_apexik_meta_description', true ) : '';
	if ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( ! $description && is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( get_queried_object_id() );
		$description = gridline_product_meta( get_queried_object_id(), 'meta_description', $product ? wp_strip_all_tags( $product->get_short_description() ) : '' );
	} elseif ( ! $description && function_exists( 'is_shop' ) && is_shop() ) {
		$description = __( 'Shop Formula 1-inspired desk mats, mouse pads and keychains built from race strategy, team radio and defining moments.', 'gridline-club' );
	} elseif ( ! $description && function_exists( 'is_product_category' ) && is_product_category() ) {
		$description = wp_strip_all_tags( term_description() );
	} elseif ( ! $description && is_singular() ) {
		$description = wp_strip_all_tags( get_the_excerpt() );
	}
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( wp_trim_words( $description, 28, '' ) ) . '">' . "\n";
	}
	if ( is_singular() ) {
		$canonical = get_post_meta( get_queried_object_id(), '_apexik_canonical', true );
		if ( $canonical ) echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
	}
	if ( is_singular( 'product' ) ) {
		$product_id = get_queried_object_id();
		$og_title   = gridline_product_meta( $product_id, 'seo_title', get_the_title( $product_id ) );
		$image      = get_the_post_thumbnail_url( $product_id, 'large' );
		echo '<meta property="og:type" content="product">' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
		if ( $description ) {
			echo '<meta property="og:description" content="' . esc_attr( wp_trim_words( $description, 28, '' ) ) . '">' . "\n";
		}
		if ( $image ) {
			echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	}
}
add_action( 'wp_head', 'gridline_meta_description', 2 );

function gridline_structured_data() {
	if ( ! is_front_page() || defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || class_exists( 'The_SEO_Framework\Load' ) ) {
		return;
	}
	$site_url = home_url( '/' );
	$data     = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			array(
				'@type' => 'Organization',
				'@id'   => $site_url . '#organization',
				'name'  => get_bloginfo( 'name' ),
				'url'   => $site_url,
			),
			array(
				'@type'           => 'WebSite',
				'@id'             => $site_url . '#website',
				'url'             => $site_url,
				'name'            => get_bloginfo( 'name' ),
				'description'     => get_bloginfo( 'description' ),
				'publisher'       => array( '@id' => $site_url . '#organization' ),
				'potentialAction' => array(
					'@type'       => 'SearchAction',
					'target'      => home_url( '/?s={search_term_string}&post_type=product' ),
					'query-input' => 'required name=search_term_string',
				),
			),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'gridline_structured_data', 30 );

function gridline_newsletter_capture( $id, $button_label ) {
	$action = get_theme_mod( 'gridline_newsletter_action', '' );
	if ( ! $action ) {
		return '<a class="gl-newsletter-fallback" href="' . esc_url( home_url( '/contact/?topic=new-drops' ) ) . '">' . esc_html__( 'Get drop updates', 'gridline-club' ) . ' <span>→</span></a>';
	}
	return '<form class="gl-newsletter" action="' . esc_url( $action ) . '" method="post"><label class="screen-reader-text" for="' . esc_attr( $id ) . '">' . esc_html__( 'Email address', 'gridline-club' ) . '</label><input id="' . esc_attr( $id ) . '" name="email" type="email" autocomplete="email" placeholder="' . esc_attr__( 'Email address', 'gridline-club' ) . '" required><button type="submit">' . esc_html( $button_label ) . ' →</button></form>';
}

function gridline_document_title( $title ) {
	if ( is_singular() ) {
		$custom = get_post_meta( get_queried_object_id(), '_apexik_seo_title', true );
		if ( $custom ) return $custom;
	}
	if ( is_front_page() ) {
		return __( 'Formula 1 Desk Mats, Mouse Pads & Keychains | APEXIK', 'gridline-club' );
	}
	if ( is_singular( 'product' ) ) {
		return gridline_product_meta( get_queried_object_id(), 'seo_title', $title );
	}
	return $title;
}
add_filter( 'pre_get_document_title', 'gridline_document_title' );

function gridline_robots_policy( $robots ) {
	if ( is_search() || isset( $_GET['orderby'] ) || isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) || isset( $_GET['filtering'] ) ) {
		$robots['noindex'] = true; $robots['follow'] = true; unset( $robots['index'] );
	}
	if ( is_singular() ) {
		$policy = get_post_meta( get_queried_object_id(), '_apexik_robots', true );
		if ( str_contains( $policy, 'noindex' ) ) { $robots['noindex'] = true; unset( $robots['index'] ); }
	}
	return $robots;
}
add_filter( 'wp_robots', 'gridline_robots_policy' );

function gridline_robots_txt( $output, $public ) {
	if ( ! $public ) return $output;
	$output .= "\nUser-agent: OAI-SearchBot\nAllow: /\n";
	return $output;
}
add_filter( 'robots_txt', 'gridline_robots_txt', 10, 2 );

function gridline_product_meta( $product_id, $key, $fallback = '' ) {
	$value = get_post_meta( $product_id, '_gridline_' . $key, true );
	return '' !== $value ? $value : $fallback;
}

function gridline_lines( $text ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $text ) ) ) );
}

/**
 * Radio archive copy shown when a shopper hovers a product image.
 *
 * Each line uses "speaker | message" so the archive remains easy to edit in
 * the product screen without exposing driver or team identities.
 */
function gridline_radio_archive_defaults() {
	return array(
		'must-be-the-water-radio-polo' => array(
			'channel'   => 'AUS 25 / RADIO ARCHIVE',
			'highlight' => 'Must be the water.',
			'context'   => "CAR | Is there a leakage?\nPIT WALL | A leakage where?\nCAR | I have the seat full of water!\nPIT WALL | Must be the water.\nCAR | Let's add that to the words of wisdom.",
		),
		'must-be-the-water-radio-mug' => array(
			'channel'   => 'AUS 25 / RADIO ARCHIVE',
			'highlight' => 'Must be the water.',
			'context'   => "CAR | I have the seat full of water!\nPIT WALL | Must be the water.\nCAR | Let's add that to the words of wisdom.",
		),
		'gp2-engine-pit-wall-hoodie' => array(
			'channel'   => 'JPN 15 / RADIO ARCHIVE',
			'highlight' => 'GP2 engine, GP2.',
			'context'   => "CAR | They pass me on the straights like in GP2.\nCAR | It is embarrassing, very embarrassing.\nCAR | GP2 engine, GP2. Aaargh!",
		),
		'simply-lovely-insulated-tumbler' => array(
			'channel'   => 'NED 24 / CHEQUERED FLAG',
			'highlight' => 'Simply lovely',
			'context'   => "CAR | Uh huh. Simply lovely, huh? Well done.",
		),
		'change-your-fxxking-car-desk-mat' => array(
			'channel'   => 'PIT WALL / UNFILTERED',
			'highlight' => 'change your f***ing car',
			'context'   => "MEETING | I have a printout here.\nMEETING | If you've got a problem, change your f***ing car.",
		),
	);
}

function gridline_radio_archive( $product_id ) {
	$defaults = gridline_radio_archive_defaults();
	$slug     = get_post_field( 'post_name', $product_id );
	$fallback = isset( $defaults[ $slug ] ) ? $defaults[ $slug ] : array();
	$context  = gridline_product_meta( $product_id, 'radio_context', isset( $fallback['context'] ) ? $fallback['context'] : '' );
	if ( ! $context ) {
		return array();
	}
	return array(
		'channel'   => gridline_product_meta( $product_id, 'radio_channel', isset( $fallback['channel'] ) ? $fallback['channel'] : 'RADIO ARCHIVE' ),
		'highlight' => gridline_product_meta( $product_id, 'radio_highlight', isset( $fallback['highlight'] ) ? $fallback['highlight'] : '' ),
		'lines'     => gridline_lines( $context ),
	);
}

function gridline_radio_line_markup( $line, $highlight ) {
	$parts   = array_map( 'trim', explode( '|', $line, 2 ) );
	$speaker = count( $parts ) > 1 ? array_shift( $parts ) : 'RADIO';
	$message = count( $parts ) ? $parts[0] : $line;
	if ( ! $highlight || false === stripos( $message, $highlight ) ) {
		return '<p><span>' . esc_html( $speaker ) . '</span>' . esc_html( $message ) . '</p>';
	}
	$pieces = preg_split( '/(' . preg_quote( $highlight, '/' ) . ')/i', $message, -1, PREG_SPLIT_DELIM_CAPTURE );
	$copy   = '';
	foreach ( $pieces as $piece ) {
		$copy .= 0 === strcasecmp( $piece, $highlight ) ? '<strong>' . esc_html( $piece ) . '</strong>' : esc_html( $piece );
	}
	return '<p class="is-highlight"><span>' . esc_html( $speaker ) . '</span>' . $copy . '</p>';
}

function gridline_loop_product_radio_media() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		woocommerce_template_loop_product_thumbnail();
		return;
	}
	$archive = gridline_radio_archive( $product->get_id() );
	echo '<div class="gl-radio-card-media">';
	woocommerce_template_loop_product_thumbnail();
	if ( $archive ) {
		$wave = get_template_directory_uri() . '/assets/images/ui/apexik-radio-wave.png';
		echo '<div class="gl-radio-card-transcript"><div class="gl-radio-card-head"><span class="gl-radio-live">LIVE</span><span>' . esc_html( $archive['channel'] ) . '</span></div><div class="gl-radio-wave" aria-hidden="true"><img src="' . esc_url( $wave ) . '" alt=""></div><div class="gl-radio-card-lines">';
		foreach ( $archive['lines'] as $line ) {
			echo gridline_radio_line_markup( $line, $archive['highlight'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div><small>FULL CONTEXT / HOVER ARCHIVE</small></div>';
	}
	echo '</div>';
}

function gridline_install_radio_product_cards() {
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_before_shop_loop_item_title', 'gridline_loop_product_radio_media', 10 );
}
add_action( 'init', 'gridline_install_radio_product_cards', 20 );

/**
 * Keep pre-production concepts visible in WooCommerce catalogues without
 * allowing an order before specifications and fulfilment are approved.
 */
function gridline_coming_soon_loop_button( $button, $product ) {
	if ( ! $product instanceof WC_Product || '1' !== get_post_meta( $product->get_id(), '_gridline_coming_soon', true ) ) {
		return $button;
	}
	return sprintf(
		'<a href="%1$s" class="button gl-coming-soon-button">%2$s</a>',
		esc_url( $product->get_permalink() ),
		esc_html__( 'Coming soon', 'gridline-club' )
	);
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'gridline_coming_soon_loop_button', 20, 2 );

function gridline_product_story_box() {
	add_meta_box( 'gridline_product_story', __( 'APEXIK Product Story & SEO', 'gridline-club' ), 'gridline_product_story_fields', 'product', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'gridline_product_story_box' );

function gridline_product_story_fields( $post ) {
	wp_nonce_field( 'gridline_product_story_save', 'gridline_product_story_nonce' );
	$fields = array(
		'subtitle'   => array( 'Product subtitle', 'text' ),
		'eyebrow'    => array( 'Eyebrow / collection label', 'text' ),
		'benefits'   => array( 'Key benefits (one per line)', 'textarea' ),
		'what'       => array( 'What it is', 'textarea' ),
		'difference' => array( 'What makes it different', 'textarea' ),
		'materials'  => array( 'Materials', 'text' ),
		'dimensions' => array( 'Dimensions / fit', 'text' ),
		'care'       => array( 'Care', 'text' ),
		'dispatch'   => array( 'Dispatch note', 'text' ),
		'faq'        => array( 'FAQ (Question | Answer, one per line)', 'textarea' ),
		'seo_title'  => array( 'SEO title', 'text' ),
		'meta_description' => array( 'Meta description', 'textarea' ),
		'keywords'   => array( 'Target keyword cluster', 'textarea' ),
		'radio_channel' => array( 'Hover archive channel label', 'text' ),
		'radio_highlight' => array( 'Hover archive highlighted phrase', 'text' ),
		'radio_context' => array( 'Hover archive dialogue (Speaker | Message, one per line)', 'textarea' ),
	);
	echo '<div class="gridline-admin-fields">';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_gridline_' . $key, true );
		echo '<p><label for="gridline_' . esc_attr( $key ) . '"><strong>' . esc_html( $field[0] ) . '</strong></label><br>';
		if ( 'textarea' === $field[1] ) {
			echo '<textarea style="width:100%;min-height:100px" id="gridline_' . esc_attr( $key ) . '" name="gridline_' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input style="width:100%" type="text" id="gridline_' . esc_attr( $key ) . '" name="gridline_' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</p>';
	}
	echo '</div>';
}

function gridline_save_product_story( $post_id ) {
	if ( ! isset( $_POST['gridline_product_story_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gridline_product_story_nonce'] ) ), 'gridline_product_story_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$keys = array( 'subtitle', 'eyebrow', 'benefits', 'what', 'difference', 'materials', 'dimensions', 'care', 'dispatch', 'faq', 'seo_title', 'meta_description', 'keywords', 'radio_channel', 'radio_highlight', 'radio_context' );
	foreach ( $keys as $key ) {
		if ( isset( $_POST[ 'gridline_' . $key ] ) ) {
			update_post_meta( $post_id, '_gridline_' . $key, sanitize_textarea_field( wp_unslash( $_POST[ 'gridline_' . $key ] ) ) );
		}
	}
}
add_action( 'save_post_product', 'gridline_save_product_story' );

function gridline_default_menu() {
	$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$items = array(
		'Shop' => $shop,
		'Desk Mats' => home_url( '/products/desk-mats/' ),
		'Mouse Pads' => home_url( '/products/desk-mats/' ),
		'Keychains' => home_url( '/product-category/small-gifts/' ),
		'Our Story' => home_url( '/about/' ),
	);
	echo '<ul class="gl-nav-list">';
	foreach ( $items as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}
