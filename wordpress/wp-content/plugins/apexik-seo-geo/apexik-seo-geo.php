<?php
/**
 * Plugin Name: APEXIK SEO & GEO Architecture
 * Description: Rights-aware content entities, commercial landing pages, SEO controls, internal links and structured data for APEXIK.
 * Version: 1.0.0
 * Requires PHP: 8.1
 */

defined( 'ABSPATH' ) || exit;

function apexik_register_content_entities() {
	$entities = array(
		'apexik_circuit' => array( '赛道资料', 'Circuit', 'circuits', true, 'dashicons-location-alt' ),
		'apexik_moment'  => array( '比赛时刻', 'Race Moment', 'race-moments', true, 'dashicons-clock' ),
		'apexik_quote'   => array( '授权对话', 'Quote', 'quotes', false, 'dashicons-format-quote' ),
		'apexik_rights'  => array( '授权记录', 'Rights', 'rights', false, 'dashicons-shield' ),
	);
	foreach ( $entities as $type => $args ) {
		register_post_type( $type, array(
			'labels' => array( 'name' => $args[0], 'singular_name' => $args[1], 'add_new_item' => '添加' . $args[0], 'edit_item' => '编辑' . $args[0] ),
			'public' => $args[3], 'show_ui' => true, 'show_in_rest' => true, 'has_archive' => false,
			'rewrite' => $args[3] ? array( 'slug' => $args[2], 'with_front' => false ) : false,
			'menu_icon' => $args[4], 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
		) );
	}
}
add_action( 'init', 'apexik_register_content_entities' );

function apexik_seo_admin_menu() {
	add_submenu_page( 'apexik-store', 'SEO / GEO 架构', 'SEO / GEO 架构', 'manage_options', 'apexik-seo-geo', 'apexik_seo_dashboard' );
}
add_action( 'admin_menu', 'apexik_seo_admin_menu', 30 );

function apexik_seo_dashboard() {
	$links = array(
		array( '商业合集', '编辑 Team Radio 合集、产品品类和礼物指南。', admin_url( 'edit.php?post_type=page' ) ),
		array( '赛道资料', '维护赛道布局、地点、版本、来源和关联商品。', admin_url( 'edit.php?post_type=apexik_circuit' ) ),
		array( '比赛时刻', '维护年份、圈数、号码、情境、来源和关联对话。', admin_url( 'edit.php?post_type=apexik_moment' ) ),
		array( '授权对话', '逐条维护原话、地区、渠道、期限与可公开字段。', admin_url( 'edit.php?post_type=apexik_quote' ) ),
		array( '授权记录', '保存 license reference、blocked terms 与复核人。', admin_url( 'edit.php?post_type=apexik_rights' ) ),
		array( '商品 SEO', '每个商品编辑页底部都有关键词、Title、Meta 与关联实体字段。', admin_url( 'edit.php?post_type=product' ) ),
	);
	echo '<div class="wrap apexik-admin"><div class="apexik-admin-hero"><h1>APEXIK SEO / GEO 架构</h1><p>围绕 Team Radio × Product 建立可抓取、可引用、可维护的搜索资产；赛道内容仅在事实与授权核验后发布。</p></div><div class="apexik-admin-grid">';
	foreach ( $links as $link ) echo '<a class="apexik-admin-card" href="' . esc_url( $link[2] ) . '"><span class="dashicons dashicons-chart-area"></span><h2>' . esc_html( $link[0] ) . '</h2><p>' . esc_html( $link[1] ) . '</p></a>';
	echo '</div></div>';
}

function apexik_add_seo_box() {
	foreach ( array( 'page', 'product', 'apexik_circuit', 'apexik_moment' ) as $type ) {
		add_meta_box( 'apexik_seo_geo', 'APEXIK SEO / GEO', 'apexik_seo_box', $type, 'normal', 'high' );
	}
	foreach ( array( 'product', 'apexik_circuit', 'apexik_moment', 'apexik_quote', 'apexik_rights' ) as $type ) {
		add_meta_box( 'apexik_rights_check', 'APEXIK 权益与实体关系', 'apexik_rights_box', $type, 'side', 'high' );
	}
}
add_action( 'add_meta_boxes', 'apexik_add_seo_box' );

