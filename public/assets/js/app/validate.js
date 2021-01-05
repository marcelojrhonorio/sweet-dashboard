sweet.validate = {

    is: function(type, obj) {
        return Object.prototype.toString.call(obj) === "[object " + type + "]";
    },

    isNull: function(x) {
        if ((typeof x === 'undefined') || (x === null)) {
            return true;
        }
        return false;
    },

    isEmpty: function(x) {
        return ((x === '') ? true : false);
    },

    isObject: function(x) {
        if (!this.isNull(x)) {
            if (x.constructor === Object) {
                return true;
            }
        }
        return false;
    },

    isFunction: function(x) {
        if (!this.isNull(x)) {
            if (x instanceof Function) {
                return true;
            }
        }
        return false;
    },

    isBoolean: function(x) {
        if (!this.isNull(x)) {
            if (x.constructor === Boolean) {
                return true;
            }
        }
        return false;
    },

    isArray: function(x) {
        //return Object.prototype.toString.call(o) === "[object Array]";
        return ($.isArray(x) ? true : false);
    },

    isString: function(x) {
        if (!this.isNull(x)) {
            if (x.constructor === String) {
                return true;
            }
        }
        return false;
    },

    isDate: function(x) {
        if (!this.isNull(x)) {
            if (x.constructor === Date) {
                return true;
            }
        }
        return false;

    },

    isNumber: function(x) {
        if (!this.isNull(x)) {
            if (!isNaN(x) && (x.constructor !== Boolean) && (x.constructor !== Array)) {
                return true;
            }
        }
        return false;
    },

    isInteger: function(x) {
        if (!this.isNull(x)) {
            if (this.isNumber(x)) {
                if ((x % 1) === 0) {
                    return true;
                }
            }
        }
        return false;
    }
};