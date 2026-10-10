"use client";

import { useEffect, useMemo, useRef, useState, type ButtonHTMLAttributes, type FormEvent, type MouseEvent as ReactMouseEvent } from "react";
import CategoryJourney from "./CategoryJourney";
import { initMetaPixel, trackMetaEvent } from "./meta-pixel";
import "./site-menu.css";
import "./content-sections.css";

type Product = {
  id: string; category: string; categoryLabel: string; title: string; price: number;
  image: string; altImage: string; story: string; hook: string; details: string[]; colors: string[]; sizes: string[];
  gallery?: Array<{ src: string; alt: string }>;
  variants?: Array<{ id?: string; style: string; size: string; skuId?: string }>;
};
type CartItem = Product & { selectedStyle: string; selectedSize: string; skuId?: string };
type RaceMoment = { id: string; title: string; summary: string; body: string; image?: string; status?: string };

function categorySlug(name: string) {
  const slug = name.toLowerCase().trim().replace(/\s+/g, "-");
  return slug === "clothing" ? "t-shirts" : slug === "key-chains" ? "keychains" : slug;
}

const products: Product[] = [
  { id: "undercut", category: "desk-mats", categoryLabel: "Desk Mats", title: "The Undercut Window Desk Mat", price: 39.99, image: "/products/undercut-desk-mat.png", altImage: "/products/reference/desk-mat.png", hook: "Abu Dhabi 2021 · Lap 58", story: "A safety-car restart turned a measured gap into one of the most debated final laps in modern racing. The print maps the telemetry spike, the redline decision and the gold light that changed everything.", details: ["900 × 400 mm", "Stitched anti-fray edge", "Non-slip rubber base"], colors: ["Signal red", "Ink black"], sizes: ["900 × 400 mm"] },
  { id: "rain-line", category: "t-shirts", categoryLabel: "Clothing", title: "Simply Lovely / Race Radio Top", price: 39.99, image: "/products/clothing/navy-polo-front.png", altImage: "/products/clothing/navy-polo-back.png", hook: "São Paulo 2024 · P17 → P1", story: "A storm, a long climb through the field and a radio line fans still replay. This two-design collection turns that unforgettable São Paulo comeback into race-day apparel: the navy Simply Lovely polo and the red Must Be the Water tee.", details: ["Two original race-story designs", "Relaxed unisex fit", "Front, back and fabric detail views"], colors: ["Navy / Simply Lovely", "Red / Must Be the Water"], sizes: ["S", "M", "L", "XL", "2XL"], gallery: [{ src: "/products/clothing/navy-polo-front.png", alt: "Navy Simply Lovely polo, front view" }, { src: "/products/clothing/navy-polo-back.png", alt: "Navy polo back print" }, { src: "/products/clothing/navy-polo-details.png", alt: "Polo chest, collar and front graphic details" }, { src: "/products/clothing/navy-polo-fabric.png", alt: "Navy polo fabric and stitching details" }, { src: "/products/clothing/navy-polo-terrace.png", alt: "Simply Lovely polo at a motorsport terrace" }, { src: "/products/clothing/navy-polo-race-day.png", alt: "Race-day polo lifestyle view" }, { src: "/products/clothing/navy-polo-size-guide.png", alt: "Polo fit and size guide" }, { src: "/products/clothing/red-must-be-the-water.png", alt: "Red Must Be the Water race-story shirt" }] },
  { id: "safety-car", category: "stickers", categoryLabel: "Stickers", title: "Wet Race / Safety Car Sticker Set", price: 39.99, image: "/products/safety-car-stickers.png", altImage: "/products/reference/stickers.png", hook: "Germany 2019 · Hockenheim rain", story: "A race that kept changing its mind: rain radar, pit-lane arrows and a safety-car lamp become five die-cut pieces for laptops, bottles and notebooks.", details: ["Five die-cut vinyl stickers", "Weatherproof matte finish", "Packed in a rigid paper sleeve"], colors: ["Rain radar", "Pit red"], sizes: ["5-piece set"] },
  { id: "radio-wave", category: "keychains", categoryLabel: "Key Chains", title: "Radio Wave Steering Key Chain", price: 39.99, image: "/products/radio-wave-keychain.png", altImage: "/products/reference/keychain.png", hook: "Baku 2017 · Radio under pressure", story: "When a radio message became the story, the object became a waveform: steering-wheel geometry, signal lamps and a line that refuses to stay quiet.", details: ["Brushed stainless steel", "Enamel signal details", "38 mm hard-wearing tag"], colors: ["Steel", "Signal red"], sizes: ["38 mm"] },
  { id: "rain-check", category: "curated-finds", categoryLabel: "Curated Finds", title: "Rain Check Pit Wall Mug", price: 39.99, image: "/products/rain-check-mug.png", altImage: "/products/reference/mug.png", hook: "Monaco 1996 · The wet-weather gamble", story: "A soaked street circuit turns the pit wall into a weather station. The red radar sweep and gold signal board wrap around the mug for late-night race replays.", details: ["350 ml glazed ceramic", "Dishwasher safe", "Gloss black interior"], colors: ["Radar red", "Ink black"], sizes: ["350 ml"] },
  { id: "watch-band", category: "curated-finds", categoryLabel: "Curated Finds", title: "Simply Lovely Race-Day Watch Band", price: 39.99, image: "/products/watch-band/main.png", altImage: "/products/watch-band/design-details.png", hook: "São Paulo 2024 · The comeback on your wrist", story: "A watch strap that carries a race-day comeback in red, white and racing navy. The checkered details and helmet artwork turn a radio-era favorite into an everyday keepsake.", details: ["Flexible printed silicone band", "Two Apple Watch size ranges", "Wipe-clean finish"], colors: ["Navy / Redline", "Checkered / Blue"], sizes: ["38 / 40 / 41 / 42 mm", "44 / 45 / 46 / 49 mm"], gallery: [{ src: "/products/watch-band/main.png", alt: "Motorsport smartwatch band product pair" }, { src: "/products/watch-band/design-details.png", alt: "Watch band design, fit and feature details" }] },
];

