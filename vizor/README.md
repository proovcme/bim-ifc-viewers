# LES VIZOR Standalone

Static WebGL CAD/BIM viewer. Runs without LES backend, npm install, login, or internet access after the files are on the host.

## Run Locally

From the repository root:

```bash
python3 -m http.server 8095
```

Open:

```text
http://127.0.0.1:8095/vizor/?source=models/demo.cad_bim_graph.json
```

## Open Models

- Press `Демо` to load the bundled IFC demo set.
- Press `Добавить` to open a local `.ifc`, `.ifczip` or `.json`.
- Enter a hosted JSON or IFC path in the top input, for example:

```text
models/demo.cad_bim_graph.json
ifc-sample/Building-Hvac.ifc
```

## Contents

```text
index.html
assets/index.js
assets/index.css
assets/worker-*.mjs
fragments/worker.mjs
web-ifc/web-ifc.wasm
web-ifc/web-ifc-mt.wasm
web-ifc/web-ifc-node.wasm
models/demo.cad_bim_graph.json
ifc-sample/*.ifc
JSON/*.cad_bim_graph.json
```

## Hosting

Any static server is enough. For production hosting, make sure `.wasm` files are served as `application/wasm`.

Do not run by double-clicking `index.html`; browsers can block local workers and WASM in `file://` mode.
