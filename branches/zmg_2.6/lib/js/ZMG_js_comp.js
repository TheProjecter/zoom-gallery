/*
zOOm Media Gallery, version 2.5.1 RC3
Copyright (C) 2006 by Mike de Boer

This code is licensed under the GPL version 2 License.

http://www.zoomfactory.org
*/
Zoom.Button=Class.create();
Zoom.Button.prototype={initialize:function(id,_2,_3,_4){
this.container=_3;
this.setOptions(_4);
this.domNode=document.createElement("div");
if(this.options.group.indexOf("members")>=0){
Element.addClassName(this.domNode,"zmg_nav_button");
}else{
Element.addClassName(this.domNode,"zmg_tb_button");
}
Element.addClassName(this.domNode,this.options.classLeave);
if(this.options.width!=null){
this.domNode.style.width=this.options.width+"px";
}
if(this.options.height!=null){
this.domNode.style.height=this.options.height+"px";
}
this.setId(id);
if(_2){
this.newLabel(_2);
}
this.container.appendChild(this.domNode);
this._attachBehaviors();
},setOptions:function(_5){
this.options={classEnter:"button_enter",classLeave:"button_leave",classClick:"button_click",classLabel:"button_label",classDisabled:"button_disabled",classLabelDisabled:"button_label_disabled",selectOnClick:false,multiSelect:false,group:null,disabled:false,width:null,height:null,actionData:null,onEnter:null,onLeave:null,onLeftClick:null,onDblClick:null,onRightClick:null,onDisable:null,onEnable:null};
Object.extend(this.options,_5||{});
},setId:function(_6){
if(typeof _6=="string"){
this.domNode.setAttribute("id",_6);
}
},getId:function(){
return this.domNode.getAttribute("id");
},newLabel:function(_7){
if(typeof _7=="string"){
_7=document.createTextNode(_7);
}
if(!this.label){
this.label=document.createElement("span");
this.label.style.width=this.options.width;
Element.addClassName(this.label,this.options.classLabel);
this.domNode.appendChild(this.label);
}
if(this.label.childNodes.length>0){
this.label.removeChild(this.label.childNodes[0]);
}
this.label.appendChild(_7);
},move:function(to){
to=$(to);
this.container=to;
this.container.appendChild(this.domNode);
},insertBefore:function(_9,_a){
_9=$(_9);
_a=$(_a);
this.container=_9;
this.container.insertBefore(this.domNode,_a);
},insertAfter:function(_b,_c){
_b=$(_b);
_c=$(_c);
this.container=_b;
this.container.insertBefore(this.domNode,_c.nextSibling);
},disable:function(){
if(!this.options.disabled&&!Element.hasClassName(this.domNode,this.options.classDisabled)){
Element.addClassName(this.domNode,this.options.classDisabled);
Element.addClassName(this.label,this.options.classLabelDisabled);
this.options.disabled=true;
if(this.options.onDisable!=null){
this.options.onDisable(this);
}
}
},enable:function(){
if(this.options.disabled&&Element.hasClassName(this.domNode,this.options.classDisabled)){
Element.removeClassName(this.domNode,this.options.classDisabled);
Element.removeClassName(this.label,this.options.classLabelDisabled);
this.options.disabled=false;
if(this.options.onEnable!=null){
this.options.onEnable(this);
}
}
},onEnter:function(e){
if(!Element.hasClassName(this.domNode,this.options.classEnter)){
Element.addClassName(this.domNode,this.options.classEnter);
}
if(this.options.onEnter!=null){
this.options.onEnter(this,e);
}
Event.stop(e);
},onLeave:function(e){
if(Element.hasClassName(this.domNode,this.options.classEnter)&&!this.selected){
Element.removeClassName(this.domNode,this.options.classEnter);
}
if(Element.hasClassName(this.domNode,this.options.classClick)&&!this.selected){
Element.removeClassName(this.domNode,this.options.classClick);
}
if(e){
if(this.options.onLeave!=null){
this.options.onLeave(this,e);
}
Event.stop(e);
}
},onClick:function(e){
if(!this.options.disabled){
if(Element.hasClassName(this.domNode,this.options.classEnter)){
Element.removeClassName(this.domNode,this.options.classEnter);
}
if(Element.hasClassName(this.domNode,this.options.classClick)&&this.selected&&!this.options.selectOnClick){
Element.removeClassName(this.domNode,this.options.classClick);
this.selected=false;
}else{
Element.addClassName(this.domNode,this.options.classClick);
this.selected=true;
}
if(e.type=="click"){
if(this.options.onLeftClick!=null){
this.options.onLeftClick(this,e);
}
}
if(e.type=="dblclick"){
if(this.options.onDblClick!=null){
this.options.onDblClick(this,e);
}
}
if(e.type=="contextmenu"){
if(this.options.onRightClick!=null){
this.options.onRightClick(this,e);
}
}
if(!this.options.selectOnClick&&Element.hasClassName(this.domNode,this.options.classClick)){
Element.removeClassName(this.domNode,this.options.classClick);
this.selected=false;
}
}
Event.stop(e);
},_attachBehaviors:function(){
this.domNode.setAttribute("rico:widget","button");
if(this.options.group!=null){
this.domNode.setAttribute("rico:group",this.options.group);
}
this.domNode.button=this;
this.selected=false;
this.domNode.onmouseover=this.onEnter.bindAsEventListener(this);
this.domNode.onmouseout=this.onLeave.bindAsEventListener(this);
this.domNode.onclick=this.onClick.bindAsEventListener(this);
this.domNode.ondblclick=this.onClick.bindAsEventListener(this);
this.domNode.oncontextmenu=this.onClick.bindAsEventListener(this);
}};