const seedProducts: Product[] = products.map((product) => ({ ...product, variants: product.colors.flatMap((style) => product.sizes.map((size) => ({ style, size }))) }));
const fallbackMoments: RaceMoment[] = [
  { id: "moment-undercut", title: "The Undercut Window", summary: "One lap. One gap. A decision that changed the order.", body: "A safety-car restart turned a measured gap into one of the most debated final laps in modern racing.", image: "/banners/abu-dhabi-2021.png" },
  { id: "moment-rain-line", title: "Rain Line / Last Corner", summary: "Dry certainty on one side, wet chaos on the other.", body: "A championship seemed settled until the last corner, the rain and a position that changed hands twice.", image: "/banners/must-be-the-water.png" },
  { id: "moment-radio-wave", title: "Radio Wave Under Pressure", summary: "When a team-radio line became the whole story.", body: "A radio message, a long silence and the pressure inside a cockpit become a visual archive entry.", image: "/banners/radio-polo.png" },
];


export default function Home() {
  const [view, setView] = useState<"home" | "shop" | "product" | "checkout" | "success">("home");
  const [activeCategory, setActiveCategory] = useState("all"); const [selected, setSelected] = useState<Product | null>(null);
  const [cart, setCart] = useState<CartItem[]>([]); const [image, setImage] = useState("");
  const [zoomOrigin, setZoomOrigin] = useState("50% 50%"); const [notice, setNotice] = useState(""); const [orderNumber, setOrderNumber] = useState("");
  const [introDone, setIntroDone] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [bannerIndex, setBannerIndex] = useState(0);
  const [activeNav, setActiveNav] = useState("design");
  const [navIndicator, setNavIndicator] = useState({ left: 0, width: 0, visible: false });
  const [catalogProducts, setCatalogProducts] = useState<Product[]>(seedProducts);
  const [moments, setMoments] = useState<RaceMoment[]>(fallbackMoments);
  const [siteContent, setSiteContent] = useState<Record<string, string>>({});
  const lastTrackedPage = useRef("");
  const lastTrackedOrder = useRef("");
  const navRef = useRef<HTMLElement>(null);
  const products = catalogProducts;
  useEffect(() => { initMetaPixel(); }, []);
  useEffect(() => {
    const loadContent = () => { try { const saved = localStorage.getItem("apexik-site-content"); if (saved) setSiteContent(JSON.parse(saved).values ?? {}); } catch {} };
    loadContent(); window.addEventListener("apexik-content-updated", loadContent); window.addEventListener("storage", loadContent);
    return () => { window.removeEventListener("apexik-content-updated", loadContent); window.removeEventListener("storage", loadContent); };
  }, []);
  useEffect(() => {
    const pageKey = `${view}:${view === "product" ? selected?.id ?? "" : view === "shop" ? activeCategory : ""}`;
    if (lastTrackedPage.current === pageKey) return;
    trackMetaEvent("PageView");
    lastTrackedPage.current = pageKey;
    if (view === "product" && selected) {
      trackMetaEvent("ViewContent", { content_ids: [selected.id], content_name: selected.title, content_type: "product", value: selected.price, currency: "USD" });
    }
    if (view === "shop") {
      trackMetaEvent("ViewCategory", { content_category: activeCategory });
    }
    if (view === "checkout") {
      trackMetaEvent("InitiateCheckout", { content_ids: cart.map((item) => item.id), num_items: cart.length, value: cart.reduce((sum, item) => sum + item.price, 0), currency: "USD" });
    }
  }, [view, activeCategory, selected, cart]);
  useEffect(() => {
    if (view === "success" && orderNumber && lastTrackedOrder.current !== orderNumber) {
      trackMetaEvent("Purchase", { content_ids: cart.map((item) => item.id), content_type: "product", num_items: cart.length, value: cart.reduce((sum, item) => sum + item.price, 0), currency: "USD", order_id: orderNumber });
      lastTrackedOrder.current = orderNumber;
    }
  }, [view, orderNumber, cart]);
  useEffect(() => {
    fetch("/api/catalog").then((response) => response.ok ? response.json() : Promise.reject(new Error("catalog unavailable"))).then((payload: { categories: Array<{ id: string; name: string }>; raceMoments?: RaceMoment[]; products: Array<{ id: string; categoryId: string; title: string; price: number; story: string; images: Array<{ src: string }>; variants: Array<{ id: string; style: string; size: string; sku?: { id: string } | null }> }> }) => {
      const categoryNames = new Map(payload.categories.map((category) => [category.id, category.name]));
      if (!payload.products.length) return;
      if (payload.raceMoments?.length) setMoments(payload.raceMoments);
      setCatalogProducts(payload.products.map((product) => {
        const variants = product.variants.length ? product.variants : [{ style: "Default", size: "Default" }];
        const images = product.images.map((item) => item.src);
        return { id: product.id, category: categorySlug(categoryNames.get(product.categoryId) ?? "Curated Finds"), categoryLabel: categoryNames.get(product.categoryId) ?? "Curated Finds", title: product.title, price: product.price, image: images[0] ?? "/products/undercut-desk-mat.png", altImage: images[1] ?? images[0] ?? "/products/undercut-desk-mat.png", gallery: images.map((src) => ({ src, alt: product.title })), hook: "APEXIK catalog · race story", story: product.story, details: ["APEXIK catalog item", "Demo inventory connected", "Made for replay nights"], colors: [...new Set(variants.map((variant) => variant.style))], sizes: [...new Set(variants.map((variant) => variant.size))], variants: variants.map((variant) => ({ id: variant.id, style: variant.style, size: variant.size, skuId: variant.sku?.id })) };
      }));
    }).catch(() => undefined);
  }, []);
  useEffect(() => { const timer = window.setTimeout(() => setIntroDone(true), 2200); return () => window.clearTimeout(timer); }, []);
  useEffect(() => {
    if (!menuOpen) return;
    const closeOnEscape = (event: KeyboardEvent) => { if (event.key === "Escape") setMenuOpen(false); };
    window.addEventListener("keydown", closeOnEscape);
    return () => window.removeEventListener("keydown", closeOnEscape);
  }, [menuOpen]);
  useEffect(() => {
    function restoreIndicator() {
      const target = navRef.current?.querySelector<HTMLButtonElement>(`button[data-nav="${activeNav}"]`);
      if (target) setNavIndicator({ left: target.offsetLeft, width: target.offsetWidth, visible: true });
    }
    const frame = window.requestAnimationFrame(restoreIndicator);
    window.addEventListener("resize", restoreIndicator);
    return () => { window.cancelAnimationFrame(frame); window.removeEventListener("resize", restoreIndicator); };
  }, [activeNav, view]);
  const visibleProducts = useMemo(() => activeCategory === "all" ? products : products.filter((p) => p.category === activeCategory), [activeCategory, products]);
  function openProduct(product: Product) { setSelected(product); setImage(product.image); setView("product"); window.scrollTo({ top: 0, behavior: "smooth" }); }
  function openCategory(category: string) { setActiveCategory(category); setView("shop"); window.scrollTo({ top: 0, behavior: "smooth" }); }
  function goToSection(selector: string, navId: string) {
    setMenuOpen(false);
    setActiveNav(navId);
    if (view !== "home") setView("home");
    window.setTimeout(() => document.querySelector(selector)?.scrollIntoView({ behavior: "smooth", block: "start" }), 40);
  }
  function addToCart(product: Product, selectedStyle: string, selectedSize: string) { const variant = product.variants?.find((item) => item.style === selectedStyle && item.size === selectedSize); setCart((items) => [...items, { ...product, selectedStyle, selectedSize, skuId: variant?.skuId }]); trackMetaEvent("AddToCart", { content_ids: [product.id], content_name: product.title, content_type: "product", value: product.price, currency: "USD" }); setNotice(`${product.title} added to your grid`); window.setTimeout(() => setNotice(""), 2800); }
  const subtotal = cart.reduce((sum, product) => sum + product.price, 0);
  const copy = (key: string, fallback: string) => siteContent[key] || fallback;
  return <main className="apx-site">
    {!introDone && <div className="opening-sequence" role="status" aria-label="Welcome to Apexik"><div className="starting-lights" aria-hidden="true">{[1, 2, 3, 4, 5].map((light) => <i key={light} />)}</div><strong>Welcome to Apexik</strong></div>}
    {notice && <div className="toast">{notice}</div>}
    <div className="announcement"><div><strong>OPENING GRID</strong> Free tracked delivery over $75 — demo offer <span>Worldwide delivery</span></div></div>
    {menuOpen && <button className="menu-backdrop" aria-label="Close navigation menu" onClick={() => setMenuOpen(false)} />}
    <header className={`header ${menuOpen ? "menu-is-open" : ""}`}><button className="menu-button" aria-expanded={menuOpen} aria-controls="site-menu" onClick={() => setMenuOpen((open) => !open)}><span className="menu-toggle-mark" aria-hidden="true">{menuOpen ? <b>×</b> : <><i/><i/><i/></>}</span><span>Menu</span></button><button className="logo" aria-label="Apexik home" onClick={() => { setActiveCategory("all"); setView("home"); setActiveNav("design"); setMenuOpen(false); }}><img src="/brand/apexik-mark.png" alt=""/></button><nav ref={navRef} onMouseLeave={() => { const target = navRef.current?.querySelector<HTMLButtonElement>(`button[data-nav="${activeNav}"]`); if (target) setNavIndicator({ left: target.offsetLeft, width: target.offsetWidth, visible: true }); }}><i className="nav-glider" aria-hidden="true" style={{ left: navIndicator.left, width: navIndicator.width, opacity: navIndicator.visible ? 1 : 0 }}/><button data-nav="shop" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => { setActiveNav("shop"); setMenuOpen(false); setView("shop"); setActiveCategory("all"); }}>Shop⌄</button><button data-nav="design" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => goToSection(".releases", "design")}>Today’s Design</button><button data-nav="race" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => goToSection(".race-section", "race")}>Race Moment</button><button data-nav="about" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => goToSection(".about-section", "about")}>About</button><button data-nav="faq" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => goToSection(".faq-section", "faq")}>FAQ</button><button data-nav="contact" onMouseEnter={(event) => setNavIndicator({ left: event.currentTarget.offsetLeft, width: event.currentTarget.offsetWidth, visible: true })} onClick={() => goToSection(".contact-panel", "contact")}>Contact</button></nav><div className="header-actions"><button className="expand-action" aria-label="Search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m15.5 15.5 5 5"/></svg><em>Search</em></button><button className="expand-action" aria-label="Account"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="7" r="3.5"/><path d="M5 21c.7-5 3-7.5 7-7.5s6.3 2.5 7 7.5"/></svg><em>Account</em></button><button className="expand-action cart-action" aria-label="Cart" onClick={() => cart.length && setView("checkout")}><svg className="cart-icon" viewBox="0 0 28 28" aria-hidden="true"><path d="M3 4h3l2.6 13.1a2.2 2.2 0 0 0 2.2 1.8h10a2.2 2.2 0 0 0 2.1-1.6L25 9H7"/><circle cx="10" cy="24" r="1.4"/><circle cx="21" cy="24" r="1.4"/></svg><em>Cart</em><span className="cart-count">{cart.length}</span></button></div>
      {menuOpen && <div className="menu-panel" id="site-menu">
        <div className="menu-panel-heading"><span>EXPLORE APEXIK</span><button onClick={() => setMenuOpen(false)} aria-label="Close menu">CLOSE ×</button></div>
        <div className="menu-panel-grid"><div className="menu-link-column"><small>SHOP BY CATEGORY</small><button onClick={() => { setMenuOpen(false); openCategory("all"); }}>Shop all products <span>↗</span></button>{[["desk-mats","Desk Mats"],["t-shirts","Clothing"],["stickers","Stickers"],["keychains","Key Chains"],["curated-finds","Curated Finds"]].map(([category,label]) => <button key={category} onClick={() => { setMenuOpen(false); openCategory(category); }}>{label}<span>↗</span></button>)}</div><div className="menu-link-column"><small>DISCOVER</small><button onClick={() => goToSection(".releases", "design")}>Today’s Design<span>↗</span></button><button onClick={() => goToSection(".race-section", "race")}>Race Moments<span>↗</span></button><button onClick={() => goToSection(".about-section", "about")}>About Apexik<span>↗</span></button><button onClick={() => goToSection(".faq-section", "faq")}>FAQ & Support<span>↗</span></button></div><div className="menu-feature"><span>RACE-DAY STORIES<br/>ENGINEERED INTO OBJECTS</span><p>Five collections. One grid. Find the moment you want to keep.</p><button onClick={() => { setMenuOpen(false); openCategory("all"); }}>ENTER THE STORE ↗</button></div></div>
      </div>}
    </header>
    <div className="ticker"><span>Free shipping above $99</span><span>Six race-inspired objects across five collections</span><span>Made for replay nights</span><span>Free shipping above $99</span></div>
    {view === "home" && <><CategoryJourney products={products} onCategory={openCategory} /><CampaignBanner active={bannerIndex} onChange={setBannerIndex} />
      <section className="section releases shell"><div className="section-head"><h2>New arrivals</h2><MagneticButton onClick={() => setView("shop")}>View all products →</MagneticButton></div><div className="product-grid">{products.slice(0, 4).map((product) => <ProductCard key={product.id} product={product} onOpen={openProduct} />)}</div></section>
      <section className="format-section section"><div className="shell"><div className="section-head"><h2>Shop All</h2><p>Race stories translated into collectible objects. Choose a format, then build your grid.</p></div><Tabs active={activeCategory} setActive={setActiveCategory} /><div className="product-grid">{visibleProducts.map((product) => <ProductCard key={product.id} product={product} onCategory={openCategory} />)}</div></div></section>
      <RaceMomentsSection moments={moments} onOpen={(moment) => setNotice(moment.body)} />
      <section className="about-section section"><div className="shell about-grid"><div><h2>About Us</h2><h3>Built from the paddock up</h3><strong>For the ones who never left.</strong></div><div><p>We turn race-day tension into useful collectible objects. Not generic logo merchandise — small physical reminders of a decision, a radio line, a weather gamble or one impossible final corner.</p><p>Every design starts with a real racing moment and ends with an object made to live on your desk, in your hand, on your keys or in the cupboard beside the late-night coffee.</p></div></div></section>
      <section className="faq-section section shell"><div className="section-head"><h2>FAQ</h2><span>Everything here is a working demo</span></div>{["Are these official licensed products?", "How are products made?", "Can I test the full checkout?"].map((q, i) => <details key={q} open={i === 2}><summary>{q}</summary><p>{i === 0 ? "This demo uses original visual concepts and avoids team, driver and championship marks." : i === 1 ? "The demo models made-to-order products with one price and selectable variables." : "Yes. Add any product to the grid, choose options, enter demo details and place a test order."}</p></details>)}</section><ContactPanel /><Newsletter /></>}
    {view === "shop" && <ShopView products={visibleProducts} active={activeCategory} setActive={setActiveCategory} onOpen={openProduct} />}
    {view === "product" && selected && <ProductViewNew product={selected} image={image} setImage={setImage} zoomOrigin={zoomOrigin} setZoomOrigin={setZoomOrigin} onAdd={(style, size) => addToCart(selected, style, size)} onBack={() => setView("shop")} onRelated={openProduct} />}
    {view === "checkout" && <CheckoutNew cart={cart} subtotal={subtotal} onBack={() => setView("shop")} onPlace={(createdOrderNumber) => { setOrderNumber(createdOrderNumber); setNotice(`Order ${createdOrderNumber} placed`); setView("success"); }} />}
    {view === "success" && <SuccessNew orderNumber={orderNumber} onHome={() => { setCart([]); setOrderNumber(""); setNotice(""); setView("home"); }} />}
    <footer className="footer"><div className="shell footer-grid"><div><button className="logo footer-logo" aria-label="Apexik home" onClick={() => { setActiveCategory("all"); setView("home"); }}><img src="/brand/apexik-mark.png" alt=""/><span>APEXIK</span></button><p>Race-day stories engineered into collectible objects.</p></div><div><b>Shop</b>{products.map((p) => <button key={p.id} onClick={() => openProduct(p)}>{p.categoryLabel}</button>)}</div><div><b>Company</b><button>About Us</button><button>Race Moments</button><button>Rights & Sources</button></div></div><div className="shell footer-bottom">© 2026 APEXIK <span>Demo storefront · no payment is captured</span></div></footer>
  </main>;
}

