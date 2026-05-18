<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'Speckle Viewer — bim.ovc.me';
$pageDescription = 'Встроенный просмотрщик моделей из Speckle: совместная работа, версионирование, комментарии.';
include __DIR__ . '/../../public_html/header.php';
?>
<style>
:root { --site-accent: var(--c-bim); }
.page-hero { padding: 3rem 0 2rem; border-bottom: 2px solid var(--c-bim); margin-bottom: 2.5rem; }
.page-hero h1 { font-family: var(--font-ui); font-size: clamp(1.6rem,4vw,2.4rem); font-weight: 900; line-height: 1.2; margin: 0 0 .5rem; color: var(--text-main); }
.page-hero .accent { color: var(--c-bim); }
.page-hero__desc { font-size: 1rem; color: var(--text-muted); max-width: 720px; line-height: 1.7; margin-bottom: 1.5rem; }
.viewer-wrapper {
  position: relative;
  width: 100%;
  padding-bottom: 56.25%; /* 16:9 aspect ratio */
  height: 0;
  border: 1px solid var(--border);
  border-radius: var(--r-lg);
  overflow: hidden;
  background: var(--bg-secondary);
  margin-bottom: 1.5rem;
}
.viewer-wrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
.viewer-note {
  font-size: .87rem;
  color: var(--text-muted);
  margin-bottom: 2rem;
  line-height: 1.6;
}
.viewer-note code {
  background: var(--bg-tertiary);
  padding: 2px 6px;
  border-radius: 4px;
  font-family: var(--font-code);
  font-size: .8rem;
  color: var(--c-bim);
}
.nav-back {
  display: flex;
  gap: 12px;
  margin-top: 2rem;
  padding-top: 1.5rem;
  border-top: 1px solid var(--border);
  flex-wrap: wrap;
}
@media (max-width: 640px) {
  .nav-back { flex-direction: column; }
}
</style>

<main class="container">
<section class="page-hero">
    <div class="breadcrumbs" style="--theme-accent:var(--c-bim)">
        <a href="/">~/</a><span class="sep">/</span><a href="/">bim.ovc.me</a><span class="sep">/</span><span class="current">speckle-viewer</span>
    </div>
    <h1>Speckle Viewer: <span class="accent">облачная коллаборация</span></h1>
    <p class="page-hero__desc">Встроенный просмотрщик моделей из проекта <code>23bad52f4c</code>. Версионирование, комментарии, экспорт — без загрузки файлов на сервер.</p>
</section>

<article class="content-block">
    <div class="viewer-wrapper">
        <iframe
            title="Speckle"
            src="https://app.speckle.systems/projects/23bad52f4c/models/17a5c2dda7,342ac4c080,660165f044,a8aece6175,b3ae9fca40,b59505b6de,c6b850c1e6,c977d1451f,fa554cca37?embedToken=c904c6c451348cfacab138aeccc5ad2c9374755cea#embed=%7B%22isEnabled%22%3Atrue%2C%22disableModelLink%22%3Atrue%2C%22manualLoad%22%3Atrue%7D"
            loading="lazy"
            allow="clipboard-write; fullscreen"
        ></iframe>
    </div>
    
    <p class="viewer-note">
        <strong>Подсказка:</strong> модель загружается вручную — нажмите <code>Load Model</code> в интерфейсе вьювера. 
        Это экономит трафик и ускоряет первоначальную отрисовку.
    </p>
    
    <p class="viewer-note">
        <strong>Токен доступа:</strong> встроен в URL (<code>embedToken</code>). 
        Если вьювер не грузится — проверьте, не истёк ли токен в настройках проекта на <a href="https://app.speckle.systems" target="_blank">app.speckle.systems</a>.
    </p>
</article>

<nav class="nav-back">
    <a href="/" class="btn btn-nav c-bim">← Вернуться на главную</a>
    <a href="https://speckle.systems/" target="_blank" class="btn btn-action c-bim">Создать свой проект</a>
</nav>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>