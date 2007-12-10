/**
 * SimpleTabs - Unobtrusive Tabs with Ajax
 * 
 * Simple and clean Tab plugin for MooTools 1.1
 * including support for Ajax content and various
 * custom Events to customise the appearance.
 * 
 * To use the Ajax feature simply use an anchor
 * with an href attribute as entry. The Ajax will
 * inject the response of this url into the tab.
 * 
 * @example
 * 
 *	var tabs = new SimpleTabs($('tab-element'), {
 * 		entrySelector: 'h2.tab-entry'
 *	});
 * 
 * @version		1.0rc0
 * 
 * @see			Events, Options
 * 
 * @license		MIT License
 * @author		Harald Kirschner <mail [at] digitarald.de>
 * @copyright	2007 Author
 */
var SimpleTabs = new Class({

	/**
	 * Options
	 * 
	 * show: Number, default 0: index for the initial selected tab
	 * entrySelector: String, default ".tab-entry". Selector to find the tab elements under the given parent element
	 * classMenu: String, default "tab-menu". className for the ul that hold the tab items
	 * classWrapper: String, default "tab-wrapper". className for the wrapper that holds the container elements
	 * classContainer:  String, default "tab-container". className for the single container elements
	 * onShow: Event. Fires when a container is shown, arguments: (tabElement, containerElement, tabIndex, tabElementOld, containerElementOld, tabIndexOld)
	 * onHide: Event. Fires when a container is hidden, same arguments
	 * onRequest: Event. Fires when Ajax request starts, same arguments
	 * onComplete: Event. Fires when Ajax request is completed successfully, same arguments
	 * onFailure: Event. Fires when a Ajax request fails, same arguments
	 * getContent: Function. Callback to return the tab content element for an entry element, default is Element::getNext()
	 * 
	 */
	options: {
		show: 0,
		entrySelector: '.tab-entry',
		classClear: 'zmg_clear',
		classWrapper: 'tab-wrapper',
		classStripWrapper: 'tab-strip-wrapper',
		classStrip: 'tab-strip',
		classTabRight: 'tab-right',
		classTabLeft: 'tab-left',
		classTabInner: 'tab-inner',
		classTabText: 'tab-text',
		classTabEdge: 'tab-edge',
		classTabHover: 'tab-over',
		classContainer: 'tab-container',
		onShow: function(toggle, container, index) {
			toggle.addClass('tab-selected');
			container.setStyle('display', '');
		},
		onHide: function(toggle, container, index) {
			toggle.removeClass('tab-selected');
			container.setStyle('display', 'none');
		},
		onRequest: function(toggle, container, index) {
			container.addClass('tab-ajax-loading');
		},
		onComplete: function(toggle, container, index) {
			container.removeClass('tab-ajax-loading');
		},
		onFailure: function(toggle, container, index) {
			container.removeClass('tab-ajax-loading');
		},
		getContent: null
	},

	/**
	 * Constructor
	 * 
	 * @param {Element} The parent Element that holds the entry elements
	 * @param {Object} Options
	 */
	initialize: function(el, options) {
		this.setOptions(options);
		this.element = $(el);
		this.selected = null;
		this.build();
	},

	build: function() {
		this.entries = [];
		this.stripWrapper = new Element('div', {'class': this.options.classStripWrapper});
		this.strip = new Element('ul', {'class': this.options.classStrip});
		this.stripEdge = new Element('li', {'class': this.options.classTabEdge});
		this.stripWrapper.adopt(this.strip.adopt(new Element('div', {'class': this.options.classClear}))
		 .adopt(this.stripEdge));
		this.wrapper = new Element('div', {'class': this.options.classWrapper});
		this.element.getElements(this.options.entrySelector).each(function(el) {
			var content = el.href || (this.options.getContent ? this.options.getContent.call(this, el) : el.getNext());
			this.addTab(el.innerHTML, el.title || el.innerHTML, content);
		}, this);
		this.element.empty().adopt(this.stripWrapper).adopt(this.wrapper);
		if (this.entries.length) this.select(this.options.show);
	},

	/**
	 * Add a new tab at the end of the tab strip
	 * 
	 * @param {String} inner Text
	 * @param {String} Title
	 * @param {Element|String} Content Element or URL for Ajax
	 */
	addTab: function(text, title, content, data) {
		if ($type(content) == 'string' && !$(content)) var url = content;
		var container = $(content) || new Element('div');
		
		var fullText = ['<em class="', this.options.classTabLeft, '">',
		  '<span class="', this.options.classTabInner, '"><span class="',
		  this.options.classTabText, '">', text, '</span></span></em>'].join('');
		
		this.entries.push({
			container: container.setStyle('display', 'none').addClass(this.options.classContainer).inject(this.wrapper),
			toggle: new Element('li').adopt(new Element('a', {
				href   : '#',
				title  : title,
				'class': this.options.classTabRight,
				events: {
					click    : this.onClick.bindWithEvent(this, [this.entries.length]),
					mouseover: this.onMouseEnter.bindWithEvent(this, [this.entries.length]),
					mouseout : this.onMouseLeave.bindWithEvent(this, [this.entries.length])
				}
			}).setHTML(fullText)).inject(this.strip),
			url : url  || null,
			data: data || null
		});
		this.strip.appendChild(this.stripEdge);
		this.strip.appendChild(this.strip.getElementsByTagName('div')[0]);
		return this;
	},

	onClick: function(evt, index) {
		evt.stop();
		this.select(index);
	},
	
	onMouseEnter: function(evt, index) {
	    this.entries[index].toggle.addClass(this.options.classTabHover);
    },
    
    onMouseLeave: function(evt, index) {
        this.entries[index].toggle.removeClass(this.options.classTabHover);
    },

	/**
	 * Select the tab via tab-index
	 * 
	 * @param {Number} Tab-index
	 */
	select: function(index) {
		if (this.selected === index || !this.entries[index]) return this;
		var entry = this.entries[index];
		var params = [entry.toggle, entry.container, index];
		if (this.selected !== null) {
			var current = this.entries[this.selected];
			if (this.ajax && this.ajax.running) this.ajax.cancel();
			params.concat([current.toggle, current.container, this.selected]);
			this.fireEvent('onHide', [current.toggle, current.container, this.selected]);
		}
		this.fireEvent('onShow', params);
		if (entry.url && !entry.loaded) {
			this.ajax = new Ajax(entry.url, $merge({
				onRequest: this.fireEvent.pass(['onRequest', params], this),
				onFailure: this.fireEvent.pass(['onFailure', params], this),
				onComplete: function(resp) {
					entry.loaded = true;
					entry.container.empty().setHTML(resp);
					this.fireEvent('onComplete', params);
				}.bind(this)
			}, this.options.ajaxOptions)).request();
		}
		this.selected = index;
		return this;
	}

});

SimpleTabs.implement(new Events, new Options);