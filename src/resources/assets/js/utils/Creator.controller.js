/**
 * Creates a new controller given an data set of elements, events and functions
 *
 * Example controller
 * {
 *      elements: {
 *          "#button": "$button",
 *          ".buttons": "$buttons"
 *      },
 *      events: {
 *          "$button": {
 *              click: function(){},
 *          }
 *      },
 *      init: function(){},
 *      customSuff: function(){}
 * }
 *
 * @param Object
 *
 * @return Object
 */
Creator.controller = function(options){

    // Initialize the controller
    var Controller = function(){
        this.options = options;

        if(this.elements) this._refreshElements();

        this.init.apply(this, arguments);
    };

    // Shorter name for working on the prototype;
    Controller.fn = Controller.prototype;
    Controller.fn.elements = {};

    // Default init function does nothing, Can be overwriten by giving an 'init' property with the options;
    Controller.fn.init = function(){};

    // Shorten the search area for selectors;
    Controller.fn.$ = function(selector){
        return $(selector, this.el);
    };

    // Populates the elements from the options
    Controller.fn._refreshElements = function(){
        for (var key in this.elements){
            // Bind the element to the controller
            this[this.elements[key]] = this.$(key);

            // Do we have events for this element?
            if (this.events && this.events[this.elements[key]]){

                // Add the events for the element
                for (var event in this.events[this.elements[key]]){
                    this[this.elements[key]].on(event, this.events[this.elements[key]][event].bind(this));
                }
            }
        }
    };

    // Include all of the options into the new controller
    if (options){
        for (var key in options){
            Controller.fn[key] = options[key];
        }
    }

    return Controller;
};
