# 安装与部署

## 1. 环境

建议：WordPress 6.6+、PHP 8.1+、HTTPS、WooCommerce 当前稳定版。开启固定链接和 HPOS，并使用 staging 站先验收。

## 2. 安装顺序

1. 安装并激活 WooCommerce。
2. 上传并激活 `apexik-story-engine.zip`。
3. 上传并激活 `apexik-automobilist-theme.zip`。
4. 进入 `Settings → Permalinks` 保存一次。
5. 进入 `Tools → APEXIK Setup`，显式创建占位结构。
6. 将首页设为静态页面；将 Shop、Cart、Checkout、My Account 页面指派给 WooCommerce。

占位商品默认是 Draft，并带 `_apx_is_placeholder=1`。Story Engine 同时阻止其发布、前台加购、WooCommerce REST 集合返回和 REST 单品读取；Printful 使用 WooCommerce API 同步时不会取得这些记录。替换完正式内容、变体与授权资料后，才可人工移除该标记。

验证同步保护：以管理员 API 凭据请求 `/wp-json/wc/v3/products`，确认返回中不存在 `_apx_is_placeholder=1` 的商品；直接请求对应 `/products/{id}` 应返回 `404 apx_placeholder_product`。

## 3. WooCommerce 产品模型

- 每个“故事 × 产品格式”为一个 canonical Product URL。
- 桌垫的 600×300 和 800×300 mm 为同一商品的 `pa_size` 变体。
- T 恤尺码/颜色为全局属性和变体。
- 汽车贴纸的尺寸/表面处理若改变 SKU 或价格，也必须使用变体。
- Keychain 如由非 Printful 供应商履约，使用独立 shipping class 和 fulfillment note。

## 4. Printful

1. 安装 Printful 官方 WooCommerce 集成。
2. 先在 Printful 建立产品模板，再发布/同步到 WooCommerce。
3. 将同步后的产品关联到对应 Race Moment 和 Story Type。
4. 不改写 Printful 创建的 mapping meta。
5. 测试订单保持手动确认，直到运费、税、付款和目标国家履约全部通过。

## 5. 多语言与市场

1. 安装 Polylang 与 Polylang for WooCommerce。
2. MVP 先用英文默认语言 + 一个测试语言。
3. 在翻译产品前，先翻译全局属性和属性项。
4. 每个语言版本需有 canonical、hreflang 和独立 sitemap。
5. 市场货币、税率、配送区域和关税提示单独配置，不能依赖语言目录推断。

## 6. 性能

- Hero 首图使用响应式 AVIF/WebP，不懒加载；其余轮播图延迟加载。
- 商品图统一比例并生成 WordPress 响应式尺寸。
- LiteSpeed 服务器只使用 LiteSpeed Cache；其他主机使用其原生页面缓存或一个缓存插件。
- Checkout、Cart、My Account、Woo fragments 与多语言 Cookie 必须排除全页缓存。
- 上线前检查首页 LCP、CLS、键盘导航和减少动态效果模式。

## 7. 内容上线门槛

只有 `rights-approved` 或 `ready` 的 Race Moment 才允许发布。所有占位价格、促销、配送承诺和赛事文案必须在正式索引前替换。
