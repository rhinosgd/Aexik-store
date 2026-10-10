import { env } from "cloudflare:workers";

export async function GET(_request: Request, context: { params: Promise<{ key: string[] }> }) {
  const { key } = await context.params;
  const bucket = (env as unknown as { IMAGES?: R2Bucket }).IMAGES;
  if (!bucket) return new Response("Image storage unavailable", { status: 503 });
  const object = await bucket.get(key.join("/"));
  if (!object) return new Response("Not found", { status: 404 });
  return new Response(object.body, { headers: { "content-type": object.httpMetadata?.contentType ?? "application/octet-stream", "cache-control": "public, max-age=31536000, immutable" } });
}
