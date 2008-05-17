if (!window.ZMG) window.ZMG = {};

(function() {
    ZMG.Dispatches = {
        stdDispatch:  function(options) {
            ZMG.GUI.showLoader();
            window.setTimeout(function() {
                new XHR({
                    onSuccess: options.onSuccess || ZMG.ServerEvents.onDispatchResult,
                    onFailure: options.onFailure || ZMG.ServerEvents.onError
                }).send(options.url, options.data || '');
            }, 20); // allowing a small delay for the browser to draw the loader-icon.
        },
        saveSettings: function(sData) {
            return this.stdDispatch({
                url: ZMG.CONST.req_uri + "&view=admin:settings:store",
                data: sData
            });
        },
        saveMedium:   function(sData) {
            return this.stdDispatch({
                url: ZMG.CONST.req_uri + "&view=admin:mediumedit:store",
                data: sData
            });
        },
        saveMedia:    function(type, sData) {
            return this.stdDispatch({
                url: ZMG.CONST.req_uri + "&view=admin:mediaupload:update:"
                 + ZMG.ClientEvents.getActiveFilter(),
                data: sData
            });
        },
        saveGallery:  function(sData) {
            return this.stdDispatch({
                url: ZMG.CONST.req_uri + "&view=admin:galleryedit:store",
                data: sData
            });
        },
        deleteGallery:  function(sData) {
            return this.stdDispatch({
                url: ZMG.CONST.req_uri + "&view=admin:galleryedit:delete",
                data: sData
            });
        },
        mediaCount:   function(gid) {
            if (isNaN(gid)) gid = 0;
            
            new XHR({
                async: false,
                onSuccess: function(text, xml) {
                    var o = Json.evaluate(text);
                    var num = parseInt(o.result);
                    ZMG.CONST.mediumcount = isNaN(num) ? 1 : num;
                }
            }).send(ZMG.CONST.req_uri + "&view=admin:update:mediacount:" + gid, '');
        }
    };
})();