var Droppables={drops:[],remove:function(_1){
this.drops=this.drops.reject(function(d){
return d.element==$(_1);
});
},add:function(_3){
_3=$(_3);
var _4=Object.extend({greedy:true,hoverclass:null},arguments[1]||{});
if(_4.containment){
_4._containers=[];
var _5=_4.containment;
if((typeof _5=="object")&&(_5.constructor==Array)){
_5.each(function(c){
_4._containers.push($(c));
});
}else{
_4._containers.push($(_5));
}
}
if(_4.accept){
_4.accept=[_4.accept].flatten();
}
Element.makePositioned(_3);
_4.element=_3;
this.drops.push(_4);
},isContained:function(_7,_8){
var _9=_7.parentNode;
return _8._containers.detect(function(c){
return _9==c;
});
},isAffected:function(_b,_c,_d){
return ((_d.element!=_c)&&((!_d._containers)||this.isContained(_c,_d))&&((!_d.accept)||(Element.classNames(_c).detect(function(v){
return _d.accept.include(v);
})))&&Position.within(_d.element,_b[0],_b[1]));
},deactivate:function(_f){
if(_f.hoverclass){
Element.removeClassName(_f.element,_f.hoverclass);
}
this.last_active=null;
},activate:function(_10){
if(_10.hoverclass){
Element.addClassName(_10.element,_10.hoverclass);
}
this.last_active=_10;
},show:function(_11,_12){
if(!this.drops.length){
return;
}
if(this.last_active){
this.deactivate(this.last_active);
}
this.drops.each(function(_13){
if(Droppables.isAffected(_11,_12,_13)){
if(_13.onHover){
_13.onHover(_12,_13.element,Position.overlap(_13.overlap,_13.element));
}
if(_13.greedy){
Droppables.activate(_13);
throw $break;
}
}
});
},fire:function(_14,_15){
if(!this.last_active){
return;
}
Position.prepare();
if(this.isAffected([Event.pointerX(_14),Event.pointerY(_14)],_15,this.last_active)){
if(this.last_active.onDrop){
this.last_active.onDrop(_15,this.last_active.element,_14);
}
}
},reset:function(){
if(this.last_active){
this.deactivate(this.last_active);
}
}};
var Draggables={drags:[],observers:[],register:function(_16){
if(this.drags.length==0){
this.eventMouseUp=this.endDrag.bindAsEventListener(this);
this.eventMouseMove=this.updateDrag.bindAsEventListener(this);
this.eventKeypress=this.keyPress.bindAsEventListener(this);
Event.observe(document,"mouseup",this.eventMouseUp);
Event.observe(document,"mousemove",this.eventMouseMove);
Event.observe(document,"keypress",this.eventKeypress);
}
this.drags.push(_16);
},unregister:function(_17){
this.drags=this.drags.reject(function(d){
return d==_17;
});
if(this.drags.length==0){
Event.stopObserving(document,"mouseup",this.eventMouseUp);
Event.stopObserving(document,"mousemove",this.eventMouseMove);
Event.stopObserving(document,"keypress",this.eventKeypress);
}
},activate:function(_19){
window.focus();
this.activeDraggable=_19;
},deactivate:function(){
this.activeDraggable=null;
},updateDrag:function(_1a){
if(!this.activeDraggable){
return;
}
var _1b=[Event.pointerX(_1a),Event.pointerY(_1a)];
if(this._lastPointer&&(this._lastPointer.inspect()==_1b.inspect())){
return;
}
this._lastPointer=_1b;
this.activeDraggable.updateDrag(_1a,_1b);
},endDrag:function(_1c){
if(!this.activeDraggable){
return;
}
this._lastPointer=null;
this.activeDraggable.endDrag(_1c);
this.activeDraggable=null;
},keyPress:function(_1d){
if(this.activeDraggable){
this.activeDraggable.keyPress(_1d);
}
},addObserver:function(_1e){
this.observers.push(_1e);
this._cacheObserverCallbacks();
},removeObserver:function(_1f){
this.observers=this.observers.reject(function(o){
return o.element==_1f;
});
this._cacheObserverCallbacks();
},notify:function(_21,_22,_23){
if(this[_21+"Count"]>0){
this.observers.each(function(o){
if(o[_21]){
o[_21](_21,_22,_23);
}
});
}
},_cacheObserverCallbacks:function(){
["onStart","onEnd","onDrag"].each(function(_25){
Draggables[_25+"Count"]=Draggables.observers.select(function(o){
return o[_25];
}).length;
});
}};
var Draggable=Class.create();
Draggable.prototype={initialize:function(_27){
var _28=Object.extend({handle:false,starteffect:function(_29){
new Effect.Opacity(_29,{duration:0.2,from:1,to:0.7});
},reverteffect:function(_2a,_2b,_2c){
var dur=Math.sqrt(Math.abs(_2b^2)+Math.abs(_2c^2))*0.02;
_2a._revert=new Effect.Move(_2a,{x:-_2c,y:-_2b,duration:dur});
},endeffect:function(_2e){
new Effect.Opacity(_2e,{duration:0.2,from:0.7,to:1});
},zindex:1000,revert:false,scroll:false,scrollSensitivity:20,scrollSpeed:15,snap:false},arguments[1]||{});
this.element=$(_27);
if(_28.handle&&(typeof _28.handle=="string")){
this.handle=Element.childrenWithClassName(this.element,_28.handle)[0];
}
if(!this.handle){
this.handle=$(_28.handle);
}
if(!this.handle){
this.handle=this.element;
}
if(_28.scroll){
_28.scroll=$(_28.scroll);
}
Element.makePositioned(this.element);
this.delta=this.currentDelta();
this.options=_28;
this.dragging=false;
this.eventMouseDown=this.initDrag.bindAsEventListener(this);
Event.observe(this.handle,"mousedown",this.eventMouseDown);
Draggables.register(this);
},destroy:function(){
Event.stopObserving(this.handle,"mousedown",this.eventMouseDown);
Draggables.unregister(this);
},currentDelta:function(){
return ([parseInt(Element.getStyle(this.element,"left")||"0"),parseInt(Element.getStyle(this.element,"top")||"0")]);
},initDrag:function(_2f){
if(Event.isLeftClick(_2f)){
var src=Event.element(_2f);
if(src.tagName&&(src.tagName=="INPUT"||src.tagName=="SELECT"||src.tagName=="OPTION"||src.tagName=="BUTTON"||src.tagName=="TEXTAREA")){
return;
}
if(this.element._revert){
this.element._revert.cancel();
this.element._revert=null;
}
var _31=[Event.pointerX(_2f),Event.pointerY(_2f)];
var pos=Position.cumulativeOffset(this.element);
this.offset=[0,1].map(function(i){
return (_31[i]-pos[i]);
});
Draggables.activate(this);
Event.stop(_2f);
}
},startDrag:function(_34){
this.dragging=true;
if(this.options.zindex){
this.originalZ=parseInt(Element.getStyle(this.element,"z-index")||0);
this.element.style.zIndex=this.options.zindex;
}
if(this.options.ghosting){
this._clone=this.element.cloneNode(true);
Position.absolutize(this.element);
this._clone.style.zIndex=500;
this.element.parentNode.insertBefore(this._clone,this.element);
}
if(this.options.scroll){
this.originalScrollLeft=this.options.scroll.scrollLeft;
this.originalScrollTop=this.options.scroll.scrollTop;
}
Draggables.notify("onStart",this,_34);
if(this.options.starteffect){
this.options.starteffect(this.element);
}
},updateDrag:function(_35,_36){
if(!this.dragging){
this.startDrag(_35);
}
Position.prepare();
Droppables.show(_36,this.element);
Draggables.notify("onDrag",this,_35);
this.draw(_36);
if(this.options.change){
this.options.change(this);
}
if(this.options.scroll){
this.stopScrolling();
var p=Position.page(this.options.scroll);
p[0]+=this.options.scroll.scrollLeft;
p[1]+=this.options.scroll.scrollTop;
p.push(p[0]+this.options.scroll.offsetWidth);
p.push(p[1]+this.options.scroll.offsetHeight);
var _38=[0,0];
if(_36[0]<(p[0]+this.options.scrollSensitivity)){
_38[0]=_36[0]-(p[0]+this.options.scrollSensitivity);
}
if(_36[1]<(p[1]+this.options.scrollSensitivity)){
_38[1]=_36[1]-(p[1]+this.options.scrollSensitivity);
}
if(_36[0]>(p[2]-this.options.scrollSensitivity)){
_38[0]=_36[0]-(p[2]-this.options.scrollSensitivity);
}
if(_36[1]>(p[3]-this.options.scrollSensitivity)){
_38[1]=_36[1]-(p[3]-this.options.scrollSensitivity);
}
this.startScrolling(_38);
}
if(navigator.appVersion.indexOf("AppleWebKit")>0){
window.scrollBy(0,0);
}
Event.stop(_35);
},finishDrag:function(_39,_3a){
this.dragging=false;
if(this.options.ghosting){
Position.relativize(this.element);
Element.remove(this._clone);
this._clone=null;
}
if(_3a){
Droppables.fire(_39,this.element);
}
Draggables.notify("onEnd",this,_39);
var _3b=this.options.revert;
if(_3b&&typeof _3b=="function"){
_3b=_3b(this.element);
}
var d=this.currentDelta();
if(_3b&&this.options.reverteffect){
this.options.reverteffect(this.element,d[1]-this.delta[1],d[0]-this.delta[0]);
}else{
this.delta=d;
}
if(this.options.zindex){
this.element.style.zIndex=this.originalZ;
}
if(this.options.endeffect){
this.options.endeffect(this.element);
}
Draggables.deactivate(this);
Droppables.reset();
},keyPress:function(_3d){
if(_3d.keyCode!=Event.KEY_ESC){
return;
}
this.finishDrag(_3d,false);
Event.stop(_3d);
},endDrag:function(_3e){
if(!this.dragging){
return;
}
this.stopScrolling();
this.finishDrag(_3e,true);
Event.stop(_3e);
},draw:function(_3f){
var pos=Position.cumulativeOffset(this.element);
var d=this.currentDelta();
pos[0]-=d[0];
pos[1]-=d[1];
if(this.options.scroll){
pos[0]-=this.options.scroll.scrollLeft-this.originalScrollLeft;
pos[1]-=this.options.scroll.scrollTop-this.originalScrollTop;
}
var p=[0,1].map(function(i){
return (_3f[i]-pos[i]-this.offset[i]);
}.bind(this));
if(this.options.snap){
if(typeof this.options.snap=="function"){
p=this.options.snap(p[0],p[1]);
}else{
if(this.options.snap instanceof Array){
p=p.map(function(v,i){
return Math.round(v/this.options.snap[i])*this.options.snap[i];
}.bind(this));
}else{
p=p.map(function(v){
return Math.round(v/this.options.snap)*this.options.snap;
}.bind(this));
}
}
}
var _47=this.element.style;
if((!this.options.constraint)||(this.options.constraint=="horizontal")){
_47.left=p[0]+"px";
}
if((!this.options.constraint)||(this.options.constraint=="vertical")){
_47.top=p[1]+"px";
}
if(_47.visibility=="hidden"){
_47.visibility="";
}
},stopScrolling:function(){
if(this.scrollInterval){
clearInterval(this.scrollInterval);
this.scrollInterval=null;
}
},startScrolling:function(_48){
this.scrollSpeed=[_48[0]*this.options.scrollSpeed,_48[1]*this.options.scrollSpeed];
this.lastScrolled=new Date();
this.scrollInterval=setInterval(this.scroll.bind(this),10);
},scroll:function(){
var _49=new Date();
var _4a=_49-this.lastScrolled;
this.lastScrolled=_49;
this.options.scroll.scrollLeft+=this.scrollSpeed[0]*_4a/1000;
this.options.scroll.scrollTop+=this.scrollSpeed[1]*_4a/1000;
Position.prepare();
Droppables.show(Draggables._lastPointer,this.element);
Draggables.notify("onDrag",this);
this.draw(Draggables._lastPointer);
if(this.options.change){
this.options.change(this);
}
}};
var SortableObserver=Class.create();
SortableObserver.prototype={initialize:function(_4b,_4c){
this.element=$(_4b);
this.observer=_4c;
this.lastValue=Sortable.serialize(this.element);
},onStart:function(){
this.lastValue=Sortable.serialize(this.element);
},onEnd:function(){
Sortable.unmark();
if(this.lastValue!=Sortable.serialize(this.element)){
this.observer(this.element);
}
}};
var Sortable={sortables:new Array(),options:function(_4d){
_4d=$(_4d);
return this.sortables.detect(function(s){
return s.element==_4d;
});
},destroy:function(_4f){
_4f=$(_4f);
this.sortables.findAll(function(s){
return s.element==_4f;
}).each(function(s){
Draggables.removeObserver(s.element);
s.droppables.each(function(d){
Droppables.remove(d);
});
s.draggables.invoke("destroy");
});
this.sortables=this.sortables.reject(function(s){
return s.element==_4f;
});
},create:function(_54){
_54=$(_54);
var _55=Object.extend({element:_54,tag:"li",dropOnEmpty:false,tree:false,overlap:"vertical",constraint:"vertical",containment:_54,handle:false,only:false,hoverclass:null,ghosting:false,scroll:false,format:/^[^_]*_(.*)$/,onChange:Prototype.emptyFunction,onUpdate:Prototype.emptyFunction},arguments[1]||{});
this.destroy(_54);
var _56={revert:true,scroll:_55.scroll,ghosting:_55.ghosting,constraint:_55.constraint,handle:_55.handle};
if(_55.starteffect){
_56.starteffect=_55.starteffect;
}
if(_55.reverteffect){
_56.reverteffect=_55.reverteffect;
}else{
if(_55.ghosting){
_56.reverteffect=function(_57){
_57.style.top=0;
_57.style.left=0;
};
}
}
if(_55.endeffect){
_56.endeffect=_55.endeffect;
}
if(_55.zindex){
_56.zindex=_55.zindex;
}
var _58={overlap:_55.overlap,containment:_55.containment,hoverclass:_55.hoverclass,onHover:Sortable.onHover,greedy:!_55.dropOnEmpty};
Element.cleanWhitespace(_54);
_55.draggables=[];
_55.droppables=[];
if(_55.dropOnEmpty){
Droppables.add(_54,{containment:_55.containment,onHover:Sortable.onEmptyHover,greedy:false});
_55.droppables.push(_54);
}
(this.findElements(_54,_55)||[]).each(function(e){
var _5a=_55.handle?Element.childrenWithClassName(e,_55.handle)[0]:e;
_55.draggables.push(new Draggable(e,Object.extend(_56,{handle:_5a})));
Droppables.add(e,_58);
_55.droppables.push(e);
});
this.sortables.push(_55);
Draggables.addObserver(new SortableObserver(_54,_55.onUpdate));
},findElements:function(_5b,_5c){
if(!_5b.hasChildNodes()){
return null;
}
var _5d=[];
$A(_5b.childNodes).each(function(e){
if(e.tagName&&e.tagName.toUpperCase()==_5c.tag.toUpperCase()&&(!_5c.only||(Element.hasClassName(e,_5c.only)))){
_5d.push(e);
}
if(_5c.tree){
var _5f=this.findElements(e,_5c);
if(_5f){
_5d.push(_5f);
}
}
});
return (_5d.length>0?_5d.flatten():null);
},onHover:function(_60,_61,_62){
if(_62>0.5){
Sortable.mark(_61,"before");
if(_61.previousSibling!=_60){
var _63=_60.parentNode;
_60.style.visibility="hidden";
_61.parentNode.insertBefore(_60,_61);
if(_61.parentNode!=_63){
Sortable.options(_63).onChange(_60);
}
Sortable.options(_61.parentNode).onChange(_60);
}
}else{
Sortable.mark(_61,"after");
var _64=_61.nextSibling||null;
if(_64!=_60){
var _63=_60.parentNode;
_60.style.visibility="hidden";
_61.parentNode.insertBefore(_60,_64);
if(_61.parentNode!=_63){
Sortable.options(_63).onChange(_60);
}
Sortable.options(_61.parentNode).onChange(_60);
}
}
},onEmptyHover:function(_65,_66){
if(_65.parentNode!=_66){
var _67=_65.parentNode;
_66.appendChild(_65);
Sortable.options(_67).onChange(_65);
Sortable.options(_66).onChange(_65);
}
},unmark:function(){
if(Sortable._marker){
Element.hide(Sortable._marker);
}
},mark:function(_68,_69){
var _6a=Sortable.options(_68.parentNode);
if(_6a&&!_6a.ghosting){
return;
}
if(!Sortable._marker){
Sortable._marker=$("dropmarker")||document.createElement("DIV");
Element.hide(Sortable._marker);
Element.addClassName(Sortable._marker,"dropmarker");
Sortable._marker.style.position="absolute";
document.getElementsByTagName("body").item(0).appendChild(Sortable._marker);
}
var _6b=Position.cumulativeOffset(_68);
Sortable._marker.style.left=_6b[0]+"px";
Sortable._marker.style.top=_6b[1]+"px";
if(_69=="after"){
if(_6a.overlap=="horizontal"){
Sortable._marker.style.left=(_6b[0]+_68.clientWidth)+"px";
}else{
Sortable._marker.style.top=(_6b[1]+_68.clientHeight)+"px";
}
}
Element.show(Sortable._marker);
},sequence:function(_6c){
_6c=$(_6c);
var _6d=Object.extend(this.options(_6c),arguments[1]||{});
return $(this.findElements(_6c,_6d)||[]).map(function(_6e){
return _6e.id.match(_6d.format)?_6e.id.match(_6d.format)[1]:"";
});
},setSequence:function(_6f,_70){
_6f=$(_6f);
var _71=Object.extend(this.options(_6f),arguments[2]||{});
var _72={};
this.findElements(_6f,_71).each(function(n){
if(n.id.match(_71.format)){
_72[n.id.match(_71.format)[1]]=[n,n.parentNode];
}
n.parentNode.removeChild(n);
});
_70.each(function(_74){
var n=_72[_74];
if(n){
n[1].appendChild(n[0]);
delete _72[_74];
}
});
},serialize:function(_76){
_76=$(_76);
var _77=encodeURIComponent((arguments[1]&&arguments[1].name)?arguments[1].name:_76.id);
return Sortable.sequence(_76,arguments[1]).map(function(_78){
return _77+"[]="+encodeURIComponent(_78);
}).join("&");
}};
Draggables.addObserver({onStart:function(_79,_7a,e){
try{
Zoom.dragTree(_7a);
}
catch(e){
}
},onDrag:function(_7c,_7d,e){
try{
Zoom.dragTree(_7d);
}
catch(e){
}
},onEnd:function(_7f,_80,e){
try{
Zoom.dragTree(_80);
}
catch(e){
}
}});


