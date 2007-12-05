var ZMG_Admin = {
    nodeContainer: null,
    nodeMenuTree : null,
    nodeContent  : null,
    menuTree     : null,
    
    start: function() {
        ZMG_Admin.nodeContainer = $('zmg_admin_cont');
        ZMG_Admin.nodeMenuTree  = $('zmg_menu_tree');
        ZMG_Admin.nodeContent   = $('zmg_view_content');
        //load the tree
        ZMG_Admin.getNavigation();
        //set the initial view
        
    },
    getNavigation: function(res) {
        ZMG_Admin.menuTree = new MooTreeControl({
            div     : ZMG_Admin.nodeMenuTree,
            mode    : 'files',
            grid    : true,
            theme   : ZMG_CONST.res_path + '/images/mootree.gif',
            loader  : {
                icon  : ZMG_CONST.res_path + '/images/mootree_loader.gif',
                text  : 'Loading...',
                color :'#a0a0a0'
            },
            onSelect: function(node, state) {
                ZMG_Admin.nodeContent.innerHTML += "<b>Tree Event:</b> " + node.id + " " + (state ? 'selected' : 'deselected') + "<br />";
            },
        }, {
            text: 'Menu',
            open: true
        });
        
        ZMG_Admin.menuTree.root.load(ZMG_CONST.req_uri + '&view=admin:treemenu');
    }
};

window.addEvent('domready', ZMG_Admin.start); 