function Tabs({ active, setActive }: { active: string; setActive: (value: string) => void }) { return <div className="tabs">{[["all", "All"], ["desk-mats", "Desk Mats"], ["t-shirts", "Clothing"], ["stickers", "Stickers"], ["keychains", "Key Chains"], ["curated-finds", "Curated Finds"]].map(([id, label]) => <button key={id} className={active === id ? "active" : ""} onClick={() => setActive(id)}>{label}</button>)}</div>; }
const campaignBanners = [
  { src: "/banners/abu-dhabi-2021.png", label: "Abu Dhabi 2021 · The final lap" },
  { src: "/banners/radio-polo.png", label: "Race radio · Simply Lovely collection" },
  { src: "/banners/must-be-the-water.png", label: "Melbourne · Must Be the Water" },
];
function CampaignBanner({ active, onChange }: { active: number; onChange: (index: number) => void }) {
  return <section className="campaign-banner" aria-label="APEXIK campaign banners">
    <img key={campaignBanners[active].src} src={campaignBanners[active].src} alt={campaignBanners[active].label} width={2200} height={800} />
    <div className="campaign-banner-controls"><span>{campaignBanners[active].label}</span><div><button aria-label="Previous banner" onClick={() => onChange((active + campaignBanners.length - 1) % campaignBanners.length)}>←</button><span>0{active + 1} / 0{campaignBanners.length}</span><button aria-label="Next banner" onClick={() => onChange((active + 1) % campaignBanners.length)}>→</button></div></div>
  </section>;
}
function RaceMomentsSection({ moments, onOpen }: { moments: RaceMoment[]; onOpen: (moment: RaceMoment) => void }) {
  const [active, setActive] = useState(0);
  const moment = moments[active] ?? moments[0];
  const fallbackImages = ["/banners/abu-dhabi-2021.png", "/banners/must-be-the-water.png", "/banners/radio-polo.png"];
  return <section className="race-section section"><div className="shell"><div className="section-head"><h2>Race Moments</h2><span>Editorial archive / {String(moments.length).padStart(2, "0")} stories</span></div><div className="race-layout"><div className="race-art race-preview">{moment && <img key={moment.id} src={moment.image ?? fallbackImages[active % fallbackImages.length]} alt=""/>}<div className="race-preview-shade"/><span>{String(active + 1).padStart(2, "0")}</span><strong>{moment?.title ?? "THE MOMENT AFTER"}</strong></div><div className="race-list">{moments.map((item, index) => <button className={index === active ? "active" : ""} key={item.id} onMouseEnter={() => setActive(index)} onFocus={() => setActive(index)} onClick={() => { setActive(index); onOpen(item); }}><small>{String(index + 1).padStart(2, "0")} · editorial archive</small><b>{item.title}</b><span>{item.summary} →</span></button>)}</div></div></div></section>;
}

