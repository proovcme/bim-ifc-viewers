# BIM IFC Viewers

Standalone browser viewers for IFC, CAD/BIM JSON and openBIM experiments.

The main artifact in this repository is **LES VIZOR Standalone**: a ready-to-host
WebGL viewer that opens IFC files and lightweight `cad_bim_graph.json` payloads
without a backend, npm install, login, or cloud service.

[![Open VIZOR](https://img.shields.io/badge/Open-VIZOR-facc15?style=for-the-badge)](./vizor/)
[![Runtime](https://img.shields.io/badge/runtime-static%20webgl-38bdf8?style=for-the-badge)](./vizor/)
[![IFC](https://img.shields.io/badge/IFC-web--ifc-22c55e?style=for-the-badge)](./vizor/ifc-sample/)

![VIZOR standalone demo](docs/assets/vizor-demo.png)

## What Is Inside

| Path | Purpose |
|---|---|
| `vizor/` | LES VIZOR standalone viewer, ready for static hosting |
| `vizor/models/demo.cad_bim_graph.json` | Tiny CAD/BIM JSON demo scene |
| `vizor/ifc-sample/` | Public IFC sample models used by the demo buttons |
| `vizor/JSON/` | Small JSON projections for the BuildingSmart demo set |
| `webjs/` | Legacy IFC.js viewer experiment |
| `app/` | Legacy Xeokit viewer experiment |
| `*.php`, `config/` | Legacy shared-hosting pages for `bim.ovc.me` |

## VIZOR Standalone

VIZOR is a static WebGL viewer for quick BIM inspection:

- opens IFC from local files or hosted sample paths;
- opens canonical CAD/BIM JSON graphs;
- renders lightweight mesh, bbox, line, polyline, arc and text geometry;
- shows models, layers, structure, object properties and scene statistics;
- supports fit, isolate, hide/show, clipping planes and simple measurements;
- runs from ordinary static hosting: GitHub Pages, Caddy, nginx, Apache, shared hosting.

It ships the runtime files it needs:

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

## Quick Start

From the repository root:

```bash
python3 -m http.server 8095
```

Open:

```text
http://127.0.0.1:8095/vizor/?source=models/demo.cad_bim_graph.json
```

For IFC demo models, open `http://127.0.0.1:8095/vizor/` and press `Демо`, or
enter a direct model path such as:

```text
ifc-sample/Building-Hvac.ifc
```

## Hosting Notes

- Serve `.wasm` as `application/wasm` when your web server allows MIME mapping.
- Keep `assets/worker-*.mjs`, `fragments/worker.mjs` and `web-ifc/*.wasm` next to the bundle.
- Do not open `vizor/index.html` by double-clicking it. Browser security can block local workers and WASM.
- The viewer is intentionally backend-free. LES/RAG integration is disabled in this public standalone package.

## Technology

- That Open Components `3.4.x`
- Three.js `0.184.0`
- web-ifc `0.0.77`
- Static Vite bundle

## Related Viewers

This repository also keeps older IFC viewer experiments for comparison:

- IFC.js / web-ifc-three prototype in `webjs/`;
- Xeokit BIM Viewer build in `app/`;
- shared-hosting PHP pages for theory and demo navigation.

They are useful as historical comparison material. New standalone work should start from `vizor/`.

## License

MIT.
