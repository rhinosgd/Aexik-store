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
  hook: string;
};

const directions = [
  { label: "Desk Mats", title: "DESK MATS", copy: "Your desk. Your pit wall. Bring the decisions that changed a race into your everyday setup.", scene: "/race-strategy-room.png", position: "65% 50%", rotation: -8 },
  { label: "Clothing", title: "CLOTHING", copy: "Wear the moment. Race-day tension, last-corner drama and the stories that stay with you.", scene: "/race-street-circuit.png", position: "72% 50%", rotation: 6 },
  { label: "Stickers", title: "STICKERS", copy: "Small pieces. Big race energy. Give your laptop, notebook or everyday carry a place on the grid.", scene: "/race-pitlane-hero.png", position: "60% 65%", rotation: -5 },
  { label: "Key Chains", title: "KEY CHAINS", copy: "Take the radio call with you. A little piece of the paddock, wherever the next day takes you.", scene: "/race-strategy-room.png", position: "90% 40%", rotation: 8 },
  { label: "Curated Finds", title: "CURATED FINDS", copy: "For the hours between races. Thoughtful objects for early starts, late replays and every Sunday ritual.", scene: "/race-street-circuit.png", position: "42% 65%", rotation: -4 },
];

function normalized(label: string) {
  return label.toLowerCase().replace(/[^a-z]/g, "");
}

function ease(value: number) {
  const t = Math.max(0, Math.min(1, value));
  return t * t * (3 - 2 * t);
}

