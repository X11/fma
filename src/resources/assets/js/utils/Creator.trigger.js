/**
 * Creates an trigger when a specific elements gets loaded into the DOM
 *
 * @param Object
 *
 * @return Object
 */
Creator.trigger = function(options){

    var Trigger = function(){
        this.options = options;

        Triggers.register(this.options.id, this.options.on.bind(this));

        this.init.apply(this, arguments);
    };

    // Shorter name for working on the prototype;
    Trigger.fn = Trigger.prototype;

    // Default init function does nothing, Can be overwriten by giving an 'init' property with the options;
    Trigger.fn.init = function(){};

    // Default do nothing
    Trigger.fn.on = function(){};

    Trigger.fn.done = false;

    if (options){
        for (var key in options){
            Trigger.fn[key] = options[key];
        }
    }

    return (new Trigger(options));
};


/**
 * Holds all the triggers
 *
 */
var Triggers = {

    /**
     * Holds all the triggers
     *
     * @var Object
     */
    triggers: {},

    /**
     * Register an trigger with a callback
     *
     * @param string
     * @param Function
     */
    register: function(id, cb){
        this.triggers[id] = cb;  
    },

    /**
     * Remove an trigger
     *
     * @param string
     */
    unregister: function(id){
        delete this.triggers[id];
    },

    /**
     * Executes a trigger
     *
     * @param string
     */
    execute: function(id){
        if (this.triggers[id]){
            this.triggers[id]();
        }
    },

    /**
     * Shortname for execute
     *
     * @param string
     */
    exec: function(id){
        this.execute(id);
    },

    /**
     * Listens for events when to check for triggers
     */
    listen: function(){
        window.addEventListener('load', this.checkTriggers.bind(this));
    },

    /**
     * Handle the triggers
     */
    checkTriggers: function(){
        var self = this;
        $('[triggers]').each(function(){
            var id = $(this).attr('triggers');
            self.execute(id);
            self.unregister(id);
        });
    }
};

// Bootstrap
Triggers.listen();
