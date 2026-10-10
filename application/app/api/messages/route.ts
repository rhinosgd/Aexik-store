import { getDb } from "../../../db";
import { inquiries, inquiryMessages } from "../../../db/schema";

export async function POST(request: Request) {
  const payload = await request.json() as { name?: string; email?: string; subject?: string; message?: string };
  if (!payload.name?.trim() || !payload.email?.trim() || !payload.message?.trim()) return Response.json({ error: "name, email and message are required" }, { status: 400 });
  const db = getDb(); const id = crypto.randomUUID(); const body = payload.message.trim();
  const [thread] = await db.insert(inquiries).values({ id, name: payload.name.trim(), email: payload.email.trim(), subject: payload.subject?.trim() || "Product question", status: "new", latestMessage: body }).returning();
  await db.insert(inquiryMessages).values({ id: crypto.randomUUID(), inquiryId: id, authorType: "customer", body });
  return Response.json({ inquiry: thread }, { status: 201 });
}
