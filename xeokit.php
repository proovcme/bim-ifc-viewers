<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'Xeokit BIM Viewer — bim.ovc.me';
$pageDescription = 'Высокопроизводительный WebGL-движок для просмотра больших BIM-моделей в браузере. Конвертация IFC в XKT, запуск без COEP, деплой на Shared-хостинг.';
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
.content-block blockquote { border-left: 3px solid var(--c-bim); padding-left: 1rem; margin: 1.5rem 0; color: var(--text-muted); font-style: italic; }
.nav-back { display: flex; gap: 12px; margin-top: 3rem; padding-top: 1.5rem; border-top: 1px solid var(--border); flex-wrap: wrap; }
@media (max-width: 640px) { .nav-back { flex-direction: column; } }
</style>

<main class="container">
<section class="page-hero">
    <div class="breadcrumbs" style="--theme-accent:var(--c-bim)">
        <a href="/">~/</a><span class="sep">/</span><a href="/">bim.ovc.me</a><span class="sep">/</span><span class="current">xeokit</span>
    </div>
    <h1>Xeokit: <span class="accent">высокопроизводительный WebGL</span></h1>
    <p class="page-hero__desc">Готовое решение для просмотра BIM-моделей в браузере на собственном движке. Без COEP, без SharedArrayBuffer, с быстрой конвертацией IFC → XKT.</p>
</section>

<article class="content-block">
    <h2>🚀 Ключевые особенности</h2>
    <ul>
        <li><strong>Без серверной логики</strong> — работает на любом статическом хостинге (Shared, GitHub Pages, VPS).</li>
        <li><strong>Не требует COOP/COEP</strong> — в отличие от аналогов, xeokit не использует WASM с <code>SharedArrayBuffer</code>, поэтому работает «из коробки» без сложной настройки заголовков.</li>
        <li><strong>Высокая производительность</strong> — собственный WebGL-движок (без Three.js) обеспечивает быструю загрузку и рендеринг больших моделей.</li>
        <li><strong>Готовый интерфейс</strong> — дерево объектов, сечения, замеры, X-ray режим, переключение 2D/3D.</li>
        <li><strong>Локальная загрузка без сервера</strong> — кнопка «Добавить XKT» позволяет открыть файл прямо из браузера, не загружая его на хостинг.</li>
    </ul>
</article>

<article class="content-block">
    <h2>📋 Требования</h2>
    <ul>
        <li><strong>Node.js 18 LTS</strong> — обязательно для конвертации моделей, версия 20+ может вызывать ошибки зависимостей.</li>
        <li><strong>npm</strong> — пакетный менеджер (устанавливается вместе с Node.js).</li>
        <li><strong>Любой веб-браузер с поддержкой WebGL 2.0</strong>.</li>
    </ul>
</article>

<article class="content-block">
    <h2>📁 Структура проекта</h2>
    <pre><code>xeo/
├── app/                      ← Веб-приложение (на хостинг загружать содержимое)
│   ├── index.html            ← Точка входа в вьювер
│   ├── css/                  ← Стили интерфейса
│   ├── dist/                 ← Скрипты xeokit (собранная версия)
│   └── data/                 ← Данные проектов и моделей
│       └── projects/
│           └── MyProject/
│               ├── index.json          ← Конфиг проекта
│               └── models/
│                   └── MainBuilding/
│                       ├── index.json  ← Конфиг модели
│                       └── geometry.xkt ← Сконвертированная 3D-модель
├── models/                   ← Исходные IFC-файлы (на сервер не загружаются!)
│   └── МОДЕЛЬ.ifc
└── xeokit-convert/           ← Инструмент конвертации (нужен только локально)</code></pre>
    <p>Один проект может содержать несколько моделей (дисциплин) — каждая в своей подпапке внутри <code>models/</code>. Все они будут отображаться в панели «Models» со своими чекбоксами.</p>
</article>

<article class="content-block">
    <h2>⚙️ Конвертация модели (IFC → XKT)</h2>
    <p>Браузер не читает <code>.ifc</code> напрямую. Модели конвертируются в бинарный формат <code>.xkt</code> (сжатие 5–20×). Конвертация выполняется один раз локально, на хостинг загружается только готовый <code>.xkt</code>.</p>

    <h3>Подготовка окружения</h3>
    <p><strong>macOS / Linux:</strong></p>
    <pre><code>nvm install 18
