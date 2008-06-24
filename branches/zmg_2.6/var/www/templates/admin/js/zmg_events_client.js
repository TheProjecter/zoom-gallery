if (!window.ZMG) window.ZMG = {};

ZMG.ClientEvents = (function() {
    var requestQueue = null;
    var lastRequest = null;
    var requestingInitView = false;
    var tabsTimeout = null;
    
    var tooltips = [];
    
    //LiveGrid content sliders
    var bodySlide = null;
    var editSlide = null;
    //LiveGrid content filter
    var activeFilter  = null;
    ZMG.Shared.register('filterSelects', [], null, 0);
    
    function onStart() {
        ZMG.Shared.register('toolbar', new ZMG.Toolbar(), null, 0)
          .setTitleImage(ZMG.CONST.res_path + "/../../shared/images/zoom_logo_medium.gif");

        window.addEvent('resize', onWindowResize);
        
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
        var oTooltip = ZMG.GUI.displayTooltip(title, msg, posTop, posLeft);
        window.setTimeout(onHideMessages.pass(oTooltip, this), 7500);
        tooltips.push(oTooltip);
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
            ZMG.GUI.hideMessageCenter();
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
        
        ZMG.GUI.lGridHideBody(livegrid);
        
        livegrid.scrollComplete(-1);
        
        ZMG.GUI.updateToolbar('mediumedit');
    };
    
    function onLiveGridEditSlide() {
        oLivegrid = ZMG.Shared.get('liveGrid');
        if (oLivegrid && bodySlide) {
            bodySlide.slideIn();
            editSlide.slideIn();
            
            ZMG.GUI.lGridShowBody(oLivegrid);
            
            ZMG.GUI.updateToolbar('zmg_view_mm');
        }
    };
    
    function onLoadNavigation() {
        var oMenuTree = ZMG.GUI.buildMenuTree();
        
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
        if (!ZMG.Shared.cacheElement('zmg_view_gm') && !requestingInitView) {
            requestingInitView = true;
            onViewSelect('admin:gallerymanager', 'html');
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
                onFailure: ZMG.ServerEvents.onError.bind(ZMG.ServerEvents)
            });
        } else {
            //prevent duplicate requests:
            if (lastRequest == vars.view)
                valid = false;
        }
        
        if (valid) {
            lastRequest = vars.view;
            ZMG.GUI.showLoader();
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
        $clear(ZMG.pingTimer);
        onViewSelect('ping');
        ZMG.pingTimer = window.setTimeout(ZMG.ClientEvents.onPing, ZMG.CONST.refreshtime);
    };
    
    function onWindowResize() {
        ZMG.GUI.updateProportions();
    };
    
    function onError() {
        console.dir(arguments);
    };
    
    function getActiveFilter() {
        return activeFilter;
    };
    
    return {
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
