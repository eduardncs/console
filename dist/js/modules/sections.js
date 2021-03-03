import * as globals from './globals.js';
import element from './element.js';
import models from './models.js';

const sections = {
    clear : () =>{
        $("#sections-container").empty();
    },
    update: async () => {
        sections.clear();
        const sec = globals.sandbox.contents().find("section");
        for (let index = 0; index < sec.length; index++) {
            const el = sec[index];
            const thumbnail = sections.createThumbnail($(sec[index]));
            const $this = $("<li class='list-group-item bg-primary section text-center d-flex' style='height:35px;' data-index='" + index + "' data-corespondent='" + $(el).data("panelid") + "'><div class='align-self-center'><span class='float-left mr-2 bg-white' style='width:50px; height:27px;'>" + thumbnail + "</span> Section</div></li>");
            $this.appendTo("#sections-container")
            $this.hover((e) => {
                $this.addClass("elevation-3");
                $this.addClass("border");
            }, (e) => {
                $this.removeClass("border");
                $this.removeClass("elevation-3");
            });
            $this.css("cursor", "move");
        }

        let st = document.getElementById('sections-container');
        new Sortable(st, {
            filter: '.notsection',
            animation: 150,
            ghostClass: 'bg-warning',
            group: {
                name: 'shared',
                pull: 'clone'
            },
            onChoose: (event) => {
                let corespondent = globals.sandbox.contents().find("." + $(event.item).data("corespondent"));
                corespondent.click();
                globals.scrollToElement();
            },
            onAdd: (event) => {
                //Add new section to sandbox.contents
                const modelId = $(event.item).data("id");
                const modelType= $(event.item).data("model");
                const sectionModel = $(models(modelType,modelId));
                const liModel = $(event.item);
                const nx = liModel.next();
                let neighbour = null;
                let addAfterNeighbour = false;
                if(nx.length === 0)
                {
                    const bf = liModel.prev();
                    if(bf.length === 0)
                    {
                        console.error("Cannot find any neighbours");
                        return;
                    }else{
                        //Vecinul e in spate 
                        //trebuie pus dupa vecinul din spate
                        neighbour = bf;
                        addAfterNeighbour = true;
                    }
                }else{
                    //are vecin in fata, deci este pus inaintea celui din fata
                    neighbour = nx;
                }
                //Mai ramane o situatie in case amandoua sunt inexistente si trebuie adaugat primul in lista
                //O sa o fac in versiuniile viitoare

                sectionModel.attr("editable","editable");
                sectionModel.addClass($(event.item).data("corespondent"));
                sectionModel.attr("data-panelid",$(event.item).data("corespondent"));
                sectionModel.attr("data-panel","section");
                sectionModel.find("[editable*=editable]").each(function(){
                    let id = globals.ID();
                    $(this).attr("data-panelid",id);
                    $(this).addClass(id);
                });
                if(addAfterNeighbour)
                {
                    //Adaugi dupa vecin
                    globals.sandbox.contents().find("."+neighbour.data("corespondent")).after(sectionModel);
                    element.addAfter(neighbour.data("corespondent"),sectionModel);
                }else{
                    //Adaugi inaintea vecinului
                    globals.sandbox.contents().find("."+neighbour.data("corespondent")).before(sectionModel);
                    element.addAfter(neighbour.data("corespondent"),sectionModel);
                }
            },
            onEnd: (event) => {
                /* Do nothing if indexes are not changed */
                if (event.oldIndex === event.newIndex)
                    return;
                const items = $("#sections-container li");
                if (event.newIndex == 0) {
                    element.moveBefore($(items[event.newIndex + 1]).data("corespondent"));
                } else {
                    element.moveAfter($(items[event.newIndex - 1]).data("corespondent"));
                }
            }
        });
    },
    /**
    * Create a mini thumbnail for the section
    */
   createThumbnail: (sec) => {
    return "<div class='border h-100 w-100' style='background:" + sec.css("background") + "'></div>";
    }
}
export default sections;