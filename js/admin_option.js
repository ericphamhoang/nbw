(function() {
  var _base;

  window.NBW || (window.NBW = {});

  (_base = window.NBW).Admin || (_base.Admin = {
    init: function(ele, model) {
      var events, self;
      self = this;
      self.btn_save = ele.find("#btn-save");
      events = {
        save_clicked: function(e) {
          e.preventDefault();
          return self._save_changes();
        }
      };
      model.events = events;
      self.model = kendo.observable(model);
      return kendo.bind(ele, self.model);
    },
    _save_changes: function() {
      var Options, data, k, self, v;
      self = this;
      self.btn_save.prop("disabled", "disabled");
      self.btn_save.html('<i class="fa fa-fw fa-spin fa-circle-o-notch"></i>&emsp;Saving...');
      Options = self.model.get("Options").toJSON();
      for (k in Options) {
        v = Options[k];
        if (v instanceof Date) {
          Options[k] = date_format("Y-m-d H:i:s", v);
        }
      }
      data = {
        action: 'NBW_option_update',
        options: Options
      };
      return jQuery.ajax({
        url: ajax_object.ajax_url,
        data: data,
        type: "POST",
        dataType: "json",
        success: function() {
          self.btn_save.prop("disabled", false);
          self.btn_save.html('<i class="fa fa-fw fa-save"></i>&emsp;Save changes');
          return alert("Settings saved");
        },
        error: function() {
          return alert("There was an error occurred");
        }
      });
    }
  });

  window.date_format = function(format, timestamp) {
    var f, formatChr, formatChrCb, jsdate, that, txt_words, _pad;
    that = this;
    jsdate = void 0;
    f = void 0;
    txt_words = ["Sun", "Mon", "Tues", "Wednes", "Thurs", "Fri", "Satur", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    formatChr = /\\?(.?)/g;
    formatChrCb = function(t, s) {
      if (f[t]) {
        return f[t]();
      } else {
        return s;
      }
    };
    _pad = function(n, c) {
      n = String(n);
      while (n.length < c) {
        n = "0" + n;
      }
      return n;
    };
    f = {
      d: function() {
        return _pad(f.j(), 2);
      },
      D: function() {
        return f.l().slice(0, 3);
      },
      j: function() {
        return jsdate.getDate();
      },
      l: function() {
        return txt_words[f.w()] + "day";
      },
      N: function() {
        return f.w() || 7;
      },
      S: function() {
        var i, j;
        j = f.j();
        i = j % 10;
        if (i <= 3 && parseInt((j % 100) / 10, 10) === 1) {
          i = 0;
        }
        return ["st", "nd", "rd"][i - 1] || "th";
      },
      w: function() {
        return jsdate.getDay();
      },
      z: function() {
        var a, b;
        a = new Date(f.Y(), f.n() - 1, f.j());
        b = new Date(f.Y(), 0, 1);
        return Math.round((a - b) / 864e5);
      },
      W: function() {
        var a, b;
        a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3);
        b = new Date(a.getFullYear(), 0, 4);
        return _pad(1 + Math.round((a - b) / 864e5 / 7), 2);
      },
      F: function() {
        return txt_words[6 + f.n()];
      },
      m: function() {
        return _pad(f.n(), 2);
      },
      M: function() {
        return f.F().slice(0, 3);
      },
      n: function() {
        return jsdate.getMonth() + 1;
      },
      t: function() {
        return (new Date(f.Y(), f.n(), 0)).getDate();
      },
      L: function() {
        var j;
        j = f.Y();
        return j % 4 === 0 & j % 100 !== 0 | j % 400 === 0;
      },
      o: function() {
        var W, Y, n;
        n = f.n();
        W = f.W();
        Y = f.Y();
        return Y + (n === 12 && W < 9 ? 1 : (n === 1 && W > 9 ? -1 : 0));
      },
      Y: function() {
        return jsdate.getFullYear();
      },
      y: function() {
        return f.Y().toString().slice(-2);
      },
      a: function() {
        if (jsdate.getHours() > 11) {
          return "pm";
        } else {
          return "am";
        }
      },
      A: function() {
        return f.a().toUpperCase();
      },
      B: function() {
        var H, i, s;
        H = jsdate.getUTCHours() * 36e2;
        i = jsdate.getUTCMinutes() * 60;
        s = jsdate.getUTCSeconds();
        return _pad(Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3);
      },
      g: function() {
        return f.G() % 12 || 12;
      },
      G: function() {
        return jsdate.getHours();
      },
      h: function() {
        return _pad(f.g(), 2);
      },
      H: function() {
        return _pad(f.G(), 2);
      },
      i: function() {
        return _pad(jsdate.getMinutes(), 2);
      },
      s: function() {
        return _pad(jsdate.getSeconds(), 2);
      },
      u: function() {
        return _pad(jsdate.getMilliseconds() * 1000, 6);
      },
      e: function() {
        throw "Not supported (see source code of date() for timezone on how to add support)";
      },
      I: function() {
        var a, b, c, d;
        a = new Date(f.Y(), 0);
        c = Date.UTC(f.Y(), 0);
        b = new Date(f.Y(), 6);
        d = Date.UTC(f.Y(), 6);
        if ((a - c) !== (b - d)) {
          return 1;
        } else {
          return 0;
        }
      },
      O: function() {
        var a, tzo;
        tzo = jsdate.getTimezoneOffset();
        a = Math.abs(tzo);
        return (tzo > 0 ? "-" : "+") + _pad(Math.floor(a / 60) * 100 + a % 60, 4);
      },
      P: function() {
        var O;
        O = f.O();
        return O.substr(0, 3) + ":" + O.substr(3, 2);
      },
      T: function() {
        return "UTC";
      },
      Z: function() {
        return -jsdate.getTimezoneOffset() * 60;
      },
      c: function() {
        return "Y-m-d\\TH:i:sP".replace(formatChr, formatChrCb);
      },
      r: function() {
        return "D, d M Y H:i:s O".replace(formatChr, formatChrCb);
      },
      U: function() {
        return jsdate / 1000 | 0;
      }
    };
    this.date = function(format, timestamp) {
      that = this;
      jsdate = (timestamp === undefined ? new Date() : (timestamp instanceof Date ? new Date(timestamp) : new Date(timestamp * 1000)));
      return format.replace(formatChr, formatChrCb);
    };
    return this.date(format, timestamp);
  };

}).call(this);
