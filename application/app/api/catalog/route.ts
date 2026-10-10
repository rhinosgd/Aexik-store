import { asc, eq } from "drizzle-orm";
import { getDb } from "../../../db";
import { categories, productImages, products, raceMoments, skus, variants } from "../../../db/schema";

export async function GET() {
  try {
    const db = getDb();
    const categoryRows = await db.select().from(categories).where(eq(categories.isPublished, true)).orderBy(asc(categories.sortOrder));
    const productRows = await db.select().from(products).where(eq(products.status, "published"));
    const imageRows = await db.select().from(productImages).orderBy(asc(productImages.sortOrder));
    const variantRows = await db.select().from(variants);
    const skuRows = await db.select().from(skus);
    const momentRows = await db.select().from(raceMoments).where(eq(raceMoments.status, "published")).orderBy(asc(raceMoments.sortOrder));
    return Response.json({
      categories: categoryRows,
      raceMoments: momentRows,
      products: productRows.map((product) => ({
        ...product,
        images: imageRows.filter((image) => image.productId === product.id),
        variants: variantRows.filter((variant) => variant.productId === product.id).map((variant) => ({ ...variant, sku: skuRows.find((sku) => sku.variantId === variant.id) ?? null })),
      })),
    }, { headers: { "cache-control": "public, max-age=60" } });
  } catch (error) {
    return Response.json({ error: error instanceof Error ? error.message : "Catalog unavailable" }, { status: 503 });
  }
}
