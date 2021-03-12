import { tooltips, Capitalize } from './globals';
import $ from 'jquery';

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

        const isFound = $("#sandbox").contents().find("#"+id);
        
        if(isFound.length > 0)
        {
            const prevObject = {
                id: id,
                item: isFound,
                parent: parent
            };
            tooltips.val.push(prevObject);
            return prevObject;
        }

        const rawitem = $(`
        <div class='tltip' style='position:absolute; background-color:#3899EC; z-index:9999; display:none;' id='" + id + "'>
        <span class="text-sm" style='color:white;'><i>` + Capitalize(data) + `</i></span>
        </div>`);
        rawitem.appendTo($("#sandbox").contents().find("body"));

        const $this = {
            id: id,
            item: rawitem,
            parent: parent
        };
        tooltips.val.push($this);
        return $this;
    },    
    /**
    * Adjust position of the tooltip acording to the Curent position and proportions of the element
    * It's done async to not impact site performance too much
    * @param {string} _tooltip
    */
    adjustTooltipPositionAsync: async(_tooltip) => {
        let paneldata = _tooltip.parent.data("panel");
        let left = _tooltip.parent.offset().left;
        let top = _tooltip.parent.offset().top;

        paneldata === "section" ? top += 0 :
            paneldata === "column" ? top += 0 :
            paneldata === "portofolio" ? top += 0 :
            paneldata === "footer" ? top += 0 :
            paneldata === "header" ? top += 10 :
            top += 0;

        paneldata === "section" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata === "column" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata === "portofolio" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata === "footer" ? left += _tooltip.parent.innerWidth() / 2 :
            paneldata === "header" ? left += _tooltip.parent.innerWidth() / 2 :
            left += 0;

        $(_tooltip.item).css({
            top: top - _tooltip.item.innerHeight() / 2 + "px",
            left: left + "px",
        });
    }
}
export default tooltip;