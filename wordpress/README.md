# APEXIK WordPress 建站包

这是一套可安装的 WordPress + WooCommerce 商店首发版，主要面向欧美赛车无线电文化、车迷礼品和轻便观赛用品市场。首发的 7 款概念商品全部低于 50 美元。当前主题版本为 **1.7.0**。

## 包含内容

- `wp-content/themes/gridline-club/`：APEXIK 定制 WooCommerce 主题（保留稳定的内部代码路径）。
- `wp-content/plugins/gridline-starter/`：一键导入首页、菜单、政策页面、SEO、分类和 7 件 APEXIK 首发商品；Polo 与卫衣自动生成 XS–3XL 变体。
- `preview/`：不需 WordPress 即可查看的 APEXIK 静态首页、商店与产品页预览。
- `packages/`：生成的主题和插件 ZIP 安装包。

## 服务器环境

- WordPress 6.5 或更高版本
- PHP 8.3 或更高版本
- MySQL 8 / MariaDB 10.11 或更高版本
- HTTPS
- WooCommerce 最新稳定版
- 建议伪静态：`Post name`

## 安装顺序

1. 新服务器上安装 WordPress，在「设置 → 固定链接」选择 `Post name`。
2. 安装并启用 WooCommerce，完成它的基础建站向导。
3. 在「外观 → 主题 → 安装主题」上传 `packages/apexik-theme.zip`并启用。
4. 在「插件 → 安装插件」上传 `packages/apexik-starter-plugin.zip`并启用。
5. 进入「工具 → APEXIK Setup」，点击 **Install / refresh starter content**。
6. 在「外观 → 自定义 → APEXIK Brand」修改品牌色、公告和正式 Logo。
7. 邮件平台确定后，在同一位置填写 Newsletter form action URL；未填写时站点不会收集邮箱。

导入后可在「工具 → APEXIK Setup」查看上线准备度，包括 HTTPS、永久链接、真实联系邮箱、隐私页、商店地址、在线支付和物流方法。该页仅检查，不会自动修改商店设置。

导入器可以重复运行：相同 slug 的页面和商品会被更新，不会重复新建。

## 上线前必须替换

- WordPress「设置 → 常规」中的管理员/客服邮箱。
- 首页示例用户评论，未经真实客户授权不得上线。
- 运费、时效、退货和免邮门槛。
- 付款、税务、币种、库存、订单邮件、隐私、Cookie 同意和法律条款。
- 实际产品的尺寸、材料、交期、商品图和包装信息。
- 示例评分与评论数据不是真实销售证明，上线时应由 WooCommerce 真实评论代替。

## 当前网站完成范围

- 首页、商店/分类、搜索、404、商品详情、购物车、结账、账户与通用内容页面样式。
- 7 件首发商品、8 个分类、Polo/卫衣尺码变体、产品故事字段与基础 SEO。
- About、FAQ、Contact、Shipping & Returns、Privacy、Terms、Cookie、Size Guide 页面。
- 搜索抽屉、移动导航、产品 FAQ、购物车数量刷新和无障碍键盘状态。
- 首页、商店和分类商品卡片的 Radio Archive 悬停层：显示对话上下文、突出商品印制句，并播放传播式声波动画；商品详情页结构保持不变。
- 网站结构化数据、产品 Open Graph 和不安装 SEO 插件时的 Meta description 回退。

本阶段不包含真实支付、真实运费、税务、邮件营销或模拟订单；这些需在域名和服务器确定后连接正式服务。

## 知识产权边界

对话原话按委托方确认的授权范围使用。站点和商品不使用 F1、车队、车手全名、赞助商、官方Logo、完整涂装或转播截图。详细规范见 `APEXIK-BRAND-GUIDE.md`。

## 后续域名/服务器阶段

拿到域名与服务器后，再完成 DNS、SSL、CDN/缓存、邮件发送、备份、安全、支付、运费、税务、分析和上线回归测试。
