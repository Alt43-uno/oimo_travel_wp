/**
 * @preserve
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/
 * @copyright 2021 ThemePunch
 * @version 6.7.0
 */
!function(t){var a={},e="revslider-transitionpack-addon",s=revslider_transitionpack_addon.bricks;function i(t){var a=document.getElementsByClassName("presets_liste tpacktrans")[0];void 0!==a&&(a.classList[t]("disabled"),document.getElementsByClassName("presets_liste_inner tpacktrans")[0].classList[t]("disabled"))}function n(){RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns=RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns||{},RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack=RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack||{},RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns=jQuery.extend(!0,RVS._R.getSlideAnim_AddonDefaults(),RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns)}function d(t){return t.ds=t.ds||{},t.evt&&(t.ds.evt=t.evt),t.evtparam&&(t.ds.evtparam=t.evtparam),t.num&&(t.ds.numeric="true"),t.allowed&&(t.ds.allowed=t.allowed),t.presets&&(t.ds.presets_text=t.presets.text,t.ds.presets_val=t.presets.val),t.ds.r="slideChange.addOns.tpack."+(t.r||t.id),t.steps&&(t.ds.steps=t.steps),void 0!==t.min&&(t.ds.min=t.min),void 0!==t.max&&(t.ds.max=t.max),void 0!==t.min&&void 0!==t.max&&(t.ds.numeric="true"),t.ds}function l(t){if(null==t)return!1;let a=RVS.F.cE({t:"ui"===t.type?"label_icon":"i"===t.type?"i":"label_a"});return t.cN&&(t.cN=" "+t.cN),a.className="i"===t.type?"label_icon material-icons"+(t.cN||""):"ui"===t.type?t.c+(t.cN||""):t.cN||"",t.c&&"ui"!==t.type&&(a.innerText=t.c),a}function p(t){return t.cN="slideinput smallinput easyinit callEvent"+(t.num?" valueduekeyboard":"")+(t.presets?" input_with_presets":"")+(t.cN?" "+t.cN:"")+(t.dyn?" rsdyn_inp":""),RVS.F.cE({t:"input",id:"sltranspack_"+t.id,type:t.type||"text",cN:t.cN,ds:d(t)})}function r(t){let a=function(t){"materialicon"==t.labeltype||t.labeltype;let a={row:RVS.F.cE({t:"row",id:t.id,cN:"direktrow"+(void 0!==t.cN?" "+t.cN:"")}),ol:RVS.F.cE({t:"onelong",cN:t.lCN}),os:RVS.F.cE({t:"oneshort",cN:t.sCN}),lo:void 0!==t.labels?l(t.labels.long):void 0,so:void 0!==t.labels?l(t.labels.short):void 0};return a.lo&&a.ol.appendChild(a.lo),a.so&&a.os.appendChild(a.so),a.row.appendChild(a.ol),a.row.appendChild(a.os),a}({id:"slttranspack_"+t.id,labels:t.labels,cN:t.cN,sCN:t.sCN,lCN:t.lCN});return t.inputs.shortv&&(a.so.style.overflow="visible"),t.inputs.long&&(a.ol.appendChild(p(t.inputs.long)),t.inputs.long.dyn&&a.ol.classList.add("dyn_inp_wrap")),t.inputs.short&&(a.os.appendChild(p(t.inputs.short)),t.inputs.short.dyn&&a.os.classList.add("dyn_inp_wrap")),a.row}function o(t){let a=RVS.F.cE({t:"div",id:"slttranspack_"+t.id,cN:t.cN}),e=l(t.label),s=RVS.F.cE({t:"select",id:"sltranspack_"+t.select.id,cN:"slideinput tos2 nosearchbox easyinit callEvent"+(t.select.cN?" "+t.select.cN:""),ds:d(t.select)});if(void 0!==t.select.o&&t.select.o.length>0)for(var i in t.select.o)s.appendChild(RVS.F.CO(t.select.o[i].v,t.select.o[i].t));if(void 0!==t.iWrap){let i=RVS.F.cE({t:"div",cN:t.iWrapCN});e&&i.appendChild(e),i.appendChild(s),a.appendChild(i)}else e&&a.appendChild(e),a.appendChild(s);return a}RVS_LANG.sltr_transpack=void 0===RVS_LANG.sltr_transpack?s.transpack:RVS_LANG.sltr_transpack,RVS_LANG.sltr_tpack=void 0===RVS_LANG.sltr_tpack?s.tpack:RVS_LANG.sltr_tpack,RVS_LANG.sltr_cube=void 0===RVS_LANG.sltr_cube?s.cube:RVS_LANG.sltr_cube,RVS_LANG.sltr_tpburn=void 0===RVS_LANG.sltr_tpburn?s.tpburn:RVS_LANG.sltr_tpburn,RVS_LANG.sltr_tpcuts=void 0===RVS_LANG.sltr_tpcuts?s.tpcuts:RVS_LANG.sltr_tpcuts,RVS_LANG.sltr_tpfluid=void 0===RVS_LANG.sltr_tpfluid?s.tpfluid:RVS_LANG.sltr_tpfluid,RVS_LANG.sltr_tpcolore=void 0===RVS_LANG.sltr_tpcolore?s.tpcolore:RVS_LANG.sltr_tpcolore,RVS_LANG.sltr_tprolls=void 0===RVS_LANG.sltr_tprolls?s.tprolls:RVS_LANG.sltr_tprolls,RVS_LANG.sltr_tpstmelt=void 0===RVS_LANG.sltr_tpstmelt?s.tpstmelt:RVS_LANG.sltr_tpstmelt,RVS_LANG.sltr_tpstsk=void 0===RVS_LANG.sltr_tpstsk?s.tpstsk:RVS_LANG.sltr_tpstsk,RVS_LANG.sltr_tpflats=void 0===RVS_LANG.sltr_tpflats?s.tpflats:RVS_LANG.sltr_tpflats,RVS.DOC.on("redrawSlideBGDone",(function t(){RVS&&RVS.SLIDER&&RVS.SLIDER.settings&&RVS.SLIDER.settings.addOns&&RVS.SLIDER.settings.addOns[e]?(RVS.SLIDER.settings.addOns[e].enable?i("remove"):i("add"),RVS.DOC.off("redrawSlideBGDone",t)):i("add")})),void 0===RVS._R.transitionPack&&jQuery.getScript(RVS.ENV.wp_plugin_url+e+"/sr6/assets/js/revolution.addon.transitionpack.js",(function(){RVS.F.showAddonInfos(e),triggerUpdateDisplay=!0,!RVS.S.ovMode&&RVS.LIB.ADDONS[e].enable&&RVS.DOC.trigger(e+"_init")})).fail((function(t,a,e){console.log(t,a,e)})),RVS.DOC.on(e+"_init",(function(){if(void 0!==RVS._R.transitionPack){if(!a.initialised){if("undefined"==typeof THREE||null==THREE){var d=document.createElement("script");d.src=RVS.ENV.plugin_url+"sr6/assets/js/libs/three.min.js",d.type="text/javascript",document.head.appendChild(d)}RVS.F.addOnContainer.create({slug:e,icon:"leaderboard",title:s.transitionpack,alias:s.transitionpack,layer:!0}),a.forms={layergeneral:t("#form_layerinner_"+e),layericon:t("#gst_layer_"+e),layer:t("#form_layer_"+e)},RVS._R.enabledSlideAnimAddons=RVS._R.enabledSlideAnimAddons||[],RVS._R.enabledSlideAnimAddons.push("transitionPackEffects"),n(),i("remove"),RVS.JHOOKS.slideAnimRowColCheck=RVS.JHOOKS.slideAnimRowColCheck||[],RVS.JHOOKS.slideAnimRowColCheck.push((function(t){return"transitionPack"===RVS.SLIDER[RVS.S.slideId].slide.slideChange.eng&&"cube"===RVS.SLIDER[RVS.S.slideId].slide.slideChange.e?RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack.row>1||RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack.col>1:t})),l=document.getElementById("slide_maintranssettings_wrap"),p=document.createElement("div"),c=document.createElement("div"),_=document.createElement("div"),a.forms.stab=document.createElement("div"),a.forms.ftab=document.createElement("div"),p.style.display="inline-block",c.id="transitionpack",c.className="ts_wrapbrtn",_.id="transitionpack_filters",_.className="ts_wrapbrtn",a.forms.stab.dataset.showtrans="#transitionpack_transsettings",a.forms.stab.className="transtarget_selector",a.forms.stab.innerText="Trans Pack",a.forms.ftab.dataset.showtrans="#transitionpack_transfilters",a.forms.ftab.className="transtarget_selector",a.forms.ftab.innerText=s.filters,c.appendChild(a.forms.stab),_.appendChild(a.forms.ftab),p.appendChild(c),p.appendChild(_),l.appendChild(p),RVS.C.sltaddon=void 0===RVS.C.sltaddon?{}:RVS.C.sltaddon,RVS.C.sltaddon[e]={menu:[jQuery(c),jQuery(_)],slt_areas:{}},function(){a.forms.W=document.getElementById("form_sanimation_sframes_innerwrap"),a.forms.JW=jQuery(a.forms.W),document.createDocumentFragment();let t=RVS.F.cE({id:"transitionpack_transsettings",cN:"group_transsettings"}),i=RVS.F.cE({id:"transitionpack_transfilters",cN:"group_transsettings"}),n=RVS.F.cE({id:"tpack_twistsettings"});n.appendChild(o({id:"twist_twe_wrap",label:{type:"a",c:s.twisteffect},select:{id:"twe",cN:"",o:[{v:"simple",t:s.twistsimple},{v:"twistwave",t:s.twistwave}],ds:{evt:"updateSlideAnimation",theme:"dark",show:".tpacktwist_*val*_show",hide:".tpacktwist__hide",showprio:"show"}}})),n.appendChild(r({id:"twist_twv_twz",labels:{long:{type:"i",c:"rotate_90_degrees_ccw"},short:{type:"ui",c:"ui_z"}},inputs:{long:{id:"twv",dyn:!0,presets:{text:"val - n*90!{min,max} - Random!(val) - Direction Based",val:"2!{-4,4}!(2)"},allowed:"none",min:-360,max:360,steps:1,evt:"updateSlideAnimation"},short:{id:"twz",dyn:!0,presets:{text:"val - 30!val - 100!val - 200",val:"30!100!200"},min:0,max:200,steps:"1",allowed:"none",evt:"updateSlideAnimation"}}})),n.appendChild(r({id:"tpacktwist_anchor",cN:"tpacktwist_simple_show tpacktwist__hide",labels:{long:{type:"a",c:s.anchor}},inputs:{long:{id:"twa",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),n.appendChild(o({id:"tpacktwist_direction",cN:"tpacktwist_twistwave_show tpacktwist__hide",label:{type:"a",c:s.direction},select:{id:"twd",cN:"",o:[{v:"left",t:s.left},{v:"right",t:s.right}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),n.appendChild(r({id:"tpacktwist_twc",cN:"gz tpacktwist_simple_hide tpacktwist_twistwave_show ",labels:{long:{type:"a",c:s.curtain}},inputs:{long:{id:"twc",type:"checkbox",evt:"updateSlideAnimation"}}})),n.appendChild(r({id:"tpacktwist_distance",cN:"tpacktwist_twistwave_show tpacktwist__hide",labels:{long:{type:"a",c:s.distance}},inputs:{long:{id:"twdi",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"none"}}})),n.appendChild(r({id:"tpacktwist_fog",cN:"transpack_basicsettings_hide tpacktwist_simple_show tpacktwist_twistwave_show",labels:{long:{type:"a",c:s.fog}},inputs:{long:{id:"twf",cN:"my-color-field",evt:"updateSlideAnimation"}}})),n.appendChild(r({id:"tpacktwist_shadow",cN:"transpack_basicsettings_hide tpacktwist_simple_show tpacktwist_twistwave_show",labels:{long:{type:"a",c:s.shadow}},inputs:{long:{id:"tws",cN:"my-color-field",evt:"updateSlideAnimation"}}})),t.appendChild(o({id:"tpbasic_ef_wrap",label:{type:"a",c:s.effect},select:{id:"ef",cN:"",o:[{v:"fade",t:s.map},{v:"fadeb",t:s.mapb},{v:"wave",t:s.wave},{v:"cut",t:s.cut},{v:"overroll",t:s.overroll},{v:"colorflow",t:s.colorflow},{v:"stretch",t:s.stretch},{v:"water",t:s.water},{v:"zoomover",t:s.zoomover},{v:"burn",t:s.burn},{v:"burnover",t:s.burnover},{v:"morph",t:s.morph},{v:"blur",t:s.blur},{v:"waterdrop",t:s.waterdrop},{v:"mosaic",t:s.mosaic},{v:"dreamy",t:s.dreamy},{v:"mirrorcube",t:s.mirrorcube},{v:"flat",t:s.flat},{v:"pano",t:s.pano},{v:"chaos",t:s.chaos},{v:"stretch",t:s.stretch},{v:"skew",t:s.skew},{v:"perspective",t:s.perspective},{v:"spin",t:s.spin},{v:"rings",t:s.rings},{v:"zoom",t:s.zoom}],ds:{evt:"updateSlideAnimation",theme:"dark",show:".transpack_*val*_show",hide:".transpack_basicsettings_hide",showprio:"show"}}})),t.appendChild(o({id:"tpbasic_map_wrap",iWrap:!0,iWrapCN:"transpack_basicsettings_hide transpack_fade_show transpack_wave_show transpack_colorflow_show transpack_overroll_show  transpack_burnover_show",label:{type:"i",c:"map"},select:{id:"dplm",cN:"",o:[{v:"1",t:s.map1},{v:"2",t:s.map2},{v:"3",t:s.map3},{v:"4",t:s.map4},{v:"5",t:s.map5},{v:"6",t:s.map6},{v:"17",t:s.map17},{v:"7",t:s.map7},{v:"8",t:s.map8},{v:"9",t:s.map9},{v:"10",t:s.map10},{v:"11",t:s.map11},{v:"12",t:s.map12},{v:"13",t:s.map13},{v:"14",t:s.map14},{v:"15",t:s.map15},{v:"16",t:s.map16}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(o({id:"tpbasic_map_flip_wrap",iWrap:!0,iWrapCN:"transpack_basicsettings_hide transpack_fade_show transpack_fadeb_show",label:{type:"i",c:"flip"},select:{id:"mfl",cN:"",o:[{v:"0",t:s.noflip},{v:"1",t:s.flip},{v:"2",t:s.dirflip}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(o({id:"tpbasic_direction_wrap",cN:"transpack_basicsettings_hide transpack_overroll_show transpack_cut_show transpack_burn_show",label:{type:"i",c:"explore"},select:{id:"dir",cN:"",o:[{v:"0",t:s.fleft},{v:"1",t:s.fright},{v:"2",t:s.ftop},{v:"3",t:s.fbottom}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(r({id:"tpbasic_directionbased_wrap",cN:"transpack_basicsettings_hide transpack_overroll_show transpack_cut_show transpack_burn_show",labels:{long:{type:"a",c:s.dbased}},inputs:{long:{id:"dbas",type:"checkbox",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_radius",cN:"transpack_basicsettings_hide transpack_wave_show transpack_waterdrop_show",labels:{long:{type:"a",c:s.rippleiny}},inputs:{long:{id:"rad",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_reflection",cN:"transpack_basicsettings_hide transpack_mirrorcube_show",labels:{long:{type:"a",c:s.reflection}},inputs:{long:{id:"ref",allowed:".",min:0,max:1,step:.1,evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_floating",cN:"transpack_basicsettings_hide transpack_mirrorcube_show",labels:{long:{type:"a",c:s.floating}},inputs:{long:{id:"flo",numeric:!0,min:1,max:100,step:1,evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_width",cN:"transpack_basicsettings_hide transpack_wave_show transpack_cut_show ",sCN:"transpack_basicsettings_hide",labels:{long:{type:"a",c:s.length},short:{type:"ui",c:"ui_height"}},inputs:{long:{id:"w",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"},short:{id:"h",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_springkling_x",cN:"transpack_basicsettings_hide transpack_cut_show ",labels:{long:{type:"a",c:s.frequencyx}},inputs:{long:{id:"ssx",min:0,max:200,step:1,evt:"updateSlideAnimation",allowed:" "}}})),t.appendChild(r({id:"tpbasic_springkling_y",cN:"transpack_basicsettings_hide transpack_cut_show ",labels:{long:{type:"a",c:s.frequencyy}},inputs:{long:{id:"ssy",min:0,max:200,step:1,evt:"updateSlideAnimation",allowed:" "}}})),t.appendChild(r({id:"tpbasic_pos_xy",cN:"transpack_basicsettings_hide transpack_mosaic_show transpack_flat_show transpack_pano_show transpack_chaos_show transpack_stretch_show transpack_skew_show transpack_morph_show transpack_colorflow_show transpack_blur_show",labels:{long:{type:"ui",c:"ui_x"},short:{type:"ui",c:"ui_y"}},inputs:{long:{id:"x",min:-1e3,max:1e3,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"2!{-10,10}!(2)"},allowed:"set,random,dir"},short:{id:"y",min:-1e3,max:1e3,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"2!{-10,10}!(2)"},allowed:"set,random,dir"}}})),t.appendChild(r({id:"tpbasic_ori_xy",cN:"transpack_basicsettings_hide transpack_perspective_show transpack_spin_show transpack_rings_show transpack_zoom_show transpack_blur_show",labels:{long:{type:"ui",c:"ui_origox"},short:{type:"ui",c:"ui_origoy"}},inputs:{long:{id:"ox",min:-500,max:500,step:1,evt:"updateSlideAnimation",allowed:"%"},short:{id:"oy",min:-500,max:500,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(o({id:"tpack_animorigin",cN:"transpack_basicsettings_hide transpack_spin_show transpack_rings_show transpack_zoom_show transpack_blur_show transpack_perspective_show",label:{type:"a",c:s.animorigin},select:{id:"ao",cN:"",o:[{v:"none",t:s.none},{v:"center",t:s.tocenter},{v:"inverse",t:s.inverse},{v:"spinaround",t:"Path: Spin Around"}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(r({id:"tpbasic_pos_z",cN:"transpack_basicsettings_hide transpack_pano_show transpack_flat_show transpack_spin_show",labels:{long:{type:"ui",c:"ui_z"}},inputs:{long:{id:"z",min:-1e3,max:1e3,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_intensity",cN:"transpack_basicsettings_hide transpack_waterdrop_show transpack_colorflow_show transpack_water_show transpack_zoomover_show transpack_morph_show transpack_blur_show transpack_pano_show transpack_burn_show transpack_stretch_show transpack_skew_show transpack_chaos_show transpack_spin_show transpack_rings_show transpack_overroll_show",labels:{long:{type:"a",c:s.intensity}},inputs:{long:{id:"iny",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"30!{-30,30}!(30)"},allowed:"%,random,dir"}}})),t.appendChild(r({id:"tpbasic_twistintensity",cN:"transpack_basicsettings_hide transpack_stretch_show",labels:{long:{type:"a",c:s.twistintensity},short:{type:"a",c:s.twistsize}},inputs:{long:{id:"stri",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"},short:{id:"strs",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_twistflip",cN:"transpack_basicsettings_hide transpack_stretch_show",labels:{long:{type:"a",c:s.flipTwist}},inputs:{long:{id:"strf",type:"checkbox",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_zrotation",cN:"transpack_basicsettings_hide  transpack_perspective_show transpack_spin_show transpack_rings_show transpack_zoom_show transpack_blur_show",labels:{long:{type:"ui",c:"ui_rotatez"}},inputs:{long:{id:"roz",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"2!{-5,5}!(2)"},allowed:"random,dir"}}})),t.appendChild(r({id:"tpbasic_zoom_rotend",cN:"transpack_basicsettings_hide transpack_zoom_show transpack_blur_show",labels:{long:{type:"a",c:s.spinEnd}},inputs:{long:{id:"zre",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{0,100}!(10)"},allowed:"random"}}})),t.appendChild(r({id:"tpbasic_rings_splits",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.rings}},inputs:{long:{id:"cispl",min:1,max:20,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val)",val:"5!{1,20}"},allowed:"random"}}})),t.appendChild(r({id:"tpbasic_rings_color",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.color}},inputs:{long:{id:"cicl",cN:"my-color-field",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_rings_shadow",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.shadow}},inputs:{long:{id:"cish",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val)",val:"0!{0,100}"},allowed:"%,random"}}})),t.appendChild(o({id:"tpbasic_rings_overlay",cN:"transpack_basicsettings_hide transpack_rings_show",label:{type:"a",c:s.overlay},select:{id:"cio",cN:"",o:[{v:"none",t:s.none},{v:"alternate",t:s.alternate},{v:"grad",t:s.gradual},{v:"inverse",t:s.inverse}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(r({id:"tpbasic_rings_cover",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.cover}},inputs:{long:{id:"cico",type:"checkbox",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_rings_dir",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.spinAlt}},inputs:{long:{id:"ciad",type:"checkbox",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_rings_mixwithsplits",cN:"transpack_basicsettings_hide transpack_rings_show",labels:{long:{type:"a",c:s.mixStaggered}},inputs:{long:{id:"cimw",type:"checkbox",evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"tpbasic_perspective",cN:"transpack_basicsettings_hide transpack_perspective_show",labels:{long:{type:"a",c:s.perspective}},inputs:{long:{id:"pr",min:-100,max:100,step:1,evt:"updateSlideAnimation",allowed:"deg"}}})),t.appendChild(r({id:"tpbasic_prange",cN:"transpack_basicsettings_hide transpack_pano_show transpack_flat_show transpack_chaos_show transpack_perspective_show transpack_skew_show transpack_spin_show transpack_rings_show transpack_zoom_show transpack_blur_show",labels:{long:{type:"a",c:s.prange}},inputs:{long:{id:"prange",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_tiltintensity",cN:"transpack_basicsettings_hide transpack_pano_show transpack_flat_show",labels:{long:{type:"a",c:s.tilt}},inputs:{long:{id:"tlt",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"10!{-20,20}!(10)"},allowed:"%,random,dir"}}})),t.appendChild(r({id:"tpbasic_efforigin",cN:"transpack_basicsettings_hide transpack_skew_show",labels:{long:{type:"a",c:s.efforigin}},inputs:{long:{id:"sko",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_shake_value",cN:"transpack_basicsettings_hide transpack_skew_show",labels:{long:{type:"a",c:s.shake}},inputs:{long:{id:"shv",min:0,max:100,step:1,evt:"updateSlideAnimation",allowed:"%"}}})),t.appendChild(r({id:"tpbasic_shake_xy",cN:"transpack_basicsettings_hide transpack_skew_show",labels:{long:{type:"a",c:s.shakeX},short:{type:"a",c:s.shakeY}},inputs:{long:{id:"shx",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"10!{-20,20}!(10)"},allowed:"%,random,dir"},short:{id:"shy",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"10!{-20,20}!(10)"},allowed:"%,random,dir"}}})),t.appendChild(r({id:"tpbasic_shake_rt",cN:"transpack_basicsettings_hide transpack_skew_show",labels:{long:{type:"a",c:s.shakeZ},short:{type:"ui",c:"ui_rotatez"}},inputs:{long:{id:"shz",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random!(val) - Direction Based",val:"10!{-20,20}!(10)"},allowed:"%,random,dir"},short:{id:"shr",min:-360,max:360,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{0,360}!(10)"},allowed:"deg,random"}}})),t.appendChild(o({id:"tpbasic_chaos_chm1",cN:"transpack_basicsettings_hide transpack_chaos_show",label:{type:"a",c:s.mixValue+" 1"},select:{id:"chm1",o:[{v:"random",t:s.random},{v:"t1",t:s.type+" 1"},{v:"t2",t:s.type+" 2"}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(o({id:"tpbasic_chaos_chm2",cN:"transpack_basicsettings_hide transpack_chaos_show",label:{type:"a",c:s.mixValue+" 2"},select:{id:"chm2",o:[{v:"random",t:s.random},{v:"t1",t:s.type+" 1"},{v:"t2",t:s.type+" 2"}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(o({id:"tpbasic_chaos_chm3",cN:"transpack_basicsettings_hide transpack_chaos_show",label:{type:"a",c:s.mixValue+" 3"},select:{id:"chm3",o:[{v:"random",t:s.random},{v:"t1",t:s.type+" 1"},{v:"t2",t:s.type+" 2"}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(o({id:"tpbasic_chaos_chm4",cN:"transpack_basicsettings_hide transpack_chaos_show",label:{type:"a",c:s.mixValue+" 4"},select:{id:"chm4",o:[{v:"random",t:s.random},{v:"t1",t:s.type+" 1"},{v:"t2",t:s.type+" 2"}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(r({id:"tpbasic_zoom_inout",cN:"transpack_basicsettings_hide transpack_zoom_show transpack_blur_show",labels:{long:{type:"a",c:s.zoomOut},short:{type:"a",c:s.in}},inputs:{long:{id:"zo",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{-100,100}!(10)"},allowed:"random"},short:{id:"zi",min:-100,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{-100,100}!(10)"},allowed:"random"}}})),t.appendChild(r({id:"tpbasic_zoom_rayblur",cN:"transpack_basicsettings_hide transpack_zoom_show",labels:{long:{type:"a",c:s.blurIntensity}},inputs:{long:{id:"zb",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{0,100}!(10)"},allowed:"random"}}})),t.appendChild(r({id:"tpbasic_zoom_warp",cN:"transpack_basicsettings_hide transpack_zoom_show",labels:{long:{type:"a",c:s.warpOut},short:{type:"a",c:s.in}},inputs:{long:{id:"zwo",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{0,100}!(10)"},allowed:"random"},short:{id:"zwi",min:0,max:100,step:1,evt:"updateSlideAnimation",presets:{text:"val - Standard!{min,max} - Random",val:"50!{0,100}!(10)"},allowed:"random"}}})),t.appendChild(r({id:"rowcol_wrap",labels:{long:{c:"Cols"},short:{c:"Rows"}},inputs:{long:{id:"col",num:!0,presets:{text:"$C$1!$C$2!$C$5!$C$10!$R$Random!$I$Default",val:"1!2!5!10!random!default!"},allowed:"random,default",evt:"updateSlideAnimationtrpackrowcol",evtparam:"col"},short:{id:"row",num:!0,presets:{text:"$C$1!$C$2!$C$5!$C$10!$R$Random!$I$Default",val:"1!2!5!10!random!default!"},allowed:"random,default",evt:"updateSlideAnimationtrpackrowcol",evtparam:"row"},shortv:!0}})),t.appendChild(r({id:"rotate_sr_wrap",labels:{long:{type:"i",c:"rotate_90_degrees_ccw"},short:{type:"ui",c:"ui_opacity"}},inputs:{long:{id:"sr",dyn:!0,presets:{text:"val - n*90!{min,max} - Random!(val) - Direction Based",val:"2!{-4,4}!(2)"},allowed:" ",min:-20,max:20,steps:1,evt:"updateSlideAnimation"},short:{id:"o",dyn:!0,presets:{text:"val - 0!val - 0.5!val - 1",val:"0!0.5!1"},min:-3,max:3,steps:"0.1",allowed:".",evt:"updateSlideAnimation"}}})),t.appendChild(o({id:"ease_sr_wrap",label:{type:"a",c:"Ease"},select:{id:"ie",cN:"easingSelect",ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(RVS.F.cE({cN:"div15",id:"slttranspack_rotate_ease_spacer"})),t.appendChild(r({id:"rotate_xy_wrap",labels:{long:{type:"ui",c:"ui_rotatex"},short:{type:"ui",c:"ui_rotatey"}},inputs:{long:{id:"rx",dyn:!0,presets:{text:"deg - Standard!{min,max} - Random![val|val|val] - Cycles!(val) - Direction Based",val:"45deg!{-20,20}![-50|50]!(45deg)"},evt:"updateSlideAnimation"},short:{id:"ry",dyn:!0,presets:{text:"deg - Standard!{min,max} - Random![val|val|val] - Cycles!(val) - Direction Based",val:"45deg!{-20,20}![-50|50]!(45deg)"},evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"rotate_z_wrap",labels:{long:{type:"ui",c:"ui_rotatez"}},inputs:{long:{id:"rz",dyn:!0,presets:{text:"deg - Standard!{min,max} - Random![val|val|val] - Cycles!(val) - Direction Based",val:"45deg!{-20,20}![-50|50]!(45deg)"},evt:"updateSlideAnimation"}}})),t.appendChild(o({id:"easegrp_sr_wrap",label:{type:"a",c:"Ease"},select:{id:"ige",cN:"easingSelect",ds:{evt:"updateSlideAnimation",theme:"dark"}}})),t.appendChild(RVS.F.cE({id:"slttranspack_scale_ease_spacer",cN:"div15"})),t.appendChild(r({id:"scale_xy_wrap",labels:{long:{type:"ui",c:"ui_scalex"},short:{type:"ui",c:"ui_scaley"}},inputs:{long:{id:"sx",evt:"updateSlideAnimation"},short:{id:"sy",dyn:!0,allowed:".,random,cycle",presets:{text:"val - Standard!{min,max} - Random![val|val|val] - Cycles",val:"0.5!{0,3}![0.2|0.8]"},evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"scale_z_wrap",labels:{long:{type:"i",c:"open_in_full"}},inputs:{long:{id:"sz",dyn:!0,allowed:".,random,cycle",presets:{text:"val - Standard!{min,max} - Random![val|val|val] - Cycles",val:"0.5!{0,3}![0.2|0.8]"},evt:"updateSlideAnimation"}}})),t.appendChild(RVS.F.cE({cN:"div15",id:"slttranspack_position_scale_spacer"})),t.appendChild(r({id:"position_xy_wrap",labels:{long:{type:"ui",c:"ui_x"},short:{type:"ui",c:"ui_y"}},inputs:{long:{id:"gx",dyn:!0,min:-500,max:500,steps:1,evt:"updateSlideAnimation"},short:{id:"gy",dyn:!0,min:-500,max:500,steps:1,evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"position_z_wrap",cN:"transpack_basicsettings_hide transpack_mirrorcube_show",labels:{long:{type:"ui",c:"ui_z"}},inputs:{long:{id:"gz",dyn:!0,min:0,max:500,steps:1,evt:"updateSlideAnimation"}}})),t.appendChild(r({id:"cube_position_z_wrap",cN:"",labels:{long:{type:"ui",c:"ui_z"}},inputs:{long:{id:"cgz",dyn:!0,min:0,max:500,steps:1,evt:"updateSlideAnimation"}}})),i.appendChild(o({id:"filtermap_wrap",label:{type:"a",c:"Post Process"},select:{id:"pp",cN:"",o:[{v:"none",t:s.none},{v:"glitches",t:s.glitches},{v:"glitch2",t:s.glitches2},{v:"blur",t:s.blur},{v:"film",t:s.film}],ds:{evt:"updateSlideAnimation",theme:"dark",show:"#transpack_*val*_filter",hide:".transpack_filters",showprio:"show"}}}));let d=RVS.F.cE({t:"div",id:"transpack_blur_filter",cN:"transpack_filters"});d.appendChild(o({id:"blur_filter_subtype",label:{type:"a",c:s.blurtype},select:{id:"ppbt",cN:"",o:[{v:"motion",t:s.blur2d},{v:"d3",t:s.blur3d},{v:"rotation",t:s.blurrotation}],ds:{evt:"updateSlideAnimation",theme:"dark",show:".blurfilter_*val*_show",hide:".blurfilter__hide",showprio:"show"}}})),d.appendChild(r({id:"blur_filter_settings",cN:"blurfilter__hide blurfilter_d3_show",labels:{long:{type:"i",c:"center_focus_strong"},short:{type:"ui",c:"ui_blur"}},inputs:{long:{id:"ppbf",min:0,max:3e3,steps:1,evt:"updateSlideAnimation"},short:{id:"ppbm",min:0,max:100,steps:.1,evt:"updateSlideAnimation"}}})),d.appendChild(r({id:"blur_filter_settings_b",cN:"blurfilter__hide blurfilter_d3_show",labels:{long:{type:"i",c:"camera"}},inputs:{long:{id:"ppba",min:0,max:100,steps:1,evt:"updateSlideAnimation"}}})),i.appendChild(d);let l=RVS.F.cE({t:"div",id:"transpack_glitches_filter",cN:"transpack_filters"});l.appendChild(r({id:"glitches_filter_settings",labels:{long:{type:"i",c:"grain"},short:{type:"i",c:"30fps_select",cN:" inshort"}},inputs:{long:{id:"ppga",min:0,max:100,steps:1,evt:"updateSlideAnimation"},short:{id:"ppgr",min:0,max:10,steps:1,evt:"updateSlideAnimation"}}})),l.appendChild(r({id:"glitches_filter_settings_b",labels:{long:{type:"i",c:"grid_4x4"},short:{type:"i",c:"update",cN:" inshort"}},inputs:{long:{id:"ppgs",min:0,max:1,steps:.1,allowed:".",evt:"updateSlideAnimation"},short:{id:"ppgl",min:2,max:240,steps:1,evt:"updateSlideAnimation"}}})),i.appendChild(l);let p=RVS.F.cE({t:"div",id:"transpack_film_filter",cN:"transpack_filters"});p.appendChild(r({id:"film_filter_settings",labels:{long:{type:"i",c:"opacity"},short:{type:"i",c:"tune",cN:" inshort"}},inputs:{long:{id:"ppfn",min:0,max:100,steps:1,evt:"updateSlideAnimation",allowed:"none"},short:{id:"ppfs",min:0,max:100,steps:1,evt:"updateSlideAnimation",allowed:"none"}}})),p.appendChild(r({id:"film_filter_settings_b",labels:{long:{type:"i",c:"grid_4x4"}},inputs:{long:{id:"ppfh",min:0,max:500,steps:.1,allowed:".",evt:"updateSlideAnimation"}}})),p.appendChild(o({id:"film_filter_settings_c",label:{type:"i",c:s.twisteffect},select:{id:"ppfbw",cN:"",o:[{v:!0,t:s.grayscale},{v:!1,t:s.colored}],ds:{evt:"updateSlideAnimation",theme:"dark"}}})),i.appendChild(p),t.style.display="none",i.style.display="none",a.forms.W.appendChild(t),t.appendChild(n),a.forms.W.appendChild(i),a.forms.JW.find(".input_with_presets").each((function(){RVS.F.prepareOneInputWithPresets(this)})),a.forms.JW.find(".tos2.easingSelect").each((function(){RVS.F.createEaseOptions(this)})),a.forms.JW.find(".tos2.nosearchbox").ddTP({placeholder:s.placeholder_select}),RVS.F.initTpColorBoxes(a.forms.JW.find(".my-color-field")),RVS.F.updateEasyInputs({container:a.forms.JW,path:RVS.S.slideId+".layers.",trigger:"init",multiselection:!0}),RVS.F.initOnOff(a.forms.JW),RVS.C.sltaddon[e].slt_areas={rowcol:jQuery("#slttranspack_rowcol_wrap"),space_scale_ease:jQuery("#slttranspack_scale_ease_spacer"),space_rotate_ease:jQuery("#slttranspack_rotate_ease_spacer"),space_position_scale:jQuery("#slttranspack_position_scale_spacer"),sr:jQuery("#slttranspack_rotate_sr_wrap"),ie:jQuery("#slttranspack_ease_sr_wrap"),rxy:jQuery("#slttranspack_rotate_xy_wrap"),rz:jQuery("#slttranspack_rotate_z_wrap"),eg:jQuery("#slttranspack_easegrp_sr_wrap"),sxy:jQuery("#slttranspack_scale_xy_wrap"),sz:jQuery("#slttranspack_scale_z_wrap"),pxy:jQuery("#slttranspack_position_xy_wrap"),pz:jQuery("#slttranspack_position_z_wrap"),cpz:jQuery("#slttranspack_cube_position_z_wrap"),animorigin:jQuery("#slttranspack_tpack_animorigin"),filtermap:jQuery("#slttranspack_filtermap_wrap"),efffect:jQuery("#slttranspack_tpbasic_ef_wrap"),map:jQuery("#slttranspack_tpbasic_map_wrap"),mapflip:jQuery("#slttranspack_tpbasic_map_flip_wrap"),dir:jQuery("#slttranspack_tpbasic_direction_wrap"),dbas:jQuery("#slttranspack_tpbasic_directionbased_wrap"),rad:jQuery("#slttranspack_tpbasic_radius"),w:jQuery("#slttranspack_tpbasic_width"),ssxy:jQuery("#slttranspack_tpbasic_scale_xy"),twist:jQuery("#tpack_twistsettings")}}(),RVS.DOC.on("slideChanged",(function(){n()})),RVS.DOC.on("slideFocusFunctionEnd",(function(){1==RVS.S.transitionpackAddonFirstInitialisation&&requestAnimationFrame(buildAllDataStructure)})),RVS.DOC.on("updateSlideAnimationViewDefault",(function(){var t;if("transitionPack"===RVS.SLIDER[RVS.S.slideId].slide.slideChange.eng)switch(RVS.C.sltaddon[e].menu[0][0].classList.remove("disabled"),RVS.C.sltaddon[e].menu[1][0].classList.remove("disabled"),RVS.C.sltaddon[e].menu[0][0].style.width="92px",RVS.SLIDER[RVS.S.slideId].slide.slideChange.e){case"cube":for(t in RVS.C.sltran)RVS.C.sltran.hasOwnProperty(t)&&(jQuery.inArray(t,["in_xy_wrap","in_mamo_wrap","out_full_wrap","filters_wrap","pause","d3_wrap"])>=0?RVS.C.sltran[t].hide():RVS.C.sltran[t].show());for(t in RVS.C.sltaddon[e].slt_areas)RVS.C.sltaddon[e].slt_areas.hasOwnProperty(t)&&(jQuery.inArray(t,["twist","efffect","map","mapflip","animorigin","pz","dbas","dir","rad","ssxy","w"])>=0?RVS.C.sltaddon[e].slt_areas[t].hide():RVS.C.sltaddon[e].slt_areas[t].show());for(t in RVS.C.sltmenu)RVS.C.sltmenu.hasOwnProperty(t)?RVS.C.sltmenu[t][0].classList.add("disabled"):RVS.C.sltmenu[t][0].classList.remove("disabled");a.forms.stab.innerHTML=s.cubesettings;break;case"tpbasic":for(t in RVS.C.sltran)RVS.C.sltran.hasOwnProperty(t)&&(jQuery.inArray(t,["in_xy_wrap","in_mamo_wrap","out_full_wrap","flow","filters_wrap","pause","d3_wrap"])>=0?RVS.C.sltran[t].hide():RVS.C.sltran[t].show());for(t in RVS.C.sltaddon[e].slt_areas)RVS.C.sltaddon[e].slt_areas.hasOwnProperty(t)&&(jQuery.inArray(t,["filtermap","map","mapflip","dir","dbas","animorigin","ie","rad","ssxy","w"])>=0?RVS.C.sltaddon[e].slt_areas[t].show():RVS.C.sltaddon[e].slt_areas[t].hide());for(t in RVS.C.sltmenu)RVS.C.sltmenu.hasOwnProperty(t)?RVS.C.sltmenu[t][0].classList.add("disabled"):RVS.C.sltmenu[t][0].classList.remove("disabled");a.forms.stab.innerHTML=s.basic;break;case"twist":for(t in RVS.C.sltran)RVS.C.sltran.hasOwnProperty(t)&&(jQuery.inArray(t,["in_xy_wrap","in_mamo_wrap","out_full_wrap","flow","filters_wrap","pause","d3_wrap"])>=0?RVS.C.sltran[t].hide():RVS.C.sltran[t].show());for(t in RVS.C.sltaddon[e].slt_areas)RVS.C.sltaddon[e].slt_areas.hasOwnProperty(t)&&(jQuery.inArray(t,["filtermap","twist","ie","rxy","rz"])>=0?RVS.C.sltaddon[e].slt_areas[t].show():RVS.C.sltaddon[e].slt_areas[t].hide());for(t in RVS.C.sltmenu)RVS.C.sltmenu.hasOwnProperty(t)?RVS.C.sltmenu[t][0].classList.add("disabled"):RVS.C.sltmenu[t][0].classList.remove("disabled");a.forms.stab.innerHTML=s.twists}})),RVS.DOC.on("updateSlideAnimationtrpackrowcol",(function(t,a){if(void 0!==a&&void 0!==a.eventparam){var e=a.eventparam.indexOf("col")>=0?"col":"row",s="row"===e?"col":"row";RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack[e]>1&&(RVS.SLIDER[RVS.S.slideId].slide.slideChange.addOns.tpack[s]=1,document.getElementById("sltranspack_"+s).value=1)}RVS.F.redrawSlideBG()})),function(){if(revslider_transitionpack_addon.hasOwnProperty("help")&&"undefined"!=typeof HelpGuide){var a={slug:"transitionpack_addon"};t.extend(!0,a,revslider_transitionpack_addon.help),HelpGuide.add(a)}}(),a.initialised=!0,RVS.S.transitionpackAddonFirstInitialisaion=!1,RVS.F.updateSlideAnimationView()}var l,p,c,_;void 0!==RVS.SLIDER.settings.addOns[e]&&RVS.SLIDER.settings.addOns[e].enable&&a.initialised||(i("add"),function(){var t={key:"fade",main:"basic"},a=RVS.F.getSlideTransitionDefaults(t);for(var e in RVS.SLIDER.slideIDs){var s=RVS.SLIDER.slideIDs[e];void 0!==RVS.SLIDER[s]&&void 0!==RVS.SLIDER[s].slide&&"transitionPack"==RVS.SLIDER[s].slide.slideChange.eng&&(RVS.SLIDER[s].slide.slideChange=jQuery.extend(!0,{},RVS.SLIDER[s].slide.slideChange,a),""+s==""+RVS.S.slideId&&(RVS.F.updateEasyInputs({container:jQuery("#form_slide_transition"),path:RVS.S.slideId+".slide.",trigger:"init"}),RVS.F.redrawSlideBG(),RVS.F.udpateSelectedSlideAnim(!0),RVS.F.updateSlideAnimationView()))}}(),a.initialised=!1,"undefined"!=typeof HelpGuide&&HelpGuide.deactivate("transitionpack_addon"))}}))}(jQuery);