nvm use 18
git clone https://github.com/xeokit/xeokit-convert.git
cd xeokit-convert && npm install && cd ..</code></pre>

    <p><strong>Windows (PowerShell):</strong></p>
    <pre><code># Скачайте Node.js 18 LTS с nodejs.org или через winget:
winget install OpenJS.NodeJS.LTS

git clone https://github.com/xeokit/xeokit-convert.git
cd xeokit-convert
npm install
cd ..</code></pre>

    <h3>Запуск конвертации</h3>
    <p><strong>macOS / Linux:</strong></p>
    <pre><code>NODE_OPTIONS="--max-old-space-size=8192" \
node ./xeokit-convert/convert2xkt.js \
  -s "models/ВАША_МОДЕЛЬ.ifc" \
  -o "app/data/projects/MyProject/models/MainBuilding/geometry.xkt" \
  -l</code></pre>

    <p><strong>Windows — PowerShell:</strong></p>
    <pre><code>$env:NODE_OPTIONS="--max-old-space-size=8192"
node ./xeokit-convert/convert2xkt.js -s "models/ВАША_МОДЕЛЬ.ifc" -o "app/data/projects/MyProject/models/MainBuilding/geometry.xkt" -l</code></pre>

    <p><strong>Windows — cmd.exe:</strong></p>
    <pre><code>set NODE_OPTIONS=--max-old-space-size=8192
node ./xeokit-convert/convert2xkt.js -s "models/ВАША_МОДЕЛЬ.ifc" -o "app/data/projects/MyProject/models/MainBuilding/geometry.xkt" -l</code></pre>

    <div class="content-block block-info">
        <div class="content-block-header">[INFO] Важно</div>
        <p>Флаг <code>-l</code> показывает прогресс в консоли. Время конвертации: 2–15 мин в зависимости от размера файла.</p>
        <p><code>NODE_OPTIONS</code> обязателен для файлов >100 МБ — без него процесс упадёт с <code>out of memory</code>.</p>
    </div>
</article>

<article class="content-block">
    <h2>Настройка конфигурации</h2>
    <p>После конвертации убедитесь, что <code>index.json</code> описывает модель корректно.</p>

    <h3>Файл проекта (<code>app/data/projects/MyProject/index.json</code>):</h3>
    <pre><code>{
  "id": "MyProject",
  "name": "Название проекта",
  "models": [{ "id": "MainBuilding", "name": "Модель" }],
  "viewerContent": { "modelsLoaded": [ "MainBuilding" ] }
}</code></pre>

    <h3>Файл модели (<code>app/data/projects/MyProject/models/MainBuilding/index.json</code>):</h3>
    <pre><code>{
  "id": "MainBuilding",
  "name": "Главная модель",
  "src": "./geometry.xkt",
  "xktVersion": 12,
  "saoEnabled": true,
  "edges": true
}</code></pre>

    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Важно</div>
        <p>Значение <code>"xktVersion"</code> должно совпадать с версией из лога конвертации (обычно v10, v11 или v12).</p>
    </div>
</article>

<article class="content-block">
    <h2>🖥️ Загрузка модели без сервера</h2>
    <p>Кнопка «Добавить XKT» в интерфейсе вьювера позволяет открыть <code>.xkt</code> файл прямо с локального диска — без загрузки на хостинг. Файл читается браузером, данные никуда не отправляются.</p>
    <p><strong>Это удобно для:</strong></p>
    <ul>
        <li>быстрой проверки результата конвертации;</li>
        <li>демонстрации модели без деплоя;</li>
        <li>работы офлайн.</li>
    </ul>
</article>

<article class="content-block">
    <h2>🌍 Запуск и деплой</h2>

    <h3>Локальный запуск</h3>
    <pre><code>cd app
