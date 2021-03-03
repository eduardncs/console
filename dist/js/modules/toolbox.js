/**
 *  Author : Eduard Neacsu
 */
import * as globals from './globals.js';
const toolbox = {
    /**
     * Create the toolbox for each element 
     * toolbox contains the buttons to edit that particular element
     * @param {string} id 
     * @param {Array} options 
     * @param {Array} func 
     * @param {JQuery Object} parent 
     */
    createToolbox: (id,options,func, parent) => {
        /** Check if it doesnt allready exists */
        id = "toolbox" + id;

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

        let head = '<div class="toolbox" style="text-transform:none; letter-spacing:0px;z-index:9999;position:absolute; height:40; display:inline-block; " id="' + id + '">';
        let body = null;
        let footer = '</div>';
        for (let index = 0; index < options.length; index++) {
            body === null ?
                body = '<a href="javascript:void(0)" onclick="' + func[index] + '" style="text-transform:none; letter-spacing:0; border-radius:25px; border: 2px solid; color:black; border-radius: 25px; border: 1px solid gray; border-color:black; background-color:white;" class="btn btn-default text-sm">' + options[index] + '</a>' :
                body += " " + '<a href="javascript:void(0)" onclick="' + func[index] + '" style="text-transform:none; letter-spacing:0; border-radius:25px; border: 2px solid; color:black; border-radius: 25px; border: 1px solid gray; border-color:black; background-color:white;" class="btn btn-default text-sm">' + options[index] + '</a>'
        }
        
        const $item = $(head + body + footer);
        $item.appendTo(globals.sandbox.contents().find("body"));
        $item.hide();
        const $this = new Object({
            id: id,
            item: $item,
            parent: parent
        })
        globals.toolboxes.val.push($this);
        return $this;
    },
    /**
     * Adjust position of the toolbox acording to the Curent position and proportions of the element
     */
    adjustToolboxPositionAsync : async (_toolbox) => {
        let data = _toolbox.parent.data("panel");
        let height = _toolbox.parent.outerHeight();
        let left = _toolbox.parent.offset().left;
        let top = _toolbox.parent.offset().top;

        data == "section" ? top += height - 50 :
            data == "portofolio" ? top += height - 50 :
            data == "footer" ? top += 0 :
            top += height + 20;

        left += _toolbox.parent.innerWidth() / 2;
        $(_toolbox.item).css({
        top: top - _toolbox.item.innerHeight() / 2 + "px",
        left: left - _toolbox.item.innerWidth() / 2 + "px",
        });
    }
}
export default toolbox;