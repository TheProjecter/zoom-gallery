if (!window.ZMG) window.ZMG = {};

ZMG.ServerEvents = (function() {
    function onView(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        //console.log('Server#onview: ', view);
        ZMG.Shared.register('lastRequest', null);
        if (view == "admin:gallerymanager") {
            onGalleryManager(text);
        } else if (view.indexOf('admin:gallerymanager:get:') > -1) {
            o = Json.evaluate(text);
            onLoadGalleryData(o);
            isJSON = true;
        } else if (view == "admin:mediamanager") {
            onMediaManager(text);
        } else if (view.indexOf('admin:mediamanager:get:') > -1) {
            o = Json.evaluate(text);
            onLoadMediumData(o);
            isJSON = true;
        } else if (view == "admin:mediamanager:upload") {
            onMediaManagerupload(text);
        } else if (view.indexOf('admin:toolbar:') > -1) {
            o = Json.evaluate(text);
            onToolbar(o);
            isJSON = true;
        } else if (view == "admin:settings:overview") {
            o = Json.evaluate(text);
            onSettingsOverview(o);
            isJSON = true;
        } else if (view == "admin:settings:meta"
          || view == "admin:settings:locale"
          || view == "admin:settings:filesystem"
          || view == "admin:settings:layout"
          || view == "admin:settings:app"
          || view == "admin:settings:plugins"
          || view == "admin:settings:info") {
            key = onGetSettingsKey(view);
            onLoadSettingsTab(key, text, view);
        } else if (view != "ping" && text) {
            o = Json.evaluate(text);
            isJSON = true;
        }
        ZMG.ClientEvents.onHideLoader();
        
        if (isJSON) {
            //check if there are any messages we need to display:
            if (o.messagecenter && o.messagecenter.messages.length) {
                for (var i = 0; i < o.messagecenter.messages.length; i++)
                    ZMG.ClientEvents.onShowMessage(o.messagecenter.messages[i].title,
                      o.messagecenter.messages[i].descr);
            }
        }
    };
    
    function onDispatchResult(text, xml) {
        var o = Json.evaluate(text);
        if (o && o.action) {
            
            if (o.action == "settings_store") {
                
            } else if (o.action == "mediumedit_store") {
                
            } else if (o.action == "galleryedit_store") {
                //update the tree structure
                var menu = ZMG.Shared.get('menuTree');
                var node = menu.get('admin:gallerymanager'); 
                node.open = false;
                node.load(node.data.load);
            }
            for (var i = 0; i < o.messagecenter.messages.length; i++) {
                ZMG.ClientEvents.onShowMessage(o.messagecenter.messages[i].title,
                  o.messagecenter.messages[i].descr);
            }
        }
        ZMG.ClientEvents.onHideLoader();
    };
    
    function onGalleryManager(html) {
        if (!ZMG.Shared.cacheElement('zmg_view_gm')) {
            var oGM = new Element('div', { id: 'zmg_view_gm' });
            ZMG.Shared.cacheElement('zmg_view_content').adopt(oGM);
            
            oGM.innerHTML = html;
            
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
        }
        onActivateView('zmg_view_gm');
    };
    
    function onLoadGalleryData(node) {
        if (node.result == ZMG.CONST.result_ok) {
            onActivateView('zmg_view_gm');
            
            var data = node.data.gallery;
            var oForm = ZMG.Shared.cacheElement('zmg_form_edit_gallery');
            oForm.reset();
            
            var oImg = ZMG.Shared.cacheElement('zmg_edit_gallery_thumbnail');
            if (data.cover_img)
                oImg.src = data.cover_img;
            else
                oImg.src = ZMG.CONST.res_path + "/images/mimetypes/unknown.png";
            
            oForm.elements['zmg_edit_gallery_gid'].value = data.gid || 'new';
            //ZMG.Shared.cacheElement('zmg_edit_medium_thumbnail').src = data.url;
            oForm.elements['zmg_edit_gallery_name'].value = data.name;
            oForm.elements['zmg_edit_gallery_dir'].value = data.dir;
            oForm.elements['zmg_edit_gallery_keywords'].value = data.keywords;
            //ZMG.Shared.cacheElement('zmg_edit_gallery_gimg');
            //ZMG.Shared.cacheElement('zmg_edit_gallery_pimg');
            var oHideNM = ZMG.Shared.cacheElement('zmg_edit_gallery_hidenm');
            oHideNM.checked = (data.hide_msg);
            FancyForm[(oHideNM.checked ? 'select' : 'deselect')](oHideNM.parentNode);
            
            var oPublish = ZMG.Shared.cacheElement('zmg_edit_gallery_published');
            oPublish.checked = (data.published);
            FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
            
            var oShared = ZMG.Shared.cacheElement('zmg_edit_gallery_shared');
            oShared.checked = (data.shared);
            FancyForm[(oShared.checked ? 'select' : 'deselect')](oShared.parentNode);
            
            oForm.elements['zmg_edit_gallery_descr'].value = data.descr;
        }
    };
    
    function onMediaManager(html) {
        if (!ZMG.Shared.cacheElement('zmg_view_mm')) {
            var oMM = new Element('div', { id: 'zmg_view_mm' });
            ZMG.Shared.cacheElement('zmg_view_content').adopt(oMM);
            oMM.innerHTML = html;
            FancyForm.start($A(oMM.getElementsByTagName('input')));
            var oSelect = oMM.getElementsByTagName('select')[0];
            
            var aFilterSelects = ZMG.Shared.get('filterSelects');
            if (oSelect && aFilterSelects)
                aFilterSelects.include(oSelect);
            
            var el    = $('zmg_mm_lgrid');
            var nav   = el.getElement('.lgrid-nav');
            var pager = $('lgrid-nav-pager');
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
            ZMG.Shared.register('liveGrid', oLiveGrid);
            
            //setup the tabs for Medium property editing:
            new SimpleTabs($('zmg_edit_medium_tabs'), {
                entrySelector: 'li a',
                onShow: function(toggle, container, idx) {
                    toggle.addClass('tab-selected');
                    container.effect('opacity').start(0, 1); // 1) first start the effect
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
            //paging[2].addEvent('click', oLiveGrid.scrollBy.bind(oLiveGrid, [-1]));
            //paging[3].addEvent('click', oLiveGrid.scrollBy.bind(oLiveGrid, [1]));
            paging[2].addEvent('click', oLiveGrid.scrollByPage.bind(oLiveGrid, [1]));
            paging[3].addEvent('click', oLiveGrid.scrollComplete.bind(oLiveGrid, [1]));
            
            pager.addEvent('change', ZMG.ClientEvents.onLiveGridPager.bind(oLiveGrid, [pager]));
        } else if (ZMG.Shared.get('liveGrid')) {
            var lGrid = ZMG.Shared.get('liveGrid');
            setTimeout(function(){
                lGrid.refresh();
            }, 100); //fix for 'NaN' bug in the liveGrid, when the control is hidden or overlapped by another layer
        }
        ZMG.ClientEvents.onMmSetFilterSelects();
        onActivateView('zmg_view_mm');
    };
    
    function onMediaManagerupload(html) {
        if (!ZMG.Shared.cacheElement('zmg_view_mm_upload')) {
            var oUpload = new Element('div', { id: 'zmg_view_mm_upload' });
            ZMG.Shared.cacheElement('zmg_view_content').adopt(oUpload);
            oUpload.innerHTML = html;
            
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
            ZMG.Shared.register('uploader', oUploader);
        }
        ZMG.ClientEvents.onMmSetFilterSelects();
        onActivateView('zmg_view_mm_upload');
    };
    
    function onLoadMediumData(node) {
        if (node.result == ZMG.CONST.result_ok) {
            var data = node.data.medium;
            var oForm = ZMG.Shared.cacheElement('zmg_form_edit_medium');
            oForm.reset();
            
            var oImg = ZMG.Shared.cacheElement('zmg_edit_medium_thumbnail');
            oImg.src = data.url;
            oImg.onload = ZMG.EventHandlers.onMediumCorrectPanel;
            ZMG.Shared.cacheElement('zmg_edit_filename').innerHTML = data.filename;
            ZMG.Shared.cacheElement('zmg_edit_name').value = data.name;
            ZMG.Shared.cacheElement('zmg_edit_keywords').value = data.keywords;
            //ZMG.Shared.cacheElement('zmg_edit_gimg');
            //ZMG.Shared.cacheElement('zmg_edit_pimg');
            var oPublish = ZMG.Shared.cacheElement('zmg_edit_published');
            oPublish.checked = (data.published);
            FancyForm[(oPublish.checked ? 'select' : 'deselect')](oPublish.parentNode);
            
            ZMG.Shared.cacheElement('zmg_edit_descr').value = data.descr;
        }
    };
    
    function onToolbar(node) {
        var name = node.toolbar.shift();
        var oToolbar = ZMG.Shared.get('toolbar');
        if (!oToolbar) return;
        oToolbar.create(name, node.toolbar).show(name);
    };
    
    var settingsMap = [];
    
    function onSettingsOverview(node) {
        if (!ZMG.Shared.cacheElement('zmg_view_settings')) {
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
                    onActivateView('zmg_view_settings');
                    if (!entry.loaded && entry.data)
                        ZMG.ClientEvents.onViewSelect(entry.data[0], entry.data[1]);
                    ZMG.ClientEvents.onSelectSettingsTab(entry.data[0]);
                }
            });
            ZMG.Shared.register('settingsTabs', oSettingsTabs);
            
            for (var i in node.tabs) {
                oSettingsTabs.addTab(node.tabs[i].name, node.tabs[i].title,
                  node.tabs[i].url, node.tabs[i].data);
                settingsMap.push(node.tabs[i].data[0]);
            }
            ZMG.Shared.register('requestingTabs', false);
        }
        onActivateView('zmg_view_settings');
        ZMG.ClientEvents.onViewSelect('admin:settings:meta', 'html');
    };
    
    function onLoadSettingsTab(idx, html, view) {
        var oSettingsTabs = ZMG.Shared.get('settingsTabs');
        if (oSettingsTabs && !oSettingsTabs.entries[idx].loaded && html) {
            oSettingsTabs.entries[idx].container.innerHTML = html;
            oSettingsTabs.entries[idx].loaded = true;
            //turn checkboxes and radiobuttons into fancy looking elements
            FancyForm.start($A(oSettingsTabs.entries[idx].container
              .getElementsByTagName('input')));
            if (view) {
                if (view == "admin:settings:plugins") {
                    var oForm = ZMG.Shared.cacheElement('zmg_settings_form');
                    var conv_select = oForm.elements['zmg_plugins_toolbox_general_conversiontool'];
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
                        ZMG.ClientEvents.onViewSelect('admin:settings:plugins:autodetect:'
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
        onActivateView('zmg_view_settings');
        oSettingsTabs.select(idx);
    };
    
    function onActivateView(el) {
        if (!ZMG.Shared.cacheElement(el)) return;
        var oParent = ZMG.Shared.cacheElement('zmg_view_content');
        for (var i = 0; i < oParent.childNodes.length; i++)
            if (oParent.childNodes[i].nodeType == 1)
                oParent.childNodes[i].setStyle('display', 'none');
        
        
        var oActiveView = ZMG.Shared.cacheElement(el);
        oActiveView.setStyle('display', '');
        ZMG.Shared.register('activeView', oActiveView);
        
        window.setTimeout("ZMG.ClientEvents.onViewSelect('admin:toolbar:"
          + el + "');", 100);
    };
    
    function onGetSettingsKey(view) {
        for (var i = 0; i < settingsMap.length; i++)
            if (settingsMap[i] == view)
                return i;
        return 0;
    };
    
    function onError() {
        //console.dir(arguments);
    }
    
    return {
        onDispatchResult: onDispatchResult,
        onError: onError,
        onGetSettingsKey: onGetSettingsKey,
        onLoadSettingsTab: onLoadSettingsTab,
        onView: onView
    };
})();
