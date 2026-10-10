import { getDb } from "../../../../db";
import { productImages } from "../../../../db/schema";
import { imageRule, isAdmin } from "../../_lib/admin";
import { readImageDimensions } from "../../_lib/image-dimensions";
import { env } from "cloudflare:workers";

export async function POST(request: Request) {
  if (!isAdmin(request)) return Response.json({ error: "Admin authorization required" }, { status: 401 });
  if (request.headers.get("content-type")?.includes("multipart/form-data")) {
    const form = await request.formData();
    const file = form.get("file");
    const productId = String(form.get("productId") ?? "");
    const kind = String(form.get("kind") ?? "product");
    if (!(file instanceof File) || !productId || !file.type.startsWith("image/")) return Response.json({ error: "Choose a valid image file and product" }, { status: 400 });
    const body = await file.arrayBuffer();
    const dimensions = readImageDimensions(body);
    if (!dimensions || !imageRule(kind, dimensions.width, dimensions.height)) return Response.json({ error: "The uploaded file's actual pixels do not match this image type", required: { product: "1600x1600", moment: "1600x900", banner: "2200x800" } }, { status: 400 });
    const { width, height } = dimensions;
    const images = (env as unknown as { IMAGES?: R2Bucket }).IMAGES;
    if (!images) return Response.json({ error: "R2 binding IMAGES is unavailable" }, { status: 503 });
    const key = `products/${productId}/${crypto.randomUUID()}-${file.name.replace(/[^a-zA-Z0-9._-]/g, "-")}`;
    await images.put(key, body, { httpMetadata: { contentType: file.type || "application/octet-stream" } });
    const src = `/api/images/${key}`;
    const db = getDb();
    const [image] = await db.insert(productImages).values({ id: crypto.randomUUID(), productId, kind, sourceType: "local", src, alt: String(form.get("alt") ?? ""), width, height, sortOrder: Number(form.get("sortOrder") ?? 0) }).returning();
    return Response.json({ image, key }, { status: 201 });
  }
  const payload = await request.json() as { productId?: string; kind?: string; sourceType?: "local" | "https"; src?: string; alt?: string; width?: number; height?: number; sortOrder?: number };
  const width = Number(payload.width); const height = Number(payload.height); const kind = payload.kind ?? "product";
  if (!payload.productId || !payload.src || !payload.sourceType || !imageRule(kind, width, height)) return Response.json({ error: "Image dimensions or required fields are invalid", required: { product: "1600x1600", moment: "1600x900", banner: "2200x800" } }, { status: 400 });
  if (payload.sourceType === "https" && !/^https:\/\//i.test(payload.src)) return Response.json({ error: "External images must use HTTPS" }, { status: 400 });
  if (payload.sourceType === "local" && !payload.src.startsWith("/")) return Response.json({ error: "Local images must use an absolute site path" }, { status: 400 });
  try {
    const db = getDb();
    const [image] = await db.insert(productImages).values({ id: crypto.randomUUID(), productId: payload.productId, kind, sourceType: payload.sourceType, src: payload.src, alt: payload.alt ?? "", width, height, sortOrder: payload.sortOrder ?? 0 }).returning();
    return Response.json({ image }, { status: 201 });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Image record failed" }, { status: 503 });
  }
}
