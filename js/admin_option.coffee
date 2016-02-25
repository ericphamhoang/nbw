#To be included in Admin option page
window.NBW or= {}
window.NBW.Admin or= {
  init: (ele, model)->
    self = this
    self.btn_save = ele.find("#btn-save")
    events = {
      save_clicked: (e)->
        e.preventDefault()
        self._save_changes()
    }
    model.events = events
    self.model = kendo.observable model
    kendo.bind ele, self.model

  _save_changes: ()->
    self = this
    self.btn_save.prop("disabled", "disabled")
    self.btn_save.html('<i class="fa fa-fw fa-spin fa-circle-o-notch"></i>&emsp;Saving...')
    Options = self.model.get("Options").toJSON()
    for k, v of Options
      if v instanceof Date
        Options[k] = date_format "Y-m-d H:i:s", v

    data =
      action: 'NBW_option_update'
      options: Options

    jQuery.ajax
      url: ajax_object.ajax_url
      data: data
      type: "POST"
      dataType: "json"
      success: ()->
        self.btn_save.prop("disabled", false)
        self.btn_save.html('<i class="fa fa-fw fa-save"></i>&emsp;Save changes')
        alert("Settings saved")
      error: ()->
        alert("There was an error occurred")
}

window.date_format = (format, timestamp) ->
  that = this
  jsdate = undefined
  f = undefined

  # Keep this here (works, but for code commented-out below for file size reasons)
  # var tal= [];
  txt_words = [
    "Sun"
    "Mon"
    "Tues"
    "Wednes"
    "Thurs"
    "Fri"
    "Satur"
    "January"
    "February"
    "March"
    "April"
    "May"
    "June"
    "July"
    "August"
    "September"
    "October"
    "November"
    "December"
  ]

  # trailing backslash -> (dropped)
  # a backslash followed by any character (including backslash) -> the character
  # empty string -> empty string
  formatChr = /\\?(.?)/g
  formatChrCb = (t, s) ->
    (if f[t] then f[t]() else s)

  _pad = (n, c) ->
    n = String(n)
    n = "0" + n  while n.length < c
    n

  f =

    # Day
    d: ->

      # Day of month w/leading 0; 01..31
      _pad f.j(), 2

    D: ->

      # Shorthand day name; Mon...Sun
      f.l().slice 0, 3

    j: ->

      # Day of month; 1..31
      jsdate.getDate()

    l: ->

      # Full day name; Monday...Sunday
      txt_words[f.w()] + "day"

    N: ->

      # ISO-8601 day of week; 1[Mon]..7[Sun]
      f.w() or 7

    S: ->

      # Ordinal suffix for day of month; st, nd, rd, th
      j = f.j()
      i = j % 10
      i = 0  if i <= 3 and parseInt((j % 100) / 10, 10) is 1
      [
        "st"
        "nd"
        "rd"
      ][i - 1] or "th"

    w: ->

      # Day of week; 0[Sun]..6[Sat]
      jsdate.getDay()

    z: ->

      # Day of year; 0..365
      a = new Date(f.Y(), f.n() - 1, f.j())
      b = new Date(f.Y(), 0, 1)
      Math.round (a - b) / 864e5


  # Week
    W: ->

      # ISO-8601 week number
      a = new Date(f.Y(), f.n() - 1, f.j() - f.N() + 3)
      b = new Date(a.getFullYear(), 0, 4)
      _pad 1 + Math.round((a - b) / 864e5 / 7), 2


  # Month
    F: ->

      # Full month name; January...December
      txt_words[6 + f.n()]

    m: ->

      # Month w/leading 0; 01...12
      _pad f.n(), 2

    M: ->

      # Shorthand month name; Jan...Dec
      f.F().slice 0, 3

    n: ->

      # Month; 1...12
      jsdate.getMonth() + 1

    t: ->

      # Days in month; 28...31
      (new Date(f.Y(), f.n(), 0)).getDate()


  # Year
    L: ->

      # Is leap year?; 0 or 1
      j = f.Y()
      j % 4 is 0 & j % 100 isnt 0 | j % 400 is 0

    o: ->

      # ISO-8601 year
      n = f.n()
      W = f.W()
      Y = f.Y()
      Y + ((if n is 12 and W < 9 then 1 else (if n is 1 and W > 9 then -1 else 0)))

    Y: ->

      # Full year; e.g. 1980...2010
      jsdate.getFullYear()

    y: ->

      # Last two digits of year; 00...99
      f.Y().toString().slice -2


  # Time
    a: ->

      # am or pm
      (if jsdate.getHours() > 11 then "pm" else "am")

    A: ->

      # AM or PM
      f.a().toUpperCase()

    B: ->

      # Swatch Internet time; 000..999
      H = jsdate.getUTCHours() * 36e2

      # Hours
      i = jsdate.getUTCMinutes() * 60

      # Minutes
      # Seconds
      s = jsdate.getUTCSeconds()
      _pad Math.floor((H + i + s + 36e2) / 86.4) % 1e3, 3

    g: ->

      # 12-Hours; 1..12
      f.G() % 12 or 12

    G: ->

      # 24-Hours; 0..23
      jsdate.getHours()

    h: ->

      # 12-Hours w/leading 0; 01..12
      _pad f.g(), 2

    H: ->

      # 24-Hours w/leading 0; 00..23
      _pad f.G(), 2

    i: ->

      # Minutes w/leading 0; 00..59
      _pad jsdate.getMinutes(), 2

    s: ->

      # Seconds w/leading 0; 00..59
      _pad jsdate.getSeconds(), 2

    u: ->

      # Microseconds; 000000-999000
      _pad jsdate.getMilliseconds() * 1000, 6


  # Timezone
    e: ->

      # Timezone identifier; e.g. Atlantic/Azores, ...
      # The following works, but requires inclusion of the very large
      # timezone_abbreviations_list() function.
      #              return that.date_default_timezone_get();
      #
      throw "Not supported (see source code of date() for timezone on how to add support)"
      return

    I: ->

      # DST observed?; 0 or 1
      # Compares Jan 1 minus Jan 1 UTC to Jul 1 minus Jul 1 UTC.
      # If they are not equal, then DST is observed.
      a = new Date(f.Y(), 0)

      # Jan 1
      c = Date.UTC(f.Y(), 0)

      # Jan 1 UTC
      b = new Date(f.Y(), 6)

      # Jul 1
      # Jul 1 UTC
      d = Date.UTC(f.Y(), 6)
      (if ((a - c) isnt (b - d)) then 1 else 0)

    O: ->

      # Difference to GMT in hour format; e.g. +0200
      tzo = jsdate.getTimezoneOffset()
      a = Math.abs(tzo)
      ((if tzo > 0 then "-" else "+")) + _pad(Math.floor(a / 60) * 100 + a % 60, 4)

    P: ->

      # Difference to GMT w/colon; e.g. +02:00
      O = f.O()
      O.substr(0, 3) + ":" + O.substr(3, 2)

    T: ->

      # Timezone abbreviation; e.g. EST, MDT, ...
      # The following works, but requires inclusion of the very
      # large timezone_abbreviations_list() function.
      #              var abbr, i, os, _default;
      #      if (!tal.length) {
      #        tal = that.timezone_abbreviations_list();
      #      }
      #      if (that.php_js && that.php_js.default_timezone) {
      #        _default = that.php_js.default_timezone;
      #        for (abbr in tal) {
      #          for (i = 0; i < tal[abbr].length; i++) {
      #            if (tal[abbr][i].timezone_id === _default) {
      #              return abbr.toUpperCase();
      #            }
      #          }
      #        }
      #      }
      #      for (abbr in tal) {
      #        for (i = 0; i < tal[abbr].length; i++) {
      #          os = -jsdate.getTimezoneOffset() * 60;
      #          if (tal[abbr][i].offset === os) {
      #            return abbr.toUpperCase();
      #          }
      #        }
      #      }
      #
      "UTC"

    Z: ->

      # Timezone offset in seconds (-43200...50400)
      -jsdate.getTimezoneOffset() * 60


  # Full Date/Time
    c: ->

      # ISO-8601 date.
      "Y-m-d\\TH:i:sP".replace formatChr, formatChrCb

    r: ->

      # RFC 2822
      "D, d M Y H:i:s O".replace formatChr, formatChrCb

    U: ->

      # Seconds since UNIX epoch
      jsdate / 1000 | 0

  @date = (format, timestamp) ->
    that = this
    # Not provided
    # JS Date()
    jsdate = ((if timestamp is `undefined` then new Date() else (if (timestamp instanceof Date) then new Date(timestamp) else new Date(timestamp * 1000)))) # UNIX timestamp (auto-convert to int)
    format.replace formatChr, formatChrCb

  @date format, timestamp