export default function CategoryJourney({ products, onCategory }: { products: JourneyProduct[]; onCategory: (category: string) => void }) {
  const section = useRef<HTMLElement>(null);
  const stage = useRef<HTMLDivElement>(null);
  const [position, setPosition] = useState(0);
  const [reducedMotion, setReducedMotion] = useState(false);
  const chapters = useMemo(() => directions.flatMap((direction) => {
    const items = products.filter((product) => normalized(product.categoryLabel) === normalized(direction.label));
    return items.length ? [{ ...direction, product: items[0], count: items.length }] : [];
  }), [products]);

  useEffect(() => {
    const preference = window.matchMedia("(prefers-reduced-motion: reduce)");
    const updatePreference = () => setReducedMotion(preference.matches);
    updatePreference();
    preference.addEventListener("change", updatePreference);
    return () => preference.removeEventListener("change", updatePreference);
  }, []);

  useEffect(() => {
    let frame = 0;
    const measure = () => {
      frame = 0;
      if (!section.current || !stage.current) return;
      const bounds = section.current.getBoundingClientRect();
      const top = parseFloat(getComputedStyle(stage.current).top) || 0;
      // One viewport of natural page scrolling advances one collection.
      // The stage stays sticky; no wheel cancellation or scroll hijacking.
      const stride = Math.max(1, (section.current.offsetHeight - stage.current.offsetHeight) / Math.max(1, chapters.length - .5));
      setPosition(Math.max(0, Math.min(chapters.length - 1, (top - bounds.top) / stride)));
    };
    const schedule = () => { if (!frame) frame = window.requestAnimationFrame(measure); };
    const observer = new ResizeObserver(schedule);
    if (section.current) observer.observe(section.current);
    if (stage.current) observer.observe(stage.current);
    window.addEventListener("scroll", schedule, { passive: true });
    window.addEventListener("resize", schedule);
    measure();
    return () => {
      window.cancelAnimationFrame(frame);
      observer.disconnect();
      window.removeEventListener("scroll", schedule);
      window.removeEventListener("resize", schedule);
    };
  }, [chapters.length]);

  if (!chapters.length) return null;
  const lower = Math.floor(position);
  const blend = ease((position - lower - .18) / .64);
  const active = Math.min(chapters.length - 1, lower + (blend >= .5 ? 1 : 0));

  function goToChapter(index: number) {
    if (!section.current || !stage.current) return;
    const top = parseFloat(getComputedStyle(stage.current).top) || 0;
    const stride = (section.current.offsetHeight - stage.current.offsetHeight) / Math.max(1, chapters.length - .5);
    window.scrollTo({ top: window.scrollY + section.current.getBoundingClientRect().top - top + index * stride, behavior: reducedMotion ? "instant" : "smooth" });
  }

  function skipJourney() {
    if (!section.current) return;
    const header = stage.current ? parseFloat(getComputedStyle(stage.current).top) || 0 : 0;
    window.scrollTo({ top: window.scrollY + section.current.getBoundingClientRect().bottom - header, behavior: reducedMotion ? "instant" : "smooth" });
  }

  return <section ref={section} className="category-journey" aria-label="Explore collections by scrolling" style={{ "--chapter-count": chapters.length } as CSSProperties}>
    <div ref={stage} className="journey-stage" data-active-category={chapters[active].product.category}>
      <div className="journey-scenes" aria-hidden="true">
        {chapters.map((chapter, index) => {
          const opacity = index === lower ? 1 - blend : index === lower + 1 ? blend : 0;
          return <img key={chapter.label} src={chapter.scene} alt="" className="journey-scene" style={{ opacity, objectPosition: chapter.position, transform: reducedMotion ? undefined : `scale(${1.08 + (position - index) * .035}) translateX(${(position - index) * -2}%)` }} />;
        })}
      </div>
      <div className="journey-shade" aria-hidden="true" />
      <div className="journey-topline"><span>APEXIK / THE COLLECTIONS</span><button onClick={skipJourney}>Skip to all products <span aria-hidden="true">↗</span></button></div>
      <div className="journey-chapters">
        {chapters.map((chapter, index) => {
          const outgoing = index === lower;
          const incoming = index === lower + 1;
          const opacity = outgoing ? 1 - blend : incoming ? blend : 0;
          const offset = outgoing ? -blend : incoming ? 1 - blend : index < lower ? -1 : 1;
          const isActive = index === active;
          return <article key={chapter.label} className={`journey-chapter ${isActive ? "is-active" : ""}`} aria-hidden={!isActive} inert={!isActive} style={{ opacity, visibility: opacity > .001 ? "visible" : "hidden", zIndex: isActive ? 2 : 1 }}>
            <div className="journey-copy" style={{ transform: reducedMotion ? undefined : `translate3d(0, ${offset * 65}px, 0)` }}>
              <p className="journey-eyebrow">COLLECTION {String(index + 1).padStart(2, "0")} / {String(chapters.length).padStart(2, "0")}</p>
              {index === 0 ? <h1>{chapter.title}</h1> : <h2>{chapter.title}</h2>}
              <p className="journey-description">{chapter.copy}</p>
              <button className="journey-shop" onClick={() => onCategory(chapter.product.category)}>Explore {chapter.label} <span aria-hidden="true">↗</span></button>
              <p className="journey-detail">{chapter.count} {chapter.count === 1 ? "design" : "designs"} <span>/</span> Made for race-day people</p>
            </div>
            <button className="journey-object" aria-label={`Browse all ${chapter.label}`} onClick={() => onCategory(chapter.product.category)} style={{ transform: reducedMotion ? undefined : `perspective(1400px) translate3d(${offset * 130}px, ${offset * 75}px, ${-Math.abs(offset) * 150}px) rotateY(${offset * -24}deg) rotateZ(${chapter.rotation + offset * 14}deg) scale(${1 - Math.abs(offset) * .13})` }}>
              <img src={chapter.product.image} alt={chapter.product.title} width={1600} height={1600} draggable={false} />
              <span className="journey-object-caption"><span>{chapter.product.title}</span><strong>${chapter.product.price.toFixed(2)}</strong></span>
            </button>
          </article>;
        })}
      </div>
      <div className="journey-bottom">
        <div className="journey-scroll-cue"><span aria-hidden="true">↓</span><span>SCROLL TO EXPLORE<small>{String(active + 1).padStart(2, "0")} — {String(chapters.length).padStart(2, "0")}</small></span></div>
        <nav className="journey-nav" aria-label="Collection chapters">
          {chapters.map((chapter, index) => <button key={chapter.label} onClick={() => goToChapter(index)} aria-current={index === active ? "step" : undefined}><span className="journey-nav-number">0{index + 1}</span><span>{chapter.label}</span><span className="journey-nav-progress" aria-hidden="true" style={{ transform: `scaleX(${Math.max(0, Math.min(1, position - index + .1))})` }} /></button>)}
        </nav>
      </div>
    </div>
  </section>;
}
