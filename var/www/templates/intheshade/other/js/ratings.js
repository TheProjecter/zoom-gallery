/**
 * RabidRatings - Simple and Pretty Ratings for Everyone
 * JavaScript functionality requires MooTools version 1.11. <http://www.mootools.net>
 * 
 * Full package available at <http://widgets.rabidlabs.net/ratings>.
 *
 * NOTE: The included JavaScript WILL ONLY WORK WITH MOOTOOLS.  It will not work if any other JavaScript
 * framework is present on the page.
 *
 * Current MooTools version: 1.11
 *
 * @author Michelle Steigerwalt <http://www.msteigerwalt.com>
 * @copyright 2007 Michelle Steigerwalt
 */

var RabidRatings = new Class({
	
	options: {
		url: null,
		activeColor: '#ffa800',
		votedColor: '#0069ff',
		fillColor: '#ff0000',
		leftMargin: 0,  /* The width in pixels of the margin before the stars. */
		starWidth: 17,  /* The width in pixels of each star. */
		starMargin: 4,  /* The width in pixels between each star. */
		scale: 5,       /* It's a five-star scale. */
		snap: 1         /* Will snap to the nearest star (can be made a decimal, too). */
	},
	
	initialize: function(options) {
		
		this.setOptions(options);
		var activeColor = this.options.activeColor;
		var votedColor  = this.options.votedColor;
		var fillColor   = this.options.fillColor;

		$$('.rabidRating').each(function(el) {
		if (!window.ie6) {
			el.id = el.getAttribute('id');
			el.offset = el.getPosition().x;
			el.fill = el.getElement('.ratingFill');
			el.starPercent = this.getStarPercent(el.id);
			el.ratableId   = this.getRatableId(el.id);
			this.fillVote(el.starPercent, el);
			el.currentFill = this.getFillPercent(el.starPercent);
			el.fillColor   = fillColor;
			el.activeColor = activeColor;
			el.votedColor  = votedColor;
			el.colorFx = new Fx.Style(el.fill, 'background-color', {wait: false});
			el.widthFx = new Fx.Style(el.fill, 'width');
			el.mouseCrap = function(e) { 
				var fill = e.clientX - el.offset;
				var fillPercent = this.getVotePercent(fill);
				var step = (100 / this.options.scale) * this.options.snap;
				var nextStep = Math.floor(fillPercent / step) + 1;
				this.fillVote(nextStep * step, el);
			}.bind(this);
			el.addEvent('mouseenter', function(e) { 
				el.colorFx.start(el.activeColor); 
				el.addEvent('mousemove', el.mouseCrap);
			});
			el.addEvent('mouseleave', function(e) {
				el.removeEvent(el.mouseCrap);
				el.colorFx.start(el.activeColor, el.fillColor);
				el.widthFx.start(el.currentFill);
			});
			el.addEvent('click', function(e) {
				el.currentFill = el.newFill;
				el.colorFx.start(votedColor);
				el.removeEvents();
				el.addClass('ratingVoted');
				var votePercent = this.getVotePercent(el.newFill);
				if (this.options.url != null) {
					var ajax = new Ajax(this.options.url, 
						{data: {vote:votePercent,id:el.ratableId}}).request();	
				}
			}.bind(this));
		} else {
			/* Makes the ratings viewable for IE6 users, but not interactive. */
			var newEl = new Element('div').setText(el.getElement('.ratingStars').getText());
			newEl.injectAfter(el);
			el.remove();
		}
		}.bind(this));
	},

	fillVote: function(percent, el) {
		el.newFill = this.getFillPercent(percent);
		if (this.getVotePercent(el.newFill) > 100) { el.newFill = this.getFillPercent(100); }
		el.fill.setStyle('width', el.newFill);
	},

	getStarPercent: function(id) {
		var stars = id.match(/(\d*)-(\d*\.?\d+)_(\d*\.?\d+)$/);
		var ratableId = stars[1].toFloat();
		var score = stars[2].toFloat();
		var scale = stars[3].toFloat();
		var percent =  (score / scale) * 100;
		return percent;
	},

	getFillPercent: function (starPercent) {
		return (starPercent/100)*((this.options.starWidth+this.options.starMargin)*this.options.scale) + this.options.leftMargin;
	},

	getVotePercent: function(divPosition) {
		var starsWidth = (this.options.starWidth+this.options.starMargin)*this.options.scale;
		var offset = this.options.leftMargin;
		var starPosition = divPosition - this.options.leftMargin;
		var percent = (starPosition / starsWidth * 100).round(2);
		return percent;
	},

	getRatableId: function(id) {
		var stars = id.match(/(\d*)-(\d*\.?\d+)_(\d*\.?\d+)$/);
		return stars[1];
	}

});

RabidRatings.implement(new Options());

window.addEvent('domready', function(e) {
	var rating = new RabidRatings({url:'ratings.php'});
});
