if (!window.ZMG) window.ZMG = {};

ZMG.Shared = {
    CACHE_LIFETIME: 30 * 60 * 1000, //30 minutes in milliseconds.
    
    register: function(name, value, childOf, lifetime) {
        if (!name) return this;
        
        this[name] = value;
        
        if (value) {
            //register the parent object ID (if provided)
            this[name]._childOf = childOf || null;

            //set timestamp for item lifetime
            if (isNaN(lifetime))
                lifetime = this.CACHE_LIFETIME;
            this[name]._registeredAt = new Date();
            this[name]._lifetime     = lifetime;
        }
        
        return this[name];
    },
    
    get: function(name) {
        return this[name] || null;
    },
    
    validate: function() {
        var now = new Date();
        for (var i in this) {
            if (this[i] && this[i]._registeredAt
              && this[i]._lifetime > 0) {
                var expires = this[i]._registeredAt + this[i]._lifetime
                if (expires > now) {
                    this.purgeChildren(i);
                    this[i] = null;
                    delete this[i];
                }
            }
        }
    },
    
    purgeChildren: function(name) {
        for (var i in this) {
            if (this[i] && this[i]._childOf === name) {
                this.purgeChildren(i);
                this[i] = null;
                delete this[i];
            }
        }
    },
    
    nodeCache: {},
    /**
     * Add a DOM element to the DOM cache, for easy retrieval throughout
     * the application.
     * @author Mike de Boer (mike AT zoomfactory.org)
     * @param {String} id
     * @param {String} elname Optional.
     * @type DOMElement
     */
    cacheElement: function(id, elname) {
        if (!this.nodeCache[id] && !this.nodeCache[elname]) {
            var el = $(id);
            if (el) {
                this.nodeCache[elname || id] = el;
                return el;
            } else
                return null;
        }
        return this.nodeCache[elname || id] || null;
    },

    purgeNodeCache: function() {
        var newCache = {};
        for (var i in this.nodeCache) {
            if (!this.nodeCache[i]) {
                this.nodeCache[i] = null;
                delete this.nodeCache[i];
            } else
                newCache[i] = this.nodeCache[i];
        }
        this.nodeCache = newCache;
    }
};

Array.extend({
    inject: function(memo, iterator) {
      this.each(function(value, index) {
        memo = iterator(memo, value, index);
      });
      return memo;
    }
});

String.extend({
   toQueryParams: function(separator) {
     var match = this.clean().match(/([^?#]*)(#.*)?$/);
     if (!match) return {};

     return match[1].split(separator || '&').inject({}, function(hash, pair) {
       if ((pair = pair.split('='))[0]) {
         var name = decodeURIComponent(pair[0]);
         var value = pair[1] ? decodeURIComponent(pair[1]) : undefined;

         if (hash[name] !== undefined) {
           if (hash[name].constructor != Array)
             hash[name] = [hash[name]];
           if (value) hash[name].push(value);
         } else
           hash[name] = value;
       }
       return hash;
    });
  } 
});

ZMG.Crypt = {
    Simple: {
        encrypt: function(s) {
            if (typeof s == "string" && s.indexOf('obfs') < 0) {
                var convert = [];
                for (var i = 0; i < s.length; i++)
                    convert.push(324 - s.charCodeAt(i));
                return "{obfs:" + convert.join('') + "}";
            }
            return s;
        }
    }
};

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

function _(s) {
    if (ZMG && ZMG.CONST && ZMG.CONST.i18n && ZMG.CONST.i18n[s])
        return ZMG.CONST.i18n[s];
    return s;
};

if (!window.console) {
    var fK = function(){};
    window.console = {
        log:      fK,
        info:     fK,
        warn:     fK,
        group:    fK,
        groupEnd: fK,
        dir:      fK
};
}
