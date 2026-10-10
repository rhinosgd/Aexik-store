import { desc, eq } from "drizzle-orm";
import { getDb } from "../../../db";
import { orderItems, orders } from "../../../db/schema";
import { isAdmin } from "../_lib/admin";

export async function GET(request: Request) {
  if (!isAdmin(request)) {
    return Response.json({ error: "Admin authorization required" }, { status: 401 });
  }
  try {
    const db = getDb();
    return Response.json({ orders: await db.select().from(orders).orderBy(desc(orders.createdAt)) });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Orders unavailable" }, { status: 503 });
  }
}

export async function POST(request: Request) {
  const payload = await request.json() as { customerEmail?: string; customerName?: string; shippingAddress?: string; items?: Array<{ skuId?: string; productTitle?: string; style?: string; size?: string; quantity?: number; unitPrice?: number }> };
  if (!payload.customerEmail || !payload.customerName || !payload.shippingAddress || !payload.items?.length) return Response.json({ error: "Customer details and items are required" }, { status: 400 });
  const items = payload.items.map((item) => ({ ...item, quantity: Math.max(1, Math.floor(item.quantity ?? 1)), unitPrice: 39.99 }));
  const subtotal = items.reduce((sum, item) => sum + item.quantity * item.unitPrice, 0);
  const id = crypto.randomUUID();
  const orderNumber = `APX-${id.slice(0, 8).toUpperCase()}`;
  try {
    const db = getDb();
    await db.insert(orders).values({ id, orderNumber, customerEmail: payload.customerEmail.trim(), customerName: payload.customerName.trim(), shippingAddress: payload.shippingAddress.trim(), subtotal, shipping: 0, total: subtotal, status: "pending" });
    await db.insert(orderItems).values(items.map((item) => ({ id: crypto.randomUUID(), orderId: id, skuId: item.skuId, productTitle: item.productTitle ?? "APEXIK product", style: item.style ?? "Default", size: item.size ?? "Default", quantity: item.quantity, unitPrice: item.unitPrice })));
    return Response.json({ order: { id, orderNumber, total: subtotal, status: "pending" } }, { status: 201 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Order creation failed" }, { status: 503 });
  }
}

export async function PATCH(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { id?: string; status?: string };
  const allowed = ["pending", "paid", "processing", "shipped", "completed", "cancelled"];
  if (!payload.id || !payload.status || !allowed.includes(payload.status)) return Response.json({ error: "Valid order id and status are required" }, { status: 400 });
  try {
    const [order] = await getDb().update(orders).set({ status: payload.status, updatedAt: new Date().toISOString() }).where(eq(orders.id, payload.id)).returning();
    return order ? Response.json({ order }) : Response.json({ error: "Order not found" }, { status: 404 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Order update failed" }, { status: 503 });
  }
}
