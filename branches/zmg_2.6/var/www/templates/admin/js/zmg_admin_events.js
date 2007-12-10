if (!window.ZMG) window.ZMG = {};

ZMG.Events = Class({
    initialize: function() {
        this.Server = new ZMG.Events.Server();
        this.Client = new ZMG.Events.Client();
    }
});

ZMG.Events.Server = Class({
    initialize: function() {
        this.settingsTabs = null;
        this.settingsMap  = [];
        this.activeView   = null;
    },
    onview: function(text, xml, data, resp) {
        var key, view = data.view;
        if (view == "admin:gallerymanager") {
            this.Server.ongallerymanager(text);
        } else if (view == "admin:mediamanager") {
            //var o = Json.evaluate(text);
            this.Server.onmediamanager(text);
        } else if (view == "admin:settings:overview") {
            var o = Json.evaluate(text);
            this.Server.onsettingsoverview(o);
        } else if (view == "admin:settings:meta") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        } else if (view == "admin:settings:locale") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        } else if (view == "admin:settings:filesystem") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        } else if (view == "admin:settings:layout") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        } else if (view == "admin:settings:app") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        } else if (view == "admin:settings:info") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text);
        }
        ZMG.Admin.Events.Client.onhideloader();
    },
    ongallerymanager: function(html) {
        if (!ZMG.Admin.cacheElement('zmg_view_gm')) {
            var oGM = new Element('div', { id: 'zmg_view_gm' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oGM);
            
            oGM.innerHTML = html;
        }
        this.onactivateview('zmg_view_gm');
    },
    onmediamanager: function(html) {
        if (!ZMG.Admin.cacheElement('zmg_view_mm')) {
            var oMM = new Element('div', { id: 'zmg_view_mm' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oMM);
            oMM.innerHTML = html;
            
            var el    = $('zmg_mm_lgrid');
            var nav   = el.getElement('.lgrid-nav');
            var pager = $('lgrid-nav-pager');
            var paging = [];

            this.liveGrid = new LiveGrid(el, {
                scroller: el.getElement('.lgrid-scroller'),
                body: el.getElement('.lgrid-body'),
                editpanel: el.getElement('.lgrid-panel-edit'),
                count: ZMG.CONST.mediumcount || 1,
                url: ZMG.CONST.req_uri + "&view=admin:mediamanager:getmedia",
                onComplete: function(xhr) {
                    nav.getFirst().setHTML(xhr.running ? (xhr.running + ' request(s) ... ') : '');
                },
                onRowClick: function(e) {
                    e = new Event(e);
                    var el = e.target;
                    while (el.tagName.toLowerCase() != "div")
                        el = el.parentNode;
                    var img_id = el.getElementsByTagName('img')[0].id.split('_')[0];
                    ZMG.Admin.Events.Client.onlivegridbodyslide(this);
                },
                onScroll: function(from, to) {
                    nav.getLast().setHTML('Displaying entries ', from, ' - ', to, ' of ', this.count);
                    pager.value = this.page;
                    if (!paging.length) return;
                    if (this.page > 1) {
                        paging[0].getParent().removeClass('lgrid-nav-btn-disabled');
                        paging[1].getParent().removeClass('lgrid-nav-btn-disabled');
                    } else {
                        paging[0].getParent().addClass('lgrid-nav-btn-disabled');
                        paging[1].getParent().addClass('lgrid-nav-btn-disabled');
                    }
                    if (this.page >= Math.ceil(this.count / this.perPage)) {
                        paging[2].getParent().addClass('lgrid-nav-btn-disabled');
                        paging[3].getParent().addClass('lgrid-nav-btn-disabled');
                    } else {
                        paging[2].getParent().removeClass('lgrid-nav-btn-disabled');
                        paging[3].getParent().removeClass('lgrid-nav-btn-disabled');
                    }
                },
                requestData: {big: '1'}
            });
            
            ZMG.Admin.Events.Client.onwindowresize();
            
            var self = this; 
            ['first', 'prev', 'next', 'last'].each(function(set) {
                paging.push(self.liveGrid.element.getElement('.lgrid-nav-btn-' + set));
            });
            paging[0].addEvent('click', this.liveGrid.scrollComplete.bind(this.liveGrid, [-1]));
            paging[0].getParent().addClass('lgrid-nav-btn-disabled');
            paging[1].addEvent('click', this.liveGrid.scrollByPage.bind(this.liveGrid, [-1]));
            paging[1].getParent().addClass('lgrid-nav-btn-disabled');
            //paging[2].addEvent('click', this.liveGrid.scrollBy.bind(this.liveGrid, [-1]));
            //paging[3].addEvent('click', this.liveGrid.scrollBy.bind(this.liveGrid, [1]));
            paging[2].addEvent('click', this.liveGrid.scrollByPage.bind(this.liveGrid, [1]));
            paging[3].addEvent('click', this.liveGrid.scrollComplete.bind(this.liveGrid, [1]));
            
            pager.addEvent('change', ZMG.Admin.Events.Client.onlivegridpager.bind(this.liveGrid, [pager]));
        }
        this.onactivateview('zmg_view_mm');
    },
    onsettingsoverview: function(node) {
        if (!ZMG.Admin.cacheElement('zmg_view_settings')) {
            //first, build the settings container DIV
            var oSettings = new Element('div', { id: 'zmg_view_settings' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oSettings);
            
            //second, initialize the SimpleTabs widget
            this.settingsTabs = new SimpleTabs(oSettings, {
                entrySelector: 'li a',
                ajaxOptions: {
                    method: 'get'
                },
                onShow: function(toggle, container, idx) {
                    toggle.addClass('tab-selected');
                    container.effect('opacity').start(0, 1); // 1) first start the effect
                    container.setStyle('display', ''); // 2) then show the element, to prevent flickering
                    
                    var klass = ZMG.Admin.Events;
                    var entry = klass.Server.settingsTabs.entries[idx];
                    klass.Server.onactivateview('zmg_view_settings');
                    if (!entry.loaded && entry.data) {
                        klass.Client.onviewselect(entry.data[0], entry.data[1]);
                    } else {
                        klass.Client.onping(idx);
                    }
                }
            });
            for (var i in node.tabs) {
                this.settingsTabs.addTab(node.tabs[i].name, node.tabs[i].title,
                  node.tabs[i].url, node.tabs[i].data);
                this.settingsMap.push(node.tabs[i].data[0]);
            }
            ZMG.Admin.Events.Client.requestingTabs = false;
        }
        this.onactivateview('zmg_view_settings');
        ZMG.Admin.Events.Client.onviewselect('admin:settings:meta', 'html');
    },
    onloadsettingstab: function(idx, html) {
        if (!this.settingsTabs.entries[idx].loaded) {
            this.settingsTabs.entries[idx].container.innerHTML = html;
            this.settingsTabs.entries[idx].loaded = true;
            //turn checkboxes and radiobuttons into fancy looking elements
            FancyForm.start($A(this.settingsTabs.entries[idx].container
              .getElementsByTagName('input')));
        }
        this.onactivateview('zmg_view_settings');
        this.settingsTabs.select(idx);
    },
    onactivateview: function(el) {
        if (!ZMG.Admin.cacheElement(el)) return;
        var oParent = ZMG.Admin.cacheElement('zmg_view_content');
        for (var i = 0; i < oParent.childNodes.length; i++)
            if (oParent.childNodes[i].nodeType == 1)
                oParent.childNodes[i].setStyle('display', 'none');
        this.activeView = ZMG.Admin.cacheElement(el);
        this.activeView.setStyle('display', '');
    },
    ongetsettingskey: function(view) {
        for (var i = 0; i < this.settingsMap.length; i++)
            if (this.settingsMap[i] == view)
                return i;
        return 0;
    },
    onerror: function() {
        console.dir(arguments);
    }
});

ZMG.Events.Client = Class({
    initialize: function() {
        this.requestQueue = null;
        this.menuTree = null;
        this.requestingTabs = false;
        //LiveGrid content sliders
        this.bodySlide = null;
        this.editSlide = null;
        
        window.addEvent('resize', this.onwindowresize.bind(this));
        
        var el = ZMG.Admin.cacheElement('zmg_tree_toolpin');
        el.onmouseover = this.onpinmouseenter.bindWithEvent(el);
        el.onmouseout  = this.onpinmouseleave.bindWithEvent(el);
        el.onclick     = this.onpinmouseclick.bindWithEvent(el);
    },
    onpinmouseenter: function(e) {
        this.addClass('zmg_tool_pinned_hover');
    },
    onpinmouseleave: function(e) {
        this.removeClass('zmg_tool_pinned_hover');
    },
    onpinmouseclick: function(e) {
        //TODO
        alert('unpin!');
    },
    onlivegridpager: function(oPager) {
        var diff = parseInt(oPager.value) - this.page;
        if ((this.page + diff) > 0 && (this.page + diff) <= Math.ceil(this.count / this.perPage))
            this.scrollByPage(diff);
    },
    onlivegridbodyslide: function(livegrid) {
        if (!this.bodySlide) {
            this.bodySlide = new Fx.Slide(livegrid.body, {mode: 'horizontal'});
            this.editSlide = new Fx.Slide(livegrid.options.editpanel,
              {mode: 'horizontal'});
        }
        this.bodySlide.slideOut();
        this.editSlide.slideOut();
        livegrid.scrollComplete(-1);
    },
    onlivegrideditslide: function() {
        if (this.bodySlide) {
            this.bodySlide.slideIn();
            this.editSlide.slideIn();
        }
    },
    onloadnavigation: function() {
        this.menuTree = new MooTreeControl({
            div     : ZMG.Admin.cacheElement('zmg_tree_body'),
            mode    : 'files',
            grid    : true,
            theme   : ZMG.CONST.res_path + '/images/mootree.gif',
            loader  : {
                icon  : ZMG.CONST.res_path + '/images/spinner_small.gif',
                text  : 'Loading...',
                color : '#a0a0a0'
            },
            onSelect: function(node, state) {
                if (state) {
                    var forcetype = "";
                    if (node.data.extra && node.data.extra.forcetype)
                        forcetype = node.data.extra.forcetype;
                    
                    var load = true;
                    var tabs = ZMG.Admin.Events.Server.settingsTabs;
                    if (tabs && node.id.indexOf('settings:') > -1) {
                        var key = ZMG.Admin.Events.Server.ongetsettingskey(node.id);
                        if (tabs.entries[key].loaded) {
                            tabs.select(key);
                            load = false;
                        }
                    }
                    if (load)
                        ZMG.Admin.Events.Client.onviewselect(node.id, forcetype);
                }
            },
            onExpand: function(node, state) {
                if (node && node.id)
                    this.onSelect(node, state);
            }
        }, {
            text: 'Menu',
            open: true
        });
        
        this.menuTree.root.load(ZMG.CONST.req_uri + '&view=admin:treemenu');
    },
    onviewselect: function(view, forcetype) {
        if (!ZMG.Admin.Events.Server.settingsTabs && !this.requestingTabs) {
            this.requestingTabs = true;
            this.onviewselect('admin:settings:overview');
        }
        
        vars = {
            'view': (view || ZMG.CONST.active_view) 
        }
        if (forcetype)
            vars.forcetype = forcetype;
            
        if (!this.requestQueue) {
            this.requestQueue = new AjaxQueue(ZMG.CONST.req_uri, {
                ajaxOptions: {
                    method: 'get'
                },
                onSuccess: ZMG.Admin.Events.Server.onview.bind(ZMG.Admin.Events),
                onFailure: ZMG.Admin.Events.Server.onerror.bind(ZMG.Admin.Events),
            });
        }
        
        var self = this;
        var f = function() {
            self.requestQueue.request(vars || '');
        };
        this.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
    },
    onping: function() {
        this.onviewselect('ping');
    },
    onshowloader: function() {
        ZMG.Admin.cacheElement('zmg_admin_loader').setStyle('display', '');
    },
    onhideloader: function() {
        ZMG.Admin.cacheElement('zmg_admin_loader').setStyle('display', 'none');
    },
    onwindowresize: function() {
        var width = ZMG.Admin.cacheElement('zmg_admin_cont').offsetWidth;
        ZMG.Admin.cacheElement('zmg_view_content').style.width = (width - 258) + "px";
        if (ZMG.Admin.Events.Server.liveGrid) {
            var gridWidth = (width - 278);
            ZMG.Admin.Events.Server.liveGrid.gridWidth = gridWidth; 
            ZMG.Admin.Events.Server.liveGrid.body.style.width =
              ZMG.Admin.Events.Server.liveGrid.options.editpanel.style.width =
              gridWidth + "px";
            ZMG.Admin.Events.Server.liveGrid.options.editpanel.style.left = gridWidth + "px"; 
        }
    },
    onerror: function() {
        console.dir(arguments);
    }
});
