if (!window.ZMG) window.ZMG = {};

ZMG.ServerEvents = (function() {
    function onView(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        console.log('Server#onview: ', view);
        ZMG.Dispatches.lastRequest = null;
        
        if (view == "gallery:show:home") {
            o = Json.evaluate(text);
            onGalleryList(o);
            isJSON = true;
        } else  if (view == "zmg:get:i18n") {
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
        
        var gallery, oGalleries = ZMG.cacheElement('zmg_gallerylist');
        
        var out = [];
        for (var i = 0; i < o.data.length; i++) {
            gallery = ZMG.Shared.register('gallery:' + o.data[i].gallery.gid, o.data[i].gallery); //keep a cache of galleries

            if (!gallery.cover_img)
                gallery.cover_img = ZMG.CONST.res_path + "/images/mimetypes/small/unknown.png";
            out.push('<div id="zmg_gallery_', gallery.gid, '" class="zmg_gallery">\
              <img src="', gallery.cover_img, '" alt="" title=""/>\
              <span class="zmg_gallery_name">',
                gallery.name,
              '</span>\
              <span class="zmg_gallery_descr">',
                gallery.descr,
              '</span>\
            </div>');
        }
        
        oGalleries.innerHTML = out.join('');
        
        //grab references and attach event handlers to the galleries
        for (var i = 0; i < oGalleries.childNodes.length; i++)
            if (oGalleries.childNodes[i].nodeName == "DIV"
              &&  oGalleries.childNodes[i].id.indexOf('zmg_gallery_') > -1) {
                oGalleries.childNodes[i].onclick     = ZMG.EventHandlers.onGalleryClick;
                oGalleries.childNodes[i].onmouseover = ZMG.EventHandlers.onGalleryEnter;
                oGalleries.childNodes[i].onmouseout  = ZMG.EventHandlers.onGalleryLeave;
            }
    };
    
    function onMediaList(o) {
        if (o.result !== ZMG.CONST.result_ok) return;
        
        var medium, el, oImages = ZMG.cacheElement('zmg_mediumlist');
        oImages.innerHTML = "";

        for (var i = 0; i < o.data.length; i++) {
            medium = o.data[i].medium;
            el = new Element('img', {
                src: medium.url,
                longdesc: '#medium:' + medium.mid,
                alt: medium.name,
                'class': 'medium_thumb'
            }).inject(oImages);
        }
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
