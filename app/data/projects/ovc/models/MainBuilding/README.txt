# 🏗️ Сюда помещается ваша 3D-модель

## Файлы в этой папке:

```
MainBuilding/
├── index.json       ← описание модели (уже создан)
└── geometry.xkt     ← ← ВАША МОДЕЛЬ ДОЛЖНА БЫТЬ ЗДЕСЬ!
```

## Как добавить модель:

### 1. Конвертируйте IFC → XKT (на локальной машине с Node.js):

```bash
# Установите конвертер (один раз)
npm install -g @xeokit/xeokit-convert

# Конвертируйте модель
node convert2xkt.js -s ваша_модель.ifc -o geometry.xkt -l
```

### 2. Скопируйте результат:

```
СКОПИРУЙТЕ: /путь/к/вашему/файлу/geometry.xkt
ВСТАВЬТЕ СЮДА: /app/data/projects/MyProject/models/MainBuilding/geometry.xkt
```

### 3. Проверьте index.json:

Файл `index.json` уже настроен правильно:
```json
{
  "id": "MainBuilding",
  "name": "Главный корпус",
  "src": "./geometry.xkt",
  "edges": true
}
```

> ✅ Убедитесь что имя файла в `src` точно совпадает с именем вашего .xkt файла!

## После загрузки на сервер:

Откройте в браузере:
```
https://ваш-домен.com/xeo/app/index.html?projectId=MyProject
```

## Если модель не отображается:

1. Проверьте консоль браузера (F12 → Console) на ошибки
2. Убедитесь что файл .xkt загружен на сервер (откройте прямой линк)
3. Проверьте что `.htaccess` содержит: `AddType application/octet-stream .xkt`

---

💡 **Совет:** Имя файла `geometry.xkt` можно изменить, но тогда нужно обновить путь в `index.json`
