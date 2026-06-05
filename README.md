# BIM IFC Viewers

Коллекция веб-просмотрщиков IFC/BIM-моделей для сравнения подходов: **LES VIZOR**, IFC.js, Xeokit, Speckle Viewer и теоретические страницы по библиотекам. Репозиторий рассчитан на простой деплой на shared hosting, GitHub Pages или любой статический сервер.

**Демо-сайт:** https://bim.ovc.me

**GitHub Pages для VIZOR:** https://proovcme.github.io/bim-ifc-viewers/vizor/

![LES VIZOR standalone demo](docs/assets/vizor-demo.png)

## Что Здесь Есть

| Вьюер | Путь | Технологии | Локальный IFC | JSON CAD/BIM | Сервер | Статус |
|---|---|---|---|---|---|---|
| **LES VIZOR** | `vizor/` | That Open Components, Three.js, web-ifc | Да | Да | Не нужен | Основной автономный вьюер |
| **IFC.js Viewer** | `webjs/` | Three.js, web-ifc-three | Да | Нет | Не нужен | Рабочее старое демо |
| **Xeokit App** | `app/` | Xeokit BIM Viewer, XKT | Через подготовленные XKT | Нет | Не нужен | Быстрый вьюер для подготовленных моделей |
| **Speckle Viewer** | `speckle-viewer.php` | Speckle Viewer | Нет | Нет | Нужен источник Speckle | Демо Speckle-подхода |
| **Теория IFC.js** | `ifcjs.php` | Статья/обзор | - | - | - | Навигационная страница |
| **Теория OBC** | `obc.php` | Статья/обзор | - | - | - | Навигационная страница |
| **Теория Xeokit** | `xeokit.php` | Статья/обзор | - | - | - | Навигационная страница |
| **Теория Speckle** | `speckle.php` | Статья/обзор | - | - | - | Навигационная страница |

## LES VIZOR

VIZOR добавлен как новый автономный вьюер для быстрого BIM/CAD просмотра без сервера и без установки npm-зависимостей на рабочем месте.

Что умеет:

- открывать `.ifc`, `.ifczip` и canonical `cad_bim_graph.json`;
- показывать несколько моделей в одной сцене;
- отображать слои, структуру, свойства выбранного объекта и статистику сцены;
- рендерить mesh, bbox, line, polyline, arc и text geometry;
- делать fit/isolate/hide/show, сечения и простые замеры;
- работать с обычного статического хостинга.

Состав:

```text
vizor/
├── index.html
├── assets/index.js
├── assets/index.css
├── assets/worker-*.mjs
├── fragments/worker.mjs
├── web-ifc/*.wasm
├── models/demo.cad_bim_graph.json
├── ifc-sample/*.ifc
└── JSON/*.cad_bim_graph.json
```

VIZOR здесь публичный и автономный. LES/RAG-интеграция из private-контура в этом standalone-пакете отключена.

## Быстрый Запуск

Из корня репозитория:

```bash
python3 -m http.server 8095
```

Открыть VIZOR:

```text
http://127.0.0.1:8095/vizor/
```

Открыть минимальный JSON demo:

```text
http://127.0.0.1:8095/vizor/?source=models/demo.cad_bim_graph.json
```

Открыть IFC sample через верхнее поле:

```text
ifc-sample/Building-Hvac.ifc
```

## Структура Репозитория

```text
bim-ifc-viewers/
├── vizor/                 # LES VIZOR, автономный вьюер
├── webjs/                 # IFC.js / web-ifc-three demo
├── app/                   # Xeokit viewer build and XKT data
├── config/bim_layout.json # Карточки сайта bim.ovc.me
├── *.php                  # Shared-hosting страницы и навигация
├── docs/assets/           # Скриншоты для GitHub README
└── frontend/ui-kit/       # Legacy UI-kit для сайта
```

## Деплой

### GitHub Pages

GitHub Pages включен из ветки `main`, путь `/`.

VIZOR доступен по адресу:

```text
https://proovcme.github.io/bim-ifc-viewers/vizor/
```

### Shared Hosting / Apache

Загрузить содержимое репозитория в корень сайта. В `.htaccess` уже добавлены MIME-типы для BIM runtime:

```apache
AddType application/wasm .wasm
AddType text/javascript .mjs
AddType application/javascript .js
AddType application/json .json
AddType application/octet-stream .ifc .ifczip
```

Важно не открывать `vizor/index.html` двойным кликом из файловой системы: браузер может заблокировать WASM и workers. Нужен любой локальный или удалённый HTTP server.

## Сравнение Подходов

| Подход | Сильная сторона | Ограничение |
|---|---|---|
| VIZOR / OBC | Готовый standalone runtime, IFC + CAD/BIM JSON, удобная сцена | Бандл крупный, нужен WebGL |
| IFC.js | Простая классика для экспериментов с IFC в браузере | Меньше готовой UI-обвязки |
| Xeokit | Быстрый просмотр подготовленных XKT | Требуется конвертация IFC/XKT pipeline |
| Speckle | Совместная работа и облачный обмен BIM-данными | Не является полностью offline/local viewer |

## Технологии

- That Open Components `3.4.x`
- Three.js `0.184.0`
- web-ifc `0.0.77`
- IFC.js / web-ifc-three, старое рабочее демо
- Xeokit BIM Viewer, старое рабочее демо
- PHP/shared-hosting навигация сайта

## Лицензия

MIT.
