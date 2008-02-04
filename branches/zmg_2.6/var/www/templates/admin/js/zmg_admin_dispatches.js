if (!window.ZMG) window.ZMG = {};

ZMG.Dispatches = {
    stdDispatch:  function(options) {
        var server = ZMG.Admin.Events.Server;
        var client = ZMG.Admin.Events.Client;
        var f = function() {
            new XHR({
                onSuccess: options.onSuccess || server.ondispatchresult.bind(ZMG.Admin.Events),
                onFailure: options.onFailure || server.onerror.bind(ZMG.Admin.Events)
            }).send(options.url, options.data || '');
        };
        client.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
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
    saveGallery:  function(sData) {
        return this.stdDispatch({
            url: ZMG.CONST.req_uri + "&view=admin:galleryedit:store",
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
                ZMG.CONST.mediumcount = isNaN(num) ? 0 : num;
            }
        }).send(ZMG.CONST.req_uri + "&view=admin:update:mediacount:" + gid, '');
    }
};
