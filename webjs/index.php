<?php
/**
 * BIM IFC Viewer v9.2.0
 */
$siteId    = 'bim';
$pageTitle = 'BIM IFC Viewer';

// COOP — нужен для изоляции вкладок, не блокирует внешние ресурсы
// COEP убран — он блокирует Метрику и worker без CORP на каждом ресурсе
// Однопоточный WASM работает без COEP, производительности достаточно
header('Cross-Origin-Opener-Policy: same-origin');

$modelsDir  = __DIR__ . '/models';
$ifcFiles   = glob($modelsDir . '/*.ifc') ?: [];
$modelsList = array_map('basename', $ifcFiles);

include __DIR__ . '/../header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@400;600;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="./style.css?v=<?php echo filemtime(__DIR__.'/style.css'); ?>">

<script>
    window.SERVER_MODELS = <?php echo json_encode($modelsList, JSON_UNESCAPED_UNICODE); ?>;
</script>

<style>
    html, body {
        height: 100vh !important;
        overflow: hidden !important;
        margin: 0; padding: 0;
        display: flex;
        flex-direction: column;
    }
    main.container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    #container {
        width: 100%;
        flex-grow: 1;
        position: relative;
        background: #f0ede6;
        overflow: hidden;
    }
    .site-footer { display: none !important; }
</style>

<div id="container">

    <div id="hardhat-overlay">
        <div id="hardhat-message">
            👷 Режим прораба<br>
            <span style="font-size:16px;font-weight:600;color:#fbbc04">WASD или Стрелочки — движение</span><br>
            <span style="font-size:12px;font-weight:400;opacity:.8">E/Q — вверх/вниз · повторный клик — выход</span>
        </div>
    </div>

    <div id="status">Инициализация…</div>

    <div class="toolbar">
        <button class="btn btn-primary" id="btn-add-file">📁 IFC</button>
        <button class="btn" id="btn-models">🗂️ МОДЕЛИ</button>
        <button class="btn" id="btn-bim-stats">📊 BIM</button>
        <button class="btn" id="toggle-tree-btn">🌳 ДЕРЕВО</button>
        <button class="btn" id="screenshot-btn">📸 СНИМОК</button>
        <button class="btn" id="btn-settings">⚙️</button>
        <button class="btn" id="btn-measure">📏 ЗАМЕР</button>
        <button class="btn" id="btn-spaces-toggle">📦 ПОМЕЩЕНИЯ</button>
        <button class="btn" id="btn-section">✂️ СЕЧЕНИЕ</button>
        <button class="btn" id="btn-cam">🎥 ОРТО</button>
        <button class="btn" id="btn-xray">🦴 X-RAY</button>
        <button class="btn btn-danger" id="btn-reset-scene">↺</button>
    </div>

    <div id="nav-panel" class="panel hidden">
        <div class="panel-header">Диспетчер сборки</div>
        <div id="space-search-wrapper" class="hidden" style="margin-bottom:12px;position:relative">
            <input type="text" id="space-search" placeholder="Поиск помещения…"
                   style="width:100%;padding:7px 10px;border:1px solid var(--border);border-radius:6px;font-size:12px;box-sizing:border-box">
            <div id="spaces-results" class="search-results hidden"></div>
        </div>
        <div id="local-models-list"></div>
    </div>

    <div id="settings-panel" class="panel hidden">
        <div class="panel-header">Настройки <span class="close-btn" id="btn-close-settings">×</span></div>
        <div class="section-control">
            <label style="font-size:11px;font-weight:700;color:#666">Качество рендера</label>
            <div style="display:flex;gap:4px;margin-top:6px">
                <button class="btn btn-mode mode-sport"   id="btn-mode-sport"   style="flex:1;font-size:10px">СПОРТ</button>
                <button class="btn btn-mode mode-balance" id="btn-mode-balance" style="flex:1;font-size:10px">БАЛАНС</button>
                <button class="btn btn-mode mode-beauty"  id="btn-mode-beauty"  style="flex:1;font-size:10px">КРАСОТА</button>
            </div>
        </div>
        <div class="section-control" style="margin-top:14px">
            <label style="font-size:11px;font-weight:700;color:#666">Цвет фона</label>
            <input type="color" id="input-bg-color" value="#f0ede6"
                   style="width:100%;height:32px;border:none;cursor:pointer;border-radius:4px;margin-top:5px">
        </div>
        <div class="section-control" style="margin-top:14px">
            <label style="font-size:11px;font-weight:700;color:#666">Чувствительность вращения</label>
            <input type="range" id="range-sens" min="0.1" max="2.0" step="0.1" value="1.0"
                   style="width:100%;margin-top:6px">
        </div>
        <div class="section-control" style="margin-top:14px;border-top:1px solid #eee;padding-top:12px">
            <label style="cursor:pointer;display:flex;align-items:center;gap:8px;font-size:11px">
                <input type="checkbox" id="check-gpu" checked> Максимальная мощь GPU
            </label>
        </div>
    </div>

    <div id="measure-panel" class="panel hidden">
        <div class="panel-header">Рулетка <span id="btn-clear-measure" style="float:right;cursor:pointer;color:#d93025">🗑️</span></div>
        <div id="measure-results"></div>
    </div>

    <div id="section-panel" class="panel hidden">
        <div class="panel-header">Сечения <span class="close-btn" id="btn-close-section">×</span></div>
        <div class="section-control">
            <label><input type="checkbox" id="check-sec-y"> Горизонталь (Y)</label>
            <input type="range" id="range-sec-y" class="hidden" step="0.1">
        </div>
        <div class="section-control" style="margin-top:12px">
            <label><input type="checkbox" id="check-sec-x"> Вертикаль (X)</label>
            <input type="range" id="range-sec-x" class="hidden" step="0.1">
        </div>
        <div style="margin-top:10px;border-top:1px solid #eee;padding-top:10px">
            <button class="btn btn-danger" id="btn-reset-section" style="width:100%;font-size:11px">↺ Сбросить</button>
        </div>
    </div>

    <div id="bim-stats-panel" class="panel hidden">
        <div class="panel-header">BIM Данные <span class="close-btn" id="btn-close-bim-stats">×</span></div>
        <!-- Вкладки: Статистика | Инженерные системы -->
        <div class="bim-tabs" id="bim-tabs">
            <button class="bim-tab active" data-tab="bim-stats-content">📊 Статистика</button>
            <button class="bim-tab" data-tab="bim-systems-tab">⚙️ Системы</button>
            <button class="bim-tab" data-tab="bim-spec-tab">📋 Спец</button>
        </div>
        <!-- Вкладка 1: Облако тегов -->
        <div id="bim-stats-content" class="bim-tab-panel">
            <div id="tag-cloud-container"><em style="font-size:11px;color:#999">Загрузите модель для анализа</em></div>
            <button class="btn" id="btn-reset-category" style="width:100%;margin-top:8px;font-size:11px;display:none">↺ Сбросить фильтр</button>
        </div>
        <!-- Вкладка 2: Инженерные системы -->
        <div id="bim-systems-tab" class="bim-tab-panel" style="display:none">
            <p class="sys-empty">Загрузите модель</p>
        </div>
        <!-- Вкладка 3: Спецификация -->
        <div id="bim-spec-tab" class="bim-tab-panel" style="display:none">
            <div style="display:flex;gap:6px;margin-bottom:8px">
                <button class="btn btn-primary" id="btn-parse-spec" style="flex:1;font-size:11px">↻ Считать</button>
                <button class="btn" id="btn-export-csv" style="font-size:11px">↓ CSV</button>
            </div>
            <div id="spec-table-wrap"><p class="sys-empty">Нажмите «Считать»</p></div>
        </div>
    </div>

    <div id="props-panel" class="panel hidden">
        <span class="close-btn" id="btn-close-props">×</span>
        <div class="panel-header">Свойства объекта</div>
        <div style="display:flex;gap:5px;margin-bottom:10px">
            <button class="btn btn-danger" style="flex:1" id="btn-hide-element">🚫 СКРЫТЬ</button>
            <button class="btn btn-primary" style="flex:1" id="btn-reset-visibility">👁️ ВСЕ</button>
        </div>
        <div id="props-content"></div>
    </div>

    <div id="tree-panel" class="hidden">
        <div class="panel-header">Структура проекта
            <button id="close-tree" style="float:right;background:none;border:none;cursor:pointer;font-size:16px;color:#999">×</button>
        </div>
        <div class="tree-search-container">
            <input type="text" id="tree-search" placeholder="Поиск по имени или ID…">
        </div>
        <div id="tree-content"></div>
    </div>

    <div id="nav-group">
        <button id="btn-secret-mode" style="background:none;border:none;font-size:22px;cursor:pointer;outline:none;margin-bottom:5px;opacity:.3">👷</button>
        <button class="btn btn-primary btn-round" id="home-btn">🏠</button>
        <div id="viewcube"></div>
    </div>

    <button id="help-btn-float">?</button>
    <div id="help-modal" class="panel hidden" style="top:auto;bottom:70px;right:20px;width:240px">
        <div class="panel-header">Управление <span class="close-btn" id="btn-close-help">×</span></div>
        <div style="font-size:11px;line-height:1.8">
            <b>ЛКМ</b> — вращение<br>
            <b>ПКМ / Shift+ЛКМ</b> — сдвиг<br>
            <b>Колесо</b> — зум<br>
            <b>2×ЛКМ</b> — выбор и свойства<br>
            <b>👷</b> — режим прораба (WASD)<br>
            <b>📏</b> — рулетка (одиночный клик)<br>
            <b>v9.2.0</b> — кликните для debug-лога
        </div>
    </div>

    <div id="app-version">v9.2.0</div>
    <div id="debug-log" class="hidden"></div>
