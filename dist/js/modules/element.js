/**
 * Author : Eduard Neacsu
 * Element is the main object you are handling with editor
 * Each editable from webpage will become this element
 * It's easier to manipulate object this way
 * I am trying to make this as much as possible OOP
 */
import { geteditablecontent } from '../editor.js';
import * as globals from './globals.js';
import sections from './sections.js';

const element = {
    valInternal: null,
    valListener: function(val) {},
    Itooltip: null,
    Itoolbox: null,
    Icontainer: null,
    set val(val) {
        this.valInternal = val;
        this.valListener(val);
        this.checkForContainer();
    },
    /**
     * @param {any} val
     */
    set tooltip(val) {
        this.Itooltip = val;
    },
    /**
     * @param {any} val
     */
    set toolbox(val) {
        this.Itoolbox = val;
    },
    set container(val) {
        this.Icontainer = val;
    },
    get val() {
        return this.valInternal;
    },
    get tooltip() {
        return this.Itooltip;
    },
    get toolbox() {
        return this.Itoolbox;
    },
    get container() {
        return this.Icontainer;
    },
    registerListener: function(listener) {
        this.valListener = listener;
    },
    checkForContainer: _ => {
        let Acontainer = element.val.parent();
        if (Acontainer.hasClass("container") || Acontainer.hasClass("container-fluid") || Acontainer.hasClass("container-solid"))
            element.container = Acontainer;
        //console.log(element.container);
    },
    remove: async () => {
        if(element.val === null)
            return ;
        if(element.val.data("panel") === "column")
        {
            globals.notoast("Please use column manager to remove columns!");
            return;
        }
        element.val.remove();
        element.Itooltip.item.remove();
        element.Itoolbox.item.remove();
        const values = {
            "action": "removeElement",
            "page": globals.page.current,
            "id": element.val.data("panelid")
        }
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
                geteditablecontent();
                sections.update();
            },
            error: (error) => {
                console.error(error);
                geteditablecontent();
            }
        })
    },
    /**
     * Set inline style of an element on the backend
     * @param {array} props 
     * @param {array} vals 
     */
    setCSS: async (props,vals) => {
        const values = {
            "action": "setCSS",
            "page": globals.page.current,
            "data": { 
                "props": props,
                "values":vals 
            },
            "id": element.val.data("panelid")
        }
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
    },
    /**
     * Change only one attribute of an element
     */
    changeAttribute: async (attribute,value) => {
        const values = {
            "action": "setAttribute",
            "page": globals.page.current,
            "data": { 
                "attribute": attribute, 
                "value": value
            },
            "id": element.val.data("panelid")
        }
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
    },
    /**
     * Change multiple attributes of an element
     * @param {array} attributes
     * @param {array} vals
     */
    changeAttributes: async (attributes, vals) => {
        const values = {
            "action": "setAttributes",
            "page": globals.page.current,
            "data": { 
                "attributes": attributes, 
                "values": vals
            },
            "id": element.val.data("panelid")
        }
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
    },
    saveText: async _ => {
        const values = {
            "action": "changeText",
            "page": globals.page.current,
            "data": { "html": element.val.html() },
            "id": element.val.data("panelid")
        }
        return $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
    },
    /**
     * Set more attributes of an element
     * @param {array} attr Attribute names
     * @param {array} value Attribute values
     * @param {array} unit Attribute units
     */
    setSize:(attr,value,unit="px") =>{
        if(!Array.isArray(attr))
            attr = [attr];

        if(!Array.isArray(value))
            value = [value];

        if(!Array.isArray(unit))
            unit = [unit];

        const values = {
            "action": "setSizes",
            "data": {
                "attr": attr,
                "value": value,
                "unit":unit
            },
            "page": globals.page.current,
            "id": element.val.data("panelid")
        };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        });
        geteditablecontent();
    },
    /**
     * Set more attributes of an element
     * @param {array} attr Attribute names
     * @param {array} value Attribute values
     * @param {array} unit Attribute units
     */
    setSizes:(attr,value,unit="px") =>{
        const values = {
            "action": "setSizes",
            "data": {
                "attr": attr,
                "value": value,
                "unit": unit
            },
            "page": globals.page.current,
            "id": element.val.data("panelid")
        };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        });
        geteditablecontent();
    },
    /**
     * Add a class to the element on the back end
     * Can also remove some classes before actualy adding new classes
     * @param {array} className Either string or array
     * @param {array} classesToRemoveBefore either string or array
     */
    addClass: async(className,classesToRemoveBefore = '') => {
        const values = { 
            "action": "addClass", 
            "data": { 
                "class": className ,
                "classesToRemoveBefore": classesToRemoveBefore
            }, 
            "page": globals.page.current, 
            "id": element.val.data("panelid") 
        };
        element.val.addClass(className);
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        });
        geteditablecontent();
    },
    /**
     * Remove one or more classes from the element
     * Will do both front and backend
     * @param {any} c String or Array
     */
    removeClass: async(className) => {
        const values = { 
            "action": "removeClass",
            "data": 
            { 
                "class": className 
            }, 
            "page": globals.page.current, 
            "id": element.val.data("panelid") 
        }
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: function(data) {
                if (Array.isArray(className))
                    for (let index = 0; index < className.length; index++) {
                        const el = className[index];
                        element.val.removeClass(el);
                    }
                else
                    element.val.removeClass(className);
                $("#ajax").html(data);
            },
            error: function() {
                console.log("Something went wrong!");
            }
        });
        geteditablecontent();
    },
    /**
     * 
     * @param {string} rowClass 
     * @param {string} newClassColValue 
     * @param {JQuery Object} rawItem 
     */
    addColumn: async (rowClass,newClassColValue,rawItem) => {
        const html = globals.escapeHtml(rawItem[0].outerHTML);
        const values = {
            "action": "addColumn",
            "data": {
                "rowClass": rowClass,
                "Class": newClassColValue,
                "rawItem": html
            },
            "page": globals.page.current,
            "id": element.val.data("panelid")
        };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                $("#ajax").html("<h2 class='text-center'>Sorry, the page was unreachable :( </h2>");
            }
        })
        geteditablecontent();
    },
    removeColumn: async (rowClass,columnClass, newClass) =>{
        if(newClass === null || typeof newClass === typeof undefined)
            newClass = 0;
        const values = {
            "action": "removeColumn",
            "data": {
                "rowClass": rowClass,
                "columnClass": columnClass,
                "newClass": newClass
            },
            "page": globals.page.current,
            "id": element.val.data("panelid")
        };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                $("#ajax").html("<h2 class='text-center'>Sorry, the page was unreachable :( </h2>");
            }
        })
        geteditablecontent();
    },
    moveAfter: (targetClass) => {
        let target = globals.sandbox.contents().find("." + targetClass);
        if (typeof target === typeof undefined || target === null)
            return;
        target.after(element.val);
        const values = { "action": "moveAfter", "data": { "that": target.data("panelid") }, "page": globals.page.current, "id": element.val.data("panelid") };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                geteditablecontent();
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
        geteditablecontent();
    },
    moveBefore: (targetClass) => {
        let target = globals.sandbox.contents().find("." + targetClass);
        if (typeof target === typeof undefined || target === null)
            return;
        target.before(element.val);
        const values = { "action": "moveBefore", "data": { "that": target.data("panelid") }, "page": globals.page.current, "id": element.val.data("panelid") };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                geteditablecontent();
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
        geteditablecontent();
    },
    addAfter: (targetClass,rawItem) => {
        if(typeof targetClass === typeof undefined || typeof rawItem === typeof undefined)
            return console.error("Class or item is undefined!");
        const target = globals.sandbox.contents().find("." + targetClass);
        const html = globals.escapeHtml(rawItem[0].outerHTML);

        const values = { "action": "addAfter", "data": { "element":html }, "page": globals.page.current, "id": target.data("panelid") };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
        geteditablecontent();
    },
    addBefore: (targetClass,rawItem) => {
        const target = globals.sandbox.contents().find("." + targetClass);
        const html = globals.escapeHtml(rawItem[0].outerHTML);

        const values = { "action": "addBefore", "data": { "element":html }, "page": globals.page.current, "id": target.data("panelid") };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
        geteditablecontent();
    },
    /**
     * Append an element as a chield of this element
     * @param {JQuery Object} rawItem 
     */
    addChield: (rawItem) => {
        const html = globals.escapeHtml(rawItem[0].outerHTML);
        const values = { "action": "addChield", "data": { "element":html }, "page": globals.page.current, "id": element.val.data("panelid") };
        $.ajax({
            url: "processors/document.req.php",
            type: "post",
            data: values,
            success: (data) => {
                geteditablecontent();
                $("#ajax").html(data);
            },
            error: (error) => {
                console.error(error);
            }
        })
        geteditablecontent();
    }
}

element.registerListener(function(val) {
    updateSidebar(val);
});
export default element;
window.element = element;