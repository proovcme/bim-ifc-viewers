<?php
declare(strict_types=1);
$siteId          = 'bim';
$pageTitle       = 'Speckle — платформа данных для AEC — bim.ovc.me';
$pageDescription = 'Speckle: open-source платформа для обмена, версионирования и автоматизации проектных данных. Архитектура, коннекторы, сводные модели, API.';
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
.workflow-diagram { background: var(--bg-tertiary); padding: 1rem; border-radius: var(--r-md); font-family: var(--font-code); font-size: .85rem; overflow-x: auto; margin: 1rem 0; }
</style>

<main class="container">
<section class="page-hero">
    <div class="breadcrumbs" style="--theme-accent:var(--c-bim)">
        <a href="/">~/</a><span class="sep">/</span><a href="/">bim.ovc.me</a><span class="sep">/</span><span class="current">speckle</span>
    </div>
    <h1>Speckle: <span class="accent">данные без файлов</span></h1>
    <p class="page-hero__desc">Open-source платформа для обмена, версионирования и автоматизации проектных данных в AEC. Заменяет экспорт/импорт на живые потоки структурированных объектов.</p>
</section>

<article class="content-block">
    <h2>🔹 Что такое Speckle</h2>
    <p>Speckle — это open-source платформа для работы с проектными данными в сфере архитектуры, инженерии и строительства (AEC). Если коротко: это «Git для BIM-моделей» + «транспорт» для данных между программами.</p>
    <blockquote>
        Традиционный рабочий процесс: файл → экспорт → отправка → импорт → конфликт версий.<br>
        Процесс со Speckle: живая ссылка на данные → синхронизация в реальном времени → версионирование.
    </blockquote>
    <p>Speckle заменяет работу с файлами на работу с потоками данных (streams), которые можно отправлять, принимать, сравнивать и автоматизировать — без ручных экспортов/импортов.</p>
    <div class="content-block block-info">
        <div class="content-block-header">[INFO] Ключевая идея</div>
        <p>Speckle не является традиционным BIM-координатором (как Navisworks или Autodesk Construction Cloud). Это платформа-транспорт + система контроля версий. «Сводность» достигается за счёт одновременной загрузки или программной агрегации, а не одной кнопкой.</p>
    </div>
</article>

<article class="content-block">
    <h2>⚙️ Архитектура: как это работает</h2>
    
    <h3>Коннекторы (Connectors)</h3>
    <p>Speckle использует лёгкие плагины — <strong>коннекторы</strong> — которые встраиваются непосредственно в инструменты проектирования:</p>
    <ul>
        <li>Revit, Archicad, Rhino & Grasshopper, Blender, AutoCAD, SketchUp</li>
        <li>Dynamo, QGIS, Power BI, Python и другие</li>
    </ul>
    <p>Коннектор извлекает геометрию и метаданные из модели и публикует их в облако Speckle без создания промежуточных файлов.</p>

    <h3>Streams, Branches, Commits</h3>
    <p>Данные в Speckle организованы в три уровня:</p>
    <ul>
        <li><strong>Stream</strong> — проект или набор связанных данных (аналог репозитория).</li>
        <li><strong>Branch</strong> — ветка внутри стрима (например: <code>main/ar</code>, <code>main/str</code>, <code>main/mech</code>).</li>
        <li><strong>Commit</strong> — снимок состояния модели в определённый момент (аналог коммита в Git).</li>
    </ul>
    <p>Каждую дисциплину удобно хранить в отдельной ветке. Это позволяет сравнивать версии, откатываться и автоматизировать процессы.</p>

    <h3>Веб-вьюер</h3>
    <p>В браузере доступен 3D-просмотрщик с поддержкой:</p>
    <ul>
        <li>Слои, прозрачность, сечения</li>
        <li>Комментарии и режим «следования» за коллегой</li>
        <li>Одновременная загрузка нескольких моделей из одного или разных стримов</li>
    </ul>
</article>

