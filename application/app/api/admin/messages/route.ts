import { desc, eq } from "drizzle-orm";
import { getDb } from "../../../../db";
import { inquiries, inquiryMessages } from "../../../../db/schema";
import { isAdmin } from "../../_lib/admin";

export async function GET(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  try {
    const db = getDb();
    const threads = await db.select().from(inquiries).orderBy(desc(inquiries.updatedAt));
    const id = new URL(request.url).searchParams.get("id");
    const messages = id ? await db.select().from(inquiryMessages).where(eq(inquiryMessages.inquiryId, id)).orderBy(inquiryMessages.createdAt) : [];
    return Response.json({ threads, messages });
  } catch (error) { return Response.json({ error: error instanceof Error ? error.message : "Messages unavailable" }, { status: 503 }); }
}

export async function PATCH(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { id?: string; status?: string };
  if (!payload.id || !payload.status) return Response.json({ error: "id and status are required" }, { status: 400 });
  const [thread] = await getDb().update(inquiries).set({ status: payload.status, updatedAt: new Date().toISOString() }).where(eq(inquiries.id, payload.id)).returning();
  return thread ? Response.json({ thread }) : Response.json({ error: "Conversation not found" }, { status: 404 });
}

export async function POST(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  const payload = await request.json() as { id?: string; body?: string; managerEmail?: string };
  if (!payload.id || !payload.body?.trim() || !payload.managerEmail) return Response.json({ error: "id, body and managerEmail are required" }, { status: 400 });
  const db = getDb();
  const [thread] = await db.select().from(inquiries).where(eq(inquiries.id, payload.id));
  if (!thread) return Response.json({ error: "Conversation not found" }, { status: 404 });
  const [message] = await db.insert(inquiryMessages).values({ id: crypto.randomUUID(), inquiryId: payload.id, authorType: "manager", body: payload.body.trim(), managerEmail: payload.managerEmail }).returning();
  await db.update(inquiries).set({ status: "awaiting-customer", latestMessage: payload.body.trim(), updatedAt: new Date().toISOString() }).where(eq(inquiries.id, payload.id));
  return Response.json({ message, emailStatus: "not-configured" }, { status: 201 });
}
