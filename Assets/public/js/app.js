
; (function (global, document) {
  'use strict';

  var xsl = {
    //config: rpconfig || {},
    dv: {
      canTouch: ('ontouchstart' in global),
      clickTap: 'click',
      windowWidth: 0,
      mem: { js: [] },
      jsName: 'app.js',
      jsVersion: null,
      device: (navigator.userAgent.match(/tablet|iPad|playbook/i) ? 'tablet' : (navigator.userAgent.match(/iPhone|android|iPod/i) ? 'mobile' : 'desktop')),
      stage: /devp|localhost/.test(window.location.host.split('.')[0]) ? 'devp.cdn.' : ((/dev|pre/.test(window.location.host.split('.')[0]) ? (window.location.host.split('.')[0] + '.') : '')),
      defaultExec: 'tab, accordion',
    },

    fn: {
      cookie: {
        read: function (name) {
          return new RegExp(name + "=([^;]+)").test(unescape(document.cookie)) ? RegExp.$1 : null;
        },
        save: function (name, value, options) { //options=[day,date]
          options = (typeof options == 'number') ? { expires: options } : options;
          var newcookie = [escape(name) + "=" + escape(value)];
          if (options) {
            if (options.expires) {
              if (typeof options.expires == 'number') {
                var now = new Date();

                //now.setTime(now.getTime()+(options.expires*24*60*60*1000));
                now.setTime(now.getTime() + (3 * 60 * 1000));

                options.expires = now;
              }
              newcookie.push("expires=" + options.expires.toGMTString());
            }
            if (options.path) {
              newcookie.push("path=" + options.path);
            }
            if (options.domain) {
              newcookie.push("domain=" + options.domain);
            }
            if (options.secure) {
              newcookie.push("secure");
            }
          }
          document.cookie = newcookie.join("; ");
        },
      },
      openMeta: function (e, link, id) {
        e.preventDefault();
        const el = document.querySelector(id);
        const last = document.querySelector('.item-meta.active');
        const lastLink = document.querySelector('.accordion-content_inner.active');



        last.classList.remove('active');
        if (lastLink) lastLink.classList.remove('active');
        el.classList.add('active');
        link.classList.add('active');

      },
      updateUser: function () {
        const check = document.querySelector('input[name="users"]:checked');
        if (!check) {
          new Toast('seleccione un usuario');
          return;
        }
        const row = document.querySelector('tr[data-row-id="' + check.dataset.id + '"]').querySelectorAll('td');
        document.getElementById('actualizar-id').value = check.dataset.id;
        document.getElementById('actualizar-usuario').value = row[2].textContent;
        document.getElementById('actualizar-persona').value = row[3].textContent;
        document.getElementById('actualizar-estado').value = row[4].textContent.toLocaleLowerCase();

        new Fancybox([{ src: "#actualizarUsuario" }]);
      },
      wopen: function ({ element, event, data }) {
        event.preventDefault ? event.preventDefault() : event.returnValue = false;

        data = Object.assign({
          width: data.width || 500,
          height: data.height || 450,
          name: data.name || 'social-popup'
        }, data);


        window.open(element.getAttribute('href'), data.name, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + data.width + ',height=' + data.height + ',left=' + ((screen.width / 2) - (data.width / 2)) + ',top=' + ((screen.height / 2) - (data.height / 2)));
      },
      /*scrollTo: function (element, event, jsn) {
        event.preventDefault ? event.preventDefault() : event.returnValue = false;
         $('html,body').animate({scrollTop:  parseInt (jsn.target ? $(jsn.target).offset().top : 0) },'fast');
    
      },*/

      render: function (str) {
        if (!str) return;
        var args = str.split(/\,/g),
          i, fn;
        for (i in args) {
          if (args[i]) {
            fn = args[i].replace(/[\-\s]/gm, '');
            if (typeof this.ui[fn] == 'function') {

              this.ui[fn].call(this);
            } else if (typeof this.fn[fn] == 'function') {
              this.fn[fn]();
            }
          }
        }
      },
      evalScripts(elm) {
        Array.from(elm.querySelectorAll("script")).forEach(oldScript => {
          const newScript = document.createElement("script");
          Array.from(oldScript.attributes)
            .forEach(attr => newScript.setAttribute(attr.name, attr.value));
          newScript.appendChild(document.createTextNode(oldScript.innerHTML));
          oldScript.parentNode.replaceChild(newScript, oldScript);
        });
      },
      /*goto: function(element, event){
        event.preventDefault ? event.preventDefault() : event.returnValue = false;
        $('html,body').animate({scrollTop:  parseInt($($(element).attr('href')).offset().top - 42) },200);
      },*/
      home: function () {
        new Splide('#splideMain', {
          type: 'loop',
          pagination: false,
          autoplay: true,
        }).mount();


        new Splide('#splideNews', {
          perPage: xsl.dv.device !== 'desktop' ? 1 : 3,
          rewind: true,
          gap: '2em',

        }).mount();
      },

      toggle: function ({ data }) {

        //event.preventDefault ? event.preventDefault() : event.returnValue = false;
        data.class = data.class || 'active';
        var $to = document.querySelector(data.to);
        console.log($to, data.class);
        $to.classList.toggle(data.class);


        /*if(!jsn.single) $to.classList.toggle(jsn.class);
        else{
          if($to.attr('data-toggle') === jsn.class) $to.attr('data-toggle', '');
          else $to.attr('data-toggle', jsn.class);

        } */
      },
      validateControl: function (event, element, showMessage) {
        var $element = $(element), value, $row,
          restrict = $element.data('restrict'),
          require = $element.data('require') || false,
          pattern = {
            alpha: /^\w+$/,
            integer: /^\d+$/,
            decimal: /^[\d|\.,]+$/,
            string: /^([^0-9]*)$/,
            date: /^[\d|\-\/]+$/,
            email: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            //email:eval("/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/"),
            all: /([^\s])/
          },
          code = event.which || (window.event && window.event.keyCode) || 0;
        if (event.type == "blur" && (require || restrict == 'email')) {
          value = 'checkbox,radio'.indexOf(element.type) >= 0 ? (element.checked ? element.value : '') : element.value;
          if (require) {
            $row = $element.closest('.x-row').removeClass('x-error');
            if (showMessage) $row.children('.x-message-error').remove()
          };
          if ((require && $.trim(value) == "") || (restrict && value && !value.match(pattern[restrict]))) {
            //if (restrict && !value.match(pattern[restrict]) && require){
            $element.closest('.x-row').addClass('x-error');
            if (showMessage) $element.after('<div class="x-message-error">' + (element.getAttribute('data-message') || 'Este valor es requerido o incorrecto') + '</>');
          }
        } else if (event.type == "keypress" && restrict != 'email') {
          if (!code || code <= 13 || String.fromCharCode(code).match(pattern[restrict])) return
          else event.preventDefault();
        }
      },

      validate: function ($forms, callback) {
        var direct = $forms.jquery ? false : ($forms = $($forms), true);
        $forms.each(function () {
          var $form = $(this), $items = $('[data-restrict],[data-require]', $form);
          $items.filter('[data-restrict]:not([data-restrict="email"])').on('keypress', function (event) { xsl.fn.validateControl(event, this, $form.data('show-message') || false) });
          $items.filter('[data-require],[data-restrict="email"]').on('blur', function (event) { xsl.fn.validateControl(event, this, $form.data('show-message') || false) });
          $form.on('submit', function (e) {
            if ($form.hasClass('noValidar')) return;
            var error;
            $items.filter('[data-require],[data-restrict="email"]').trigger('blur');
            error = $('.x-error', $form).length > 0;
            if (!error && typeof callback == 'function') {
              callback($form);
              return false;
            } else return !error;
          });
        });
        if (direct) {
          if (callback.type) callback.preventDefault();
          $forms.removeAttr('onsubmit').trigger('submit');
        }
      },

      wshare: function (element, event, setting) {
        try {
          ga('send', 'event', 'compartir', 'click', setting.social + ' | ' + (setting.url || window.location.origin + window.location.pathname));
        } catch (e) { }

        xsl.fn.wopen(element, event, setting);
      },

      copy: function (element) {
        const getCode = () => {
          var code = element.parentNode.querySelector('.item-service--input').value;

          return code;
        }

        const el = document.createElement('textarea');
        el.value = getCode();
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        var toast = new Toast('El código se ha copiado en el portapapeles');
      },
      scrollTo: function ({ data }) {

        window.scrollTo({ top: (typeof data.to == 'string' ? document.querySelector(data.to).offsetTop - 140 : (typeof data.to == 'number' ? data.to : 0)), behavior: 'smooth' });
      }
    },
    ui: {
      slide: function (elements) {
        elements = elements ? [elements] : document.querySelectorAll('.x-slide');
        elements.forEach((element) => {
          new Slide(element);
        });
      },
      dragable: function () {
        $(".draggable").draggable({
          handle: ".window--header", drag: function (event, ui) {
            $(this).css({ bottom: "auto" });
          }
        });
      },



      lazy: function (elements) {
        elements = elements ? [elements] : document.querySelectorAll('.x-lazy');
        /*elements.forEach((element)=>{
          new Lazy(element);
        });*/
        new Lazy(elements);

      },
      accordion: function (elements) {
        elements = elements ? [elements] : document.querySelectorAll('.x-accordion');
        //elements.accordion();

        elements.forEach((element) => {
          var accordion = new Accordion(element);
        });
      },
      filter: function (elements) {
        elements = elements ? $(elements) : $('.x-filter');
        elements.xfilter();
      },
      tab: function (element, e) {
        const elementsTabs = document.querySelectorAll(".x-tab");
        //elements.tab();

        elementsTabs.forEach((el) => {
          new tab(el);
        });

      },
      ago: function (elements) {
        elements = elements ? $(elements) : $('.x-ago');
        elements.ago();
      },
      adsbody: function (page) {
        var param = {
          filter: '.article__main .body',
          length: 850,
          positions: ['Interna1', 'Interna2', 'Interna3', 'Interna4', 'Interna5'],
          plIds: ['1028310']
        }, _content, _paragraphs, length = 0, position = 0;

        _content = document.querySelector(param.filter);
        _paragraphs = _content.querySelectorAll(':scope > p');

        for (var i = 0; i < _paragraphs.length; i++) {
          length += _paragraphs[i].textContent.length;
          if (typeof nom_ads === 'string' && length > param.length) {


            const adUnidName = nom_ads + param.positions[position];


            _paragraphs[i].insertAdjacentHTML('beforebegin', `<div class="banner banner--interna"><div class="banner__cover" data-interna="true" id="${adUnidName}"></div></div>`);

            renderSlot({ id: adUnidName, slot: adUnidName, size: [[300, 250], [250, 250], [1, 1]], mobileSize: [[300, 250], [320, 50], [320, 100], [1, 1]] });
            /* adUnits.push({
              code: adUnid,
                mediaTypes: {
                  banner: {
                      sizes: size
                  }
              },
              bids: [{
                  bidder: 'appnexus',
                  params: {
                    placementId: param.plIds[position]
                  }
              },{
                  bidder: 'rubicon',
                  params: {
                    accountId: '19264',
                    siteId: '429738',   
                    zoneId: '2457988'
                  }
                }]
          }); */

            /* googletag.cmd.push(function() {
              var mapping = false;
              var g = googletag.defineSlot('/1028310/'+ adUnid,size, adUnidName).defineSizeMapping(mapping).addService(googletag.pubads());      
              googletag.pubads().refresh([g]); 
              googletag.display(adUnidName);
            }); */

            length = 0;
            position++;

            if (position === param.positions.length) return false;
          }
        }

      }


    },
    init: function () {
      var that = this;
      var str = document.body.dataset.x;



      setTimeout(function () {
        that.fn.render.call(that, str);
        that.fn.render.call(that, that.dv.defaultExec);
      }, 10);


    }
  };

  //$(document).on('ready', xsl.init.bind(xsl));
  xsl.init(); //defer
  //xsl.init();
  global.fn = function (element, event, jsn) {
    if (xsl.fn[jsn.method]) xsl.fn[jsn.method].call(xsl, { element: element, event: event, data: jsn });
  }


  window.xsl = xsl; //temporal

})(window, document);

