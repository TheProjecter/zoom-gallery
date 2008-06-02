if (!window.ZMG) window.ZMG = {};

ZMG.ServerEvents = (function() {
    function onView(text, xml, data, resp) {
        var view = data.view, o, isJSON = false;
        console.log('Server#onview: ', view);
        ZMG.Dispatches.lastRequest = null;
        
        ZMG.CONST.active_view = view || "";
        if (view == "gallery:show:home") {
            o = Json.evaluate(text);
            onGalleryList(o);
            isJSON = true;
        } else if (view.indexOf('gallery:show:') > -1) {
            o = Json.evaluate(text);
            onGalleryContent(o);
            isJSON = true;
        } else if (view.indexOf('medium:show:') > -1) {
            o = Json.evaluate(text);
            onMediumContent(o);
            isJSON = true;
        } else if (view == "zmg:get:i18n") {
            Json.evaluate(text);
            isJSON = true;
        }
        
        ZMG.ClientEvents.onHideLoader();
        
        if (isJSON) { //TODO: needs to be implemented still!
            //check if there are any messages we need to display:
            if (o.messagecenter && o.messagecenter.messages.length) {
                for (var i = 0; i < o.messagecenter.messages.length; i++)
                    ZMG.ClientEvents.onShowMessage(o.messagecenter.messages[i].title,
                      o.messagecenter.messages[i].descr);
            }
        }
    };
    
    function onGalleryList(o) {
        if (o.result !== ZMG.CONST.result_ok) return;
        
        var gallery, oGalleries = ZMG.Shared.cacheElement('zmg_gallery_list');
        
        var i, out = [];
        for (i = 0; i < o.data.length; i++) {
            gallery = ZMG.Shared.register('gallery:' + o.data[i].gallery.gid, o.data[i].gallery); //keep a cache of galleries

            out.push(ZMG.GUI.buildGalleryDiv(gallery));
        }
        
        oGalleries.innerHTML = out.join('');
        
        //grab references and attach event handlers to the galleries
        for (i = 0; i < oGalleries.childNodes.length; i++)
            if (oGalleries.childNodes[i].nodeName == "A"
              &&  oGalleries.childNodes[i].id.indexOf('zmg_gallery_') > -1) {
                oGalleries.childNodes[i].onclick     = ZMG.EventHandlers.onGalleryClick;
                oGalleries.childNodes[i].onmouseover = ZMG.EventHandlers.onGalleryEnter;
                oGalleries.childNodes[i].onmouseout  = ZMG.EventHandlers.onGalleryLeave;
            }

        ZMG.ClientEvents.onActivateView('zmg_gallery_list');
    };
    
    function onGalleryContent(o) {
        if (o.result !== ZMG.CONST.result_ok) return;
        
        oList = ZMG.Shared.cacheElement('zmg_gallery_content');
        oList.innerHTML = "";
        
        var i, obj, out = [];
        var iGid = 0;
        
        var oGallery = o.data.shift().gallery;
        ZMG.Shared.register('gallery:' + oGallery.gid, oGallery); //keep this Gallery in cache!
        
        for (i = 0; i < o.data.length; i++) {
            var isGallery = o.data[i].gallery ? true : false;
            obj = isGallery ? o.data[i].gallery : o.data[i].medium;

            if (isGallery)
                obj = ZMG.Shared.register('gallery:' + obj.gid, obj); //keep a cache of galleries
            else {
                if (!iGid) iGid = obj.gid;
                obj = ZMG.Shared.register('medium:' + obj.mid, obj, iGid); //keep a cache of media
            }

            out.push(isGallery ? ZMG.GUI.buildGalleryDiv(obj) : ZMG.GUI.buildMediumDiv(obj));
        }
        oList.innerHTML = out.join('');
        
        //grab references and attach event handlers to the galleries
        for (i = 0; i < oList.childNodes.length; i++)
            if (oList.childNodes[i].nodeName == "DIV"
              &&  oList.childNodes[i].className.indexOf('zmg_medium_thumb_') > -1) {
                //oList.childNodes[i].onclick     = ZMG.EventHandlers.onMediumClick;
                oList.childNodes[i].onmouseover = ZMG.EventHandlers.onMediumEnter;
                oList.childNodes[i].onmouseout  = ZMG.EventHandlers.onMediumLeave;
            }
        
        if (oGallery && o.data.length > 0) {
            Shadowbox.setup($$('a.zmg_medium_thumb'), {
                gallery: oGallery.name
            });
        }
        
        ZMG.ClientEvents.onActivateView('zmg_gallery_content');
    };
    
    function onMediumContent(o) {
        if (o.result !== ZMG.CONST.result_ok) return;
        
        var medium = o.data.medium;
        var gallery = ZMG.Shared.get('gallery:' + medium.gid);
        Shadowbox.open({
            title:      medium.name,
            type:       'img',
            content:    medium.url_view,
            gallery:    gallery ? gallery.name : null
        });
    };
    
    function onError() {
        //TODO
    };
    
    //publish to the world:
    return {
        onView: onView,
        onError: onError
    };
})();
