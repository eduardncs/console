import * as globals from "./globals.js";
const tooltip = {
    /**
     * Create the blue tooltip describing what this item actualy is
     * @param {JQuery Object} parent 
     * @param {string} id 
     * @param {string} data 
     */
    createTooltip: (parent, id ,data) =>{
        /** Check if it doesnt allready exists */
        id = "tooltip" + id;

        const isFound = globals.sandbox.contents().find("#"+id);
        if(isFound.length > 0)
        {
            const prevObject = new Object({
                id: id,
                item: isFound,
                parent: parent
            });
            globals.tooltips.val.push(prevObject);
            return prevObject;
        }

        const rawitem = $("<div class='tltip' style='position:absolute; background-color:#3899EC; z-index:9999; display:none;' id='" + id + "'><span style='color:white;'><i>" + globals.capitalize(data) + "</i></span></div>");
        rawitem.appendTo(globals.sandbox.contents().find("body"));

        const $this = new Object({
            id: id,
            item: rawitem,
            parent: parent
        });
        globals.tooltips.val.push($this);
        return $this;
    },    
    /**
    * Adjust position of the tooltip acording to the Curent position and proportions of the element
    * It's done async no not impact site performance
    * @param {string} _tooltip
    */
    adjustTooltipPositionAsync: async(_tooltip) => {
        let paneldata = _tooltip.parent.data("panel");
        let height = _tooltip.parent.outerHeight();
        let left = _tooltip.parent.offset().left;
        let top = _tooltip.parent.offset().top;

        paneldata == "section" ? top += 0 :
            paneldata == "column" ? top += 0 :
            paneldata == "portofolio" ? top += 0 :
            paneldata == "footer" ? top += 0 :
            paneldata == "header" ? top += 10 :
            top += 0;

        paneldata == "section" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata == "column" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata == "portofolio" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata == "footer" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata == "header" ? left += _tooltip.parent.innerWidth() / 2 :
            left += 0;

        $(_tooltip.item).css({
            top: top - _tooltip.item.innerHeight() / 2 + "px",
            left: left + "px",
        });
    }
}
export default tooltip;