/*
  Lazy Load JS for "X Simple Library"
  v1.1.0
  by ...
  10/2015 */

(function (global, document) {
  'use strict';

  var Lazy = function (elements) {
    var that = this;
    this.countAd = 0;

    that.io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        entry.target.dataset.bind = true;
        if (entry.isIntersecting) {
          const lazyElement = entry.target
          this.in(lazyElement);
        }
      })
    });

    elements.forEach((element) => {
      if (!element.dataset.bind) that.io.observe(element);
    });
  }

  Lazy.prototype.in = function (element) {
    var that = this;
    var par = element.dataset.x ? JSON.parse(element.dataset.x) : 0;
    var type = par ? par.type : 0;

    switch (type) {
      case 'html':
        element.append(par.content);
        break;
      case "exec":
        if (typeof window[par.fn] === 'function') window[par.fn]();
        break;
      case 'articles':
        var content = document.getElementById(par.content.replace('#', ''));

        if (!element.dataset.next) {
          that.io.unobserve(element)
          return;
        }


        fetch(par.url.replace('[n]', element.dataset.next)).then(function (response) {
          return response.json();
        }).then(function (jsn) {

          try {
            //setInnerHTML(content, jsn.html, true);
            content.insertAdjacentHTML("beforeend", jsn.html);
            xsl.fn.evalScripts(content);
            //content.insertAdjacentHTML("beforeend", jsn.html);
          } catch (o) { }

          swift.bindLinks(content);
          xsl.ui.lazy();
          try {
            radioApp.findSubPlayers();
          } catch (o) { }
          element.dataset.next = jsn.nextPage;

          let items = content.querySelectorAll('.podcast-card');
          let sizes = xsl.dv.device === 'desktop' ? [12, 21] : [8, 14];

          items.forEach((element, ix) => {
            if (ix + 1 == sizes[that.countAd]) {

              let n = that.countAd + 2, adUnit = content.dataset.adunid + '_middle' + n;

              element.parentElement.insertAdjacentHTML("afterend", `<div class="cell-ads cell-${n}">
                  <div class="x-ads ads ads-middle" id="${adUnit}" data-size="[728,90]" data-msize="[300,250]">
                  </div>
                </div>`);

              swift.findAds(true);
              that.countAd++;
            }
          });



        });


        break;
      default:
        //element.attr('src', $element.data('src'));
        element.src = element.dataset.src
        //element.off("scrollin");

        break;
    }

    element.classList.add("loaded");
    that.io.unobserve(element);

    //$element.css({opacity:1});

  }
  global.Lazy = Lazy;

  /*$.fn.lazy = function () {
    this.each(function () {
      new Lazy(this);
    });
  };*/



})(window, document);

