<?php
/**
 * ХЕДЕР chernetchenko.pro v7.0 — OVC Design System
 * Фаза 1–2 миграции: CSS/JS из ui-kit, структура site-header OVC
 */
$sites = [
    'main' => ['id'=>'main', 'page_title'=>'Олег Чернетченко', 'url'=>'https://ovc.me',     'color'=>'var(--c-main)', 'cmd'=>'~/'],
    'waf'  => ['id'=>'waf',  'page_title'=>'Прикл. ИИ',       'url'=>'https://ai.ovc.me',  'color'=>'var(--c-waf)',  'cmd'=>'cd ai'],
    'rag'  => ['id'=>'rag',  'page_title'=>'RAG',              'url'=>'https://rag.ovc.me', 'color'=>'var(--c-rag)',  'cmd'=>'cd rag'],
    'bim'  => ['id'=>'bim',  'page_title'=>'BIM / ТИМ',          'url'=>'https://bim.ovc.me', 'color'=>'var(--c-bim)',  'cmd'=>'cd bim'],
    'toc'  => ['id'=>'toc',  'page_title'=>'Сделано',          'url'=>'https://toc.ovc.me', 'color'=>'var(--c-toc)',  'cmd'=>'cd toc'],
    'fun'  => ['id'=>'fun',  'page_title'=>'Лаба',              'url'=>'https://fun.ovc.me', 'color'=>'var(--c-fun)',  'cmd'=>'cd fun'],
];
$siteId  = $siteId ?? 'main';
$current = $sites[$siteId] ?? $sites['main'];

