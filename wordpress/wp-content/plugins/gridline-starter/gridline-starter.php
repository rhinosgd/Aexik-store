<?php
/**
 * Plugin Name: APEXIK Starter Content
 * Description: Installs the APEXIK pages, seven original radio-culture products, SEO metadata, categories, and menus.
 * Version: 1.8.1
 * Requires Plugins: woocommerce
 * Requires PHP: 8.1
 * Text Domain: gridline-starter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function gridline_starter_admin_menu() {
	add_menu_page( 'APEXIK 店铺管理', 'APEXIK 店铺', 'manage_woocommerce', 'apexik-store', 'gridline_store_dashboard_page', 'dashicons-store', 2 );
	add_submenu_page( 'apexik-store', '店铺总览', '店铺总览', 'manage_woocommerce', 'apexik-store', 'gridline_store_dashboard_page' );
	add_submenu_page( 'apexik-store', '首页内容', '首页内容', 'manage_options', 'apexik-home', 'gridline_home_settings_page' );
	add_submenu_page( 'apexik-store', '商品管理', '商品管理', 'edit_products', 'edit.php?post_type=product' );
	add_submenu_page( 'apexik-store', '添加商品', '添加商品', 'edit_products', 'post-new.php?post_type=product' );
	add_submenu_page( 'apexik-store', '订单管理', '订单管理', 'edit_shop_orders', 'admin.php?page=wc-orders' );
	add_management_page( 'APEXIK Setup', 'APEXIK Setup', 'manage_options', 'gridline-setup', 'gridline_starter_page' );
}
add_action( 'admin_menu', 'gridline_starter_admin_menu' );

function gridline_store_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'apexik-store' ) && false === strpos( $hook, 'apexik-home' ) ) return;
	wp_add_inline_style( 'wp-admin', '.apexik-admin{max-width:1180px}.apexik-admin-hero{background:#111;color:#fff;padding:30px 34px;border-left:6px solid #c51d24;margin:22px 0}.apexik-admin-hero h1{color:#fff;margin:0 0 8px;font-size:30px}.apexik-admin-hero p{margin:0;color:#d7d7d7;font-size:15px}.apexik-admin-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:16px}.apexik-admin-card{display:block;background:#fff;border:1px solid #dcdcde;border-radius:6px;padding:22px;text-decoration:none;color:#1d2327}.apexik-admin-card:hover{border-color:#c51d24;color:#1d2327}.apexik-admin-card .dashicons{font-size:28px;width:32px;height:32px;color:#c51d24}.apexik-admin-card h2{margin:12px 0 6px;font-size:17px}.apexik-admin-card p{margin:0;color:#646970;line-height:1.5}.apexik-settings{background:#fff;border:1px solid #dcdcde;padding:26px 30px;max-width:900px}.apexik-settings table{width:100%}.apexik-settings th{text-align:left;width:220px;padding:14px 20px 14px 0;vertical-align:top}.apexik-settings td{padding:10px 0}.apexik-settings input,.apexik-settings textarea{width:100%;max-width:620px}.apexik-settings textarea{min-height:90px}' );
}
add_action( 'admin_enqueue_scripts', 'gridline_store_admin_assets' );

function gridline_store_dashboard_page() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) return;
	$cards = array(
		array( 'dashicons-products', '商品管理', '上架、改价、库存、图片、分类和商品详情。', admin_url( 'edit.php?post_type=product' ) ),
		array( 'dashicons-plus-alt2', '添加新商品', '录入标题、图片、价格、规格和描述。', admin_url( 'post-new.php?post_type=product' ) ),
		array( 'dashicons-clipboard', '订单管理', '查看订单、付款状态、客户地址和履约进度。', admin_url( 'admin.php?page=wc-orders' ) ),
		array( 'dashicons-layout', '首页内容', '编辑 Banner 短句、按钮、栏目标题和品牌故事。', admin_url( 'admin.php?page=apexik-home' ) ),
		array( 'dashicons-admin-customizer', '品牌与菜单', '修改 Logo、主题颜色、公告栏和导航菜单。', admin_url( 'customize.php' ) ),
		array( 'dashicons-admin-settings', '店铺基本信息', '设置地址、销售地区、币种和税务选项。', admin_url( 'admin.php?page=wc-settings&tab=general' ) ),
		array( 'dashicons-car', '配送设置', '设置欧洲、澳洲及其他市场的配送区域与运费。', admin_url( 'admin.php?page=wc-settings&tab=shipping' ) ),
		array( 'dashicons-money-alt', '支付设置', '连接并启用 Stripe、PayPal 等支付方式。', admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ),
		array( 'dashicons-email-alt', '订单邮件', '设置订单通知的发件人、样式与邮件模板。', admin_url( 'admin.php?page=wc-settings&tab=email' ) ),
	);
	?><div class="wrap apexik-admin"><div class="apexik-admin-hero"><h1>APEXIK 店铺管理</h1><p>集中管理商品、订单、首页、配送、支付和邮件。</p></div><div class="apexik-admin-grid"><?php foreach ( $cards as $card ) : ?><a class="apexik-admin-card" href="<?php echo esc_url( $card[3] ); ?>"><span class="dashicons <?php echo esc_attr( $card[0] ); ?>"></span><h2><?php echo esc_html( $card[1] ); ?></h2><p><?php echo esc_html( $card[2] ); ?></p></a><?php endforeach; ?></div></div><?php
}

function gridline_home_settings_defaults() {
	return array(
		'hero_channel' => 'Radio drop 01', 'hero_title' => 'Heard it. Wear it.', 'hero_button' => 'Shop radio drop', 'hero_button_url' => '',
		'categories_eyebrow' => '01 / Choose your channel', 'categories_title' => 'Radio moments, made useful.', 'products_eyebrow' => '02 / Radio drop 01', 'products_title' => 'Shop the radio drop.',
		'story_eyebrow' => '03 / Our point of view', 'story_title' => "Not team merch.\nRadio culture.", 'story_text' => 'We take authorized team radio phrases and rebuild them through Apexik waveforms, channel codes, signal blocks, and products fans can use every day.',
		'newsletter_title' => 'Get the next quote before lights out.',
	);
}
function gridline_home_settings() { return wp_parse_args( (array) get_option( 'apexik_home_settings', array() ), gridline_home_settings_defaults() ); }

function gridline_save_home_settings() {
	if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Permission denied.' );
	check_admin_referer( 'apexik_save_home' );
	$raw = isset( $_POST['apexik_home'] ) ? wp_unslash( $_POST['apexik_home'] ) : array();
	$clean = array();
	foreach ( gridline_home_settings_defaults() as $key => $default ) {
		$value = isset( $raw[ $key ] ) ? $raw[ $key ] : '';
		$clean[ $key ] = 'hero_button_url' === $key ? esc_url_raw( $value ) : sanitize_textarea_field( $value );
	}
	update_option( 'apexik_home_settings', $clean );
	wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'admin.php?page=apexik-home' ) ) ); exit;
}
add_action( 'admin_post_apexik_save_home', 'gridline_save_home_settings' );

function gridline_home_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	$values = gridline_home_settings();
	$labels = array( 'hero_channel' => 'Banner 小标签', 'hero_title' => 'Banner 主标题', 'hero_button' => 'Banner 按钮文字', 'hero_button_url' => 'Banner 按钮链接（留空进入商店）', 'categories_eyebrow' => '分类区小标题', 'categories_title' => '分类区主标题', 'products_eyebrow' => '商品区小标题', 'products_title' => '商品区主标题', 'story_eyebrow' => '品牌故事小标题', 'story_title' => '品牌故事标题（可换行）', 'story_text' => '品牌故事简介', 'newsletter_title' => '订阅区标题' );
	?><div class="wrap apexik-admin"><div class="apexik-admin-hero"><h1>首页内容</h1><p>保存后立即生效；视觉布局、赛车 Banner 和商品详情页不会改变。</p></div><?php if ( isset( $_GET['updated'] ) ) : ?><div class="notice notice-success"><p>首页内容已保存。</p></div><?php endif; ?><form class="apexik-settings" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post"><input type="hidden" name="action" value="apexik_save_home"><?php wp_nonce_field( 'apexik_save_home' ); ?><table><?php foreach ( $labels as $key => $label ) : ?><tr><th><label for="apexik-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th><td><?php if ( in_array( $key, array( 'story_title', 'story_text' ), true ) ) : ?><textarea id="apexik-<?php echo esc_attr( $key ); ?>" name="apexik_home[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $values[ $key ] ); ?></textarea><?php else : ?><input id="apexik-<?php echo esc_attr( $key ); ?>" type="<?php echo 'hero_button_url' === $key ? 'url' : 'text'; ?>" name="apexik_home[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $values[ $key ] ); ?>"><?php endif; ?></td></tr><?php endforeach; ?></table><?php submit_button( '保存首页内容' ); ?></form></div><?php
}

function gridline_starter_contact_shortcode() {
	$email = sanitize_email( get_option( 'admin_email' ) );
	if ( ! $email ) {
		return '<div class="gl-contact-card"><p>' . esc_html__( 'Customer support details will be published before the store opens.', 'gridline-starter' ) . '</p></div>';
	}
	$subject = rawurlencode( 'APEXIK customer support' );
	return '<div class="gl-contact-card"><p class="gl-eyebrow">RADIO CHECK</p><h2>' . esc_html__( 'Talk to the pit wall.', 'gridline-starter' ) . '</h2><p>' . esc_html__( 'For product, delivery, return, or order questions, email the customer support channel. Include your order number when applicable.', 'gridline-starter' ) . '</p><a class="gl-button gl-button--dark" href="mailto:' . esc_attr( $email ) . '?subject=' . esc_attr( $subject ) . '">' . esc_html( $email ) . ' <span>→</span></a><p class="gl-contact-note">' . esc_html__( 'Typical response target: within two business days.', 'gridline-starter' ) . '</p></div>';
}
add_shortcode( 'gridline_contact', 'gridline_starter_contact_shortcode' );

function gridline_starter_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$message = '';
	if ( isset( $_POST['gridline_install_demo'] ) ) {
		check_admin_referer( 'gridline_install_demo' );
		$result  = gridline_starter_install();
		$message = is_wp_error( $result ) ? $result->get_error_message() : 'APEXIK starter content installed. Verify supplier specifications, product variants, payments, shipping, and policies before launch.';
	}
	?>
	<div class="wrap"><h1>APEXIK Setup</h1>
		<?php if ( $message ) : ?><div class="notice notice-info"><p><?php echo esc_html( $message ); ?></p></div><?php endif; ?>
		<p>This importer creates the homepage, navigation, essential information pages, product categories, and seven APEXIK launch products. It is safe to run again: existing items with the same slugs are updated rather than duplicated.</p>
		<p><strong>Before running:</strong> activate WooCommerce and the APEXIK theme.</p>
		<form method="post"><?php wp_nonce_field( 'gridline_install_demo' ); ?><button class="button button-primary button-hero" name="gridline_install_demo" value="1">Install / refresh starter content</button></form>
		<h2 style="margin-top:40px">Launch readiness</h2>
		<p>These checks do not change settings. Review them again after the domain, payments, shipping, and legal pages are configured.</p>
		<table class="widefat striped" style="max-width:980px"><thead><tr><th style="width:180px">Status</th><th>Check</th><th>Next action</th></tr></thead><tbody>
		<?php foreach ( gridline_starter_readiness_checks() as $check ) : ?>
			<tr><td><strong style="color:<?php echo $check['ready'] ? '#16764a' : '#a64b00'; ?>"><?php echo $check['ready'] ? '✓ Ready' : '○ Needs attention'; ?></strong></td><td><?php echo esc_html( $check['label'] ); ?></td><td><?php echo esc_html( $check['action'] ); ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>
	</div>
	<?php
}

function gridline_starter_category_descriptions() {
	return array(
		'Radio Apparel'   => 'Team radio quote polos, hoodies, and streetwear built around authorized phrases and Apexik-original signal graphics.',
		'Drinkware'       => 'Motorsport quote mugs and insulated tumblers designed for early starts, travel days, and full race weekends.',
		'Small Gifts'     => 'Affordable team radio gifts under $30, including key tags, stickers, watch accessories, and desk notes.',
		'Desk & Tech'     => 'Pit-wall-inspired desk mats, mouse pads, watch accessories, and useful objects for race fans.',
		'Race Day Gear'   => 'Lightweight trackside and watch-party gear for more comfortable race weekends.',
		'Radio Unfiltered'=> 'The bluntest authorized radio phrases, presented with censored storefront wording and original Apexik graphics.',
		'Gifts'           => 'Independent motorsport gifts for race fans, from drinkware to practical trackside gear.',
		'Best Sellers'    => 'The lead APEXIK radio-culture products selected for the launch collection.',
		'Everyday Carry'  => 'Original compact carry goods designed for useful everyday moments: pocketable, giftable, and free from licensed motorsport references.',
	);
}

function gridline_starter_readiness_checks() {
	$woocommerce_ready = class_exists( 'WooCommerce' );
	$contact            = get_page_by_path( 'contact', OBJECT, 'page' );
	$contact_email      = sanitize_email( get_option( 'admin_email' ) );
	$contact_ready      = $contact && $contact_email && ! str_ends_with( strtolower( $contact_email ), '@example.com' );
	$privacy_id         = (int) get_option( 'wp_page_for_privacy_policy' );
	$privacy_ready      = $privacy_id > 0 && 'publish' === get_post_status( $privacy_id );
	$gateways_ready     = false;
	$shipping_ready     = false;

	if ( $woocommerce_ready && function_exists( 'WC' ) && WC()->payment_gateways() ) {
		foreach ( WC()->payment_gateways()->payment_gateways() as $gateway ) {
			if ( 'yes' === $gateway->enabled && ! in_array( $gateway->id, array( 'cod', 'bacs', 'cheque' ), true ) ) {
				$gateways_ready = true;
				break;
			}
		}
	}

	if ( $woocommerce_ready && class_exists( 'WC_Shipping_Zones' ) ) {
		$zones = WC_Shipping_Zones::get_zones();
		$zones[] = array( 'shipping_methods' => WC_Shipping_Zones::get_zone( 0 )->get_shipping_methods( true ) );
		foreach ( $zones as $zone ) {
			if ( ! empty( $zone['shipping_methods'] ) ) {
				$shipping_ready = true;
				break;
			}
		}
	}

	return array(
		array( 'ready' => $woocommerce_ready, 'label' => 'WooCommerce', 'action' => $woocommerce_ready ? 'WooCommerce is active.' : 'Install and activate WooCommerce.' ),
		array( 'ready' => (bool) get_option( 'permalink_structure' ), 'label' => 'Product-friendly permalinks', 'action' => get_option( 'permalink_structure' ) ? 'A custom permalink structure is active.' : 'Choose Post name under Settings → Permalinks.' ),
		array( 'ready' => is_ssl(), 'label' => 'HTTPS', 'action' => is_ssl() ? 'The current admin request uses HTTPS.' : 'Enable SSL and force HTTPS after connecting the domain.' ),
		array( 'ready' => $contact_ready, 'label' => 'Real customer-service email', 'action' => $contact_ready ? 'The WordPress admin email is ready for customer contact.' : 'Set a real admin email under Settings → General.' ),
		array( 'ready' => $privacy_ready, 'label' => 'Published privacy policy', 'action' => $privacy_ready ? 'A published privacy-policy page is assigned.' : 'Create and assign it under Settings → Privacy.' ),
		array( 'ready' => $woocommerce_ready && (bool) get_option( 'woocommerce_store_address' ), 'label' => 'Store address', 'action' => get_option( 'woocommerce_store_address' ) ? 'WooCommerce has a store address.' : 'Complete WooCommerce → Settings → General.' ),
		array( 'ready' => $gateways_ready, 'label' => 'Online payment gateway', 'action' => $gateways_ready ? 'At least one non-manual gateway is enabled.' : 'Connect Stripe, PayPal, or the selected online payment provider.' ),
		array( 'ready' => $shipping_ready, 'label' => 'Shipping method', 'action' => $shipping_ready ? 'At least one enabled shipping method was found.' : 'Add methods for the US, UK, EU, and other target regions.' ),
		array( 'ready' => false, 'label' => 'Production specifications verified', 'action' => 'Confirm sizes, materials, compatibility, battery runtime, packaging, and dispatch times with suppliers before launch.' ),
	);
}

function gridline_starter_products() {
	return array(
		array(
			'slug' => 'must-be-the-water-radio-polo', 'name' => 'Must Be the Water Radio Polo', 'price' => '44', 'image' => 'apexik-water-polo.png', 'categories' => array( 'Radio Apparel', 'Best Sellers' ), 'options' => array( 'Size' => array( 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL' ) ),
			'short' => 'A racing-red technical polo built around one of the radio archive’s driest explanations for unexpected pace.',
			'description' => 'Premium team radio quote polo featuring MUST BE THE WATER, a restrained chest waveform, and APEXIK-original channel ticks. Designed for race weekends, travel days, and fans who prefer references over logos.',
			'meta' => array(
				'eyebrow' => 'Radio drop 01 / Racing red', 'subtitle' => 'Unexpected pace. One very simple explanation.',
				'radio_channel' => 'AUS 25 / RADIO ARCHIVE', 'radio_highlight' => 'Must be the water.', 'radio_context' => "CAR | Is there a leakage?\nPIT WALL | A leakage where?\nCAR | I have the seat full of water!\nPIT WALL | Must be the water.\nCAR | Let's add that to the words of wisdom.",
				'benefits' => "Breathable technical pique\nRelaxed unisex race-weekend fit\nAuthorized radio quote artwork",
				'what' => 'A lightweight technical polo translating an unexpectedly funny radio explanation into clean contemporary apparel. The front stays quiet with a small audio trace; the lower graphic carries the full radio moment.',
				'difference' => 'The quote is paired with Apexik-original waveforms, grids, and signal marks. There are no team logos, driver names, sponsor graphics, or copied liveries.',
				'materials' => '92% recycled polyester, 8% elastane technical pique', 'dimensions' => 'Relaxed unisex fit; planned XS–3XL', 'care' => 'Cold wash inside-out; line dry; do not iron print', 'dispatch' => 'Made to order; dispatches in 3–6 business days',
				'faq' => "How does it fit? | Relaxed through the chest with a straight hem. Choose your usual size for the intended fit.\nIs the phrase authorized? | Yes. The radio wording is used within the rights made available to the store and is paired with original APEXIK artwork.\nIs this official team merchandise? | No. APEXIK is an independent brand and this product does not use team or championship branding.",
				'seo_title' => 'Must Be the Water Radio Polo | Racing Quote Shirt | APEXIK', 'meta_description' => 'Shop the racing-red Must Be the Water technical polo: an under-$50 team radio quote shirt with original waveform and channel graphics.', 'keywords' => 'must be the water polo, team radio polo, motorsport quote apparel, race fan polo, team radio gift',
			),
		),
		array(
			'slug' => 'gp2-engine-pit-wall-hoodie', 'name' => 'GP2 Engine Pit Wall Hoodie', 'price' => '49', 'image' => 'apexik-gp2-hoodie.jpg', 'categories' => array( 'Radio Apparel', 'Best Sellers' ), 'options' => array( 'Size' => array( 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL' ) ),
			'short' => 'A heavyweight charcoal hoodie carrying one of team radio’s most unfiltered performance reviews.',
			'description' => 'Heavyweight motorsport quote hoodie featuring GP2 ENGINE across an original audio-analysis back panel and a minimal RADIO // 05 chest mark. Built as premium streetwear rather than replica team merchandise.',
			'meta' => array(
				'eyebrow' => 'Radio apparel / Unfiltered', 'subtitle' => 'The most direct engine review ever sent over the radio.',
				'radio_channel' => 'JPN 15 / RADIO ARCHIVE', 'radio_highlight' => 'GP2 engine, GP2.', 'radio_context' => "CAR | They pass me on the straights like in GP2.\nCAR | It is embarrassing, very embarrassing.\nCAR | GP2 engine, GP2. Aaargh!",
				'benefits' => "Heavyweight brushed fleece\nRelaxed unisex fit\nFront, back, and sleeve detailing",
				'what' => 'A substantial charcoal hoodie that turns a brutally concise team radio quote into an audio-analysis graphic. The front is minimal; the rear carries the full signal.',
				'difference' => 'Every waveform, timestamp, and interface element is drawn for Apexik. The design contains no vehicle, team crest, driver name, sponsor, or official series mark.',
				'materials' => '80% cotton, 20% recycled polyester, 360 gsm', 'dimensions' => 'Relaxed unisex fit; planned XS–3XL', 'care' => 'Cold wash inside-out; low tumble or line dry', 'dispatch' => 'Made to order; dispatches in 3–6 business days',
				'faq' => "Is the back print oversized? | Yes. The rear graphic is designed as the primary statement.\nIs it fleece-lined? | The planned blank uses a soft brushed interior suitable for cool race weekends.\nIs this official merchandise? | No. It is independent radio-culture apparel without team, driver, or championship branding.",
				'seo_title' => 'GP2 Engine Team Radio Hoodie | Motorsport Quote Gift | APEXIK', 'meta_description' => 'Heavyweight GP2 Engine hoodie with original radio waveform artwork. Independent motorsport quote apparel for race fans, priced under $50.', 'keywords' => 'GP2 engine hoodie, team radio hoodie, motorsport quote sweatshirt, race fan gift, pit wall apparel',
			),
		),
		array(
			'slug' => 'must-be-the-water-radio-mug', 'name' => 'Must Be the Water Radio Mug', 'price' => '22', 'image' => 'apexik-water-mug.jpg', 'categories' => array( 'Drinkware', 'Small Gifts' ),
			'short' => 'An off-white team radio mug for unexplained pace, suspicious hydration, and early starts.',
			'description' => 'A 12 oz ceramic racing quote mug featuring MUST BE THE WATER, an original audio waveform, and Apexik Radio Goods detailing. A compact gift for motorsport fans and team radio collectors.',
			'meta' => array(
				'eyebrow' => 'Drinkware / Radio archive', 'subtitle' => 'For the mornings when the pace needs an explanation.',
				'radio_channel' => 'AUS 25 / RADIO ARCHIVE', 'radio_highlight' => 'Must be the water.', 'radio_context' => "CAR | I have the seat full of water!\nPIT WALL | Must be the water.\nCAR | Let's add that to the words of wisdom.",
				'benefits' => "12 oz durable ceramic\nDishwasher-safe planned print\nGift-ready under $25",
				'what' => 'A warm off-white ceramic mug built around an authorized team radio phrase and an original audio trace. It brings team radio humor to desks without borrowed identity.',
				'difference' => 'The typography and waveform are entirely Apexik-made. No team color block, driver name, sponsor, or championship mark appears on the product.',
				'materials' => 'Glazed ceramic with durable wrap print', 'dimensions' => '12 oz / 355 ml', 'care' => 'Dishwasher safe; microwave testing required before launch', 'dispatch' => 'Printed to order; dispatches in 3–5 business days',
				'faq' => "Is the print on both sides? | The planned wrap design remains visible from either side.\nDoes it arrive boxed? | Yes. A protective recyclable gift box is planned.\nIs it official team merchandise? | No. It is an independent radio-quote design.",
				'seo_title' => 'Must Be the Water Racing Quote Mug | Motorsport Fan Gift', 'meta_description' => 'A 12 oz Must Be the Water team radio mug with original waveform artwork. An affordable motorsport meme gift for race fans.', 'keywords' => 'racing quote mug, must be the water mug, motorsport fan gift, team radio mug, race weekend coffee mug',
			),
		),
		array(
			'slug' => 'simply-lovely-insulated-tumbler', 'name' => 'Simply Lovely Insulated Tumbler', 'price' => '32', 'image' => 'apexik-simply-tumbler.jpg', 'categories' => array( 'Drinkware', 'Best Sellers' ),
			'short' => 'A midnight-blue thermal tumbler with a 33 / MV code and a radio phrase made for victory laps.',
			'description' => '20 oz insulated race fan tumbler featuring SIMPLY LOVELY, a coded 33 / MV detail, and Apexik-original signal graphics. Sized for commutes, flights, grandstands, and full race weekends.',
			'meta' => array(
				'eyebrow' => 'Drinkware / 33 MV', 'subtitle' => 'Cold for the formation lap. Still cold at the chequered flag.',
				'radio_channel' => 'NED 24 / CHEQUERED FLAG', 'radio_highlight' => 'Simply lovely', 'radio_context' => "CAR | Uh huh. Simply lovely, huh? Well done.",
				'benefits' => "Double-wall stainless steel\n20 oz travel size\nSplash-resistant clear lid",
				'what' => 'A tall insulated tumbler that translates a celebratory radio phrase into a clean travel object. The coded number and initials reward the fan who recognizes the reference.',
				'difference' => 'The custom navy, orange, and off-white system belongs to Apexik and does not reproduce a team livery. There are no names, logos, cars, or sponsor marks.',
				'materials' => 'Food-grade stainless steel, BPA-free lid', 'dimensions' => '20 oz / 590 ml; standard cup-holder base', 'care' => 'Hand wash recommended; lid top-rack dishwasher safe', 'dispatch' => 'Dispatches in 3–5 business days',
				'faq' => "How long will it keep drinks cold? | Final production testing will confirm the launch specification; the target is 8+ hours.\nWhat does 33 / MV mean? | It is a coded fan reference without a driver or team name.\nIs the lid leakproof? | It is planned as splash-resistant rather than fully leakproof.",
				'seo_title' => 'Simply Lovely Racing Tumbler | 33 MV Motorsport Gift | APEXIK', 'meta_description' => '20 oz Simply Lovely insulated tumbler with coded 33 / MV detail. A practical team radio gift for travel and race weekends.', 'keywords' => 'simply lovely tumbler, racing fan tumbler, motorsport drinkware, race weekend gift, 33 MV cup',
			),
		),
		array(
			'slug' => 'radio-check-race-fan-accessory-kit', 'name' => 'Radio Check Accessory Kit', 'price' => '28', 'image' => 'apexik-radio-accessory-kit.jpg', 'categories' => array( 'Small Gifts', 'Desk & Tech' ),
			'short' => 'A giftable set of BOX BOX key tag, RADIO ON watch strap, weatherproof stickers, and BOX / STAY OUT notes.',
			'description' => 'Four race-fan essentials in one compact gift kit: embroidered key tag, silicone watch strap, four radio-signal stickers, and a two-column pit-wall sticky-note pad.',
			'meta' => array(
				'eyebrow' => 'Small gifts / Four-piece kit', 'subtitle' => 'The whole radio channel, packed for desks, keys, laptops, and wrists.',
				'benefits' => "Four coordinated accessories\nWeatherproof vinyl stickers\nGift-ready rigid envelope",
				'what' => 'A compact accessories bundle that spreads the Apexik radio system across the things race fans already carry: keys, watch, laptop, and strategy notes.',
				'difference' => 'The kit uses generic command language and Apexik-original radio icons. It stays recognizable without relying on a team crest, vehicle, driver name, or copied graphic.',
				'materials' => 'Embroidered fabric, silicone, weatherproof vinyl, FSC paper', 'dimensions' => 'Watch strap compatibility and sizes to be confirmed', 'care' => 'Wipe accessories clean; keep paper pad dry', 'dispatch' => 'Dispatches in 2–4 business days',
				'faq' => "What is included? | One BOX BOX key tag, one RADIO ON watch strap, four stickers, and one BOX / STAY OUT note pad.\nWill the strap fit my watch? | Final listing will include exact lug width and compatible models.\nAre the stickers weatherproof? | The planned vinyl is UV- and water-resistant for bottles, laptops, and car windows.",
				'seo_title' => 'Team Radio Accessory Gift Kit | Key Tag Stickers Notes', 'meta_description' => 'A four-piece team radio gift kit with Box Box key tag, watch strap, weatherproof stickers and pit-wall sticky notes.', 'keywords' => 'racing keychain, motorsport sticker pack, race fan watch strap, racing sticky notes, small motorsport gifts',
			),
		),
		array(
			'slug' => 'change-your-fxxking-car-desk-mat', 'name' => 'Change Your FXXking Car Desk Mat', 'price' => '39', 'image' => 'apexik-unfiltered-desk-mat.jpg', 'categories' => array( 'Desk & Tech', 'Radio Unfiltered' ), 'options' => array( 'Size' => array( '800 x 300 mm', '900 x 400 mm', '1000 x 500 mm' ), 'Style' => array( 'Standard Edge', 'Stitched Edge' ) ),
			'short' => 'An oversized stitched desk mat for the most direct radio instruction in the archive.',
			'description' => 'Extended motorsport desk mat featuring CHANGE YOUR FXXKING CAR across an original waveform, strategy grid, and unfiltered radio interface. Sized for keyboard, mouse, and race control arguments.',
			'meta' => array(
				'eyebrow' => 'Desk & tech / Radio unfiltered', 'subtitle' => 'Subtle was never an option on this channel.',
				'radio_channel' => 'PIT WALL / UNFILTERED', 'radio_highlight' => 'change your f***ing car', 'radio_context' => "MEETING | I have a printout here.\nMEETING | If you've got a problem, change your f***ing car.",
				'benefits' => "Extended keyboard-and-mouse size\nStitched anti-fray edge\nNon-slip natural rubber base",
				'what' => 'A wide desk mat turning an uncensored radio command into a full pit-wall workstation graphic. The surface balances bold type with functional mouse tracking.',
				'difference' => 'It references radio culture through an authorized phrase and wholly original data visuals—not footage, timing data, broadcast graphics, or team identity.',
				'materials' => 'Smooth polyester surface, 3 mm natural rubber base', 'dimensions' => 'Approx. 90 × 40 cm / 35.4 × 15.7 in', 'care' => 'Spot clean; roll print-side out for storage', 'dispatch' => 'Printed to order; dispatches in 3–6 business days',
				'faq' => "Is the wording censored? | The design uses FXXKING exactly as shown in the product title and images.\nWill it fit a full-size keyboard? | Yes. The planned 90 cm width fits a full keyboard and mouse area.\nIs it official merchandise? | No. It is independent radio-culture desk gear.",
				'seo_title' => 'Change Your FXXking Car Racing Desk Mat | Radio Quote Gift', 'meta_description' => 'Oversized team radio quote desk mat with stitched edge, non-slip base and original pit-wall waveform graphics.', 'keywords' => 'racing desk mat, motorsport mouse pad, radio quote desk pad, race fan office gift, pit wall desk setup',
			),
		),
		array(
			'slug' => 'lights-out-trackside-viewing-kit', 'name' => 'Lights Out Trackside Viewing Kit', 'price' => '49', 'image' => 'apexik-viewing-kit.jpg', 'categories' => array( 'Race Day Gear', 'Gifts' ),
			'short' => 'A lightweight roll-up grass mat and compact neck fan for long, warm race-day sessions.',
			'description' => 'Portable race-day viewing kit pairing a waterproof-backed roll mat with a rechargeable bladeless neck fan and drawstring pouch. Built for grass banks, grandstand approaches, and outdoor watch parties.',
			'meta' => array(
				'eyebrow' => 'Race day gear / Two-piece kit', 'subtitle' => 'Less carrying. More comfortable laps.',
				'benefits' => "Roll-up waterproof-backed mat\nRechargeable bladeless neck fan\nCoordinated storage pouch",
				'what' => 'A compact comfort kit for fans who spend full days outdoors. The mat rolls down to shoulder-carry size; the neck fan keeps hands free for tickets, drinks, and radios.',
				'difference' => 'Designed around real spectator friction rather than display-only memorabilia. The visual language remains independent, practical, and easy to pack.',
				'materials' => 'Polyester top, waterproof PEVA backing, ABS neck fan', 'dimensions' => 'Target mat size: 145 × 100 cm; final fan weight to be confirmed', 'care' => 'Wipe mat underside; air dry fully; charge fan with supplied cable', 'dispatch' => 'Dispatches in 3–5 business days',
				'faq' => "Is the fan rechargeable? | Yes. The planned unit charges by USB-C; final battery runtime will be confirmed before launch.\nHow small does the mat roll? | The target packed width is approximately 25 cm.\nCan I take it into every venue? | Venue rules vary. Check permitted bag and device policies before travelling.",
				'seo_title' => 'Lightweight Race Day Viewing Kit | Grass Mat & Neck Fan', 'meta_description' => 'Portable race-day viewing kit with waterproof grass mat and rechargeable neck fan. Lightweight spectator comfort gear under $50.', 'keywords' => 'race day viewing kit, portable grass mat, neck fan for sporting events, trackside fan gear, motorsport spectator gifts',
			),
		),
		array(
			'slug' => 'fold-to-carry-tote-charm', 'name' => 'Fold-to-Carry Tote Charm', 'price' => '24', 'image' => 'apexik-fold-tote-charm.png', 'categories' => array( 'Everyday Carry', 'Gifts' ),
			'short' => 'A pocket-size clip-on pouch that unfolds into a lightweight carry tote when you need one.',
			'description' => 'A compact ripstop tote designed to live on your keys, bag, or belt loop. Pull it from its pouch and it opens into a useful everyday carry bag—one item, no assembly, no electronics, and no licensed references.',
			'meta' => array(
				'eyebrow' => 'Everyday carry / Transform', 'subtitle' => 'From pocket to tote.',
				'benefits' => "Clip-on pocket pouch\nUnfolds into one lightweight tote\nNo battery, magnet, or assembly",
				'what' => 'A small carry solution with a satisfying transformation: a compact pouch becomes a full-size reusable tote in seconds. It is intentionally quiet, practical, and designed for everyday errands, travel, and unexpected extra items.',
				'difference' => 'The signature angular fold pattern and compact clip-on format make it feel like an object you want to carry, not just a bag you remember to pack.',
				'materials' => 'Recycled ripstop nylon, zinc-alloy clip, recycled polyester pouch lining', 'dimensions' => 'Target capacity 12–15 L; final packed and unfolded dimensions confirmed after sampling', 'care' => 'Cold hand wash; air dry fully before folding', 'dispatch' => 'Coming soon — supplier sample and load testing in progress',
				'faq' => "Is this a bundle? | No. The pouch and tote are a single integrated product.\nDoes it use a magnet? | No. The planned design uses a zip pouch and mechanical clip only.\nWhen can I order? | Join the launch list. Final material, dimensions, and production timing will be published after sampling.",
				'seo_title' => 'Foldable Tote Bag Charm | Clip-On Reusable Carry Tote | APEXIK', 'meta_description' => 'A compact foldable tote bag charm that clips to your bag and opens into a lightweight reusable carry tote. Coming soon from APEXIK.', 'keywords' => 'foldable tote bag charm, clip on reusable tote, compact shopping bag, travel tote pouch, giftable everyday carry', 'coming_soon' => '1', 'generic_product' => '1',
			),
		),
		array(
			'slug' => 'pivot-key-stand', 'name' => 'Pivot Key Stand', 'price' => '19', 'image' => 'apexik-pivot-key-stand.png', 'categories' => array( 'Everyday Carry', 'Gifts' ),
			'short' => 'A compact keychain that pivots open into a stable phone stand—no magnet, battery, or adhesive.',
			'description' => 'Pivot Key Stand is an original compact aluminum object that lives on a keyring and opens into a desk-ready phone stand. Its mechanical hinge and triangular support are designed for hands-free calls, recipes, videos, and travel.',
			'meta' => array(
				'eyebrow' => 'Everyday carry / Pivot', 'subtitle' => 'Unfold. Set. Go.',
				'benefits' => "Keyring-ready compact form\nMechanical hinge with stable triangular support\nNo magnet, battery, adhesive, or app",
				'what' => 'A genuinely useful object for the moments a phone needs to stand up. Closed, it stays compact with your keys. Open, its hinged geometry forms a stable support for a phone in portrait or landscape orientation.',
				'difference' => 'The product is designed around a clear one-motion demonstration rather than a multi-piece kit. A controlled hinge and distinctive geometry give it an ownable visual character.',
				'materials' => 'Anodized aluminum alloy, stainless-steel pivot, silicone contact pads', 'dimensions' => 'Target closed length under 70 mm; final compatibility and load limit confirmed after testing', 'care' => 'Wipe clean with a dry microfiber cloth', 'dispatch' => 'Coming soon — hinge-cycle and compatibility testing in progress',
				'faq' => "Does it use a magnet? | No. The planned stand is entirely mechanical.\nWill it fit every phone? | Final compatible phone size and case thickness will be confirmed after load testing.\nWhen can I order? | Join the launch list for final specifications and availability.",
				'seo_title' => 'Keychain Phone Stand | Compact Folding Aluminum Stand | APEXIK', 'meta_description' => 'A compact folding keychain phone stand with a mechanical hinge and stable triangular support. No magnet, battery, or adhesive. Coming soon.', 'keywords' => 'keychain phone stand, folding phone stand, compact aluminum phone holder, travel phone stand, non magnetic phone stand', 'coming_soon' => '1', 'generic_product' => '1',
			),
		),
		array(
			'slug' => 'kinetic-focus-dial', 'name' => 'Kinetic Focus Dial', 'price' => '29', 'image' => 'apexik-focus-dial.png', 'categories' => array( 'Everyday Carry', 'Gifts' ),
			'short' => 'A palm-sized mechanical dial with a tactile turn—made for a deliberate reset between tasks.',
			'description' => 'Kinetic Focus Dial is a non-electronic, non-magnetic mechanical desk token. Turn its precision edge for a quiet, physical pause before a meeting, a study session, or the next task. It is one compact, giftable object rather than a multi-piece set.',
			'meta' => array(
				'eyebrow' => 'Everyday carry / Mechanical', 'subtitle' => 'Turn the dial. Start again.',
				'benefits' => "Purely mechanical tactile dial\nNo battery, magnet, or screen\nPocketable single-piece gift",
				'what' => 'A tactile object for people who think better with something physical in their hands. The dial is designed to offer controlled resistance and clear mechanical feedback—an intentional micro-ritual before you begin again.',
				'difference' => 'Rather than adding features, it focuses on one satisfying mechanical action. The form is deliberately giftable, durable, and visually distinct on a desk or in a pocket.',
				'materials' => 'Anodized aluminum body, stainless-steel mechanical ratchet components', 'dimensions' => 'Target diameter 42 mm; final thickness, mechanism feel, and weight confirmed after sampling', 'care' => 'Keep dry; wipe with a soft cloth; do not disassemble', 'dispatch' => 'Coming soon — mechanical durability and safety testing in progress',
				'faq' => "Is it magnetic? | No. The planned feedback mechanism is purely mechanical.\nDoes it need charging? | No. It has no battery or electronic components.\nIs it a toy? | It is a desk and everyday-carry accessory; final age guidance will be confirmed before sale.",
				'seo_title' => 'Mechanical Focus Dial | Non-Magnetic Tactile Desk Gift | APEXIK', 'meta_description' => 'A compact non-magnetic mechanical focus dial with a tactile precision turn. A thoughtful desk and everyday-carry gift. Coming soon.', 'keywords' => 'mechanical focus dial, tactile desk accessory, non magnetic fidget dial, metal desk gift, analog focus tool', 'coming_soon' => '1', 'generic_product' => '1',
			),
		),
		array(
			'slug' => 'flat-pack-eyewear-vault', 'name' => 'Flat-Pack Eyewear Vault', 'price' => '22', 'image' => 'apexik-flatpack-eyewear-case.png', 'categories' => array( 'Everyday Carry', 'Gifts' ),
			'short' => 'A fold-flat eyewear case that turns into a structured protective vault when your glasses need it.',
			'description' => 'Flat-Pack Eyewear Vault uses an original fold structure to stay slim when empty and form a protective case when in use. Its planned closure uses an elastic loop and mechanical tab—no magnet, no electronics, and no licensed references.',
			'meta' => array(
				'eyebrow' => 'Everyday carry / Flat-pack', 'subtitle' => 'Flat when empty. Ready when needed.',
				'benefits' => "Folds flat for storage\nStructured when in use\nNon-magnetic elastic-loop closure",
				'what' => 'A foldable solution for eyewear that does not waste space when it is empty. The case is designed to open quickly into a protective shape, then return to a slim format when your glasses are out.',
				'difference' => 'The transformation is the product: a small, considered fold pattern turns a normally bulky case into a useful travel object with a strong visual demonstration.',
				'materials' => 'Vegan leather outer, recycled microfiber lining, elastic loop, mechanical tab closure', 'dimensions' => 'Target fit for most standard sunglasses and reading glasses; final fit dimensions confirmed after sampling', 'care' => 'Wipe clean with a damp cloth; air dry', 'dispatch' => 'Coming soon — fold-cycle and drop testing in progress',
				'faq' => "Does the case use magnets? | No. The planned closure is elastic and mechanical.\nWill oversized sunglasses fit? | The final internal dimensions will be published after sample testing.\nWhen can I buy it? | Join the launch list to receive the production release date.",
				'seo_title' => 'Flat Fold Eyeglass Case | Non-Magnetic Travel Eyewear Case | APEXIK', 'meta_description' => 'A flat-fold eyewear case that opens into a structured protective vault. Non-magnetic, compact, and giftable. Coming soon from APEXIK.', 'keywords' => 'flat fold eyeglass case, non magnetic eyewear case, compact travel sunglasses case, foldable glasses case, giftable travel accessory', 'coming_soon' => '1', 'generic_product' => '1',
			),
		),
		array(
			'slug' => 'quiet-key-orbit', 'name' => 'Quiet Key Orbit', 'price' => '26', 'image' => 'apexik-quiet-key-orbit.png', 'categories' => array( 'Everyday Carry', 'Gifts' ),
			'short' => 'A compact mechanical key organizer that turns a noisy key bundle into quiet, one-handed access.',
			'description' => 'Quiet Key Orbit is an original compact key organizer for everyday carry. It holds a small set of keys in a flat, controlled stack and lets you fan out the one you need. It is a single mechanical accessory with no magnetic or electronic parts.',
			'meta' => array(
				'eyebrow' => 'Everyday carry / Quiet', 'subtitle' => 'Quiet carry. Quick access.',
				'benefits' => "Organizes a small key set\nAdjustable mechanical pivot tension\nNo magnet, battery, or extra pieces",
				'what' => 'A simple fix for loose, noisy keys. The organizer holds keys in a neat stack and lets you select one with a controlled, one-handed motion—less bulk in a pocket and less clutter on a desk.',
				'difference' => 'It is designed as a single-object upgrade, with an ownable angular frame, visible pivot detail, and a practical adjustment system instead of a complicated kit.',
				'materials' => 'Anodized aluminum frame, stainless-steel hardware, non-slip fiber spacers', 'dimensions' => 'Target capacity 2–6 standard keys; final key thickness and hardware compatibility confirmed after testing', 'care' => 'Check pivot tension occasionally; wipe dry after moisture exposure', 'dispatch' => 'Coming soon — key compatibility and anti-loosening tests in progress',
				'faq' => "Does it contain a magnet? | No. The planned organizer is fully mechanical.\nHow many keys does it hold? | The target capacity is 2–6 standard keys, subject to final sample validation.\nIs it a multi-piece kit? | No. It is one organizer with its installed mechanical hardware.",
				'seo_title' => 'Compact Key Organizer | Quiet Mechanical Key Holder | APEXIK', 'meta_description' => 'A compact mechanical key organizer for quiet, one-handed key access. Holds a small key set with no magnetic or electronic parts. Coming soon.', 'keywords' => 'compact key organizer, quiet key holder, mechanical key organizer, aluminum key holder, everyday carry gift', 'coming_soon' => '1', 'generic_product' => '1',
			),
		),
	);
}

function gridline_starter_install() {
	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Product_Simple' ) ) {
		return new WP_Error( 'missing_woocommerce', 'WooCommerce must be active before installing starter content.' );
	}

	$pages = array(
		'home' => array( 'Home', '' ),
		'about' => array( 'About', '<h2>We turn the radio up.</h2><p>APEXIK translates the funniest, sharpest, and most chaotic moments from team radio into apparel, drinkware, desk gear, and trackside essentials. Authorized phrases meet an original visual system of waveforms, channel codes, strategy grids, and signal marks.</p><h2>References, not replicas</h2><p>We do not use team logos, driver names, sponsor graphics, copied liveries, or official championship branding. APEXIK is an independent radio-culture brand.</p>' ),
		'faq' => array( 'FAQ', '<h2>Are these official team products?</h2><p>No. APEXIK is independent and does not use team, driver, sponsor, or championship branding. Radio phrases are used within the rights made available to the store.</p><h2>How quickly do orders ship?</h2><p>Estimated production and dispatch times appear on each product page. A tracking link is emailed after fulfilment.</p><h2>Can I return an item?</h2><p>Unused products can be returned within 30 days of delivery. Personalized, worn, washed, hygiene-sensitive, and final-sale items may be excluded where local law permits.</p><h2>Will apparel sizing be the same for every product?</h2><p>No. Use the size guide on the relevant product page and check the garment measurements before ordering.</p>' ),
		'contact' => array( 'Contact', '[gridline_contact]' ),
		'shipping-returns' => array( 'Shipping & Returns', '<h2>Shipping</h2><p>Available destinations, rates, and delivery estimates are shown at checkout. Dispatch estimates exclude weekends, customs delays, and carrier disruptions. Import taxes or duties may apply where they are not collected at checkout.</p><h2>Returns</h2><p>Contact us within 30 days of delivery before sending anything back. Products must be unused, unworn, unwashed, and returned with original packaging. Personalized, hygiene-sensitive, and final-sale products may be excluded where local law permits.</p><h2>Damaged or incorrect orders</h2><p>Contact us promptly with your order number and clear photographs of the product and packaging so the team can assess a replacement or refund.</p><p><em>Final destinations, prices, delivery windows, and return addresses will be confirmed before launch.</em></p>' ),
		'privacy-policy' => array( 'Privacy Policy', '<h2>Information we collect</h2><p>We may collect account, contact, delivery, payment-status, device, and order information needed to operate the store. Card details are processed by the selected payment provider and are not stored directly by APEXIK.</p><h2>How information is used</h2><p>Information may be used to fulfil orders, provide support, prevent fraud, maintain the store, meet legal obligations, and—with consent—send marketing communications.</p><h2>Sharing and retention</h2><p>Information may be shared with service providers such as hosting, payment, analytics, email, and fulfilment partners only as needed to provide their services. Records are retained only as long as required for the stated purpose and applicable law.</p><h2>Your choices</h2><p>You may request access, correction, deletion, or other rights available in your region by using the Contact page. Marketing consent can be withdrawn at any time.</p><p><em>This policy must be reviewed with the final business entity, service providers, cookie tools, and selling regions before launch.</em></p>' ),
		'terms-conditions' => array( 'Terms & Conditions', '<h2>Store terms</h2><p>By placing an order, you agree to the product information, price, delivery estimate, and policies displayed at checkout. An order is accepted only after payment is authorized and an order confirmation is issued.</p><h2>Products and pricing</h2><p>We aim to describe products accurately, but screen colors and handmade or printed finishes may vary slightly. Prices and availability can change before an order is accepted.</p><h2>Independent brand</h2><p>APEXIK is an independent team radio culture brand and is not affiliated with any team, driver, sponsor, series, or championship.</p><h2>Liability and governing terms</h2><p>Nothing in these terms limits rights that cannot legally be limited. Final business identity, governing law, dispute terms, tax treatment, and regional consumer-rights language must be completed before launch.</p>' ),
		'cookie-policy' => array( 'Cookie Policy', '<h2>How cookies are used</h2><p>Essential cookies may keep the cart, checkout, account, security, and site preferences working. Optional analytics or marketing cookies should run only after consent where required.</p><h2>Your choices</h2><p>The production site will provide a consent control for optional cookies. You can also remove stored cookies using your browser settings, although essential store functions may then stop working.</p><h2>Final configuration</h2><p>The final cookie list, providers, purposes, and retention periods will be generated after hosting, analytics, advertising, payment, and email services are selected.</p>' ),
		'size-guide' => array( 'Size Guide', '<h2>How to choose your size</h2><p>Lay a similar garment flat, measure across the chest from seam to seam, and compare it with the final product chart. For a relaxed fit, select the larger size when you fall between measurements.</p><h2>Launch sizes</h2><p>Polo and hoodie options are configured from XS to 3XL. Exact garment measurements, tolerance, model size, and fit photography will be added after supplier samples are approved.</p><h2>Before ordering</h2><p>Do not rely on a size used by another brand. Product measurements can vary by blank, material, and production region.</p>' ),
	);
	if ( in_array( get_option( 'blogname' ), array( '', 'My WordPress', 'Gridline Club' ), true ) ) {
		update_option( 'blogname', 'APEXIK' );
	}
	if ( ! get_option( 'blogdescription' ) || 'Just another WordPress site' === get_option( 'blogdescription' ) ) {
		update_option( 'blogdescription', 'Team radio quote apparel, gifts, drinkware and trackside gear under $50.' );
	}

	$page_ids = array();
	foreach ( $pages as $slug => $page ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		$data = array( 'post_title' => $page[0], 'post_name' => $slug, 'post_content' => $page[1], 'post_status' => 'publish', 'post_type' => 'page' );
		$page_id = $existing ? wp_update_post( array_merge( $data, array( 'ID' => $existing->ID ) ) ) : wp_insert_post( $data );
		if ( is_wp_error( $page_id ) ) {
			return $page_id;
		}
		$page_ids[ $slug ] = (int) $page_id;
	}
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', (int) $page_ids['home'] );
	update_option( 'wp_page_for_privacy_policy', (int) $page_ids['privacy-policy'] );

	$shop_id = wc_get_page_id( 'shop' );
	if ( $shop_id <= 0 || 'trash' === get_post_status( $shop_id ) ) {
		$shop_id = wp_insert_post( array( 'post_title' => 'Shop', 'post_name' => 'shop', 'post_status' => 'publish', 'post_type' => 'page' ) );
		if ( is_wp_error( $shop_id ) ) {
			return $shop_id;
		}
		update_option( 'woocommerce_shop_page_id', (int) $shop_id );
	}

	$menu_name = 'APEXIK Primary';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? $menu->term_id : wp_create_nav_menu( $menu_name );
	if ( ! is_wp_error( $menu_id ) ) {
		$existing_items = wp_get_nav_menu_items( $menu_id );
		if ( empty( $existing_items ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Shop', 'menu-item-object' => 'page', 'menu-item-object-id' => $shop_id, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'New Drops', 'menu-item-url' => add_query_arg( 'orderby', 'date', get_permalink( $shop_id ) ), 'menu-item-type' => 'custom', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Gifts', 'menu-item-url' => home_url( '/product-category/gifts/' ), 'menu-item-type' => 'custom', 'menu-item-status' => 'publish' ) );
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => 'Our Story', 'menu-item-object' => 'page', 'menu-item-object-id' => $page_ids['about'], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
		}
		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	$footer_menu_name = 'APEXIK Footer';
	$footer_menu      = wp_get_nav_menu_object( $footer_menu_name );
	$footer_menu_id   = $footer_menu ? $footer_menu->term_id : wp_create_nav_menu( $footer_menu_name );
	if ( ! is_wp_error( $footer_menu_id ) ) {
		$footer_items = wp_get_nav_menu_items( $footer_menu_id );
		if ( empty( $footer_items ) ) {
			foreach ( array( 'about', 'faq', 'shipping-returns', 'contact', 'privacy-policy', 'terms-conditions', 'cookie-policy' ) as $footer_slug ) {
				wp_update_nav_menu_item( $footer_menu_id, 0, array( 'menu-item-title' => get_the_title( $page_ids[ $footer_slug ] ), 'menu-item-object' => 'page', 'menu-item-object-id' => $page_ids[ $footer_slug ], 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			}
		}
		$locations           = get_theme_mod( 'nav_menu_locations', array() );
		$locations['footer'] = $footer_menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	foreach ( gridline_starter_products() as $item ) {
		$category_ids = array();
		foreach ( $item['categories'] as $category_name ) {
			$term = term_exists( $category_name, 'product_cat' );
			$descriptions = gridline_starter_category_descriptions();
			$description  = isset( $descriptions[ $category_name ] ) ? $descriptions[ $category_name ] : '';
			if ( ! $term ) {
				$term = wp_insert_term( $category_name, 'product_cat', array( 'description' => $description ) );
			} elseif ( $description ) {
				$term_id = is_array( $term ) ? $term['term_id'] : $term;
				wp_update_term( (int) $term_id, 'product_cat', array( 'description' => $description ) );
			}
			if ( ! is_wp_error( $term ) ) {
				$category_ids[] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
			}
		}

		$existing    = get_page_by_path( $item['slug'], OBJECT, 'product' );
		$has_options = ! empty( $item['options'] );
		if ( $existing && $has_options ) {
			wp_set_object_terms( $existing->ID, 'variable', 'product_type' );
			$product = new WC_Product_Variable( $existing->ID );
		} elseif ( $existing ) {
			wp_set_object_terms( $existing->ID, 'simple', 'product_type' );
			$product = new WC_Product_Simple( $existing->ID );
		} else {
			$product = $has_options ? new WC_Product_Variable() : new WC_Product_Simple();
		}
		$product->set_name( $item['name'] );
		$product->set_slug( $item['slug'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_regular_price( $item['price'] );
		$product->set_price( $item['price'] );
		$product->set_short_description( $item['short'] );
		$product->set_description( $item['description'] );
		$product->set_category_ids( $category_ids );
		// Keep launch concepts visible in catalogues. The theme replaces their purchase CTA with a launch-update link.
		$product->set_stock_status( 'instock' );
		$product->set_featured( in_array( 'Best Sellers', $item['categories'], true ) );
		$product_id = $product->save();
		if ( $has_options ) {
			gridline_starter_configure_variations( $product, $item['options'], $item['price'] );
		}
		update_post_meta( $product_id, '_gridline_starter_item', '1' );
		foreach ( $item['meta'] as $key => $value ) {
			update_post_meta( $product_id, '_gridline_' . $key, $value );
		}
		$image_id = gridline_starter_import_image( $item['image'], $item['name'] );
		if ( $image_id && ! is_wp_error( $image_id ) ) {
			$product->set_image_id( $image_id );
			$product->save();
		}
	}
	$legacy_slugs = array( 'box-box-stay-out-radio-polo', 'night-circuit-ambient-lamp', 'tire-stack-desk-organizer', 'race-strategy-magnetic-board', 'telemetry-archive-art-print', 'apex-line-heavyweight-tee', 'pit-wall-strategy-coaster-set' );
	foreach ( $legacy_slugs as $legacy_slug ) {
		$legacy = get_page_by_path( $legacy_slug, OBJECT, 'product' );
		if ( $legacy && 'publish' === $legacy->post_status ) {
			wp_update_post( array( 'ID' => $legacy->ID, 'post_status' => 'draft' ) );
		}
	}
	update_option( 'gridline_starter_version', '1.8.1' );
	return true;
}

function gridline_starter_configure_variations( $product, $options, $price ) {
	if ( ! $product instanceof WC_Product_Variable ) {
		return;
	}
	$attributes = array();
	foreach ( $options as $name => $values ) {
		$attribute = new WC_Product_Attribute();
		$attribute->set_id( 0 );
		$attribute->set_name( $name );
		$attribute->set_options( array_values( $values ) );
		$attribute->set_position( count( $attributes ) );
		$attribute->set_visible( true );
		$attribute->set_variation( true );
		$attributes[] = $attribute;
	}
	$product->set_attributes( $attributes );
	$product->save();

	$existing_variations = array();
	foreach ( $product->get_children() as $variation_id ) {
		$variation  = new WC_Product_Variation( $variation_id );
		$attributes = $variation->get_attributes();
		if ( 1 === count( $attributes ) && isset( $attributes['size'] ) && isset( $options['Style'][0] ) ) {
			$attributes['style'] = $options['Style'][0];
			$variation->set_attributes( $attributes );
			$variation->save();
		}
		ksort( $attributes );
		$existing_variations[ wp_json_encode( $attributes ) ] = $variation_id;
	}

	$combinations = array( array() );
	foreach ( $options as $name => $values ) {
		$next = array();
		foreach ( $combinations as $combination ) {
			foreach ( $values as $value ) {
				$next[] = array_merge( $combination, array( sanitize_title( $name ) => $value ) );
			}
		}
		$combinations = $next;
	}

	foreach ( $combinations as $attributes ) {
		ksort( $attributes );
		$variation_key = wp_json_encode( $attributes );
		$variation = isset( $existing_variations[ $variation_key ] ) ? new WC_Product_Variation( $existing_variations[ $variation_key ] ) : new WC_Product_Variation();
		$variation->set_parent_id( $product->get_id() );
		$variation->set_attributes( $attributes );
		$variation->set_regular_price( $price );
		$variation->set_price( $price );
		$variation->set_status( 'publish' );
		$variation->set_stock_status( 'instock' );
		$variation_id = $variation->save();
		update_post_meta( $variation_id, '_gridline_starter_attributes', wp_json_encode( $attributes ) );
	}
	WC_Product_Variable::sync( $product->get_id() );
	wc_delete_product_transients( $product->get_id() );
}

function gridline_starter_import_image( $filename, $title ) {
	$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_gridline_source_image', 'meta_value' => $filename ) );
	if ( $existing ) {
		return $existing[0]->ID;
	}
	$path = plugin_dir_path( __FILE__ ) . 'assets/products/' . basename( $filename );
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	$bits = wp_upload_bits( basename( $filename ), null, file_get_contents( $path ) );
	if ( $bits['error'] ) {
		return new WP_Error( 'upload_error', $bits['error'] );
	}
	$filetype = wp_check_filetype( $bits['file'], null );
	$attachment_id = wp_insert_attachment( array( 'post_mime_type' => $filetype['type'], 'post_title' => sanitize_text_field( $title ), 'post_status' => 'inherit' ), $bits['file'] );
	if ( ! is_wp_error( $attachment_id ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $bits['file'] ) );
		update_post_meta( $attachment_id, '_gridline_source_image', $filename );
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', sanitize_text_field( $title ) );
	}
	return $attachment_id;
}