function MagneticButton({ className = "", children, onMouseMove, onMouseLeave, ...props }: ButtonHTMLAttributes<HTMLButtonElement>) {
  function move(event: ReactMouseEvent<HTMLButtonElement>) {
    if (window.matchMedia("(prefers-reduced-motion: reduce), (hover: none)").matches) return;
    const box = event.currentTarget.getBoundingClientRect();
    const x = (event.clientX - box.left - box.width / 2) * .16;
    const y = (event.clientY - box.top - box.height / 2) * .18;
    event.currentTarget.style.transform = `translate3d(${x}px, ${y}px, 0)`;
    onMouseMove?.(event);
  }
  function reset(event: ReactMouseEvent<HTMLButtonElement>) {
    event.currentTarget.style.transform = "translate3d(0, 0, 0)";
    onMouseLeave?.(event);
  }
  return <button {...props} className={`magnetic-button ${className}`.trim()} onMouseMove={move} onMouseLeave={reset}>{children}</button>;
}
function ProductCard({ product, onOpen, onCategory }: { product: Product; onOpen?: (product: Product) => void; onCategory?: (category: string) => void }) { return <article className="product-card" onClick={() => onCategory ? onCategory(product.category) : onOpen?.(product)}><div className="card-image"><img src={product.image} alt={product.title}/><span>01</span></div><div className="card-meta"><small>{product.categoryLabel} · {product.hook}</small><h3>{product.title}</h3><b>${product.price.toFixed(2)}</b></div></article>; }
function ShopView({ products, active, setActive, onOpen }: { products: Product[]; active: string; setActive: (value: string) => void; onOpen: (p: Product) => void }) { return <section className="page-view shell"><div className="page-kicker">SHOP / {active === "all" ? "THE FULL GRID" : `${products[0]?.categoryLabel ?? "CATEGORY"} COLLECTION`}</div><h1>{active === "all" ? "Objects for the moments you replay." : `${products[0]?.categoryLabel ?? "Category"} for the moments you replay.`}</h1><p className="page-intro">{active === "all" ? "Six original F1-inspired objects across five collections. One price. Choose your object and build your grid." : `The complete ${products[0]?.categoryLabel?.toLowerCase() ?? "category"} collection. Select the object to see its story, options and demo checkout.`}</p><Tabs active={active} setActive={setActive}/><div className="product-grid">{products.map((p) => <ProductCard key={p.id} product={p} onOpen={onOpen}/>)}</div></section>; }
function ProductView({ product, image, setImage, zoomOrigin, setZoomOrigin, onAdd, onBack, onRelated }: { product: Product; image: string; setImage: (value: string) => void; zoomOrigin: string; setZoomOrigin: (value: string) => void; onAdd: () => void; onBack: () => void; onRelated: (product: Product) => void }) { return <section className="product-view"><div className="shell product-layout"><div><button className="back-link" onClick={onBack}>← Back to the grid</button><div className="gallery-wrap"><div className="thumbs"><button className={image === product.image ? "active" : ""} onClick={() => setImage(product.image)}><img src={product.image} alt="Primary view"/></button><button className={image === product.altImage ? "active" : ""} onClick={() => setImage(product.altImage)}><img src={product.altImage} alt="Alternate view"/></button></div><div className="product-visual" onMouseMove={(event) => { const box = event.currentTarget.getBoundingClientRect(); setZoomOrigin(`${((event.clientX - box.left) / box.width) * 100}% ${((event.clientY - box.top) / box.height) * 100}%`); }}><img src={image} alt={product.title} style={{ transformOrigin: zoomOrigin }}/><span className="zoom-label">Hover to inspect 1:1 detail</span></div></div></div><div className="product-summary"><p className="eyebrow">{product.categoryLabel} · {product.hook}</p><h1>{product.title}</h1><p className="price">${product.price.toFixed(2)}</p><p className="story">{product.story}</p><hr/><label>Style<select><option>{product.colors[0]}</option><option>{product.colors[1]}</option></select></label><label>Size<select>{product.sizes.map((size) => <option key={size}>{size}</option>)}</select></label><button className="add-button" onClick={onAdd}>Add to your grid <span>→</span></button><div className="details">{product.details.map((detail) => <p key={detail}><span>+</span>{detail}</p>)}</div><div className="assurance"><span>01</span><p><b>One story / one object</b> Every design carries the moment that inspired it.</p><span>02</span><p><b>$39.99 flat price</b> Demo pricing stays simple from product to checkout.</p><span>03</span><p><b>Built for replay nights</b> Small objects with a strong point of view.</p></div></div></div><section className="related-products shell"><div className="section-head"><h2>You may also replay</h2><span>Related stories / 04 objects</span></div><div className="product-grid">{products.filter((item) => item.id !== product.id).slice(0, 4).map((item) => <ProductCard key={item.id} product={item} onOpen={onRelated} />)}</div></section></section>; }
function Checkout({ cart, subtotal, onBack, onPlace }: { cart: Product[]; subtotal: number; onBack: () => void; onPlace: () => void }) { return <section className="checkout-view shell"><button className="back-link" onClick={onBack}>← Continue shopping</button><div className="checkout-grid"><form onSubmit={(e) => { e.preventDefault(); onPlace(); }}><p className="eyebrow">CHECKOUT / DEMO</p><h1>Finish your grid.</h1><div className="form-section"><h2>Contact</h2><label>Email<input type="email" placeholder="fan@example.com" required/></label></div><div className="form-section"><h2>Delivery</h2><div className="two-col"><label>First name<input required placeholder="Alex"/></label><label>Last name<input required placeholder="Paddock"/></label></div><label>Address<input required placeholder="1 Race Day Lane"/></label><div className="two-col"><label>City<input required placeholder="Monaco"/></label><label>Postcode<input required placeholder="98000"/></label></div></div><div className="form-section"><h2>Payment</h2><label className="radio"><input type="radio" defaultChecked/> Test payment — no charge</label><p className="form-note">No real payment or order is submitted in this demo.</p></div><button className="add-button" type="submit">Place demo order <span>→</span></button></form><aside className="order-summary"><h2>Your grid</h2>{cart.map((p) => <div className="order-item" key={p.id}><img src={p.image} alt=""/><div><b>{p.title}</b><span>Style / Size selected</span></div><strong>${p.price.toFixed(2)}</strong></div>)}<dl><div><dt>Subtotal</dt><dd>${subtotal.toFixed(2)}</dd></div><div><dt>Shipping</dt><dd>Free</dd></div><div><dt>Total</dt><dd>${subtotal.toFixed(2)}</dd></div></dl></aside></div></section>; }
function Success({ cart, onHome }: { cart: Product[]; onHome: () => void }) { return <section className="success-view shell"><div className="success-mark">✓</div><p className="eyebrow">ORDER / DEMO COMPLETE</p><h1>Your story is in the grid.</h1><p>Demo order APX-{String(cart.length * 1739).padStart(6, "0")} is ready for the next step. No payment was captured.</p><button className="add-button" onClick={onHome}>Return to the site <span>→</span></button></section>; }
function Newsletter() { return <section className="newsletter"><div className="shell newsletter-inner"><h2>The next story drops after the chequered flag.</h2><form onSubmit={(e) => e.preventDefault()}><label htmlFor="email">Email address</label><div><input id="email" type="email" placeholder="fan@example.com" required/><button type="submit">Join the grid →</button></div></form></div></section>; }
function ContactPanel() { const [sent, setSent] = useState(false); const [error, setError] = useState(""); async function submit(event: FormEvent<HTMLFormElement>) { event.preventDefault(); setError(""); const form = new FormData(event.currentTarget); const response = await fetch("/api/messages", { method: "POST", headers: { "content-type": "application/json" }, body: JSON.stringify({ name: form.get("name"), email: form.get("email"), subject: form.get("subject"), message: form.get("message") }) }); if (response.ok) { setSent(true); event.currentTarget.reset(); } else setError("Please check your details and try again."); } return <section className="contact-panel section shell"><div><p className="eyebrow">CONTACT / CUSTOMER SUPPORT</p><h2>Have a question about a story?</h2><p>Ask about a product, delivery or your order. The Apexik team will reply from the customer messages inbox.</p></div>{sent ? <p className="contact-success">Message received. We’ll get back to you soon.</p> : <form onSubmit={submit}><div className="two-col"><input name="name" placeholder="Your name" required/><input name="email" type="email" placeholder="Email address" required/></div><input name="subject" placeholder="Subject" required/><textarea name="message" rows={4} placeholder="Write your question…" required/><button className="add-button" type="submit">Send message <span>→</span></button>{error && <small>{error}</small>}</form>}</section>; }

