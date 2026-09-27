# LSKY-Pro-Frontend 主题说明（liushen 风格改造）

本仓库在原 `willow-god/LSKY-Pro-LiuShen` v3.0.1 基础上做了一次**视觉层收敛**：参考
<https://www.liushen.fun/> 的「中性灰 + 单一琥珀强调」体系，移除堆叠的渐变/流光/多色光斑，
并把暗色下会失真的硬编码色值统一收进 CSS 令牌。

改造只涉及样式与模板 class/style，**未改动后端 PHP、JS 逻辑、Docker/CI 配置**。

---

## 1. 设计令牌（`resources/css/common.less`）

### 浅色 `:root`

| 变量 | 值 | 对比度（vs `--card-bg` #ffffff） |
|---|---|---|
| `--text-primary` | `#171717` | 17.93:1 |
| `--text-secondary` | `#525252` | 7.81:1 |
| `--text-muted` | `#737373` | 4.74:1 |
| `--primary` | `#b45309` | 5.02:1 |
| `--primary-hover` | `#92400e` | 7.09:1 |
| `--accent` | `#0369a1` | 5.93:1 |
| `--success` | `#15803d` | 5.02:1 |
| `--warning` | `#c2410c` | 5.18:1 |
| `--danger` | `#b91c1c` | 6.47:1 |
| `--info` | `#1d4ed8` | 6.70:1 |
| `--content-bg` | `#fafafa` | — |
| `--content-surface` | `#f5f5f5` | — |
| `--card-bg` / `--panel-bg-strong` | `#ffffff` | — |
| `--border-color` / `--border-strong` | `#e5e5e5` / `#d4d4d4` | — |

### 深色 `html.dark`

| 变量 | 值 | 对比度（vs `--card-bg` #171717） |
|---|---|---|
| `--text-primary` | `#fafafa` | 17.18:1 |
| `--text-secondary` | `#d4d4d4` | 12.09:1 |
| `--text-muted` | `#a3a3a3` | 7.11:1 |
| `--primary` | `#fbbf24` | 10.74:1 |
| `--primary-hover` | `#fcd34d` | 12.43:1 |
| `--accent` | `#7dd3fc` | 10.75:1 |
| `--success` | `#4ade80` | 10.29:1 |
| `--warning` | `#fb923c` | 7.92:1 |
| `--danger` | `#f87171` | 6.48:1 |
| `--info` | `#60a5fa` | 7.05:1 |
| `--content-bg` / `--content-surface` | `#0a0a0a` / `#111111` | — |
| `--card-bg` | `#171717` | — |
| `--border-color` / `--border-strong` | `#303030` / `#404040` | — |

> **最低正文对比度：浅色 4.74:1，深色 6.48:1**（WCAG AA 正文阈值 4.5:1）。

### 新增的成对令牌（解决「深色下按钮文字看不清」）

| 变量 | 浅色 | 深色 | 说明 |
|---|---|---|---|
| `--btn-bg` | `linear-gradient(135deg,#b45309,#92400e)` | `linear-gradient(135deg,#fbbf24,#f59e0b)` | 主按钮底色 |
| `--btn-fg` | `#ffffff` | `#1c1917` | 主按钮文字色 —— **深色下琥珀底必须配近黑字** |
| `--on-primary` | `#ffffff` | `#1c1917` | 主色块上的前景色 |
| `--focus-ring` | `rgba(180,83,9,.30)` | `rgba(251,191,36,.35)` | 焦点环 / ring |
| `--grid-dot` | `rgba(23,23,23,.08)` | `rgba(255,255,255,.07)` | 点阵装饰 |
| `--overlay` | `rgba(23,23,23,.45)` | `rgba(0,0,0,.6)` | 抽屉 / 弹窗遮罩 |
| `--panel-dark*` | 固定暖黑（不随模式切换） | 同左 | 登录页左侧品牌面板 |

只要元素用了 `background: var(--btn-bg)`，其自身与内部 `i` / `svg` / `.text-white` 的颜色会被
自动设为 `--btn-fg`（见 common.less 里的 `[style*="var(--btn-bg)"]` 规则），无需逐处写 color。

---

## 2. 收敛掉的「AI 味」

