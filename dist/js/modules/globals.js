import element from './element.js';
/**
 *  Author : Eduard Neacsu
 */
const sandbox = $("#sandbox");
const toolboxes = {
    valInternal: [],
    set val(val) {
        this.valInternal = val;
    },
    get val(){
        return this.valInternal;
    },
    clear: () =>{
        toolboxes.val = [];
    }
};
const tooltips = {
    valInternal: [],
    set val(val) {
        this.valInternal = val;
    },
    get val(){
        return this.valInternal;
    },
    clear: () =>{
        tooltips.val = [];
    }
};
const editable = {
    valInternal: [],
    set val(val) {
        this.valInternal = val;
    },
    get val(){
        return this.valInternal;
    },
    clear: () =>{
        editable.val = [];
    }
};
const page = {
    pageInternal: null,
    currentPage: null,
    pageListener: function(val) {},
    set val(val) {
        Array.isArray(val) ? this.pageInternal = val[0] : this.pageInternal = val;
        this.pageListener(val);
    },
    set current(val) {
        this.currentPage = val;
        $("#currentpage").text(capitalize(val))
    },
    get val() {
        return this.pageInternal;
    },
    get current() {
        return this.currentPage;
    },
    registerListener: function(listener) {
        this.pageListener = listener;
    }
}
page.registerListener(function(val) {
    sandbox.attr("src", val);
});
const clipboard = {
    internal: null,
    get value() {
        return this.internal;
    },
    set value(val) {
        this.internal = val;
        console.log(val);
    }
}
const isCTRLpressed = {
    internal: false,
    get value() {
        return this.internal;
    },
    set value(val) {
        this.internal = val;
    }
};
const isDELpressed = {
    internal: false,
    get value() {
        return this.internal;
    },
    set value(val) {
        this.internal = val;
        if (element.val != null)
            element.remove();
    }
};
const isSHIFTpressed = {
        internal: false,
        get value() {
            return this.internal;
        },
        set value(val) {
            this.internal = val;
        }
    }
/**
 * Global variable
 */
const isSummernoteOpen = {
    internal: false,
    get status(){
        return this.internal;
    },
    set status(val) {
        this.internal = val;
    }
};
/**
 * X axis guideLine
 */
var guideLineX = {
    internal: null,
    get value() {
        return this.internal;
    },
    /**
     * @param {any} val
     */
    set value(val) {
        this.internal = val;
    }
};
/**
 * Y axis guideLine
 */
var guideLineY = {
    internal: null,
    get value() {
        return this.internal;
    },
    /**
     * @param {any} val
     */
    set value(val) {
        this.internal = val;
    }
};
/**
 * Equivalent to ucfrist in php
 * @param {string} s Capitalize first letter from the string
 */
const capitalize = (s) => {
        if (typeof s !== 'string') return ''
        return s.charAt(0).toUpperCase() + s.slice(1)
    }
    /**
     * Return an unique id
     * @return string id
     */
const ID = _ => {
    return '_' + Math.random().toString(36).substr(2, 9);
};
/**
 * Equivalent to lcfirst in php
 * @param {string} s UnCapitalize first letter from the string
 */
const uncapitalize = (s) => {
        if (typeof s !== 'string') return ''
        return s.charAt(0).toLowerCase() + s.slice(1)
    }
    /**
     * Trim white space from a string
     * Equivalent to php trim()
     * @param {string} s 
     */
const trim = (s) => {
        if (typeof s !== 'string') return '';
        return s.replace(' ', '');
    }
    /**
     * Escape html characters from a string
     * Equivalent to htmlcharactersencode in php
     * @param {string} s  HTML string
     */
