<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'IFC.js (web-ifc-three) — bim.ovc.me';
$pageDescription = 'Архитектура и функционал вьюера bim.ovc.me/webjs на базе web-ifc-three v9.6.0. Стек, особенности, версии и уроки разработки.';
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
.content-block h3 { font-family: var(--font-ui); font-size: 1.1rem; font-weight: 700; margin: 1.5rem 0 .8rem; color: var(--text-main); }
.content-block p, .content-block li { font-size: .95rem; line-height: 1.7; color: var(--text-main); margin-bottom: .8rem; }
.content-block ul, .content-block ol { padding-left: 1.5rem; margin-bottom: 1rem; }
.content-block code { background: var(--bg-tertiary); padding: 2px 6px; border-radius: 4px; font-family: var(--font-code); font-size: .85rem; color: var(--c-bim); }
.content-block pre { background: var(--bg-tertiary); padding: 12px; border-radius: var(--r-md); overflow-x: auto; margin: 1rem 0; }
.content-block pre code { background: transparent; padding: 0; color: var(--text-muted); font-family: var(--font-code); }
.nav-back { display: flex; gap: 12px; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap; }
@media (max-width: 640px) { .nav-back { flex-direction: column; } }
</style>

<main class="container">
<section class="page-hero">
    <div class="breadcrumbs" style="--theme-accent:var(--c-bim)">
        <a href="/">~/</a><span class="sep">/</span><a href="/">bim.ovc.me</a><span class="sep">/</span><span class="current">ifcjs</span>
    </div>
    <h1>IFC.js Viewer: <span class="accent">архитектура v9.6.0</span></h1>
    <p class="page-hero__desc">Проект bim.ovc.me/webjs. Стек web-ifc-three v0.0.126, Three.js r149. Рабочее решение для просмотра IFC на shared-хостинге.</p>
</section>

<article class="content-block">
    <h2>🔹 Проект и стек</h2>
    <p>Вьювер представляет собой SPA (Single Page Application) в файле <code>app.js</code>, интегрированный в PHP-окружение через <code>importmap</code>.</p>
    <div class="content-block block-info">
        <div class="content-block-header">[INFO] Состав стека</div>
        <ul>
            <li><code>web-ifc-three@0.0.126</code> — IFCLoader и интеграция с Three.js</li>
            <li><code>web-ifc@0.0.44</code> — ESM-константы (<code>IFCWALL</code>, <code>IFCSLAB</code> и т.д.)</li>
            <li><code>web-ifc@0.0.39</code> — WASM-движок, используемый внутри IFCLoader</li>
            <li><code>three@r149</code> — графическая библиотека</li>
        </ul>
    </div>
</article>

<article class="content-block">
    <h2>⚙️ Архитектурные решения</h2>
    
    <h3>1. Два пакета web-ifc — намеренно</h3>
    <p>Структура <code>node_modules</code> содержит две версии ядра:</p>
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead><tr><th>Пакет</th><th>Версия</th><th>Роль</th></tr></thead>
            <tbody>
                <tr><td><code>node_modules/web-ifc/</code></td><td>0.0.44</td><td>Источник констант для <code>app.js</code></td></tr>
                <tr><td><code>node_modules/web-ifc-three/node_modules/web-ifc/</code></td><td>0.0.39</td><td>WASM-движок, используется IFCLoader</td></tr>
            </tbody>
        </table>
    </div>
    <p>Пути в коде разделены: <code>WASM_PATH</code> указывает на <code>0.0.39</code>, константы импортируются из корня. Выравнивать версии не нужно.</p>

    <h3>2. Importmap с двумя алиасами three</h3>
    <p><code>IFCLoader.js</code> использует старый путь <code>three/examples/jsm/</code>. Для работы importmap должен содержать оба алиаса:</p>
    <pre><code>{
  "three":               "./node_modules/three/build/three.module.js",
  "three/addons/":       "./node_modules/three/examples/jsm/",
  "three/examples/jsm/": "./node_modules/three/examples/jsm/",
  "web-ifc-three":       "./node_modules/web-ifc-three/IFCLoader.js",
  "web-ifc":             "./node_modules/web-ifc-three/node_modules/web-ifc/web-ifc-api.js"
}</code></pre>

    <h3>3. COEP убран намеренно</h3>
    <p>Заголовок <code>Cross-Origin-Embedder-Policy: require-corp</code> удален. Он блокирует Яндекс.Метрику, Google Fonts и CDN.</p>
    <p>Многопоточный WASM не используется — однопоточный режим <code>web-ifc@0.0.39</code> обеспечивает достаточную производительность.</p>

    <h3>4. Инверсные атрибуты не работают</h3>
    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Важно</div>
        <p>Метод <code>getItemProperties(modelID, id, true)</code> не возвращает данные в версии 0.0.39.</p>
        <p>Все отношения читаются через прямое сканирование: <code>getAllItemsOfType(IFCREL...)</code> и последующую итерацию. Например, <code>IFCRELASSIGNSTOPRODUCT</code> для систем и <code>IFCRELDEFINESBYTYPE</code> для типов.</p>
    </div>

    <h3>5. Константы IFC верифицированы</h3>
    <p>Некоторые типы отсутствуют в пакете <code>0.0.44</code>:</p>
    <ul>
        <li><code>IFCSPACETHERMOSTAT</code> — нет, используй <code>IFCSENSOR</code></li>
        <li><code>IFCFOOTING</code> — нет</li>
        <li><code>IFCFLOWSEGMENT</code> — есть, но это абстрактный базовый класс</li>
        <li><code>IFCDUCTSEGMENT</code> — есть (верифицировано)</li>
    </ul>