| 原实现 | 现状 |
|---|---|
| `.btn-gradient::after` shimmer 流光横扫 | 删除，改实色按钮 |
| `@keyframes shimmer` + `.progress-shimmer` 流光进度条 | 删除（类已无引用） |
| `@keyframes float` + `.animate-float` 浮动装饰 | 删除 |
| `@keyframes pulse-glow` 靛蓝脉冲光晕 | 删除 |
| `.glass-dark` 靛蓝玻璃拟态 | 删除 |
| `.gradient-text` 翠绿渐变文字 | 改实色 `--text-primary` + 展示字体 |
| 欢迎页四角多色光晕 + feTurbulence 噪点纹理 + 发光圆球 | 只留一层点阵网格 + 全局双径向底色 |
| 登录页左侧 4 段渐变（深绿→翡翠→青） + 3 个浮动玻璃方块 | 暖黑面板 + 单点琥珀光晕 + 点阵 |
| `::-webkit-scrollbar-thumb` 翠绿渐变 | 中性灰 |
| `table thead` 翠绿渐变底 | 纯色 `--code-bg` |
| 卡片 hover 抬升 + 彩色双层投影 | 只换边框色 + 中性阴影 |
| `input:focus { transform: scale(1.002) }` | 删除 |
| `.theme-fab:hover { translateY(-2px) scale(1.02) }` | 只留边框/文字色变化 |
| `animation: fadeInLeft 0.5s` 逐条错峰入场 | 删除 |
| 分页按钮靛蓝 `#4f46e5 / #7c3aed` | 琥珀令牌 |
| `.admin-section` / `.shadow-custom` 翠绿投影 | 中性阴影 |

---

## 3. 暗色可读性补全 + 浅色对比度修复

原 `html.dark` 重映射表只覆盖 24 个 Tailwind 固定色阶类，实际模板里用到了 88 个。
这次把缺口补齐，并统一了取值口径：

- 中性：`text-slate-400 → --text-secondary`、`text-slate-300 → #b8b8b8`、
  `bg-slate-50/100/gray-* → rgba(255,255,255,.03~.06)`、`border-slate-300/700/800 → --border-*`、
  `placeholder-slate-400 → #7a7a7a`
- 品牌（**两种模式都统一到琥珀**，无需逐页改）：
  `.text-emerald-*`、`[class*="hover:text-emerald-"]:hover` → `--primary`
  `.bg-emerald-50*`、`[class*="hover:bg-emerald-"]:hover` → `--primary-soft`
  `.border-emerald-*` → `--primary` / `--primary-soft`
  `[class*="ring-emerald-"]`、`[class*="focus:ring-emerald-"]:focus` → `--focus-ring`
- `text-sky/blue/teal-*` → `--accent`；`bg-blue-500` → `--primary`；`bg-yellow-500` → `--warning`
- 语义色**保留色相只调明度**：`text-green-500`（成功对勾）、`bg-emerald-400/500`（状态圆点）不动，
  避免"正常/冻结"这类状态点被改成琥珀后丢失语义

### 浅色模式漏网：`text-slate-400`

原来的重映射**只补了暗色侧**，浅色侧完全依赖 Tailwind 原值，导致一个真实缺陷：

| 类 | 使用处数 | 浅色原值 | 落在 `#fafafa`/`#ffffff` 上的对比度 | 结论 |
|---|---|---|---|---|
| `text-gray-500` | 110 | `#6b7280` | 4.64:1 / 4.84:1 | 通过 |
| `text-slate-500` | 62 | `#64748b` | 4.56:1 / 4.74:1 | 擦边通过 |
| **`text-slate-400`** | **44** | `#94a3b8` | **2.44:1 / 2.55:1** | **不达标，已修** |
| `text-slate-300` / `text-gray-300` | 9 / 1 | `#cbd5e1` | 1.41:1 | 不修 |

后两者不用修，因为 `text-slate-300` 全部只出现在 `dark:` 变体里（`dark:text-slate-300`，
编译成 `.dark\:text-slate-300`，与本重映射表互不干涉），`text-gray-300` 只用于
`default-avatar` 的装饰性头像剪影。

`text-slate-400` 是页脚文字和登录/注册表单的 input 前置图标（信封、锁），
2.44:1 连 WCAG 非文本 3:1 都不到。修法是在 `common.less` 里加一条**不带 `html.dark` 前缀**的规则：

```less
.text-slate-400 { color: var(--text-muted) !important; }
```

特异性 `0,1,0` 低于 `html.dark .text-slate-400` 的 `0,2,0`，所以暗色侧仍走 `--text-secondary`
（`#d4d4d4`），两侧不会互相打架。改完实测：浅色 4.74:1、暗色 13.6:1。

> **注意加载顺序**：`layouts/app.blade.php:37-38` 是 `common.css` **先**、`app.css` **后**，
> 所以浅色侧覆盖必须带 `!important`，否则会被 app.css 里 Tailwind 生成的
> `.text-slate-400{color:rgb(148 163 184/…)}` 盖掉。