function ProductViewNew({ product, image, setImage, zoomOrigin, setZoomOrigin, onAdd, onBack, onRelated }: { product: Product; image: string; setImage: (value: string) => void; zoomOrigin: string; setZoomOrigin: (value: string) => void; onAdd: (style: string, size: string) => void; onBack: () => void; onRelated: (product: Product) => void }) {
  const [style, setStyle] = useState(product.colors[0]);
  const [size, setSize] = useState(product.sizes[0]);
  const [lightboxOpen, setLightboxOpen] = useState(false);
  const gallery = product.gallery?.length ? product.gallery : [{ src: product.image, alt: `${product.title} primary view` }, { src: product.altImage, alt: `${product.title} alternate view` }];
  useEffect(() => {
    if (!lightboxOpen) return;
    const previousOverflow = document.body.style.overflow;
    const closeOnEscape = (event: KeyboardEvent) => { if (event.key === "Escape") setLightboxOpen(false); };
    document.body.style.overflow = "hidden";
    window.addEventListener("keydown", closeOnEscape);
    return () => { document.body.style.overflow = previousOverflow; window.removeEventListener("keydown", closeOnEscape); };
  }, [lightboxOpen]);
  return <section className="product-view"><div className="shell product-layout"><div className="product-gallery-column"><button className="back-link" onClick={onBack}>← Back to the grid</button><div className="gallery-sticky"><div className="gallery-wrap"><div className="thumbs">{gallery.map((item) => <button key={item.src} className={image === item.src ? "active" : ""} onClick={() => setImage(item.src)}><img src={item.src} alt={item.alt}/></button>)}</div><button type="button" className="product-visual" aria-label={`Enlarge ${product.title} image`} onClick={() => setLightboxOpen(true)} onMouseMove={(event) => { const box = event.currentTarget.getBoundingClientRect(); setZoomOrigin(`${((event.clientX - box.left) / box.width) * 100}% ${((event.clientY - box.top) / box.height) * 100}%`); }}><img src={image} alt={product.title} style={{ transformOrigin: zoomOrigin }}/><span className="zoom-plus" aria-hidden="true">+</span><span className="zoom-label">Pinned gallery · Hover to zoom · Click to enlarge</span></button></div></div></div><div className="product-summary"><p className="eyebrow">{product.categoryLabel} · {product.hook}</p><h1>{product.title}</h1><p className="price">${product.price.toFixed(2)}</p><p className="story">{product.story}</p><hr/><label>Style<select value={style} onChange={(event) => setStyle(event.target.value)}>{product.colors.map((color) => <option key={color}>{color}</option>)}</select></label><label>Size<select value={size} onChange={(event) => setSize(event.target.value)}>{product.sizes.map((item) => <option key={item}>{item}</option>)}</select></label><MagneticButton className="add-button" onClick={() => onAdd(style, size)}>Add to your grid <span>→</span></MagneticButton><div className="details">{product.details.map((detail) => <p key={detail}><span>+</span>{detail}</p>)}</div><div className="assurance"><span>01</span><p><b>One story / one object</b> Every design carries the moment that inspired it.</p><span>02</span><p><b>$39.99 flat price</b> Demo pricing stays simple from product to checkout.</p><span>03</span><p><b>Built for replay nights</b> Small objects with a strong point of view.</p></div></div></div><section className="related-products shell"><div className="section-head"><h2>You may also replay</h2><span>Related stories / 04 objects</span></div><div className="product-grid">{seedProducts.filter((item) => item.id !== product.id).slice(0, 4).map((item) => <ProductCard key={item.id} product={item} onOpen={onRelated} />)}</div></section>{lightboxOpen && <div className="image-lightbox" role="dialog" aria-modal="true" aria-label={`${product.title} enlarged image`} onClick={() => setLightboxOpen(false)}><button type="button" className="image-lightbox-close" aria-label="Close enlarged image" onClick={() => setLightboxOpen(false)}>×</button><img src={image} alt={product.title} onClick={(event) => event.stopPropagation()}/></div>}</section>;
}

