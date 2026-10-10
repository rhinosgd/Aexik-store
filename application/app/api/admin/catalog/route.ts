import { asc, eq } from "drizzle-orm";
import { getDb } from "../../../../db";
import { categories, inventory, raceMoments, skus, variants } from "../../../../db/schema";
import { isAdmin } from "../../_lib/admin";

export async function GET(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  try {
    const db = getDb();
    const [categoryRows, variantRows, skuRows, inventoryRows, momentRows] = await Promise.all([
      db.select().from(categories).orderBy(asc(categories.sortOrder)),
      db.select().from(variants), db.select().from(skus), db.select().from(inventory), db.select().from(raceMoments),
    ]);
    return Response.json({ categories: categoryRows, variants: variantRows, skus: skuRows, inventory: inventoryRows, raceMoments: momentRows });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Admin catalog unavailable" }, { status: 503 });
  }
}

export async function PATCH(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { entity?: "category" | "inventory" | "moment"; id?: string; sortOrder?: number; isPublished?: boolean; available?: number; reserved?: number; status?: string; rightsStatus?: string; title?: string; summary?: string; body?: string; sourceUrl?: string };
  if (!payload.entity || !payload.id) return Response.json({ error: "entity and id are required" }, { status: 400 });
  try {
    const db = getDb();
    if (payload.entity === "category") {
      const [category] = await db.update(categories).set({ sortOrder: payload.sortOrder, isPublished: payload.isPublished, updatedAt: new Date().toISOString() }).where(eq(categories.id, payload.id)).returning();
      return category ? Response.json({ category }) : Response.json({ error: "Category not found" }, { status: 404 });
    }
    if (payload.entity === "inventory") {
      const [row] = await db.update(inventory).set({ available: payload.available, reserved: payload.reserved, updatedAt: new Date().toISOString() }).where(eq(inventory.id, payload.id)).returning();
      return row ? Response.json({ inventory: row }) : Response.json({ error: "Inventory row not found" }, { status: 404 });
    }
    const [moment] = await db.update(raceMoments).set({ title: payload.title, summary: payload.summary, body: payload.body, sourceUrl: payload.sourceUrl, sortOrder: payload.sortOrder, status: payload.status, rightsStatus: payload.rightsStatus, updatedAt: new Date().toISOString() }).where(eq(raceMoments.id, payload.id)).returning();
    return moment ? Response.json({ raceMoment: moment }) : Response.json({ error: "Race Moment not found" }, { status: 404 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Catalog update failed" }, { status: 503 });
  }
}

export async function POST(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as Record<string, unknown> & { entity?: string };
  if (!payload.entity) return Response.json({ error: "entity is required" }, { status: 400 });
  try {
    const db = getDb(); const id = crypto.randomUUID();
    if (payload.entity === "category") { const [row] = await db.insert(categories).values({ id, slug: String(payload.slug), name: String(payload.name), sortOrder: Number(payload.sortOrder ?? 0), isPublished: payload.isPublished !== false }).returning(); return Response.json({ category: row }, { status: 201 }); }
    if (payload.entity === "variant") { const [row] = await db.insert(variants).values({ id, productId: String(payload.productId), style: String(payload.style), size: String(payload.size) }).returning(); return Response.json({ variant: row }, { status: 201 }); }
    if (payload.entity === "sku") { const [row] = await db.insert(skus).values({ id, variantId: String(payload.variantId), code: String(payload.code), providerCode: payload.providerCode ? String(payload.providerCode) : null }).returning(); return Response.json({ sku: row }, { status: 201 }); }
    if (payload.entity === "inventory") { const [row] = await db.insert(inventory).values({ id, skuId: String(payload.skuId), available: Number(payload.available ?? 0), reserved: 0 }).returning(); return Response.json({ inventory: row }, { status: 201 }); }
    if (payload.entity === "moment") { const [row] = await db.insert(raceMoments).values({ id, slug: String(payload.slug), title: String(payload.title), summary: String(payload.summary ?? ""), body: String(payload.body ?? ""), status: String(payload.status ?? "draft"), rightsStatus: String(payload.rightsStatus ?? "draft") }).returning(); return Response.json({ raceMoment: row }, { status: 201 }); }
    return Response.json({ error: "Unsupported entity" }, { status: 400 });
  } catch (error) { return Response.json({ error: error instanceof Error ? error.message : "Catalog creation failed" }, { status: 503 }); }
}

export async function DELETE(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { entity?: string; id?: string };
  if (!payload.entity || !payload.id) return Response.json({ error: "entity and id are required" }, { status: 400 });
  try {
    const db = getDb();
    if (payload.entity === "category") await db.delete(categories).where(eq(categories.id, payload.id));
    else if (payload.entity === "variant") await db.delete(variants).where(eq(variants.id, payload.id));
    else if (payload.entity === "sku") await db.delete(skus).where(eq(skus.id, payload.id));
    else if (payload.entity === "inventory") await db.delete(inventory).where(eq(inventory.id, payload.id));
    else if (payload.entity === "moment") await db.delete(raceMoments).where(eq(raceMoments.id, payload.id));
    else return Response.json({ error: "Unsupported entity" }, { status: 400 });
    return Response.json({ ok: true });
  } catch (error) { return Response.json({ error: error instanceof Error ? error.message : "Catalog deletion failed" }, { status: 503 }); }
}
