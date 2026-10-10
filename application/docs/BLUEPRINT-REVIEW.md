# 新版蓝图核对与修正记录

源文件：`Automobilist结构参考-WP电商搭建蓝图-A1-A.pdf`（9 页）。

## 结论

保留蓝图的页面结构和运营意图，但不按原数据模型直接实现。原蓝图在 WordPress 模板层级、WooCommerce 商品字段、多语言、SEO 和 Printful 数据归属上存在会导致二次返工的问题。本项目已在代码层完成以下修正。

## 已修正的 P0 问题

### 1. 明确主题类型

采用 Hybrid Classic Theme：PHP 模板负责高定制结构，`theme.json` 负责设计令牌和编辑器样式。蓝图不再混称 Block Theme。

### 2. Race Moments 使用标准模板层级

- CPT：`apexik_moment`
- 归档：`archive-apexik_moment.php`
- 详情：`single-apexik_moment.php`
- 地址：`/race-moments/{slug}/`

删除无效的 `article-product.php` 方案。

### 3. 商品数据归 WooCommerce / Printful

- 类目：`product_cat`
- 尺寸、颜色、材质：WooCommerce 全局属性和变体
- SKU、价格、库存、订单：WooCommerce / Printful
- APEXIK 插件只保存故事说明、来源引用、生产说明和内容关系

这避免同一规格在 ACF 与 WooCommerce 中出现两套冲突数据。

### 4. Meta Keywords 改为结构化分类法

筛选使用：

- `apx_event`
- `apx_championship`
- `apx_story_type`
- `apx_team`
- `apx_driver`

SEO 标题、描述、canonical、hreflang 和 sitemap 交给 SEO/多语言插件，不使用 `meta_keywords`。

### 5. 随机商品改为确定性回退

首页优先级应为：人工精选 → 销量 → 新品。框架不使用每次请求 `ORDER BY RAND()`，避免破坏缓存和首页稳定性。

### 6. 多语言依赖明确

WooCommerce 多语言不能只装免费 Polylang。生产配置需使用 Polylang for WooCommerce；需要 URL slug 翻译时再增加 Polylang Pro。语言、货币、税和配送是四套不同配置。

### 7. WooCommerce 优先 hooks

主题不创建总入口 `woocommerce.php`，不大面积复制 WooCommerce 模板。商品档案页、商品详情故事区和容器通过 hooks 扩展，Cart/Checkout 保留官方 Blocks，以降低升级维护成本。

### 8. MVP 品类统一

首发核心导航以 Desk Mats、T-Shirts、Stickers、Keychains 四类为主；Curated Finds 保留为次级扩展位。蓝图中的 `Chain Key` 统一改为 `Keychains`。

## 结构保留项

- 公告条、吸顶导航、Shop 巨幕菜单
- 五个占位 Hero 故事位
- NEW ARRIVALS
- 按产品格式浏览
- 按故事类型浏览（Strategy / Radio / Team Story / Driver Talk）
- Race Moments 内容档案
- 内容预告、订阅、页脚
- 商品详情、集合筛选、购物车、结账、账户和支持页入口

## 后续生产决策

正式上线前仍需确认：

1. Printful 对 600×300 / 800×300 mm 桌垫、汽车贴纸和钥匙扣在各目标国家的真实供货与运费。
2. 结算基础货币、多币种显示/收款方案。
3. EU/UK/US/JP/AU/BR 税费、关税和退货政策。
4. Webfont 的网页使用许可；商品授权不自动等于字体许可。
5. 每个 Race Moment 的来源记录、引用范围和发布审批状态。
