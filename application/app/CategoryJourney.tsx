"use client";

import { useEffect, useMemo, useRef, useState, type CSSProperties } from "react";
import "./category-journey.css";

type JourneyProduct = {
  id: string;
  category: string;
  categoryLabel: string;
  title: string;
  image: string;
  price: number;
};

const directions = [
  { label: "Desk Mats", title: "DESK MATS", scene: "/race-strategy-room.png", position: "65% 50%", rotation: -6 },
  { label: "Clothing", title: "CLOTHING", scene: "/race-street-circuit.png", position: "72% 50%", rotation: 4 },
  { label: "Stickers", title: "STICKERS", scene: "/race-pitlane-hero.png", position: "60% 65%", rotation: -4 },
  { label: "Key Chains", title: "KEY CHAINS", scene: "/race-strategy-room.png", position: "90% 40%", rotation: 6 },
  { label: "Curated Finds", title: "CURATED FINDS", scene: "/race-street-circuit.png", position: "42% 65%", rotation: -3 },
];

function normalized(label: string) {
  return label.toLowerCase().replace(/[^a-z]/g, "");
}

export default function CategoryJourney({ products, onCategory }: { products: JourneyProduct[]; onCategory: (category: string) => void }) {
  const section = useRef<HTMLElement>(null);
  const [active, setActive] = useState(0);
  const [reducedMotion, setReducedMotion] = useState(false);
  const chapters = useMemo(() => directions.flatMap((direction) => {
    const items = products.filter((product) => normalized(product.categoryLabel) === normalized(direction.label));
    return items.length ? [{ ...direction, product: items[0] }] : [];
  }), [products]);

  useEffect(() => {
    const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
    const updatePreference = () => setReducedMotion(preference.matches);
    updatePreference();
    preference.addEventListener("change", updatePreference);
    return () => preference.removeEventListener("change", updatePreference);
  }, []);

  useEffect(() => {
    const root = section.current;
    if (!root) return;
    const panels = [...root.querySelectorAll<HTMLElement>(".journey-panel")];
    const observer = new IntersectionObserver((entries) => {
      const mostVisible = entries.filter((entry) => entry.isIntersecting).sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];
      if (mostVisible) setActive(Number((mostVisible.target as HTMLElement).dataset.index));
    }, { threshold: [0.25, 0.5, 0.75] });
    panels.forEach((panel) => observer.observe(panel));
    return () => observer.disconnect();
  }, [chapters.length]);

  if (!chapters.length) return null;

  function goToChapter(index: number) {
    section.current?.querySelector<HTMLElement>(`[data-index="${index}"]`)?.scrollIntoView({ behavior: reducedMotion ? "instant" : "smooth", block: "start" });
  }

  return <section ref={section} className="category-journey" aria-label="Explore product collections">
    {chapters.map((chapter, index) => <article className="journey-panel" data-index={index} data-active={active === index} key={chapter.label}>
      <img className="journey-scene" src={chapter.scene} alt="" aria-hidden="true" style={{ objectPosition: chapter.position }} />
      <div className="journey-shade" aria-hidden="true" />
      <div className="journey-topline"><span>APEXIK / COLLECTION {String(index + 1).padStart(2, "0")} — {String(chapters.length).padStart(2, "0")}</span><button onClick={() => document.querySelector(".releases")?.scrollIntoView({ behavior: reducedMotion ? "instant" : "smooth" })}>Skip to new arrivals <span aria-hidden="true">↗</span></button></div>
      <div className="journey-content">
        <div className="journey-copy">
          <h1>{chapter.title}</h1>
          <button className="journey-shop" aria-label={`Enter ${chapter.label} collection`} title={`Enter ${chapter.label}`} onClick={() => onCategory(chapter.product.category)}><span className="journey-shop-label">Explore {chapter.label}</span><span className="journey-shop-lamp" aria-hidden="true"><svg viewBox="0 0 64 64"><path d="M10 32h34M31 16l17 16-17 16" fill="none" stroke="currentColor" strokeWidth="7" strokeLinecap="round" strokeLinejoin="round"/></svg></span></button>
        </div>
        <button className="journey-object" aria-label={`Browse all ${chapter.label}`} onClick={() => onCategory(chapter.product.category)} style={{ "--object-rotation": `${chapter.rotation}deg` } as CSSProperties}>
          <img src={chapter.product.image} alt={chapter.product.title} width={1600} height={1600} draggable={false} />
        </button>
      </div>
      <div className="journey-bottom">
        <div className="journey-scroll-cue"><span aria-hidden="true">↓</span><span>{index === chapters.length - 1 ? "CONTINUE TO THE STORE" : "SCROLL FOR NEXT COLLECTION"}<small>{String(index + 1).padStart(2, "0")} — {String(chapters.length).padStart(2, "0")}</small></span></div>
        <nav className="journey-nav" aria-label="Jump to collection">
          {chapters.map((item, itemIndex) => <button key={item.label} onClick={() => goToChapter(itemIndex)} aria-current={itemIndex === active ? "step" : undefined}><span className="journey-nav-number">0{itemIndex + 1}</span><span>{item.label}</span><i aria-hidden="true" /></button>)}
        </nav>
      </div>
    </article>)}
  </section>;
}
