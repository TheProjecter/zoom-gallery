if (!window.ZMG) window.ZMG = {};

ZMG.Events = Class({
    initialize: function() {
        this.Server = new ZMG.Events.Server();
        this.Client = new ZMG.Events.Client();
    }
});

ZMG.Events.Server = Class({
    initialize: function() {
        this.settingsTabs = null;
    },
    onview: function(text, xml, data, resp) {
        var view = data.view;
        if (view == "admin:settings:overview") {
            var o = Json.evaluate(text);
            this.Server.onsettingsoverview(o);
        } else if (view == "admin:settings:meta") {
            this.Server.onsettingsmeta(text);
        } else if (view == "admin:settings:locale") {
            this.Server.onsettingslocale(text);
        } else if (view == "admin:settings:filesystem") {
            this.Server.onsettingsfilesystem(text);
        } else if (view == "admin:settings:layout") {
            this.Server.onsettingslayout(text);
        } else if (view == "admin:settings:app") {
            this.Server.onsettingsapp(text);
        } else if (view == "admin:settings:info") {
            this.Server.onsettingsinfo(text);
        }
        ZMG.Admin.Events.Client.onhideloader();
    },
    onsettingsoverview: function(node) {
        if (this.settingsTabs) return;
        //first, initialize the SimpleTabs widget
        this.settingsTabs = new SimpleTabs(ZMG.Admin.nodeContent, {
            entrySelector: 'li a',
            ajaxOptions: {
                method: 'get'
            },
            onShow: function(toggle, container, idx) {
                toggle.addClass('tab-selected');
                container.effect('opacity').start(0, 1); // 1) first start the effect
                container.setStyle('display', ''); // 2) then show the element, to prevent flickering
                
                var klass = ZMG.Admin.Events;
                var entry = klass.Server.settingsTabs.entries[idx];
                if (!entry.loaded && entry.data) {
                    klass.Client.onviewselect(entry.data[0], entry.data[1]);
                }
            }
        });
        for (var i in node.tabs) {
            this.settingsTabs.addTab(node.tabs[i].name, node.tabs[i].title,
              node.tabs[i].url, node.tabs[i].data);
        }
        ZMG.Admin.Events.Client.requestingTabs = false;
    },
    onsettingsmeta: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(0, html);
    },
    onsettingslocale: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(1, html);
    },
    onsettingsfilesystem: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(2, html);
    },
    onsettingslayout: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(3, html);
    },
    onsettingsapp: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(4, html);
    },
    onsettingsinfo: function(html) {
        ZMG.Admin.Events.Client.onloadsettingstab(5, html);
    },
    onerror: function() {
        console.dir(arguments);
    }
});

ZMG.Events.Client = Class({
    initialize: function() {
        this.requestQueue = null;
        this.menuTree = null;
        this.requestingTabs = false;
        
        window.addEvent('resize', this.onwindowresize.bind(this));
    },
    onloadnavigation: function() {
        this.menuTree = new MooTreeControl({
            div     : ZMG.Admin.nodeMenuTree,
            mode    : 'files',
            grid    : true,
            theme   : ZMG.CONST.res_path + '/images/mootree.gif',
            loader  : {
                icon  : ZMG.CONST.res_path + '/images/spinner_small.gif',
                text  : 'Loading...',
                color : '#a0a0a0'
            },
            onSelect: function(node, state) {
                if (state) {
                    var forcetype = "";
                    if (node.data.extra && node.data.extra.forcetype)
                        forcetype = node.data.extra.forcetype;
                    ZMG.Admin.Events.Client.onviewselect(node.id, forcetype);
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
        
        this.menuTree.root.load(ZMG.CONST.req_uri + '&view=admin:treemenu');
    },
    onloadsettingstab: function(idx, html) {
        var tabs = ZMG.Admin.Events.Server.settingsTabs;
        if (!tabs.entries[idx].loaded) {
            tabs.entries[idx].container.innerHTML = html;
            tabs.entries[idx].loaded = true;
            //turn checkboxes and radiobuttons into fancy looking elements
            FancyForm.start();
        }
        tabs.select(idx);
    },
    onviewselect: function(view, forcetype) {
        if (!ZMG.Admin.Events.Server.settingsTabs && !this.requestingTabs) {
            this.requestingTabs = true;
            this.onviewselect('admin:settings:overview');
        }
        
        vars = {
            'view': (view || ZMG.CONST.active_view) 
        }
        if (forcetype)
            vars.forcetype = forcetype;
            
        if (!this.requestQueue) {
            this.requestQueue = new AjaxQueue(ZMG.CONST.req_uri, {
                ajaxOptions: {
                    method: 'get'
                },
                onSuccess: ZMG.Admin.Events.Server.onview.bind(ZMG.Admin.Events),
                onFailure: ZMG.Admin.Events.Server.onerror.bind(ZMG.Admin.Events),
            });
        }
        
        var self = this;
        var f = function() {
            self.requestQueue.request(vars || '');
        };
        this.onshowloader();
        window.setTimeout(f.bind(this), 20); // allowing a small delay for the browser to draw the loader-icon.
    },
    onshowloader: function() {
        ZMG.Admin.nodeLoader.setStyles({
            'display': '',
            'height' : ZMG.Admin.nodeContainer.parentNode.offsetHeight
        });
    },
    onhideloader: function() {
        ZMG.Admin.nodeLoader.setStyle('display', 'none');
    },
    onwindowresize: function() {
        var width = ZMG.Admin.nodeContainer.offsetWidth;
        ZMG.Admin.nodeContent.style.width = (width - 358) + "px";
    },
    onerror: function() {
        console.dir(arguments);
    }
});