class Accordion {
  constructor(element) {
    this.element = element;
    this.headers = this.element.getElementsByClassName("accordion-header");

    for (let header of this.headers) {
      header.addEventListener("click", this.toggleAccordion.bind(this));
    }
  }

  toggleAccordion(event) {
    const accordionHeader = event.target;
    const accordionContent = accordionHeader.nextElementSibling;
    accordionContent.style.maxHeight = accordionContent.style.maxHeight ? null : accordionContent.scrollHeight + "px";

    // Agrega o quita la clase 'open' para cambiar el estilo del indicador de flecha
    accordionHeader.classList.toggle("open");
  }
}
// Instancia el acordeón cuando se cargue la página
document.addEventListener("DOMContentLoaded", function () {

  window.accordion = Accordion
});


(function (d) {

  var toast = function (text) {
    this.text = text;
    this.render();
    this.binned();
    //addEvents(this);
  }

  toast.prototype = {
    render: function () {

      let htmlToast = d.createElement('div');
      htmlToast.className = 'x-toast';
      htmlToast.innerHTML = `<div class="content-toast">${this.text.toString().trim()}</div>`;
      this.el = htmlToast;
      d.body.appendChild(htmlToast);
    },
    destroy: function () {
      this.el.remove();
    },
    binned: function () {
      var that = this;

      setTimeout(function () {
        that.el.classList.add('out')
      }, 7000);

      setTimeout(function () {
        that.destroy();
      }, 8000);

    }
  }
  window.Toast = toast;
})(document);

