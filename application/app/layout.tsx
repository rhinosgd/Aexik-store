import type { Metadata } from "next";
import "./globals.css";
import "./overrides.css";
import "./admin/admin.css";

export const metadata: Metadata = {
  title: "APEXIK — Race-day stories engineered into objects",
  description: "A complete APEXIK demo storefront with six original F1-inspired objects across five collections.",
  icons: {
    icon: "/brand/apexik-mark.png",
    shortcut: "/brand/apexik-mark.png",
  },
};

const structuredData = {
  "@context": "https://schema.org",
  "@type": "Organization",
  name: "APEXIK",
  url: "https://apexik.com",
  description: "Race-day stories engineered into collectible objects.",
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <body>{children}<script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }} /></body>
    </html>
  );
}