function Node(id,_2,_3,_4,_5,_6,_7,_8,_9,_a,_b){
this.id=id;
this.pid=_2;
this.name=_3;
this.url=_4;
this.title=_5;
this.target=_6;
this.icon=_7;
this.iconOpen=_8;
this.moverAction=_a||null;
this.moutAction=_b||null;
this._io=_9||false;
this._is=false;
this._ls=false;
this._hc=false;
this._ai=0;
this._p;
}
function dTree(_c){
this.config={target:null,folderLinks:true,useSelection:true,useCookies:true,useLines:true,useIcons:true,useStatusText:false,closeSameLevel:false,inOrder:false,iconPrefix:"components/com_zoom/www/dtree/"};
this.icon={root:"base.gif",folder:"folder.gif",folderOpen:"folderopen.gif",node:"page.gif",empty:"empty.gif",line:"line.gif",join:"join.gif",joinBottom:"joinbottom.gif",plus:"plus.gif",plusBottom:"plusbottom.gif",minus:"minus.gif",minusBottom:"minusbottom.gif",nlPlus:"nolines_plus.gif",nlMinus:"nolines_minus.gif"};
this.obj=_c;
this.aNodes=[];
this.aIndent=[];
this.root=new Node(-1);
this.selectedNode=null;
this.selectedFound=false;
this.completed=false;
}
dTree.prototype.add=function(id,_e,_f,url,_11,_12,_13,_14,_15,_16,_17){
this.aNodes[this.aNodes.length]=new Node(id,_e,_f,url,_11,_12,_13,_14,_15,_16,_17);
};
dTree.prototype.openAll=function(){
this.oAll(true);
};
dTree.prototype.closeAll=function(){
this.oAll(false);
};
dTree.prototype.toString=function(){
var str="<div class=\"dtree\">\n";
if(document.getElementById){
if(this.config.useCookies){
this.selectedNode=this.getSelected();
}
str+=this.addNode(this.root);
}else{
str+="Browser not supported.";
}
str+="</div>";
if(!this.selectedFound){
this.selectedNode=null;
}
this.completed=true;
return str;
};
dTree.prototype.addNode=function(_19){
var str="";
var n=0;
if(this.config.inOrder){
n=_19._ai;
}
for(n;n<this.aNodes.length;n++){
if(this.aNodes[n].pid==_19.id){
var cn=this.aNodes[n];
cn._p=_19;
cn._ai=n;
this.setCS(cn);
if(!cn.target&&this.config.target){
cn.target=this.config.target;
}
if(cn._hc&&!cn._io&&this.config.useCookies){
cn._io=this.isOpen(cn.id);
}
if(!this.config.folderLinks&&cn._hc){
cn.url=null;
}
if(this.config.useSelection&&cn.id==this.selectedNode&&!this.selectedFound){
cn._is=true;
this.selectedNode=n;
this.selectedFound=true;
}
str+=this.node(cn,n);
if(cn._ls){
break;
}
}
}
return str;
};
dTree.prototype.node=function(_1d,_1e){
var str="<div class=\"dTreeNode\">"+this.indent(_1d,_1e);
if(this.config.useIcons){
if(!_1d.icon){
_1d.icon=(this.root.id==_1d.pid)?this.config.iconPrefix+this.icon.root:((_1d._hc)?this.config.iconPrefix+this.icon.folder:this.config.iconPrefix+this.icon.node);
}
if(!_1d.iconOpen){
_1d.iconOpen=(_1d._hc)?this.config.iconPrefix+this.icon.folderOpen:this.config.iconPrefix+this.icon.node;
}
if(this.root.id==_1d.pid){
_1d.icon=this.config.iconPrefix+this.icon.root;
_1d.iconOpen=this.config.iconPrefix+this.icon.root;
}
str+="<img id=\"i"+this.obj+_1e+"\" src=\""+((_1d._io)?_1d.iconOpen:_1d.icon)+"\" alt=\"\" />";
}
if(_1d.url){
str+="<a id=\"s"+this.obj+_1e+"\" class=\""+((this.config.useSelection)?((_1d._is?"nodeSel":"node")):"node")+"\" href=\""+_1d.url+"\"";
if(_1d.title){
str+=" title=\""+_1d.title+"\"";
}
if(_1d.target){
str+=" target=\""+_1d.target+"\"";
}
if(this.config.useStatusText){
str+=" onmouseover=\"window.status='"+_1d.name+"';return true;\" onmouseout=\"window.status='';return true;\" ";
}
if(!this.config.useStatusText&&_1d.moverAction!=null){
str+=" onmouseover=\""+_1d.moverAction+"\" ";
}
if(!this.config.useStatusText&&_1d.moutAction!=null){
str+=" onmouseout=\""+_1d.moutAction+"\" ";
}
if(this.config.useSelection&&((_1d._hc&&this.config.folderLinks)||!_1d._hc)){
str+=" onclick=\""+this.obj+".s("+_1e+");\"";
}
str+=">";
}else{
if((!this.config.folderLinks||!_1d.url)&&_1d._hc&&_1d.pid!=this.root.id){
str+="<a href=\"javascript: "+this.obj+".o("+_1e+");\" class=\"node\">";
}
}
str+=_1d.name;
if(_1d.url||((!this.config.folderLinks||!_1d.url)&&_1d._hc)){
str+="</a>";
}
str+="</div>";
if(_1d._hc){
str+="<div id=\"d"+this.obj+_1e+"\" class=\"clip\" style=\"display:"+((this.root.id==_1d.pid||_1d._io)?"block":"none")+";\">";
str+=this.addNode(_1d);
str+="</div>";
}
this.aIndent.pop();
return str;
};
dTree.prototype.indent=function(_20,_21){
var str="";
if(this.root.id!=_20.pid){
for(var n=0;n<this.aIndent.length;n++){
str+="<img src=\""+((this.aIndent[n]==1&&this.config.useLines)?this.config.iconPrefix+this.icon.line:this.config.iconPrefix+this.icon.empty)+"\" alt=\"\" />";
}
(_20._ls)?this.aIndent.push(0):this.aIndent.push(1);
if(_20._hc){
str+="<a href=\"javascript: "+this.obj+".o("+_21+");\"><img id=\"j"+this.obj+_21+"\" src=\"";
if(!this.config.useLines){
str+=(_20._io)?this.config.iconPrefix+this.icon.nlMinus:this.config.iconPrefix+this.icon.nlPlus;
}else{
str+=((_20._io)?((_20._ls&&this.config.useLines)?this.config.iconPrefix+this.icon.minusBottom:this.config.iconPrefix+this.icon.minus):((_20._ls&&this.config.useLines)?this.config.iconPrefix+this.icon.plusBottom:this.config.iconPrefix+this.icon.plus));
}
str+="\" alt=\"\" /></a>";
}else{
str+="<img src=\""+((this.config.useLines)?((_20._ls)?this.config.iconPrefix+this.icon.joinBottom:this.config.iconPrefix+this.icon.join):this.config.iconPrefix+this.icon.empty)+"\" alt=\"\" />";
}
}
return str;
};
dTree.prototype.setCS=function(_24){
var _25;
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n].pid==_24.id){
_24._hc=true;
}
if(this.aNodes[n].pid==_24.pid){
_25=this.aNodes[n].id;
}
}
if(_25==_24.id){
_24._ls=true;
}
};
dTree.prototype.getSelected=function(){
var sn=this.getCookie("cs"+this.obj);
return (sn)?sn:null;
};
dTree.prototype.s=function(id){
if(!this.config.useSelection){
return;
}
var cn=this.aNodes[id];
if(cn._hc&&!this.config.folderLinks){
return;
}
if(this.selectedNode!=id){
if(this.selectedNode||this.selectedNode==0){
eOld=document.getElementById("s"+this.obj+this.selectedNode);
eOld.className="node";
}
eNew=document.getElementById("s"+this.obj+id);
eNew.className="nodeSel";
this.selectedNode=id;
if(this.config.useCookies){
this.setCookie("cs"+this.obj,cn.id);
}
}
};
dTree.prototype.o=function(id){
var cn=this.aNodes[id];
this.nodeStatus(!cn._io,id,cn._ls);
cn._io=!cn._io;
if(this.config.closeSameLevel){
this.closeLevel(cn);
}
if(this.config.useCookies){
this.updateCookie();
}
};
dTree.prototype.oAll=function(_2c){
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n]._hc&&this.aNodes[n].pid!=this.root.id){
this.nodeStatus(_2c,n,this.aNodes[n]._ls);
this.aNodes[n]._io=_2c;
}
}
if(this.config.useCookies){
this.updateCookie();
}
};
dTree.prototype.openTo=function(nId,_2f,_30){
if(!_30){
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n].id==nId){
nId=n;
break;
}
}
}
var cn=this.aNodes[nId];
if(cn.pid==this.root.id||!cn._p){
return;
}
cn._io=true;
cn._is=_2f;
if(this.completed&&cn._hc){
this.nodeStatus(true,cn._ai,cn._ls);
}
if(this.completed&&_2f){
this.s(cn._ai);
}else{
if(_2f){
this._sn=cn._ai;
}
}
this.openTo(cn._p._ai,false,true);
};
dTree.prototype.closeLevel=function(_33){
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n].pid==_33.pid&&this.aNodes[n].id!=_33.id&&this.aNodes[n]._hc){
this.nodeStatus(false,n,this.aNodes[n]._ls);
this.aNodes[n]._io=false;
this.closeAllChildren(this.aNodes[n]);
}
}
};
dTree.prototype.closeAllChildren=function(_35){
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n].pid==_35.id&&this.aNodes[n]._hc){
if(this.aNodes[n]._io){
this.nodeStatus(false,n,this.aNodes[n]._ls);
}
this.aNodes[n]._io=false;
this.closeAllChildren(this.aNodes[n]);
}
}
};
dTree.prototype.nodeStatus=function(_37,id,_39){
eDiv=document.getElementById("d"+this.obj+id);
eJoin=document.getElementById("j"+this.obj+id);
if(this.config.useIcons){
eIcon=document.getElementById("i"+this.obj+id);
eIcon.src=(_37)?this.aNodes[id].iconOpen:this.aNodes[id].icon;
}
eJoin.src=(this.config.useLines)?((_37)?((_39)?this.config.iconPrefix+this.icon.minusBottom:this.config.iconPrefix+this.icon.minus):((_39)?this.config.iconPrefix+this.icon.plusBottom:this.config.iconPrefix+this.icon.plus)):((_37)?this.config.iconPrefix+this.icon.nlMinus:this.config.iconPrefix+this.icon.nlPlus);
eDiv.style.display=(_37)?"block":"none";
};
dTree.prototype.clearCookie=function(){
var now=new Date();
var _3b=new Date(now.getTime()-1000*60*60*24);
this.setCookie("co"+this.obj,"cookieValue",_3b);
this.setCookie("cs"+this.obj,"cookieValue",_3b);
};
dTree.prototype.setCookie=function(_3c,_3d,_3e,_3f,_40,_41){
document.cookie=escape(_3c)+"="+escape(_3d)+(_3e?"; expires="+_3e.toGMTString():"")+(_3f?"; path="+_3f:"")+(_40?"; domain="+_40:"")+(_41?"; secure":"");
};
dTree.prototype.getCookie=function(_42){
var _43="";
var _44=document.cookie.indexOf(escape(_42)+"=");
if(_44!=-1){
var _45=_44+(escape(_42)+"=").length;
var _46=document.cookie.indexOf(";",_45);
if(_46!=-1){
_43=unescape(document.cookie.substring(_45,_46));
}else{
_43=unescape(document.cookie.substring(_45));
}
}
return (_43);
};
dTree.prototype.updateCookie=function(){
var str="";
for(var n=0;n<this.aNodes.length;n++){
if(this.aNodes[n]._io&&this.aNodes[n].pid!=this.root.id){
if(str){
str+=".";
}
str+=this.aNodes[n].id;
}
}
this.setCookie("co"+this.obj,str);
};
dTree.prototype.isOpen=function(id){
var _4a=this.getCookie("co"+this.obj).split(".");
for(var n=0;n<_4a.length;n++){
if(_4a[n]==id){
return true;
}
}
return false;
};
if(!Array.prototype.push){
Array.prototype.push=function array_push(){
for(var i=0;i<arguments.length;i++){
this[this.length]=arguments[i];
}
return this.length;
};
}
if(!Array.prototype.pop){
Array.prototype.pop=function array_pop(){
lastElement=this[this.length-1];
this.length=Math.max(this.length-1,0);
return lastElement;
};
}