<article class="content-block">
    <h2>🧩 Работа с несколькими моделями</h2>
    <p>Speckle не имеет кнопки «Собрать сводную модель из файлов». Сборка реализуется через рабочий процесс:</p>
    
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th>Задача</th>
                    <th>Как реализуется в Speckle</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Загрузка нескольких моделей</td>
                    <td>Через коннекторы отправляете модели в один Stream. Каждую дисциплину — в отдельную Branch (<code>main/ar</code>, <code>main/str</code>, <code>main/mech</code>).</td>
                </tr>
                <tr>
                    <td>Одновременный просмотр</td>
                    <td>В веб-Viewer подключаете несколько моделей из одного или разных Streams. Они отображаются в общей сцене с поддержкой слоёв и сечений.</td>
                </tr>
                <tr>
                    <td>Программное объединение</td>
                    <td>Через Speckle Automate, Python/C# SDK или кастомные скрипты настраиваете пайплайн, который подтягивает последние коммиты, объединяет геометрию и сохраняет результат как новую модель.</td>
                </tr>
                <tr>
                    <td>Проверка коллизий</td>
                    <td>Speckle передаёт структурированные данные. Для clash detection используют связку: Speckle → Dynamo / Rhino.Compute / Grasshopper / внешние BIM-платформы, либо кастомный скрипт на базе Speckle API.</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3>Типовой рабочий процесс для сводной модели</h3>
    <ol>
        <li>Отправьте модели из разных программ в один Stream (каждая в свою ветку).</li>
        <li>В Viewer откройте несколько моделей одновременно для визуальной координации.</li>
        <li>Если нужна автоматическая сборка: настройте Speckle Automate или напишите скрипт (Python/TypeScript), который:
            <ul>
                <li>Подтягивает последние коммиты по тегам/веткам</li>
                <li>Объединяет объекты, фильтрует по категориям</li>
                <li>Сохраняет результат в новую ветку <code>main/consolidated</code></li>
            </ul>
        </li>
        <li>Для проверки коллизий используйте связку Speckle + Grasshopper/Dynamo или внешние инструменты, принимающие данные через Speckle API.</li>
    </ol>

    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Ограничения</div>
        <ul>
            <li>В веб-интерфейсе нет одной кнопки «загрузить 5 файлов и автоматически слить в один».</li>
            <li>Speckle хранит модели как независимые коммиты, а не как единый файл.</li>
            <li>Для полноценной BIM-координации (отчёты по коллизиям, статусы, согласования) обычно используют Speckle как источник данных + внешнюю систему управления.</li>
        </ul>
    </div>
</article>

<article class="content-block">
    <h2>🎯 Для каких задач используют</h2>
    
    <div class="workflow-diagram">
graph LR
    A[Архитектор в Revit] -->|Отправляет модель| S(Speckle Cloud)
    B[Инженер в Rhino] -->|Получает данные| S
    C[Аналитик в Power BI] -->|Строит дашборд| S
    D[Заказчик в браузере] -->|Смотрит 3D + комментирует| S
    E[Скрипт Automate] -->|Проверяет коллизии| S
    S -->|Возвращает результат| A & B & C & D & E
    </div>

    <p><strong>Типичные сценарии:</strong></p>
    <ul>
        <li>🔄 <strong>Междисциплинарная координация:</strong> архитектор, конструктор и инженер обмениваются актуальными данными без экспорта IFC/DWG.</li>
        <li>📈 <strong>Бизнес-аналитика:</strong> автоматический подсчёт площадей, объёмов, стоимости, CO₂ на основе модели.</li>
        <li>✅ <strong>Валидация моделей:</strong> скрипт проверяет, что все элементы имеют нужные параметры, перед отправкой заказчику.</li>
        <li>👁️ <strong>Презентации:</strong> интерактивная 3D-ссылка для клиента — без установки ПО.</li>
        <li>🗃️ <strong>Архив и знания:</strong> все версии моделей хранятся централизованно, можно найти, кто и когда изменил параметр.</li>
    </ul>
</article>

