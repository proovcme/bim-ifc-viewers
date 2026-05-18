<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'Open BIM Components (OBC) — bim.ovc.me';
$pageDescription = 'Практический разбор OBC: архитектура, проблемы с COEP, зависимости и почему мы вернулись к IFC.js';
include __DIR__ . '/../../public_html/header.php';
?>
<style>
:root { --site-accent: var(--c-bim); }
.page-hero { padding: 3rem 0 2rem; border-bottom: 2px solid var(--c-bim); margin-bottom: 2.5rem; }
.page-hero h1 { font-family: var(--font-ui); font-size: clamp(1.6rem,4vw,2.4rem); font-weight: 900; line-height: 1.2; margin: 0 0 .5rem; color: var(--text-main); }
.page-hero .accent { color: var(--c-bim); }
.page-hero__desc { font-size: 1rem; color: var(--text-muted); max-width: 720px; line-height: 1.7; margin-bottom: 1.5rem; }
.content-block { margin-bottom: 2.5rem; }
.content-block h2 { font-family: var(--font-ui); font-size: 1.35rem; font-weight: 800; margin: 0 0 1rem; color: var(--text-main); }
.content-block p, .content-block li { font-size: .95rem; line-height: 1.7; color: var(--text-main); margin-bottom: .8rem; }
.content-block ul, .content-block ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.content-block code { background: var(--bg-tertiary); padding: 2px 6px; border-radius: 4px; font-family: var(--font-code); font-size: .85rem; color: var(--c-bim); }
.content-block pre { background: var(--bg-tertiary); padding: 12px; border-radius: var(--r-md); overflow-x: auto; margin: 1rem 0; }
.content-block pre code { background: transparent; padding: 0; color: var(--text-muted); font-family: var(--font-code); }
.content-block blockquote { border-left: 3px solid var(--c-bim); padding-left: 1rem; margin: 1.5rem 0; color: var(--text-muted); font-style: italic; }
.nav-back { display: flex; gap: 12px; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap; }
@media (max-width: 640px) { .nav-back { flex-direction: column; } }
</style>

<main class="container">
<section class="page-hero">
    <div class="breadcrumbs" style="--theme-accent:var(--c-bim)">
        <a href="/">~/</a><span class="sep">/</span><a href="/">bim.ovc.me</a><span class="sep">/</span><span class="current">obc</span>
    </div>
    <h1>Open BIM Components: <span class="accent">зачем мы его пробовали</span></h1>
    <p class="page-hero__desc">Практический разбор на основе реального проекта bim.ovc.me: архитектура, подводные камни COEP, нестабильные зависимости и причины возврата к IFC.js.</p>
</section>

<article class="content-block">
    <h2>Что такое Open BIM Components (OBC)</h2>
    <p>Open BIM Components — или That Open Engine — это стек открытых библиотек от компании That Open Company для работы с IFC-файлами в браузере. Позиционируется как преемник IFC.js, следующее поколение open source BIM в вебе.</p>
    <p><strong>Архитектура стека выглядит так:</strong></p>
    <ul>
        <li><code>web-ifc</code> — C++ скомпилированный в WASM: парсинг IFC на нативной скорости</li>
        <li><code>@thatopen/fragments</code> — бинарный формат <code>.frag</code> на базе FlatBuffers</li>
        <li><code>@thatopen/components</code> — ядро: BIM-компоненты поверх Three.js</li>
        <li><code>@thatopen/components-front</code> — расширения для браузера (постпроцессинг, Highlighter, Clipper)</li>
        <li><code>@thatopen/ui</code> — UI-библиотека на Web Components / Lit</li>
        <li><code>@thatopen/ui-obc</code> — готовые plug-n-play BIM-виджеты</li>
    </ul>
    <div class="content-block block-info">
        <div class="content-block-header">[INFO] Ключевая концепция</div>
        <p>IFC не рендерится напрямую. Движок сначала конвертирует <code>.ifc</code> → <code>.frag</code> (бинарный формат Fragments), и уже <code>.frag</code> отображается в 3D. В продакшене рекомендуется конвертировать один раз, хранить <code>.frag</code> и грузить его — это быстрее.</p>
        <p>Звучит солидно. На бумаге — современно, производительно, модульно.</p>
    </div>