function apexik_seo_box( $post ) {
	wp_nonce_field( 'apexik_meta_save', 'apexik_meta_nonce' );
	$fields = array(
		'primary_keyword' => array( '主关键词', 'text' ), 'secondary_keywords' => array( '辅助关键词（逗号分隔）', 'textarea' ),
		'search_intent' => array( '搜索意图', 'text' ), 'seo_title' => array( 'SEO Title', 'text' ),
		'meta_description' => array( 'Meta Description', 'textarea' ), 'canonical' => array( 'Canonical（通常留空自动生成）', 'url' ),
		'robots' => array( 'Robots（index,follow 或 noindex,follow）', 'text' ), 'source_notes' => array( '事实来源 / 核验说明', 'textarea' ),
	);
	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $field ) {
		$value = get_post_meta( $post->ID, '_apexik_' . $key, true );
		echo '<tr><th><label for="apexik_' . esc_attr( $key ) . '">' . esc_html( $field[0] ) . '</label></th><td>';
		if ( 'textarea' === $field[1] ) echo '<textarea class="large-text" rows="3" id="apexik_' . esc_attr( $key ) . '" name="apexik_' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
		else echo '<input class="large-text" type="' . esc_attr( $field[1] ) . '" id="apexik_' . esc_attr( $key ) . '" name="apexik_' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
}

function apexik_rights_box( $post ) {
	wp_nonce_field( 'apexik_meta_save', 'apexik_meta_nonce' );
	$status = get_post_meta( $post->ID, '_apexik_rights_status', true );
	$blocked = get_post_meta( $post->ID, '_apexik_blocked_terms', true );
	$related = get_post_meta( $post->ID, '_apexik_related_entities', true );
	$license = get_post_meta( $post->ID, '_apexik_license_reference', true );
	$territories = get_post_meta( $post->ID, '_apexik_territories', true );
	$channels = get_post_meta( $post->ID, '_apexik_channels', true );
	$expiry = get_post_meta( $post->ID, '_apexik_rights_expiry', true );
	$reviewer = get_post_meta( $post->ID, '_apexik_reviewer', true );
	echo '<p><label><strong>授权状态</strong></label><select style="width:100%" name="apexik_rights_status"><option value="pending"' . selected( $status, 'pending', false ) . '>待核验</option><option value="approved"' . selected( $status, 'approved', false ) . '>已授权</option><option value="restricted"' . selected( $status, 'restricted', false ) . '>有限授权</option></select></p>';
	echo '<p><label><strong>禁止公开词</strong></label><textarea style="width:100%" rows="4" name="apexik_blocked_terms">' . esc_textarea( $blocked ) . '</textarea></p>';
	echo '<p><label><strong>关联实体 ID</strong></label><input style="width:100%" name="apexik_related_entities" value="' . esc_attr( $related ) . '"><small>用逗号分隔 Product / Circuit / Moment / Quote ID。</small></p>';
	echo '<p><label><strong>授权编号</strong></label><input style="width:100%" name="apexik_license_reference" value="' . esc_attr( $license ) . '"></p>';
	echo '<p><label><strong>销售地区</strong></label><input style="width:100%" name="apexik_territories" value="' . esc_attr( $territories ) . '" placeholder="EU, UK, AU, US"></p>';
	echo '<p><label><strong>允许渠道</strong></label><input style="width:100%" name="apexik_channels" value="' . esc_attr( $channels ) . '" placeholder="product, website, SEO, ads"></p>';
	echo '<p><label><strong>授权到期日</strong></label><input style="width:100%" type="date" name="apexik_rights_expiry" value="' . esc_attr( $expiry ) . '"></p>';
	echo '<p><label><strong>复核人</strong></label><input style="width:100%" name="apexik_reviewer" value="' . esc_attr( $reviewer ) . '"></p>';
}

