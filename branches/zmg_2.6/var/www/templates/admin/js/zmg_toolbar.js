ZMG.Toolbar = new Class({
    initialize: function() {
        this.cache = {};
        
        this.mode = (ZMG.CONST.is_admin) ? 0 : 1;
        this.buttonSource = null;
        this.spacerSource = null;
        
        if (this.mode == 0) {
            this.node = $(ZMG.CONST.toolbar_node);
            this.classButton = ZMG.CONST.toolbar_buttonclass;
        } else {
            //TODO: create our own toolbar node
            this.classButton = "zmg_tb_button";
        }
        this.selectSource();
    },
    selectSource: function() {
        if (this.mode == 0) {
            this.buttonSource = this.node.getElement('.' + this.classButton);
            this.buttonSource.setStyle('display', 'none');
            //no spacer yet...
        }
    },
    create: function(name, buttons) {
        if (name == "clear") return;
        if (this.cache[name]) return this.show(name);
        
        this.clear();
        
        this.cache[name] = {
            nodes: []
        };
        var self = this;
        buttons.each(function(button) {
            var node = self.buttonSource.clone();
            node.id = "zmg_btn_" + button.id;
            
            var anchor = node.getElement('a'); 
            anchor.href      = "javascript:void(0);";
            anchor.onclick   = ZMG.Admin.Events.Client['on' + button.id + 'click']
              .bindWithEvent(ZMG.Admin.Events.Client);
            anchor.innerHTML = ['<span class="zmg_tb_icon zmg_tb_icon_', button.id, '" title="',
              button.title, '"></span>', button.title].join('');
            node.obj = button;
            
            if (button.disabled) {
                node.addClass('zmg_tb_disabled');
                anchor._onclick = anchor.onclick;
                anchor.onclick  = null;
            }
            
            self.buttonSource.getParent().appendChild(node);
            
            self.cache[name].nodes.push(node);
        });
    },
    get: function(name) {
        for (var i in this.cache) {
            if (i == name)
                return this.cache[i];
        }
        return null;
    },
    show: function(name) {
        var hideAll = (name == "clear");
        
        if (!this.cache[name] && !hideAll)
            return;
        
        for (var i in this.cache) {
            if (i != name || hideAll)
                this.cache[i].nodes.each(function(node) {
                    node.setStyle('display', 'none');
                });
        }
        if (!hideAll) {
            this.cache[name].nodes.each(function(node) {
                node.setStyle('display', '');
            });
        }
    },
    clear: function() {
        this.show('clear');
    },
    disable: function(toolbar, buttons) {
        if (!buttons.length) return;
        
        var oTb = this.get(toolbar);
        if (!oTb) return;
        for (var i = 0; i < oTb.nodes.length; i++) {
            var oButton = oTb.nodes[i].obj;
            if (oButton && !oButton.disabled) {
                buttons.each(function(id) {
                    if (oButton.id == id) {
                        var anchor = oTb.nodes[i].getElement('a');
                        oTb.nodes[i].addClass('zmg_tb_disabled');
                        anchor._onclick  = anchor.onclick;
                        anchor.onclick   = null;
                        oButton.disabled = true;
                    }
                });
            }
        }
    },
    enable: function(toolbar, buttons) {
        if (!buttons.length) return;
        
        var oTb = this.get(toolbar);
        if (!oTb) return;
        for (var i = 0; i < oTb.nodes.length; i++) {
            var oButton = oTb.nodes[i].obj;
            if (oButton && oButton.disabled) {
                buttons.each(function(id) {
                    if (oButton.id == id) {
                        var anchor = oTb.nodes[i].getElement('a');
                        oTb.nodes[i].removeClass('zmg_tb_disabled');
                        anchor.onclick   = anchor._onclick;
                        anchor._onclick  = null;
                        oButton.disabled = false;
                    }
                });
            }
        }
    }
});
