import * as globals from "./globals.js";
const menu = {
    sortables: {
        value: null,
        get function() { return this.value; },
        set function(val) {
            Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
        },
        add: (val) => {
            Array.prototype.isArray(this.value) ? this.value.push(val) : this.value = val;
        }
    },
    createSortable: () => {
        let nestedSortables = $(".nested-sortable");
        for (let i = 0; i < nestedSortables.length; i++) {
            menu.sortables.add = new Sortable(nestedSortables[i], {
                group: 'nested',
                ghostClass: 'active',
                animation: 150,
                fallbackOnBody: true,
                onStart: function(event) {
                    //console.log(event);
                },
                onUpdate: (event) => {
                    let inverted = false;
                    let neighbour = $(event.item).prev();
                    if (neighbour.length === 0) {
                        neighbour = $(event.item).next();
                        inverted = true;
                    }
                    if (neighbour.length === 0) { console.error("This element has no neighbours"); return; }
                    menu.changeIndex($(event.item).data("link"), neighbour.data("link"), inverted);
                },
                onAdd: (event) => {
                    if ($(event.item).data("isfolder") == true) {
                        globals.notoast("FE: Folders cannot be moved inside other folders yet ...");
                        return;
                    }
                    let oldParent = $(event.from).data("parent");
                    let newParent = $(event.to).data("parent");
                    if (typeof oldParent === typeof undefined)
                        oldParent = "menu-container-master";
                    if (typeof newParent === typeof undefined)
                        newParent = "menu-container-master";
                    let neighbour = $(event.item).prev();
                    let inverted = false;
                    let isFirst = false;
                    if (neighbour.length === 0) {
                        neighbour = $(event.item).next();
                        inverted = true;
                    }
                    if (neighbour.length === 0) {
                        console.log("This element has no neighbours");
                        console.log("So it's first one in parent!");
                        isFirst = true;
                        neighbour = $(event.item).parent().parent();
                    }
                    console.log(neighbour);
                    menu.move($(event.item).data("link"), newParent, neighbour.data("link"), inverted, isFirst);
                }
            });
        }
    },
    destroySortable: () => {
        for (let i = 0; i < menu.sortables.length; i++) {
            const e = menu.sortables[i];
            e.destroy();
        }
    },
    move: (who, to, neighbour, inverted, isFirst) => {
        let rawItem = globals.sandbox.contents().find("nav ." + who).detach();
        if (rawItem.length === 0) { console.error("Raw item was not found!!!"); return; }
        //No longer a dropdown item
        //So ajax request to get an item skeleton
        $.ajax({
            url: '../clients/' + BN + '/' + PN + '/core/data.php',
            dataType: "json",
            success: function(data) {
                let item;
                to === "menu-container-master" ? item = $(data['MENU_BASE'][1]) :
                    item = $(data['MENU_TREE'][1]);
                //Trebuie vazut UNDE ii dai append
                if (isFirst) {
                    globals.sandbox.contents().find("." + neighbour + " .dropdown-menu").append(item);
                } else {
                    !inverted ? globals.sandbox.contents().find("." + neighbour).after(item) :
                        globals.sandbox.contents().find("." + neighbour).before(item)
                }
                let anchor = item.find("a");
                let exanchor = rawItem.find("a");

                if (exanchor.length === 0)
                    exanchor = rawItem; //rawITem is the exanchor
                if (anchor.length === 0)
                    anchor = item; //item is the anchor
                item.removeClass(item.data("link"));
                item.attr("data-link", who);
                item.addClass(who);
                anchor.attr("href", exanchor.attr("href"));
                anchor.text(exanchor.text());
                anchor.attr("target", exanchor.attr("target"));
                // Now make the ajax call to backend
                const values = { "action": "move", "data": { "Key": who, "To": to, "Neighbour": neighbour, "Inverted": inverted, "isFirst": isFirst } };
                console.log(values);
                $.ajax({
                    url: "processors/editor.req.php",
                    type: "post",
                    data: values,
                    success: function(data) {
                        $("#ajax").html(data);
                    },
                    error: function() {
                        globals.notoast("Something went wrong ... please reload the page");
                        return;
                    }
                });
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
                return;
            }
        })

    },
    changeIndex: (who, neighbour, inverted) => {
        console.log("Changing index");
        let rawItem = globals.sandbox.contents().find("nav ." + who).detach();
        if (rawItem.length === 0) { console.error("Raw item was not found!!!"); return; };
        !inverted ? globals.sandbox.contents().find("." + neighbour).after(rawItem) :
            globals.sandbox.contents().find("." + neighbour).before(rawItem)
        const values = { "action": "changeIndex", "data": { "Key": who, "Neighbour": neighbour, "Inverted": inverted } };
        $.ajax({
            url: "processors/editor.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
                return;
            }
        });
    },
    addLink: (id) => {
        if (id === null)
            return;
        const values = { "action": "addLink", "data": { "Key": id, "isFolder": false } };
        $.ajax({
            url: "processors/editor.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
                return;
            }
        });
        $.ajax({
            url: '../clients/' + BN + '/' + PN + '/core/data.php',
            dataType: "json",
            success: function(data) {
                let item = $(data['MENU_BASE'][1]);
                globals.sandbox.contents().find("._R3xd13dsc").append(item);
                let anchor = item.find("a");
                item.removeClass(item.data("link"));
                item.attr("data-link", id);
                item.addClass(id);
                anchor.attr("href", "#");
                anchor.text("New link");
                anchor.attr("target", "_self");
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
                return;
            }
        })
    },
    addFolder: (id) => {
        if (id === null)
            return;
        const values = { "action": "addLink", "data": { "Key": id, "isFolder": true } };
        $.ajax({
            url: "processors/editor.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
            }
        });
        $.ajax({
            url: '../clients/' + BN + '/' + PN + '/core/data.php',
            dataType: "json",
            success: function(data) {
                let item = $(data['MENU_TREE'][0]);
                globals.sandbox.contents().find("._R3xd13dsc").append(item);
                let anchor = item.find("a");
                item.removeClass(item.data("link"));
                item.attr("data-link", id);
                item.addClass(id);
                anchor.attr("href", "javascript:void(0)");
                anchor.text("New folder");
                globals.sandbox.contents().find("._R3xd13dsc").append($(data['MENU_TREE'][2]));
            },
            error: function() {
                globals.notoast("Something went wrong ... please reload the page");
                return;
            }
        })
    },
    /**
     * Send an async ajax request to remove link
     * @param {string} link 
     */
    removeLink: (link) => {
        if (link === null)
            return;
        const values = { "action": "removeLink", "data": { "Key": link, "isFolder": false } };
        $.ajax({
            url: "processors/editor.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
            },
            error: function() {
                globals.notoast("Something went wrong...");
            }
        });
        globals.sandbox.contents().find("." + link).remove();
    },
    /**
     * Send a request to backend to edit a certain link
     * @param {array} val 
     */
    editLink: (val) => {
        const values = { "action": "editLink", "data": { "Key": val[0]['value'], "isFolder": val[1]['value'], "Text": val[2]['value'], "Href": val[3]['value'], "Target": val[4]['value'] } }
        $.ajax({
            url: "processors/editor.req.php",
            type: "post",
            data: values,
            success: function(data) {
                $("#ajax").html(data);
                let itm = globals.sandbox.contents().find("." + val[0]['value']);
                let anchor = itm.find("a");
                if (anchor.length === 0)
                    anchor = itm;
                anchor.text(val[2]['value']);
                $("." + val[0]['value'] + " .text-name").first().text(val[2]['value']);
            },
            error: function() {
                globals.notoast("Something went wrong...");
            }
        });
    }
}
export default menu;