function CheckoutNew({ cart, subtotal, onBack, onPlace }: { cart: CartItem[]; subtotal: number; onBack: () => void; onPlace: (orderNumber: string) => void }) {
  const [error, setError] = useState(""); const [submitting, setSubmitting] = useState(false);
  async function submit(event: FormEvent<HTMLFormElement>) { event.preventDefault(); setError(""); setSubmitting(true); const data = new FormData(event.currentTarget); try { const response = await fetch("/api/orders", { method: "POST", headers: { "content-type": "application/json" }, body: JSON.stringify({ customerEmail: String(data.get("email") ?? ""), customerName: `${data.get("firstName")} ${data.get("lastName")}`.trim(), shippingAddress: `${data.get("address")}, ${data.get("city")} ${data.get("postcode")}`, items: cart.map((item) => ({ skuId: item.skuId, productTitle: item.title, style: item.selectedStyle, size: item.selectedSize, quantity: 1 })) }) }); const payload = await response.json() as { error?: string; order?: { orderNumber: string } }; if (!response.ok || !payload.order) throw new Error(payload.error ?? "Order could not be created"); onPlace(payload.order.orderNumber); } catch (submitError) { setError(submitError instanceof Error ? submitError.message : "Order could not be created"); } finally { setSubmitting(false); } }
  return <section className="checkout-view shell"><button className="back-link" onClick={onBack}>← Continue shopping</button><div className="checkout-grid"><form onSubmit={submit}><p className="eyebrow">CHECKOUT / DEMO</p><h1>Finish your grid.</h1><div className="form-section"><h2>Contact</h2><label>Email<input name="email" type="email" placeholder="fan@example.com" required/></label></div><div className="form-section"><h2>Delivery</h2><div className="two-col"><label>First name<input name="firstName" required placeholder="Alex"/></label><label>Last name<input name="lastName" required placeholder="Paddock"/></label></div><label>Address<input name="address" required placeholder="1 Race Day Lane"/></label><div className="two-col"><label>City<input name="city" required placeholder="Monaco"/></label><label>Postcode<input name="postcode" required placeholder="98000"/></label></div></div><div className="form-section"><h2>Payment</h2><label className="radio"><input type="radio" defaultChecked/> Test payment — no charge</label><p className="form-note">No real payment or order is submitted in this demo.</p></div>{error && <p className="form-note" style={{ color: "var(--red)" }}>{error}</p>}<MagneticButton className="add-button" type="submit" disabled={submitting}>{submitting ? "Placing order…" : "Place demo order"}<span>→</span></MagneticButton></form><aside className="order-summary"><h2>Your grid</h2>{cart.map((item, index) => <div className="order-item" key={`${item.id}-${index}`}><img src={item.image} alt=""/><div><b>{item.title}</b><span>{item.selectedStyle} / {item.selectedSize}</span></div><strong>${item.price.toFixed(2)}</strong></div>)}<dl><div><dt>Subtotal</dt><dd>${subtotal.toFixed(2)}</dd></div><div><dt>Shipping</dt><dd>Free</dd></div><div><dt>Total</dt><dd>${subtotal.toFixed(2)}</dd></div></dl></aside></div></section>;
}

function SuccessNew({ orderNumber, onHome }: { orderNumber: string; onHome: () => void }) { return <section className="success-view shell"><div className="success-mark">✓</div><p className="eyebrow">ORDER / DEMO COMPLETE</p><h1>Your story is in the grid.</h1><p>Demo order {orderNumber || "APX-PENDING"} is ready for the next step. No payment was captured.</p><button className="add-button" onClick={onHome}>Return to the site <span>→</span></button></section>; }
