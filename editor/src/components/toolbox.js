import $ from 'jquery';
import { toolboxes, loadExtModule } from './globals';

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

        const isFound = $("#sandbox").contents().find("#"+id);
        if(isFound.length > 0)
        {
            const prevObject = {
                id: id,
                item: isFound,
                parent: parent
            };
            toolboxes.val.push(prevObject);
            return prevObject;
        }

        let head = '<div class="toolbox" style="z-index:9999;position:absolute; height:40; display:inline-block; " id="' + id + '">';
        let body = '';
        let footer = '</div>';
        for (let i = 0; i < options.length; i++) {
            const btn = '<a href="javascript:void(0)" style="border-radius:25px;" class="btn btn-default bg-white border text-sm text-black" id="btn_'+ i + id + '">' + options[i] + '</a>';
            body += btn;
        }
        
        const $item = $(head + body + footer);
        $item.appendTo($("#sandbox").contents().find("body"));
        for(let j = 0; j < options.length; j++){
            const $btn = $item.find("#btn_"+ j + id);
            $btn.on("click",(e)=>{
                loadExtModule("tools/"+func[j]);
            })
        }
        
        $item.hide();
        const $this = {
            id: id,
            item: $item,
            parent: parent
        };
        toolboxes.val.push($this);
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

        data === "section" ? top += height - 50 :
            data === "portofolio" ? top += height - 50 :
            data === "footer" ? top += 0 :
            top += height + 20;

        left += _toolbox.parent.innerWidth() / 2;
        $(_toolbox.item).css({
        top: top - _toolbox.item.innerHeight() / 2 + "px",
        left: left - _toolbox.item.innerWidth() / 2 + "px",
        });
    },
    show : (_toolbox) =>{
        $("#sandbox").contents().find(".toolbox").each((index,element)=>{
            $(element).hide();
        })
        _toolbox.item.show();
    }
}
export default toolbox;