// SEO Override Patch
$seoFile = __DIR__ . '/config/seo_overrides.json';
if (file_exists($seoFile)) {
    $ov = json_decode(file_get_contents($seoFile), true) ?: [];
    $curScript = basename($_SERVER['SCRIPT_NAME']);
    if (!empty($ov[$curScript]['title'])) {
        $pageTitle = $ov[$curScript]['title'];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? $current['page_title']) ?></title>
<link rel="icon" type="image/svg+xml" href="https://ovc.me/favicon.svg">

<!-- OVC Design System v3.0 -->
<link rel="stylesheet" href="/frontend/ui-kit/css/01_tokens.css">
<link rel="stylesheet" href="/frontend/ui-kit/css/02_base.css">
<link rel="stylesheet" href="/frontend/ui-kit/css/03_components.css">

<!-- Yandex.Metrika -->
<script type="text/javascript">
(function(m,e,t,r,i,k,a){
m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
m[i].l=1*new Date();
for(var j=0;j<document.scripts.length;j++){if(document.scripts[j].src===r){return;}}
k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
})(window,document,'script','https://mc.yandex.ru/metrika/tag.js?id=108508539','ym');
ym(108508539,'init',{ssr:true,webvisor:true,clickmap:true,ecommerce:"dataLayer",referrer:document.referrer,url:location.href,accurateTrackBounce:true,trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/108508539" style="position:absolute;left:-9999px;" alt=""/></div></noscript>
<!-- /Yandex.Metrika -->

<style>
/* ── Адаптер: сайт-переменные → OVC токены ──────────────────── */
:root {
  /* Текущий акцент сайта — используется в page-specific стилях */
  --accent: <?= htmlspecialchars($current['color'], ENT_QUOTES, 'UTF-8') ?>;
  /* Совместимость со старыми page-стилями */
  --bg:         var(--bg-main);
  --ink:        var(--text-main);
  --ink2:       var(--text-muted);
  --ink3:       var(--text-dim);
  --border-old: var(--border);
  --font-body:  var(--font-ui);
  --font-mono:  var(--font-code);
  --font-title: var(--font-ui);
  --container-w: 1200px;
  /* Цвета подсайтов для page-specific стилей */
  --c-waf-site: var(--c-waf);
  --c-toc-site: var(--c-toc);
  --c-fun-site: var(--c-fun);
}

/* ── Burger меню (мобильная навигация) ──────────────────────── */
.burger {
  display: none;
  flex-direction: column;
  gap: 5px;
  background: none;
  border: 1px solid var(--border);
  border-radius: var(--r-sm);
  cursor: pointer;
  padding: 8px 10px;
  transition: border-color var(--ease);
}
.burger:hover { border-color: var(--text-muted); }
.burger span {
  display: block;
  width: 20px;
  height: 2px;
  background: var(--text-muted);
  border-radius: 2px;
  transition: all .3s;
}
.burger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.burger.open span:nth-child(2) { opacity: 0; }
.burger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Сайт-навигация: цвет активного сайта ──────────────────── */
.site-header__link.active .site-header__link-cmd { opacity: 1; }
.site-header__link.active {
  border-color: var(--border);
  background: rgba(255,255,255,.06);
}

/* ── Telegram кнопки: сохраняем две иконки ─────────────────── */
.header-tg-group {
  display: flex;
  gap: 6px;
  align-items: center;
}
.site-header__tg-personal {
  display: flex;
  flex-direction: column;
  gap: 3px;
  text-decoration: none;
  padding: 6px 12px;
  border-radius: var(--r-sm);
  border: 1px solid rgba(36,161,222,.3);
  background: rgba(36,161,222,.05);
  transition: border-color var(--ease), background var(--ease);
}
.site-header__tg-personal:hover {
  border-color: var(--c-tg);
  background: rgba(36,161,222,.12);
}
.site-header__tg-personal .cmd {
  font-family: var(--font-code);
  font-size: .88rem;
  font-weight: bold;
  color: var(--c-tg);
  line-height: 1;
}
.site-header__tg-personal .lbl {
  font-size: .58rem;
  color: var(--text-dim);
  text-transform: uppercase;
  letter-spacing: .07em;
  line-height: 1;
}

/* ── Хедер: одна строка, без переноса ──────────────────────── */
.site-header__inner {
  flex-wrap: nowrap;
}
.site-header__logo  { flex-shrink: 0; }
.site-header__nav   { flex-shrink: 0; gap: 4px; }
.site-header__right { flex-shrink: 0; }

/* ── Мобильная навигация ────────────────────────────────────── */
@media (max-width: 1000px) {
  .burger { display: flex; }
  .site-header__nav {
    display: none;
    position: fixed;
    top: 52px;
    left: 0; right: 0;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border);
    flex-direction: column;
    padding: 12px 16px;
    gap: 6px;
    z-index: 199;
  }
  .site-header__nav.open { display: flex; }
  .site-header__nav .site-header__link {
    flex-direction: row;
    gap: 10px;
    align-items: center;
    padding: 10px 14px;
  }
  .site-header__nav .site-header__link-label { display: block; }
  .header-tg-group { display: none; }
  #mode-toggle .lbl,
  #theme-toggle .lbl { display: none; }
}
@media (max-width: 600px) {
  .site-header__inner { padding: 0 16px; }
}

/* ── Светлая тема — адаптер для сайт-переменных ─────────────── */
body.light-theme {
  --bg:   var(--bg-main);
  --ink:  var(--text-main);
  --ink2: var(--text-muted);
  --ink3: var(--text-dim);
}
</style>
</head>
<body>

<!-- ============================================================
     SITE HEADER (OVC Design System)
     ============================================================ -->
<header class="site-header">
  <div class="site-header__inner">

    <!-- Логотип / название сайта -->
    <a href="<?= htmlspecialchars($current['url'], ENT_QUOTES, 'UTF-8') ?>" class="site-header__logo">
      <?php if ($siteId === 'main'): ?>
        ovc.<span>me</span><span class="cli-only">>_</span>
      <?php else: ?>
        <span style="color:<?= htmlspecialchars($current['color'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($current['page_title'], ENT_QUOTES, 'UTF-8') ?></span><span class="cli-only">>_</span>
      <?php endif; ?>
    </a>

    <!-- Бургер (мобилка) -->
    <button class="burger" id="burger" aria-label="Меню"><span></span><span></span><span></span></button>

    <!-- Основная навигация по подсайтам -->
    <nav class="site-header__nav" id="site-nav">
      <a href="<?= htmlspecialchars($sites['main']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='main')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-main)">
          <span class="cli-only">~/</span><span class="mgmt-only">home</span>
        </span>
        <span class="site-header__link-label">Автор</span>
      </a>
      <a href="<?= htmlspecialchars($sites['waf']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='waf')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-waf)">
          <span class="cli-only">cd </span>ai
        </span>
        <span class="site-header__link-label">Прикл. ИИ</span>
      </a>
      <a href="<?= htmlspecialchars($sites['rag']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='rag')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-rag)">
          <span class="cli-only">cd </span>rag
        </span>
        <span class="site-header__link-label">RAG</span>
      </a>
      <a href="<?= htmlspecialchars($sites['bim']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='bim')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-bim)">
          <span class="cli-only">cd </span>bim
        </span>
        <span class="site-header__link-label">BIM</span>
      </a>
      <a href="<?= htmlspecialchars($sites['toc']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='toc')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-toc)">
          <span class="cli-only">cd </span>toc
        </span>
        <span class="site-header__link-label">Сделано</span>
      </a>
      <a href="<?= htmlspecialchars($sites['fun']['url'], ENT_QUOTES, 'UTF-8') ?>"
         class="site-header__link <?= ($siteId==='fun')?'active':'' ?>">
        <span class="site-header__link-cmd" style="color:var(--c-fun)">
          <span class="cli-only">cd </span>fun
        </span>
        <span class="site-header__link-label">Лаба</span>
      </a>
    </nav>

    <!-- Правая часть: переключатели + Telegram -->
    <div class="site-header__right">

      <!-- Mode toggle: Разработчик / Менеджер -->
      <button class="mode-toggle" id="mode-toggle"
              title="Переключить режим интерфейса"
              aria-label="Переключить режим интерфейса">
        <span class="cmd" id="mode-toggle-cmd">
          <span class="cli-only">role --dev</span>
          <span class="mgmt-only">role --mgr</span>
        </span>
        <span class="lbl" id="mode-toggle-lbl">Разработчик</span>
      </button>

      <!-- Theme toggle: тёмная / светлая -->
      <button class="theme-toggle" id="theme-toggle"
              title="Переключить тему"
              aria-label="Переключить тему">
        <span class="cmd" id="theme-toggle-cmd">
          <span class="cli-only">theme --light</span>
          <span class="mgmt-only">Light</span>
        </span>
        <span class="lbl" id="theme-toggle-lbl">Светлая</span>
      </button>

      <!-- Telegram канал -->
      <div class="header-tg-group">
        <a href="https://t.me/wearefired"
           class="site-header__tg"
           target="_blank" rel="noopener">
          <span class="cmd"><span class="cli-only">@</span>wearefired</span>
          <span class="lbl">Канал</span>
        </a>
      </div>

    </div><!-- /.site-header__right -->
  </div><!-- /.site-header__inner -->
</header>

<!-- OVC JS — Components и Interactions -->
<script src="/frontend/ui-kit/js/04_components.js"></script>
<script src="/frontend/ui-kit/js/05_interactions.js"></script>

<!-- Burger toggle (мобилка) -->
<script>
(function(){
  var btn = document.getElementById('burger');
  var nav = document.getElementById('site-nav');
  if (!btn || !nav) return;
  btn.addEventListener('click', function(e) {
    e.stopPropagation();
    nav.classList.toggle('open');
    btn.classList.toggle('open');
  });
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.site-header')) {
      nav.classList.remove('open');
      btn.classList.remove('open');
    }
  });
})();
</script>
