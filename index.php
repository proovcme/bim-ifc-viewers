<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'BIM & ТИМ — bim.ovc.me';
$pageDescription = 'IFC-просмотрщики: теория и практика. IFC.js, Speckle, OBC, Xeokit.';
include __DIR__ . '/../../public_html/header.php';
require_once __DIR__ . '/../../public_html/lib/frontmatter.php';
require_once __DIR__ . '/../../public_html/lib/views.php';
require_once __DIR__ . '/../../public_html/lib/sites.php';

$layout   = json_decode(file_get_contents(__DIR__ . '/config/bim_layout.json'), true) ?: [];
$hero     = $layout['hero']     ?? [];
$sections = $layout['sections'] ?? [];
?>
<meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<meta property="og:title"       content="<?= htmlspecialchars($pageTitle) ?>">
<meta property="og:url"         content="https://bim.ovc.me/">
<style>
:root { --site-accent: var(--c-bim); }
.bim-hero { padding: 4rem 0 2.5rem; border-bottom: 2px solid var(--c-bim); margin-bottom: 2.5rem; }
.bim-hero__sub { font-family: var(--font-code); font-size: .78rem; color: var(--c-bim); text-transform: uppercase; letter-spacing: .1em; font-weight: 700; margin-bottom: .8rem; }
.bim-hero h1 { font-family: var(--font-ui); font-size: clamp(1.8rem,5vw,3rem); font-weight: 900; line-height: 1.15; margin: 0 0 1rem; color: var(--text-main); letter-spacing: -.02em; }
.bim-hero h1 .accent { color: var(--c-bim); }
.bim-hero__desc { font-size: 1rem; color: var(--text-muted); max-width: 640px; line-height: 1.7; margin-bottom: 1.5rem; }
.bim-hero__chips { display: flex; flex-wrap: wrap; gap: 8px; }
.bim-chip { font-family: var(--font-code); font-size: .7rem; font-weight: 700; text-transform: uppercase; text-decoration: none; color: var(--text-muted); padding: 5px 12px; border: 1px solid var(--border); border-radius: 20px; transition: all .2s; }
.bim-chip:hover { border-color: var(--c-bim); color: var(--c-bim); }
.section-wrap { margin-bottom: 3.5rem; scroll-margin-top: 120px; }
.bim-section-head { font-family: var(--font-code); font-size: .9rem; font-weight: 700; margin: 0 0 1.2rem; color: var(--text-main); text-transform: uppercase; letter-spacing: .06em; display: flex; align-items: center; gap: 10px; }
.bim-section-head::after { content: ""; flex: 1; height: 1px; background: linear-gradient(to right, var(--c-bim), transparent); }
.bim-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }

/* === БАЗОВЫЕ КАРТОЧКИ === */
.bim-card { position: relative; display: block; text-decoration: none; background: var(--bg-secondary); border: 1px solid var(--border); border-radius: var(--r-lg); padding: 20px; color: var(--text-main); transition: transform .2s, border-color .2s, box-shadow .2s; overflow: hidden; }
.bim-card::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--c-bim); }
.bim-card:hover { transform: translateY(-3px); border-color: var(--c-bim); box-shadow: 0 6px 20px rgba(23,138,153,.18); }
.bim-card__tag { font-family: var(--font-code); font-size: .65rem; font-weight: 700; text-transform: uppercase; color: var(--c-bim); margin-bottom: 8px; letter-spacing: .05em; }
.bim-card__title { font-family: var(--font-ui); font-size: 1rem; font-weight: 800; margin: 0 0 8px; line-height: 1.3; }
.bim-card__desc { font-size: .87rem; color: var(--text-muted); line-height: 1.5; margin: 0 0 10px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
.bim-card__foot { font-family: var(--font-code); font-size: .67rem; color: var(--text-dim); display: flex; justify-content: space-between; }

/* === LIVE DEMO КАРТОЧКИ === */
.bim-card--live {
  border: 2px solid var(--c-bim);
  background: linear-gradient(135deg, var(--bg-secondary) 0%, rgba(23,138,153,0.08) 100%);
  animation: pulse-border 2s ease-in-out infinite;
}
.bim-card--live::after {
  content: "▶";
  position: absolute;
  top: 10px;
  right: 14px;
  font-size: .7rem;
  color: var(--c-bim);
  animation: blink 1.5s step-end infinite;
}
.bim-card--live:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 25px rgba(23,138,153,0.25);
  border-color: var(--c-bim);
}
.bim-card__tag--live {
  background: var(--c-bim);
  color: #fff;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: .6rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .05em;
}
@keyframes pulse-border {
  0%, 100% { box-shadow: 0 0 0 0 rgba(23,138,153,0.4); }
  50% { box-shadow: 0 0 0 6px rgba(23,138,153,0); }
}
@keyframes blink {
  0%, 49% { opacity: 1; }
  50%, 100% { opacity: .4; }
}

