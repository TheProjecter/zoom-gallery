if (!window.ZMG) window.ZMG = {};

ZMG.ServerEvents = (function() {
    function onView(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        text = text.trim();
        //console.log('Server#onview: ', view);
        ZMG.Shared.register('lastRequest', null, null, 0);
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
        ZMG.GUI.hideLoader();
        
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
        ZMG.GUI.hideLoader();
    };
    
    var oNewClick;
    
    function onGalleryManager(html) {
        if (!ZMG.Shared.cacheElement('zmg_view_gm'))
            oNewClick = ZMG.GUI.buildGalleryManager(html);
        var oGM = onActivateView('zmg_view_gm');
        $(oGM.firstChild).setStyle('display', 'none');
        if (oNewClick) oNewClick.setStyle('display', '');
    };
    
    function onLoadGalleryData(node) {
        if (node.result == ZMG.CONST.result_ok) {
            if (oNewClick) oNewClick.setStyle('display', 'none');
            
            var oGM = onActivateView('zmg_view_gm');
            oGM.firstChild.setStyle('display', '');
            
            var oData = node.data.gallery;
            ZMG.GUI.updateGalleryForm(oData);
        }
    };
    
    function onMediaManager(html) {
        if (!ZMG.Shared.cacheElement('zmg_view_mm')) {
            ZMG.GUI.buildMediaManager(html);
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
        if (!ZMG.Shared.cacheElement('zmg_view_mm_upload'))
            ZMG.GUI.buildUploadTabs(html);

        ZMG.ClientEvents.onMmSetFilterSelects();
        onActivateView('zmg_view_mm_upload');
    };
    
    function onLoadMediumData(node) {
        if (node.result == ZMG.CONST.result_ok) {
            var oData = node.data.medium;
            ZMG.GUI.updateMediumForm(oData);
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
            var oSettingsTabs = ZMG.GUI.buildSettingsTabs();
            
            for (var i in node.tabs) {
                oSettingsTabs.addTab(node.tabs[i].name, node.tabs[i].title,
                  node.tabs[i].url, node.tabs[i].data);
                settingsMap.push(node.tabs[i].data[0]);
            }
            ZMG.Shared.register('requestingTabs', false, null, 0);
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
                            duration: 150, //animations: be fast, otherwise they get annoying instead of useful
                            onActive: function(toggler, element){
                                toggler.setStyle('color', '#0b55c4');
                            },
                            onBackground: function(toggler, element){
                                toggler.setStyle('color', '#666');
                            }
                        }, $('zmg_plugins_accordion'));
                    }, 20); //timeout needed, to make this work in edge cases too :-S
                } else if(view == "admin:settings:layout") {
                    //first, setup the accordion tab control:
                    window.setTimeout(function() {
                        new Accordion('h3.zmg_accordion_start', 'div.zmg_accordion_start', {
                            opacity: false,
                            duration: 150, //animations: be fast, otherwise they get annoying instead of useful
                            onActive: function(toggler, element){
                                toggler.setStyle('color', '#0b55c4');
                            },
                            onBackground: function(toggler, element){
                                toggler.setStyle('color', '#666');
                            }
                        }, $('zmg_settings_layout'));
                    }, 20); //timeout needed, to make this work in edge cases too :-S
                }
            }
        }
        onActivateView('zmg_view_settings');
        oSettingsTabs.select(idx);
    };
    
    var sActiveView = null;
    
    function onActivateView(el) {
        if (!ZMG.Shared.cacheElement(el)) return;
        var oParent = ZMG.Shared.cacheElement('zmg_view_content');
        for (var i = 0; i < oParent.childNodes.length; i++)
            if (oParent.childNodes[i].nodeType == 1)
                oParent.childNodes[i].setStyle('display', 'none');
        
        sActiveView = el;
        var oActiveView = ZMG.Shared.cacheElement(el);
        oActiveView.setStyle('display', '');
        ZMG.Shared.register('activeView', oActiveView, null, 0);
        
        window.setTimeout("ZMG.ClientEvents.onViewSelect('admin:toolbar:"
          + el + "');", 100);
          
        return oActiveView;
    };
    
    function onGetSettingsKey(view) {
        for (var i = 0; i < settingsMap.length; i++)
            if (settingsMap[i] == view)
                return i;
        return 0;
    };
    
    function onError() {
        console.dir(arguments);
    }
    
    return {
        onDispatchResult: onDispatchResult,
        onError: onError,
        onGetSettingsKey: onGetSettingsKey,
        onLoadSettingsTab: onLoadSettingsTab,
        onActivateView: onActivateView,
        onView: onView
    };
})();
