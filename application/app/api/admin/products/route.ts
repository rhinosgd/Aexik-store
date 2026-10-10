import { and, eq } from "drizzle-orm";
import { getDb } from "../../../../db";
import { inventory, productImages, products, skus, variants } from "../../../../db/schema";
import { isAdmin } from "../../_lib/admin";

export async function GET(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  try {
    const db = getDb();
    return Response.json({ products: await db.select().from(products) });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Products unavailable" }, { status: 503 });
  }
}

export async function PATCH(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { id?: string; title?: string; price?: number; status?: string; story?: string };
  if (!payload.id) return Response.json({ error: "id is required" }, { status: 400 });
  const updates = Object.fromEntries(Object.entries({ title: payload.title, price: payload.price, status: payload.status, story: payload.story }).filter(([, value]) => value !== undefined));
  if (!Object.keys(updates).length) return Response.json({ error: "No editable fields supplied" }, { status: 400 });
  try {
    const db = getDb();
    const [product] = await db.update(products).set({ ...updates, updatedAt: new Date().toISOString() }).where(eq(products.id, payload.id)).returning();
    return product ? Response.json({ product }) : Response.json({ error: "Product not found" }, { status: 404 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Product update failed" }, { status: 503 });
  }
}

type BatchProduct = { slug?: string; title?: string; categoryId?: string; price?: number; status?: string; story?: string; description?: string; styles?: string; sizes?: string; stock?: number; imageUrl?: string };

export async function POST(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { items?: BatchProduct[] };
  const items = payload.items?.slice(0, 100) ?? [];
  if (!items.length) return Response.json({ error: "items are required" }, { status: 400 });
  if (items.some((item) => !item.slug || !item.title || !item.categoryId)) return Response.json({ error: "slug, title and categoryId are required" }, { status: 400 });
  try {
    const db = getDb();
    const imported: string[] = [];
    for (const item of items) {
      const now = new Date().toISOString();
      await db.insert(products).values({ id: crypto.randomUUID(), categoryId: item.categoryId!, slug: item.slug!, title: item.title!, description: item.description ?? "", story: item.story ?? "", price: Number(item.price) || 0, status: item.status === "published" ? "published" : "draft", updatedAt: now }).onConflictDoUpdate({ target: products.slug, set: { categoryId: item.categoryId!, title: item.title!, description: item.description ?? "", story: item.story ?? "", price: Number(item.price) || 0, status: item.status === "published" ? "published" : "draft", updatedAt: now } });
      const [product] = await db.select().from(products).where(eq(products.slug, item.slug!)).limit(1);
      const styleList = splitOptions(item.styles, "Style A");
      const sizeList = splitOptions(item.sizes, "One Size");
      for (const style of styleList) for (const size of sizeList) {
        await db.insert(variants).values({ id: crypto.randomUUID(), productId: product.id, style, size }).onConflictDoNothing();
        const [variant] = await db.select().from(variants).where(and(eq(variants.productId, product.id), eq(variants.style, style), eq(variants.size, size))).limit(1);
        const code = `${item.slug}-${style}-${size}`.toUpperCase().replace(/[^A-Z0-9]+/g, "-").replace(/^-|-$/g, "").slice(0, 80);
        await db.insert(skus).values({ id: crypto.randomUUID(), variantId: variant.id, code }).onConflictDoNothing();
        const [sku] = await db.select().from(skus).where(eq(skus.code, code)).limit(1);
        await db.insert(inventory).values({ id: crypto.randomUUID(), skuId: sku.id, available: Math.max(0, Number(item.stock) || 0), updatedAt: now }).onConflictDoUpdate({ target: inventory.skuId, set: { available: Math.max(0, Number(item.stock) || 0), updatedAt: now } });
      }
      if (item.imageUrl) {
        const [existingImage] = await db.select().from(productImages).where(and(eq(productImages.productId, product.id), eq(productImages.src, item.imageUrl))).limit(1);
        if (!existingImage) await db.insert(productImages).values({ id: crypto.randomUUID(), productId: product.id, sourceType: item.imageUrl.startsWith("https://") ? "external" : "local", src: item.imageUrl, alt: item.title!, width: 1600, height: 1600 });
      }
      imported.push(product.id);
    }
    return Response.json({ imported: imported.length, productIds: imported }, { status: 201 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Batch import failed" }, { status: 503 });
  }
}

function splitOptions(value: string | undefined, fallback: string) {
  const values = (value || fallback).split("|").map((part) => part.trim()).filter(Boolean);
  return values.length ? values : [fallback];
}