function apexik_save_meta( $post_id ) {
	if ( ! isset( $_POST['apexik_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apexik_meta_nonce'] ) ), 'apexik_meta_save' ) || ! current_user_can( 'edit_post', $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) return;
	$fields = array( 'primary_keyword', 'secondary_keywords', 'search_intent', 'seo_title', 'meta_description', 'canonical', 'robots', 'source_notes', 'rights_status', 'blocked_terms', 'related_entities', 'license_reference', 'territories', 'channels', 'rights_expiry', 'reviewer' );
	foreach ( $fields as $key ) {
		$name = 'apexik_' . $key;
		if ( isset( $_POST[ $name ] ) ) update_post_meta( $post_id, '_apexik_' . $key, 'canonical' === $key ? esc_url_raw( wp_unslash( $_POST[ $name ] ) ) : sanitize_textarea_field( wp_unslash( $_POST[ $name ] ) ) );
	}
}
add_action( 'save_post', 'apexik_save_meta' );

function apexik_landing_shortcode( $atts ) {
	$key = sanitize_key( $atts['key'] ?? '' );
	$pages = apexik_landing_definitions();
	if ( ! isset( $pages[ $key ] ) ) return '';
	$d = $pages[ $key ];
	ob_start();
	?>
	<div class="apexik-seo-landing">
		<p class="gl-eyebrow"><?php echo esc_html( $d['eyebrow'] ); ?></p>
		<p class="apexik-answer"><?php echo esc_html( $d['answer'] ); ?></p>
		<?php foreach ( $d['sections'] as $section ) : ?><section><h2><?php echo esc_html( $section[0] ); ?></h2><?php echo wp_kses_post( wpautop( $section[1] ) ); ?></section><?php endforeach; ?>
		<?php if ( ! empty( $d['products'] ) ) : ?><section class="apexik-landing-products"><h2><?php echo esc_html( $d['product_heading'] ); ?></h2><?php echo do_shortcode( '[products ids="' . esc_attr( implode( ',', $d['products'] ) ) . '" columns="' . min( 4, count( $d['products'] ) ) . '" orderby="post__in"]' ); ?></section><?php endif; ?>
		<?php if ( ! empty( $d['faq'] ) ) : ?><section class="apexik-landing-faq"><h2>Frequently asked questions</h2><?php foreach ( $d['faq'] as $item ) : ?><details><summary><?php echo esc_html( $item[0] ); ?></summary><p><?php echo esc_html( $item[1] ); ?></p></details><?php endforeach; ?></section><?php endif; ?>
		<nav class="apexik-context-links" aria-label="Related guides"><a href="<?php echo esc_url( home_url( '/collections/team-radio/' ) ); ?>">Team radio gifts</a><a href="<?php echo esc_url( home_url( '/guides/motorsport-gifts-under-50/' ) ); ?>">Gifts under $50</a><a href="<?php echo esc_url( home_url( '/pages/licensing-and-authenticity/' ) ); ?>">Licensing & authenticity</a></nav>
	</div>
	<?php return ob_get_clean();
}
add_shortcode( 'apexik_landing', 'apexik_landing_shortcode' );