</article>

<article class="content-block">
    <h2>🚀 Функциональность v9.6.0</h2>
    
    <h3>Загрузка моделей</h3>
    <ul>
        <li><strong>Серверные:</strong> сканирование папки <code>models/</code>, кнопки загрузки.</li>
        <li><strong>С диска:</strong> drag-and-drop, множественные файлы, <code>ArrayBuffer → Blob URL</code>.</li>
        <li><strong>Авто-центровка:</strong> если модель дальше 300 ед. от начала координат — центрируется по XZ.</li>
    </ul>

    <h3>Навигация</h3>
    <ul>
        <li>Орбитальная камера с демпфированием.</li>
        <li>Переключение Перспектива / Орто (размер вычисляется от дистанции).</li>
        <li>👷 <strong>Режим прораба (WASD + E/Q):</strong> пешая навигация внутри модели.</li>
        <li>ViewCube (клик по осям X/Y/Z) и Pivot-сфера.</li>
    </ul>

    <h3>Выбор и свойства (двойной клик)</h3>
    <p>Панель с 4 вкладками:</p>
    <ol>
        <li>📋 <strong>Свойства:</strong> Name, Tag, GlobalId + все PropertySets.</li>
        <li>📐 <strong>Количества:</strong> <code>IfcElementQuantity</code> (длина, площадь, объём, вес).</li>
        <li>🏷️ <strong>Тип:</strong> TypeProperties.</li>
        <li>🧱 <strong>Материалы:</strong> слои с толщиной в мм, MaterialList.</li>
    </ol>

    <h3>BIM Статистика и Системы</h3>
    <ul>
        <li><strong>Облако тегов:</strong> категории с подсчётом элементов. Клик → X-Ray подсветка.</li>
        <li><strong>⚙️ Инженерные системы:</strong> Парсинг <code>IFCSYSTEM</code> через <code>IFCRELASSIGNSTOPRODUCT</code>. Аккордеон по разделам (ОВиК 💨 / ВК 🚿 / ЭОМ ⚡ / MEP 🔧).</li>
    </ul>

    <h3>Спецификация</h3>
    <ul>
        <li><strong>Ленивый расчёт:</strong> кнопка «Считать» для каждой категории (Qto не рассчитываются сразу, чтобы не зависнуть).</li>
        <li><strong>Экспорт:</strong> CSV по одной категории или общий CSV по всем посчитанным.</li>
    </ul>

    <h3>Проект-дерево</h3>
    <ul>
        <li>Spatial Structure (Project → Site → Building → Storey).</li>
        <li><strong>Lazy-load:</strong> 2 уровня сразу, глубже — по клику ▶.</li>
        <li>Поиск по имени и ExpressID.</li>
        <li>Глаз 👁️ — скрыть/показать элемент и дочерние.</li>
    </ul>

    <h3>Дополнительно</h3>
    <ul>
        <li><strong>Сечения:</strong> Горизонтальный (Y) и Вертикальный (X) слайдеры, raycast фильтрация за плоскостью.</li>
        <li><strong>Рулетка:</strong> ΔX, ΔY, ΔZ + расстояние, цветные линии.</li>
        <li><strong>Помещения:</strong> Индекс <code>IfcSpace</code>, поиск, zoom-to.</li>
        <li><strong>Скриншот:</strong> Суперсэмплинг 1.5x (без OOM).</li>
    </ul>
</article>

<article class="content-block">
    <h2>📜 История версий</h2>
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead><tr><th>Версия</th><th>Что сделано</th></tr></thead>
            <tbody>
                <tr><td>v8.x</td><td>Исходная версия с 10 критическими багами</td></tr>
                <tr><td>v9.0</td><td>Рефакторинг в класс BIMApp, исправление багов</td></tr>
                <tr><td>v9.1</td><td>Вкладки свойств: Основные/Количества/Тип/Материалы. Promise.all</td></tr>
                <tr><td>v9.2</td><td>Importmap fix, убран COEP, верификация констант</td></tr>
                <tr><td>v9.3</td><td>Блок MEP: Парсинг систем, вкладка ⚙️, обратный индекс</td></tr>
                <tr><td>v9.4</td><td>Фикс систем: замена IsGroupedBy на IFCRELASSIGNSTOPRODUCT</td></tr>
                <tr><td>v9.5</td><td>Спецификация переписана под bimData (мгновенно)</td></tr>
                <tr><td>v9.6</td><td>Ленивый расчёт Qto, CSV по категориям</td></tr>
            </tbody>
        </table>
    </div>
</article>

<article class="content-block">
    <h2>🧠 Ключевые уроки проекта</h2>
    <ul>
        <li>Два <code>web-ifc</code> в <code>node_modules</code> — нормально при таком стеке. Не выравнивай их.</li>
        <li>COEP + CORP = война со сторонними скриптами. Для однопоточного WASM COEP не нужен.</li>
        <li><code>IFCLoader</code> тащит старый путь <code>three/examples/jsm/</code> — всегда добавляй оба алиаса.</li>
        <li>Константы IFC верифицируй по реальному экспорту пакета, не по документации.</li>
        <li>Считать Qto сразу по всем элементам = зависание. Ленивый расчёт по категории — единственный подход.</li>
        <li><code>IFCSYSTEM</code> может отсутствовать в модели (нормально для Revit по умолчанию).</li>
    </ul>
</article>

<nav class="nav-back">
    <a href="/" class="btn btn-nav c-bim">← Вернуться на главную</a>
    <a href="/webjs/" class="btn btn-action c-bim">Открыть IFC.js Viewer →</a>
</nav>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>