const escapeHtml = (s) => {
        let map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return s.replace(/[&<>"']/g, (m) => { return map[m]; });
    }
    /**
     * @param {string} link Link to pe used on attr("src") for sandbox
     * @param {string} current Current page taken from $("#currentpage") 
     */
const changepage = (link, current) => {
    page.val = link;
    page.current = current;
}
const toast = (msg) => {
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    Toast.fire({ icon: 'success', title: msg });
}
const notoast = (msg) => {
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    Toast.fire({ icon: 'error', title: msg });
}
const wtoast = (msg) => {
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    Toast.fire({ icon: 'warning', title: msg });
}
const itoast = (msg) => {
    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
    Toast.fire({ icon: 'info', title: msg });
}
const showerror = r => Swal.fire({ icon: "error", title: "Oops...", text: r, scrollbarPadding: !1, allowOutsideClick: !1 });
const showsuccess = (c, s = !0, t) => Swal.fire({ icon: "success", title: "Success! :)", html: c, scrollbarPadding: !1, confirmButtonText: "Great!", allowOutsideClick: !1 }).then(() => { s || (window.location.href = t) });
/**
 * Get full content of a widget and inject it into an element container
 * @param {string} url 
 * @param {mixed} object Any informations you need to pass / DEF: null
 * @param {string} target ID of the container / DEF:null
 */
const getwidget = async(url, object = null, target = null) => {
    return $.ajax({
        url: url,
        type: "post",
        dataType: 'html',
        cache: false,
        data: { "object": object },
        success: (data) => {
            target !== null ? $("#" + target).html(data) :
                $("#widgetcontainer").html(data);
        },
        error: (error) => {
            notoast("Sorry, the page was unreachable :( ");
        }
    })
};
/**
 * Just a simple contianer cleaner
 */
const clearWidget = async() => {
    $("#widgetcontainer").html("");
};
/** 
 * Change the view mode of the viewport
 * It lets you preview your page as it is on mobile or on desktop
 */
const changeView = (m) => {
    if (m == "desktop") {
        //Desktop
        $("#cview").text("Desktop");
        $(".viewport").animate({ width: '80%', height: '100%' }, 500, 'linear');
    } else {
        //Mobile
        $("#cview").text("Mobile");
        $(".viewport").animate({ width: '350px', height: '648px' }, 500, 'linear');
    }
}
const scrollToElement = () => {
    const body = sandbox.contents().find("html,body");
    const offset = element.val.offset();
    body.animate({scrollTop:offset.top},500);
}
jQuery.fn.tagName = () => {
    return this.prop("tagName");
};
/**
 * A simple JQueryUI plugin
 * It will calculate each move the new position for GuideLineX and GuideLineY
 * It's made to have guidelines just like in photoshop
 */
$.ui.plugin.add("draggable", "smartguides", {
    start: function(event, ui) {
        let i = $(this).data("uiDraggable"),
            o = i.options;
        i.elements = [];
        sandbox.contents().find("*[editable*=editable]").each(() => {

            let $t = $(this);
            let $o = $t.offset();
            if (this != i.element[0])
                i.elements.push({
                    item: this,
                    width: $t.outerWidth(),
                    height: $t.outerHeight(),
                    top: $o.top,
                    left: $o.left
                });
        });
    },
    stop: function(event, ui) {
        guideLineX.value.css({ "display": "none" });
        guideLineY.value.css({ "display": "none" });
    },
    drag: function(event, ui) {
        let inst = $(this).data("uiDraggable"),
            o = inst.options;
        let d = o.tolerance;
        guideLineX.value.css({ "display": "none" });
        guideLineY.value.css({ "display": "none" });
        let x1 = ui.offset.left,
            x2 = x1 + inst.helperProportions.width,
            y1 = ui.offset.top,
            y2 = y1 + inst.helperProportions.height,
            xc = (x1 + x2) / 2,
            yc = (y1 + y2) / 2;
        for (let i = inst.elements.length - 1; i >= 0; i--) {
            let l = inst.elements[i].left,
                r = l + inst.elements[i].width,
                t = inst.elements[i].top,
                b = t + inst.elements[i].height,
                hc = (l + r) / 2,
                vc = (t + b) / 2;

            let ls = Math.abs(l - x2) <= d;
            let rs = Math.abs(r - x1) <= d;
            let ts = Math.abs(t - y2) <= d;
            let bs = Math.abs(b - y1) <= d;
            let hs = Math.abs(hc - xc) <= d; //Horizontal center
            let vs = Math.abs(vc - yc) <= d; //Vertical center
            if (ls) {
                ui.position.left = inst._convertPositionTo("relative", { top: 0, left: l - inst.helperProportions.width }).left - inst.margins.left;
                guideLineX.value.css({ "left": l, "display": "block" });
            }
            if (rs) {
                ui.position.left = inst._convertPositionTo("relative", { top: 0, left: r }).left - inst.margins.left;
                guideLineX.value.css({ "left": r, "display": "block" });
            }
            if (ts) {
                ui.position.top = inst._convertPositionTo("relative", { top: t - inst.helperProportions.height, left: 0 }).top - inst.margins.top;
                guideLineY.value.css({ "top": t, "display": "block" });
            }
            if (bs) {
                ui.position.top = inst._convertPositionTo("relative", { top: b, left: 0 }).top - inst.margins.top;
                guideLineY.value.css({ "top": b, "display": "block" });
            }
            if (hs) {
                ui.position.left = inst._convertPositionTo("relative", { top: 0, left: hc - inst.helperProportions.width / 2 }).left - inst.margins.left;
                guideLineX.value.css({ "left": hc, "display": "block" });
            }
            if (vs) {
                ui.position.top = inst._convertPositionTo("relative", { top: vc - inst.helperProportions.height / 2, left: 0 }).top - inst.margins.top;
                guideLineY.value.css({ "top": vc, "display": "block" });
            }
        };
    }
});

/**
 * Create shortcuts
 * A simple jquery script to find if a certain key is pressed
 */

/**
 * Shortcuts for sandbox
 */
sandbox.on("load", (e) => {
    $(document.getElementById('sandbox').contentWindow.document).keydown((e) => {
        e.which === 17 ? isCTRLpressed.value = true :
            e.which === 46 ? isDELpressed.value = true :
            e.which === 16 ? isSHIFTpressed.value = true :
            console.log(e.keyCode);
    });
    $(document.getElementById('sandbox').contentWindow.document).keyup((e) => {
        isCTRLpressed.value = false;
        isSHIFTpressed.value = false;
    })
    $(document.getElementById('sandbox').contentWindow.document).bind("copy", () => {
        if (element.val !== null)
            clipboard.value = element.val;
        else
            console.log("no element to copy!");
    })
    $(document.getElementById('sandbox').contentWindow.document).bind("paste", () => {
        console.log("paste");
    })
});
/**
 * Shortcuts for editor
 * Should be identical to sandbox
 */
$(document).keydown((e) => {
    e.which === 17 ? isCTRLpressed.value = true :
        e.which === 46 ? isDELpressed.value = true :
        e.which === 16 ? isSHIFTpressed.value = true :
        console.log(e.keyCode);
});
$(document).keyup((e) => {
    isCTRLpressed.value = false;
    isSHIFTpressed.value = false;
})
$(document).bind("copy", () => {
    if (element.val !== null)
        clipboard.value = element.val;
    else
        console.log("no element to copy!");
})
$(document).bind("paste", () => {
    console.log("paste");
});
export {
    sandbox,
    page,
    toolboxes,
    tooltips,
    editable,
    isCTRLpressed,
    isDELpressed,
    isSHIFTpressed,
    guideLineX,
    guideLineY,
    changepage,
    changeView,
    getwidget,
    toast,
    notoast,
    wtoast,
    itoast,
    capitalize,
    trim,
    uncapitalize,
    escapeHtml,
    showsuccess,
    showerror,
    ID,
    clearWidget,
    scrollToElement,
    isSummernoteOpen
};