python3 -m http.server 8080</code></pre>
    <p>Откройте: <code>http://localhost:8080/index.html?projectId=MyProject</code></p>

    <h3>Деплой на Shared-хостинг</h3>
    <ol>
        <li>Откройте папку <code>app/</code> — это и есть корень сайта.</li>
        <li>Загрузите всё содержимое <code>app/</code> через FTP или File Manager в <code>public_html</code>.</li>
        <li>Проверьте, что файлы <code>.xkt</code> и <code>.json</code> загрузились без ошибок.</li>
    </ol>
    <p>Готово — Node.js на хостинге не нужен.</p>

    <h3>Деплой на VPS (Nginx)</h3>
    <p>Nginx по умолчанию не знает MIME-типы для <code>.xkt</code> и может отдавать <code>.json</code> как <code>text/plain</code> — это ломает загрузку моделей. Добавьте в конфиг:</p>
    <pre><code>server {
    # ...

    # MIME-типы для xeokit
    types {
        application/octet-stream  xkt;
        application/json          json;
    }

    # Кеширование статики
    location ~* \.(xkt|json)$ {
        expires 7d;
        add_header Cache-Control "public";
    }
}</code></pre>

    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Критично</div>
        <p>Строка <code>application/json json;</code> критична — без неё некоторые сборки Nginx отдают <code>index.json</code> как <code>text/plain</code>, и xeokit не может распарсить конфиг проекта.</p>
    </div>
</article>

<article class="content-block">
    <h2>🆘 Решение проблем</h2>
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th>Ошибка</th>
                    <th>Причина</th>
                    <th>Решение</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>404 на <code>all.min.css</code> / <code>tippy.js</code></td>
                    <td>CDN недоступен</td>
                    <td>Проверьте интернет-соединение. Пути заменены на CDN в <code>index.html</code>.</td>
                </tr>
                <tr>
                    <td>Чёрный экран</td>
                    <td>WebGL недоступен или canvas без размеров</td>
                    <td>Откройте консоль (F12), проверьте ошибки.</td>
                </tr>
                <tr>
                    <td>Модель не грузится / 404 на <code>.xkt</code></td>
                    <td>Неверный путь в <code>index.json</code></td>
                    <td>Проверьте <code>src</code> в <code>models/.../index.json</code>. Откройте путь к <code>.xkt</code> напрямую в браузере.</td>
                </tr>
                <tr>
                    <td><code>index.json</code> не парсится (VPS)</td>
                    <td>Nginx отдаёт JSON как <code>text/plain</code></td>
                    <td>Добавьте <code>application/json json;</code> в секцию <code>types {}</code> конфига Nginx.</td>
                </tr>
                <tr>
                    <td><code>ERR_MODULE_NOT_FOUND</code> при конвертации</td>
                    <td>Проблемы с зависимостями loaders.gl</td>
                    <td>Запускайте конвертацию строго на Node.js 18.</td>
                </tr>
                <tr>
                    <td><code>out of memory</code> при конвертации</td>
                    <td>Недостаточно heap</td>
                    <td>Используйте <code>NODE_OPTIONS="--max-old-space-size=8192"</code> перед командой.</td>
                </tr>
                <tr>
                    <td>Дерево объектов пустое</td>
                    <td>XKT создан без метаданных</td>
                    <td>Убедитесь, что исходный IFC содержит метаданные и при конвертации не было ошибок парсинга.</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<article class="content-block">
    <h2>📚 Полезные ссылки</h2>
    <ul>
        <li><a href="https://xeokit.io/" target="_blank">xeokit Official Docs</a></li>
        <li><a href="https://github.com/xeokit/xeokit-convert" target="_blank">GitHub: xeokit-convert</a></li>
        <li><a href="https://github.com/xeokit/xeokit-bim-viewer" target="_blank">GitHub: xeokit-bim-viewer</a></li>
        <li><a href="https://www.gnu.org/licenses/agpl-3.0.html" target="_blank">Лицензия (AGPLv3)</a></li>
    </ul>
</article>

<nav class="nav-back">
    <a href="/" class="btn btn-nav c-bim">← Вернуться на главную</a>
    <a href="/xeo/app/" class="btn btn-action c-bim">Открыть Xeokit Viewer →</a>
</nav>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>