模板内联 `style="...#hex..."` 的硬编码也已清空，改为引用令牌；仅剩 `rgba(0,0,0,...)` 这类中性阴影与
`welcome.blade.php` 里 CSS mask 用的 `rgba(0,0,0,.25/.75)`（mask 透明度，非视觉色）。

---

## 4. 字体

- 在 `layouts/app.blade.php` 与 `layouts/guest.blade.php` 的 `<head>` 加入
  `https://jsd.liiiu.cn/gh/willow-god/Sharding-fonts/ZhuqueFangsong-Regular/result.min.css`
  （与安装向导页引入字体同一个 CDN、同一作者仓库，项目本就依赖该域名）。
- **标题 / 品牌区用朱雀仿宋**（`h1, h2, .page-title, .display-font, .gradient-text` → `--font-display`），
  表格 / 表单 / 按钮 / 正文保持系统无衬线栈（`--font-body`），保证后台数据界面可读性。
- 两个布局里原有的 `<style>body{font-family:'Inter',...}</style>` 已改为
  `body { font-family: var(--font-body); }`。
- **一键回退衬线**：把 `--font-display` 改成 `var(--font-body)` 即可全站回到无衬线。

---

## 5. 构建与缓存

`public/css/*.css` 是**提交进 git 的编译产物**，且线上 Docker 构建不跑 npm，所以**改样式后必须重新编译并提交产物**：

```bash
npm ci
npm run production        # 输出 public/css/{app,common,gallery}.css 等
```

两个布局的缓存串已从 `?t=20260302` bump 到 `?t=20260927`（`css/app.css`、`css/common.css`、`js/app.js`）；
下次再改样式记得同步 bump，否则用户会命中旧缓存。

> 构建环境提示：本机沙箱会拦截 `mix` 收尾阶段的批量 copy（`SAFE_DELETE_BULK_CONFIRM_REQUIRED`），
> 报错出现在 `public/js/**`、`public/webfonts/**` 的拷贝步骤，但 `public/css/*.css` **在此之前已经写出**，
> 产物有效。构建后如发现 `public/js/**`、`public/css/markdown-css/**` 等出现"改动"，
> 那只是 CRLF/LF 行尾 churn，可用 `git checkout -- public/js public/css/fontawesome.css public/css/viewer-js public/css/markdown-css public/css/justified-gallery` 还原。

---

## 6. 本次改动的文件

**样式源**
- `resources/css/common.less` —— 令牌表重写、去 AI 味、品牌色统一、暗色重映射补齐
- `resources/css/app.css` —— 分页琥珀化、`shadow-custom` / `admin-section` 中性化

**布局与组件**
- `resources/views/layouts/app.blade.php`、`layouts/guest.blade.php` —— 字体 link、body 字体变量、缓存串
- `resources/views/components/button.blade.php` —— 内联翠绿渐变 → `var(--btn-*)`
- `resources/views/components/box.blade.php` —— 标题条 → `var(--primary)`
- `resources/views/components/auth-card.blade.php` —— 登录页左panel 重做
- `resources/views/components/{modal,no-data,upload}.blade.php`

**页面**
- `admin/console/index.blade.php`（4 张统计卡改中性底 + 琥珀图标，删掉整段 nth-child 暗色覆盖）
- `user/dashboard.blade.php`（同上 + 容量条改实色）
- `welcome.blade.php`（背景系统重写）
- `user/settings.blade.php`、`user/images.blade.php`
- `admin/{group,image,strategy,user,setting}/index.blade.php`
- `auth/{login,register,forgot-password,reset-password}.blade.php`
- `common/tokens.blade.php`、`layouts/sidebar.blade.php`
- `layouts/app.blade.php`、`layouts/guest.blade.php`（字体引入 + `?t=20260927-2` 缓存戳）

**署名**
- `welcome.blade.php:155-157`、`components/auth-card.blade.php:59-61` —— 页脚在
  `初始项目: 兰空图床 | UI设计: 清羽飞扬` 之后补上 `二改者: kemiao`
  （链到 <https://github.com/kemiao-moretti>）。
  **前两段是上游 GPL v3 要求保留的版权署名，不要删。**

**编译产物**
- `public/css/common.css`、`public/css/app.css`

---

## 7. 预览

仓库外的 `../theme-preview.html` 可直接双击打开（引用本仓库的 `public/css/*.css` + FontAwesome），
内含色板、统计卡、按钮/徽章、表格、容量条、表单、上传区、Toast、弹窗、空状态、分页、文本层级，
以及**页脚署名**（同时展示 welcome 版 `text-slate-400` 与 auth-card 版
`text-[var(--text-muted)]` 两种写法），右上角按钮切换明暗模式 —— 无需 Docker 即可比对效果。