/* new Splide( '#splideMain', {
    type    : 'loop',
    pagination: false,
    autoplay: true,
}).mount();

new Splide( '#splideNews', {
    perPage: 3,
    rewind : true,
    gap: '2em',

}).mount();
*/

class tab {
  constructor(element) {
    this.content = element;
    this.links = element.querySelectorAll('[role="tab"]');


    //this.lastActive = element.querySelector('a.active');
    this.binned();
  }

  binned() {
    const that = this;
    let active;

    this.links.forEach((link) => {
      link.removeAttribute('onclick');
      link.onclick = (ev) => {
        //ev.preventDefault();
        if (link.getAttribute('aria-selected') === 'true') return;
        active = that.content.querySelector('[aria-selected="true"]');
        active.setAttribute('aria-selected', false);
        link.setAttribute('aria-selected', true);
        document.getElementById(active.getAttribute('aria-controls')).setAttribute('hidden', '');
        document.getElementById(link.getAttribute('aria-controls')).removeAttribute('hidden');
      }
    });
  }
}

const toogleTranslate = (e, el) => {

  if (!el.dataset.binned) {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.async = true;

    el.dataset.binned = true;
    script.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.getElementsByTagName("head")[0].appendChild(script);
    el.insertAdjacentHTML('beforebegin', '<div class="translateContent active"><div id="google_translate_element"></div></div>');
  } else {
    document.querySelector('.translateContent').classList.toggle('active');
  }



}
function googleTranslateElementInit() {
  new google.translate.TranslateElement({ includedLanguages: 'en,fr,de,es', }, 'google_translate_element');
}
/* 
$('.table-js').DataTable({
  paging: false,
  language: {
      "searchPlaceholder": "Código / Nombre del plano",
      "decimal": "",
      "emptyTable": "No hay información",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
      "infoFiltered": "(Filtrado de _MAX_ total entradas)",
      "infoPostFix": "",
      "thousands": ",",
      "lengthMenu": "Mostrar _MENU_ Entradas",
      "loadingRecords": "Cargando...",
      "processing": "Procesando...",
      "search": "",
      "zeroRecords": "Sin resultados encontrados",
      "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
      }
  }
}); */

Fancybox.bind('[data-fancybox="nodrag"]', {
  dragToClose: false
});