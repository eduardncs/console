import React, { useState, useEffect, useRef } from "react";
import Iframe from 'react-iframe';
import $ from 'jquery';
import tooltip from './tooltip';
import toolbox from './toolbox';
import element from './element';
import { domain, getGlobals } from './backend';
import { User, Project } from './data';

const Sandbox = () => {
    const [source, setSource] = useState([]);
    const elements = useRef([]);
    useEffect(() =>{ 
        const fetchData = async() => {
            await getGlobals();
            setSource(domain+"clients/"+User.getUser.Business_Name+"/"+Project.getProject.project_name_short+"/home");
        }
        //console.log("Im calling fetchData");
        fetchData();
    }
    , [])

    
    const disableClicks = () =>{
        $("#sandbox").contents().find('a').each(function() {
            $(this).on("click", function(event) {
                event.preventDefault();
            });
        });
        $("#sandbox").contents().find("form").each(function() {
            $(this).submit(function(event) {
                event.preventDefault();
                event.stopPropagation();
            });
        });
        $("#sandbox").contents().find("[onclick]").each(function() { $(this).removeAttr('onclick'); });
    }

    /**
     * Return a toolbox acording to the data-panel and data-panelid of the selcted element
     * @param {JQuery Object} parent toolbox parent
     * @param {string} i data-panelid
     * @param {string} s data-panel
     */
    const createToolbox = (parent, i, s) => {
        switch (s) {
            case "menu":
                return toolbox.createToolbox(i, 
                    ['Edit menu'
                ], [
                    'Menu'
                ],parent);
            case "footer":
                return toolbox.createToolbox(i, [
                    'Edit footer',
                    'Rows & columns',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`' + s + '`)',
                    'parent.openPanel(`column`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "text":
                return toolbox.createToolbox(i, [
                    'Edit text',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`' + s + '`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "row":
                return toolbox.createToolbox(i, [
                    'Change background',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`background`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "column":
                return toolbox.createToolbox(i, [
                    'Change background',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`background`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "social":
                return toolbox.createToolbox(i, [
                    'Set social links'
                ], [
                    'parent.openPanel(`' + s + '`)'
                ],parent);
            case "portofolio":
                return toolbox.createToolbox(i, [
                    'Edit projects'
                ], [
                    'parent.openPanel(`' + s + '`)'
                ],parent);
            case "image":
                return toolbox.createToolbox(i, [
                    'Edit image',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`' + s + '`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "header":
                return toolbox.createToolbox(i, [
                    'Edit header',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`' + s + '`)',
                    'parent.openPanel(`layout`)'

                ],parent);
            case "button":
                return toolbox.createToolbox(i, [
                    'Edit button',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`' + s + '`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "container":
                return toolbox.createToolbox(i, [
                    'Change background',
                    '<i class="fas fa-file-image"></i>'
                ], [
                'parent.openPanel(`background`)',
                'parent.openPanel(`layout`)'
            ],parent);
            case "list":
                return toolbox.createToolbox(i, [
                    'Edit list',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`list`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "form":
                return toolbox.createToolbox(i, [
                    'Edit form',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`form`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            case "input":
                return toolbox.createToolbox(i, [
                    'Edit field',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`input`)',
                    'parent.openPanel(`layout`)'
                ],parent);
            default :
                return toolbox.createToolbox(i, [
                    'Change background',
                    'Rows & columns',
                    '<i class="fas fa-file-image"></i>'
                ], [
                    'parent.openPanel(`background`)',
                    'parent.openPanel(`column`)',
                    'parent.openPanel(`layout`)'
                ],parent);
        }
    }

    const getEditableContent = () =>{
        $("#sandbox").contents().find("[editable*=editable]").each((index,element)=>{
            const that = $(element);
            const id = that.data("panelid"),
                  data = that.data("panel"),
                  _tooltip = tooltip.createTooltip(that,id,data),
                  _toolbox = createToolbox(that,id,data);

            const $this = {
                internal: that,
                tooltip: _tooltip,
                toolbox: _toolbox
            };
            elements.current = [...elements.current, $this];
            tooltip.adjustTooltipPositionAsync(_tooltip);
            toolbox.adjustToolboxPositionAsync(_toolbox);

            that.on("mouseover", () => onMouseOver($this));
            that.on("mouseout", () => onMouseOut($this));
            that.on("click", () => onClick($this));
        })
    }

    const onMouseOver = ($this) =>{
        $this.internal.css({
            "outline":"2px solid blue",
            "cursor":"move"
        });
        $this.tooltip.item.show();
        return false;
    }

    const onMouseOut = ($this) =>{
        if($this.internal.data("panelid") === element.data.panelid)
            return false;
        $this.internal.css({
            "outline":"none",
            "cursor":"default"
        });
        $this.tooltip.item.hide();
        return false;
    }

    const onClick = ($this) =>{
        for (let i = 0; i < elements.current.length; i++) {
            const e = elements.current[i];
            if(e.internal === $this.internal)
                continue;
            e.internal.css({"cursor":"default","outline":"none"})
            e.tooltip.item.hide();
        }
        toolbox.show($this.toolbox);
        element.val = $this.internal;
        $this.internal.css({
            "cursor":"move",
            "outline":"2px solid blue"
        });
        return false;
    }

    const openSummernote = (id) =>{
        const options = {
            callbacks: {
                onInit: function() {
                    //parent.doShit();
                    element.val.show();
                    element.val.css({ "visibility": "hidden" });
                    $("#sandbox").contents().find(".note-statusbar").remove();
                    let noteBtnSave = '<button id="saveSummernote" type="button" class="btn ml-2 btn-success btn-sm btn-small" title="Save all changes and close" data-event="something" tabindex="-1"><i class="fas fa-check"></i></button>';
                    let noteBtnExit = '<button id="closeSummernote" type="button" class="btn btn-danger btn-sm btn-small" title="Close without saving" data-event="something" tabindex="-1"><i class="fas fa-times"></i></button>';
                    let fileGroup = '<div class="note-file btn-group">' + noteBtnSave + noteBtnExit + '</div>';
                    $(fileGroup).appendTo($("#sandbox").contents().find('.note-toolbar'));
                },
                onChange: function(values) {
                    element.val.html(values);
                    let h = element.val.height();
                    let w = element.val.width();
                    let pt = element.val.position().top;
                    let pl = element.val.position().left;
                    let ml = element.val.css("margin-left");
                    $("#sandbox").contents().find(".note-editor").css({
                        "width": w,
                        "height": h,
                        "margin-left":ml,
                        "top": pt,
                        "left": pl
                    });
                    $("#sandbox").contents().find(".note-editor .note-editable").css({ "padding": "0", "width": w });
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
/*         const initValues = element.val.html();
 */        let position = element.val.position();
        let margin = element.val.css("margin-left");
        let proportions = [element.val.outerWidth(), element.val.outerHeight()];
        document.getElementById('sandbox').contentWindow.openSummernote(id, options);
        $("#sandbox").contents().find(".note-editor").on("click", function(e) {
            e.preventDefault();
        })
        //globals.isSummernoteOpen.status = true;
        $("#sandbox").contents().find(".note-editor").css({
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
        $("#sandbox").contents().find(".note-editor .note-editable").css({ "padding": "0", "width": proportions[0] });
        element.toolbox.item.hide();

/*         $("#sandbox").contents().find("#saveSummernote").on("click", (event) => {
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

        $("#sandbox").contents().find("#closeSummernote").on("click", (event) => {
            element.val.css({ "visibility": "visible" });
            console.log("Closing summernote!");
            document.getElementById('sandbox').contentWindow.openSummernote(id, "destroy").then(_ => {
                globals.isSummernoteOpen.status = false;
                element.val.html(initValues);
            });
            globals.clearWidget();
            element.toolbox.item.show();
        }) */
        return null;
    }

    return(
        <Iframe
        url={source}
        width="100%"
        height="100%"
        id="sandbox"
        className="sandbox mx-auto"
        onLoad={
            () => {
                if(source.length === 0)
                    return false;
                console.log("Sandbox is fully loaded!");
                $("#sandbox").contents().find("body").css({
                    "padding-left":"5px",
                    "padding-right":"5px"
                });
                document.getElementById("overlay").style.display = "none";
                disableClicks();
                getEditableContent();
            }
        }
        />
    )
}

export default Sandbox;