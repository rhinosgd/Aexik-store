# MVP 验收矩阵

## 结构

- [ ] 首页公告、锚点导航、Hero、New Arrivals、五列产品格式、五列 Gaga、Race Moments 文章、FAQ、Contact、订阅与页脚齐全。
- [ ] Shop、Product、Race Moments、标准页面、404、Cart、Checkout、My Account 可访问。
- [ ] 桌垫、T 恤、贴纸、钥匙扣 URL 与分类命名一致。
- [ ] Curated Finds 不混入主赛车 SEO 分类。

## 交互

- [ ] 首页锚点和移动菜单支持键盘、Esc 与焦点返回。
- [ ] 购物车抽屉可打开、关闭并更新数量。
- [ ] Hero 可暂停、切换，并遵守 `prefers-reduced-motion`。
- [ ] 分类标签、商品规格、数量和加购状态清晰。
- [ ] Newsletter 有无效和成功反馈。

## WooCommerce / Printful

- [ ] Product / Variation 是 SKU、价格、库存唯一事实源。
- [ ] Style 与 Size 均为商品变量；桌垫 Size 包含 600×300 与 800×300 mm，且不是重复 SEO 页面。
- [ ] 变体 SKU 与 Printful 映射逐个验证。
- [ ] Processing 订单可进入 Printful，测试阶段不自动生产。
- [ ] 目标国家供货、运费、跟踪、退货和缺货同步通过。
- [ ] Cart / Checkout Blocks 与 HPOS 通过。

## 多语言 / 市场

- [ ] 默认英文与一个测试语言的商品、属性、邮件、结账同步。
- [ ] canonical、hreflang、x-default 和 sitemap 正确。
- [ ] 货币、税、配送、关税不由语言目录错误推断。
- [ ] EU/UK 撤回权、隐私与 Cookie 文案由专业人员复核。

## SEO / 性能

- [ ] Product 与 Breadcrumb Schema 只有一套输出。
- [ ] Race Moment Article Schema 通过验证。
- [ ] 占位内容、测试语言和筛选参数页 noindex。
- [ ] Hero 首图不懒加载，其余图片延迟加载。
- [ ] 首页移动端 LCP、CLS 和 INP 达标。

## 无障碍

- [ ] WCAG 2.2 AA 对比度。
- [ ] 跳转主内容、可见焦点、语义标题层级。
- [ ] 菜单、轮播、筛选、规格和表单可仅用键盘完成。
- [ ] 动态状态有 `aria-live` 或等价反馈。
- [ ] 200% 缩放和 320px 宽度无横向溢出。

## 内容与授权

- [ ] 每个 Race Moment 有 source title、URL、timestamp/lap reference。
- [ ] 每个发布条目为 `rights-approved` 或 `ready`。
- [ ] 正式图片、文案、价格、促销与配送承诺替换完毕。
- [ ] Webfont、照片、车队/车手/赛事元素的使用范围分别确认。
