if (!window.ZMG) window.ZMG = {};

(function() {

ZMG.GUI = {
    init: function() {
        ZMG.Shared.cacheElement('zmg_admin_cont');
        ZMG.Shared.cacheElement('zmg_admin_loader');
        ZMG.Shared.cacheElement('zmg_tree_body');
        ZMG.Shared.cacheElement('zmg_view_content');
        var mc = ZMG.Shared.cacheElement('zmg_admin_messagecenter');
        document.body.appendChild(mc);
    },
    
    displayTooltip: function(sTitle, sMsg, iPosTop, iPosLeft) {
        var mc  = ZMG.Shared.cacheElement('zmg_admin_messagecenter');
        mc.setStyle('display', '');
        var oToolbar = ZMG.Shared.get('toolbar');
        if (!iPosTop && !iPosLeft && oToolbar) {
            var pos = oToolbar.node.getCoordinates();
            iPosTop  = pos.top + 12;
            iPosLeft = pos.left - 250;
        }
        
        mc.setStyle('top',  iPosTop  + "px");
        mc.setStyle('left', iPosLeft + "px");
        
        return new ZMG.Tooltip(false, {
            parentNode: mc
        }).setContent(sTitle, sMsg).show();
    },
    
    hideMessageCenter: function() {
        ZMG.Shared.cacheElement('zmg_admin_messagecenter').setStyle('display', 'none');
    },
    
    lGridHideBody: function(oLivegrid) {
        ZMG.Shared.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'hidden');
        oLivegrid.scroller.setStyle('overflow-y', 'hidden');
    },
    
    lGridShowBody: function(oLiveGrid) {
        oLiveGrid.scroller.setStyle('overflow-y', 'visible');
        ZMG.Shared.cacheElement('zmg_lgrid_pagination').setStyle('visibility', 'visible');
    },
    
    buildMenuTree: function() {
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
                        ZMG.ClientEvents.onViewSelect(node.id, forcetype);
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

        return ZMG.Shared.register('menuTree', oMenuTree, null, 0);
    },
    
    updateToolbar: function(sView) {
        var oToolbar = ZMG.Shared.get('toolbar');
        if (oToolbar) oToolbar.show(sView);
    },
    
    showLoader: function() {
        ZMG.Shared.cacheElement('zmg_admin_loader').setStyle('display', '');
    },
    
    hideLoader: function() {
        ZMG.Shared.cacheElement('zmg_admin_loader').setStyle('display', 'none');
    },
    
    updateProportions: function() {
        var width = ZMG.Shared.cacheElement('zmg_admin_cont').offsetWidth;
        ZMG.Shared.cacheElement('zmg_view_content').style.width = (width - 258) + "px";
        var lGrid = ZMG.Shared.get('liveGrid');
        if (lGrid) {
            var gridWidth = (width - 278);
            lGrid.gridWidth = gridWidth;
            lGrid.body.style.width = lGrid.options.editpanel.style.width = (gridWidth - 4) + "px";
            lGrid.options.editpanel.style.left = (gridWidth + 4) + "px"; 
        }
    },
    
    buildGalleryManager: function(sHtml) {
        oGM = new Element('div', { id: 'zmg_view_gm' });
        ZMG.Shared.cacheElement('zmg_view_content').adopt(oGM);
        
        oGM.innerHTML = sHtml;
        
        FancyForm.start($A(oGM.getElementsByTagName('input')));
        new SimpleTabs($('zmg_edit_gallery_tabs'), {
            entrySelector: 'li a',
            onShow: function(toggle, container, idx) {
                toggle.addClass('tab-selected');
                //container.effect('opacity').start(0, 1); // 1) first start the effect
                container.setStyle('display', ''); // 2) then show the element, to prevent flickering
            },
            getContent: function(el) {
                var content, rel = el.getAttribute('rel');
                oGM.getElements('div.tab-container')
                 .each(function(el) {
                    if (el.getAttribute('rel') == rel) content = el;
                });
                return content;
            }
        });

        this.updateProportions();
        
        return $('zmg_gallerymanager_newclick')
    },
    
    updateGalleryForm: function(oData) {
        var oForm = ZMG.Shared.cacheElement('zmg_form_edit_gallery');
        oForm.reset();
        
        var oImg = ZMG.Shared.cacheElement('zmg_edit_gallery_thumbnail');
        if (oData.cover_img)
            oImg.src = oData.cover_img;
        else
            oImg.src = ZMG.CONST.res_path + "/images/mimetypes/unknown.png";
        
        oForm.elements['zmg_edit_gallery_gid'].value = oData.gid || 'new';
        //ZMG.Shared.cacheElement('zmg_edit_medium_thumbnail').src = oData.url;
        oForm.elements['zmg_edit_gallery_name'].value = oData.name;
        oForm.elements['zmg_edit_gallery_dir'].value = oData.dir;
        oForm.elements['zmg_edit_gallery_keywords'].value = oData.keywords;
        //ZMG.Shared.cacheElement('zmg_edit_gallery_gimg');
        //ZMG.Shared.cacheElement('zmg_edit_gallery_pimg');
        var oHideNM = ZMG.Shared.cacheElement('zmg_edit_gallery_hidenm');
        oHideNM.checked = (oData.hide_msg);
        FancyForm[(oHideNM.checked ? 'select' : 'deselect')](oHideNM.parentNode);
        
        var oPublish = ZMG.Shared.cacheElement('zmg_edit_gallery_published');
        oPublish.checked = (oData.published);
        FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
        
        var oShared = ZMG.Shared.cacheElement('zmg_edit_gallery_shared');
        oShared.checked = (oData.shared);
        FancyForm[(oShared.checked ? 'select' : 'deselect')](oShared.parentNode);
        
        oForm.elements['zmg_edit_gallery_descr'].value = oData.descr;
        
        this.setACLSelect(oData.uid, 'zmg_edit_gallery_acl_gid');
    },
    
    buildMediaManager: function(sHtml) {
        var oMM = new Element('div', { id: 'zmg_view_mm' });
        ZMG.Shared.cacheElement('zmg_view_content').adopt(oMM);
        oMM.innerHTML = sHtml;
        FancyForm.start($A(oMM.getElementsByTagName('input')));
        var oSelect = oMM.getElementsByTagName('select')[0];
        
        var aFilterSelects = ZMG.Shared.get('filterSelects');
        if (oSelect && aFilterSelects)
            aFilterSelects.include(oSelect);
        
        var el     = $('zmg_mm_lgrid');
        var nav    = el.getElement('.lgrid-nav');
        var pager  = $('lgrid-nav-pager');
        var paging = [];

        var oLiveGrid = new LiveGrid(el, {
            scroller: el.getElement('.lgrid-scroller'),
            body: el.getElement('.lgrid-body'),
            editpanel: el.getElement('.lgrid-panel-edit'),
            count: ZMG.CONST.mediumcount || 1,
            url: ZMG.CONST.req_uri + "&view=admin:mediamanager:getmedia",
            onComplete: function(xhr) {
                nav.getFirst().setHTML(xhr.running ? (xhr.running + ' request(s) ... ') : '');
            },
            onRowMouseDown: function(e) {
                e = new Event(e);
                var el = e.target;
                while (el.tagName.toLowerCase() != "div")
                    el = el.parentNode;
                el.addClass('lgrid-body-itemsel');
            },
            onRowMouseUp: function(e) {
                e = new Event(e);
                var el = e.target;
                while (el.tagName.toLowerCase() != "div")
                    el = el.parentNode;
                el.removeClass('lgrid-body-itemsel');
            },
            onRowClick: function(e) {
                e = new Event(e);
                var el = e.target;
                while (el.tagName.toLowerCase() != "div")
                    el = el.parentNode;
                
                var imgs   = el.getElementsByTagName('img');
                if (!imgs.length) return; //prolly clicked a 'No media' row
                
                var img_id = el.getElementsByTagName('img')[0].id.split('_')[0];
                ZMG.ClientEvents.onLiveGridBodySlide(this);
                
                //fetch the data for this gallery from the server
                ZMG.ClientEvents.onViewSelect('admin:mediamanager:get:' + img_id);
                window.setTimeout("ZMG.ClientEvents.onViewSelect('admin:toolbar:mediumedit');", 20);
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
        ZMG.Shared.register('liveGrid', oLiveGrid, null, 0);
        
        //setup the tabs for Medium property editing:
        new SimpleTabs($('zmg_edit_medium_tabs'), {
            entrySelector: 'li a',
            onShow: function(toggle, container, idx) {
                toggle.addClass('tab-selected');
                //container.effect('opacity').start(0, 1); // 1) first start the effect
                container.setStyle('display', ''); // 2) then show the element, to prevent flickering
            },
            getContent: function(el) {
                var content, rel = el.getAttribute('rel');
                oLiveGrid.options.editpanel.getElements('div.tab-container')
                 .each(function(el) {
                    if (el.getAttribute('rel') == rel) content = el;
                });
                return content;
            }
        });
        
        ZMG.ClientEvents.onWindowResize();
        
        ['first', 'prev', 'next', 'last'].each(function(set) {
            paging.push(oLiveGrid.element.getElement('.lgrid-nav-btn-' + set));
        });
        paging[0].addEvent('click', oLiveGrid.scrollComplete.bind(oLiveGrid, [-1]));
        paging[0].getParent().addClass('lgrid-nav-btn-disabled');
        paging[1].addEvent('click', oLiveGrid.scrollByPage.bind(oLiveGrid, [-1]));
        paging[1].getParent().addClass('lgrid-nav-btn-disabled');
        paging[2].addEvent('click', oLiveGrid.scrollByPage.bind(oLiveGrid, [1]));
        paging[3].addEvent('click', oLiveGrid.scrollComplete.bind(oLiveGrid, [1]));
        
        pager.addEvent('change', ZMG.ClientEvents.onLiveGridPager.bind(oLiveGrid, [pager]));
    },
    
    buildUploadTabs: function(sHtml) {
        var oUpload = new Element('div', { id: 'zmg_view_mm_upload' });
        ZMG.Shared.cacheElement('zmg_view_content').adopt(oUpload);
        oUpload.innerHTML = sHtml;
        
        //fancify the checkboxes and radiobuttons
        FancyForm.start($A(oUpload.getElementsByTagName('input')));
        //setup the tabs
        new SimpleTabs($('zmg_upload_tabs'), {
            entrySelector: 'li a',
            onShow: function(toggle, container, idx) {
                toggle.addClass('tab-selected');
                //container.effect('opacity').start(0, 1); // 1) first start the effect
                container.setStyle('display', ''); // 2) then show the element, to prevent flickering
            },
            getContent: function(el) {
                var content, rel = el.getAttribute('rel');
                oUpload.getElements('div.tab-container')
                 .each(function(el) {
                    if (el.getAttribute('rel') == rel) content = el;
                });
                return content;
            }
        });
        
        var oSelect = oUpload.getElementsByTagName('select')[0];
        var aFilterSelects = ZMG.Shared.get('filterSelects');
        if (oSelect && aFilterSelects)
            aFilterSelects.include(oSelect);
        
        var oForm = $('zmg_fancyupload');
        oForm.action = ZMG.CONST.req_uri + "&view=admin:mediaupload:store&"
          + ZMG.CONST.sessionname + "=" + ZMG.CONST.sessionid;
        
        var oUploader = new FancyUpload($('zmg_fancyupload_filedata'), {
            swf: ZMG.CONST.base_path + '/var/www/templates/admin/other/uploader.swf',
            multiple: true,
            queued: true,
            container: $('zmg_fancyupload'),
            queueList: 'zmg_fancyupload_queue',
            instantStart: false,
            allowDuplicates: true,
            types: {'All Files (*.*)': '*.*'},
            onAllComplete: ZMG.EventHandlers.onMmUploadCompleted
        });
        ZMG.Shared.register('uploader', oUploader, null, 0);
    },
    
    updateMediumForm: function(oData) {
        var oForm = ZMG.Shared.cacheElement('zmg_form_edit_medium');
        oForm.reset();
        
        var oImg = ZMG.Shared.cacheElement('zmg_edit_medium_thumbnail');
        oImg.src = oData.url_thumb;
        oImg.onload = ZMG.EventHandlers.onMediumCorrectPanel;
        oForm.elements['zmg_edit_mid'].value = oData.mid
        ZMG.Shared.cacheElement('zmg_edit_filename').innerHTML = oData.filename;
        oForm.elements['zmg_edit_name'].value = oData.name;
        oForm.elements['zmg_edit_keywords'].value = oData.keywords;
        //ZMG.Shared.cacheElement('zmg_edit_gimg');
        //ZMG.Shared.cacheElement('zmg_edit_pimg');
        var oPublish = ZMG.Shared.cacheElement('zmg_edit_published');
        oPublish.checked = (oData.published);
        FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
        
        oForm.elements['zmg_edit_descr'].value = oData.descr;
        
        this.setACLSelect(oData.uid, 'zmg_edit_acl_gid');
    },
    
    setACLSelect: function(uid, mSelect) {
        uid = parseInt(uid) || 0; //default: public access '0'
        var oSelect = (typeof mSelect == "string") ? $(mSelect) : mSelect;

        for (var i = 0; i < oSelect.options.length; i++)
            oSelect.options[i].selected = (parseInt(oSelect.options[i].value) === uid);
    },
    
    buildSettingsTabs: function() {
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
        ZMG.Shared.cacheElement('zmg_view_content').adopt(oSettings);
        
        //second, initialize the SimpleTabs widget
        var oSettingsTabs = new SimpleTabs(oForm, {
            entrySelector: 'li a',
            ajaxOptions: {
                method: 'get'
            },
            onShow: function(toggle, container, idx) {
                toggle.addClass('tab-selected');
                container.setStyle('display', ''); // 2) then show the element, to prevent flickering
                
                var entry = oSettingsTabs.entries[idx];
                ZMG.ServerEvents.onActivateView('zmg_view_settings');
                if (!entry.loaded && entry.data)
                    ZMG.ClientEvents.onViewSelect(entry.data[0], entry.data[1]);
                ZMG.ClientEvents.onSelectSettingsTab(entry.data[0]);

                ZMG.GUI.updateProportions();
            }
        });

        this.updateProportions();

        return ZMG.Shared.register('settingsTabs', oSettingsTabs, null, 0);
    }
};

})();