</div>

<input type="file" id="file-input" accept=".ifc" multiple style="display:none">

<script type="importmap">
{
    "imports": {
        "three":               "./node_modules/three/build/three.module.js",
        "three/addons/":       "./node_modules/three/examples/jsm/",
        "three/examples/jsm/": "./node_modules/three/examples/jsm/",
        "web-ifc-three":       "./node_modules/web-ifc-three/IFCLoader.js",
        "web-ifc":             "./node_modules/web-ifc-three/node_modules/web-ifc/web-ifc-api.js"
    }
}
</script>
<script type="module" src="./app.js?v=<?php echo filemtime(__DIR__.'/app.js'); ?>"></script>

<script>
// Переключатель вкладок BIM-панели
document.getElementById('bim-tabs')?.addEventListener('click', e => {
    const btn = e.target.closest('.bim-tab');
    if (!btn) return;
    const target = btn.dataset.tab;
    document.querySelectorAll('#bim-tabs .bim-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.bim-tab-panel').forEach(p => {
        p.style.display = p.id === target ? '' : 'none';
    });
});
</script>

<script>
(function(){
    function chk(){ document.body.classList.toggle('is-mobile', window.innerWidth <= 1000); }
    chk();
    window.addEventListener('resize', chk);
})();
</script>

<?php include __DIR__ . '/../footer.php'; ?>
