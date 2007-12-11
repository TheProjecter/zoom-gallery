if (!window.ZMG) window.ZMG = {};

ZMG.Admin = {
    nodeCache    : {},
    menuTree     : null,
    Events       : null,
    
    start: function() {
        ZMG.Admin.cacheElement('zmg_admin_cont');
        ZMG.Admin.cacheElement('zmg_admin_loader');
        ZMG.Admin.cacheElement('zmg_tree_body');
        ZMG.Admin.cacheElement('zmg_view_content');
        ZMG.Admin.Events = new ZMG.Events();
        //set correct dimensions of the admin content
        ZMG.Admin.Events.Client.onwindowresize();
        //load the tree
        ZMG.Admin.Events.Client.onloadnavigation();
        //set the initial view
        ZMG.Admin.Events.Client.onviewselect();
    },
    
    /**
     * Add a DOM element to the DOM cache, for easy retrieval throughout
     * the application.
     * @author Mike de Boer (mike AT zoomfactory.org)
     * @param {String} id
     * @param {String} elname Optional.
     * @type DOMElement
     */
    cacheElement :
    function(id, elname) {
        if (!this.nodeCache[id] && !this.nodeCache[elname]) {
            var el = $(id);
            if (el) {
                this.nodeCache[elname || id] = el;
                return el;
            } else
                return null;
        }
        return this.nodeCache[elname || id] || null;
    }
};

window.addEvent('domready', ZMG.Admin.start);

Array.extend({
    inject: function(memo, iterator) {
      this.each(function(value, index) {
        memo = iterator(memo, value, index);
      });
      return memo;
    }
});

/**
 * The following code has been derived from work done by the Prototype js team
 * (Sam Stephenson et al.).
 * See Prototype's Form#Element#Methods and Form#Element#Serializers classes
 * for a complete reference. 
 */
var FormSerializer = {
  serialize: function(form) {
    return FormSerializer.serializeElements(FormSerializer.getElements($(form)));
  },
  
  getElements: function(form) {
    return $A($(form).getElementsByTagName('*')).inject([],
      function(elements, child) {
        if (FormSerializer.Serializers[child.tagName.toLowerCase()])
          elements.push($(child));
        return elements;
      }
    );
  },
  
  serializeElements: function(elements) {
    return elements.inject([], function(queryComponents, element) {
      var queryComponent = FormSerializer.serializeElement(element);
      if (queryComponent) queryComponents.push(queryComponent);
      return queryComponents;
    }).join('&');
  },
  
  serializeElement: function(element) {
    element = $(element);
    if (element.disabled) return '';
    var method = element.tagName.toLowerCase();
    var parameter = FormSerializer.Serializers[method](element);

    if (parameter) {
      var key = encodeURIComponent(parameter[0]);
      if (key.length == 0) return;

      if (parameter[1].constructor != Array)
        parameter[1] = [parameter[1]];

      return parameter[1].map(function(value) {
        return key + '=' + encodeURIComponent(value);
      }).join('&');
    }
  }
};

FormSerializer.Serializers = {
  input: function(element) {
    switch (element.type.toLowerCase()) {
      case 'checkbox':
      case 'radio':
        return FormSerializer.Serializers.inputSelector(element);
      default:
        return FormSerializer.Serializers.textarea(element);
    }
    return false;
  },

  inputSelector: function(element) {
    if (element.checked)
      return [element.name, element.value];
  },

  textarea: function(element) {
    return [element.name, element.value];
  },

  select: function(element) {
    return FormSerializer.Serializers[element.type == 'select-one' ?
      'selectOne' : 'selectMany'](element);
  },

  selectOne: function(element) {
    var value = '', opt, index = element.selectedIndex;
    if (index >= 0) {
      opt = $(element.options[index]);
      // Uses the new potential extension if hasAttribute isn't native.
      value = opt.hasAttribute('value') ? opt.value : opt.text;
    }
    return [element.name, value];
  },

  selectMany: function(element) {
    var value = [];
    for (var i = 0, length = element.length; i < length; i++) {
      var opt = $(element.options[i]);
      if (opt.selected)
        // Uses the new potential extension if hasAttribute isn't native.
        value.push(opt.hasAttribute('value') ? opt.value : opt.text);
    }
    return [element.name, value];
  }
    
};
