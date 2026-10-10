import { sql } from "drizzle-orm";
import { integer, real, sqliteTable, text, uniqueIndex } from "drizzle-orm/sqlite-core";

const timestamps = {
  createdAt: text("created_at").notNull().default(sql`CURRENT_TIMESTAMP`),
  updatedAt: text("updated_at").notNull().default(sql`CURRENT_TIMESTAMP`),
};

export const adminUsers = sqliteTable("admin_users", {
  id: text("id").primaryKey(), email: text("email").notNull().unique(), name: text("name").notNull(),
  passwordHash: text("password_hash").notNull(), role: text("role").notNull().default("editor"), ...timestamps,
});
export const categories = sqliteTable("categories", {
  id: text("id").primaryKey(), slug: text("slug").notNull().unique(), name: text("name").notNull(),
  sortOrder: integer("sort_order").notNull().default(0), isPublished: integer("is_published", { mode: "boolean" }).notNull().default(true),
  seoTitle: text("seo_title"), seoDescription: text("seo_description"), ...timestamps,
});
export const products = sqliteTable("products", {
  id: text("id").primaryKey(), categoryId: text("category_id").notNull().references(() => categories.id), slug: text("slug").notNull().unique(),
  title: text("title").notNull(), description: text("description").notNull().default(""), story: text("story").notNull().default(""),
  price: real("price").notNull().default(39.99), status: text("status").notNull().default("draft"), seoTitle: text("seo_title"), seoDescription: text("seo_description"), ...timestamps,
});
export const variants = sqliteTable("variants", {
  id: text("id").primaryKey(), productId: text("product_id").notNull().references(() => products.id, { onDelete: "cascade" }),
  style: text("style").notNull(), size: text("size").notNull(), ...timestamps,
}, (table) => ({ productStyleSize: uniqueIndex("variants_product_style_size_idx").on(table.productId, table.style, table.size) }));
export const skus = sqliteTable("skus", {
  id: text("id").primaryKey(), variantId: text("variant_id").notNull().references(() => variants.id, { onDelete: "cascade" }),
  code: text("code").notNull().unique(), providerCode: text("provider_code"), ...timestamps,
});
export const inventory = sqliteTable("inventory", {
  id: text("id").primaryKey(), skuId: text("sku_id").notNull().references(() => skus.id, { onDelete: "cascade" }).unique(),
  available: integer("available").notNull().default(0), reserved: integer("reserved").notNull().default(0), reorderPoint: integer("reorder_point").notNull().default(0), ...timestamps,
});
export const productImages = sqliteTable("product_images", {
  id: text("id").primaryKey(), productId: text("product_id").notNull().references(() => products.id, { onDelete: "cascade" }),
  kind: text("kind").notNull().default("product"), sourceType: text("source_type").notNull(), src: text("src").notNull(),
  alt: text("alt").notNull().default(""), width: integer("width").notNull(), height: integer("height").notNull(), sortOrder: integer("sort_order").notNull().default(0), ...timestamps,
});
export const raceMoments = sqliteTable("race_moments", {
  id: text("id").primaryKey(), slug: text("slug").notNull().unique(), title: text("title").notNull(), summary: text("summary").notNull().default(""), body: text("body").notNull().default(""), sortOrder: integer("sort_order").notNull().default(0),
  sourceUrl: text("source_url"), rightsStatus: text("rights_status").notNull().default("draft"), status: text("status").notNull().default("draft"), ...timestamps,
});
export const orders = sqliteTable("orders", {
  id: text("id").primaryKey(), orderNumber: text("order_number").notNull().unique(), customerEmail: text("customer_email").notNull(), customerName: text("customer_name").notNull(),
  shippingAddress: text("shipping_address").notNull(), subtotal: real("subtotal").notNull(), shipping: real("shipping").notNull().default(0), total: real("total").notNull(), status: text("status").notNull().default("pending"), ...timestamps,
});
export const orderItems = sqliteTable("order_items", {
  id: text("id").primaryKey(), orderId: text("order_id").notNull().references(() => orders.id, { onDelete: "cascade" }), skuId: text("sku_id").references(() => skus.id),
  productTitle: text("product_title").notNull(), style: text("style").notNull(), size: text("size").notNull(), quantity: integer("quantity").notNull().default(1), unitPrice: real("unit_price").notNull(),
});
export const inquiries = sqliteTable("inquiries", {
  id: text("id").primaryKey(), name: text("name").notNull(), email: text("email").notNull(), subject: text("subject").notNull().default("Product question"),
  status: text("status").notNull().default("new"), latestMessage: text("latest_message").notNull().default(""), ...timestamps,
});
export const inquiryMessages = sqliteTable("inquiry_messages", {
  id: text("id").primaryKey(), inquiryId: text("inquiry_id").notNull().references(() => inquiries.id, { onDelete: "cascade" }), authorType: text("author_type").notNull().default("customer"),
  body: text("body").notNull(), managerEmail: text("manager_email"), ...timestamps,
});
export const schema = { adminUsers, categories, products, variants, skus, inventory, productImages, raceMoments, orders, orderItems, inquiries, inquiryMessages };
