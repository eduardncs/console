/*!
 * Rosance-Editor V 0.1 Alpha(https://rosance.com)
 * Author : Eduard Neacsu
 * Copyright © 2020-2021
 */
import * as globals from './modules/globals.js';
import tooltip from './modules/tooltip.js';
import toolbox from './modules/toolbox.js';
import element from './modules/element.js';
import menu from './modules/menu.js';
import * as column from './modules/column.js';
import sections from './modules/sections.js';
import models from './modules/models.js';
import * as devMode from './modules/devmode.js';
import * as image from './modules/image.js';
import * as media from './modules/media.js';
import * as background from './modules/background.js';
import * as button from './modules/button.js';

window.toast = globals.toast;
window.notoast = globals.notoast;
window.itoast = globals.itoast;
window.wtoast = globals.wtoast;
window.showsuccess = globals.showsuccess;
window.showerror = globals.showerror;
window.ID = globals.ID;
window.getwidget = globals.getwidget;

window.menu = menu;
window.column = column;
window.image = image;
window.media = media;
window.background = background;
window.button = button;

$(document).ready(function() {
    globals.changepage('../clients/' + BN + '/' + PN + '/home', 'home')
    globals.getwidget("widgets/editor-sidebar-right.html", null, "editor-sidebar-right");
    globals.getwidget("widgets/editor-sidebar-left.html", null, "editor-sidebar-left");
})

globals.sandbox.on('load', function() {
    setInterval(function() { $(".overlay_main").hide(); }, 300);
    deactivatelinks();
    geteditablecontent();
    sections.update();
    globals.sandbox.contents().find("body").append('<div class="guideLineX" style="display:none;width:0px;height:100%;position:absolute;top:0px;left:10px;border-left:1px solid orange;" ></div>\n<div class="guideLineY" style="display:none;width:100%;height:0px;position:absolute;top:10px;left:0px;border-bottom:1px solid orange;"></div>');
    globals.guideLineX.value = globals.sandbox.contents().find(".guideLineX");
    globals.guideLineY.value = globals.sandbox.contents().find(".guideLineY");
    /* Disable scrollbar on sandbox body */
    const scrollbarHiddenRule = "<style> body { -ms-overflow-style: none; scrollbar-width: none;} body::-webkit-scrollbar {display: none;} </style>";
    globals.sandbox.contents().find("body").append(scrollbarHiddenRule);
})
$(window).resize(function() {
    geteditablecontent();
})
$("#devModeTrigger").on("click",(e) => {
    devMode.open();
})

const geteditablecontent = _ => {
    clearAll();
    globals.sandbox.contents().find('*[editable*=editable]').each(function() {
        globals.editable.val.push($(this));
        const data = $(this).data("panel"),
            id = $(this).data("panelid");

        const _tooltip = tooltip.createTooltip($(this),id,data);
        const _toolbox = createToolbox(data, id, $(this));

        tooltip.adjustTooltipPositionAsync(_tooltip);
        toolbox.adjustToolboxPositionAsync(_toolbox);

        $(this).droppable({
            greedy:true,
            over: function(event,ui){
                $(this).css({
                    "outline":"1px solid green"
                })
            },
            out: function(event,ui){
                $(this).css({
                    "outline":"none"
                });
            },
            drop: function(event, ui){
                const itemDropped = ui.draggable;
                if(itemDropped.data("action") === "add")
                {
                    const item = $(models(itemDropped.data("from"),itemDropped.data("id")));
                    const parent = $(event.target);
                    element.val = parent;
                 
                    let x = ui.offset.left
                    let y = ui.offset.top;
        
                    const corX = globals.sandbox.offset().left;
                    const corX2 = globals.sandbox.contents().find("html,bodt").scrollLeft();
        
                    const corY = globals.sandbox.offset().top;
                    const corY2 = globals.sandbox.contents().find("html,body").scrollTop();
        
                                        
                    x -= corX;
                    x += corX2;
        
                    y -= corY;
                    y += corY2; 
                   
                    const w = ui.helper.outerWidth(),
                            h = ui.helper.outerHeight();
                    item.appendTo(parent);
                    item.css({
                        position:'absolute',
                        width:w,
                        height:h
                    });
                    const id = globals.ID();
                    item.addClass(id);
                    item.attr("data-panelid",id);
                    element.addChield(item);
                }
            }
        })

        $(this).mouseover((e) => {
            if (globals.isSummernoteOpen.status)
                return false;
            if (element.val != null) {
                element.val.data('panelid') == id ?
                    $(this).css({
                        'outline': '1px solid blue',
                        'cursor': 'move'
                    }) :
                    $(this).css({
                        'outline': '1px solid blue',
                        'cursor': 'pointer'
                    });
                _tooltip.item.show();
                return false;
            }
            $(this).css({
                'outline': '1px solid blue',
                'cursor': 'pointer'
            });
            _tooltip.item.show();
            return false;
        });
        $(this).mouseout((e) => {
            if (globals.isSummernoteOpen.status)
                return false;

            if (element.val != null) {
                if (element.val.data('panelid') == id)
                    return false;
            }
            $(this).css({
                'outline': 'none',
                'cursor': 'default'
            });
            _tooltip.item.hide();
            return false;
        });
        $(this).on("click", (e) => {
            if (globals.isSummernoteOpen.status)
                return false;
            
            if (element.val != null) {
                if (element.val.data('panelid') == id)
                    return false;
            }
            if (globals.isCTRLpressed.value) {
                selectParent(id);
                return false;
            }
            if (globals.isSHIFTpressed.value) {
                selectBody();
                return false;
            }

            element.val = $(this);
            element.tooltip = _tooltip;
            element.toolbox = _toolbox;

            element.val.css({ 'outline': '1px solid blue', "cursor": "move" });

            unfocusallbut(id);
            untooltipallbut(id);
            untoolboxall();

            restorestyles();

            movePanel(id);
            _toolbox.item.show();

            return false;
        });
    });
}
window.geteditablecontent = geteditablecontent;

