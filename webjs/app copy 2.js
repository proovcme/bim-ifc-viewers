/**
 * BIM IFC Viewer v9.2.0
 * three@r149 + web-ifc-three@0.0.126
 * Константы из web-ifc@0.0.44 (ESM). WASM → внутренний web-ifc@0.0.39.
 */

import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { IFCLoader } from 'web-ifc-three';
import {
    IFCSPACE,
    IFCWALL, IFCWALLSTANDARDCASE,
    IFCDOOR, IFCWINDOW,
    IFCSLAB, IFCCOLUMN, IFCBEAM, IFCMEMBER,
    IFCSTAIR, IFCROOF,
    IFCBUILDINGELEMENTPROXY, IFCFURNISHINGELEMENT,
    IFCFLOWCONTROLLER, IFCFLOWMOVINGDEVICE, IFCFLOWTERMINAL,
    IFCFLOWSTORAGEDEVICE, IFCENERGYCONVERSIONDEVICE,
    // Блок 3 — Инженерные системы (верифицировано по web-ifc@0.0.44)
    IFCSYSTEM,
    // ОВиК — воздух
    IFCAIRTERMINAL, IFCAIRTERMINALBOX, IFCDAMPER,
    IFCDUCTFITTING, IFCDUCTSEGMENT, IFCDUCTSILENCER,
    IFCFAN, IFCFILTER, IFCAIRTOAIRHEATRECOVERY,
    // ОВиК — тепло/холод
    IFCBOILER, IFCCHILLER, IFCCOIL, IFCCOOLEDBEAM,
    IFCCOOLINGTOWER, IFCEVAPORATIVECOOLER, IFCHEATEXCHANGER,
    IFCPUMP, IFCSPACEHEATER, IFCVALVE,
    // ВК/ОВиК — трубопроводы
    IFCPIPESEGMENT, IFCPIPEFITTING,
    // ВК — сантехника и канализация
    IFCSANITARYTERMINAL, IFCWASTETERMINAL,
    IFCFIRESUPPRESSIONTERMINAL, IFCINTERCEPTOR,
    IFCSTACKTERMINAL, IFCLIQUIDTERMINAL,
    // ЭОМ — кабельные сети
    IFCCABLESEGMENT, IFCCABLEFITTING,
    IFCCABLECARRIERSEGMENT, IFCCABLECARRIERFITTING,
    IFCJUNCTIONBOX, IFCELECTRICDISTRIBUTIONBOARD,
    IFCPROTECTIVEDEVICE, IFCTRANSFORMER, IFCSWITCHINGDEVICE,
    // ЭОМ — оборудование
    IFCELECTRICMOTOR, IFCELECTRICGENERATOR,
    IFCELECTRICAPPLIANCE, IFCELECTRICFLOWSTORAGEDEVICE,
    IFCELECTRICFLOWTREATMENTDEVICE,
    IFCLIGHTFIXTURE, IFCOUTLET, IFCSENSOR, IFCACTUATOR,
    // MEP базовые (fallback)
    IFCFLOWSEGMENT, IFCFLOWFITTING,
    IFCFLOWINSTRUMENT, IFCFLOWMETER, IFCDISTRIBUTIONFLOWELEMENT,
} from 'web-ifc';

const VERSION = '9.3.0';
const WASM_PATH = './node_modules/web-ifc-three/node_modules/web-ifc/';

const CFG = {
    WALK_SPEED: 0.8, XRAY_OPACITY: 0.12, ORIGIN_THRESHOLD: 300,
    NEAR: 0.05, FAR: 50000, SS_SCALE: 1.5, MAX_PIXEL_RATIO: 2,
};
const COLOR = {
    HIGHLIGHT: 0x174ea6, MEASURE_P1: 0x1a73e8,
    MEASURE_P2: 0xd93025, MEASURE_LINE: 0xfbbc04, CAT_FOCUS: 0xffffff,
};

const BIM_CATS = [
    { key:'wall',   types:[IFCWALL, IFCWALLSTANDARDCASE],                                    label:'Стены',        icon:'🧱', g:'arch'  },
    { key:'slab',   types:[IFCSLAB],                                                          label:'Перекрытия',   icon:'⬜', g:'arch'  },
    { key:'column', types:[IFCCOLUMN],                                                        label:'Колонны',      icon:'🏛',  g:'arch'  },
    { key:'beam',   types:[IFCBEAM, IFCMEMBER],                                              label:'Балки/Элем.',  icon:'〰', g:'arch'  },
    { key:'door',   types:[IFCDOOR],                                                          label:'Двери',        icon:'🚪', g:'arch'  },
    { key:'window', types:[IFCWINDOW],                                                        label:'Окна',         icon:'🪟', g:'arch'  },
    { key:'stair',  types:[IFCSTAIR],                                                         label:'Лестницы',     icon:'🪜', g:'arch'  },
    { key:'roof',   types:[IFCROOF],                                                          label:'Кровля',       icon:'🏠', g:'arch'  },
    { key:'pipe',   types:[IFCPIPESEGMENT, IFCPIPEFITTING],                                 label:'Трубопровод',  icon:'🔧', g:'mep'   },
    { key:'mep',    types:[IFCFLOWCONTROLLER, IFCFLOWMOVINGDEVICE, IFCFLOWTERMINAL,
                            IFCFLOWSTORAGEDEVICE, IFCENERGYCONVERSIONDEVICE],                label:'Инж.системы',  icon:'⚙️', g:'mep'  },
    { key:'equip',  types:[IFCBUILDINGELEMENTPROXY],                                         label:'Оборудование', icon:'🔩', g:'mep'   },
    { key:'furn',   types:[IFCFURNISHINGELEMENT],                                            label:'Мебель',       icon:'🛋',  g:'arch'  },
    { key:'space',  types:[IFCSPACE],                                                         label:'Помещения',    icon:'📦', g:'space' },
];

const IFC_RU = {
    IFCPROJECT:'Проект', IFCSITE:'Участок', IFCBUILDING:'Здание',
    IFCBUILDINGSTOREY:'Этаж', IFCWALL:'Стена', IFCWALLSTANDARDCASE:'Стена',
    IFCWINDOW:'Окно', IFCDOOR:'Дверь', IFCSLAB:'Перекрытие',
    IFCCOLUMN:'Колонна', IFCBEAM:'Балка', IFCSTAIR:'Лестница',
    IFCPIPESEGMENT:'Труба', IFCPIPEFITTING:'Фитинг',
    IFCBUILDINGELEMENTPROXY:'Оборудование', IFCROOF:'Кровля',
    IFCSPACE:'Помещение', IFCMEMBER:'Элемент',
    IFCFLOWCONTROLLER:'Регулятор', IFCFLOWMOVINGDEVICE:'Насос/Вент.',
    IFCFLOWTERMINAL:'Концевой элемент', IFCFLOWSTORAGEDEVICE:'Ёмкость',
    IFCENERGYCONVERSIONDEVICE:'Теплообменник', IFCFURNISHINGELEMENT:'Мебель',
};

