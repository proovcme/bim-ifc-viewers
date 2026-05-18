/* ============================================================
   OVC Trigger — полный массив расшифровок
   Структура: [O][V][C] : [M][E]
   Никакой "настоящей" расшифровки нет — это и есть пасхалка.
   ============================================================ */
const expansions = [
  // — RAG & AI —
  "Open Vector Context : Model Environment",
  "Output Validation Core : Machine Expertise",
  "Оптимизация Векторных Цепочек : Машинная Экспертиза",
  "Оценка Вычислительных Центров : Моделирование и Эффективность",

  // — TOC —
  "Optimized Value Chain : Management Execution",
  "Overcoming Vicious Constraints : Metrics & Efficiency",
  "Order Vs Chaos : Management Engine",
  "Обход Вредных Циклов : Менеджмент и Эффективность",
  "Оптимизация Внутренних Цепей : Методы Экспертизы",

  // — BIM —
  "Operational Virtual Construction : Master Engineering",
  "Object Validity Control : Modeling Engine",
  "Объектно-Виртуальный Центр : Масштабируемая Емкость",

  // — Пасхалки: технотреп, серьёзно по форме —
  "Orphaned Vectors Contextualized : Memory Exhausted",
  "Остаточный Векторный Контекст : Минимальная Энтропия",
  "Obvious Violation Concealed : Metrics Excellent",
  "Оптимизировано Вчера Совещанием : Метрика Ещё Считается",
  "Object Vanished Completely : Model Exists",
  "Объект Висит Честно : Модель Едет",

  // — Мета-пасхалка: вылезает если очень повезёт —
  "Означает Всё Что Захочешь : Можешь Ещё Раз",
  "Obviously Very Creative : Meaning Escapes",
];

// Счётчик кликов — для мета-пасхалки при 10+ кликах
let clickCount = 0;
let lastIdx = 0;

function updateExpansion() {
  const el = document.getElementById('ovc-expansion');
  if (!el) return;

  clickCount++;

  // Мета-пасхалка на 10-м клике подряд
  if (clickCount === 10) {
    el.style.opacity = '0';
    setTimeout(() => {
      el.textContent = "Означает Всё Что Захочешь : Можешь Ещё Раз";
      el.style.opacity = '1';
      clickCount = 0;
    }, 200);
    return;
  }

  el.style.opacity = '0';
  setTimeout(() => {
    let idx;
    // Пасхалки (индексы 12–18) появляются с вероятностью ~15%
    const showEaster = Math.random() < 0.15;
    const pool = showEaster
      ? expansions.slice(12, 19)   // только пасхалки
      : expansions.slice(0, 12);   // только серьёзные
    do { idx = Math.floor(Math.random() * pool.length); }
    while (pool[idx] === el.textContent && pool.length > 1);
    lastIdx = idx;
    el.textContent = pool[idx];
    el.style.opacity = '1';
  }, 200);
}

/* ============================================================
   Copy code
   ============================================================ */
function copyCode(btn) {
  const code = btn.closest('.code-wrapper').querySelector('code');
  if (!code) return;
  navigator.clipboard.writeText(code.innerText).then(() => {
    btn.textContent = 'Copied!';
    btn.classList.add('copied');
    setTimeout(() => { btn.textContent = 'Copy'; btn.classList.remove('copied'); }, 2000);
  });
}

  /* ============================================================
     Management Mode Toggle
     ============================================================ */
  const modeBtn = document.getElementById('mode-toggle');
  const modeCmd = document.getElementById('mode-toggle-cmd');
  const modeLbl = document.getElementById('mode-toggle-lbl');

  const applyMode = (isManagement) => {
    document.body.classList.toggle('management-mode', isManagement);
    modeLbl.textContent = isManagement ? 'Роль: Менеджер' : 'Роль: Разработчик';
    localStorage.setItem('ovc-mode', isManagement ? 'management' : 'tech');
  };

  // Восстановить режим из localStorage
  const savedMode = localStorage.getItem('ovc-mode');
  if (savedMode === 'management') applyMode(true);

  if (modeBtn) {
    modeBtn.addEventListener('click', () => {
      applyMode(!document.body.classList.contains('management-mode'));
    });
  }

  /* ============================================================
     Init
     ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  /* Основной trigger в branding header */
  const trigger = document.getElementById('ovc-trigger');
  if (trigger) {
    trigger.addEventListener('click', updateExpansion);
    trigger.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); updateExpansion(); }
    });
  }

  /* Демо-trigger в секции 11 — независимый, свой счётчик */
  const triggerDemo = document.getElementById('ovc-trigger-demo');
  const expansionDemo = document.getElementById('ovc-expansion-demo');
  let demoClicks = 0;
  if (triggerDemo && expansionDemo) {
    const updateDemo = () => {
      demoClicks++;
      if (demoClicks === 10) {
        expansionDemo.style.opacity = '0';
        setTimeout(() => { expansionDemo.textContent = "Означает Всё Что Захочешь : Можешь Ещё Раз"; expansionDemo.style.opacity = '1'; demoClicks = 0; }, 200);
        return;
      }
      expansionDemo.style.opacity = '0';
      const showEaster = Math.random() < 0.15;
      const pool = showEaster ? expansions.slice(12, 19) : expansions.slice(0, 12);
      setTimeout(() => { expansionDemo.textContent = pool[Math.floor(Math.random() * pool.length)]; expansionDemo.style.opacity = '1'; }, 200);
    };
    triggerDemo.addEventListener('click', updateDemo);
    triggerDemo.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); updateDemo(); } });
  }

  /* Переключатель темы */
  const themeBtn = document.getElementById('theme-toggle');
  const themeCmd = document.getElementById('theme-toggle-cmd');
  const themeLbl = document.getElementById('theme-toggle-lbl');

  const applyTheme = (isLight) => {
    document.body.classList.toggle('light-theme', isLight);
    themeCmd.textContent = isLight ? 'theme --dark' : 'theme --light';
    themeLbl.textContent = isLight ? 'Тёмная тема' : 'Светлая тема';
    localStorage.setItem('ovc-theme', isLight ? 'light' : 'dark');
  };

  // Восстановить тему из localStorage
  const savedTheme = localStorage.getItem('ovc-theme');
  if (savedTheme === 'light') applyTheme(true);

  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      applyTheme(!document.body.classList.contains('light-theme'));
    });
  }
  const sections = document.querySelectorAll('[id^="s"]');
  const navLinks = document.querySelectorAll('.side-nav a');
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        navLinks.forEach(a => a.classList.remove('active'));
        const lnk = document.querySelector(`.side-nav a[href="#${e.target.id}"]`);
        if (lnk) lnk.classList.add('active');
      }
    });
  }, { threshold:0.35 });
  sections.forEach(s => io.observe(s));
});