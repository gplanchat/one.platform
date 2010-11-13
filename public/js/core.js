$.extend({one: {}});

var $$ = function(identifier) {
  return document.getElementById(identifier);
};

(function(ns){
    ns.loadFile = function(library) {
        document.write('<script type="text/javascript" src="'+library+'"></script>')
        };

    ns.emptyFunction = function(){
        };

    ns.Class = {};

    ns.$A = function(){
        };

    ns.Class.addMethods = function(object, methodList){
        if (arguments[1] === undefined) {
            object = this;
            methodList = arguments[0];
        }
        for (var method in methodList) {
            object[method] = methodList[method];
        }

        return object;
        };

    ns.Class.classname = function(){
        var object = arguments[0] || this;
        if (object && object.constructor && object.constructor.toString) {
            var matches = object.constructor.toString().match(/function\s+(\w+)/);
            if (matches && matches.length == 2) {
                return matches[1];
            }
        }
        return false;
        };

    ns.Class.extend = function(parentClass, object) {
        object.parent = parentClass;
        for (var method in parentClass) {
            if (object[method] === undefined) {
                object[method] = parentClass[method];
            }
        }

        return object;
        };

    ns.Class.create = function(methodList) {
        var Class = function (){
            ns.Class.addMethods(this, ns.Class.Methods);
            ns.Class.addMethods(this, methodList);

            this.__construct.apply(this, arguments);

            return this;
            };

        return Class;
        };

    ns.Class.Methods = {
        __construct: ns.emptyFunction,
        addMethods: ns.Class.addMethods,
        classname: ns.Class.classname
        };

    ns.Object = new Object();

    ns.Class.extend(ns.Object, ns.Class.Methods);

    ns.Object.extend = function(object) {
        ns.Class.extend(this.prototype, object);
        };

    ns.Exception = ns.Class.create({
        __construct: function(message, code){this.message = message;this.code = code || -1;},
        getMessage: function(){return this.message;},
        getCode: function(){return this.code;}
        });

    ns.Url = ns.Class.create({
        __construct: function(path) {
            path = path || '';

            this._baseUrl    = '';
            this._prefix     = '';
            this._controller = '';
            this._action     = '';
            this._params     = [];

            var match = null;

            match = $('script[src$=js/core.js]').attr('src').match('^(.*)js\/core.js$');
            this._baseUrl = (match[1] !== undefined ? match[1] : '/' ) + path;

            match = document.location.pathname.match('^' + this._baseUrl + '(.*)$');
            var route = match[1] || ''; 

            match = route.match('([^/]+)(?:/?(.*))');
            this._prefix = (match !== null && match[1] !== undefined) ? match[1] : 'core';

            match = match[2] !== undefined ? match[2].match('([^/]+)(?:/?(.*))') : null;
            this._controller = (match !== null && match[1] !== undefined) ? match[1] : '';

            match = match[2] !== undefined ? match[2].match('([^/]+)(?:/?(.*))') : null;
            this._action = (match !== null && match[1] !== undefined) ? match[1] : '';
            },
        toString: function(options) {
            options = options || [];

            var baseUrl    = this._baseUrl;
            var prefix     = this._prefix;
            var controller = this._controller;
            var action     = this._action;
            var params     = this._params;

            var query = '';
            $(options).each(function(index, element){
                switch (element.name) {
                case 'base-url':
                    baseUrl = element.value;
                    break;

                case 'prefix':
                    prefix = element.value;
                    break;

                case 'controller':
                    controller = element.value;
                    break;

                case 'action':
                    action = element.value;
                    break;

                default:
                    params[params.length] = (element);
                    break;
                }
                });

            $(params).each(function(index, element){
                var chr = '&';
                if (index === 0) {
                    chr = '?';
                }
                query += chr + element.name + '=' + element.value;
                });
            var url = baseUrl
            if (prefix !== '') {
                url += '/' + prefix;
            }
            if (controller !== '') {
                url += '/' + controller;
            }
            if (action !== '') {
                url += '/' + action;
            }
            return url + query;
            }
        });
    })($.one);