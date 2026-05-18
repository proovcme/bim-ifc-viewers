/* ============================================================
   Web Component: ovc-header (для демо-секции S2)
   ============================================================ */
class OvcHeader extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
      <div style="
        display:flex;justify-content:space-between;flex-wrap:wrap;
        background:rgba(20,23,26,.9);padding:12px 20px;
        border-radius:8px;border:1px solid var(--border);
        align-items:center;gap:14px;
      ">
        <div style="display:flex;gap:22px;align-items:center;flex-wrap:wrap;">
          <span style="font-family:var(--font-code);font-size:.92rem;font-weight:700;color:var(--text-main)">ovc.me>_</span>
          ${[['~/','Главная','var(--c-main)'],['cd rag','ИИ & Данные','var(--c-rag)'],['cd toc','Процессы','var(--c-toc)']].map(([cmd,lbl,c])=>`
            <a href="#" style="text-decoration:none;display:flex;flex-direction:column;gap:1px;opacity:1;transition:opacity .15s"
              onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'">
              <span style="font-family:var(--font-code);color:${c};font-weight:700;font-size:.82rem">${cmd}</span>
              <span style="font-size:.58rem;color:var(--text-dim);text-transform:uppercase;letter-spacing:.06em">${lbl}</span>
            </a>`).join('')}
        </div>
        <a href="#" style="text-decoration:none;font-family:var(--font-code);color:var(--c-tg);font-weight:700;font-size:.82rem;
          opacity:1;transition:opacity .15s;border:1px solid rgba(36,161,222,.3);padding:4px 12px;border-radius:5px"
          onmouseover="this.style.opacity='.65'" onmouseout="this.style.opacity='1'">ping @tg</a>
      </div>`;
  }
}
customElements.define('ovc-header', OvcHeader);

/* ============================================================
   Web Component: ovc-footer (для демо-секции S2)
   ============================================================ */
class OvcFooter extends HTMLElement {
  connectedCallback() {
    this.innerHTML = `
      <footer style="
        background:rgba(20,23,26,.9);padding:20px;margin-top:16px;
        border-radius:8px;border:1px solid var(--border);
      ">
        <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:12px;align-items:center;margin-bottom:12px;
          padding-bottom:12px;border-bottom:1px dashed var(--border)">
          <span style="font-family:var(--font-code);font-size:.88rem;font-weight:700;color:var(--text-muted)">
            root@<span style="color:var(--c-main)">ovc.me</span> ~ #
          </span>
          <a href="#" style="text-decoration:none;font-family:var(--font-code);color:var(--c-main);font-weight:600;
            font-size:.82rem;opacity:1;transition:opacity .15s"
            onmouseover="this.style.opacity='.6'" onmouseout="this.style.opacity='1'">cd /about</a>
        </div>
        <div style="font-family:var(--font-code);font-size:.72rem;color:var(--text-dim);
          display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px">
          <span>[Process completed] All systems operational.</span>
          <span>2026 © OVC.ME</span>
        </div>
      </footer>`;
  }
}
customElements.define('ovc-footer', OvcFooter);