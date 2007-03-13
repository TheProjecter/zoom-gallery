/**
 * @version $Id:scriptaculous.js 129 2007-02-18 20:29:47Z kevinuru $
 */
var sAgent    = navigator.userAgent.toLowerCase();
var is_ie     = (sAgent.indexOf("msie") != -1);
var is_ie7    = (sAgent.indexOf("msie 7") != -1);
var is_gecko  = !is_ie;
var is_safari = (sAgent.indexOf("safari") != -1);
var is_nav    = (sAgent.indexOf("netscape") != -1);

if (is_ie) {
    try {
        document.execCommand("BackgroundImageCache", false, true);
    } catch(e) {};
}


var Zoom = {
    Version: '1.6.5',
    libs: new Array(),
    folded: new Array(),
    connector: null,
    method: 'post',
    /*charset was already defined CORRECTLY in the prepareAjax function.*/
    /*charset: 'UTF-8',*/
    state: 'new',
    treenode: null,
    treePinned: true,
    treeDrag: null,
    treePos: null,
    treeCaptionPos: null,
    treeObj: null,
    /*req_uri was already defined CORRECTLY in the prepareAjax function. This has issue with localhost and other non-com sites*/
    /*req_uri: window.location.protocol + "//" + window.location.host + window.location.pathname.replace(/\/(administrator\/)?index(.*)\.php$/i, '') + "/components/com_zoom/www/ajaxcallback.php",*/
    site_uri: '',
    uid: '0',
    activeCat: '-1',
    activeSubcat: '0',
    lightboxActive: false,
    votingActive: false,
    votingMedium: null,
    require: function(libraryName) {
        // inserting via DOM fails in Safari 2.0, so brute force approach
        document.write('<script type="text/javascript" src="'+libraryName+'"></script>');
    },
    load: function() {
        if((typeof Prototype=='undefined') || 
           (typeof Element == 'undefined') || 
           (typeof Element.Methods=='undefined') ||
           parseFloat(Prototype.Version.split(".")[0] + "." +
                      Prototype.Version.split(".")[1]) < 1.5)
           throw("script.aculo.us requires the Prototype JavaScript framework >= 1.5.0");
        
        $A(document.getElementsByTagName("script")).findAll( function(s) {
          return (s.src && s.src.match(/scriptaculous\.js(\?.*)?$/))
        }).each( function(s) {
          var path = s.src.replace(/scriptaculous\.js(\?.*)?$/,'');
          var includes = s.src.match(/\?.*load=([a-zA-Z,]*)/);
          (includes ? includes[1] : 'button,effects,dragdrop,mm,tjpzoom').split(',').each(
           function(include) { Zoom.require(path+include+'.js') });
        });
    },
    
    buildMembersButtons:
    function(node, src, dst) {
        new Zoom.Button('members_add', '', node, {
            width: 22,
            height: 22,
            group: 'members_actions',
            classEnter: 'zmg_nav_btn_right_hover',
            classLeave: 'zmg_nav_btn_right',
            actionData: [src, dst],
            onEnter: function(button) {
                overlib('Add selected group or user to list');
            },
            onLeave : function(button) {
                nd();
            },
            onLeftClick: function(button) {
                Zoom.rebuildMembersList(button.getId(), button.options.actionData[0], button.options.actionData[1]);
            }
        });
        new Zoom.Button('members_remove', '', node, {
            width: 22,
            height: 22,
            group: 'members_actions',
            classEnter: 'zmg_nav_btn_left_hover',
            classLeave: 'zmg_nav_btn_left',
            actionData: [src, dst],
            onEnter: function(button) {
                overlib('Remove selected group or user from list');
            },
            onLeave : function(button) {
                nd();
            },
            onLeftClick: function(button) {
                Zoom.rebuildMembersList(button.getId(), button.options.actionData[0], button.options.actionData[1]);
            }
        });
    },
    
    rebuildMembersList :
    function(button, src, dst) {
        var i;
        var oSrc = $(src);
        var oDst = $(dst);
        if (button.indexOf('add') >= 0) {
            for (i = 0; i < oSrc.childNodes.length; i++) {
                if (oSrc.childNodes[i].selected && oSrc.childNodes[i].value != "0" && !this.hasOption(oDst, oSrc.childNodes[i].value)) {
                    oDst.appendChild(oSrc.childNodes[i].cloneNode(true));
                }
            }
        } else {
            for (i = 0; i < oDst.childNodes.length; i++) {
                if (oDst.childNodes[i].selected) {
                    oDst.removeChild(oDst.childNodes[i]);
                }
            }
        }
    },
    
    buildMembersList :
    function(members, src, dst) {
        var i, j, uid, option, aclsplit;
        var oSrc = $(src);
        var oDst = $(dst);
        // First, clear the destination <SELECT>
        oDst.innerHTML = "";
        if (members) {
            // Then we populate it again with the values from the {members} array
            if (typeof members == "string") members = members.split(',');
            for (i = 0; i < members.length; i++) {
                uid = members[i].getAttribute('id');
                aclsplit = uid.split('-');
                if (uid == "1") {
                    option = oSrc.childNodes[3].cloneNode(true);
                } else if (uid == "2") {
                    option = oSrc.childNodes[7].cloneNode(true);
                } else if (aclsplit[0] == 'gid') {
                    option = document.createElement('option');
                    option.value = uid;
                    option.innerHTML = members[i].lastChild.nodeValue;
                } else {
                    option = document.createElement('option');
                    option.value = uid;
                    option.innerHTML = uid + "-" + members[i].lastChild.nodeValue + "(" + members[i].getAttribute('username') + ")";
                }
                oDst.appendChild(option);
            }
        }
    },
    
    hasOption :
    function(elmnt, value) {
        var found = false;
        for (var i = 0; i < elmnt.childNodes.length && !found; i++)
            if (elmnt.childNodes[i].nodeName.toLowerCase() == "option")
                if (elmnt.childNodes[i].value == value) found = true;
        return found;
    },
    
    getGalleryTree :
    function(elmnt) {
        if (!elmnt) elmnt = 'cats_tree';
        this.treenode = elmnt;
        var params = 'uid=' + this.uid + '&id=' + id + '&task=catsmgr_getlist';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: this.buildTree});
    },
    
    buildTree : 
    function(callback) {
        //alert(callback.responseText);
        $('cats_tree').innerHTML = "";

        Zoom.treeObj = new JSDragDropTree();
        Zoom.treeObj.setTreeId('cats_tree');
        Zoom.treeObj.setImageFolder(Zoom.site_uri + '/components/com_zoom/www/dhtmlgoodies/');
        Zoom.treeObj.setUserFunction('onItemSelect', function(){
            var item = this;
            if (item.nodeName.toLowerCase() == "img" || item.nodeName.toLowerCase() == "a")
                item = item.parentNode; 
            var id = item.id.replace(/node/, '');
            if (id == "0") resetFormValues();
            else Zoom.ongalleryclick(id);
        });
        Zoom.treeObj.setUserFunction('onItemEnter', function() {
            var item = this;
            if (item.nodeName.toLowerCase() == "img" || item.nodeName.toLowerCase() == "a")
                item = item.parentNode;
            Zoom.ongalleryhover(item.id.replace(/node/, ''), item.evData.published, item.evData.shared, item.evData.uid);
        });
        Zoom.treeObj.setUserFunction('onItemLeave', function(){return nd();});
        Zoom.treeObj.setUserFunction('onItemDelete', function(ids, orig, callback) {
            
        });
        Zoom.treeObj.setUserFunction('onItemRename', Zoom.ongalleryrename);
        
        Zoom.treeObj.initTree('xml', callback.responseXML);
    },
    
    resortGallery :
    function() {
    	showMe();
        var params = 'uid=' + this.uid + '&id=' + id + '&tree=' + this.treeObj.getNodeOrders() + '&task=catsmgr_resort';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: function(callback) {
            	Zoom.setFormFields(callback);
            }});
    },
    
    getGalleryFromElement :
    function(element) {
    	var a = element.getElementsByTagName('a');
    	if (a.length > 0) {
			var a_pointer = a[a.length - 1].href.substr(31, (a[a.length - 1].href.length - 33)).replace(/\'/gi, '');
			return a_pointer.split(',');
    	}
    	return [0, 0, 0, 0];
    },
    
    ongalleryclick :
    function(catid) {
        showMe();
        var params = 'uid=' + this.uid + '&id=' + id + '&catid=' + catid + '&task=catsmgr_editform';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: Zoom.setFormFields });
    },
    
    getNewDir :
    function() {
    	var params = 'uid=' + this.uid + '&id=' + id + '&task=catsmgr_newdir';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: function(callback) {
            	var nodes = callback.responseXML.getElementsByTagName('newdir');
            	if (nodes.length >= 1) {
            		document.catsmgr.elements['catdir'].value = nodes[0].firstChild.nodeValue;
            	}
            }});
    },
    
    setFormFields :
    function(callback) {
    	//alert(callback.responseText);
        var msg;
        hideMe();
        Zoom.getToolbar('catsmgr_editform');
        Zoom.getParentOptions();
        var action = callback.responseXML.getElementsByTagName('zoom').item(0).getAttribute('action');
        var result = callback.responseXML.getElementsByTagName('result').item(0);
        if (result.firstChild.nodeValue == "KO") {
            msg = callback.responseXML.getElementsByTagName('reason');
            if (msg[0] && msg[0].firstChild.nodeValue != "none") alert(msg[0].firstChild.nodeValue);
        } else if (result.firstChild.nodeValue == "OK") {
            msg = callback.responseXML.getElementsByTagName('msg');
            if (msg[0] && msg[0].firstChild.nodeValue != "none")
                alert(msg[0].firstChild.nodeValue);
            if (action == "catsmgr_delete")
                return Zoom.treeObj.__deleteComplete($('node' + Zoom.activeCat));
            var gallery = callback.responseXML.getElementsByTagName('gallery').item(0);
            if (gallery) Zoom.activeCat = gallery.getAttribute('id');
            if (action == "catsmgr_new") {
				Zoom.getGalleryTree('cats_tree');
            } else if (action == "catsmgr_save") {
                var item = $('node' + gallery.getAttribute('id'));
                item.evData.published = gallery.getAttribute("published");
				item.evData.shared    = gallery.getAttribute("shared");
				item.evData.keywords  = gallery.getAttribute("keywords");
				item.evData.uid       = gallery.getAttribute("uid");
                Zoom.treeObj.setItemText(gallery.getAttribute('id'), gallery.getAttribute('name'));
                var subcat_id = gallery.getAttribute("subcat_id");
                if (item.evData.subcat_id != subcat_id) {
                    Zoom.getParentOptions();
                }
            } else if (action == "catsmgr_resort") {
                Zoom.getParentOptions();
            }
            if (gallery) {
                Zoom.state = 'edit';
                Zoom.activeSubcat = gallery.getAttribute('subcat_id');
                setFormValues(gallery.getAttribute('id'), gallery.getAttribute('name'), gallery.getAttribute('dir'), 
                    gallery.getAttribute('password'), gallery.getAttribute('keywords'), gallery.lastChild.nodeValue,
                    Zoom.activeSubcat, gallery.getAttribute('pos'),
                    gallery.getAttribute('hide_msg'), gallery.getAttribute('published'), gallery.getAttribute('shared'));
                var members = gallery.getElementsByTagName('member');
                Zoom.buildMembersList(members, 'members_opt', 'members_sel');
            } else {
                Zoom.state = 'new';
                resetFormValues();
            }
        }
    },
    
    ongalleryrename :
    function(catid, newname, call) {
        var params = 'uid=' + Zoom.uid + '&id=' + id + '&task=catsmgr_rename&catid=' + catid + '&newname=' + newname;
        this.connector = new Ajax.Request(Zoom.req_uri, {
            method: Zoom.method,
            encoding: Zoom.charset,
            parameters: params,
            onSuccess: function(callback, call) {
            	call()
            }});
    },
    
    ongalleryhover :
    function(id, published, shared, uid) {
        if (!published | !shared | !uid) return;
        var html = '<div class="cats_hoverbox"><img src="' + Zoom.site_uri;
        if (published == "1") {
            html += '/components/com_zoom/www/images/publish_g.png" alt="" border="0" />&nbsp;' +
                LANG_PUBLISHED + ': <b>' + LANG_YES + '</b>';
        } else {
            html += '/components/com_zoom/www/images/publish_x.png" alt="" border="0" />&nbsp;' +
                LANG_PUBLISHED + ': <b>' + LANG_NO + '</b>';
        }
        html += '<br /><img src="' + Zoom.site_uri;
        if (shared == "1") {
            html += '/components/com_zoom/www/images/share_u.png" alt="" border="0" />&nbsp;' +
                LANG_SHARED + ': <b>' + LANG_YES + '</b>';
        } else {
            html += '/components/com_zoom/www/images/share_l.png" alt="" border="0" />&nbsp;' +
                LANG_SHARED + ': <b>' + LANG_NO + '</b>';
        }
        html += '<br /><img src="' + Zoom.site_uri +'/components/com_zoom/www/images/home.gif" alt="" border="0" />ID: <strong>' + id + '</strong></div>';
        overlib(html);
    },
    
    getToolbar :
    function(action) {
        var buttons;
        if (typeof action == "undefined") action = "catsmgr";
        var params = 'uid=' + this.uid + '&id=' + id + '&action=' + action + '&task=get_toolbar';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: this.buildToolbar});
    },
    
    buildToolbar :
    function(callback) {
        var i, j, name, caption, domButton, domImg;
        var spacerSize = 10; //width of the table cells that function as a spacer in 'px'
        var buttons = callback.responseXML.getElementsByTagName('button');
        var toolbar = IS_BACKEND ? $('toolbar').rows[0] : $('ZMG_toolbar');
        
        if (IS_BACKEND) {
            if (is_ie) { //apparently, IE doesn't like it when you clear a table row with 'innerHTML' :S
                for (i = 0; i < toolbar.childNodes.length; i++) {
                    if (toolbar.childNodes[i]) {
                        if (toolbar.childNodes[i].clientWidth == 10)
                            toolbar.childNodes[i].style.width = "0px";
                        if (toolbar.childNodes[i].nodeName && toolbar.childNodes[i].nodeName.toLowerCase() == "td") {
                            try {
                                toolbar.removeChild(toolbar.childNodes[i]);
                            } catch (ex) {}; //do nothing...
                        }
                    }
                }
            } else {
                toolbar.innerHTML = "";
            }
            //spacer:
            toolbar.insertCell(0).width = spacerSize;
            
            domButton = toolbar.insertCell(0).appendChild(document.createElement('a'));
            domButton.id = "ZMG_back";
            domButton.href = Zoom.site_uri + "/administrator/index2.php?option=com_zoom&page=admin";
            Element.addClassName(domButton, 'toolbar');
            domImg = domButton.appendChild(document.createElement('img'));
            domImg.border = 0;
            domImg.align = "middle";
            domImg.name = "back";
            domImg.alt = LANG_BTN_BACK;
            domImg.src = Zoom.site_uri + "/administrator/images/back_f2.png";
            domButton.appendChild(document.createElement('br'));
            domButton.appendChild(document.createTextNode(LANG_BTN_BACK));
        } else {
            toolbar.innerHTML = "";
        }
        
        for (var j = 0; j < buttons.length; j++) {
            name = buttons[j].getAttribute('type');
            caption = buttons[j].getAttribute('caption') || '';
            if (IS_BACKEND) {
                //spacer:
                toolbar.insertCell(0).width = spacerSize;

                domButton = toolbar.insertCell(0).appendChild(document.createElement('a'));
                domButton.id = "ZMG_" + name;
                domButton.href = "#";
                domButton.onclick = function() {
                    Zoom.dispatch(this.id.replace(/ZMG_/g, ''));
                };
                Element.addClassName(domButton, 'toolbar');
                domImg = domButton.appendChild(document.createElement('img'));
                domImg.border = 0;
                domImg.align = "middle";
                domImg.name = name;
                domImg.alt = caption;
                domImg.src = Zoom.site_uri + "/administrator/images/" + name + "_f2.png";
                domButton.appendChild(document.createElement('br'));
                domButton.appendChild(document.createTextNode(caption));
            } else {
                new Zoom.Button(name, '', toolbar, {
                    width: 32,
                    height: 32,
                    group: caption,
                    classEnter: 'zmg_tb_btn_' + name + '_hover',
                    classLeave: 'zmg_tb_btn_' + name,
                    onEnter: function(button) {
                        overlib(button.options.group);
                    },
                    onLeave : function(button) {
                        nd();
                    },
                    onLeftClick: function(button) {
                        Zoom.dispatch(button.getId());
                    }
                });
            }
        }
        if (IS_BACKEND) {
            //spacer:
            toolbar.insertCell(0).width = spacerSize;
            
            domButton = toolbar.insertCell(0).appendChild(document.createElement('a'));
            domButton.id = "ZMG_ordersave";
            domButton.href = "#";
            domButton.onclick = function() {
                Zoom.resortGallery();
            };
            Element.addClassName(domButton, 'toolbar');
            domImg = domButton.appendChild(document.createElement('img'));
            domImg.border = 0;
            domImg.align = "middle";
            domImg.name = "ordersave";
            domImg.alt = LANG_BTN_ORDERSAVE;
            domImg.src = Zoom.site_uri + "/components/com_zoom/www/images/admin/ordersave_f2.png";
            domButton.appendChild(document.createElement('br'));
            domButton.appendChild(document.createTextNode(LANG_BTN_ORDERSAVE));
        } else {
            new Zoom.Button('ZMG_ordersave', '', toolbar, {
                width: 32,
                height: 32,
                group: LANG_BTN_ORDERSAVE,
                classEnter: 'zmg_tb_btn_ordersave_hover',
                classLeave: 'zmg_tb_btn_ordersave',
                onEnter: function(button) {
                    overlib(button.options.group);
                },
                onLeave : function(button) {
                    nd();
                },
                onLeftClick: function(button) {
                    Zoom.resortGallery();
                }
            });
        }
    },
    
    getParentOptions :
    function() {
        var params = 'uid=' + this.uid + '&id=' + id + '&task=get_galleries';
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: this.setParentOptions});
    },
    
    setParentOptions :
    function(callback) {
        var oParent = document.catsmgr.elements['parent'];
        var sel = "0";
        
        var options = callback.responseXML.getElementsByTagName('gallery');
        var option, id;
        
        oParent.innerHTML = "";
        for (var j = 0; j < options.length; j++) {
            id = options[j].getAttribute('id');
            if (id != Zoom.activeCat) {
                var option = document.createElement('option');
                option.value = id;
                option.innerHTML = options[j].firstChild.nodeValue;
                if (option.value == Zoom.activeSubcat)
                    option.selected = true;
                oParent.appendChild(option);
            }
        }
    },
    
    dispatch :
    function(action) {
        var i, descr = getEditorContent();
        if (descr == null)
            descr = $F('catdescr');
        var form = document.catsmgr;
        var oParent = form.elements['parent'];
        var sParent = "0";
        if (action == "delete" && !confirm(LANG_CONFIRM_DEL)) return;
        showMe();
        for (i = 0; i < oParent.childNodes.length; i++)
            if (oParent.childNodes[i].selected) sParent = oParent.childNodes[i].value;
        var params = 'uid=' + this.uid + '&id=' + id + '&catid=' + $F('catid') + '&task=catsmgr_' + action + '&parent=' + sParent +
            '&name=' + $F('catname').replace('&', '%26') + '&dir=' + $F('catdir') +
            '&password=' + $F('catpassword') + '&keywords=' + $F('catkeywords').replace('&', '%26') +
            '&descr=' + descr + '&hide_msg=' + ((form.elements['hidemsg'].checked) ? "1" : "0") +
            '&published=' + ((form.elements['published'].checked) ? "1" : "0") + '&shared=' + ((form.elements['shared'].checked) ? "1" : "0");
        for (i = 0; i < form.elements['members_sel'].options.length; i++)
            params += '&members_sel[]=' + form.elements['members_sel'].options[i].value;
        this.connector = new Ajax.Request(this.req_uri, {
            method: this.method,
            encoding: this.charset,
            parameters: params,
            onSuccess: Zoom.setFormFields });
    },
    
    doPinTree: function() {
        if (this.treePinned)
            this.unpinTree();
        else
            this.pinTree();
    },
    
    unpinTree: function() {
        //if (is_ie) this.treeUnsetDragdrop();
        var oTree = $('cats_tree_container');
        var oTreeCaption = $('cats_tree_caption');
        oTree.style.zIndex = 200;
        oTreeCaption.style.zIndex = 200;
        Position.prepare();
        Position.absolutize(oTree);
        Position.absolutize(oTreeCaption);
        var aPosT = Position.page(oTree);
        var aPosC = Position.page(oTreeCaption);
        this.treePos = [aPosT[0] + Position.deltaX, aPosT[1] + Position.deltaY];
        this.treeCaptionPos = [aPosC[0] + Position.deltaX, aPosC[1] + Position.deltaY];
        Element.addClassName($('cats_tree'), 'cats_tree_unpinned');
        Element.addClassName(oTreeCaption, 'cats_tree_caption_unpinned');
        this.treeDrag = new Draggable(oTreeCaption);
        oTreeCaption.style.cursor = "move";
        document.body.appendChild(oTreeCaption);
        document.body.appendChild(oTree);
        Element.hide($('cats_tree_table'));
        var oImg = $('cats_tree_pinimg');
        oImg.src = oImg.src.replace(/unpin.gif/, 'pin.gif');
        this.treePinned = false;
        this.dragTree(this.treeDrag);
    },
    
    pinTree: function() {
        //if (is_ie) this.treeSetDragdrop();
        var oTree = $('cats_tree_container');
        var oTable = $('cats_tree_table');
        var oTreeCaption = $('cats_tree_caption');
        oTree.style.zIndex = 0;
        oTreeCaption.style.zIndex = 0;
        Element.show(oTable);
        Position.prepare();
        oTree.style.left = this.treePos[0] + "px";
        oTree.style.top = this.treePos[1] + "px";
        oTreeCaption.style.left = this.treeCaptionPos[0] + "px";
        oTreeCaption.style.top = this.treeCaptionPos[1] + "px";
        Position.relativize(oTree);
        Position.relativize(oTreeCaption);
        this.treeDrag.destroy();
        this.treeDrag = null;
        oTreeCaption.style.cursor = "";
        Element.removeClassName($('cats_tree'), 'cats_tree_unpinned');
        Element.removeClassName(oTreeCaption, 'cats_tree_caption_unpinned');
        oTable.appendChild(oTreeCaption);
        oTable.appendChild(oTree);
        var oImg = $('cats_tree_pinimg');
        oImg.src = oImg.src.replace(/pin.gif/, 'unpin.gif');
        this.treePinned = true;
    },
    
    dragTree:
    function(draggable) {
        if (draggable == this.treeDrag) {
            var d = draggable.currentDelta();
            //$('catsmgr_tabs_page1').innerHTML += "DEBUG: " + draggable + ", " + pos[0] + ", " + pos[1] + "<br />";
            var oTree = $('cats_tree_container');
            oTree.style.left = d[0] + "px";
            oTree.style.top = (d[1] + 35) + "px";
        }
    },
    
    unit_save_vote :
    function(imgid, vote) {
        if (!this.votingActive) {
            var params = 'uid=' + this.uid + '&id=' + id + '&task=view_vote&imgid=' + imgid + '&vote=' + vote;
            this.votingMedium = imgid;
            this.votingActive = true;
            this.connector = new Ajax.Request(this.req_uri, {
                method: 'post',
                encoding: this.charset,
                parameters: params,
                onSuccess: this.unit_update_vote });
        }
    },
    
    unit_update_vote :
    function(callback) {
        Zoom.votingActive = false;
        var msgContainer = $('unit_long' + Zoom.votingMedium);
        var result = callback.responseXML.getElementsByTagName('result').item(0);
        if (result.firstChild.nodeValue == 'KO') {
            msg = callback.responseXML.getElementsByTagName('reason');
            if (msg[0] && msg[0].firstChild.nodeValue != 'none') msgContainer.innerHTML = msg[0].firstChild.nodeValue;
        } else if (result.firstChild.nodeValue == 'OK') {
            msg = callback.responseXML.getElementsByTagName('msg');
            if (msg[0] && msg[0].firstChild.nodeValue != 'none') msgContainer.innerHTML = msg[0].firstChild.nodeValue;
        }
    },
    
    lightboxAdd :
    function(imgid, type, image) {
        if (!this.lightboxActive) {
            var params = 'uid=' + this.uid + '&id=' + id + '&task=view_lightbox&imgid=' + imgid + '&type=' + type;
            this.lightboxObj = image;
            image.src = Zoom.site_uri + '/components/com_zoom/www/images/indicator.gif';
            this.connector = new Ajax.Request(this.req_uri, {
                method: 'post', parameters: params,
                onSuccess: this.lightboxResult });
        }
        
    },
    
    lightboxResult :
    function(callback) {
        Zoom.lightboxActive = false;
        var result = callback.responseXML.getElementsByTagName('result').item(0);
        if (result.firstChild.nodeValue == 'KO') {
            msg = callback.responseXML.getElementsByTagName('reason');
            Zoom.lightboxObj.src = Zoom.site_uri + '/components/com_zoom/www/images/delete.png';
            if (msg[0] && msg[0].firstChild.nodeValue != 'none') Zoom.lightboxObj.parentNode.onmouseover = function(){return overlib(msg[0].firstChild.nodeValue, CAPTION, lb_title);};
        } else if (result.firstChild.nodeValue == 'OK') {
            msg = callback.responseXML.getElementsByTagName('msg');
            Zoom.lightboxObj.src = Zoom.site_uri + '/components/com_zoom/www/images/priv_yes.png';
            if (msg[0] && msg[0].firstChild.nodeValue != 'none') Zoom.lightboxObj.parentNode.onmouseover = function(){return overlib(msg[0].firstChild.nodeValue, CAPTION, lb_title);};
        }
    },
    
    slide :
    function(what, siteUrl) {
        var theImage = "";
        if (Zoom.folded[what] == 1) {
          new Effect.SlideDown($(what + 'Body'), {duration: 0.5});
          Zoom.folded[what] = 0;
          theImage = siteUrl + "/components/com_zoom/www/images/blocks/arrow_up_white.png";
        } else {
          new Effect.SlideUp($(what + 'Body'), {duration: 0.5});
          Zoom.folded[what] = 1;
          theImage = siteUrl + "/components/com_zoom/www/images/blocks/arrow_down_white.png";
        }
        MM_swapImage(what + 'Image', '', theImage, 1);
    },
    
    toggleDisplay :
    function(what, siteUrl) {
        var theImage;
        if (Zoom.folded[what] == 1) {
          new Element.show($(what + 'Body'));
          Zoom.folded[what] = 0;
          theImage = "/components/com_zoom/www/images/blocks/arrow_up_white.png";
          MM_swapImage(blockImage, '', theImage, 1);
        } else {
          new Element.hide($(what + 'Body'));
          Zoom.folded[what] = 1;
          theImage = siteUrl + "/components/com_zoom/www/images/blocks/arrow_down_white.png";
        }
        MM_swapImage(what + 'Image', '', theImage, 1);
    },
    
    changeArrow :
    function(what, color, siteUrl) {
        var theImage = "";
        var direction = "";
        if (Zoom.folded[what] == 1) {
            direction = "down";
        } else {
            direction = "up";
        }
        theImage = siteUrl + "/components/com_zoom/www/images/blocks/arrow_" + direction + "_" + color + ".png";
        MM_swapImage(what + 'Image', '', theImage, 1);
    },

    emoticon :
    function(text) {
        var txtarea = document.post.comment;
        text = ' ' + text + ' ';
        if (txtarea.createTextRange && txtarea.caretPos) {
        	var caretPos = txtarea.caretPos;
        	caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        	txtarea.focus();
        } else {
        	txtarea.value  += text;
        	txtarea.focus();
        }
    },
    
    storeCaret :
    function(textEl) {
        if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
    },
    
    
    getWindowSize :
    function (win) {
    	if (typeof win == "undefined")
    		win = window;
     	if (win.innerHeight != null)
     		return {width: win.innerWidth, height:  win.innerHeight};
    	if (win.document.documentElement && win.document.documentElement.clientHeight)
    		return this.getElementSize(win.document.documentElement);
    	if (win.clientHeight != null)
    		return {width: win.clientWidth, height: win.clientHeight};
    	return this.getElementSize(win.document.body);
    },
    
    getElementSize :
    function (el) {
     	if (el.offsetHeight)
     		return {width: el.offsetWidth, height: el.offsetHeight};
    	if (el.style && el.style.width && el.style.height)
     		return {width: parseInt(el.style.width), height: parseInt(el.style.height)};
    	if (el.clientHeight)
     		return {width: el.clientWidth, height: el.clientHeight};
    	return {width: -1, height: -1};
    }
}

Zoom.load();