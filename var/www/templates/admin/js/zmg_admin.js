if (!window.ZMG) window.ZMG = {};

ZMG.Admin = {
    menuTree     : null,
    Events       : null,
    
    start: function() {
        ZMG.Shared.cacheElement('zmg_admin_cont');
        ZMG.Shared.cacheElement('zmg_admin_loader');
        ZMG.Shared.cacheElement('zmg_tree_body');
        ZMG.Shared.cacheElement('zmg_view_content');
        var mc = ZMG.Shared.cacheElement('zmg_admin_messagecenter');
        document.body.appendChild(mc);

        ZMG.ClientEvents.onStart();
    }
};

window.addEvent('domready', ZMG.Admin.start);
