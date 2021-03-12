import $ from 'jquery';
import * as globals from './globals';
/**
 * Author : Eduard Neacsu
 * Element is the main object you are handling with editor
 * Each editable from webpage will become this element
 * It's easier to manipulate object this way
 * I am trying to make this as much as possible OOP
 */
 const element = {
     internal: null,
     internalData: {
         selfid: null,
         panelid: null,
         panel: null
     },
     valListener: function(val) {},
     Itooltip: null,
     Itoolbox: null,
     Icontainer: null,
     set val(val) {
        if(typeof val === typeof undefined)
            return;
        
         this.internal = val;
         this.data.selfid = val.attr("id");
         this.data.panelid = val.data("panelid");
         this.data.panel = val.data("panel");
         this.valListener(val);
         this.checkForContainer();
     },
     /**
      * @param {any} val
      */
     set container(val) {
         this.Icontainer = val;
     },
     get val() {
         return this.internal;
     },
     get data() {
         return this.internalData;
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
             globals.etoast("Please use column manager to remove columns!");
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
                 
                 //sections.update();
             },
             error: (error) => {
                 console.error(error);
                 
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
         
     },
     /**
      * 
      * @param {string} rowClass 
      * @param {string} newClassColValue 
      * @param {JQuery Object} rawItem 
      */
     addColumn: async (rowClass,newClassColValue,rawItem) => {
         const html = escape(rawItem[0].outerHTML);
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
         
     },
     moveAfter: (targetClass) => {
         let target = $("#sandbox").contents().find("." + targetClass);
         if (typeof target === typeof undefined || target === null)
             return;
         target.after(element.val);
         const values = { "action": "moveAfter", "data": { "that": target.data("panelid") }, "page": globals.page.current, "id": element.val.data("panelid") };
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
     moveBefore: (targetClass) => {
         let target = $("#sandbox").contents().find("." + targetClass);
         if (typeof target === typeof undefined || target === null)
             return;
         target.before(element.val);
         const values = { "action": "moveBefore", "data": { "that": target.data("panelid") }, "page": globals.page.current, "id": element.val.data("panelid") };
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
     addAfter: (targetClass,rawItem) => {
         if(typeof targetClass === typeof undefined || typeof rawItem === typeof undefined)
             return console.error("Class or item is undefined!");
         const target = $("#sandbox").contents().find("." + targetClass);
         const html = escape(rawItem[0].outerHTML);
 
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
         
     },
     addBefore: (targetClass,rawItem) => {
         const target = $("#sandbox").contents().find("." + targetClass);
         const html = escape(rawItem[0].outerHTML);
 
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
         
     },
     /**
      * Append an element as a chield of this element
      * @param {JQuery Object} rawItem 
      */
     addChield: (rawItem) => {
         const html = escape(rawItem[0].outerHTML);
         const values = { "action": "addChield", "data": { "element":html }, "page": globals.page.current, "id": element.val.data("panelid") };
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
         
     }
 }
 
 element.registerListener(function(val) {
     
 });
 export default element;