function apexik_landing_definitions() {
	return array(
		'team-radio' => array( 'eyebrow' => 'TEAM RADIO COLLECTION', 'answer' => 'Authorized team radio phrases, rebuilt as independent apparel, drinkware, desk gear and practical gifts for motorsport fans.', 'product_heading' => 'Shop team radio gifts', 'products' => array( 30, 39, 48, 50, 52, 54 ), 'sections' => array(
			array( 'What makes a team radio gift?', 'A team radio gift turns a recognizable conversation or pit-wall instruction into an object a fan can wear or use. APEXIK pairs authorized wording with original waveforms, channel codes and signal graphics - never copied team logos, sponsor artwork or driver names.' ),
			array( 'Choose by use, not just by quote', 'Pick apparel for race weekends, insulated drinkware for early starts and travel, desk mats for sim-racing or office setups, and small accessories for an easy gift under $30. Each product page lists materials, dimensions, care and dispatch information.' ),
		), 'faq' => array( array( 'Is APEXIK official team merchandise?', 'No. APEXIK is an independent radio-culture brand and does not claim team, driver, sponsor or championship affiliation.' ), array( 'Are the radio phrases authorized?', 'The phrases offered for sale are used within the rights made available to the store. Public pages omit unlicensed team and driver identity.' ) ) ),
		'desk-mats' => array( 'eyebrow' => 'DESK GEAR / RACING DESK MATS', 'answer' => 'Extended racing desk mats combine a smooth mouse surface, stitched edge and non-slip base with APEXIK-original team radio graphics.', 'product_heading' => 'Racing desk mat', 'products' => array( 54 ), 'sections' => array(
			array( 'Built for keyboard and mouse', 'The planned 90 × 40 cm surface fits a full-size keyboard and leaves practical tracking space for a mouse. A stitched perimeter helps resist fraying, while the rubber base keeps the mat stable during work, gaming and race weekends.' ),
			array( 'Original pit-wall visual system', 'The design uses an authorized radio phrase with original waveform, strategy-grid and signal details. It does not reproduce broadcast timing screens, team artwork, sponsor marks or an official livery.' ),
		), 'faq' => array( array( 'What desk mat size fits a full keyboard?', 'A width around 90 cm is a practical extended format for a full keyboard and mouse area. Measure the usable desk width before ordering.' ), array( 'How do I clean a stitched-edge desk mat?', 'Spot clean with mild soap and cool water, then air dry flat. Avoid high heat and aggressive machine cycles.' ) ) ),
		'drinkware' => array( 'eyebrow' => 'TEAM RADIO DRINKWARE', 'answer' => 'Motorsport tumblers and mugs featuring authorized team radio wording, accurate capacity details and original APEXIK signal artwork.', 'product_heading' => 'Team radio mugs and tumblers', 'products' => array( 48, 50 ), 'sections' => array(
			array( 'For early starts and long race days', 'Choose a ceramic mug for desks and watch parties or an insulated stainless-steel tumbler for commutes, flights and grandstands. Capacity, lid type and care instructions are stated on each product page.' ),
			array( 'Designed as a gift, specified as a product', 'Every listing separates the story from practical specifications so shoppers can confirm material, size, care and planned dispatch before purchasing.' ),
		), 'faq' => array( array( 'Are the mugs dishwasher safe?', 'Check the care section on the individual product page because print method and final production specification can differ.' ) ) ),
		'apparel' => array( 'eyebrow' => 'TEAM RADIO APPAREL', 'answer' => 'Independent motorsport polos and hoodies built around authorized radio phrases and original speed, waveform and channel graphics.', 'product_heading' => 'Shop radio apparel', 'products' => array( 30, 39 ), 'sections' => array(
			array( 'References without replica branding', 'APEXIK apparel is designed for fans who recognize the moment without needing a team crest. Public designs exclude unlicensed driver names, team logos, sponsor graphics and official championship branding.' ),
			array( 'Check fit before ordering', 'Use the size selector and product-specific measurements rather than relying on another brand’s label. Material, care and dispatch information appear in the specification section of each product page.' ),
		), 'faq' => array( array( 'Is this official team apparel?', 'No. These are independent radio-culture designs and are not presented as team uniforms or official merchandise.' ) ) ),
		'gifts-under-50' => array( 'eyebrow' => 'MOTORSPORT GIFT GUIDE', 'answer' => 'Seven practical motorsport gifts under $50, from small radio accessories to apparel, desk gear, drinkware and a lightweight viewing kit.', 'product_heading' => 'Motorsport gifts under $50', 'products' => array( 30, 39, 48, 50, 52, 54, 56 ), 'sections' => array(
			array( 'Under $25: compact fan gifts', 'Choose a ceramic radio mug or small accessories when you need a recognizable reference at a lower price and an easy-to-wrap format.' ),
			array( '$25-$40: desk, travel and everyday use', 'Insulated drinkware, accessory kits and extended desk gear work well for fans who want something useful beyond race day.' ),
			array( '$40-$50: apparel and trackside comfort', 'Polos, hoodies and lightweight spectator kits suit birthdays, race trips and watch-party hosts. Confirm size or venue requirements before purchasing.' ),
		), 'faq' => array( array( 'What is a good gift for a motorsport fan who already owns team merchandise?', 'Choose an independent team radio reference, practical desk item or trackside accessory that complements rather than duplicates official team clothing.' ), array( 'Are all items really under $50?', 'The launch collection is priced below $50 before shipping and applicable taxes. Current product pages show the live selling price.' ) ) ),
		'desk-mat-guide' => array( 'eyebrow' => 'PRACTICAL SIZE GUIDE', 'answer' => 'Measure usable desk width and depth first: extended mats around 90 × 40 cm suit most full-size keyboard-and-mouse setups, while smaller desks need more clearance.', 'product_heading' => 'Recommended extended desk mat', 'products' => array( 54 ), 'sections' => array(
			array( 'How to measure', 'Measure the clear horizontal space after allowing for monitor stands, speakers and cable routes. Then measure usable depth from the desk edge to the monitor base. Leave enough uncovered surface for drinks and charging devices.' ),
			array( 'Choose the surface and edge', 'A smooth polyester top supports everyday mouse tracking. A stitched edge can improve durability; a natural-rubber base adds grip. Product thickness affects softness and how easily the mat rolls for storage.' ),
			array( 'Care and storage', 'Spot clean small marks promptly. For deeper cleaning, use cool water and mild soap only when the product instructions allow it. Air dry completely and roll print-side out rather than folding.' ),
		), 'faq' => array( array( 'Will a 90 cm mat fit a full-size keyboard?', 'Usually yes, with room for a mouse, but the final fit depends on keyboard width and your preferred mouse movement area.' ) ) ),
		'licensing' => array( 'eyebrow' => 'LICENSING & AUTHENTICITY', 'answer' => 'APEXIK is an independent team radio culture brand. Authorized phrases are paired with original design work; unlicensed team and driver identity is excluded from public products.', 'product_heading' => '', 'products' => array(), 'sections' => array(
			array( 'What is authorized', 'The store uses team radio wording within the rights made available for products and public content. Each reusable quote is tracked internally with status, channel, territory and review notes before publication.' ),
			array( 'What APEXIK does not copy', 'Public products do not use unlicensed team logos, driver names, sponsor graphics, official championship marks, copied liveries or real driver portraits. Driver numbers are used only where the available rights and intended channel permit them.' ),
			array( 'How original design is created', 'APEXIK builds its own waveforms, channel codes, signal blocks, speed lines, typography and product layouts. Recognition comes from the authorized phrase and the fan’s memory of the moment, not from imitating official merchandise.' ),
		), 'faq' => array( array( 'Is APEXIK affiliated with a racing team or championship?', 'No. APEXIK is independent and does not claim an official team, driver, sponsor, series or championship relationship.' ) ) ),
		'materials' => array( 'eyebrow' => 'MATERIALS & PRODUCTION', 'answer' => 'Every APEXIK listing separates verified product specifications from design storytelling so buyers can check material, dimensions, care and dispatch before ordering.', 'product_heading' => '', 'products' => array(), 'sections' => array(
			array( 'Specification before promotion', 'Material, dimensions, capacity, compatibility, care and planned dispatch are maintained at product level. Claims such as battery runtime, insulation performance and device compatibility are published only after supplier or sample verification.' ),
			array( 'Print and finish', 'Artwork is prepared as APEXIK-original typography, waveforms and signal systems. Final print method and finish can vary by product and supplier, so the individual listing remains the controlling specification.' ),
			array( 'Responsible launch process', 'Product pages may identify specifications that still require final sample approval. These must be confirmed before paid launch, stock commitment or broad advertising.' ),
		), 'faq' => array( array( 'Where can I find exact dimensions?', 'Open the individual product page and use the specifications section; apparel sizing is also linked from the size guide.' ) ) ),
	);
}

