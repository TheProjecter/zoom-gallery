if (!window.ZMG) window.ZMG = {};

ZMG.ClientEvents = (function() {
    var requestQueue = null;
    var lastRequest = null;
    var requestingTabs = false;
    var tabsTimeout = null;
    
    var tooltips = [];
    
    //LiveGrid content sliders
    var bodySlide = null;
    var editSlide = null;
    //LiveGrid content filter
    var activeFilter  = null;
    ZMG.Shared.register('filterSelects', []);
    
    function onStart() {
        ZMG.Shared.register('toolbar', new ZMG.Toolbar());
//        this.tooltip = new ZMG.Tooltip(null, {
//            parentElement: ZMG.Admin.cacheElement('zmg_admin_messagecenter')
//        });
        window.addEvent('resize', onWindowResize.bind(this));
        
        var el = ZMG.Shared.cacheElement('zmg_tree_toolpin');
        el.onmouseover = ZMG.EventHandlers.onPinMouseEnter;
        el.onmouseout  = ZMG.EventHandlers.onPinMouseLeave;
        el.onclick     = ZMG.EventHandlers.onPinMouseClick;
        
        //set correct dimensions of the admin content
        onWindowResize();
        //load the tree
        onLoadNavigation();
        //set the initial view
        onViewSelect();
    }
    
    function onShowMessage(title, msg, posTop, posLeft) {
        var mc  = ZMG.Shared.cacheElement('zmg_admin_messagecenter');
        mc.setStyle('display', '');
        var oToolbar = ZMG.Shared.get('toolbar');
        if (!posTop && !posLeft && oToolbar) {
            var pos = oToolbar.node.getCoordinates();
            posTop  = pos.top + 12;
            posLeft = pos.left - 250;
        }
        
        mc.setStyle('top',  posTop  + "px");
        mc.setStyle('left', posLeft + "px");
        
        var tooltip = new ZMG.Tooltip(false, {
            parentNode: mc
        }).setContent(title, msg).show();
        
        window.setTimeout(onHideMessages.pass(tooltip, this), 7500);
        
        tooltips.push(tooltip);
    };
    
    function onHideMessages(tooltip) {
        tooltip.hide();
        
        var found = false;
        for (var i = 0; i < tooltips.length && !found; i++) {
            if (tooltips[i] == tooltip) {
                tooltips.splice(i, 1);
                found = true;
            }
        }
        if (!tooltips.length)
            ZMG.Shared.cacheElement('zmg_admin_messagecenter').setStyle('display', 'none');
    };
    
    function onLiveGridPager(oPager) {
        var diff = parseInt(oPager.value) - this.page;
        if ((this.page + diff) > 0 && (this.page + diff) <= Math.ceil(this.count / this.perPage))
            this.scrollByPage(diff);
    };
    
    function onLiveGridBodySlide(livegrid) {
        if (!bodySlide) {
            bodySlide = new Fx.Slide(livegrid.body, {mode: 'horizontal', duration: 220, fps: 25});
            editSlide = new Fx.Slide(livegrid.options.editpanel,
              {mode: 'horizontal', duration: 220, fps: 25});
        }
        bodySlide.slideOut();
        editSlide.slideOut();
        
        ZMG.Shared.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'hidden');
        livegrid.scroller.setStyle('overflow-y', 'hidden');
        
        livegrid.scrollComplete(-1);
        
        var oToolbar = ZMG.Shared.get('toolbar');
        if (oToolbar) oToolbar.show('mediumedit');
    };
    
    function onLiveGridEditSlide() {
        oLiveGrid = ZMG.Shared.get('liveGrid');
        if (oLiveGrid && bodySlide) {
            oLiveGrid.scroller.setStyle('overflow-y', 'visible');
            bodySlide.slideIn();
            editSlide.slideIn();
            
            ZMG.Shared.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'visible');
            
            var oToolbar = ZMG.Shared.get('toolbar');
            if (oToolbar) oToolbar.show('zmg_view_mm');
        }
    };
    
    function onLoadNavigation() {
        var oMenuTree = new MooTreeControl({
            div     : ZMG.Shared.cacheElement('zmg_tree_body'),
            mode    : 'files',
            grid    : true,
            theme   : ZMG.CONST.res_path + '/images/mootree.gif',
            loader  : {
                icon  : ZMG.CONST.res_path + '/images/spinner_small.gif',
                text  : 'Loading...',
                color : '#a0a0a0'
            },
            onSelect: function(node, state) {
                if (!node.id) return;
                if (state) {
                    var forcetype = "";
                    if (node.data.extra && node.data.extra.forcetype)
                        forcetype = node.data.extra.forcetype;
                    
                    var load = true;
                    var oTabs = ZMG.Shared.get('settingsTabs');
                    if (oTabs && node.id.indexOf('settings:') > -1) {
                        var key = ZMG.ServerEvents.onGetSettingsKey(node.id);
                        if (oTabs.entries[key].loaded) {
                            oTabs.select(key);
                            load = false;
                        }
                    }
                    if (load)
                        onViewSelect(node.id, forcetype);
                    else
                        ZMG.ServerEvents.onLoadSettingsTab(
                          ZMG.ServerEvents.onGetSettingsKey(node.id, null));
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
        ZMG.Shared.register('menuTree', oMenuTree);
        
        oMenuTree.root.load(ZMG.CONST.req_uri + '&view=admin:treemenu');
    };
    
    function onUpdateToolbar(view) {
        if (!view) return;
        var oToolbar = ZMG.Shared.get('toolbar');
        if (!oToolbar) return;

        if (view === "admin:gallerymanager") {
            oToolbar.disable('zmg_view_gm', ['gallerySave', 'galleryDelete']);
        } else if (view.indexOf('admin:gallerymanager:get:new') > -1) {
            oToolbar.enable('zmg_view_gm', ['gallerySave']);
        }  else if (view.indexOf('admin:gallerymanager:get:') > -1) {
            oToolbar.enable('zmg_view_gm', ['gallerySave', 'galleryDelete']);
        }
    }
    
    function onViewSelect(view, forcetype) {
        if (!ZMG.Shared.get('settingsTabs') && !requestingTabs) {
            requestingTabs = true;
            onViewSelect('admin:settings:overview');
        }
        view = view || ZMG.CONST.active_view;
        if (!view) return;
        
        //toolbar control:
        onUpdateToolbar(view);
        
        vars = {
            'view': view 
        }
        if (forcetype)
            vars.forcetype = forcetype;
        
        var valid = true;
        
        if (!requestQueue) {
            requestQueue = new AjaxQueue(ZMG.CONST.req_uri, {
                ajaxOptions: {
                    method: 'get'
                },
                onSuccess: ZMG.ServerEvents.onView.bind(ZMG.ServerEvents),
                onFailure: ZMG.ServerEvents.onError.bind(ZMG.ServerEvents),
            });
        } else {
            //prevent duplicate requests:
            if (lastRequest == vars.view)
                valid = false;
        }
        
        if (valid) {
            lastRequest = vars.view;
            onShowLoader();
            // allowing a small delay for the browser to draw the loader-icon.
            window.setTimeout(function() {
                requestQueue.request(vars || '');
            }, 20);
        }
    };
    
    function onSelectSettingsTab(view) {
        if (view == "admin:settings:plugins") {
            if (!tabsTimeout) {
                tabsTimeout = window.setTimeout(function() {
                    onViewSelect('admin:settings:plugins:autodetect');
                    clearTimeout(tabsTimeout);
                    tabsTimeout = null;
                }, 1500);
            }
        }
    };
    
    function onMmSetFilterSelects() {
        var oSelects = ZMG.Shared.get('filterSelects');
        if (!oSelects) return;
        
        if (arguments[0]) oSelects.include(arguments[0]);
        for (var i = 0; i < oSelects.length; i++) {
            if (oSelects[i])
                oSelects[i].value = activeFilter;
        }
    };
    
    function onMmGalleryChange(oSelect) {
        var oSelects = ZMG.Shared.get('filterSelects');
        if (!oSelects) return;
        
        oSelects.include(oSelect);
        
        var value = parseInt(oSelect.value);
        if (value === 0) value = null;
        var reload = (activeFilter !== value);
        
        activeFilter = value;
        
        //get new number of media:
        ZMG.Dispatches.mediaCount(value);
        
        var liveGrid = ZMG.Shared.get('liveGrid');
        liveGrid.setCount(ZMG.CONST.mediumcount || 1)
         .setUrl(ZMG.CONST.req_uri + "&view=admin:mediamanager:getmedia"
          + (value ? ":" + value : "")).refresh();
    };
    
    function onPing() {
        clearTimeout(ZMG.pingTimer);
        onViewSelect('ping');
        ZMG.pingTimer = window.setTimeout(onPing, ZMG.CONST.refreshtime);
    };
    
    function onShowLoader() {
        ZMG.Shared.cacheElement('zmg_admin_loader').setStyle('display', '');
    };
    
    function onHideLoader() {
        ZMG.Shared.cacheElement('zmg_admin_loader').setStyle('display', 'none');
    };
    
    function onWindowResize() {
        var width = ZMG.Shared.cacheElement('zmg_admin_cont').offsetWidth;
        ZMG.Shared.cacheElement('zmg_view_content').style.width = (width - 258) + "px";
        var lGrid = ZMG.Shared.get('liveGrid');
        if (lGrid) {
            var gridWidth = (width - 278);
            lGrid.gridWidth = gridWidth;
            lGrid.body.style.width = lGrid.options.editpanel.style.width = (gridWidth - 4) + "px";
            lGrid.options.editpanel.style.left = (gridWidth + 4) + "px"; 
        }
    };
    
    function onError() {
        console.dir(arguments);
    };
    
    function getActiveFilter() {
        return activeFilter;
    };
    
    return {
        onShowLoader: onShowLoader,
        onHideLoader: onHideLoader,
        onLiveGridBodySlide: onLiveGridBodySlide,
        onLiveGridEditSlide: onLiveGridEditSlide,
        onLiveGridPager: onLiveGridPager,
        onLoadNavigation: onLoadNavigation,
        onMmSetFilterSelects: onMmSetFilterSelects,
        onMmGalleryChange: onMmGalleryChange,
        onSelectSettingsTab: onSelectSettingsTab,
        onShowMessage: onShowMessage,
        onStart: onStart,
        onViewSelect: onViewSelect,
        onWindowResize: onWindowResize,
        onPing: onPing,
        getActiveFilter: getActiveFilter
    };
})();

ZMG.pingTimer = window.setTimeout(ZMG.ClientEvents.onPing, ZMG.CONST.refreshtime);