</article>

<article class="content-block">
    <h2>Почему мы вообще взялись за OBC</h2>
    <p>Нам нужен был браузерный IFC-вьювер для проекта bim.ovc.me: загрузка <code>.ifc</code> файлов, просмотр свойств элементов, дерево модели, сечения, фильтрация по категориям. Шаред-хостинг, PHP-бэкенд, никаких Node.js на продакшене.</p>
    <p>OBC казался очевидным выбором:</p>
    <ul>
        <li>активно поддерживается (релизы каждые несколько недель)</li>
        <li>большое сообщество на GitHub</li>
        <li>готовые компоненты из коробки — дерево, свойства, highlighter</li>
        <li>официальная документация и туториалы</li>
    </ul>
    <p>Мы начали разворачивать. И сразу уткнулись в стену.</p>
</article>

<article class="content-block">
    <h2>Первая проблема: WASM требует особых HTTP-заголовков</h2>
    <p><code>web-ifc</code> использует WebAssembly в многопоточном режиме — через <code>SharedArrayBuffer</code>. Это быстро, но браузер разрешает <code>SharedArrayBuffer</code> только при соблюдении политики <strong>Cross-Origin Isolation</strong>. Нужны два заголовка одновременно:</p>
    <pre><code>Cross-Origin-Opener-Policy: same-origin
Cross-Origin-Embedder-Policy: require-corp</code></pre>
    <p>Без них — <code>SharedArrayBuffer is not defined</code>, движок падает или деградирует в однопоточный режим, который в OBC работает нестабильно.</p>
    <p>На VPS это решаемо: добавил в nginx — и готово. Но у нас <strong>шаред-хостинг</strong>. Там нет доступа к конфигу nginx. <code>.htaccess</code> помогает только частично. Решение — <code>coi-serviceworker</code>, Service Worker который инжектирует нужные заголовки. Это костыль, но работающий. Мы пошли дальше.</p>
</article>

<article class="content-block">
    <h2>Вторая проблема: COEP убивает всё вокруг</h2>
    <p><code>Cross-Origin-Embedder-Policy: require-corp</code> — это не просто заголовок. Это политика изоляции, которая говорит браузеру: «любой ресурс, загружаемый этой страницей, обязан явно разрешить себя загружать через заголовок <code>Cross-Origin-Resource-Policy</code>».</p>
    <p><strong>Что это означает на практике:</strong></p>
    <ul>
        <li><strong>Яндекс.Метрика</strong> — заблокирована. Их CDN не выставляет <code>CORP</code>.</li>
        <li><strong>Google Fonts</strong> — заблокированы.</li>
        <li><strong>Любой внешний CDN без <code>CORP</code></strong> — заблокирован.</li>
        <li><strong>Сторонние виджеты, пиксели аналитики</strong> — заблокированы.</li>
    </ul>
    <p>Мы добавили COEP ради WASM — и вся страница превратилась в изолированный бункер, отрезанный от внешнего мира. Для production-сайта это неприемлемо.</p>
    <p>Можно попробовать выставить <code>CORP: cross-origin</code> на все свои ресурсы и молиться, чтобы сторонние тоже поддерживали. На практике — это не работает для реальных сайтов с аналитикой и сторонними скриптами.</p>
</article>