String.prototype.parseColor=function(){
var _1="#";
if(this.slice(0,4)=="rgb("){
var _2=this.slice(4,this.length-1).split(",");
var i=0;
do{
_1+=parseInt(_2[i]).toColorPart();
}while(++i<3);
}else{
if(this.slice(0,1)=="#"){
if(this.length==4){
for(var i=1;i<4;i++){
_1+=(this.charAt(i)+this.charAt(i)).toLowerCase();
}
}
if(this.length==7){
_1=this.toLowerCase();
}
}
}
return (_1.length==7?_1:(arguments[0]||this));
};
Element.collectTextNodes=function(_4){
return $A($(_4).childNodes).collect(function(_5){
return (_5.nodeType==3?_5.nodeValue:(_5.hasChildNodes()?Element.collectTextNodes(_5):""));
}).flatten().join("");
};
Element.collectTextNodesIgnoreClass=function(_6,_7){
return $A($(_6).childNodes).collect(function(_8){
return (_8.nodeType==3?_8.nodeValue:((_8.hasChildNodes()&&!Element.hasClassName(_8,_7))?Element.collectTextNodesIgnoreClass(_8,_7):""));
}).flatten().join("");
};
Element.setContentZoom=function(_9,_a){
_9=$(_9);
Element.setStyle(_9,{fontSize:(_a/100)+"em"});
if(navigator.appVersion.indexOf("AppleWebKit")>0){
window.scrollBy(0,0);
}
};
Element.getOpacity=function(_b){
var _c;
if(_c=Element.getStyle(_b,"opacity")){
return parseFloat(_c);
}
if(_c=(Element.getStyle(_b,"filter")||"").match(/alpha\(opacity=(.*)\)/)){
if(_c[1]){
return parseFloat(_c[1])/100;
}
}
return 1;
};
Element.setOpacity=function(_d,_e){
_d=$(_d);
if(_e==1){
Element.setStyle(_d,{opacity:(/Gecko/.test(navigator.userAgent)&&!/Konqueror|Safari|KHTML/.test(navigator.userAgent))?0.999999:null});
if(/MSIE/.test(navigator.userAgent)){
Element.setStyle(_d,{filter:Element.getStyle(_d,"filter").replace(/alpha\([^\)]*\)/gi,"")});
}
}else{
if(_e<0.00001){
_e=0;
}
Element.setStyle(_d,{opacity:_e});
if(/MSIE/.test(navigator.userAgent)){
Element.setStyle(_d,{filter:Element.getStyle(_d,"filter").replace(/alpha\([^\)]*\)/gi,"")+"alpha(opacity="+_e*100+")"});
}
}
};
Element.getInlineOpacity=function(_f){
return $(_f).style.opacity||"";
};
Element.childrenWithClassName=function(_10,_11,_12){
var _13=new RegExp("(^|\\s)"+_11+"(\\s|$)");
var _14=$A($(_10).getElementsByTagName("*"))[_12?"detect":"select"](function(c){
return (c.className&&c.className.match(_13));
});
if(!_14){
_14=[];
}
return _14;
};
Element.forceRerendering=function(_16){
try{
_16=$(_16);
var n=document.createTextNode(" ");
_16.appendChild(n);
_16.removeChild(n);
}
catch(e){
}
};
Array.prototype.call=function(){
var _18=arguments;
this.each(function(f){
f.apply(this,_18);
});
};
var Effect={tagifyText:function(_1a){
if(typeof Builder=="undefined"){
throw ("Effect.tagifyText requires including script.aculo.us' builder.js library");
}
var _1b="position:relative";
if(/MSIE/.test(navigator.userAgent)){
_1b+=";zoom:1";
}
_1a=$(_1a);
$A(_1a.childNodes).each(function(_1c){
if(_1c.nodeType==3){
_1c.nodeValue.toArray().each(function(_1d){
_1a.insertBefore(Builder.node("span",{style:_1b},_1d==" "?String.fromCharCode(160):_1d),_1c);
});
Element.remove(_1c);
}
});
},multiple:function(_1e,_1f){
var _20;
if(((typeof _1e=="object")||(typeof _1e=="function"))&&(_1e.length)){
_20=_1e;
}else{
_20=$(_1e).childNodes;
}
var _21=Object.extend({speed:0.1,delay:0},arguments[2]||{});
var _22=_21.delay;
$A(_20).each(function(_23,_24){
new _1f(_23,Object.extend(_21,{delay:_24*_21.speed+_22}));
});
},PAIRS:{"slide":["SlideDown","SlideUp"],"blind":["BlindDown","BlindUp"],"appear":["Appear","Fade"]},toggle:function(_25,_26){
_25=$(_25);
_26=(_26||"appear").toLowerCase();
var _27=Object.extend({queue:{position:"end",scope:(_25.id||"global"),limit:1}},arguments[2]||{});
Effect[_25.visible()?Effect.PAIRS[_26][1]:Effect.PAIRS[_26][0]](_25,_27);
}};
var Effect2=Effect;
Effect.Transitions={};
Effect.Transitions.linear=Prototype.K;
Effect.Transitions.sinoidal=function(pos){
return (-Math.cos(pos*Math.PI)/2)+0.5;
};
Effect.Transitions.reverse=function(pos){
return 1-pos;
};
Effect.Transitions.flicker=function(pos){
return ((-Math.cos(pos*Math.PI)/4)+0.75)+Math.random()/4;
};
Effect.Transitions.wobble=function(pos){
return (-Math.cos(pos*Math.PI*(9*pos))/2)+0.5;
};
Effect.Transitions.pulse=function(pos){
return (Math.floor(pos*10)%2==0?(pos*10-Math.floor(pos*10)):1-(pos*10-Math.floor(pos*10)));
};
Effect.Transitions.none=function(pos){
return 0;
};
Effect.Transitions.full=function(pos){
return 1;
};
Effect.ScopedQueue=Class.create();
Object.extend(Object.extend(Effect.ScopedQueue.prototype,Enumerable),{initialize:function(){
this.effects=[];
this.interval=null;
},_each:function(_2f){
this.effects._each(_2f);
},add:function(_30){
var _31=new Date().getTime();
var _32=(typeof _30.options.queue=="string")?_30.options.queue:_30.options.queue.position;
switch(_32){
case "front":
this.effects.findAll(function(e){
return e.state=="idle";
}).each(function(e){
e.startOn+=_30.finishOn;
e.finishOn+=_30.finishOn;
});
break;
case "end":
_31=this.effects.pluck("finishOn").max()||_31;
break;
}
_30.startOn+=_31;
_30.finishOn+=_31;
if(!_30.options.queue.limit||(this.effects.length<_30.options.queue.limit)){
this.effects.push(_30);
}
if(!this.interval){
this.interval=setInterval(this.loop.bind(this),40);
}
},remove:function(_35){
this.effects=this.effects.reject(function(e){
return e==_35;
});
if(this.effects.length==0){
clearInterval(this.interval);
this.interval=null;
}
},loop:function(){
var _37=new Date().getTime();
this.effects.invoke("loop",_37);
}});
Effect.Queues={instances:$H(),get:function(_38){
if(typeof _38!="string"){
return _38;
}
if(!this.instances[_38]){
this.instances[_38]=new Effect.ScopedQueue();
}
return this.instances[_38];
}};
Effect.Queue=Effect.Queues.get("global");
Effect.DefaultOptions={transition:Effect.Transitions.sinoidal,duration:1,fps:25,sync:false,from:0,to:1,delay:0,queue:"parallel"};
Effect.Base=function(){
};
Effect.Base.prototype={position:null,start:function(_39){
this.options=Object.extend(Object.extend({},Effect.DefaultOptions),_39||{});
this.currentFrame=0;
this.state="idle";
this.startOn=this.options.delay*1000;
this.finishOn=this.startOn+(this.options.duration*1000);
this.event("beforeStart");
if(!this.options.sync){
Effect.Queues.get(typeof this.options.queue=="string"?"global":this.options.queue.scope).add(this);
}
},loop:function(_3a){
if(_3a>=this.startOn){
if(_3a>=this.finishOn){
this.render(1);
this.cancel();
this.event("beforeFinish");
if(this.finish){
this.finish();
}
this.event("afterFinish");
return;
}
var pos=(_3a-this.startOn)/(this.finishOn-this.startOn);
var _3c=Math.round(pos*this.options.fps*this.options.duration);
if(_3c>this.currentFrame){
this.render(pos);
this.currentFrame=_3c;
}
}
},render:function(pos){
if(this.state=="idle"){
this.state="running";
this.event("beforeSetup");
if(this.setup){
this.setup();
}
this.event("afterSetup");
}
if(this.state=="running"){
if(this.options.transition){
pos=this.options.transition(pos);
}
pos*=(this.options.to-this.options.from);
pos+=this.options.from;
this.position=pos;
this.event("beforeUpdate");
if(this.update){
this.update(pos);
}
this.event("afterUpdate");
}
},cancel:function(){
if(!this.options.sync){
Effect.Queues.get(typeof this.options.queue=="string"?"global":this.options.queue.scope).remove(this);
}
this.state="finished";
},event:function(_3e){
if(this.options[_3e+"Internal"]){
this.options[_3e+"Internal"](this);
}
if(this.options[_3e]){
this.options[_3e](this);
}
},inspect:function(){
return "#<Effect:"+$H(this).inspect()+",options:"+$H(this.options).inspect()+">";
}};
Effect.Parallel=Class.create();
Object.extend(Object.extend(Effect.Parallel.prototype,Effect.Base.prototype),{initialize:function(_3f){
this.effects=_3f||[];
this.start(arguments[1]);
},update:function(_40){
this.effects.invoke("render",_40);
},finish:function(_41){
this.effects.each(function(_42){
_42.render(1);
_42.cancel();
_42.event("beforeFinish");
if(_42.finish){
_42.finish(_41);
}
_42.event("afterFinish");
});
}});
Effect.Opacity=Class.create();
Object.extend(Object.extend(Effect.Opacity.prototype,Effect.Base.prototype),{initialize:function(_43){
this.element=$(_43);
if(/MSIE/.test(navigator.userAgent)&&(!this.element.currentStyle.hasLayout)){
this.element.setStyle({zoom:1});
}
var _44=Object.extend({from:this.element.getOpacity()||0,to:1},arguments[1]||{});
this.start(_44);
},update:function(_45){
this.element.setOpacity(_45);
}});
Effect.Move=Class.create();
Object.extend(Object.extend(Effect.Move.prototype,Effect.Base.prototype),{initialize:function(_46){
this.element=$(_46);
var _47=Object.extend({x:0,y:0,mode:"relative"},arguments[1]||{});
this.start(_47);
},setup:function(){
this.element.makePositioned();
this.originalLeft=parseFloat(this.element.getStyle("left")||"0");
this.originalTop=parseFloat(this.element.getStyle("top")||"0");
if(this.options.mode=="absolute"){
this.options.x=this.options.x-this.originalLeft;
this.options.y=this.options.y-this.originalTop;
}
},update:function(_48){
this.element.setStyle({left:Math.round(this.options.x*_48+this.originalLeft)+"px",top:Math.round(this.options.y*_48+this.originalTop)+"px"});
}});
Effect.MoveBy=function(_49,_4a,_4b){
return new Effect.Move(_49,Object.extend({x:_4b,y:_4a},arguments[3]||{}));
};
Effect.Scale=Class.create();
Object.extend(Object.extend(Effect.Scale.prototype,Effect.Base.prototype),{initialize:function(_4c,_4d){
this.element=$(_4c);
var _4e=Object.extend({scaleX:true,scaleY:true,scaleContent:true,scaleFromCenter:false,scaleMode:"box",scaleFrom:100,scaleTo:_4d},arguments[2]||{});
this.start(_4e);
},setup:function(){
this.restoreAfterFinish=this.options.restoreAfterFinish||false;
this.elementPositioning=this.element.getStyle("position");
this.originalStyle={};
["top","left","width","height","fontSize"].each(function(k){
this.originalStyle[k]=this.element.style[k];
}.bind(this));
this.originalTop=this.element.offsetTop;
this.originalLeft=this.element.offsetLeft;
var _50=this.element.getStyle("font-size")||"100%";
["em","px","%","pt"].each(function(_51){
if(_50.indexOf(_51)>0){
this.fontSize=parseFloat(_50);
this.fontSizeType=_51;
}
}.bind(this));
this.factor=(this.options.scaleTo-this.options.scaleFrom)/100;
this.dims=null;
if(this.options.scaleMode=="box"){
this.dims=[this.element.offsetHeight,this.element.offsetWidth];
}
if(/^content/.test(this.options.scaleMode)){
this.dims=[this.element.scrollHeight,this.element.scrollWidth];
}
if(!this.dims){
this.dims=[this.options.scaleMode.originalHeight,this.options.scaleMode.originalWidth];
}
},update:function(_52){
var _53=(this.options.scaleFrom/100)+(this.factor*_52);
if(this.options.scaleContent&&this.fontSize){
this.element.setStyle({fontSize:this.fontSize*_53+this.fontSizeType});
}
this.setDimensions(this.dims[0]*_53,this.dims[1]*_53);
},finish:function(_54){
if(this.restoreAfterFinish){
this.element.setStyle(this.originalStyle);
}
},setDimensions:function(_55,_56){
var d={};
if(this.options.scaleX){
d.width=Math.round(_56)+"px";
}
if(this.options.scaleY){
d.height=Math.round(_55)+"px";
}
if(this.options.scaleFromCenter){
var _58=(_55-this.dims[0])/2;
var _59=(_56-this.dims[1])/2;
if(this.elementPositioning=="absolute"){
if(this.options.scaleY){
d.top=this.originalTop-_58+"px";
}
if(this.options.scaleX){
d.left=this.originalLeft-_59+"px";
}
}else{
if(this.options.scaleY){
d.top=-_58+"px";
}
if(this.options.scaleX){
d.left=-_59+"px";
}
}
}
this.element.setStyle(d);
}});
Effect.Highlight=Class.create();
Object.extend(Object.extend(Effect.Highlight.prototype,Effect.Base.prototype),{initialize:function(_5a){
this.element=$(_5a);
var _5b=Object.extend({startcolor:"#ffff99"},arguments[1]||{});
this.start(_5b);
},setup:function(){
if(this.element.getStyle("display")=="none"){
this.cancel();
return;
}
this.oldStyle={backgroundImage:this.element.getStyle("background-image")};
this.element.setStyle({backgroundImage:"none"});
if(!this.options.endcolor){
this.options.endcolor=this.element.getStyle("background-color").parseColor("#ffffff");
}
if(!this.options.restorecolor){
this.options.restorecolor=this.element.getStyle("background-color");
}
this._base=$R(0,2).map(function(i){
return parseInt(this.options.startcolor.slice(i*2+1,i*2+3),16);
}.bind(this));
this._delta=$R(0,2).map(function(i){
return parseInt(this.options.endcolor.slice(i*2+1,i*2+3),16)-this._base[i];
}.bind(this));
},update:function(_5e){
this.element.setStyle({backgroundColor:$R(0,2).inject("#",function(m,v,i){
return m+(Math.round(this._base[i]+(this._delta[i]*_5e)).toColorPart());
}.bind(this))});
},finish:function(){
this.element.setStyle(Object.extend(this.oldStyle,{backgroundColor:this.options.restorecolor}));
}});
Effect.ScrollTo=Class.create();
Object.extend(Object.extend(Effect.ScrollTo.prototype,Effect.Base.prototype),{initialize:function(_62){
this.element=$(_62);
this.start(arguments[1]||{});
},setup:function(){
Position.prepare();
var _63=Position.cumulativeOffset(this.element);
if(this.options.offset){
_63[1]+=this.options.offset;
}
var max=window.innerHeight?window.height-window.innerHeight:document.body.scrollHeight-(document.documentElement.clientHeight?document.documentElement.clientHeight:document.body.clientHeight);
this.scrollStart=Position.deltaY;
this.delta=(_63[1]>max?max:_63[1])-this.scrollStart;
},update:function(_65){
Position.prepare();
window.scrollTo(Position.deltaX,this.scrollStart+(_65*this.delta));
}});
Effect.Fade=function(_66){
_66=$(_66);
var _67=_66.getInlineOpacity();
var _68=Object.extend({from:_66.getOpacity()||1,to:0,afterFinishInternal:function(_69){
if(_69.options.to!=0){
return;
}
_69.element.hide();
_69.element.setStyle({opacity:_67});
}},arguments[1]||{});
return new Effect.Opacity(_66,_68);
};
Effect.Appear=function(_6a){
_6a=$(_6a);
var _6b=Object.extend({from:(_6a.getStyle("display")=="none"?0:_6a.getOpacity()||0),to:1,afterFinishInternal:function(_6c){
_6c.element.forceRerendering();
},beforeSetup:function(_6d){
_6d.element.setOpacity(_6d.options.from);
_6d.element.show();
}},arguments[1]||{});
return new Effect.Opacity(_6a,_6b);
};
Effect.Puff=function(_6e){
_6e=$(_6e);
var _6f={opacity:_6e.getInlineOpacity(),position:_6e.getStyle("position")};
return new Effect.Parallel([new Effect.Scale(_6e,200,{sync:true,scaleFromCenter:true,scaleContent:true,restoreAfterFinish:true}),new Effect.Opacity(_6e,{sync:true,to:0})],Object.extend({duration:1,beforeSetupInternal:function(_70){
_70.effects[0].element.setStyle({position:"absolute"});
},afterFinishInternal:function(_71){
_71.effects[0].element.hide();
_71.effects[0].element.setStyle(_6f);
}},arguments[1]||{}));
};
Effect.BlindUp=function(_72){
_72=$(_72);
_72.makeClipping();
return new Effect.Scale(_72,0,Object.extend({scaleContent:false,scaleX:false,restoreAfterFinish:true,afterFinishInternal:function(_73){
_73.element.hide();
_73.element.undoClipping();
}},arguments[1]||{}));
};
Effect.BlindDown=function(_74){
_74=$(_74);
var _75=_74.getDimensions();
return new Effect.Scale(_74,100,Object.extend({scaleContent:false,scaleX:false,scaleFrom:0,scaleMode:{originalHeight:_75.height,originalWidth:_75.width},restoreAfterFinish:true,afterSetup:function(_76){
_76.element.makeClipping();
_76.element.setStyle({height:"0px"});
_76.element.show();
},afterFinishInternal:function(_77){
_77.element.undoClipping();
}},arguments[1]||{}));
};
Effect.SwitchOff=function(_78){
_78=$(_78);
var _79=_78.getInlineOpacity();
return new Effect.Appear(_78,Object.extend({duration:0.4,from:0,transition:Effect.Transitions.flicker,afterFinishInternal:function(_7a){
new Effect.Scale(_7a.element,1,{duration:0.3,scaleFromCenter:true,scaleX:false,scaleContent:false,restoreAfterFinish:true,beforeSetup:function(_7b){
_7b.element.makePositioned();
_7b.element.makeClipping();
},afterFinishInternal:function(_7c){
_7c.element.hide();
_7c.element.undoClipping();
_7c.element.undoPositioned();
_7c.element.setStyle({opacity:_79});
}});
}},arguments[1]||{}));
};
Effect.DropOut=function(_7d){
_7d=$(_7d);
var _7e={top:_7d.getStyle("top"),left:_7d.getStyle("left"),opacity:_7d.getInlineOpacity()};
return new Effect.Parallel([new Effect.Move(_7d,{x:0,y:100,sync:true}),new Effect.Opacity(_7d,{sync:true,to:0})],Object.extend({duration:0.5,beforeSetup:function(_7f){
_7f.effects[0].element.makePositioned();
},afterFinishInternal:function(_80){
_80.effects[0].element.hide();
_80.effects[0].element.undoPositioned();
_80.effects[0].element.setStyle(_7e);
}},arguments[1]||{}));
};
Effect.Shake=function(_81){
_81=$(_81);
var _82={top:_81.getStyle("top"),left:_81.getStyle("left")};
return new Effect.Move(_81,{x:20,y:0,duration:0.05,afterFinishInternal:function(_83){
new Effect.Move(_83.element,{x:-40,y:0,duration:0.1,afterFinishInternal:function(_84){
new Effect.Move(_84.element,{x:40,y:0,duration:0.1,afterFinishInternal:function(_85){
new Effect.Move(_85.element,{x:-40,y:0,duration:0.1,afterFinishInternal:function(_86){
new Effect.Move(_86.element,{x:40,y:0,duration:0.1,afterFinishInternal:function(_87){
new Effect.Move(_87.element,{x:-20,y:0,duration:0.05,afterFinishInternal:function(_88){
_88.element.undoPositioned();
_88.element.setStyle(_82);
}});
}});
}});
}});
}});
}});
};
Effect.SlideDown=function(_89){
_89=$(_89);
_89.cleanWhitespace();
var _8a=$(_89.firstChild).getStyle("bottom");
var _8b=_89.getDimensions();
return new Effect.Scale(_89,100,Object.extend({scaleContent:false,scaleX:false,scaleFrom:window.opera?0:1,scaleMode:{originalHeight:_8b.height,originalWidth:_8b.width},restoreAfterFinish:true,afterSetup:function(_8c){
_8c.element.makePositioned();
_8c.element.firstChild.makePositioned();
if(window.opera){
_8c.element.setStyle({top:""});
}
_8c.element.makeClipping();
_8c.element.setStyle({height:"0px"});
_8c.element.show();
},afterUpdateInternal:function(_8d){
_8d.element.firstChild.setStyle({bottom:(_8d.dims[0]-_8d.element.clientHeight)+"px"});
},afterFinishInternal:function(_8e){
_8e.element.undoClipping();
if(/MSIE/.test(navigator.userAgent)){
_8e.element.undoPositioned();
_8e.element.firstChild.undoPositioned();
}else{
_8e.element.firstChild.undoPositioned();
_8e.element.undoPositioned();
}
_8e.element.firstChild.setStyle({bottom:_8a});
}},arguments[1]||{}));
};
Effect.SlideUp=function(_8f){
_8f=$(_8f);
_8f.cleanWhitespace();
var _90=$(_8f.firstChild).getStyle("bottom");
return new Effect.Scale(_8f,window.opera?0:1,Object.extend({scaleContent:false,scaleX:false,scaleMode:"box",scaleFrom:100,restoreAfterFinish:true,beforeStartInternal:function(_91){
_91.element.makePositioned();
_91.element.firstChild.makePositioned();
if(window.opera){
_91.element.setStyle({top:""});
}
_91.element.makeClipping();
_91.element.show();
},afterUpdateInternal:function(_92){
_92.element.firstChild.setStyle({bottom:(_92.dims[0]-_92.element.clientHeight)+"px"});
},afterFinishInternal:function(_93){
_93.element.hide();
_93.element.undoClipping();
_93.element.firstChild.undoPositioned();
_93.element.undoPositioned();
_93.element.setStyle({bottom:_90});
}},arguments[1]||{}));
};
Effect.Squish=function(_94){
return new Effect.Scale(_94,window.opera?1:0,{restoreAfterFinish:true,beforeSetup:function(_95){
_95.element.makeClipping(_95.element);
},afterFinishInternal:function(_96){
_96.element.hide(_96.element);
_96.element.undoClipping(_96.element);
}});
};
Effect.Grow=function(_97){
_97=$(_97);
var _98=Object.extend({direction:"center",moveTransition:Effect.Transitions.sinoidal,scaleTransition:Effect.Transitions.sinoidal,opacityTransition:Effect.Transitions.full},arguments[1]||{});
var _99={top:_97.style.top,left:_97.style.left,height:_97.style.height,width:_97.style.width,opacity:_97.getInlineOpacity()};
var _9a=_97.getDimensions();
var _9b,initialMoveY;
var _9c,moveY;
switch(_98.direction){
case "top-left":
_9b=initialMoveY=_9c=moveY=0;
break;
case "top-right":
_9b=_9a.width;
initialMoveY=moveY=0;
_9c=-_9a.width;
break;
case "bottom-left":
_9b=_9c=0;
initialMoveY=_9a.height;
moveY=-_9a.height;
break;
case "bottom-right":
_9b=_9a.width;
initialMoveY=_9a.height;
_9c=-_9a.width;
moveY=-_9a.height;
break;
case "center":
_9b=_9a.width/2;
initialMoveY=_9a.height/2;
_9c=-_9a.width/2;
moveY=-_9a.height/2;
break;
}
return new Effect.Move(_97,{x:_9b,y:initialMoveY,duration:0.01,beforeSetup:function(_9d){
_9d.element.hide();
_9d.element.makeClipping();
_9d.element.makePositioned();
},afterFinishInternal:function(_9e){
new Effect.Parallel([new Effect.Opacity(_9e.element,{sync:true,to:1,from:0,transition:_98.opacityTransition}),new Effect.Move(_9e.element,{x:_9c,y:moveY,sync:true,transition:_98.moveTransition}),new Effect.Scale(_9e.element,100,{scaleMode:{originalHeight:_9a.height,originalWidth:_9a.width},sync:true,scaleFrom:window.opera?1:0,transition:_98.scaleTransition,restoreAfterFinish:true})],Object.extend({beforeSetup:function(_9f){
_9f.effects[0].element.setStyle({height:"0px"});
_9f.effects[0].element.show();
},afterFinishInternal:function(_a0){
_a0.effects[0].element.undoClipping();
_a0.effects[0].element.undoPositioned();
_a0.effects[0].element.setStyle(_99);
}},_98));
}});
};
Effect.Shrink=function(_a1){
_a1=$(_a1);
var _a2=Object.extend({direction:"center",moveTransition:Effect.Transitions.sinoidal,scaleTransition:Effect.Transitions.sinoidal,opacityTransition:Effect.Transitions.none},arguments[1]||{});
var _a3={top:_a1.style.top,left:_a1.style.left,height:_a1.style.height,width:_a1.style.width,opacity:_a1.getInlineOpacity()};
var _a4=_a1.getDimensions();
var _a5,moveY;
switch(_a2.direction){
case "top-left":
_a5=moveY=0;
break;
case "top-right":
_a5=_a4.width;
moveY=0;
break;
case "bottom-left":
_a5=0;
moveY=_a4.height;
break;
case "bottom-right":
_a5=_a4.width;
moveY=_a4.height;
break;
case "center":
_a5=_a4.width/2;
moveY=_a4.height/2;
break;
}
return new Effect.Parallel([new Effect.Opacity(_a1,{sync:true,to:0,from:1,transition:_a2.opacityTransition}),new Effect.Scale(_a1,window.opera?1:0,{sync:true,transition:_a2.scaleTransition,restoreAfterFinish:true}),new Effect.Move(_a1,{x:_a5,y:moveY,sync:true,transition:_a2.moveTransition})],Object.extend({beforeStartInternal:function(_a6){
_a6.effects[0].element.makePositioned();
_a6.effects[0].element.makeClipping();
},afterFinishInternal:function(_a7){
_a7.effects[0].element.hide();
_a7.effects[0].element.undoClipping();
_a7.effects[0].element.undoPositioned();
_a7.effects[0].element.setStyle(_a3);
}},_a2));
};
Effect.Pulsate=function(_a8){
_a8=$(_a8);
var _a9=arguments[1]||{};
var _aa=_a8.getInlineOpacity();
var _ab=_a9.transition||Effect.Transitions.sinoidal;
var _ac=function(pos){
return _ab(1-Effect.Transitions.pulse(pos));
};
_ac.bind(_ab);
return new Effect.Opacity(_a8,Object.extend(Object.extend({duration:3,from:0,afterFinishInternal:function(_ae){
_ae.element.setStyle({opacity:_aa});
}},_a9),{transition:_ac}));
};
Effect.Fold=function(_af){
_af=$(_af);
var _b0={top:_af.style.top,left:_af.style.left,width:_af.style.width,height:_af.style.height};
Element.makeClipping(_af);
return new Effect.Scale(_af,5,Object.extend({scaleContent:false,scaleX:false,afterFinishInternal:function(_b1){
new Effect.Scale(_af,1,{scaleContent:false,scaleY:false,afterFinishInternal:function(_b2){
_b2.element.hide();
_b2.element.undoClipping();
_b2.element.setStyle(_b0);
}});
}},arguments[1]||{}));
};
["setOpacity","getOpacity","getInlineOpacity","forceRerendering","setContentZoom","collectTextNodes","collectTextNodesIgnoreClass","childrenWithClassName"].each(function(f){
Element.Methods[f]=Element[f];
});
Element.Methods.visualEffect=function(_b4,_b5,_b6){
s=_b5.gsub(/_/,"-").camelize();
effect_class=s.charAt(0).toUpperCase()+s.substring(1);
new Effect[effect_class](_b4,_b6);
return $(_b4);
};
Element.addMethods();


