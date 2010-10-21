(function(ns){
    ns.Security = {};

    ns.Security.BYTE_LENGTH = 8;

    /**
     * @var Security.Bytemap bytemap
     * @var integer length
     * @return Security.Bytemap
     */
    ns.Security.random = function(bytemap, length) {
        for (var i = 0; i < length; i++) {
            bytemap.set(i, Math.floor(Math.random() * (1 << ns.Security.BYTE_LENGTH)));
        }
        return bytemap;
        };

    ns.Security.Bytemap = ns.Class.create({
        __construct: function(size) {
            if (arguments[0] === undefined) {
                this.length = 0;
                this.data = [];
            } else if (typeof(arguments[0]) === 'number') {
                this.length = arguments[0];
                this.data = new Array(size >> 2);
            } else if (arguments[0].classname() == 'Bytemap'){
                this.copy(arguments[0]);
            } else {
                throw new ns.Exception("Wrong parameter list.");
            }

            for (var i = 0; i < (size >> 2); i++) {
                this.data[i] = 0;
            }
            },

        count: function() {
            this.data.length;
            },

        set: function(offset, value) {
            this.unset(offset);
            this.data[Math.floor(offset / 4)] |= (value & ((1 << 8) - 1)) << ((offset % 4) * ns.Security.BYTE_LENGTH);
            //TODO: update this.length
            return this;
            },

        get: function(offset) {
            if (offset > this.length) {
                return undefined;
            }
            var mask = ((1 << ns.Security.BYTE_LENGTH) - 1) << (offset % 4) * ns.Security.BYTE_LENGTH;
            return ((this.data[Math.floor(offset / 4)] & mask) >> ((offset % 4) * ns.Security.BYTE_LENGTH)) & 0xFF;
            },

        has: function(offset) {
            if (offset > this.length) {
                return false;
            }
            return true;
            },

        unset: function(offset) {
            if (offset > this.length) {
                return undefined;
            }
            var mask = ((1 << 8) - 1) << ((offset % 4) * ns.Security.BYTE_LENGTH);
            var eraser = this.data[Math.floor(offset / 4)] & mask;
            this.data[Math.floor(offset / 4)] ^= eraser;
            //TODO: update this.length
            return this;
            },

        clone: function(){
            object = arguments[0] || new ns.Security.Bytemap(this.size);
            for (var i = 0; i < this.data.length; i++) {
                object.data[i] = this.data[i];
            }
            return object;
            },

        copy: function(object){
            for (var i = 0; i < this.data.length; i++) {
                object.data[i] = this.data[i];
            }
            return this;
            },
            
        append: function(bytemap) {
            for (var i = 0; i < bytemap.length; i++) {
                this.set(this.length, bytemap.get(i));
                this.length++;
            }

            return this;
            },

        random: function(){
            return ns.Security.random(this, this.length);
            },

        toString: function(outputFilter){
            outputFilter = outputFilter || ns.Security.Bytemap.Filter.Output.base64;
            return outputFilter(this);
            }
        });

    ns.Security.Bytemap.Filter = {};
    ns.Security.Bytemap.Filter.Input = {};
    ns.Security.Bytemap.Filter.Output = {};

    ns.Security.Bytemap.Filter.Output.base64 = function(bytemap) {
        var string = '';
        var CHARACTER_SET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        var CHARACTER_PAD = '=';
        var block = 0;

        var char = new Array(3);

        for (var i = 0; i < bytemap.length; i += 3) {
            block = 0;
            block |= ((bytemap.get(i + 0)) & 0xFF) << 16;
            block |= ((bytemap.get(i + 1)) & 0xFF) << 8;
            block |= ((bytemap.get(i + 2)) & 0xFF);

            string += CHARACTER_SET.charAt((block & 0x00FC0000) >> 18);
            string += CHARACTER_SET.charAt((block & 0x0003F000) >> 12);
            string += ((i + 1) < (bytemap.length) ? CHARACTER_SET.charAt((block & 0x00000FC0) >> 6) : CHARACTER_PAD);
            string += ((i + 2) < (bytemap.length) ? CHARACTER_SET.charAt((block & 0x0000003F)) : CHARACTER_PAD);
        }

        return string;
        };

    ns.Security.Bytemap.Filter.Input.base64 = function(string) {
        var CHARACTER_SET = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        var CHARACTER_PAD = '=';

        var length = string.length / 4 * 3;
        if (string[string.length - 1] == CHARACTER_PAD) length--;
        if (string[string.length - 2] == CHARACTER_PAD) length--;

        var bytemap = new ns.Security.Bytemap(length);
        var block = 0;
        var binChars = {};
        for (var i = 0; i < 64; i++) {
            binChars[CHARACTER_SET[i]] = i;
        }

        for (var i = 0, j = 0; i < string.length; i += 4) {
            block = (binChars[string[i + 0]] & 0x3F) << 18;
            block += (binChars[string[i + 1]] & 0x3F) << 12;
            if (string[i + 2] != CHARACTER_PAD) {
                block += (binChars[string[i + 2]] & 0x3F) << 6;
            }
            if (string[i + 3] != CHARACTER_PAD) {
                block += (binChars[string[i + 3]] & 0x3F) << 0;
            }

            bytemap.set(j++, (block & 0x00FF0000) >> 16);
            if (j <= bytemap.length) {
                bytemap.set(j++, (block & 0x0000FF00) >> 8);
            }
            if (j <= bytemap.length) {
                bytemap.set(j++, (block & 0x000000FF) >> 0);
            }
        }

        return bytemap;
        };

    ns.Security.Bytemap.Filter.Output.hex = function(bytemap) {
        var string = '';
        var CHARACTER_SET = "0123456789ABCDEF";

        for (var i = 0; i < bytemap.length; i++) {
            string += CHARACTER_SET.charAt(((bytemap.get(i) & 0xF0) >> 4));
            string += CHARACTER_SET.charAt(bytemap.get(i) & 0xF);
        }
        return string;
        };

    ns.Security.Bytemap.Filter.Input.hex = function(string) {
        var CHARACTER_SET = "0123456789ABCDEF";
        var bytemap = new ns.Security.Bytemap(Math.floor(string.length / 2));
        var binChars = {};
        for (var i = 0; i < 16; i++) {
            binChars[CHARACTER_SET[i]] = i;
        }

        for (var i = 0; i < string.length; i += 2) {
            bytemap.set(i / 2, ((binChars[string[i]] & 0xF) << 4) | (binChars[string[i + 1]] & 0xF));
        }
        return bytemap;
        };

    ns.Security.Bytemap.Filter.Input.raw = function(string) {
        var hash = new ns.Security.Bytemap(string.length);

        for (var i = 0; i < string.length; i++) {
            hash.set(i, (string.charCodeAt(i)));
        }
        return hash;
        };

    ns.Security.Bytemap.Filter.Output.raw = function(hash) {
        var string = '';

        for (var i = 0; i < hash.length; i++) {
            string += String.fromCharCode(hash.get(i));
        }
        return string;
        };

    ns.Security.Bytemap.Hash = {};

    // FIXME:
    ns.Security.Bytemap.Hash.sha256 = function(message) {
        var CHARACTER_LENGTH = ns.Security.BYTE_LENGTH;

        var _add = function (a, b) {return ((a >> 16) + (b >> 16) + ((a & 0xFFFF) + (b & 0xFFFF) >> 16) << 16) | ((a & 0xFFFF) + (b & 0xFFFF) & 0xFFFF);};
        var S = function (x, n) {return (x >>> n) | (x << (32 - n));};
        var R = function (x, n) {return (x >>> n);};

        var Ch = function (x, y, z) {return (x & y) ^ ((~x) & z);};
        var Maj = function (x, y, z) {return (x & y) ^ (x & z) ^ (y & z);};

        var s0 = function(x) {return S(x, 2) ^ S(x, 13) ^ S(x, 22);};
        var s1 = function(x) {return S(x, 6) ^ S(x, 11) ^ S(x, 25);};
        var g0 = function(x) {return S(x, 7) ^ S(x, 18) ^ R(x, 3);};
        var g1 = function(x) {return S(x, 17) ^ S(x, 19) ^ R(x, 10);};

        var _core = function(message){
            var k = [
                0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5,
                0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5,
                0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3,
                0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174,
                0xE49B69C1, 0xEFBE4786, 0x0FC19DC6, 0x240CA1CC,
                0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA,
                0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7,
                0xC6E00BF3, 0xD5A79147, 0x06CA6351, 0x14292967,
                0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13,
                0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85,
                0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3,
                0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070,
                0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5,
                0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3,
                0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208,
                0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2
                ];
            var hash = [
                0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A,
                0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19
                ];
            var w = new Array(64);
            var stack = new Array(8);
            var t1, t2;
            var m = [];
            var mask = (1 << CHARACTER_LENGTH) - 1;

            for (var i = 0; i < (message.length * CHARACTER_LENGTH); i += CHARACTER_LENGTH) {
                m[i >> 5] |= (message.get(i / CHARACTER_LENGTH) & mask) << (24 - i % 32);
            }
            m[(message.length * CHARACTER_LENGTH) >> 5] |= 0x80 << (24 - (message.length * CHARACTER_LENGTH) % 32);
            m[(((message.length * CHARACTER_LENGTH) + 64 >> 9) << 4) + 15] = (message.length * CHARACTER_LENGTH);

            for (var i = 0; i < m.length; i += 16) {
                for (var j = 0; j < 8; j++) {
                    stack[j] = hash[j];
                }
                for (var j = 0; j < 64; j++) {
                    if (j < 16) {
                        w[j] = m[j + i];
                    } else {
                        w[j] = _add(_add(_add(g1(w[j - 2]), w[j - 7]), g0(w[j - 15])), w[j - 16])
                    }
                    t1 = _add(_add(_add(_add(stack[7], s1(stack[4])), Ch(stack[4], stack[5], stack[6])), k[j]), w[j]);
                    t2 = _add(s0(stack[0]), Maj(stack[0], stack[1], stack[2]));

                    stack[7] = stack[6];
                    stack[6] = stack[5];
                    stack[5] = stack[4];
                    stack[4] = _add(stack[3], t1);
                    stack[3] = stack[2];
                    stack[2] = stack[1];
                    stack[1] = stack[0];
                    stack[0] = _add(t1, t2);
                }
                for (var j = 0; j < 8; j++) {
                    hash[j] = _add(stack[j], hash[j]);
                }
            }
            var bytemap = new ns.Security.Bytemap(32);
            for (var i = 0; i < 8; i++) {
                bytemap.set((i * 4) + 0, (hash[i] & 0xFF000000) >> 24);
                bytemap.set((i * 4) + 1, (hash[i] & 0x00FF0000) >> 16);
                bytemap.set((i * 4) + 2, (hash[i] & 0x0000FF00) >> 8);
                bytemap.set((i * 4) + 3, (hash[i] & 0x000000FF) >> 0);
            }
            return bytemap;
            };

        return _core(message);
        };

    ns.Security.sha256 = function(message, raw) {
        var CHARACTER_LENGTH = ns.Security.BYTE_LENGTH;

        var _add = function (a, b) {return ((a >> 16) + (b >> 16) + ((a & 0xFFFF) + (b & 0xFFFF) >> 16) << 16) | ((a & 0xFFFF) + (b & 0xFFFF) & 0xFFFF);};
        var S = function (x, n) {return (x >>> n) | (x << (32 - n));};
        var R = function (x, n) {return (x >>> n);};

        var Ch = function (x, y, z) {return (x & y) ^ ((~x) & z);};
        var Maj = function (x, y, z) {return (x & y) ^ (x & z) ^ (y & z);};

        var s0 = function(x) {return S(x, 2) ^ S(x, 13) ^ S(x, 22);};
        var s1 = function(x) {return S(x, 6) ^ S(x, 11) ^ S(x, 25);};
        var g0 = function(x) {return S(x, 7) ^ S(x, 18) ^ R(x, 3);};
        var g1 = function(x) {return S(x, 17) ^ S(x, 19) ^ R(x, 10);};

        var _core = function(message){
            var k = [
                0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5,
                0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5,
                0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3,
                0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174,
                0xE49B69C1, 0xEFBE4786, 0x0FC19DC6, 0x240CA1CC,
                0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA,
                0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7,
                0xC6E00BF3, 0xD5A79147, 0x06CA6351, 0x14292967,
                0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13,
                0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85,
                0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3,
                0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070,
                0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5,
                0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3,
                0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208,
                0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2
                ];
            var hash = [
                0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A,
                0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19
                ];
            var w = new Array(64);
            var stack = new Array(8);
            var t1, t2;
            var m = [];
            var mask = (1 << CHARACTER_LENGTH) - 1;

            for (var i = 0; i < (message.length * CHARACTER_LENGTH); i += CHARACTER_LENGTH) {
                m[i >> 5] |= (message.charCodeAt(i / CHARACTER_LENGTH) & mask) << (24 - i % 32);
            }
            m[(message.length * CHARACTER_LENGTH) >> 5] |= 0x80 << (24 - (message.length * CHARACTER_LENGTH) % 32);
            m[(((message.length * CHARACTER_LENGTH) + 64 >> 9) << 4) + 15] = (message.length * CHARACTER_LENGTH);

            for (var i = 0; i < m.length; i += 16) {
                for (var j = 0; j < 8; j++) {
                    stack[j] = hash[j];
                }
                for (var j = 0; j < 64; j++) {
                    if (j < 16) {
                        w[j] = m[j + i];
                    } else {
                        w[j] = _add(_add(_add(g1(w[j - 2]), w[j - 7]), g0(w[j - 15])), w[j - 16])
                    }
                    t1 = _add(_add(_add(_add(stack[7], s1(stack[4])), Ch(stack[4], stack[5], stack[6])), k[j]), w[j]);
                    t2 = _add(s0(stack[0]), Maj(stack[0], stack[1], stack[2]));

                    stack[7] = stack[6];
                    stack[6] = stack[5];
                    stack[5] = stack[4];
                    stack[4] = _add(stack[3], t1);
                    stack[3] = stack[2];
                    stack[2] = stack[1];
                    stack[1] = stack[0];
                    stack[0] = _add(t1, t2);
                }
                for (var j = 0; j < 8; j++) {
                    hash[j] = _add(stack[j], hash[j]);
                }
            }
            var bytemap = '';
            for (var i = 0; i < 8; i++) {
                bytemap += String.fromCharCode((hash[i] & 0xFF000000) >> 24);
                bytemap += String.fromCharCode((hash[i] & 0x00FF0000) >> 16);
                bytemap += String.fromCharCode((hash[i] & 0x0000FF00) >>  8);
                bytemap += String.fromCharCode((hash[i] & 0x000000FF) >>  0);
            }
            return bytemap;
            };

        if (raw === true) {
            return _core(message);
        } else {
            return ns.Security.Bytemap.Filter.Input.raw(_core(message)).toString(ns.Security.Bytemap.Filter.Output.base64);
        }
        };

    ns.Security.Base64 = {};

    ns.Security.Base64.decode = function(message) {
        ns.Security.Bytemap.Filter.Input.base64(message).toString(ns.Security.Bytemap.Filter.Output.raw)
        };

    ns.Security.Base64.encode = function(message) {
        ns.Security.Bytemap.Filter.Input.raw(message).toString(ns.Security.Bytemap.Filter.Output.base64)
        };
    })($.one)