<article class="content-block">
    <h2>Третья проблема: версии ломаются без предупреждения</h2>
    <p><code>@thatopen/components</code> релизится активно — мажорные изменения API между минорными версиями. Документация отстаёт. Туториалы с GitHub написаны под одну версию, а npm отдаёт другую — и код молча не работает или падает с непонятными ошибками.</p>
    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Реальный пример из нашей сессии</div>
        <p>воркер FragmentsManager в версии 3.4.x пытается вызвать <code>OBC.FragmentsManager.getWorker()</code> — это тянет воркер с <code>unpkg.com</code>. Без COEP blob-воркер не создаётся, <code>getWorker()</code> возвращает <code>undefined</code>, <code>fragments.init(undefined)</code> падает с ошибкой <code>Component.uuid is not defined</code>. Трейс указывает внутрь минифицированного бандла — отлаживать без исходников мучительно.</p>
    </div>
    <p>Зафиксировать версии? Пробовали. Но <code>@thatopen/components-front 3.4.3</code> требует <code>components 3.4.6</code>, который требует <code>web-ifc 0.0.77</code>, который требует Node.js ≥ 22 для <code>camera-controls</code> — а у нас Node 20 LTS. Цепочка зависимостей нестабильна.</p>
</article>

<article class="content-block">
    <h2>Четвёртая проблема: TypeScript-first архитектура без запасного пути</h2>
    <p>OBC проектировался под Vite + TypeScript + сборку. Всё API — типизированные классы, дженерики, декораторы. Использовать как vanilla JS через <code>&lt;script type="module"&gt;</code> без бандлера — теоретически можно, но на практике разваливается: импорты из <code>unpkg</code> тянут не те версии, типы не резолвятся, конфигурация WASM-пути требует знания внутренней структуры пакета.</p>
    <p>Для нашего шаред-хостинга с PHP и без CI/CD это создавало дополнительный слой сложности: нужен локальный Node.js для сборки, <code>npm run build</code>, деплой <code>dist/</code> по FTP. Не смертельно, но добавляет инфраструктуры.</p>
</article>

<article class="content-block">
    <h2>Что сказали другие LLM</h2>
    <blockquote>
        Важный контекст: задача пришла к нам уже после того, как Claude, Gemini и Qwen не смогли заставить OBC работать. Все три столкнулись с одними и теми же проблемами: заголовки, версии, воркеры. Сгенерированный код либо не запускался, либо зависал на инициализации.
    </blockquote>
    <p>Это само по себе сигнал: если три независимых AI-ассистента не могут воспроизвести рабочий пример по официальной документации — значит, сложность системы превышает то, что можно надёжно использовать без глубокой экспертизы.</p>
</article>

<article class="content-block">
    <h2>Почему мы вернулись к IFC.js (web-ifc-three)</h2>
    <p><code>web-ifc-three</code> — это старый стек. Пакет не обновлялся активно с 2022 года. Версия которую мы используем (<code>0.0.126</code>) вышла давно. Но у него есть качества, которые в нашем случае оказались важнее «современности».</p>
    <ul>
        <li>✅ <strong>Работает без COEP.</strong> <code>web-ifc@0.0.39</code> внутри IFCLoader запускается в однопоточном режиме — без SharedArrayBuffer, без COEP, без войны со сторонними скриптами. Яндекс.Метрика работает. Google Fonts работают.</li>
        <li>✅ <strong>Vanilla JS, без бандлера.</strong> Весь стек подключается через <code>importmap</code> в HTML-файле — ни Vite, ни TypeScript, ни сборка. Деплой на шаред-хостинг: скопировал <code>node_modules</code>, готово.</li>
        <li>✅ <strong>Стабильный API.</strong> Версия <code>0.0.126</code> не меняется. Написанный код через год будет работать так же.</li>
        <li>✅ <strong>Прямой доступ к WASM.</strong> <code>IFCLoader.getIfcAPI()</code> отдаёт прямой доступ к парсеру — <code>getAllItemsOfType</code>, <code>getItemProperties</code>, <code>getPropertySets</code>. Всё что нужно для работы с BIM-данными — доступно.</li>
    </ul>
</article>

