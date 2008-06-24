if (!window.ZMG) window.ZMG = {};

ZMG.Tooltip = new Class({
    options: {
        classTip          : 'zmg-tip',
        classTopLeft      : 'zmg-tip-tl',
        classTopRight     : 'zmg-tip-tr',
        classTopCenter    : 'zmg-tip-tc',
        classHeader       : 'zmg-tip-header',
        classHeaderText   : 'zmg-tip-header-text',
        classBodyWrap     : 'zmg-tip-bwrap',
        classMiddleLeft   : 'zmg-tip-ml',
        classMiddleRight  : 'zmg-tip-mr',
        classMiddleCenter : 'zmg-tip-mc',
        classTipBody      : 'zmg-tip-body',
        classBottomLeft   : 'zmg-tip-bl',
        classBottomRight  : 'zmg-tip-br',
        classBottomCenter : 'zmg-tip-bc',
        relElement        : null,
        parentNode        : null,
        closeButton       : false,
        zIndex            : 20000,
        initX             : 0,
        initY             : 0,
        initWidth         : 240
    },
    
    initialize: function(id, options) {
        this.setOptions(options);
        ZMG.Tooltip.COUNTER++;
        this.id = id || "zmg_tooltip" + ZMG.Tooltip.COUNTER;
        this.listening = (this.options.relElement != null);
        this.build()._attachBehaviors();
    },
    
    build: function() {
        this.domNode = new Element('div', {
            'class': this.options.classTip,
            'id'   : this.id
        });
        this.domNode.innerHTML = ['<div class="', this.options.classTopLeft, '">\
              <div class="', this.options.classTopRight, '">\
                <div class="', this.options.classTopCenter, '">\
                  <div class="', this.options.classHeader, '">\
                    <span class="', this.options.classHeaderText, '" id="', this.id, '_headertext"></span>\
                  </div>\
                </div>\
              </div>\
            </div>\
            <div class="', this.options.classBodyWrap, '">\
              <div class="', this.options.classMiddleLeft, '">\
                <div class="', this.options.classMiddleRight,'">\
                  <div class="', this.options.classMiddleCenter,'">\
                    <div class="', this.options.classTipBody,'" id="', this.id,'_tipbody"></div>\
                  </div>\
                </div>\
              </div>\
              <div class="', this.options.classBottomLeft,'">\
                <div class="', this.options.classBottomRight,'">\
                  <div class="', this.options.classBottomCenter,'"></div>\
                </div>\
              </div>\
            </div>'].join('');
        (this.options.parentNode || document.body).appendChild(this.domNode);
        
        this.domHeader = $(this.id + '_headertext');
        this.domBody   = $(this.id + '_tipbody');

        return this;
    },
    
    setContent: function(header, text) {
        if (!header) return this;
        if (!text) text = "";
        
        this.domHeader.innerHTML = header;
        this.domBody.innerHTML   = text;
        
        return this;
    },
    
    show: function() {
        //TODO: animation
        this.domNode.setStyles({
            'visibility': 'visible',
            'display'   : ''
        });
        return this;
    },
    
    hide: function() {
        //TODO: animation
        this.domNode.setStyles({
            'visibility': 'hidden',
            'display'   : 'none'
        });
        return this;
    },
    
    locate: function(x, y) {
        if (typeof x != "number")
          x = this.options.initX;
        this.domNode.setStyle('left', x + 'px');
        
        if (typeof y != "number")
          y = this.options.initY;
        this.domNode.setStyle('top', y + 'px');
        
        return this;
    },
    
    setWidth: function(width) {
        if (typeof width != "number")
          width = this.options.initWidth;
        
        this.domNode.style.width = width + "px";
        this.domBody.style.width = (width - 12) + "px";
        return this;
    },
    
    _attachBehaviors: function() {
        //TODO: close button events, animation setup
        this.setWidth().locate().hide();
    }
});

ZMG.Tooltip.COUNTER = 0;

ZMG.Tooltip.implement(new Options(), new Events());