// ─── Блок 3: карта разделов ИС ─────────────────────────────────────────────
const MEP_SECTIONS = {
    ovic: {
        label: 'ОВиК', icon: '💨',
        types: new Set([
            IFCDUCTSEGMENT, IFCDUCTFITTING, IFCDUCTSILENCER,
            IFCAIRTERMINAL, IFCAIRTERMINALBOX, IFCDAMPER,
            IFCFAN, IFCFILTER, IFCAIRTOAIRHEATRECOVERY,
            IFCBOILER, IFCCHILLER, IFCCOIL, IFCCOOLEDBEAM,
            IFCCOOLINGTOWER, IFCEVAPORATIVECOOLER, IFCHEATEXCHANGER,
            IFCPUMP, IFCSPACEHEATER, IFCVALVE,
            IFCPIPESEGMENT, IFCPIPEFITTING,
        ]),
    },
    vk: {
        label: 'ВК', icon: '🚿',
        types: new Set([
            IFCPIPESEGMENT, IFCPIPEFITTING, IFCVALVE,
            IFCSANITARYTERMINAL, IFCWASTETERMINAL,
            IFCFIRESUPPRESSIONTERMINAL, IFCINTERCEPTOR,
            IFCSTACKTERMINAL, IFCLIQUIDTERMINAL,
        ]),
    },
    eom: {
        label: 'ЭОМ', icon: '⚡',
        types: new Set([
            IFCCABLESEGMENT, IFCCABLEFITTING,
            IFCCABLECARRIERSEGMENT, IFCCABLECARRIERFITTING,
            IFCJUNCTIONBOX, IFCELECTRICDISTRIBUTIONBOARD,
            IFCPROTECTIVEDEVICE, IFCTRANSFORMER, IFCSWITCHINGDEVICE,
            IFCELECTRICMOTOR, IFCELECTRICGENERATOR,
            IFCELECTRICAPPLIANCE, IFCELECTRICFLOWSTORAGEDEVICE,
            IFCELECTRICFLOWTREATMENTDEVICE,
            IFCLIGHTFIXTURE, IFCOUTLET, IFCSENSOR, IFCACTUATOR,
        ]),
    },
    mep_other: {
        label: 'MEP прочее', icon: '🔧',
        types: new Set([
            IFCFLOWSEGMENT, IFCFLOWFITTING, IFCFLOWTERMINAL,
            IFCFLOWCONTROLLER, IFCFLOWMOVINGDEVICE,
            IFCFLOWSTORAGEDEVICE, IFCFLOWINSTRUMENT,
            IFCFLOWMETER, IFCDISTRIBUTIONFLOWELEMENT,
        ]),
    },
};
// Обратный индекс typeCode → sectionKey (первый раздел в порядке объявления побеждает)
const TYPE_TO_SECTION = {};
for (const [key, sec] of Object.entries(MEP_SECTIONS)) {
    for (const t of sec.types) { if (!(t in TYPE_TO_SECTION)) TYPE_TO_SECTION[t] = key; }
}

const $ = id => document.getElementById(id);
function safeDispose(n){ n&&n.geometry&&n.geometry.dispose(); const m=Array.isArray(n&&n.material)?n.material:[n&&n.material]; m.forEach(x=>x&&x.dispose()); }
function deepDispose(o){ o&&o.traverse(n=>{if(n.isMesh)safeDispose(n);}); }
function collectAllIds(n){ const ids=[n.expressID]; if(n.children&&n.children.length)n.children.forEach(c=>ids.push(...collectAllIds(c))); return ids; }
async function getLabel(mgr,modelID,expressID,type){
    try{ const p=await mgr.getItemProperties(modelID,expressID); const n=p&&(p.Name&&p.Name.value||p.LongName&&p.LongName.value); if(n&&n!=='undefined')return String(n); }catch(_){}
    return IFC_RU[(type||'').toUpperCase()]||type||('ID:'+expressID);
}
async function tryIFC(fn,fallback){ if(fallback===undefined)fallback=null; try{return await fn();}catch(e){return fallback;} }
function fmtQty(q){
    const t=(key,unit)=>{ const raw=q[key]; if(raw==null)return null; const v=raw.value!==undefined?raw.value:raw; const n=parseFloat(v); return isNaN(n)?null:n.toFixed(3)+unit; };
    return t('LengthValue',' м')||t('AreaValue',' м²')||t('VolumeValue',' м³')||t('WeightValue',' кг')||t('CountValue',' шт')
        ||(q.NominalValue!=null?String(q.NominalValue.value!==undefined?q.NominalValue.value:q.NominalValue):null)||'—';
}
function _isQset(ps){ return ps&&(ps.type==='IFCELEMENTQUANTITY'||(ps.Name&&ps.Name.value&&(ps.Name.value.startsWith('Qto_')||ps.Name.value.startsWith('BaseQuantities')))); }
function _psName(ps){ return (ps.Name&&ps.Name.value)?ps.Name.value:'PropertySet'; }

class BIMApp {
    constructor() {
        this.scene=null; this.camera=null; this.renderer=null; this.controls=null; this.loader=null;
        this.cubeScene=null; this.cubeCamera=null; this.cubeRenderer=null;
        this.models=new Map(); this.sel={id:null,mid:null}; this.hidden=new Set();
        this.xray=false; this.ortho=false; this.secMode=false; this.measMode=false; this.walkMode=false;
        this.needsRender=true;
        this.planeY=new THREE.Plane(new THREE.Vector3(0,-1,0),0);
        this.planeX=new THREE.Plane(new THREE.Vector3(-1,0,0),0);
        this.bbox=new THREE.Box3();
        this.measPts=[]; this.measGroup=new THREE.Group(); this.measGroup.renderOrder=999;
        this.bimData={}; this.activeCat=null; this._catMat=null; this._prevXray=false;
        this.spaces=[];
        this.rc=new THREE.Raycaster(); this.mouse=new THREE.Vector2(); this.pDown=new THREE.Vector2();
        this.hlMat=new THREE.MeshBasicMaterial({color:COLOR.HIGHLIGHT,depthTest:false,transparent:true,opacity:0.5});
        this.el=this._dom();
        this._init();
    }

    _dom(){
        return {
            container:$('container'),status:$('status'),fileInput:$('file-input'),bgColor:$('input-bg-color'),checkGpu:$('check-gpu'),
            checkY:$('check-sec-y'),rangeY:$('range-sec-y'),checkX:$('check-sec-x'),rangeX:$('range-sec-x'),
            propsPanel:$('props-panel'),propsContent:$('props-content'),
            panelSection:$('section-panel'),panelModels:$('nav-panel'),panelSettings:$('settings-panel'),
            panelBim:$('bim-stats-panel'),panelMeasure:$('measure-panel'),measureRes:$('measure-results'),
            modelsList:$('local-models-list'),tagCloud:$('tag-cloud-container'),btnResetCat:$('btn-reset-category'),
            hardhatOv:$('hardhat-overlay'),hardhatMsg:$('hardhat-message'),
            btnXray:$('btn-xray'),btnCam:$('btn-cam'),btnSection:$('btn-section'),btnSpaces:$('btn-spaces-toggle'),
            btnMeasure:$('btn-measure'),btnModels:$('btn-models'),btnBim:$('btn-bim-stats'),
            btnSecret:$('btn-secret-mode'),btnSettings:$('btn-settings'),
        };
    }