function apexik_install_seo_architecture() {
	$defs = array(
		'collections' => array( 0, 'Collections', '', '', '', 'noindex,follow' ),
		'team-radio' => array( 'collections', 'Team Radio Gifts & Quote Collection', '[apexik_landing key="team-radio"]', 'team radio gifts', 'Team Radio Gifts & Authorized Racing Quote Collection | APEXIK', 'index,follow', 'Authorized team radio phrases on independent apparel, drinkware, desk gear and motorsport gifts. Shop the APEXIK radio collection under $50.' ),
		'products' => array( 0, 'Shop by Product', '', '', '', 'noindex,follow' ),
		'desk-mats' => array( 'products', 'Racing Desk Mats & Motorsport Mouse Pads', '[apexik_landing key="desk-mats"]', 'racing desk mat', 'Racing Desk Mats & Motorsport Mouse Pads | APEXIK', 'index,follow', 'Shop extended racing desk mats with stitched edges, non-slip bases and original team radio graphics for motorsport desk and sim-racing setups.' ),
		'drinkware' => array( 'products', 'Team Radio Mugs & Motorsport Tumblers', '[apexik_landing key="drinkware"]', 'racing insulated tumbler', 'Team Radio Mugs & Motorsport Tumblers | APEXIK', 'index,follow', 'Shop team radio mugs and insulated motorsport tumblers with original waveform artwork, practical specifications and gift-ready prices under $50.' ),
		'apparel' => array( 'products', 'Team Radio Apparel', '[apexik_landing key="apparel"]', 'team radio apparel', 'Team Radio Apparel: Polos & Hoodies | APEXIK', 'index,follow', 'Independent team radio polos and motorsport hoodies featuring authorized phrases and original speed, channel and waveform graphics.' ),
		'guides' => array( 0, 'Motorsport Gift & Product Guides', '', '', '', 'noindex,follow' ),
		'motorsport-gifts-under-50' => array( 'guides', 'Motorsport Gifts Under $50', '[apexik_landing key="gifts-under-50"]', 'motorsport gifts under $50', 'Motorsport Gifts Under $50 for Racing Fans | APEXIK', 'index,follow', 'Compare practical motorsport gifts under $50, including team radio apparel, drinkware, desk gear, accessories and lightweight trackside essentials.' ),
		'racing-desk-mat-size-guide' => array( 'guides', 'How to Choose a Racing Desk Mat Size', '[apexik_landing key="desk-mat-guide"]', 'racing desk mat size', 'How to Choose a Racing Desk Mat Size | APEXIK Guide', 'index,follow', 'Measure and choose an extended racing desk mat for a full-size keyboard, mouse and sim-racing setup. Includes surface, edge, care and storage guidance.' ),
		'pages' => array( 0, 'APEXIK Standards', '', '', '', 'noindex,follow' ),
		'licensing-and-authenticity' => array( 'pages', 'Licensing & Authenticity', '[apexik_landing key="licensing"]', 'authorized team radio gifts', 'Licensing & Authenticity | APEXIK Radio Goods', 'index,follow', 'How APEXIK uses authorized team radio phrases with original design work while excluding unlicensed team, driver, sponsor and championship identity.' ),
		'materials-and-production' => array( 'pages', 'Materials & Production', '[apexik_landing key="materials"]', 'motorsport gift materials', 'Materials & Production | APEXIK Radio Goods', 'index,follow', 'Read how APEXIK verifies product materials, dimensions, care, compatibility, print finish and dispatch details before launch.' ),
	);
	$ids = array();
	foreach ( $defs as $slug => $d ) {
		$parent = is_string( $d[0] ) ? ( $ids[ $d[0] ] ?? 0 ) : 0;
		$existing = get_page_by_path( ( $parent ? get_post_field( 'post_name', $parent ) . '/' : '' ) . $slug );
		$post = array( 'post_type' => 'page', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $d[1], 'post_content' => $d[2], 'post_parent' => $parent );
		$id = $existing ? wp_update_post( array_merge( $post, array( 'ID' => $existing->ID ) ) ) : wp_insert_post( $post );
		$ids[ $slug ] = (int) $id;
		if ( ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_apexik_primary_keyword', $d[3] ); update_post_meta( $id, '_apexik_seo_title', $d[4] ); update_post_meta( $id, '_apexik_robots', $d[5] );
			if ( isset( $d[6] ) ) update_post_meta( $id, '_apexik_meta_description', $d[6] );
			update_post_meta( $id, '_apexik_page_type', str_contains( $slug, 'guide' ) || 'motorsport-gifts-under-50' === $slug ? 'guide' : ( in_array( $slug, array( 'licensing-and-authenticity', 'materials-and-production' ), true ) ? 'trust' : 'collection' ) );
		}
	}
	$product_seo = array(
		30 => array( 'team radio polo shirt', 'Must Be the Water Team Radio Polo | APEXIK', 'A racing-red radio polo featuring an authorized Must Be the Water phrase, original waveform graphics, XS-3XL sizing and product details under $50.' ),
		39 => array( 'team radio hoodie', 'GP2 Engine Team Radio Hoodie | APEXIK', 'Heavyweight team radio hoodie with authorized GP2 Engine wording and original pit-wall waveform graphics. Independent motorsport apparel under $50.' ),
		48 => array( 'team radio mug', 'Must Be the Water Team Radio Mug | APEXIK', 'A 12 oz ceramic team radio mug with authorized Must Be the Water wording and original waveform artwork - a practical motorsport gift under $25.' ),
		50 => array( 'racing insulated tumbler', 'Simply Lovely Racing Insulated Tumbler | APEXIK', 'A 20 oz insulated racing tumbler with authorized Simply Lovely wording, coded signal graphics and practical travel details for motorsport fans.' ),
		52 => array( 'motorsport accessory gift kit', 'Team Radio Accessory Gift Kit | APEXIK', 'Four coordinated motorsport accessories: key tag, watch strap, weatherproof stickers and strategy notes in one team radio gift kit under $30.' ),
		54 => array( 'racing desk mat', 'Change Your FXXking Car Racing Desk Mat | APEXIK', 'Extended 90 × 40 cm racing desk mat with stitched edge, non-slip base and authorized radio wording across original pit-wall graphics.' ),
		56 => array( 'race day viewing kit', 'Lightweight Race Day Viewing Kit | APEXIK', 'Portable race-day comfort kit with waterproof-backed grass mat, rechargeable neck fan and storage pouch - practical trackside gear under $50.' ),
	);
	foreach ( $product_seo as $id => $seo ) if ( 'product' === get_post_type( $id ) ) { update_post_meta( $id, '_apexik_primary_keyword', $seo[0] ); update_post_meta( $id, '_apexik_seo_title', $seo[1] ); update_post_meta( $id, '_apexik_meta_description', $seo[2] ); update_post_meta( $id, '_apexik_search_intent', 'transactional' ); update_post_meta( $id, '_apexik_rights_status', 'approved' ); }
	update_option( 'blogdescription', 'Authorized team radio apparel, motorsport gifts, desk gear, drinkware and trackside essentials under $50.' );
	set_theme_mod( 'gridline_announcement', 'INDEPENDENT TEAM RADIO GOODS · EVERY LAUNCH ITEM UNDER $50' );
	$menu = wp_get_nav_menu_object( 'APEXIK Primary' );
	if ( $menu ) {
		foreach ( (array) wp_get_nav_menu_items( $menu->term_id ) as $item ) wp_delete_post( $item->ID, true );
		$nav = array( 'Shop' => home_url( '/shop/' ), 'Team Radio' => get_permalink( $ids['team-radio'] ), 'Desk Mats' => get_permalink( $ids['desk-mats'] ), 'Gifts Under $50' => get_permalink( $ids['motorsport-gifts-under-50'] ), 'Our Story' => home_url( '/about/' ) );
		foreach ( $nav as $label => $url ) wp_update_nav_menu_item( $menu->term_id, 0, array( 'menu-item-title' => $label, 'menu-item-url' => $url, 'menu-item-type' => 'custom', 'menu-item-status' => 'publish' ) );
	}
	flush_rewrite_rules(); update_option( 'apexik_seo_geo_version', '1.0.0' ); return $ids;
}