function MM_findObj(n,d){
var p,i,x;
if(!d){
d=document;
}
if((p=n.indexOf("?"))>0&&parent.frames.length){
d=parent.frames[n.substring(p+1)].document;
n=n.substring(0,p);
}
if(!(x=d[n])&&d.all){
x=d.all[n];
}
for(i=0;!x&&i<d.forms.length;i++){
x=d.forms[i][n];
}
for(i=0;!x&&d.layers&&i<d.layers.length;i++){
x=MM_findObj(n,d.layers[i].document);
}
if(!x&&d.getElementById){
x=d.getElementById(n);
}
return x;
}
function MM_swapImage(){
var i,j=0,x,a=MM_swapImage.arguments;
document.MM_sr=new Array;
for(i=0;i<(a.length-2);i+=3){
if((x=MM_findObj(a[i]))!=null){
document.MM_sr[j++]=x;
if(!x.oSrc){
x.oSrc=x.src;
}
x.src=a[i+2];
}
}
}
function MM_swapImgRestore(){
var i,x,a=document.MM_sr;
for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++){
x.src=x.oSrc;
}
}
function MM_preloadImages(){
var d=document;
if(d.images){
if(!d.MM_p){
d.MM_p=new Array();
}
var i,j=d.MM_p.length,a=MM_preloadImages.arguments;
for(i=0;i<a.length;i++){
if(a[i].indexOf("#")!=0){
d.MM_p[j]=new Image;
d.MM_p[j++].src=a[i];
}
}
}
}
function MM_validateForm(){
var i,p,q,nm,test,num,min,max,errors="",args=MM_validateForm.arguments;
for(i=0;i<(args.length-2);i+=3){
test=args[i+2];
val=MM_findObj(args[i]);
if(val){
nm=val.name;
if((val=val.value)!=""){
if(test.indexOf("isEmail")!=-1){
p=val.indexOf("@");
if(p<1||p==(val.length-1)){
errors+="- "+nm+" must contain an e-mail address.\n";
}
}else{
if(test!="R"){
num=parseFloat(val);
if(val!=""+num){
errors+="- "+nm+" must contain a number.\n";
}
if(test.indexOf("inRange")!=-1){
p=test.indexOf(":");
min=test.substring(8,p);
max=test.substring(p+1);
if(num<min||max<num){
errors+="- "+nm+" must contain a number between "+min+" and "+max+".\n";
}
}
}
}
}else{
if(test.charAt(0)=="R"){
errors+="- "+nm+" is required.\n";
}
}
}
}
if(errors){
alert("The following error(s) occurred:\n"+errors);
}
document.MM_returnValue=(errors=="");
}


