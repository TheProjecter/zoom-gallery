/*
Class: Slider
        Creates a slider with two elements: a knob and a container. Returns the values.
 
Note:
        The Slider requires an XHTML doctype.
 
Arguments:
        element - the knob container
        knob - the handle
        options - see Options below
        maxknob - an optional maximum slider handle
 
Options:
        steps - the number of steps for your slider.
        mode - either 'horizontal' or 'vertical'. defaults to horizontal.
        offset - relative offset for knob position. default to 0.
        knobheight - positions the max slider knob
        spread - steps between the knob and maxknob
 
Events:
        onChange - a function to fire when the value changes.
        onComplete - a function to fire when you're done dragging.
        onTick - optionally, you can alter the onTick behavior, for example displaying an effect of the knob moving to the desired position.
                Passes as parameter the new position.
*/
 
var Slider = new Class({
    options: {
        onChange: Class.empty,
        onComplete: Class.empty,
        onTick: function(pos){
                this.knob.setStyle(this.p, pos);
        },
        onMaxTick: function(pos){
                this.maxknob.setStyle(this.p, pos);
        },
        mode: 'horizontal',
        steps: 100,
        knobheight: 20,
        spread: 6,
        offset: 0
    },
 
    initialize: function(el, knob, options, maxknob){
        this.element = $(el);
        this.knob = $(knob);
        this.setOptions(options);
        this.previousChange = -1;
        this.previousEnd = -1;
        this.step = -1;
        if(maxknob != null)
            this.maxknob = $(maxknob);
        else
            this.element.addEvent('mousedown', this.clickedElement.bindWithEvent(this));
        var mod, offset;
        switch(this.options.mode){
            case 'horizontal':
                this.z = 'x';
                this.p = 'left';
                mod = {'x': 'left', 'y': false};
                offset = 'offsetWidth';
                break;
            case 'vertical':
                this.z = 'y';
                this.p = 'top';
                mod = {'x': false, 'y': 'top'};
                offset = 'offsetHeight';
        }
        this.max = this.element[offset] - this.knob[offset] + (this.options.offset * 2);
        this.half = this.knob[offset]/2;
        this.getPos = this.element['get' + this.p.capitalize()].bind(this.element);
        this.knob.setStyle('position', 'relative').setStyle(this.p, - this.options.offset);
        if(maxknob != null) {
            this.maxPreviousChange = -1;
            this.maxPreviousEnd = -1;
            this.maxstep = this.options.steps;
            this.maxknob.setStyle('position', 'relative').setStyle(this.p, + this.max).setStyle('bottom', this.options.knobheight);
        }
        var lim = {};
        lim[this.z] = [- this.options.offset, this.max - this.options.offset];
        this.drag = new Drag.Base(this.knob, {
            limit: lim,
            modifiers: mod,
            snap: 0,
            onStart: function(){
                this.draggedKnob();
            }.bind(this),
            onDrag: function(){
                this.draggedKnob();
            }.bind(this),
            onComplete: function(){
                this.draggedKnob();
                this.end();
            }.bind(this)
        });
        if(maxknob != null) {  
            this.maxdrag = new Drag.Base(this.maxknob, {
                limit: lim,
                modifiers: mod,
                snap: 0, 
                onStart: function(){
                    this.draggedKnob(1);
                }.bind(this),
                onDrag: function(){
                    this.draggedKnob(1);
                }.bind(this),
                onComplete: function(){
                    this.draggedKnob(1);
                    this.end();
                }.bind(this)
            });
        }
        if (this.options.initialize) this.options.initialize.call(this);
    },
 
        /*
        Property: set
                The slider will get the step you pass.
 
        Arguments:
                step - one integer
        */
 
    set: function(step){
        this.step = step.limit(0, this.options.steps);
        this.checkStep();
        this.end();
        this.fireEvent('onTick', this.toPosition(this.step));
        return this;
    },
 
    clickedElement: function(event){
        var position = event.page[this.z] - this.getPos() - this.half;
        position = position.limit(-this.options.offset, this.max -this.options.offset);
        this.step = this.toStep(position);
        this.checkStep();
        this.end();
        this.fireEvent('onTick', position);
    },
 
    draggedKnob: function(mx){
        if(mx == null) {
            this.step = this.toStep(this.drag.value.now[this.z]);
            this.checkStep();
        }
        else {  
            this.maxstep = this.toStep(this.maxdrag.value.now[this.z]);
            this.checkStep(1);
        }
    },
 
    checkStep: function(mx){
        if(mx == null) {
            if (this.previousChange != this.step){
                this.previousChange = this.step;
            }
        }
        else {  
            if (this.maxPreviousChange != this.maxstep){
                this.maxPreviousChange = this.maxstep;
            }
        }
        if(this.maxknob != null) {
            if(this.step < this.maxstep)
                this.fireEvent('onChange', { minpos: this.step, maxpos: this.maxstep });
            else    
                this.fireEvent('onChange', { minpos: this.maxstep, maxpos: this.step });
        }
        else {  
            this.fireEvent('onChange', this.step);
        }
    },
 
    end: function(){
        if (this.previousEnd !== this.step || (this.maxknob != null && this.maxPreviousEnd != this.maxstep)){
            this.previousEnd = this.step;
            if(this.maxknob != null) {
                this.maxPreviousEnd = this.maxstep;
                if(this.step < this.maxstep)
                    this.fireEvent('onComplete', { minpos: this.step + '', maxpos: this.maxstep + '' });
                else    
                    this.fireEvent('onComplete', { minpos: this.maxstep + '', maxpos: this.step + '' });
            }
            else {  
                this.fireEvent('onComplete', this.step + '');
            }
        }
    },
 
    toStep: function(position){
        return Math.round((position + this.options.offset) / this.max * this.options.steps);
    },
 
    toPosition: function(step){
        return this.max * step / this.options.steps;
    }
 
});
 
Slider.implement(new Events);
Slider.implement(new Options);