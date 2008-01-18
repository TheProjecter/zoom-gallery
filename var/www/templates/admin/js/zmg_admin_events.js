if (!window.ZMG) window.ZMG = {};

ZMG.Events = new Class({
    initialize: function() {
        this.Server = new ZMG.Events.Server();
        this.Client = new ZMG.Events.Client();
    }
});

ZMG.Events.Server = new Class({
    initialize: function() {
        this.settingsTabs = null;
        this.settingsMap  = [];
        this.activeView   = null;
    },
    onview: function(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        console.log('Server#onview: ', view);
        ZMG.Admin.Events.Client.lastRequest = null;
        if (view == "admin:gallerymanager") {
            this.Server.ongallerymanager(text);
        } else if (view.indexOf('admin:gallerymanager:get:') > -1) {
            o = Json.evaluate(text);
            this.Server.onloadgallerydata(o);
            isJSON = true;
        } else if (view == "admin:mediamanager") {
            this.Server.onmediamanager(text);
        } else if (view.indexOf('admin:mediamanager:get:') > -1) {
            o = Json.evaluate(text);
            this.Server.onloadmediumdata(o);
            isJSON = true;
        } else if (view == "admin:mediamanager:upload") {
            this.Server.onmediamanagerupload(text);
        } else if (view.indexOf('admin:toolbar:') > -1) {
            o = Json.evaluate(text);
            this.Server.ontoolbar(o);
            isJSON = true;
        } else if (view == "admin:settings:overview") {
            o = Json.evaluate(text);
            this.Server.onsettingsoverview(o);
            isJSON = true;
        } else if (view == "admin:settings:meta"
          || view == "admin:settings:locale"
          || view == "admin:settings:filesystem"
          || view == "admin:settings:layout"
          || view == "admin:settings:app"
          || view == "admin:settings:plugins"
          || view == "admin:settings:info") {
            key = this.Server.ongetsettingskey(view);
            this.Server.onloadsettingstab(key, text, view);
        } else if (view != "ping" && text) {
            o = Json.evaluate(text);
            isJSON = true;
        }
        ZMG.Admin.Events.Client.onhideloader();
        
        if (isJSON) {
            //check if there are any messages we need to display:
            if (o.messagecenter && o.messagecenter.messages.length) {
                for (var i = 0; i < o.messagecenter.messages.length; i++)
                    this.Client.onshowmessage(o.messagecenter.messages[i].title,
                      o.messagecenter.messages[i].descr);
            }
        }
    },
    ondispatchresult: function(text, xml) {
        var o = Json.evaluate(text);
        if (o && o.action) {
            
            if (o.action == "settings_store") {
                
            } else if (o.action == "medium_store") {
                
            } else if (o.action == "gallery_store") {
                
            }
            for (var i = 0; i < o.messagecenter.messages.length; i++) {
                this.Client.onshowmessage(o.messagecenter.messages[i].title,
                  o.messagecenter.messages[i].descr);
            }
        }
        this.Client.onhideloader();
    },
    ongallerymanager: function(html) {
        if (!ZMG.Admin.cacheElement('zmg_view_gm')) {
            var oGM = new Element('div', { id: 'zmg_view_gm' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oGM);
            
            oGM.innerHTML = html;
            
            FancyForm.start($A(oGM.getElementsByTagName('input')));
            new SimpleTabs($('zmg_edit_gallery_tabs'), {
                entrySelector: 'li a',
                onShow: function(toggle, container, idx) {
                    toggle.addClass('tab-selected');
                    container.effect('opacity').start(0, 1); // 1) first start the effect
                    container.setStyle('display', ''); // 2) then show the element, to prevent flickering
                },
                getContent: function(el) {
                    var content, rel = el.innerHTML.toString().toLowerCase();
                    oGM.getElements('div.tab-container')
                     .each(function(el) {
                        if (el.getAttribute('rel') == rel) content = el;
                    });
                    return content;
                }
            });
        }
        this.onactivateview('zmg_view_gm');
    },
    onloadgallerydata: function(node) {
        if (node.result == ZMG.CONST.result_ok) {
            var data = node.data.gallery;
            var form = ZMG.Admin.cacheElement('zmg_form_edit_gallery');
            form.reset();
            //ZMG.Admin.cacheElement('zmg_edit_medium_thumbnail').src = data.url;
            ZMG.Admin.cacheElement('zmg_edit_gallery_name').value = data.name;
            ZMG.Admin.cacheElement('zmg_edit_gallery_keywords').value = data.keywords;
            //ZMG.Admin.cacheElement('zmg_edit_gallery_gimg');
            //ZMG.Admin.cacheElement('zmg_edit_gallery_pimg');
            var oPublish = ZMG.Admin.cacheElement('zmg_edit_gallery_published');
            oPublish.checked = (data.published);
            FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
            
            ZMG.Admin.cacheElement('zmg_edit_gallery_descr').value = data.descr;
        }
    },
    onmediamanager: function(html) {
        if (!ZMG.Admin.cacheElement('zmg_view_mm')) {
            var oMM = new Element('div', { id: 'zmg_view_mm' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oMM);
            oMM.innerHTML = html;
            FancyForm.start($A(oMM.getElementsByTagName('input')));
            ZMG.Admin.Events.Client.filterSelects.include(oSelect);
            var oSelect = oMM.getElementsByTagName('select')[0];
            if (oSelect)
                ZMG.Admin.Events.Client.filterSelects.include(oSelect);
            
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
                    
                    //fetch the data for this gallery from the server
                    ZMG.Admin.Events.Client.onviewselect('admin:mediamanager:get:' + img_id);
                    window.setTimeout("ZMG.Admin.Events.Client.onviewselect('admin:toolbar:mediumedit');", 20);
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
            
            var self = this;
            //setup the tabs for Medium property editing:
            new SimpleTabs($('zmg_edit_medium_tabs'), {
                entrySelector: 'li a',
                onShow: function(toggle, container, idx) {
                    toggle.addClass('tab-selected');
                    container.effect('opacity').start(0, 1); // 1) first start the effect
                    container.setStyle('display', ''); // 2) then show the element, to prevent flickering
                },
                getContent: function(el) {
                    var content, rel = el.innerHTML.toString().toLowerCase();
                    self.liveGrid.options.editpanel.getElements('div.tab-container')
                     .each(function(el) {
                        if (el.getAttribute('rel') == rel) content = el;
                    });
                    return content;
                }
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
        ZMG.Admin.Events.Client.onmm_setfilterselects();
        this.onactivateview('zmg_view_mm');
    },
    onmediamanagerupload: function(html) {
        if (!ZMG.Admin.cacheElement('zmg_view_mm_upload')) {
            var oUpload = new Element('div', { id: 'zmg_view_mm_upload' });
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oUpload);
            oUpload.innerHTML = html;
            var oSelect = oUpload.getElementsByTagName('select')[0];
            if (oSelect)
                ZMG.Admin.Events.Client.filterSelects.include(oSelect);
            
            var oForm = oUpload.getElementsByTagName('form')[0];
            if (oForm)
                oForm.action = ZMG.CONST.req_uri + "&view=admin:mediaupload:store";
            
            this.Uploader = new FancyUpload($('zmg_fancyupload_filedata'), {
                swf: ZMG.CONST.base_path + '/var/www/templates/admin/other/uploader.swf',
                multiple: true,
                queued: true,
                queueList: 'zmg_fancyupload_queue',
                instantStart: false,
                allowDuplicates: true,
                types: {'All Files (*.*)': '*.*'},
                onAllComplete: function(){
                    //MediaManager.refreshFrame();
                    alert('done!' + arguments.length);
                }
            });
            
            $('zmg_fancyupload_clear').onclick = this.Uploader.clearList.bind(this.Uploader, [false]); 
        }
        ZMG.Admin.Events.Client.onmm_setfilterselects();
        this.onactivateview('zmg_view_mm_upload');
    },
    onloadmediumdata: function(node) {
        if (node.result == ZMG.CONST.result_ok) {
            var data = node.data.medium;
            var form = ZMG.Admin.cacheElement('zmg_form_edit_medium');
            form.reset();
            ZMG.Admin.cacheElement('zmg_edit_medium_thumbnail').src = data.url;
            ZMG.Admin.cacheElement('zmg_edit_filename').innerHTML = data.filename;
            ZMG.Admin.cacheElement('zmg_edit_name').value = data.name;
            ZMG.Admin.cacheElement('zmg_edit_keywords').value = data.keywords;
            //ZMG.Admin.cacheElement('zmg_edit_gimg');
            //ZMG.Admin.cacheElement('zmg_edit_pimg');
            var oPublish = ZMG.Admin.cacheElement('zmg_edit_published');
            oPublish.checked = (data.published);
            FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
            
            ZMG.Admin.cacheElement('zmg_edit_descr').value = data.descr;
        }
    },
    ontoolbar: function(node) {
        var name = node.toolbar.shift();
        ZMG.Admin.Events.Client.toolbar.create(name, node.toolbar);
        ZMG.Admin.Events.Client.toolbar.show(name);
    },
    onsettingsoverview: function(node) {
        if (!ZMG.Admin.cacheElement('zmg_view_settings')) {
            //first, build the settings container DIV
            var oSettings = new Element('div', {
                'id'   : 'zmg_view_settings',
                'class': 'tab-all-container'
            });
            var oForm = new Element('form', {
                'name': 'zmg_settings_form',
                'id'  : 'zmg_settings_form' 
            });
            oSettings.adopt(oForm);
            ZMG.Admin.cacheElement('zmg_view_content').adopt(oSettings);
            
            //second, initialize the SimpleTabs widget
            this.settingsTabs = new SimpleTabs(oForm, {
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
                    if (!entry.loaded && entry.data)
                        klass.Client.onviewselect(entry.data[0], entry.data[1]);
                    klass.Client.onselectsettingstab(entry.data[0]);
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
    onloadsettingstab: function(idx, html, view) {
        if (!this.settingsTabs.entries[idx].loaded && html) {
            this.settingsTabs.entries[idx].container.innerHTML = html;
            this.settingsTabs.entries[idx].loaded = true;
            //turn checkboxes and radiobuttons into fancy looking elements
            FancyForm.start($A(this.settingsTabs.entries[idx].container
              .getElementsByTagName('input')));
            if (view) {
                if (view == "admin:settings:plugins") {
                    var form = ZMG.Admin.cacheElement('zmg_settings_form');
                    var conv_select = form.elements['zmg_plugins_toolbox_general_conversiontool'];
                    conv_select.onchange = function() {
                        var tool = "";
                        if (this.value == "1") {
                            tool = "imagemagick";
                        } else if (this.value == "2") {
                            tool = "netpbm";
                        } else if (this.value == "3") {
                            tool = "gd1x";
                        } else if (this.value == "4") {
                            tool = "gd2x";
                        }
                        ZMG.Admin.Events.Client.onviewselect('admin:settings:plugins:autodetect:'
                          + tool);
                    }
                    
                    //first, setup the accordion tab control:
                    window.setTimeout(function() {
                        new Accordion('h3.zmg_accordion_start', 'div.zmg_accordion_start', {
                            opacity: false,
                            onActive: function(toggler, element){
                                toggler.setStyle('color', '#0b55c4');
                            },
                            onBackground: function(toggler, element){
                                toggler.setStyle('color', '#666');
                            }
                        }, $('zmg_plugins_accordion'));
                    }, 20); //timeout needed, to make this work in edge cases too :-S
                }
            }
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
        window.setTimeout("ZMG.Admin.Events.Client.onviewselect('admin:toolbar:"
          + el + "');", 100);
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

ZMG.Events.Client = new Class({
    initialize: function() {
        this.requestQueue = null;
        this.lastRequest = null;
        this.menuTree = null;
        this.requestingTabs = false;
        this.tabsTimeout = null;
        this.toolbar = new ZMG.Toolbar();
//        this.tooltip = new ZMG.Tooltip(null, {
//            parentElement: ZMG.Admin.cacheElement('zmg_admin_messagecenter')
//        });
        this.tooltips = [];
        //LiveGrid content sliders
        this.bodySlide = null;
        this.editSlide = null;
        //LiveGrid content filter
        this.activeFilter  = null;
        this.filterSelects = [];
        
        window.addEvent('resize', this.onwindowresize.bind(this));
        
        var el = ZMG.Admin.cacheElement('zmg_tree_toolpin');
        el.onmouseover = this.onpinmouseenter;
        el.onmouseout  = this.onpinmouseleave;
        el.onclick     = this.onpinmouseclick;
        
        this.onping.periodical(ZMG.CONST.refreshtime);
    },
    onshowmessage: function(title, msg, posTop, posLeft) {
        var mc  = ZMG.Admin.cacheElement('zmg_admin_messagecenter');
        mc.setStyle('display', '');
        if (!posTop && !posLeft) {
            var pos = this.toolbar.node.getCoordinates();
            posTop  = pos.top + 12;
            posLeft = pos.left - 250;
        }
        
        mc.setStyle('top',  posTop  + "px");
        mc.setStyle('left', posLeft + "px");
        
        var tooltip = new ZMG.Tooltip(false, {
            parentNode: mc
        }).setContent(title, msg).show();
        
        window.setTimeout(this.onhidemessages.pass(tooltip, this), 7500);
        
        this.tooltips.push(tooltip);
    },
    onhidemessages: function(tooltip) {
        tooltip.hide();
        
        var found = false;
        for (var i = 0; i < this.tooltips.length && !found; i++) {
            if (this.tooltips[i] == tooltip) {
                this.tooltips.splice(i, 1);
                found = true;
            }
        }
        if (!this.tooltips.length)
            ZMG.Admin.cacheElement('zmg_admin_messagecenter').setStyle('display', 'none');
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
        ZMG.Admin.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'hidden');
        livegrid.scrollComplete(-1);
        this.toolbar.show('mediumedit');
    },
    onlivegrideditslide: function() {
        if (this.bodySlide) {
            this.bodySlide.slideIn();
            this.editSlide.slideIn();
            ZMG.Admin.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'visible');
            this.toolbar.show('zmg_view_mm');
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
                if (!node.id) return;
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
                    else
                        ZMG.Admin.Events.Server.onloadsettingstab(
                          ZMG.Admin.Events.Server.ongetsettingskey(node.id, null));
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
    onupdatetoolbar: function(view) {
        view = view || ZMG.Admin.Events.Server.activeView;
        if (!view) return;
        
        if (view == "admin:gallerymanager") {
            this.toolbar.disable('zmg_view_gm', ['gallerysave', 'gallerydelete']);
        } else if (view.indexOf('admin:gallerymanager:get:') > -1) {
            this.toolbar.enable('zmg_view_gm', ['gallerysave', 'gallerydelete']);
        }
    },
    onviewselect: function(view, forcetype) {
        if (!ZMG.Admin.Events.Server.settingsTabs && !this.requestingTabs) {
            this.requestingTabs = true;
            this.onviewselect('admin:settings:overview');
        }
        view = view || ZMG.CONST.active_view;
        if (!view) return;
        
        //toolbar control:
        this.onupdatetoolbar(view);
        
        vars = {
            'view': view 
        }
        if (forcetype)
            vars.forcetype = forcetype;
        
        var valid = true;
        
        if (!this.requestQueue) {
            this.requestQueue = new AjaxQueue(ZMG.CONST.req_uri, {
                ajaxOptions: {
                    method: 'get'
                },
                onSuccess: ZMG.Admin.Events.Server.onview.bind(ZMG.Admin.Events),
                onFailure: ZMG.Admin.Events.Server.onerror.bind(ZMG.Admin.Events),
            });
        } else {
            //prevent duplicate requests:
            if (this.lastRequest == vars.view)
                valid = false;
        }
        
        if (valid) {
            this.lastRequest = vars.view;
            var self = this;
            var f = function() {
                self.requestQueue.request(vars || '');
            };
            this.onshowloader();
            // allowing a small delay for the browser to draw the loader-icon.
            window.setTimeout(f.bind(this), 20);
        }
    },
    onselectsettingstab: function(view) {
        if (view == "admin:settings:plugins") {
            if (!this.tabsTimeout) {
                this.tabsTimeout = window.setTimeout(function() {
                    ZMG.Admin.Events.Client.onviewselect('admin:settings:plugins:autodetect');
                    clearTimeout(ZMG.Admin.Events.Client.tabsTimeout);
                    ZMG.Admin.Events.Client.tabsTimeout = null;
                }, 1500);
            }
        }
    },
    onmm_setfilterselects: function() {
        if (arguments[0]) this.filterSelects.include(arguments[0]);
        for (var i = 0; i < this.filterSelects.length; i++) {
            if (this.filterSelects[i])
                this.filterSelects[i].value = this.activeFilter;
        }
    },
    onmm_uploadclick: function(e) {
        this.onviewselect('admin:mediamanager:upload', 'html');
    },
    onmm_gallerychange: function(oSelect) {
        this.filterSelects.include(oSelect);
        
        var value = parseInt(oSelect.value);
        if (value === 0) value = null;
        var reload = (this.activeFilter !== value);
        
        this.activeFilter = value;
        
        //get new number of media:
        new XHR({
            async: false,
            onSuccess: function(text, xml) {
                var o = Json.evaluate(text);
                ZMG.CONST.mediumcount = parseInt(o.result);
            }
        }).send(ZMG.CONST.req_uri + "&view=admin:update:mediacount:" + value, '');
        
        var liveGrid = ZMG.Admin.Events.Server.liveGrid;
        liveGrid.options.count = ZMG.CONST.mediumcount || 1;
        liveGrid.options.url = ZMG.CONST.req_uri + "&view=admin:mediamanager:getmedia"
          + (value ? ":" + value : "");
        liveGrid.refresh();
    },
    onsettingssaveclick: function(e) {
        var data = FormSerializer.serialize($('zmg_settings_form'));
        var url  = ZMG.CONST.req_uri + "&view=admin:settings:store";
        var f = function() {
            new XHR({
                onSuccess: ZMG.Admin.Events.Server.ondispatchresult.bind(ZMG.Admin.Events),
                onFailure: ZMG.Admin.Events.Server.onerror.bind(ZMG.Admin.Events)
            }).send(url, data || '');
        };
        this.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
    },
    onmediumbackclick: function(e) {
        this.toolbar.clear();
        this.onlivegrideditslide();
    },
    onmediumsaveclick: function(e) {
        var data = FormSerializer.serialize($('zmg_form_edit_medium'));
        var url  = ZMG.CONST.req_uri + "&view=admin:mediumedit:store";
        var f = function() {
            new XHR({
                onSuccess: ZMG.Admin.Events.Server.ondispatchresult.bind(ZMG.Admin.Events),
                onFailure: ZMG.Admin.Events.Server.onerror.bind(ZMG.Admin.Events)
            }).send(url, data || '');
        };
        this.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
    },
    ongallerynewclick: function(e) {
        
    },
    ongallerysaveclick: function(e) {
        alert('clickie!');
    },
    ongallerydeleteclick: function(e) {
        alert('clickie!');
    },
    onping: function() {
        ZMG.Admin.Events.Client.onviewselect('ping');
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
              (gridWidth - 4) + "px";
            ZMG.Admin.Events.Server.liveGrid.options.editpanel.style.left = (gridWidth + 4) + "px"; 
        }
    },
    onerror: function() {
        console.dir(arguments);
    }
});