.bim-empty { border: 1px dashed var(--c-bim); border-radius: var(--r-lg); padding: 1.5rem; color: var(--text-muted); font-size: .88rem; }
.bim-empty strong { display: block; font-family: var(--font-code); font-size: .72rem; color: var(--c-bim); text-transform: uppercase; margin-bottom: .4rem; }
@media (max-width: 640px) { .bim-grid { grid-template-columns: 1fr; } }
</style>
<main class="container">
<section class="bim-hero">
<?php if (!empty($hero['subtitle'])): ?>
<div class="bim-hero__sub"><?= htmlspecialchars($hero['subtitle']) ?></div>
<?php endif; ?>
<h1><?= htmlspecialchars($hero['title_line1'] ?? 'BIM & ТИМ:') ?><br>
<span class="accent"><?= htmlspecialchars($hero['title_line2'] ?? 'автоматизация без магии') ?></span>
</h1>
<?php if (!empty($hero['description'])): ?>
<p class="bim-hero__desc"><?= htmlspecialchars($hero['description']) ?></p>
<?php endif; ?>
<nav class="bim-hero__chips">
<?php foreach ($sections as $s): if (empty($s['visible'])) continue; ?>
<a href="#<?= htmlspecialchars($s['id']) ?>" class="bim-chip"><?= htmlspecialchars($s['title']) ?></a>
<?php endforeach; ?>
</nav>
</section>

<?php foreach ($sections as $sec):
if (empty($sec['visible'])) continue;
$secId   = htmlspecialchars($sec['id'] ?? '');
$secTitle= htmlspecialchars($sec['title'] ?? '');
?>
<section class="section-wrap" id="<?= $secId ?>">
<h2 class="bim-section-head"><?= $secTitle ?></h2>

<?php if ($sec['type'] === 'cms_loop'):
  $p = $sec['cms_params'] ?? [];
  $arts = getArticles($p['site'] ?? 'bim', $p['section'] ?? '', false);
  $limit = (int)($p['limit'] ?? 0);
  if ($limit > 0) $arts = array_slice($arts, 0, $limit);
  $views = function_exists('getAllViewCounts') ? getAllViewCounts() : [];
  if (!empty($arts)): ?>
  <div class="bim-grid">
  <?php foreach ($arts as $art):
    $m = $art['meta'];
    $slug = htmlspecialchars($m['slug'] ?? '');
  ?>
  <a href="/article.php?slug=<?= $slug ?>" class="bim-card">
    <div class="bim-card__tag"><?= htmlspecialchars($m['section'] ?? '') ?></div>
    <h3 class="bim-card__title"><?= htmlspecialchars($m['title'] ?? '') ?></h3>
    <p class="bim-card__desc"><?= htmlspecialchars($m['description'] ?? '') ?></p>
    <div class="bim-card__foot">
      <span>👁 <?= (int)($views[$m['slug']] ?? 0) ?></span>
      <span><?= htmlspecialchars($m['date'] ?? '') ?></span>
    </div>
  </a>
  <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="bim-empty">
    <strong>[<?= strtoupper($secId) ?>]</strong>
    Статьи появятся здесь. Добавь через <a href="/admin/?tab=cms" style="color:var(--c-bim)">CMS</a> → сайт BIM → раздел «<?= $secTitle ?>».
  </div>
  <?php endif; ?>

<?php elseif ($sec['type'] === 'dev_grid' && !empty($sec['cards'])): ?>
<div class="bim-grid">
  <?php foreach ($sec['cards'] as $card): 
    $url = htmlspecialchars($card['url'] ?? '#');
    $target = htmlspecialchars($card['target'] ?? '_self');
    $color = htmlspecialchars($card['color_class'] ?? 'c-bim');
    $isDemo = !empty($card['is_demo']);
    $cardClass = $isDemo ? 'bim-card bim-card--live' : 'bim-card';
    $tagClass = $isDemo ? 'bim-card__tag--live' : 'bim-card__tag';
  ?>
  <a href="<?= $url ?>" class="<?= $cardClass ?>" target="<?= $target ?>">
    <div class="<?= $tagClass ?>"><?= htmlspecialchars($card['tag'] ?? '') ?></div>
    <h3 class="bim-card__title"><?= htmlspecialchars($card['title'] ?? '') ?></h3>
    <p class="bim-card__desc"><?= htmlspecialchars($card['desc'] ?? '') ?></p>
    <div class="bim-card__foot">
      <span class="<?= $color ?>">↗</span>
      <span><?= $isDemo ? 'Запустить' : 'Открыть' ?></span>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<?php else: ?>
<div class="bim-empty">
  <strong>[<?= strtoupper($secId) ?>]</strong>
  Секция настроена, но контент ещё не добавлен.
</div>
<?php endif; ?>
</section>
<?php endforeach; ?>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>