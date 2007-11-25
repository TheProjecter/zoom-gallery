/**
 * @version $Id:button.js 106 2007-02-10 22:30:30Z kevinuru $
 * @class Button
 * @contructor
 * @extends Rico
 * @author Mike de Boer (mike AT ebuddy.com)
 */ 
Zoom.Button = Class.create();
Zoom.Button.prototype = {
    /**
     * Initialize the Button class.
     * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {String} id
	 * @param {mixed} label Value of the label inside the button.
	 * @param {String} container The container element of this Button instance.
	 * @param {Object} options Optional.
	 * @type Button
	 */
    initialize :
    function(id, label, container, options) {
        this.container = container;
        this.setOptions(options);
        
        this.domNode = document.createElement('div');
        if (this.options.group.indexOf('members') >= 0) //07-25-2006 mikedeboer: custom for ZMG
            Element.addClassName(this.domNode, 'zmg_nav_button');
        else
            Element.addClassName(this.domNode, 'zmg_tb_button');
        Element.addClassName(this.domNode, this.options.classLeave);
        if (this.options.width != null)
            this.domNode.style.width = this.options.width + "px";
        if (this.options.height != null)
            this.domNode.style.height = this.options.height + "px";
        
        this.setId(id);
        if (label)
            this.newLabel(label);
        
        this.container.appendChild(this.domNode);
        this._attachBehaviors();
    },
    /**
	 * Set the optional global settings for the Button. If no options are provided,
	 * the defaults are used.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {Object} options Generic object, containing all the custom options.
	 * @type void
	 */
    setOptions :
    function(options) {
        this.options = {
            classEnter         : 'button_enter',
            classLeave         : 'button_leave',
            classClick         : 'button_click',
            classLabel         : 'button_label',
            classDisabled      : 'button_disabled',
            classLabelDisabled : 'button_label_disabled',
            selectOnClick      : false,
            multiSelect        : false,
            group              : null,
            disabled           : false,
            width              : null,
            height             : null,
            actionData         : null,
            onEnter            : null,
            onLeave            : null,
            onLeftClick        : null,
            onDblClick         : null,
            onRightClick       : null,
            onDisable          : null,
            onEnable           : null
        }
        Object.extend(this.options, options || {});
   },
   /**
	 * Return the Identifier of this Button instance
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {String} newid The new Identifier for this Button instance.
	 * @type void
	 */
   setId :
   function(newid) {
       if (typeof newid == "string")
           this.domNode.setAttribute('id', newid);
   },
   /**
	 * Return the Identifier of this Button instance
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @type String
	 */
   getId :
   function() {
       return this.domNode.getAttribute('id');
   },
   /**
	 * Set a (new) label inside the Button
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {mixed} newLabel
	 * @type void
	 */
   newLabel :
   function(newlabel) {
       if (typeof newlabel == "string")
            newlabel = document.createTextNode(newlabel);
        
        if (!this.label) {
            this.label = document.createElement('span');
            /* set the label width to make horizontal positioning easier to deal with: */
            this.label.style.width = this.options.width;
            Element.addClassName(this.label, this.options.classLabel);
            this.domNode.appendChild(this.label);
        }
        if (this.label.childNodes.length > 0)
            this.label.removeChild(this.label.childNodes[0]);

        this.label.appendChild(newlabel);
        
   },
   /**
	 * Move the Button DOM node to another location in the document tree.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {mixed} to
	 * @type void
	 */
   move :
   function(to) {
       to = $(to);
       this.container = to;
       this.container.appendChild(this.domNode);
   },
   /**
	 * Place the Button DOM node directly before a sibling in the document tree.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {mixed} before
	 * @type void
	 */
   insertBefore :
   function(parent, before) {
       parent = $(parent);
       before = $(before);
       this.container = parent;
       this.container.insertBefore(this.domNode, before);
   },
   /**
	 * Place the Button DOM node directly after a sibling in the document tree.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {mixed} before
	 * @type void
	 */
   insertAfter :
   function(parent, after) {
       parent = $(parent);
       after = $(after);
       this.container = parent;
       this.container.insertBefore(this.domNode, after.nextSibling);
   },
   /**
     * Set this Button to 'disabled', ie. not clickable/ changeable.
     * @author Mike de Boer (mike AT ebuddy.com)
	 * @type void
	 */
    disable :
    function() {
        if (!this.options.disabled && !Element.hasClassName(this.domNode, this.options.classDisabled)) {
            Element.addClassName(this.domNode, this.options.classDisabled);
            Element.addClassName(this.label, this.options.classLabelDisabled);
            this.options.disabled = true;
            if (this.options.onDisable != null)
                this.options.onDisable(this);
        }
    },
    /**
	 * Set this Button to 'enabled', ie. clickable AND changeable.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @type void
	 */
    enable :
    function() {
        if (this.options.disabled && Element.hasClassName(this.domNode, this.options.classDisabled)) {
            Element.removeClassName(this.domNode, this.options.classDisabled);
            Element.removeClassName(this.label, this.options.classLabelDisabled);
            this.options.disabled = false;
            if (this.options.onEnable != null)
                this.options.onEnable(this);
        }
    },
   /**
	 * Change the visual appearance of this Button as the mouse hovers over it.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {Event} e
	 * @type void
	 */
   onEnter :
   function(e) {
       if (!Element.hasClassName(this.domNode, this.options.classEnter))
           Element.addClassName(this.domNode, this.options.classEnter);
       
       if (this.options.onEnter != null)
           this.options.onEnter(this, e);
       
       Event.stop(e);
   },
   /**
	 * Change the visual appearance of this Button after the mouse left.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {Event} e
	 * @type void
	 */
   onLeave :
   function(e) {
       if (Element.hasClassName(this.domNode, this.options.classEnter) && !this.selected)
           Element.removeClassName(this.domNode, this.options.classEnter);
       
       if (Element.hasClassName(this.domNode, this.options.classClick) && !this.selected)
           Element.removeClassName(this.domNode, this.options.classClick);

       if (e) {
           if (this.options.onLeave != null)
              this.options.onLeave(this, e);
           Event.stop(e);
       }
   },
   /**
	 * Change the value and visual appearance of this Button after it has been clicked.
	 * @author Mike de Boer (mike AT ebuddy.com)
	 * @param {Event} e
	 * @type void
	 */
   onClick :
   function(e) {
       if (!this.options.disabled) {
           if (Element.hasClassName(this.domNode, this.options.classEnter))
               Element.removeClassName(this.domNode, this.options.classEnter);
           
           if (Element.hasClassName(this.domNode, this.options.classClick) && this.selected && !this.options.selectOnClick) {
               Element.removeClassName(this.domNode, this.options.classClick);
               this.selected = false;
           } else {
               Element.addClassName(this.domNode, this.options.classClick);
               this.selected = true;
           }
           if (e.type == "click") {
               if (this.options.onLeftClick != null)
                   this.options.onLeftClick(this, e);
           }
           if (e.type == "dblclick") {
               if (this.options.onDblClick != null)
                   this.options.onDblClick(this, e);
           }
           if (e.type == "contextmenu") {
               if (this.options.onRightClick != null)
                   this.options.onRightClick(this, e);
           }
           if (!this.options.selectOnClick && Element.hasClassName(this.domNode, this.options.classClick)) {
               Element.removeClassName(this.domNode, this.options.classClick);
               this.selected = false;
           }
       }
       Event.stop(e);
   },
   /**
     * Add various event handlers to a <i>Button</i> object.
     * @author Mike de Boer (mike AT ebuddy.com)
     * @type void
     */
   _attachBehaviors :
   function() {
       this.domNode.setAttribute('rico:widget', 'button');
       if (this.options.group != null)
           this.domNode.setAttribute('rico:group', this.options.group);
       this.domNode.button = this;
       this.selected = false;
       this.domNode.onmouseover   = this.onEnter.bindAsEventListener(this);
       this.domNode.onmouseout    = this.onLeave.bindAsEventListener(this);
       this.domNode.onclick       = this.onClick.bindAsEventListener(this);
       this.domNode.ondblclick    = this.onClick.bindAsEventListener(this);
       this.domNode.oncontextmenu = this.onClick.bindAsEventListener(this);
   }
};