<article class="content-block">
    <h2>✨ Что умеет платформа</h2>
    
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th>Категория</th>
                    <th>Возможности</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>🔗 Интеграции</td>
                    <td>Коннекторы для: Revit, Rhino/Grasshopper, Blender, SketchUp, AutoCAD, Archicad, Dynamo, QGIS, Power BI, Python и др.</td>
                </tr>
                <tr>
                    <td>🌐 Веб-просмотр</td>
                    <td>3D-вьюер в браузере: слои, сечения, комментарии, режим «следования», федерация моделей.</td>
                </tr>
                <tr>
                    <td>🔄 Версионирование</td>
                    <td>Полная история изменений, сравнение версий (diff), ветвление, возможность «отката».</td>
                </tr>
                <tr>
                    <td>🤖 Автоматизация</td>
                    <td>Speckle Automate — CI/CD-платформа для запуска скриптов (Python/TS) при обновлении моделей: проверка стандартов, генерация отчётов, экспорт.</td>
                </tr>
                <tr>
                    <td>📊 Аналитика</td>
                    <td>Speckle Intelligence: дашборды для отслеживания прогресса, стоимости, углеродного следа; интеграция с Power BI, Snowflake, Databricks.</td>
                </tr>
                <tr>
                    <td>🔐 Контроль данных</td>
                    <td>Вы владеете своими данными. Поддержка SOC 2, гибкие настройки доступа, возможность хостинга on-premise.</td>
                </tr>
                <tr>
                    <td>🧩 Расширяемость</td>
                    <td>Открытый API, SDK (Python, C#, JS), возможность строить свои приложения поверх платформы.</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<article class="content-block">
    <h2>⚖️ Плюсы и ограничения</h2>
    
    <div class="terminal-table-wrapper">
        <table class="terminal-table">
            <thead>
                <tr>
                    <th>✅ Преимущества</th>
                    <th>⚠️ Ограничения</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Не зависит от вендоров (open-source)</td>
                    <td>Не заменяет полноценный BIM-координатор (Clash detection — через скрипты/внешние инструменты)</td>
                </tr>
                <tr>
                    <td>Работает с геометрией + метаданными + связями</td>
                    <td>Требует настройки рабочих процессов (не «установил и готово»)</td>
                </tr>
                <tr>
                    <td>Живая синхронизация вместо файлов</td>
                    <td>Для сложных автоматизаций нужны навыки программирования (Python/JS)</td>
                </tr>
                <tr>
                    <td>Масштабируется от фрилансера до корпорации</td>
                    <td>Веб-вьюер — для просмотра, не для редактирования геометрии</td>
                </tr>
                <tr>
                    <td>Вы контролируете данные и инфраструктуру</td>
                    <td>Бесплатный облачный хостинг имеет лимиты; для enterprise — платные тарифы</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<article class="content-block">
    <h2>🚀 Как начать</h2>
    
    <h3>Вариант 1: Облако (speckle.xyz) — быстро</h3>
    <ol>
        <li>Зарегистрируйтесь на <a href="https://speckle.xyz" target="_blank">speckle.xyz</a> (бесплатный тариф).</li>
        <li>Установите коннектор для вашего инструмента:
            <ul>
                <li>Revit: через <a href="https://apps.autodesk.com" target="_blank">Autodesk App Store</a> или <code>speckle install</code></li>
                <li>Rhino: <code>PackageManager</code> → поиск «Speckle»</li>
                <li>Blender: <code>Preferences → Add-ons → Install from disk</code></li>
            </ul>
        </li>
        <li>Откройте панель Speckle в инструменте → авторизуйтесь → выберите/создайте Stream → Publish.</li>
        <li>Модель появится в веб-интерфейсе: <code>https://speckle.xyz/streams/[stream_id]</code>.</li>
    </ol>

    <h3>Вариант 2: Локальный сервер (self-hosted)</h3>
    <p>Speckle можно развернуть на своём сервере через Docker.</p>
    <pre><code># Минимальный docker-compose.yml
version: '3.8'
services:
  speckle-server:
    image: speckle/speckle-server:latest
    ports:
      - "3000:80"
    environment:
      - SPECKLE_SERVER_URL=http://localhost:3000
      - POSTGRES_USER=speckle
      - POSTGRES_PASSWORD=your_password
    volumes:
      - speckle_data:/data
volumes:
  speckle_data:</code></pre>
    <p>После запуска:</p>
    <ol>
        <li>Откройте <code>http://localhost:3000</code> и создайте аккаунт.</li>
        <li>В коннекторах укажите URL вашего сервера вместо <code>speckle.xyz</code>.</li>
        <li>Все данные остаются внутри вашего периметра.</li>
    </ol>
    <div class="content-block block-warn">
        <div class="content-block-header">[WARN] Важно</div>
        <p>Для self-hosted требуется базовая настройка PostgreSQL, Redis и SMTP. Полная инструкция: <a href="https://github.com/specklesystems/speckle-server" target="_blank">GitHub: speckle-server</a>.</p>
    </div>

    <h3>Вариант 3: Загрузка файлов без коннектора</h3>
    <p>Если у вас нет коннектора для вашего инструмента, Speckle поддерживает drag & drop загрузку файлов форматов <code>.ifc</code>, <code>.obj</code>, <code>.stl</code> прямо в веб-интерфейс.</p>
    <p>Файл конвертируется в структурированные объекты и становится доступен для просмотра и скачивания.</p>
</article>

<article class="content-block">
    <h2>🔌 Developer Platform: API и SDK</h2>
    <p>Speckle предоставляет открытое API и SDK для Python, .NET и JavaScript для программного доступа к данным.</p>
    
    <h3>Пример на Python: получить объекты из стрима</h3>
    <pre><code>from specklepy.api.client import SpeckleClient
from specklepy.api.credentials import get_default_account

client = SpeckleClient(host="speckle.xyz")
account = get_default_account()
client.authenticate(token=account.token)

stream = client.stream.get("stream_id")
objects = client.object.get_many(stream_id=stream.id, object_ids=["obj_id1", "obj_id2"])
for obj in objects:
    print(obj.speckle_type, obj.displayValue)</code></pre>

    <h3>Пример на JavaScript: подписаться на обновления</h3>
    <pre><code>import { StreamWrapper } from '@speckle/objects'

const wrapper = new StreamWrapper('https://speckle.xyz/streams/abc123/branches/main')
const client = await wrapper.getSpeckleClient()

// Подписка на новые коммиты
client.stream.on('commit_created', (commit) => {
  console.log('Новая версия:', commit.id)
  // Загрузить и обработать
})</code></pre>

    <p>Документация по схемам данных и примерам: <a href="https://speckle.guide/dev/" target="_blank">speckle.guide/dev</a>.</p>
</article>

<article class="content-block">
    <h2>🆘 Решение проблем</h2>
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
                    <td>Коннектор не находит аккаунт</td>
                    <td>Не авторизован в приложении</td>
                    <td>Откройте панель Speckle в инструменте → Log In → браузер</td>
                </tr>
                <tr>
                    <td>Модель не отображается в Viewer</td>
                    <td>Не выбран коммит или ветка</td>
                    <td>В интерфейсе стрима выберите Branch → Commit → нажмите Load</td>
                </tr>
                <tr>
                    <td>Нет метаданных в веб-вьюере</td>
                    <td>Исходная модель не содержит свойств</td>
                    <td>Проверьте экспорт: в Revit включите «Параметры» → «Экспорт параметров»</td>
                </tr>
                <tr>
                    <td>Скрипт Automate не запускается</td>
                    <td>Неверный триггер или права доступа</td>
                    <td>Проверьте вкладку Automate → Triggers → Permissions</td>
                </tr>
                <tr>
                    <td>API возвращает 401</td>
                    <td>Истёк или неверный токен</td>
                    <td>Сгенерируйте новый токен в настройках аккаунта → API Tokens</td>
                </tr>
                <tr>
                    <td>Большие модели грузятся медленно</td>
                    <td>Ограничения бесплатного тарифа</td>
                    <td>Разбейте модель на дисциплины или рассмотрите платный тариф / self-hosted</td>
                </tr>
            </tbody>
        </table>
    </div>
</article>

<article class="content-block">
    <h2>📚 Полезные ссылки</h2>
    <ul>
        <li><a href="https://speckle.systems" target="_blank">Официальный сайт</a></li>
        <li><a href="https://speckle.guide/" target="_blank">Документация пользователя</a></li>
        <li><a href="https://speckle.guide/dev/" target="_blank">Документация разработчика</a></li>
        <li><a href="https://github.com/specklesystems" target="_blank">GitHub: организация</a></li>
        <li><a href="https://github.com/specklesystems/speckle-server" target="_blank">GitHub: сервер (self-hosted)</a></li>
        <li><a href="https://speckle.systems/pricing" target="_blank">Тарифы и лимиты</a></li>
    </ul>
</article>

<div class="content-block" style="border-top: 1px solid var(--border); padding-top: 1rem; color: var(--text-muted); font-size: .85rem;">
    <p><strong>Проект:</strong> bim.ovc.me — браузерный IFC-вьювер без серверной части</p>
    <p><strong>Интеграция:</strong> Speckle используется как источник данных для веб-вьюеров и автоматизации. Для визуализации в bim.ovc.me данные экспортируются в IFC → XKT / web-ifc.</p>
</div>

<nav class="nav-back">
    <a href="/" class="btn btn-nav c-bim">← Вернуться на главную</a>
    <a href="/speckle-viewer.php" class="btn btn-action c-bim">Открыть Speckle Viewer →</a>
</nav>
</main>
<?php include __DIR__ . '/../../public_html/footer.php'; ?>