<article class="content-block">
    <h2>Что получилось в итоге</h2>
    <p>За две сессии мы собрали <code>BIMApp v9.2.0</code> — полноценный браузерный IFC-вьювер на <code>web-ifc-three</code>:</p>
    <ul>
        <li>загрузка серверных и локальных IFC, несколько моделей одновременно</li>
        <li>орбитальная камера, перспектива/орто, режим прораба (WASD)</li>
        <li>ViewCube, X-Ray, сечения, рулетка</li>
        <li>дерево модели с lazy-load и поиском</li>
        <li>свойства элемента в 4 вкладках: основные, количества (Qto), тип, материалы</li>
        <li>BIM-статистика по категориям с фильтрацией и подсветкой</li>
        <li>поиск по помещениям</li>
        <li>скриншот с суперсэмплингом</li>
    </ul>
    <p>Всё это — без COEP, без TypeScript, без CI/CD, на шаред-хостинге, в одном файле <code>app.js</code>.</p>
    <div class="content-block block-success">
        <div class="content-block-header">[SUCCESS] Главный вывод</div>
        <p>OBC — хорошая система для правильной инфраструктуры: VPS или облако с полным контролем заголовков, Node.js на продакшене, TypeScript в проекте, команда знакомая с современным JS-тулчейном.</p>
        <p>Для шаред-хостинга, для быстрого старта, для проектов где стабильность важнее фич — <code>web-ifc-three</code> до сих пор рабочий выбор. Он старый, но он работает. А в нашем случае это было важнее, чем быть на передовой.</p>
    </div>
</article>

<article class="content-block">
    <h2>Техническая шпаргалка: почему не заводится OBC</h2>
    <p>Если вы всё равно хотите попробовать OBC — вот список мин которые мы прошли:</p>
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th>Симптом</th>
                    <th>Причина</th>
                    <th>Решение</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>SharedArrayBuffer is not defined</code></td>
                    <td>Нет COEP/COOP заголовков</td>
                    <td>Добавить в сервер или использовать <a href="https://github.com/gzuidhof/coi-serviceworker" target="_blank">coi-serviceworker</a></td>
                </tr>
                <tr>
                    <td>Яндекс.Метрика/CDN не грузится</td>
                    <td>COEP блокирует внешние ресурсы</td>
                    <td>Убрать COEP — но тогда пункт выше</td>
                </tr>
                <tr>
                    <td><code>Component.uuid is not defined</code></td>
                    <td><code>getWorker()</code> вернул <code>undefined</code></td>
                    <td>Указать путь к воркеру явно, не через <code>getWorker()</code></td>
                </tr>
                <tr>
                    <td>Чёрный экран без ошибок</td>
                    <td>Забыли вызвать <code>components.init()</code></td>
                    <td><code>components.init()</code> после создания world/scene/renderer/camera</td>
                </tr>
                <tr>
                    <td>Модель не появляется</td>
                    <td>FragmentsManager не слушает камеру</td>
                    <td>Добавить <code>camera.controls.addEventListener("update", ...)</code></td>
                </tr>
                <tr>
                    <td>Ошибки типов на 3.4.x vs 3.3.x</td>
                    <td>Минорные breaking changes в API</td>
                    <td>Фиксировать версии без <code>^</code>, проверять changelog</td>
                </tr>
                <tr>
                    <td><code>Unexpected token '&lt;'</code> в консоли</td>
                    <td>Vite упал, отдаёт HTML вместо JS</td>
                    <td>Смотреть терминал с <code>npm run dev</code>, там ошибка компиляции</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<div class="content-block" style="border-top: 1px solid var(--border); padding-top: 1rem; color: var(--text-muted); font-size: .85rem;">
    <p><strong>Проект:</strong> bim.ovc.me — браузерный IFC-вьювер без серверной части<br>
    <strong>Стек итоговый:</strong> <code>web-ifc-three@0.0.126</code> + <code>web-ifc@0.0.44/0.0.39</code> + <code>three.js r149</code></p>
</div>

<nav class="nav-back">
    <a href="/" class="btn btn-nav c-bim">← Вернуться на главную</a>
    <a href="/webjs/" class="btn btn-action c-bim">Открыть IFC.js Viewer →</a>
</nav>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>