const clearAll = async () =>{
    globals.toolboxes.clear();
    globals.tooltips.clear();
    globals.editable.clear();
}

const untoolboxall = () => {
    globals.toolboxes.val.forEach(tbx => {
        tbx.item.hide();
    });
}

const unfocusallbut = (s) => {
    globals.editable.val.forEach(element => {
        if (element.data("panelid") == s)
            return;
    })
}

const untooltipallbut = (s) => {
    globals.tooltips.val.forEach(element => {
        if (element.item.attr("id") == "tooltip" + s)
            return;
        element.item.hide();
    })
}

const restorestyles = _ => {
        globals.editable.val.forEach(e => {
            if (e.data("panelid") == element.val.data("panelid"))
                return;

            e.css({ "outline": "none", "cursor": "default" });
        })
    }
/**
 * Return a toolbox acording to the data-panel and data-panelid of the selcted element
 * @param {string} s data-panel
 * @param {string} i data-panelid
 * @param {JQuery Object} parent toolbox parent
 */
const createToolbox = (s, i, parent) => {
    switch (s) {
        case "menu":
            return toolbox.createToolbox(i, 
                ['Edit menu'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)'
            ],parent);
        case "footer":
            return toolbox.createToolbox(i, [
                'Edit footer',
                'Rows & columns',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)',
                'parent.openPanel(`column`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "text":
            return toolbox.createToolbox(i, [
                'Edit text',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "section":
            return toolbox.createToolbox(i, [
                'Change background',
                'Rows & columns',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`background`,`' + i + '`)',
                'parent.openPanel(`column`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "row":
            return toolbox.createToolbox(i, [
                'Change background',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`background`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "column":
            return toolbox.createToolbox(i, [
                'Change background',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`background`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "social":
            return toolbox.createToolbox(i, ['Set social links'], ['parent.openPanel(`' + s + '`,`' + i + '`)'],parent);
        case "portofolio":
            return toolbox.createToolbox(i, ['Edit projects'], ['parent.openPanel(`' + s + '`,`' + i + '`)'],parent);
        case "image":
            return toolbox.createToolbox(i, [
                'Edit image',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "header":
            return toolbox.createToolbox(i, [
                'Edit header',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'

            ],parent);
        case "button":
            return toolbox.createToolbox(i, [
                'Edit button',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`' + s + '`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "container":
            return toolbox.createToolbox(i, [
                'Change background',
                '<i class="fas fa-file-image"></i>'
            ], [
            'parent.openPanel(`background`,`' + i + '`)',
            'parent.openPanel(`layout`,`' + i + '`)'
        ],parent);
        case "list":
            return toolbox.createToolbox(i, [
                'Edit list',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`list`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "form":
            return toolbox.createToolbox(i, [
                'Edit form',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`form`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
        case "input":
            return toolbox.createToolbox(i, [
                'Edit field',
                '<i class="fas fa-file-image"></i>'
            ], [
                'parent.openPanel(`input`,`' + i + '`)',
                'parent.openPanel(`layout`,`' + i + '`)'
            ],parent);
    }
}
const deactivatelinks = () => {
    globals.sandbox.contents().find('a').each(function() {
        $(this).on("click", function(event) {
            event.preventDefault();
        });
    });
    globals.sandbox.contents().find("form").each(function() {
        $(this).submit(function(event) {
            event.preventDefault();
            event.stopPropagation();
        });
    });
    globals.sandbox.contents().find("[onclick]").each(function() { $(this).removeAttr('onclick'); });
}

const openPanel = function(panelcode, objectid) {
    switch (panelcode) {
        case "header":
            return globals.getwidget("widgets/header/header.html");
        case "socialmenu":
            return globals.getwidget("widgets/socialwidget.php");
        case "text":
            let id = "." + objectid;
            let options = {
                callbacks: {
                    onInit: function() {
                        parent.doShit();
                        element.val.show();
                        element.val.css({ "visibility": "hidden" });
                        globals.sandbox.contents().find(".note-statusbar").remove();
                        let noteBtnSave = '<button id="saveSummernote" type="button" class="btn ml-2 btn-success btn-sm btn-small" title="Save all changes and close" data-event="something" tabindex="-1"><i class="fas fa-check"></i></button>';
                        let noteBtnExit = '<button id="closeSummernote" type="button" class="btn btn-danger btn-sm btn-small" title="Close without saving" data-event="something" tabindex="-1"><i class="fas fa-times"></i></button>';
                        let fileGroup = '<div class="note-file btn-group">' + noteBtnSave + noteBtnExit + '</div>';
                        $(fileGroup).appendTo(globals.sandbox.contents().find('.note-toolbar'));
                    },
                    onChange: function(values) {
                        element.val.html(values);
                        let h = element.val.height();
                        let w = element.val.width();
                        let pt = element.val.position().top;
                        let pl = element.val.position().left;
                        let ml = element.val.css("margin-left");
                        globals.sandbox.contents().find(".note-editor").css({
                            "width": w,
                            "height": h,
                            "margin-left":ml,
                            "top": pt,
                            "left": pl
                        });
                        globals.sandbox.contents().find(".note-editor .note-editable").css({ "padding": "0", "width": w });
                        console.log("OnChange Fired");
                    }
                },
                airMode: false,
                focus: true,
                disableResizeEditor: false,
                fontSizes: ['8', '9', '10', '11', '12', '14', '18', '24', '36', '48', '64', '82', '150'],
                fontsizeunit: "px",
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']]
                ],
            };
            const initValues = element.val.html();
            let position = element.val.position();
            let margin = element.val.css("margin-left");
            let proportions = [element.val.outerWidth(), element.val.outerHeight()];
            document.getElementById('sandbox').contentWindow.openSummernote(id, options);
            globals.sandbox.contents().find(".note-editor").on("click", function(e) {
                e.preventDefault();
            })
            globals.isSummernoteOpen.status = true;
            globals.sandbox.contents().find(".note-editor").css({
                "cursor": "text",
                "position": "absolute",
                "left": position.left /* * 1 + 15 */,
                "margin-left": margin,
                "top": position.top,
                "width": proportions[0],
                "height": proportions[1],
                "background-color": "rgba(255,255,255,0.4)",
                "outline": "1px solid blue",
                "display": "block"
            });
            globals.sandbox.contents().find(".note-editor .note-editable").css({ "padding": "0", "width": proportions[0] });
            element.toolbox.item.hide();

            globals.sandbox.contents().find("#saveSummernote").on("click", (event) => {
                element.val.css({ "visibility": "visible" });
                document.getElementById('sandbox').contentWindow.openSummernote(id, "destroy").then(_ => {
                    globals.isSummernoteOpen.status = false;
                });
                if (initValues === element.val.html())
                    return;
                element.saveText();
                globals.clearWidget();
                toolbox.adjustToolboxPositionAsync(element.toolbox);
                element.toolbox.item.show();
            })

            globals.sandbox.contents().find("#closeSummernote").on("click", (event) => {
                element.val.css({ "visibility": "visible" });
                console.log("Closing summernote!");
                document.getElementById('sandbox').contentWindow.openSummernote(id, "destroy").then(_ => {
                    globals.isSummernoteOpen.status = false;
                    element.val.html(initValues);
                });
                globals.clearWidget();
                element.toolbox.item.show();
            })
            return null;
        case "background":
            return globals.getwidget("widgets/design/background.html");
        case "portofolio":
            return globals.getwidget("widgets/portofolio.php");
        case "column":
            return globals.getwidget("widgets/columns/columns.html");
        case "image":
            return globals.getwidget("widgets/image/image.html");
        case "menu":
            return globals.getwidget("widgets/menu.php");
        case "layout":
            return globals.getwidget("widgets/design/layout.html");
        case "button":
            return globals.getwidget("widgets/design/button.html");
    }
}
window.openPanel = openPanel;

const stopalldragging = async() => {
    globals.editable.val.forEach(element => {
        if (element.data('ui-draggable'))
            element.draggable('disable');
    })
}

const removeMedia = (source, id) => {
    var values = { "Source": source, "Source-id": id };
    $.ajax({
        url: "system/requestprocessor.php",
        type: "POST",
        data: values,
        success: function(data) {
            $("#addmediamodal").modal('hide');
            $("#mediamodal").modal('hide');
            $("#ajax").html(data);
        }
    })
}

const loadMediaContents = async(url, target) => {
    $.ajax({
        url: url,
        method: 'post',
        data: { 'id': element.val.data('panelid'), 'page': globals.page.val },
        beforeSend: function() { $("#m_overlay").show(); },
        success: function(data) {
            $("#" + target).html(data);
            $("#m_overlay").hide();
        },
        error: function() {
            $("#m_overlay").hide();
            notoast("Something went wrong , please try again later");
        }
    })
}
window.loadMediaContents = loadMediaContents;
/**
 * To be continued , need to make responsive for mobile
 * @param {any} id 
 */
const movePanel = (id) => {
    if (globals.isSummernoteOpen.status)
        return;
    stopalldragging();
    let _tooltip = globals.sandbox.contents().find("#" + "__" + id);
    const _html = _tooltip.html();

    let data = element.val.data("panel");
    let left;
    let top;

    if (data != "section" || data != "portofolio" || data != "column" || data != "header")
        $(element.val).draggable({
            cursor: "move",
            distance: 10,
            containment: globals.sandbox.contents().find("body"),
            smartguides: globals.sandbox.contents().find("*[editable*=editable]"),
            tolerance: 5
        });
    else {
        $(element.val).draggable({
            cursor: "move",
            distance: 10,
            axis: "y",
            containment: globals.sandbox.contents().find("body"),
            smartguides: globals.sandbox.contents().find("*[editable*=editable]"),
            tolerance: 5
        });
    }

    $(element.val).draggable('enable');
    $(element.val).draggable({
        start: function() {
            left = element.val.offset().left;
            top = element.val.offset().top;
            element.toolbox.item.hide();
        },
        drag: function() {
            let left = Math.round($(this).offset().left);
            let top = Math.round($(this).offset().top);
            _tooltip.html("<div style='color:white;'>x: " + left + " , y: " + top + "</div>");
            tooltip.adjustTooltipPositionAsync(element.tooltip).then(() => {
                _tooltip.html("<div style='color:white;'>x: " + left + " , y: " + top + "</div>");
            });
        },
        stop: function() {
            if ((element.val.offset().top - top) === 0 && (element.val.offset().left - left) === 0)
                return;
/*             let l = (100 * parseFloat(element.val.position().left / parseFloat(element.val.parent().width()))) + "%";
            let t = (100 * parseFloat(element.val.position().top / parseFloat(element.val.parent().height()))) + "%";
            element.val.css({
                "left": l,
                "top": t
            }); */
            tooltip.adjustTooltipPositionAsync(element.tooltip);
            toolbox.adjustToolboxPositionAsync(element.toolbox);
            _tooltip.html(_html);
            element.toolbox.item.show();
            let item = document.getElementById('sandbox').contentWindow.document.querySelector("." + id);
            setdata(id, item.style.left, item.style.top);
        }
    });
}

const setdata = async(i, l, t) => {
        let css = { "position": element.val.css('position'), "left": l, "top": t };
        let values = { "action": "FinishDragging", "css": css, "id": i, "page": globals.page.current };
        console.log(values);
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            cache: false,
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            }
        })
    };

/**
 * Return parent of the selected element
 * @param {string} id 
 */
const selectParent = (id) => {
    if (globals.isCTRLpressed.value)
        globals.isCTRLpressed.value = false;

    let parent = globals.sandbox.contents().find("." + id).parent();
    if (parent.tagName() === "BODY")
        return;
    let attr = parent.attr("editable");
    while (typeof attr === typeof undefined) {
        parent = parent.parent();
        if (parent.tagName() === "BODY")
            return;
        attr = parent.attr("editable");
    }
    parent.click();
}
window.selectParent = selectParent;


$("#chw-d").on("click", (e) => {
    globals.changeView("desktop");
})
$("#chw-m").on("click", (e) => {
    globals.changeView("mobile");
})
const doShit = () => {
    globals.getwidget("widgets/summernote/toolbar.html").then(
        _ => {
            let x = globals.sandbox.contents().find(".note-toolbar").addClass("d-flex").detach();
            if (x.length > 0)
                $("#note-toolbar-container").html(x);
        }
    )
}

window.doShit = doShit;
export {geteditablecontent};