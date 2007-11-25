/**
 * tjpzoom 2.0 2006.0221.
 * details/usage: http://valid.tjp.hu/zoom2/
 *
 * @version $Id:tjpzoom.js 106 2007-02-10 22:30:30Z kevinuru $
 */
var tjpZoom = {
    isopera: (navigator.userAgent.toLowerCase().indexOf('opera') != -1),
    /* Configuration; you can change the appearance and behavior of tjpzoom here */
    zoomid: null,
    zoomw : 160,
    zoomh : 120,
    defzoomamount: 4,
    
    zoomamountstep: 1.2,
    zoomsizemin   : 1000,
    zoomsizemax   : 100000,
    zoomsizestep  : 1.2,
    zoomamountmin : 1,
    zoomamountmax : 12,
    zoomborder    : (navigator.userAgent.indexOf('Firefox/1.0') != -1) ? 0 : 2,
    zoomborderoricolor: '#315FB7',
    zoomborderhotcolor: '#ffeb00',
    tooltipstyle      : 'background-color: #ffffe1; border: 1px solid #000000; padding: 2px; font-size: 70%; font-family: verdana,arial;',
    //if you don't want to show the 'Loading...' tooltip, uncomment the following:
    // tooltipstyle: 'display:none',
    
    /* Internal variables, DO NOT CHANGE!!! */
    zoomamount: this.defzoomamount,
    hotspots: {},
    objw: 0,
    objh: 0,
    zoomid: '',
    zoomratio: this.zoomw / this.zoomh,
    ieox: 0,
    ieoy: 0,
    ffox: 0,
    ffoy: 0,
    flagHidone: 0,
    overhotspot: 0,
    lastover: '',
    lastxx: 0,
    lastyy: 0,
    zoombordercolor: '',
    /* variables, holding the newly created DOM Elements */
    oController: null,
    oTrigger: null,
    oContainer: null,
    oClipborder: null,
    oPadder: null,
    oClip: null,
    oTooltip: null,
    oPic: null,
    
    /* tjpzoom functions */
    
    click: function() {
        if(this.overhotspot != 0) {
            var todo = this.hotspots[this.overhotspot][4];
            if (todo.indexOf('http://') == 0)
                document.location = todo;
            else
                eval(todo);
        }
        return false;
    },
    
    hidone: function() {
        this.flagHidone = 1;
        this.oContainer.style.cursor = 'default';
        this.oTooltip.style.display  = 'none';
    },
    
    set: function(e) {
        var e = e ? e : window.event ? window.event : null;
        if(!e) return;
        if (tjpZoom.zoomid == '' || parseInt(tjpZoom.oContainer.style.width) == 0) return true;
        //left:
        if (e.keyCode == 37 || e.keyCode == 100) {
            tjpZoom.zoomw /= tjpZoom.zoomsizestep;
            tjpZoom.zoomh /= tjpZoom.zoomsizestep;
            if (tjpZoom.zoomw * tjpZoom.zoomh < tjpZoom.zoomsizemin) {
                tjpZoom.zoomh = Math.sqrt(tjpZoom.zoomsizemin / tjpZoom.zoomratio);
                tjpZoom.zoomw = tjpZoom.zoomh * tjpZoom.zoomratio;
            }
            tjpZoom.init();
            tjpZoom.move();
            return;
        }
        //right:
        if (e.keyCode == 39 || e.keyCode == 102) {
            tjpZoom.zoomw *= tjpZoom.zoomsizestep;
            tjpZoom.zoomh *= tjpZoom.zoomsizestep;
            if (tjpZoom.zoomw * tjpZoom.zoomh > tjpZoom.zoomsizemax) {
                tjpZoom.zoomh = Math.sqrt(tjpZoom.zoomsizemax / tjpZoom.zoomratio);
                tjpZoom.zoomw = tjpZoom.zoomh * tjpZoom.zoomratio;
            }
            if (tjpZoom.zoomw > tjpZoom.objw) {
                tjpZoom.zoomw = tjpZoom.objw;
                tjpZoom.zoomh = tjpZoom.objw / tjpZoom.zoomratio;
            } else if (tjpZoom.zoomh > tjpZoom.objh) {
                tjpZoom.zoomh = tjpZoom.objh;
                tjpZoom.zoomw = tjpZoom.objh * tjpZoom.zoomratio
            }
            tjpZoom.init();
            tjpZoom.move();
            return;
        }
        //down:
        if(e.keyCode == 40 || e.keyCode == 98) {
            tjpZoom.zoomamount /= tjpZoom.zoomamountstep;
            if (tjpZoom.zoomamount < tjpZoom.zoomamountmin) {
                tjpZoom.zoomamount = tjpZoom.zoomamountmin;
            }
            tjpZoom.init();
            tjpZoom.move();
            return;
        }
        //up:
        if (e.keyCode == 38 || e.keyCode == 104) {
            tjpZoom.zoomamount *= tjpZoom.zoomamountstep;
            if (tjpZoom.zoomamount > tjpZoom.zoomamountmax)
                tjpZoom.zoomamount = tjpZoom.zoomamountmax;
            tjpZoom.init();
            tjpZoom.move();
            return;
        } 
        return;
    },
    
    init: function() {
        this.oClip.style.width  = this.objw * this.zoomamount + 'px';
        this.oClip.style.height = this.objh * this.zoomamount + 'px';
        this.oTrigger.focus();
    },
        
    move: function(e) {
        var xx, yy;
        if(typeof(e) == 'object') {
            var e = e ? e : window.event ? window.event : null;
            if (!e) return;
            if (e.pageX) {
                xx = e.pageX - this.ffox;
                yy = e.pageY - this.ffoy;
            } else {
                if (typeof($(this.zoomid) + 1) == 'number') return true; //mert az ie egy ocska kurva
                xx = e.clientX - this.ieox;
                yy = e.clientY - this.ieoy;
            }
        } else {
            xx = this.lastxx;
            yy = this.lastyy;
        }
        this.lastxx = xx;
        this.lastyy = yy;
        
        if (this.flagHidone == 1) {
            if (this.overhotspot == 0) {
                for (key in this.hotspots) {
                    if (xx >= this.hotspots[key][0] && yy >= this.hotspots[key][1] && xx <= this.hotspots[key][2] && yy <= this.hotspots[key][3]) {
                        this.overhotspot = key;
                        this.oContainer.style.cursor='pointer';
                        if (!this.isopera) {
                            this.oTooltip.style.display = 'block';
                            this.oTooltip.innerHTML = key;
                        }
                        this.zoombordercolor = this.zoomborderhotcolor;
                    }
                }
            } else {
                var os = 0;
                for (key in this.hotspots) {
                    if (xx >= this.hotspots[key][0] && yy >= this.hotspots[key][1] && xx <= this.hotspots[key][2] && yy <= this.hotspots[key][3])
                        os = 1;
                }
                if (os == 0) {
                    this.overhotspot = 0;
                    this.zoombordercolor = this.zoomborderoricolor;
                    this.oContainer.style.cursor = 'default';   
                    Element.hide(this.oTooltip);
                }
            }
        }
        
        if (this.zoomborder > 0) {
            zbl = this.zoomborder;
            zbt = this.zoomborder;
            zbl = (Math.abs(this.zoomw / 2 - xx) - Math.abs(this.zoomw / 2 - xx + this.zoomborder) + this.zoomborder) / 2;
            zbt = (Math.abs(this.zoomh / 2 - yy) - Math.abs(this.zoomh / 2 - yy + this.zoomborder) + this.zoomborder) / 2;
            zbr = (Math.abs((xx + this.zoomw / 2 + zbl) - this.objw - this.zoomborder) - Math.abs((xx + this.zoomw / 2 + zbl) - this.objw) + this.zoomborder) / 2;
            zbb = (Math.abs((yy + this.zoomh / 2 + zbt) - this.objh - this.zoomborder) - Math.abs((yy + this.zoomh / 2 + zbt) - this.objh) + this.zoomborder) / 2;
            
            this.oClipborder.style.borderLeft   = zbl + 'px solid ' + this.zoombordercolor;
            this.oClipborder.style.borderTop    = zbt + 'px solid ' + this.zoombordercolor; 
            this.oClipborder.style.borderBottom = zbb + 'px solid ' + this.zoombordercolor; 
            this.oClipborder.style.borderRight  = zbr + 'px solid ' + this.zoombordercolor; 
        } else {
            zbt = zbr = zbb = zbl = 0;
        }
        
        var clipMarginTop  = ((yy - this.zoomh / 2 > 0) ? (this.zoomh / 2 - yy * this.zoomamount) : (yy * (1 - this.zoomamount))) + zbt;
        var clipMarginLeft = ((xx - this.zoomw / 2 > 0) ? (this.zoomw / 2 - xx * this.zoomamount) : (xx * (1 - this.zoomamount))) + zbl;
        this.oClip.style.margin = clipMarginTop + 'px 0px 0px ' + clipMarginLeft + 'px';
        
        var containerMarginTop    = ((yy - this.zoomh / 2 > 0) ? (yy - this.zoomh / 2) : (0)) - zbt;
        var containerMarginLeft   = ((xx - this.zoomw / 2 > 0) ? (xx - this.zoomw / 2) : (0)) - zbl;
        this.oContainer.style.margin= containerMarginTop + 'px 0px 0px ' + containerMarginLeft + 'px';
    
        this.oPadder.style.height = ((Math.abs(yy) - Math.abs(yy - this.zoomh / 2) + this.zoomh / 2) / 2 + this.zoomh / 2) + 'px';
        this.oPadder.style.width  = ((Math.abs(xx) - Math.abs(xx - this.zoomw / 2) + this.zoomw / 2) / 2 + this.zoomw / 2) + 'px';
        
        var containerWidth = ((xx + this.zoomw / 2 < this.objw) ? ((this.zoomw / 2 < xx) ? (this.zoomw) : (this.zoomw / 2 + xx)) : (this.zoomw / 2 + this.objw - xx));
        if (containerWidth < 0)
            containerWidth = 0;
        var containerHeight = ((yy + this.zoomh / 2 < this.objh) ? ((zbt + this.zoomh / 2 < yy) ? (this.zoomh) : (this.zoomh / 2 + yy)) : (this.zoomh / 2 + this.objh - yy));
        if (containerHeight < 0)
            containerHeight = 0;
        this.oContainer.style.width  = (containerWidth + zbl + zbr) + 'px';
        this.oContainer.style.height = (containerHeight + zbt + zbb) + 'px';
        return false;
    },
    
    off: function() {
        if (this.zoomid != '') {
            this.oContainer.style.width  = '0px';
            this.oContainer.style.height = '0px';
        }
        this.zoomid   = '';
        this.hotspots = {};
    },
    
    countoffset: function() {
        this.ieox=0;
        this.ieoy=0;
        for (zmi = 0; zmi < 50; zmi++) {
            zme = '$(tjpZoom.zoomid)';
            for (zmj = 1; zmj <= zmi; zmj++)
                zme += '.offsetParent';
            if (eval(zme) + 1 == 1)
                zmi = 100;
            else {
                this.ieox += eval(zme + '.offsetLeft');
                this.ieoy += eval(zme + '.offsetTop');
            }
        }
        this.ffox = this.ieox;
        this.ffoy = this.ieoy;
        this.ieox -= document.body.scrollLeft;
        this.ieoy -= document.body.scrollTop;
    },
    
    on: function(e, ow, oh, lowres, highres) {
        var e = e ? e : window.event ? window.event : null;
        if (!e) return;
        var thisid = lowres + highres + ow + oh;
        thisid = 'zoom_' + thisid.replace(/[^a-z0-9]/ig, '');
        if (this.zoomid == thisid) return;
        if (this.lastover == thisid) {
            this.zoomid = thisid;
            this.countoffset();
            this.move();
            return;
        }
        if (typeof(highres) == "undefined")
            highres = lowres;
        
        /* set internal variables on intialisation */
        this.defzoomamount = this.isopera ? 2 : this.defzoomamount;
        this.zoomamount = this.defzoomamount;
        this.zoomamountmax = this.isopera ? 3 : this.zoomamountmax;
        this.tooltipstyle = this.isopera ? 'display: none' : this.tooltipstyle;
        this.zoombordercolor = this.zoomborderoricolor;
        
        this.objw = ow;
        this.objh = oh;
        this.zoomid = thisid;
        if (e.pageX) {
            e.target.parentNode.id = thisid;
            this.countoffset();
            this.lastxx = e.pageX - this.ffox;
            this.lastyy = e.pageY - this.ffoy;
        } else {
            e.srcElement.parentNode.id = thisid;
            this.countoffset();
            this.lastxx = e.clientX - this.ieox;
            this.lastyy = e.clientY - this.ieoy; 
        }
        this.lastover = thisid;
        this.oController = $(thisid);
        this.oController.style.width     = ow + 'px';
        this.oController.style.height    = (oh + ((this.isopera) ? 5 : 0)) + 'px';
        this.oController.style.overflow  = 'hidden';
        this.oController.style.textAlign = 'center';
        
        this.oController.innerHTML=
            '<textarea id="' + thisid + '_trigger" style="font-size: 1px; position: absolute; width: 100px; height: ' + ((this.isopera) ? '4px' : '0; filter: alpha(opacity=0);') +
            '; border: 0; margin: 0; padding: 0; overflow: hidden; z-index: 0;"/></textarea>' +
            '<div style = "text-align: left; position: absolute; overflow: hidden; width: 0; height: 0; border: 0;" id = "' + thisid + '_container" onmousemove="tjpZoom.move(event);" onmouseout="tjpZoom.off()">' +
            '  <div id="' + thisid + '_clipborder" style="position: absolute; overflow: hidden; border: ' + this.zoomborder + 'px solid ' + this.zoombordercolor + ';">' +
            '    <div style="width: 0; height: 0; overflow: hidden;" id="' + thisid + '_padder"></div>' +
            '  </div>' +
            '  <img src="' + highres + '" alt="" id="' + thisid + '_clip" style="padding: 0; margin: 0; border: 0;" onload="tjpZoom.hidone();" />' +
            '</div>' +
            '<div style="text-align: left; position: absolute; z-index: 2; float: left; ' + this.tooltipstyle + '" id="' + thisid + '_tooltip">Loading...</div>' +
            '<img src="' + lowres + '" id="' + thisid + '_pic" alt="" style="padding:0; margin: 0; border: 0; z-index: 10; width: ' + ow + 'px; height: ' + oh + 'px"/>';
        this.oTrigger    = $(thisid + '_trigger');
        this.oContainer  = $(thisid + '_container');
        this.oClipborder = $(thisid + '_clipborder');
        this.oPadder     = $(thisid + '_padder');
        this.oClip       = $(thisid + '_clip');
        this.oTooltip    = $(thisid + '_tooltip');
        this.oPic        = $(thisid + '_pic');
        
        if (this.zoomw > this.objw) {
            this.zoomw = this.objw;
            this.zoomh = this.objw / this.zoomratio;
        } else if (this.zoomh > this.objh) {
            this.zoomh = this.objh;
            this.zoomw = this.objh * this.zoomratio;
        }
        this.init();
        this.move('');
        this.oContainer.style.cursor = 'wait';
        this.flagHidone = 0;
        if (this.isopera) {
            document.onkeypress = this.set;
        } else {
            if (document.all) {
                document.onkeydown = this.set;
            } else {
                window.captureEvents(Event.KEYDOWN);
                window.onkeydown = this.set;
            }
        }
        return false;
    }
}