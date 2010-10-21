$.extend({one: {}});

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

    })($.one);