"use client";

import { ChangeEvent, useMemo, useState } from "react";

type Product = { id: string; slug: string; title: string; description: string; story: string; price: number; status: string; image: string; categoryId: string; category: string; styles: string; sizes: string; stock: number };

const demoProducts: Product[] = [
  { id: "prod-undercut", slug: "undercut-window-desk-mat", title: "The Undercut Window Desk Mat", description: "Telemetry, timing and the decision that changed the final lap.", story: "A safety-car restart turned a measured gap into one of the most debated final laps in modern racing.", price: 39.99, status: "published", image: "/products/undercut-desk-mat.png", categoryId: "desk-mats", category: "Desk Mats", styles: "Style A|Style B", sizes: "600 × 300 mm", stock: 48 },
  { id: "prod-radio-wave", slug: "radio-wave-key-chain", title: "Radio Wave Steering Key Chain", description: "A radio message translated into a waveform object.", story: "When a radio message became the story, the object became a waveform.", price: 19.99, status: "draft", image: "/products/radio-wave-keychain.png", categoryId: "key-chains", category: "Key Chains", styles: "Style A", sizes: "One Size", stock: 35 },
];

const columns = ["slug", "title", "categoryId", "price", "status", "story", "styles", "sizes", "stock", "imageUrl"];

export default function AdminCatalogPage() {
  const [products, setProducts] = useState(demoProducts);
  const [selectedId, setSelectedId] = useState(demoProducts[0].id);
  const [key, setKey] = useState("");
  const [message, setMessage] = useState("可编辑单个商品，也可使用 CSV 模板批量上架。");
  const [query, setQuery] = useState("");
  const [batch, setBatch] = useState<Product[]>([]);
  const selected = products.find((item) => item.id === selectedId) ?? products[0];
  const filtered = useMemo(() => products.filter((item) => `${item.title} ${item.category}`.toLowerCase().includes(query.toLowerCase())), [products, query]);

  function update(field: keyof Product, value: string | number) {
    setProducts((items) => items.map((item) => item.id === selected.id ? { ...item, [field]: field === "price" || field === "stock" ? Number(value) : value } : item));
  }

  async function save() {
    const response = await fetch("/api/admin/products", { method: "PATCH", headers: { "content-type": "application/json", "x-apexik-admin-key": key }, body: JSON.stringify({ id: selected.id, title: selected.title, price: selected.price, status: selected.status, story: selected.story }) });
    setMessage(response.ok ? "商品已保存并同步。" : "保存失败：请检查后台密钥和数据库连接。");
  }

  function downloadTemplate() {
    const sample = ["story-desk-mat", "Story Desk Mat", "desk-mats", "39.99", "draft", "Race story", "Style A|Style B", "600 × 300 mm|800 × 300 mm", "20", "https://example.com/image.jpg"];
    const blob = new Blob([`${columns.join(",")}\n${sample.map(csvCell).join(",")}\n`], { type: "text/csv;charset=utf-8" });
    const link = document.createElement("a"); link.href = URL.createObjectURL(blob); link.download = "apexik-product-template.csv"; link.click(); URL.revokeObjectURL(link.href);
  }

  async function importCsv(event: ChangeEvent<HTMLInputElement>) {
    const file = event.target.files?.[0]; if (!file) return;
    const rows = parseCsv(await file.text());
    const header = rows.shift()?.map((cell) => cell.trim()) ?? [];
    if (!columns.every((column) => header.includes(column))) { setMessage("CSV 字段不完整，请先下载最新模板。"); return; }
    const parsed = rows.filter((row) => row.some(Boolean)).map((row, index) => {
      const item = Object.fromEntries(header.map((name, i) => [name, row[i] ?? ""]));
      return { id: `batch-${index}`, slug: item.slug, title: item.title, categoryId: item.categoryId, category: item.categoryId, price: Number(item.price) || 0, status: item.status || "draft", story: item.story, description: "", styles: item.styles || "Style A", sizes: item.sizes || "One Size", stock: Number(item.stock) || 0, image: item.imageUrl } as Product;
    });
    setBatch(parsed); setMessage(`已读取 ${parsed.length} 个商品，确认后可批量上架。`);
  }

  async function publishBatch() {
    if (!batch.length) return;
    const response = await fetch("/api/admin/products", { method: "POST", headers: { "content-type": "application/json", "x-apexik-admin-key": key }, body: JSON.stringify({ items: batch.map((item) => ({ ...item, imageUrl: item.image })) }) });
    if (response.ok) { setProducts((current) => [...batch, ...current]); setBatch([]); setMessage("批量商品已写入数据库。"); }
    else setMessage("批量上架失败：请检查类目 ID、后台密钥和数据库连接。");
  }

  return <main className="admin-page catalog-workspace"><div className="catalog-heading"><div><p className="eyebrow">APEXIK / PRODUCT CATALOG</p><h1>商品管理</h1><p className="admin-intro">维护商品、变体、库存、主图和前台发布状态。</p></div><button className="admin-export" onClick={downloadTemplate}>下载 CSV 模板</button></div><div className="catalog-toolbar"><label>后台密钥<input type="password" value={key} onChange={(event) => setKey(event.target.value)} placeholder="APEXIK_ADMIN_KEY" /></label><span>{message}</span></div><section className="batch-card"><div><h2>批量上架</h2><p>按模板填写商品。Style、Size 用竖线 | 分隔，系统自动生成变体与 SKU。</p></div><label className="batch-upload">选择 CSV<input type="file" accept=".csv,text/csv" onChange={(event) => void importCsv(event)} /></label><button className="admin-export" disabled={!batch.length} onClick={() => void publishBatch()}>批量上架 {batch.length ? `(${batch.length})` : ""}</button></section>{batch.length > 0 && <div className="batch-preview">{batch.slice(0, 8).map((item) => <span key={item.id}><b>{item.title}</b><small>{item.categoryId} · {item.status} · ${item.price.toFixed(2)}</small></span>)}</div>}<div className="catalog-split"><section className="catalog-list-panel"><div className="catalog-panel-head"><div><h2>商品列表</h2><p>{products.length} 个商品</p></div></div><input className="catalog-search" value={query} onChange={(event) => setQuery(event.target.value)} placeholder="搜索商品或类目" /><div className="catalog-products">{filtered.map((product) => <button className={`catalog-product ${selected.id === product.id ? "active" : ""}`} key={product.id} onClick={() => setSelectedId(product.id)}><img src={product.image} alt=""/><span><b>{product.title}</b><small>{product.category} · {product.status}</small></span><strong>${product.price.toFixed(2)}</strong></button>)}</div></section><section className="catalog-detail-panel"><div className="catalog-detail-head"><div><p className="eyebrow">PRODUCT DETAIL</p><h2>商品详情</h2><p>单个编辑商品内容、价格、库存和前台状态。</p></div><label className="catalog-publish"><input type="checkbox" checked={selected.status === "published"} onChange={(event) => update("status", event.target.checked ? "published" : "draft")} /> 前台显示</label></div><div className="catalog-edit-grid"><Field label="英文名称" value={selected.title} onChange={(value) => update("title", value)}/><Field label="类目 ID" value={selected.categoryId} onChange={(value) => update("categoryId", value)}/><Field label="英文简介" value={selected.description} onChange={(value) => update("description", value)} textarea/><Field label="产品故事 / Race Moment" value={selected.story} onChange={(value) => update("story", value)} textarea/><Field label="Style（| 分隔）" value={selected.styles} onChange={(value) => update("styles", value)}/><Field label="Size（| 分隔）" value={selected.sizes} onChange={(value) => update("sizes", value)}/><label className="catalog-field">价格（USD）<input type="number" step="0.01" value={selected.price} onChange={(event) => update("price", event.target.value)} /></label><label className="catalog-field">可用库存<input type="number" value={selected.stock} onChange={(event) => update("stock", event.target.value)} /></label></div><div className="catalog-image-box"><h3>商品图片</h3><div className="catalog-image-preview"><img src={selected.image} alt={selected.title}/><div><b>当前主图</b><p>统一规格 1600 × 1600，支持本地媒体库与 HTTPS URL。</p></div></div></div><div className="catalog-actions"><button className="add-button" onClick={() => void save()}>保存并发布 <span>→</span></button><button className="catalog-danger" onClick={() => update("status", "archived")}>归档商品</button></div></section></div></main>;
}

function Field({ label, value, onChange, textarea }: { label: string; value: string; onChange: (value: string) => void; textarea?: boolean }) { return <label className="catalog-field">{label}{textarea ? <textarea value={value} onChange={(event) => onChange(event.target.value)} /> : <input value={value} onChange={(event) => onChange(event.target.value)} />}</label>; }
function csvCell(value: string) { return `"${value.replaceAll('"', '""')}"`; }
function parseCsv(text: string) { const rows: string[][] = []; let row: string[] = [], cell = "", quoted = false; for (let i = 0; i < text.length; i++) { const char = text[i]; if (char === '"' && quoted && text[i + 1] === '"') { cell += '"'; i++; } else if (char === '"') quoted = !quoted; else if (char === "," && !quoted) { row.push(cell); cell = ""; } else if ((char === "\n" || char === "\r") && !quoted) { if (char === "\r" && text[i + 1] === "\n") i++; row.push(cell); rows.push(row); row = []; cell = ""; } else cell += char; } if (cell || row.length) { row.push(cell); rows.push(row); } return rows; }