var tjpZoom={isopera:(navigator.userAgent.toLowerCase().indexOf("opera")!=-1),zoomid:null,zoomw:160,zoomh:120,defzoomamount:4,zoomamountstep:1.2,zoomsizemin:1000,zoomsizemax:100000,zoomsizestep:1.2,zoomamountmin:1,zoomamountmax:12,zoomborder:(navigator.userAgent.indexOf("Firefox/1.0")!=-1)?0:2,zoomborderoricolor:"#315FB7",zoomborderhotcolor:"#ffeb00",tooltipstyle:"background-color: #ffffe1; border: 1px solid #000000; padding: 2px; font-size: 70%; font-family: verdana,arial;",zoomamount:this.defzoomamount,hotspots:{},objw:0,objh:0,zoomid:"",zoomratio:this.zoomw/this.zoomh,ieox:0,ieoy:0,ffox:0,ffoy:0,flagHidone:0,overhotspot:0,lastover:"",lastxx:0,lastyy:0,zoombordercolor:"",oController:null,oTrigger:null,oContainer:null,oClipborder:null,oPadder:null,oClip:null,oTooltip:null,oPic:null,click:function(){
if(this.overhotspot!=0){
var _1=this.hotspots[this.overhotspot][4];
if(_1.indexOf("http://")==0){
document.location=_1;
}else{
eval(_1);
}
}
return false;
},hidone:function(){
this.flagHidone=1;
this.oContainer.style.cursor="default";
this.oTooltip.style.display="none";
},set:function(e){
var e=e?e:window.event?window.event:null;
if(!e){
return;
}
if(tjpZoom.zoomid==""||parseInt(tjpZoom.oContainer.style.width)==0){
return true;
}
if(e.keyCode==37||e.keyCode==100){
tjpZoom.zoomw/=tjpZoom.zoomsizestep;
tjpZoom.zoomh/=tjpZoom.zoomsizestep;
if(tjpZoom.zoomw*tjpZoom.zoomh<tjpZoom.zoomsizemin){
tjpZoom.zoomh=Math.sqrt(tjpZoom.zoomsizemin/tjpZoom.zoomratio);
tjpZoom.zoomw=tjpZoom.zoomh*tjpZoom.zoomratio;
}
tjpZoom.init();
tjpZoom.move();
return;
}
if(e.keyCode==39||e.keyCode==102){
tjpZoom.zoomw*=tjpZoom.zoomsizestep;
tjpZoom.zoomh*=tjpZoom.zoomsizestep;
if(tjpZoom.zoomw*tjpZoom.zoomh>tjpZoom.zoomsizemax){
tjpZoom.zoomh=Math.sqrt(tjpZoom.zoomsizemax/tjpZoom.zoomratio);
tjpZoom.zoomw=tjpZoom.zoomh*tjpZoom.zoomratio;
}
if(tjpZoom.zoomw>tjpZoom.objw){
tjpZoom.zoomw=tjpZoom.objw;
tjpZoom.zoomh=tjpZoom.objw/tjpZoom.zoomratio;
}else{
if(tjpZoom.zoomh>tjpZoom.objh){
tjpZoom.zoomh=tjpZoom.objh;
tjpZoom.zoomw=tjpZoom.objh*tjpZoom.zoomratio;
}
}
tjpZoom.init();
tjpZoom.move();
return;
}
if(e.keyCode==40||e.keyCode==98){
tjpZoom.zoomamount/=tjpZoom.zoomamountstep;
if(tjpZoom.zoomamount<tjpZoom.zoomamountmin){
tjpZoom.zoomamount=tjpZoom.zoomamountmin;
}
tjpZoom.init();
tjpZoom.move();
return;
}
if(e.keyCode==38||e.keyCode==104){
tjpZoom.zoomamount*=tjpZoom.zoomamountstep;
if(tjpZoom.zoomamount>tjpZoom.zoomamountmax){
tjpZoom.zoomamount=tjpZoom.zoomamountmax;
}
tjpZoom.init();
tjpZoom.move();
return;
}
return;
},init:function(){
this.oClip.style.width=this.objw*this.zoomamount+"px";
this.oClip.style.height=this.objh*this.zoomamount+"px";
this.oTrigger.focus();
},move:function(e){
var xx,yy;
if(typeof (e)=="object"){
var e=e?e:window.event?window.event:null;
if(!e){
return;
}
if(e.pageX){
xx=e.pageX-this.ffox;
yy=e.pageY-this.ffoy;
}else{
if(typeof ($(this.zoomid)+1)=="number"){
return true;
}
xx=e.clientX-this.ieox;
yy=e.clientY-this.ieoy;
}
}else{
xx=this.lastxx;
yy=this.lastyy;
}
this.lastxx=xx;
this.lastyy=yy;
if(this.flagHidone==1){
if(this.overhotspot==0){
for(key in this.hotspots){
if(xx>=this.hotspots[key][0]&&yy>=this.hotspots[key][1]&&xx<=this.hotspots[key][2]&&yy<=this.hotspots[key][3]){
this.overhotspot=key;
this.oContainer.style.cursor="pointer";
if(!this.isopera){
this.oTooltip.style.display="block";
this.oTooltip.innerHTML=key;
}
this.zoombordercolor=this.zoomborderhotcolor;
}
}
}else{
var os=0;
for(key in this.hotspots){
if(xx>=this.hotspots[key][0]&&yy>=this.hotspots[key][1]&&xx<=this.hotspots[key][2]&&yy<=this.hotspots[key][3]){
os=1;
}
}
if(os==0){
this.overhotspot=0;
this.zoombordercolor=this.zoomborderoricolor;
this.oContainer.style.cursor="default";
Element.hide(this.oTooltip);
}
}
}
if(this.zoomborder>0){
zbl=this.zoomborder;
zbt=this.zoomborder;
zbl=(Math.abs(this.zoomw/2-xx)-Math.abs(this.zoomw/2-xx+this.zoomborder)+this.zoomborder)/2;
zbt=(Math.abs(this.zoomh/2-yy)-Math.abs(this.zoomh/2-yy+this.zoomborder)+this.zoomborder)/2;
zbr=(Math.abs((xx+this.zoomw/2+zbl)-this.objw-this.zoomborder)-Math.abs((xx+this.zoomw/2+zbl)-this.objw)+this.zoomborder)/2;
zbb=(Math.abs((yy+this.zoomh/2+zbt)-this.objh-this.zoomborder)-Math.abs((yy+this.zoomh/2+zbt)-this.objh)+this.zoomborder)/2;
this.oClipborder.style.borderLeft=zbl+"px solid "+this.zoombordercolor;
this.oClipborder.style.borderTop=zbt+"px solid "+this.zoombordercolor;
this.oClipborder.style.borderBottom=zbb+"px solid "+this.zoombordercolor;
this.oClipborder.style.borderRight=zbr+"px solid "+this.zoombordercolor;
}else{
zbt=zbr=zbb=zbl=0;
}
var _6=((yy-this.zoomh/2>0)?(this.zoomh/2-yy*this.zoomamount):(yy*(1-this.zoomamount)))+zbt;
var _7=((xx-this.zoomw/2>0)?(this.zoomw/2-xx*this.zoomamount):(xx*(1-this.zoomamount)))+zbl;
this.oClip.style.margin=_6+"px 0px 0px "+_7+"px";
var _8=((yy-this.zoomh/2>0)?(yy-this.zoomh/2):(0))-zbt;
var _9=((xx-this.zoomw/2>0)?(xx-this.zoomw/2):(0))-zbl;
this.oContainer.style.margin=_8+"px 0px 0px "+_9+"px";
this.oPadder.style.height=((Math.abs(yy)-Math.abs(yy-this.zoomh/2)+this.zoomh/2)/2+this.zoomh/2)+"px";
this.oPadder.style.width=((Math.abs(xx)-Math.abs(xx-this.zoomw/2)+this.zoomw/2)/2+this.zoomw/2)+"px";
var _a=((xx+this.zoomw/2<this.objw)?((this.zoomw/2<xx)?(this.zoomw):(this.zoomw/2+xx)):(this.zoomw/2+this.objw-xx));
if(_a<0){
_a=0;
}
var _b=((yy+this.zoomh/2<this.objh)?((zbt+this.zoomh/2<yy)?(this.zoomh):(this.zoomh/2+yy)):(this.zoomh/2+this.objh-yy));
if(_b<0){
_b=0;
}
this.oContainer.style.width=(_a+zbl+zbr)+"px";
this.oContainer.style.height=(_b+zbt+zbb)+"px";
return false;
},off:function(){
if(this.zoomid!=""){
this.oContainer.style.width="0px";
this.oContainer.style.height="0px";
}
this.zoomid="";
this.hotspots={};
},countoffset:function(){
this.ieox=0;
this.ieoy=0;
for(zmi=0;zmi<50;zmi++){
zme="$(tjpZoom.zoomid)";
for(zmj=1;zmj<=zmi;zmj++){
zme+=".offsetParent";
}
if(eval(zme)+1==1){
zmi=100;
}else{
this.ieox+=eval(zme+".offsetLeft");
this.ieoy+=eval(zme+".offsetTop");
}
}
this.ffox=this.ieox;
this.ffoy=this.ieoy;
this.ieox-=document.body.scrollLeft;
this.ieoy-=document.body.scrollTop;
},on:function(e,ow,oh,_f,_10){
var e=e?e:window.event?window.event:null;
if(!e){
return;
}
var _11=_f+_10+ow+oh;
_11="zoom_"+_11.replace(/[^a-z0-9]/ig,"");
if(this.zoomid==_11){
return;
}
if(this.lastover==_11){
this.zoomid=_11;
this.countoffset();
this.move();
return;
}
if(typeof (_10)=="undefined"){
_10=_f;
}
this.defzoomamount=this.isopera?2:this.defzoomamount;
this.zoomamount=this.defzoomamount;
this.zoomamountmax=this.isopera?3:this.zoomamountmax;
this.tooltipstyle=this.isopera?"display: none":this.tooltipstyle;
this.zoombordercolor=this.zoomborderoricolor;
this.objw=ow;
this.objh=oh;
this.zoomid=_11;
if(e.pageX){
e.target.parentNode.id=_11;
this.countoffset();
this.lastxx=e.pageX-this.ffox;
this.lastyy=e.pageY-this.ffoy;
}else{
e.srcElement.parentNode.id=_11;
this.countoffset();
this.lastxx=e.clientX-this.ieox;
this.lastyy=e.clientY-this.ieoy;
}
this.lastover=_11;
this.oController=$(_11);
this.oController.style.width=ow+"px";
this.oController.style.height=(oh+((this.isopera)?5:0))+"px";
this.oController.style.overflow="hidden";
this.oController.style.textAlign="center";
this.oController.innerHTML="<textarea id=\""+_11+"_trigger\" style=\"font-size: 1px; position: absolute; width: 100px; height: "+((this.isopera)?"4px":"0; filter: alpha(opacity=0);")+"; border: 0; margin: 0; padding: 0; overflow: hidden; z-index: 0;\"/></textarea>"+"<div style = \"text-align: left; position: absolute; overflow: hidden; width: 0; height: 0; border: 0;\" id = \""+_11+"_container\" onmousemove=\"tjpZoom.move(event);\" onmouseout=\"tjpZoom.off()\">"+"  <div id=\""+_11+"_clipborder\" style=\"position: absolute; overflow: hidden; border: "+this.zoomborder+"px solid "+this.zoombordercolor+";\">"+"    <div style=\"width: 0; height: 0; overflow: hidden;\" id=\""+_11+"_padder\"></div>"+"  </div>"+"  <img src=\""+_10+"\" alt=\"\" id=\""+_11+"_clip\" style=\"padding: 0; margin: 0; border: 0;\" onload=\"tjpZoom.hidone();\" />"+"</div>"+"<div style=\"text-align: left; position: absolute; z-index: 2; float: left; "+this.tooltipstyle+"\" id=\""+_11+"_tooltip\">Loading...</div>"+"<img src=\""+_f+"\" id=\""+_11+"_pic\" alt=\"\" style=\"padding:0; margin: 0; border: 0; z-index: 10; width: "+ow+"px; height: "+oh+"px\"/>";
this.oTrigger=$(_11+"_trigger");
this.oContainer=$(_11+"_container");
this.oClipborder=$(_11+"_clipborder");
this.oPadder=$(_11+"_padder");
this.oClip=$(_11+"_clip");
this.oTooltip=$(_11+"_tooltip");
this.oPic=$(_11+"_pic");
if(this.zoomw>this.objw){
this.zoomw=this.objw;
this.zoomh=this.objw/this.zoomratio;
}else{
if(this.zoomh>this.objh){
this.zoomh=this.objh;
this.zoomw=this.objh*this.zoomratio;
}
}
this.init();
this.move("");
this.oContainer.style.cursor="wait";
this.flagHidone=0;
if(this.isopera){
document.onkeypress=this.set;
}else{
if(document.all){
document.onkeydown=this.set;
}else{
window.captureEvents(Event.KEYDOWN);
window.onkeydown=this.set;
}
}
return false;
}};