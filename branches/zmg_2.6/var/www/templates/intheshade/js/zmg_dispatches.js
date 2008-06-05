if (!window.ZMG) window.ZMG = {};

ZMG.Dispatches = {
    lastRequest : null,
    requestQueue: null,
    
    stdDispatch: function(options) {
        ZMG.ClientEvents.onShowLoader();
        new XHR({
            async: options.async || true,
            onSuccess: options.onSuccess || ZMG.ServerEvents.onDispatchResult,
            onFailure: options.onFailure || ZMG.ServerEvents.onError
        }).send(options.url, options.data || '');
    },
    
    getI18n: function() {
        return this.stdDispatch({
            url: ZMG.CONST.req_uri + "&view=zmg:get:i18n",
            onSuccess: function(text) {
                Json.evaluate(text);
                ZMG.ClientEvents.onI18nAvailable();
            }
        });
    },
    
    selectView: function(view, forcetype) {
        view = view || ZMG.CONST.active_view;
        if (!view) return false;
        
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
                onSuccess: ZMG.ServerEvents.onView,
                onFailure: ZMG.ServerEvents.onError
            });
        } else {
            //prevent duplicate requests:
            if (this.lastRequest == vars.view)
                valid = false;
        }
        
        if (valid) {
            this.lastRequest = vars.view;
            if (view.indexOf('metadata') == -1)
                Shadowbox.close();
            ZMG.ClientEvents.onShowLoader();
            
            var self = this;
            var f = function() {
                self.requestQueue.request(vars || '');
            };
            // allowing a small delay for the browser to draw the loader-icon.
            window.setTimeout(f.bind(this), 20);
        }
        
        return valid;
    }
};