function apexik_page_schema() {
	if ( ! is_singular() ) return;
	$id = get_queried_object_id(); $type = get_post_meta( $id, '_apexik_page_type', true );
	if ( ! $type ) return;
	$items = array(); $key = '';
	if ( preg_match( '/key="([^"]+)"/', get_post_field( 'post_content', $id ), $m ) ) $key = $m[1];
	$defs = apexik_landing_definitions();
	if ( $key && isset( $defs[ $key ] ) ) foreach ( $defs[ $key ]['products'] as $position => $pid ) $items[] = array( '@type' => 'ListItem', 'position' => $position + 1, 'url' => get_permalink( $pid ), 'name' => get_the_title( $pid ) );
	$graph = array( array( '@type' => 'BreadcrumbList', 'itemListElement' => array( array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ), array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title( $id ), 'item' => get_permalink( $id ) ) ) ) );
	if ( $items ) $graph[] = array( '@type' => 'ItemList', 'name' => get_the_title( $id ), 'itemListElement' => $items );
	if ( $key && ! empty( $defs[ $key ]['faq'] ) ) { $faq = array(); foreach ( $defs[ $key ]['faq'] as $q ) $faq[] = array( '@type' => 'Question', 'name' => $q[0], 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $q[1] ) ); $graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $faq ); }
	echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'apexik_page_schema', 35 );