    async _init(){
        this.loader=new IFCLoader();
        try{ await this.loader.ifcManager.setWasmPath(WASM_PATH); this._log('WASM OK'); }
        catch(e){ this._log('WASM ERR: '+e.message); this._status('WASM error'); }

        const bgVal=(this.el.bgColor&&this.el.bgColor.value)||'#f0ede6';
        this.scene=new THREE.Scene(); this.scene.background=new THREE.Color(bgVal); this.scene.add(this.measGroup);
        this.ambient=new THREE.AmbientLight(0xffffff,1.3);
        this.dirLight=new THREE.DirectionalLight(0xffffff,0.5); this.dirLight.position.set(20,50,20);
        this.scene.add(this.ambient,this.dirLight);

        const vp=this._vp();
        this.camera=new THREE.PerspectiveCamera(45,vp.w/vp.h,CFG.NEAR,CFG.FAR);
        this.camera.position.set(0,25,60);

        const hiperf=localStorage.getItem('bim_gpu_perf')!=='false';
        if(this.el.checkGpu)this.el.checkGpu.checked=hiperf;
        this.renderer=new THREE.WebGLRenderer({antialias:true,alpha:true,powerPreference:hiperf?'high-performance':'default'});
        this.renderer.setSize(vp.w,vp.h);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio,CFG.MAX_PIXEL_RATIO));
        this.renderer.localClippingEnabled=false;
        this.el.container.appendChild(this.renderer.domElement);

        this.controls=new OrbitControls(this.camera,this.renderer.domElement);
        this.controls.enableDamping=true;
        this.controls.addEventListener('change',()=>{this.needsRender=true;});

        this.pivot=new THREE.Mesh(new THREE.SphereGeometry(0.06,12,12),new THREE.MeshBasicMaterial({color:0xff00ff,depthTest:false,transparent:true,opacity:0.85}));
        this.pivot.visible=false; this.scene.add(this.pivot);
        this.controls.addEventListener('start',()=>{this.pivot.position.copy(this.controls.target);this.pivot.visible=true;this.needsRender=true;});
        this.controls.addEventListener('end',()=>{this.pivot.visible=false;this.needsRender=true;});

        this._initCube(); this._setQuality(0); this._bindEvents();
        this._renderServerModels(); this._status('Выберите модель'); this._animate();
        window.bimApp=this;
        const ver=$('app-version'); if(ver)ver.textContent='v'+VERSION;
        this._log('BIM Viewer v'+VERSION);
    }

    _vp(){ return {w:(this.el.container&&this.el.container.clientWidth)||window.innerWidth,h:(this.el.container&&this.el.container.clientHeight)||window.innerHeight}; }

    _animate(){
        requestAnimationFrame(()=>this._animate());
        const moved=this.controls.update();
        if(moved||this.needsRender){
            this.renderer.render(this.scene,this.camera);
            if(this.cubeRenderer){ this.cubeCamera.position.copy(this.camera.position).sub(this.controls.target).setLength(6); this.cubeCamera.lookAt(0,0,0); this.cubeRenderer.render(this.cubeScene,this.cubeCamera); }
            this.needsRender=false;
        }
    }

    _status(t){ if(this.el.status)this.el.status.textContent=t; }
    _log(msg){ const el=$('debug-log'); if(!el)return; el.innerHTML+='['+new Date().toLocaleTimeString()+'] '+msg+'\n'; el.scrollTop=el.scrollHeight; }

    _bindEvents(){
        window.addEventListener('resize',()=>this._onResize());
        window.addEventListener('dblclick',e=>this._onDblClick(e));
        window.addEventListener('keydown',e=>{if(this.walkMode)this._walk(e);});
        this.renderer.domElement.addEventListener('pointerdown',e=>this.pDown.set(e.clientX,e.clientY));
        this.renderer.domElement.addEventListener('pointerup',e=>{
            if(e.button!==0||!this.measMode)return;
            if(Math.hypot(e.clientX-this.pDown.x,e.clientY-this.pDown.y)>6)return;
            this._castRay(e,hits=>{if(hits.length)this._addMeasPt(hits[0].point);});
        });
        this.el.propsContent&&this.el.propsContent.addEventListener('click',async e=>{
            const ic=e.target.closest('.copy-icon'); if(!ic)return;
            try{ await navigator.clipboard.writeText(ic.dataset.value||''); const o=ic.textContent; ic.textContent='✅'; setTimeout(()=>{ic.textContent=o;},1400); }catch(_){}
        });

        const on=(id,fn)=>{const el=$(id);if(el)el.addEventListener('click',fn);};
        on('btn-add-file',()=>this.el.fileInput&&this.el.fileInput.click());
        this.el.fileInput&&this.el.fileInput.addEventListener('change',e=>this._handleUpload(e));
        on('btn-reset-scene',()=>location.reload());
        on('home-btn',()=>this._fitCamera());
        on('btn-close-props',()=>this._hideProps());
        on('btn-hide-element',()=>this._hideSelected());
        on('btn-reset-visibility',()=>this._resetVis());
        on('btn-cam',()=>this._toggleCamera());
        on('btn-xray',()=>this._toggleXray());
        on('btn-spaces-toggle',()=>this._toggleSpaces());
        on('btn-measure',()=>this._toggleMeasure());
        on('btn-clear-measure',()=>this._clearMeasure());
        on('btn-section',()=>this._toggleSection());
        on('btn-close-section',()=>{if(this.secMode)this._toggleSection();});
        on('btn-secret-mode',()=>this._toggleWalk());
        on('screenshot-btn',()=>this._screenshot());
        on('app-version',()=>{const d=$('debug-log');if(d)d.classList.toggle('hidden');});
        on('help-btn-float',()=>{const m=$('help-modal');if(m)m.classList.remove('hidden');});
        on('btn-close-help',()=>{const m=$('help-modal');if(m)m.classList.add('hidden');});
        on('btn-reset-section',()=>this._resetSection());
        on('btn-reset-category',()=>this._resetCat());
        on('btn-mode-sport',()=>this._setQuality(0));
        on('btn-mode-balance',()=>this._setQuality(1));
        on('btn-mode-beauty',()=>this._setQuality(2));
        on('btn-models',()=>{this.el.panelModels&&this.el.panelModels.classList.toggle('hidden');this.el.btnModels&&this.el.btnModels.classList.toggle('btn-active');});
        on('btn-bim-stats',()=>{
            this.el.panelBim&&this.el.panelBim.classList.toggle('hidden');
            this.el.btnBim&&this.el.btnBim.classList.toggle('btn-active');
            if(this.models.size&&this.el.panelBim&&!this.el.panelBim.classList.contains('hidden')&&!Object.keys(this.bimData).length)this._parseBIM();
        });
        on('btn-close-bim-stats',()=>{this.el.panelBim&&this.el.panelBim.classList.add('hidden');this.el.btnBim&&this.el.btnBim.classList.remove('btn-active');});
        on('btn-settings',()=>{this.el.panelSettings&&this.el.panelSettings.classList.toggle('hidden');this.el.btnSettings&&this.el.btnSettings.classList.toggle('btn-active');});
        on('btn-close-settings',()=>{this.el.panelSettings&&this.el.panelSettings.classList.add('hidden');this.el.btnSettings&&this.el.btnSettings.classList.remove('btn-active');});

        this.el.checkY&&this.el.checkY.addEventListener('change',()=>this._applyClip());
        this.el.checkX&&this.el.checkX.addEventListener('change',()=>this._applyClip());
        this.el.rangeY&&this.el.rangeY.addEventListener('input',e=>{this.planeY.constant=+e.target.value;this.needsRender=true;});
        this.el.rangeX&&this.el.rangeX.addEventListener('input',e=>{this.planeX.constant=+e.target.value;this.needsRender=true;});
        this.el.bgColor&&this.el.bgColor.addEventListener('input',e=>{this.scene.background=new THREE.Color(e.target.value);this.needsRender=true;});
        const sens=$('range-sens'); if(sens)sens.addEventListener('input',e=>{this.controls.rotateSpeed=+e.target.value;});
        this.el.checkGpu&&this.el.checkGpu.addEventListener('change',e=>{localStorage.setItem('bim_gpu_perf',e.target.checked);this._status('GPU сохранено — перезагрузите');});

        const treePanel=$('tree-panel'),treeBtn=$('toggle-tree-btn');
        const updTree=()=>{if(!treeBtn)return;const open=treePanel&&!treePanel.classList.contains('hidden');treeBtn.style.background=open?'#ffc107':'';treeBtn.style.color=open?'#000':'';treeBtn.style.borderColor=open?'#ffc107':'';};
        if(treeBtn)treeBtn.addEventListener('click',()=>{treePanel&&treePanel.classList.toggle('hidden');updTree();});
        on('close-tree',()=>{treePanel&&treePanel.classList.add('hidden');updTree();});
        if(this.el.btnSecret){this.el.btnSecret.style.opacity='0.3';this.el.btnSecret.addEventListener('mouseenter',()=>{this.el.btnSecret.style.opacity='0.8';});this.el.btnSecret.addEventListener('mouseleave',()=>{this.el.btnSecret.style.opacity=this.walkMode?'1':'0.3';});}
    }

    _onResize(){
        const vp=this._vp();
        if(this.ortho){const a=vp.w/vp.h,t=this.camera.top;this.camera.left=-t*a;this.camera.right=t*a;}
        else{this.camera.aspect=vp.w/vp.h;}
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(vp.w,vp.h);this.needsRender=true;
    }

    _castRay(e,cb){
        const rect=this.renderer.domElement.getBoundingClientRect();
        this.mouse.x=((e.clientX-rect.left)/rect.width)*2-1;
        this.mouse.y=-((e.clientY-rect.top)/rect.height)*2+1;
        this.rc.setFromCamera(this.mouse,this.camera);
        const vis=[]; this.models.forEach(m=>{if(m.visible)vis.push(m);});
        cb(this._clipFilter(this.rc.intersectObjects(vis,true)));
    }
    _clipFilter(hits){
        if(!this.secMode)return hits;
        return hits.filter(h=>{
            if(this.el.checkY&&this.el.checkY.checked&&this.planeY.distanceToPoint(h.point)<-0.01)return false;
            if(this.el.checkX&&this.el.checkX.checked&&this.planeX.distanceToPoint(h.point)<-0.01)return false;
            return true;
        });
    }

    _onDblClick(e){
        if(e.target.tagName!=='CANVAS'||this.measMode)return;
        this._castRay(e,async hits=>{
            this._clearHL();
            if(!hits.length){this._hideProps();return;}
            const h=hits[0],mid=h.object.modelID;
            const id=this.loader.ifcManager.getExpressId(h.object.geometry,h.faceIndex);
            if(id==null)return;
            this.sel={id,mid};
            await tryIFC(()=>this.loader.ifcManager.createSubset({modelID:mid,ids:[id],material:this.hlMat,scene:this.scene,removePrevious:true,customId:'hl'}));
            this.controls.target.copy(h.point);this.controls.update();this.needsRender=true;
            await this._showProps(mid,id);
        });
    }
    _clearHL(){
        if(this.sel.mid!==null){tryIFC(()=>this.loader.ifcManager.removeSubset(this.sel.mid,this.hlMat,'hl'));this.sel={id:null,mid:null};this.needsRender=true;}
    }

    async _showProps(mid,id){
        if(this.el.propsContent)this.el.propsContent.innerHTML='<div class="props-loading">⏳ Загрузка…</div>';
        this.el.propsPanel&&this.el.propsPanel.classList.remove('hidden');
        const mgr=this.loader.ifcManager;
        const [props,allSets,typeSets,matSets]=await Promise.all([
            tryIFC(()=>mgr.getItemProperties(mid,id)),
            tryIFC(()=>mgr.getPropertySets(mid,id,true),[]),
            tryIFC(()=>mgr.getTypeProperties(mid,id,true),[]),
            tryIFC(()=>mgr.getMaterialsProperties(mid,id,true),[]),
        ]);

        let tabMain='<div class="prop-group-title">Идентификация</div>'+this._propRow('ExpressID',id);
        if(props){
            ['Name','LongName','ObjectType','Tag','Description'].forEach(k=>{
                if(!props[k])return;
                const v=String(props[k].value!==undefined?props[k].value:props[k]);
                if(v&&v!=='null'&&v!=='undefined')tabMain+=this._propRow(k,v);
            });
            if(props.GlobalId&&props.GlobalId.value)tabMain+=this._propRow('GlobalId',props.GlobalId.value);
        }
        (allSets||[]).filter(ps=>ps&&ps.HasProperties&&ps.HasProperties.length&&!_isQset(ps)).forEach(ps=>{
            tabMain+='<div class="prop-group-title">'+_psName(ps)+'</div>';
            ps.HasProperties.forEach(p=>{if(!p||!p.Name)return;const v=p.NominalValue!=null?String(p.NominalValue.value!==undefined?p.NominalValue.value:p.NominalValue):'—';tabMain+=this._propRow(p.Name.value,v);});
        });

        const qsets=[...(allSets||[]).filter(_isQset),...(typeSets||[]).filter(_isQset)];
        let tabQto=qsets.length?'':'<div class="props-empty">Количества (Qto) не заданы в модели</div>';
        qsets.forEach(qs=>{tabQto+='<div class="prop-group-title">'+_psName(qs)+'</div>';(qs.Quantities||qs.HasProperties||[]).forEach(q=>{if(!q||!q.Name)return;tabQto+=this._propRow(q.Name.value,fmtQty(q));});});

        let tabType='';
        if(props&&props.ObjectType&&props.ObjectType.value)tabType='<div class="prop-group-title">Тип элемента</div>'+this._propRow('ObjectType',props.ObjectType.value);
        const tsets=(typeSets||[]).filter(ps=>ps&&ps.HasProperties&&ps.HasProperties.length&&!_isQset(ps));
        tsets.forEach(ts=>{tabType+='<div class="prop-group-title">'+_psName(ts)+'</div>';ts.HasProperties.forEach(p=>{if(!p||!p.Name)return;const v=p.NominalValue!=null?String(p.NominalValue.value!==undefined?p.NominalValue.value:p.NominalValue):'—';tabType+=this._propRow(p.Name.value,v);});});
        if(!tabType)tabType='<div class="props-empty">Типовые свойства не заданы</div>';

        let tabMat='';
        (matSets||[]).forEach((mat,i)=>{
            if(!mat)return;
            const mn=(mat.Name&&mat.Name.value)?mat.Name.value:(mat.ForLayerSet&&mat.ForLayerSet.LayerSetName&&mat.ForLayerSet.LayerSetName.value)?mat.ForLayerSet.LayerSetName.value:'Материал '+(i+1);
            tabMat+='<div class="prop-group-title">'+mn+'</div>';
            if(mat.ForLayerSet&&mat.ForLayerSet.MaterialLayers){mat.ForLayerSet.MaterialLayers.forEach(l=>{if(!l)return;const ln=l.Material&&l.Material.Name&&l.Material.Name.value?l.Material.Name.value:'Слой';const th=l.LayerThickness&&l.LayerThickness.value!==undefined?(parseFloat(l.LayerThickness.value)*1000).toFixed(0)+' мм':'—';tabMat+=this._propRow(ln,th);});}
            else if(mat.Materials){mat.Materials.forEach(m=>{if(m&&m.Name)tabMat+=this._propRow(m.Name.value,'—');});}
            else if(mat.Name&&mat.Name.value){tabMat+=this._propRow('Наименование',mat.Name.value);if(mat.Description&&mat.Description.value)tabMat+=this._propRow('Описание',mat.Description.value);}
        });
        if(!tabMat)tabMat='<div class="props-empty">Материалы не заданы в модели</div>';

        const hasQto=qsets.length>0,hasType=tsets.length>0||(props&&props.ObjectType&&props.ObjectType.value),hasMat=(matSets||[]).length>0;
        const tBtn=(k,l,a)=>'<button class="props-tab'+(a?' active':'')+'" data-tab="'+k+'">'+l+'</button>';
        const html='<div class="props-tabs">'+tBtn('main','📋 Свойства',true)+(hasQto?tBtn('qto','📐 Количества',false):'')+(hasType?tBtn('type','🏷️ Тип',false):'')+(hasMat?tBtn('mat','🧱 Материалы',false):'')+'</div>'
            +'<div class="props-tab-body" data-pane="main">'+tabMain+'</div>'
            +(hasQto?'<div class="props-tab-body hidden" data-pane="qto">'+tabQto+'</div>':'')
            +(hasType?'<div class="props-tab-body hidden" data-pane="type">'+tabType+'</div>':'')
            +(hasMat?'<div class="props-tab-body hidden" data-pane="mat">'+tabMat+'</div>':'');

        if(this.el.propsContent){
            this.el.propsContent.innerHTML=html;
            this.el.propsContent.querySelectorAll('.props-tab').forEach(btn=>{
                btn.addEventListener('click',()=>{
                    this.el.propsContent.querySelectorAll('.props-tab').forEach(b=>b.classList.remove('active'));
                    this.el.propsContent.querySelectorAll('.props-tab-body').forEach(p=>p.classList.add('hidden'));
                    btn.classList.add('active');
                    const pane=this.el.propsContent.querySelector('[data-pane="'+btn.dataset.tab+'"]');if(pane)pane.classList.remove('hidden');
                });
            });
        }
    }
    _propRow(name,val){
        const s=String(val).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        return '<div class="prop-row"><span class="prop-name">'+name+'</span><span class="prop-val">'+s+' <span class="copy-icon" data-value="'+s+'" title="Копировать">📋</span></span></div>';
    }
    _hideProps(){this.el.propsPanel&&this.el.propsPanel.classList.add('hidden');this._clearHL();}

    _renderServerModels(){
        const list=this.el.modelsList; if(!list)return;
        list.querySelectorAll('.srv-btn').forEach(b=>b.remove());
        const sm=window.SERVER_MODELS||[];
        if(!sm.length){list.innerHTML='<div style="font-size:11px;color:#999;padding:5px 0">Нет .ifc в /models</div>';return;}
        sm.forEach(name=>{
            if(this.models.has(name))return;
            const btn=document.createElement('button');
            btn.className='btn btn-primary srv-btn';btn.style.cssText='margin:4px 0;width:100%;text-align:left';
            btn.textContent='📂 '+name;
            btn.addEventListener('click',()=>this._loadServer(name,btn));
            list.prepend(btn);
        });
    }
    async _loadServer(name,btn){
        if(btn){btn.disabled=true;btn.textContent='⏳ '+name;}
        this._status('Загрузка '+name+'...');
        try{
            const m=await this.loader.loadAsync('./models/'+name);
            m.name=name;this._centerModel(m);this.scene.add(m);this.models.set(name,m);
            if(btn)btn.remove();
            this._fitCamera();this._renderList();
            this._status(name+' загружена ('+this.models.size+' мод.)');this.needsRender=true;
            this._buildTree(m.modelID).catch(e=>this._log('Tree: '+e.message));
            this._parseBIM();
            this.el.panelBim&&this.el.panelBim.classList.remove('hidden');
            this.el.btnBim&&this.el.btnBim.classList.add('btn-active');
        }catch(err){
            if(btn){btn.disabled=false;btn.textContent='📂 '+name;}
            this._status('Ошибка: '+name);this._log('ERROR '+name+': '+err.message);console.error(err);
        }
    }
    async _handleUpload(event){
        const files=Array.from(event.target.files);if(!files.length)return;
        for(const file of files){
            this._status('⏳ '+file.name);await new Promise(r=>setTimeout(r,30));
            try{
                const url=URL.createObjectURL(new Blob([await file.arrayBuffer()]));
                const m=await this.loader.loadAsync(url);
                m.name=file.name;this._centerModel(m);this.scene.add(m);this.models.set(file.name,m);
                URL.revokeObjectURL(url);
                if(this.secMode){this._calcBbox();this._applyClip();}
                this._renderList();this.needsRender=true;
            }catch(err){this._status('Ошибка: '+file.name);this._log('Upload: '+err.message);}
        }
        if(this.spaces.length)await this._indexSpaces();
        this._fitCamera();this._status('Моделей: '+this.models.size);event.target.value='';
    }
    _renderList(){
        const list=this.el.modelsList;if(!list)return;
        list.querySelectorAll('.model-item').forEach(e=>e.remove());
        this.models.forEach((m,name)=>{
            const item=document.createElement('div');item.className='model-item';
            item.innerHTML='<div class="model-name" title="'+name+'" style="'+(m.visible?'':'text-decoration:line-through;opacity:.5')+'">'+name+'</div>'
                +'<div class="model-actions"><button class="icon-btn btn-vis">'+(m.visible?'👁️':'🕶️')+'</button><button class="icon-btn btn-del" style="color:#d93025">🗑️</button></div>';
            item.querySelector('.btn-vis').onclick=()=>this._toggleVis(name);
            item.querySelector('.btn-del').onclick=()=>this._unload(name);
            list.appendChild(item);
        });
    }
    _toggleVis(name){const m=this.models.get(name);if(!m)return;m.visible=!m.visible;this._clearHL();this._renderList();this.needsRender=true;}
    _unload(name){
        const m=this.models.get(name);if(!m)return;
        tryIFC(()=>this.loader.ifcManager.close(m.modelID,this.scene));
        this.scene.remove(m);deepDispose(m);this.models.delete(name);
        this._clearHL();this._hideProps();this._renderList();this._renderServerModels();
        if(this.secMode)this._calcBbox();this._status('Удалено: '+name);this.needsRender=true;
    }
    _centerModel(m){
        const box=new THREE.Box3().setFromObject(m);if(box.isEmpty())return;
        const c=box.getCenter(new THREE.Vector3()),dxz=Math.sqrt(c.x*c.x+c.z*c.z);
        if(dxz>CFG.ORIGIN_THRESHOLD){m.position.x-=c.x;m.position.z-=c.z;this._log('Авто-центровка: '+dxz.toFixed(0)+' ед.');}
    }
    _fitCamera(){
        const box=new THREE.Box3();this.models.forEach(m=>{if(m.visible)box.expandByObject(m);});if(box.isEmpty())return;
        const c=box.getCenter(new THREE.Vector3()),s=box.getSize(new THREE.Vector3()),r=Math.max(s.x,s.y,s.z)||10;
        this.controls.target.copy(c);this.camera.position.set(c.x+r*1.6,c.y+r,c.z+r*1.6);this.camera.lookAt(c);this.controls.update();this.needsRender=true;
    }
    _toggleCamera(){
        this.ortho=!this.ortho;this.el.btnCam&&this.el.btnCam.classList.toggle('active',this.ortho);
        const vp=this._vp(),a=vp.w/vp.h,pos=this.camera.position.clone(),tgt=this.controls.target.clone();
        if(this.ortho){const d=pos.distanceTo(tgt)*Math.tan(45*Math.PI/360);this.camera=new THREE.OrthographicCamera(-d*a,d*a,d,-d,CFG.NEAR,CFG.FAR);}
        else{this.camera=new THREE.PerspectiveCamera(45,a,CFG.NEAR,CFG.FAR);}
        this.camera.position.copy(pos);this.camera.lookAt(tgt);this.controls.object=this.camera;this.controls.target.copy(tgt);this.controls.update();this.needsRender=true;
    }
    _toggleXray(){this.xray=!this.xray;this.el.btnXray&&this.el.btnXray.classList.toggle('active',this.xray);this._applyXray(this.xray);this.needsRender=true;}
    _applyXray(on){
        this.models.forEach(m=>m.traverse(n=>{
            if(!n.isMesh)return;
            const mats=Array.isArray(n.material)?n.material:[n.material];
            mats.forEach(mat=>{if(!mat||mat===this.hlMat||mat===this._catMat)return;mat.transparent=on;mat.opacity=on?CFG.XRAY_OPACITY:1.0;mat.needsUpdate=true;});
        }));
    }
    _toggleSection(){
        this.secMode=!this.secMode;this.el.btnSection&&this.el.btnSection.classList.toggle('btn-active',this.secMode);
        this.renderer.localClippingEnabled=this.secMode;this._forceMatUpdate();
        if(this.secMode){this._calcBbox();this.el.panelSection&&this.el.panelSection.classList.remove('hidden');}
        else{this.el.panelSection&&this.el.panelSection.classList.add('hidden');if(this.el.checkY)this.el.checkY.checked=false;if(this.el.checkX)this.el.checkX.checked=false;}
        this._applyClip();
    }
    _resetSection(){if(this.el.checkY)this.el.checkY.checked=false;if(this.el.checkX)this.el.checkX.checked=false;this._calcBbox();this._applyClip();this._status('Сечение сброшено');}
    _calcBbox(){
        this.bbox.makeEmpty();this.models.forEach(m=>{if(m.visible)this.bbox.expandByObject(m);});if(this.bbox.isEmpty())return;
        const mn=this.bbox.min,mx=this.bbox.max;
        if(this.el.rangeY){this.el.rangeY.min=mn.y;this.el.rangeY.max=mx.y;if(!this.el.checkY||!this.el.checkY.checked){this.el.rangeY.value=mx.y;this.planeY.constant=mx.y;}}
        if(this.el.rangeX){this.el.rangeX.min=mn.x;this.el.rangeX.max=mx.x;if(!this.el.checkX||!this.el.checkX.checked){this.el.rangeX.value=mx.x;this.planeX.constant=mx.x;}}
    }
    _applyClip(){
        const planes=[];
        if(this.secMode){
            if(this.el.checkY&&this.el.checkY.checked){planes.push(this.planeY);this.el.rangeY&&this.el.rangeY.classList.remove('hidden');}else{this.el.rangeY&&this.el.rangeY.classList.add('hidden');}
            if(this.el.checkX&&this.el.checkX.checked){planes.push(this.planeX);this.el.rangeX&&this.el.rangeX.classList.remove('hidden');}else{this.el.rangeX&&this.el.rangeX.classList.add('hidden');}
        }
        this.models.forEach(m=>m.traverse(n=>{if(!n.isMesh)return;const mats=Array.isArray(n.material)?n.material:[n.material];mats.forEach(mat=>{if(!mat)return;mat.clippingPlanes=planes.length?planes:null;mat.clipShadows=true;});}));
        this.needsRender=true;
    }
    _forceMatUpdate(){this.models.forEach(m=>m.traverse(n=>{if(!n.isMesh)return;const mats=Array.isArray(n.material)?n.material:[n.material];mats.forEach(mat=>{if(mat)mat.needsUpdate=true;});}));}

    _toggleMeasure(){
        this.measMode=!this.measMode;this.el.btnMeasure&&this.el.btnMeasure.classList.toggle('btn-active',this.measMode);
        if(this.measMode){this.el.panelMeasure&&this.el.panelMeasure.classList.remove('hidden');this._status('📏 Клик — точка 1, ещё раз — точка 2');}
        else{this.el.panelMeasure&&this.el.panelMeasure.classList.add('hidden');this._clearMeasure();this._status('Рулетка выключена');}
        this._updateMeasUI();
    }
    _addMeasPt(pt){
        if(this.measPts.length>=2)this._clearMeasure();
        const sp=new THREE.Mesh(new THREE.SphereGeometry(0.12,12,12),new THREE.MeshBasicMaterial({color:this.measPts.length===0?COLOR.MEASURE_P1:COLOR.MEASURE_P2,depthTest:false}));
        sp.position.copy(pt);this.measGroup.add(sp);this.measPts.push(pt);
        if(this.measPts.length===2){const geo=new THREE.BufferGeometry().setFromPoints(this.measPts);const line=new THREE.Line(geo,new THREE.LineDashedMaterial({color:COLOR.MEASURE_LINE,dashSize:.25,gapSize:.1,depthTest:false}));line.computeLineDistances();this.measGroup.add(line);}
        this._updateMeasUI();this.needsRender=true;
    }
    _clearMeasure(){while(this.measGroup.children.length){const c=this.measGroup.children[0];safeDispose(c);this.measGroup.remove(c);}this.measPts=[];this._updateMeasUI();this.needsRender=true;}
    _updateMeasUI(){
        const r=this.el.measureRes;if(!r)return;
        if(!this.measPts.length){r.innerHTML='<em>Жду точку 1…</em>';return;}
        if(this.measPts.length===1){r.innerHTML='<div>Точка 1: ✅</div><em>Жду точку 2…</em>';return;}
        const p1=this.measPts[0],p2=this.measPts[1],f=v=>Math.abs(v).toFixed(3);
        r.innerHTML='<div class="measure-row"><span class="axis-x">ΔX:</span><span>'+f(p2.x-p1.x)+' м</span></div><div class="measure-row"><span class="axis-y">ΔY:</span><span>'+f(p2.y-p1.y)+' м</span></div><div class="measure-row"><span class="axis-z">ΔZ:</span><span>'+f(p2.z-p1.z)+' м</span></div><span class="axis-total">📐 '+p1.distanceTo(p2).toFixed(3)+' м</span>';
    }

    async _toggleSpaces(){
        const on=this.el.btnSpaces&&this.el.btnSpaces.textContent.includes('ВКЛ')?false:true;
        const wrap=$('space-search-wrapper');
        if(on){if(this.el.btnSpaces){this.el.btnSpaces.textContent='📦 ПОМЕЩЕНИЯ: ВКЛ';this.el.btnSpaces.classList.add('btn-primary');}if(wrap)wrap.classList.remove('hidden');await this._indexSpaces();this._initSpaceSearch();}
        else{if(this.el.btnSpaces){this.el.btnSpaces.textContent='📦 ПОМЕЩЕНИЯ';this.el.btnSpaces.classList.remove('btn-primary');}if(wrap)wrap.classList.add('hidden');this.spaces=[];}
    }
    async _indexSpaces(){
        this.spaces=[];
        for(const[,m]of this.models){const ids=await tryIFC(()=>this.loader.ifcManager.getAllItemsOfType(m.modelID,IFCSPACE,false),[]);if(!ids||!ids.length)continue;for(const id of ids){const p=await tryIFC(()=>this.loader.ifcManager.getItemProperties(m.modelID,id));this.spaces.push({mid:m.modelID,id,name:(p&&(p.Name&&p.Name.value||p.LongName&&p.LongName.value))||('Space '+id)});}}
        this._status('Помещений: '+this.spaces.length);
    }
    _initSpaceSearch(){
        const inp=$('space-search'),res=$('spaces-results');if(!inp||!res)return;
        inp.oninput=()=>{const q=inp.value.toLowerCase();res.innerHTML='';if(q.length<2){res.classList.add('hidden');return;}const found=this.spaces.filter(s=>s.name.toLowerCase().includes(q)).slice(0,10);if(!found.length){res.classList.add('hidden');return;}res.classList.remove('hidden');found.forEach(s=>{const d=document.createElement('div');d.className='space-item-result';d.textContent=s.name;d.onclick=()=>{this._zoomTo(s.mid,s.id);res.classList.add('hidden');inp.value=s.name;};res.appendChild(d);});};
    }
    async _zoomTo(mid,id){
        const sub=await tryIFC(()=>this.loader.ifcManager.createSubset({modelID:mid,ids:[id],scene:this.scene,removePrevious:true,customId:'ztmp'}));if(!sub)return;
        const box=new THREE.Box3().setFromObject(sub),c=box.getCenter(new THREE.Vector3()),s=box.getSize(new THREE.Vector3()),off=Math.max(s.x,s.y,s.z)*1.5;
        this.controls.target.copy(c);this.camera.position.set(c.x+off,c.y+off,c.z+off);this.controls.update();this.needsRender=true;
        setTimeout(()=>tryIFC(()=>this.loader.ifcManager.removeSubset(mid,undefined,'ztmp')),800);
    }

    _hideSelected(){if(this.sel.id==null)return;tryIFC(()=>this.loader.ifcManager.hideItems(this.sel.mid,[this.sel.id]));this._clearHL();this._hideProps();this.needsRender=true;}
    _resetVis(){this.models.forEach(m=>tryIFC(()=>this.loader.ifcManager.showAllItems(m.modelID)));this.hidden.clear();this._clearHL();this.needsRender=true;}
    _toggleElemVis(mid,node,eye){
        const ids=collectAllIds(node),wasHidden=this.hidden.has(node.expressID);
        if(wasHidden){ids.forEach(id=>this.hidden.delete(id));eye.style.opacity='1';eye.title='Скрыть';tryIFC(()=>this.loader.ifcManager.showItems(mid,ids));}
        else{ids.forEach(id=>this.hidden.add(id));eye.style.opacity='.3';eye.title='Показать';tryIFC(()=>this.loader.ifcManager.hideItems(mid,ids));}
        this.needsRender=true;
    }
    async _focusOn(mid,id){
        const sub=await tryIFC(()=>this.loader.ifcManager.createSubset({modelID:mid,ids:[id],removePrevious:true,customId:'ftmp'}));if(!sub)return;
        sub.geometry.computeBoundingBox();const bb=sub.geometry.boundingBox;if(!bb)return;
        const c=new THREE.Vector3();bb.getCenter(c);c.applyMatrix4(sub.matrixWorld);
        const s=bb.getSize(new THREE.Vector3()),off=Math.max(s.x,s.y,s.z,2)*2.2;
        this.controls.target.copy(c);this.camera.position.set(c.x+off,c.y+off,c.z+off);this.controls.update();this.needsRender=true;
        setTimeout(()=>tryIFC(()=>this.loader.ifcManager.removeSubset(mid,undefined,'ftmp')),500);
    }

    async _parseBIM(){
        if(!this.models.size)return;
        if(this.el.tagCloud)this.el.tagCloud.innerHTML='<em style="font-size:11px;color:#999">Сканирование…</em>';
        this._status('📊 BIM сканирование…');
        const mgr=this.loader.ifcManager,res={};
        BIM_CATS.forEach(c=>{res[c.key]=Object.assign({},c,{midMap:[],count:0});});
        for(const[,m]of this.models){
            await Promise.all(BIM_CATS.map(async cat=>{
                for(const t of cat.types){const ids=await tryIFC(()=>mgr.getAllItemsOfType(m.modelID,t,false),[]);if(ids&&ids.length){res[cat.key].midMap.push({mid:m.modelID,ids});res[cat.key].count+=ids.length;}}
            }));
        }
        this.bimData=res;
        const total=Object.values(res).reduce((s,c)=>s+c.count,0);
        this._renderTagCloud();this._status('BIM: '+total+' элементов');
        await this._parseSystems();
    }
    _renderTagCloud(){
        const el=this.el.tagCloud;if(!el)return;
        const counts=Object.values(this.bimData).map(c=>c.count).filter(Boolean);
        if(!counts.length){el.innerHTML='<em style="font-size:11px;color:#999">Элементы не найдены</em>';return;}
        const max=Math.max(...counts),grps={arch:'Архитектура',mep:'Инженерные системы',space:'Пространство'};
        let html='';
        for(const g in grps){const cats=Object.values(this.bimData).filter(c=>c.g===g&&c.count>0);if(!cats.length)continue;html+='<div class="tag-group-label">'+grps[g]+'</div><div class="tag-cloud">';cats.forEach(c=>{const size=(10+(c.count/max)*10).toFixed(1),active=this.activeCat===c.key?' active':'';html+='<span class="bim-tag '+c.g+active+'" data-key="'+c.key+'" style="font-size:'+size+'px">'+c.icon+' '+c.label+' <span class="tag-count">'+c.count+'</span></span>';});html+='</div>';}
        el.innerHTML=html;
        el.querySelectorAll('.bim-tag').forEach(t=>t.addEventListener('click',()=>this._applyCat(t.dataset.key)));
    }
    _applyCat(key){
        if(this.activeCat===key){this._resetCat();return;}this._resetCat();
        this.activeCat=key;const cat=this.bimData[key];if(!cat||!cat.midMap.length)return;
        this._prevXray=this.xray;if(!this.xray)this._toggleXray();
        this._catMat=new THREE.MeshLambertMaterial({color:COLOR.CAT_FOCUS});
        cat.midMap.forEach(({mid,ids})=>tryIFC(()=>this.loader.ifcManager.createSubset({modelID:mid,ids,scene:this.scene,removePrevious:true,customId:'cat_'+key,material:this._catMat})));
        this._status('🏷️ '+cat.label+': '+cat.count+' эл.');
        if(this.el.btnResetCat)this.el.btnResetCat.style.display='';
        this._renderTagCloud();this.needsRender=true;
    }
    _resetCat(){
        if(!this.activeCat)return;const key=this.activeCat;this.activeCat=null;
        this.models.forEach(m=>tryIFC(()=>this.loader.ifcManager.removeSubset(m.modelID,undefined,'cat_'+key)));
        if(this._catMat){this._catMat.dispose();this._catMat=null;}
        if(this.xray!==this._prevXray)this._toggleXray();
        if(this.el.btnResetCat)this.el.btnResetCat.style.display='none';
        this._status('Фильтр сброшен');this._renderTagCloud();this.needsRender=true;
    }

    // ─── Блок 3: Инженерные системы ─────────────────────────────────────────

    async _parseSystems(){
        this._systems={ovic:[],vk:[],eom:[],mep_other:[]};
        this._sysActive=null;
        const mgr=this.loader.ifcManager;
        for(const[,m]of this.models){
            let sysIDs;
            try{ sysIDs=await mgr.getAllItemsOfType(m.modelID,IFCSYSTEM,false); }catch{ continue; }
            if(!sysIDs||!sysIDs.length)continue;
            for(const sysID of sysIDs){
                // Имя системы
                let name='Система #'+sysID;
                try{ const p=await mgr.getItemProperties(m.modelID,sysID); name=p?.Name?.value??p?.LongName?.value??p?.Description?.value??name; }catch(_){}
                // Участники через IsGroupedBy → RelatedObjects
                const memberIDs=[];
                try{
                    const rels=await mgr.getItemProperties(m.modelID,sysID,true);
                    for(const rel of rels?.IsGroupedBy??[]){
                        const relObj=await mgr.getItemProperties(m.modelID,rel.value);
                        for(const mb of relObj?.RelatedObjects??[]) memberIDs.push(mb.value);
                    }
                }catch(_){}
                // Определяем раздел по типу первых элементов
                let section='mep_other';
                for(const mid of memberIDs.slice(0,8)){
                    try{ const mp=await mgr.getItemProperties(m.modelID,mid); const code=mp?.type; if(code&&TYPE_TO_SECTION[code]){section=TYPE_TO_SECTION[code];break;} }catch(_){}
                }
                this._systems[section].push({modelID:m.modelID,sysID,name,memberIDs});
            }
        }
        for(const arr of Object.values(this._systems)) arr.sort((a,b)=>a.name.localeCompare(b.name,'ru'));
        this._renderSystemsTab();
    }

    _renderSystemsTab(){
        const container=document.getElementById('bim-systems-tab');
        if(!container)return;
        container.innerHTML='';
        if(!this._systems){ container.innerHTML='<p class="sys-empty">Загрузите модель</p>'; return; }
        const total=Object.values(this._systems).reduce((s,a)=>s+a.length,0);
        if(!total){ container.innerHTML='<p class="sys-empty">Систем не найдено<br><span>Модель не содержит IFCSYSTEM</span></p>'; return; }
        let first=true;
        for(const[key,sec]of Object.entries(MEP_SECTIONS)){
            const items=this._systems[key]??[];
            if(!items.length)continue;
            const wrap=document.createElement('div');
            wrap.className='sys-section'+(first?' open':'');
            first=false;
            const hdr=document.createElement('div');
            hdr.className='sys-hdr';
            hdr.innerHTML=`<span class="sys-icon">${sec.icon}</span><span class="sys-label">${sec.label}</span><span class="sys-count">${items.length}</span><span class="sys-chevron">▶</span>`;
            hdr.addEventListener('click',()=>wrap.classList.toggle('open'));
            const list=document.createElement('ul');
            list.className='sys-list';
            for(const sys of items){
                const li=document.createElement('li');
                li.className='sys-item';
                li.innerHTML=`<span class="sys-name">${sys.name}</span><span class="sys-elem-count">${sys.memberIDs.length?sys.memberIDs.length+' эл.':'—'}</span>`;
                li.addEventListener('click',()=>this._highlightSystem(li,sys));
                list.appendChild(li);
            }
            wrap.appendChild(hdr);
            wrap.appendChild(list);
            container.appendChild(wrap);
        }
    }

    async _highlightSystem(liEl,sys){
        // Снимаем предыдущую подсветку
        document.querySelectorAll('.sys-item.active').forEach(el=>el.classList.remove('active'));
        const key=`${sys.modelID}_${sys.sysID}`;
        if(this._sysActive===key){
            this._sysActive=null;
            // Сброс: убираем sys-subset и X-Ray
            if(this._sysSubset){
                tryIFC(()=>this.loader.ifcManager.removeSubset(sys.modelID,this._catMat,'sys'));
                this._sysSubset=null;
            }
            if(this._sysXray){ this._toggleXray(); this._sysXray=false; }
            this._status('Подсветка системы сброшена');
            this.needsRender=true;
            return;
        }
        this._sysActive=key;
        liEl.classList.add('active');
        if(!sys.memberIDs.length){ this._status('Система пуста'); return; }
        // Включаем X-Ray если ещё не включён
        if(!this.xray){ this._toggleXray(); this._sysXray=true; } else { this._sysXray=false; }
        // Создаём subset с белым материалом (переиспользуем логику _applyCat)
        if(!this._catMat) this._catMat=new THREE.MeshLambertMaterial({color:COLOR.CAT_FOCUS});
        try{
            this._sysSubset=await tryIFC(()=>this.loader.ifcManager.createSubset({
                modelID:sys.modelID, ids:sys.memberIDs,
                material:this._catMat, scene:this.scene, removePrevious:true, customId:'sys',
            }));
        }catch(e){ this._log('Sys subset err: '+e.message); }
        this._status('⚙️ '+sys.name+' — '+sys.memberIDs.length+' эл.');
        this.needsRender=true;
    }

    async _buildTree(mid){
        const tc=$('tree-content');if(!tc)return;
        tc.innerHTML='<em style="font-size:11px;color:#999">Построение…</em>';
        const mgr=this.loader.ifcManager;
        const project=await tryIFC(()=>mgr.getSpatialStructure(mid));
        if(!project){tc.innerHTML='<span style="color:#d93025">Не удалось получить структуру</span>';return;}
        tc.innerHTML='';
        const self=this;
        const mkNode=async(node,depth)=>{
            depth=depth||0;const div=document.createElement('div');div.className='tree-node';
            const name=await getLabel(mgr,mid,node.expressID,node.type);
            div.dataset.name=name.toLowerCase();div.dataset.id=node.expressID;
            const hasKids=node.children&&node.children.length>0;
            const title=document.createElement('div');title.className='tree-node-title';
            title.innerHTML='<span class="tree-toggle">'+(hasKids?'▶':'&nbsp;')+'</span><span class="tree-name">'+name+' <small>['+node.type+']</small></span><span class="tree-eye">👁️</span>';
            title.querySelector('.tree-name').onclick=async()=>{await self._focusOn(mid,node.expressID);await self._showProps(mid,node.expressID);};
            const eye=title.querySelector('.tree-eye');eye.onclick=e=>{e.stopPropagation();self._toggleElemVis(mid,node,eye);};
            div.appendChild(title);
            if(hasKids){
                const kids=document.createElement('div');kids.className='node-children';kids.style.display='none';let built=depth<2;
                title.querySelector('.tree-toggle').onclick=async()=>{const show=kids.style.display==='none';if(show&&!built){built=true;for(const ch of node.children)kids.appendChild(await mkNode(ch,depth+1));}kids.style.display=show?'block':'none';title.querySelector('.tree-toggle').textContent=show?'▼':'▶';};
                if(built)for(const ch of node.children)kids.appendChild(await mkNode(ch,depth+1));
                div.appendChild(kids);
            }
            return div;
        };
        tc.appendChild(await mkNode(project,0));
        const si=$('tree-search');
        if(si)si.oninput=e=>{const q=e.target.value.toLowerCase().trim();tc.querySelectorAll('.tree-node').forEach(n=>{if(!q){n.style.display='';return;}const match=(n.dataset.name&&n.dataset.name.includes(q))||(n.dataset.id&&n.dataset.id.includes(q));n.style.display=match?'':'none';if(match){let p=n.parentElement&&n.parentElement.closest('.tree-node');while(p){p.style.display='';const kk=p.querySelector('.node-children');if(kk)kk.style.display='block';p=p.parentElement&&p.parentElement.closest('.tree-node');}}});};
    }

    _setQuality(mode){
        ['btn-mode-sport','btn-mode-balance','btn-mode-beauty'].forEach(id=>{const b=$(id);if(b)b.style.outline='none';});
        const defs=[{id:'btn-mode-sport',pr:1.0,shad:false,ai:1.5,dl:false,msg:'Спорт: макс FPS'},{id:'btn-mode-balance',pr:1.0,shad:true,ai:1.2,dl:true,msg:'Баланс'},{id:'btn-mode-beauty',pr:Math.min(window.devicePixelRatio,CFG.MAX_PIXEL_RATIO),shad:true,ai:1.2,dl:true,msg:'Красота: Retina'}];
        const d=defs[mode]||defs[0];const b=$(d.id);if(b)b.style.outline='2px solid #333';
        this.renderer.setPixelRatio(d.pr);this.renderer.shadowMap.enabled=d.shad;if(this.dirLight)this.dirLight.visible=d.dl;if(this.ambient)this.ambient.intensity=d.ai;this._status(d.msg);this.needsRender=true;
    }
    _screenshot(){
        const sz=new THREE.Vector2();this.renderer.getSize(sz);const pr=this.renderer.getPixelRatio();
        this.pivot.visible=false;this.renderer.setPixelRatio(pr*CFG.SS_SCALE);this.renderer.setSize(sz.x,sz.y,false);this.renderer.render(this.scene,this.camera);
        const url=this.renderer.domElement.toDataURL('image/png');const link=document.createElement('a');link.download='BIM_'+Date.now()+'.png';link.href=url;link.click();
        this.renderer.setPixelRatio(pr);this.renderer.setSize(sz.x,sz.y,true);this.needsRender=true;
    }
    _toggleWalk(){
        this.walkMode=!this.walkMode;if(this.el.btnSecret)this.el.btnSecret.style.opacity=this.walkMode?'1':'0.3';
        const ov=this.el.hardhatOv,msg=this.el.hardhatMsg;
        if(this.walkMode){if(ov){ov.style.display='block';requestAnimationFrame(()=>requestAnimationFrame(()=>{ov.classList.add('active');if(msg)msg.classList.add('show');}));}if(this._walkTO)clearTimeout(this._walkTO);this._walkTO=setTimeout(()=>{if(msg)msg.classList.remove('show');},4000);this._status('👷 Прораб: WASD + E/Q');}
        else{if(msg)msg.classList.remove('show');if(ov)ov.classList.remove('active');setTimeout(()=>{if(ov)ov.style.display='none';},400);this._status('Орбитальная камера');}
    }
    _walk(e){
        if(document.activeElement&&document.activeElement.tagName==='INPUT')return;
        const s=CFG.WALK_SPEED,fwd=new THREE.Vector3();this.camera.getWorldDirection(fwd);fwd.y=0;fwd.normalize();
        const rgt=new THREE.Vector3().crossVectors(this.camera.up,fwd).normalize();
        const mv=(v,sign)=>{this.camera.position.addScaledVector(v,s*sign);this.controls.target.addScaledVector(v,s*sign);};
        if(e.code==='KeyW'||e.code==='ArrowUp')mv(fwd,1);if(e.code==='KeyS'||e.code==='ArrowDown')mv(fwd,-1);
        if(e.code==='KeyA'||e.code==='ArrowLeft')mv(rgt,1);if(e.code==='KeyD'||e.code==='ArrowRight')mv(rgt,-1);
        if(e.code==='KeyE'){this.camera.position.y+=s;this.controls.target.y+=s;}if(e.code==='KeyQ'){this.camera.position.y-=s;this.controls.target.y-=s;}
        this.controls.update();this.needsRender=true;
    }
    _initCube(){
        const cnt=$('viewcube');if(!cnt)return;
        this.cubeScene=new THREE.Scene();this.cubeCamera=new THREE.PerspectiveCamera(45,1,0.1,20);this.cubeCamera.position.set(0,0,6);
        this.cubeRenderer=new THREE.WebGLRenderer({antialias:true,alpha:true});this.cubeRenderer.setSize(120,120);cnt.appendChild(this.cubeRenderer.domElement);
        const ax=(color,rot,name)=>{const g=new THREE.Group();g.name=name;const sh=new THREE.Mesh(new THREE.CylinderGeometry(.1,.1,2.2),new THREE.MeshBasicMaterial({color}));sh.position.y=1.1;const tip=new THREE.Mesh(new THREE.ConeGeometry(.25,.6),new THREE.MeshBasicMaterial({color}));tip.position.y=2.4;g.add(sh,tip);g.rotation.set(rot[0],rot[1],rot[2]);return g;};
        this.cubeScene.add(ax(0xff3e3e,[0,0,-Math.PI/2],'x'),ax(0x32cd32,[0,0,0],'y'),ax(0x1e90ff,[Math.PI/2,0,0],'z'));
        cnt.addEventListener('click',e=>{
            const r=cnt.getBoundingClientRect(),rc=new THREE.Raycaster();rc.setFromCamera({x:((e.clientX-r.left)/r.width)*2-1,y:-((e.clientY-r.top)/r.height)*2+1},this.cubeCamera);
            const hs=rc.intersectObjects(this.cubeScene.children,true);if(!hs.length)return;
            let o=hs[0].object;while(o.parent&&!o.name)o=o.parent;
            const d=this.camera.position.distanceTo(this.controls.target),t=this.controls.target.clone();
            if(o.name==='x')this.camera.position.set(t.x+d,t.y,t.z);if(o.name==='y')this.camera.position.set(t.x,t.y+d,t.z);if(o.name==='z')this.camera.position.set(t.x,t.y,t.z+d);
            this.camera.lookAt(t);this.controls.update();this.needsRender=true;
        });
    }
}

new BIMApp();
