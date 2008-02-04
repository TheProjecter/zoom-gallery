if (!window.ZMG) window.ZMG = {};

ZMG.Dispatches = {
    lastRequest : null,
    requestQueue: null,
    
    stdDispatch: function(options) {
        var server = ZMG.Events.Server;
        var client = ZMG.Events.Client;
        var f = function() {
            new XHR({
                onSuccess: options.onSuccess || server.ondispatchresult.bind(server),
                onFailure: options.onFailure || server.onerror.bind(server)
            }).send(options.url, options.data || '');
        };
        client.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
    },
    getGalleries: function(pos, parentGid) {
        if (isNaN(pos) || isNaN(parentGid)) return;
        
        this.stdDispatch({
            url: ZMG.CONST.req_uri + '&view=gallery:get&pos=' + pos + '&sub=' + parentGid,
            onSuccess: ZMG.Events.Server.ongallerylist.bind(ZMG.Events.Server)
        });
    },
    selectView: function(view, forcetype) {
        var server = ZMG.Events.Server;
        var client = ZMG.Events.Client;
        
        view = view || ZMG.CONST.active_view;
        if (!view) return;
        
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
                onSuccess: server.onview.bind(server),
                onFailure: server.onerror.bind(server),
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
            client.onshowloader();
            // allowing a small delay for the browser to draw the loader-icon.
            window.setTimeout(f.bind(this), 20);
        }
        
        return valid;
    }
};