function apexik_sitemap_exclude_noindex( $args, $post_type ) {
	if ( in_array( $post_type, array( 'page', 'product', 'apexik_circuit', 'apexik_moment' ), true ) ) {
		$args['meta_query'] = array(
			'relation' => 'OR',
			array( 'key' => '_apexik_robots', 'compare' => 'NOT EXISTS' ),
			array( 'key' => '_apexik_robots', 'value' => 'noindex', 'compare' => 'NOT LIKE' ),
		);
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'apexik_sitemap_exclude_noindex', 10, 2 );

function apexik_redirect_legacy_radio_collection() {
	if ( '/collections/race-radio/' === trailingslashit( wp_parse_url( $_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH ) ) ) {
		wp_safe_redirect( home_url( '/collections/team-radio/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'apexik_redirect_legacy_radio_collection', 1 );

function apexik_migrate_team_radio_keyword() {
	$collection = get_page_by_path( 'collections/race-radio' );
	if ( $collection ) wp_update_post( array( 'ID' => $collection->ID, 'post_name' => 'team-radio', 'post_title' => 'Team Radio Gifts & Quote Collection' ) );
	$ids = apexik_install_seo_architecture();
	$target_posts = array( 11, 16, 30, 39, 48, 50, 52, 54, 56 );
	$replacements = array(
		'racing-radio' => 'team radio', 'Racing-radio' => 'Team radio', 'racing radio' => 'team radio', 'Racing radio' => 'Team radio',
		'race-radio' => 'team radio', 'Race-radio' => 'Team radio', 'race radio' => 'team radio', 'Race radio' => 'Team radio',
	);
	foreach ( $target_posts as $post_id ) {
		$post = get_post( $post_id );
		if ( ! $post ) continue;
		wp_update_post( array( 'ID' => $post_id, 'post_content' => strtr( $post->post_content, $replacements ), 'post_excerpt' => strtr( $post->post_excerpt, $replacements ) ) );
		foreach ( array( 'subtitle', 'what', 'difference', 'faq', 'seo_title', 'meta_description', 'keywords' ) as $key ) {
			$value = get_post_meta( $post_id, '_gridline_' . $key, true );
			if ( $value ) update_post_meta( $post_id, '_gridline_' . $key, strtr( $value, $replacements ) );
		}
	}
	foreach ( array( 'radio-apparel', 'small-gifts', 'desk-tech', 'drinkware' ) as $slug ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) wp_update_term( $term->term_id, 'product_cat', array( 'description' => strtr( $term->description, $replacements ) ) );
	}
	flush_rewrite_rules();
	return $ids;
}
