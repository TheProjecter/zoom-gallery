if (!window.ZMG) window.ZMG = {};

ZMG.ServerEvents = (function() {
    function onView(text, xml, data, resp) {
        var key, view = data.view, o, isJSON = false;
        console.log('Server#onview: ', view);
        ZMG.Dispatches.lastRequest = null;
        
        //TODO
        if (view == "admin:gallerymanager") {
            onGalleryManager(text);
        }
    }
    
    function onMediaList(text, xml) {
        ZMG.ClientEvents.onHideLoader();
        
        var o = Json.evaluate(text);

        if (o.result == ZMG.CONST.result_ok) {
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
        }
        
        //ZMG.ImageFlow.refresh(true);
    }
    
    function onError() {
        //TODO
    }
    
    //publish to the world:
    return {
        onView: onView,
        onMediaList: onMediaList,
        onError: onError
    };
})();
