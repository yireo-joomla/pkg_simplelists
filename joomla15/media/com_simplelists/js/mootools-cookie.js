/**
 * Joomla! component Simple Lists
 *
 * @author Yireo
 * @copyright Copyright (C) 2011
 * @link http://www.yireo.com/
 */

var SLCookie = new Class({

    setOptions: function(){
        //this.options = $merge.run([this.options].extend(arguments));
        if (!this.addEvent) return this;
        for (var option in this.options){
            if ($type(this.options[option]) != 'function' || !(/^on[A-Z]/).test(option)) continue;
            this.addEvent(option, this.options[option]);
            delete this.options[option];
        }
        return this;
    },

    options: {
        path: false,
        domain: false, 
        duration: false,
        secure: false,
        document: document
    },

    initialize: function(key, options){
        this.key = key;
        this.setOptions(options);
    },

    write: function(value){
        value = encodeURIComponent(value);
        if (this.options.domain) value += '; domain=' + this.options.domain;
        if (this.options.path) value += '; path=' + this.options.path;
        if (this.options.duration){
            var date = new Date();
            date.setTime(date.getTime() + this.options.duration * 24 * 60 * 60 * 1000);
            value += '; expires=' + date.toGMTString();
        }
        if (this.options.secure) value += '; secure';
        this.options.document.cookie = this.key + '=' + value;
        return this;
    },

    read: function(){
        var value = this.options.document.cookie.match('(?:^|;)\\s*' + this.key.escapeRegExp() + '=([^;]*)');
        return (value) ? decodeURIComponent(value[1]) : null;
    },

    dispose: function(){
        new SLCookie(this.key, $merge(this.options, {duration: -1})).write('');
        return this;
    }

});

SLCookie.write = function(key, value, options){
    return new SLCookie(key, options).write(value);
};

SLCookie.read = function(key){
    return new SLCookie(key).read();
};

SLCookie.dispose = function(key, options){
    return new SLCookie(key, options).dispose();
};


