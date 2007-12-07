if (!window.ZMG) window.ZMG = {};

ZMG.Admin = {
    nodeContainer: null,
    nodeMenuTree : null,
    nodeContent  : null,
    menuTree     : null,
    Events       : null,
    
    start: function() {
        ZMG.Admin.nodeContainer = $('zmg_admin_cont');
        ZMG.Admin.nodeLoader    = $('zmg_admin_loader');
        ZMG.Admin.nodeMenuTree  = $('zmg_menu_tree');
        ZMG.Admin.nodeContent   = $('zmg_view_content');
        ZMG.Admin.Events = new ZMG.Events();
        //set correct dimensions of the admin content
        ZMG.Admin.Events.Client.onwindowresize();
        //load the tree
        ZMG.Admin.Events.Client.onloadnavigation();
        //set the initial view
        ZMG.Admin.Events.Client.onviewselect();
    }
};

window.addEvent('domready', ZMG.Admin.start); 