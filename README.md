# BIM IFC Viewers

Коллекция независимых веб-вьюеров IFC моделей для сравнения технологий. Готовая статика для деплоя на shared-хостинг.

## Демо-сайт
**https://bim.ovc.me**

## Вьюеры

| Вьюер | Технологии | WASM | IFC локально | Статус |
|-------|-------------|------|---------------|--------|
| **OBC** | That Open Components + Three.js | Да | Да | ✅ Готово |
| **Speckle** | @speckle/viewer@2.20.0 | Нет (сеть) | Нет | ✅ Готово |
| **Xeokit** | @xeokit/xeokit-sdk | Да | Да | ✅ Готово |
| **IFC.js** | Vanilla JS + web-ifc-three | Да | Да | ✅ Готово |

## Быстрый старт

### Локальный запуск (разработка)
```bash
# OBC
cd obc && npm i && npm run dev

# Speckle
cd spekle && npm i && npm run dev

# Xeokit
cd xeo && npm i && npm run dev

# IFC.js (открыть index.php в PHP окружении)
cd ifcjs && php -S localhost:8080
```

### Сборка для продакшена
```bash
# В каждой папке:
npm run build
# Статика появится в dist/
```

## Структура проекта
```
bim-ifc-viewers/
├── obc/          # That Open Components вьюер
│   ├── src/main.ts
│   ├── public/
│   └── dist/        # Сборка
├── spekle/       # Speckle Viewer
│   ├── src/main.ts
│   ├── public/
│   └── dist/        # Сборка
├── xeo/          # Xeokit SDK вьюер
│   ├── src/main.ts
│   ├── public/
│   └── dist/        # Сборка
├── ifcjs/        # IFC.js вьюер (Vanilla JS)
│   ├── app.js
│   ├── index.php
│   └── models/
├── public_html/   # Сайт bim.ovc.me
│   ├── index.php
│   ├── header.php
│   ├── footer.php
│   └── frontend/ui-kit/
└── AUDIT.md      # Отчет по аудиту кода
```

## Особенности

### OBC (That Open Components)
- Нативная загрузка IFC через WebIFCHandler
- Three.js рендеринг
- Single-thread режим для shared-хостинга
- Кнопка сброса камеры

### Speckle Viewer
- Загрузка моделей через Stream API
- Облачное хранение моделей
- WASM подгружается по сети
- Статус загрузки

### Xeokit Viewer
- Высокопроизводительный WebGL рендеринг
- WebIFCLoaderPlugin для IFC
- Изоляция выделенных элементов
- Single-thread режим

### IFC.js Viewer
- Классический вьюер на чистом JS
- Three.js + web-ifc-three
- Расширенный функционал:
  - Дерево проекта
  - Измерения (рулетка)
  - Сечения (X/Y плоскости)
  - X-Ray режим
  - Поиск помещений
  - Скриншоты
  - Minecraft режим (секретный)

## Требования
- Node.js 18+
- PHP 7.4+ (для ifcjs и сайта)
- Современный браузер с поддержкой WebGL

## Деплой на shared-хостинг
1. Загрузить содержимое папок `obc/dist/`, `spekle/dist/`, `xeo/dist/` в соответствующие подпапки на хостинге
2. Загрузить `ifcjs/` как отдельный подраздел
3. Загрузить `public_html/` как корень сайта bim.ovc.me
4. Убедиться, что пути в `vite.config.ts` настроены на `base: './'`

## Известные проблемы
- Файл `test.ifc` (61 MB) превышает рекомендуемый размер GitHub (50 MB)
- Для оптимизации рекомендуется использовать Git LFS
- На shared-хостингах может потребоваться настройка CORS для WASM файлов

## Аудит кода
Подробный отчет по аудиту проекта доступен в [AUDIT.md](./AUDIT.md).

## Лицензия
MIT

## Ссылки
- GitHub: https://github.com/proovcme/bim-ifc-viewers
- Сайт: https://bim.ovc.me
- That Open Components: https://github.com/thatopen/components
- Speckle Viewer: https://github.com/specklesystems/speckle-viewer
- Xeokit SDK: https://github.com/xeokit/xeokit-sdk
- IFC.js: https://github.com/IFCjs/ifcjs