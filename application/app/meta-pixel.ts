"use client";

type MetaEventParams = Record<string, string | number | string[] | undefined>;
type MetaPixelFunction = ((...args: unknown[]) => void) & { callMethod?: (...args: unknown[]) => void; queue?: unknown[]; push?: MetaPixelFunction; loaded?: boolean; version?: string };

declare global {
  interface Window {
    fbq?: MetaPixelFunction;
    _fbq?: MetaPixelFunction;
  }
}

interface ImportMetaEnv {
  readonly VITE_META_PIXEL_ID?: string;
}

interface ImportMeta {
  readonly env: ImportMetaEnv;
}

const pixelId = (import.meta as ImportMeta).env?.VITE_META_PIXEL_ID?.trim();
let initializedPixelId: string | undefined;

export function initMetaPixel() {
  if (typeof window === "undefined" || !pixelId || initializedPixelId === pixelId) return;

  const fbq: MetaPixelFunction = window.fbq ?? Object.assign(
    (...args: unknown[]) => {
      if (fbq.callMethod) fbq.callMethod(...args);
      else fbq.queue?.push(args);
    },
    { queue: [] as unknown[], push: undefined as MetaPixelFunction | undefined, loaded: true, version: "2.0" },
  );
  fbq.push = fbq;
  window.fbq = window._fbq = fbq;

  if (!document.querySelector('script[data-apexik-meta-pixel="true"]')) {
    const script = document.createElement("script");
    script.async = true;
    script.src = "https://connect.facebook.net/en_US/fbevents.js";
    script.dataset.apexikMetaPixel = "true";
    document.head.appendChild(script);
  }

  fbq("init", pixelId);
  initializedPixelId = pixelId;
}

export function trackMetaEvent(eventName: string, params?: MetaEventParams) {
  if (typeof window === "undefined" || !pixelId || !window.fbq) return;
  if (params) window.fbq("track", eventName, params);
  else window.fbq("track", eventName);
}
