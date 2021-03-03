import * as globals from "./globals.js";
import { geteditablecontent } from "./../editor.js";
import element from "./element.js";

var isColumn;

const rowModel = '<div class="d-flex bg-primary w-100 d-flex align-items-stretch row-e"></div>';
const colModel = '<div class="bg-white border col-e mt-1 mb-1 d-flex justify-content-center align-items-center"></div>';


const selectedRow = {
    rowContainerSandbox:null,
    rowContainerInternal:null,
    set val(val) {
        if(val === null)
            return;
        this.rowContainerSandbox = val[0];
        this.rowContainerInternal = val[1];
        this.rowSelected();
    },
    get val() {
        return [this.rowContainerSandbox, this.rowContainerInternal];
    },
    rowSelected: () =>{
        rows.val.forEach(row => {
            $(row).removeClass("elevation-2");
            $(row).removeClass("border");
        });
        columns.val.forEach(column => {
            $(column).removeClass("elevation-2");
            $(column).removeClass("border");
        });
        selectedRow.val[1].addClass("elevation-2");
        selectedRow.val[1].addClass("border");
        $("#remove_element_btn").prop("disabled",false);
        $("#remove_element_btn").removeClass("disabled");
        $("#add_column_btn").prop("disabled",false);
        $("#add_column_btn").removeClass("disabled");
        console.log(selectedRow);
    }
}

const selectedColumn = {
    colSandbox: null,
    colInternal: null,
    row : null,
    set val(val) {
        if(val === null)
            return;
        this.colSandbox = val[0];
        this.colInternal = val[1];
        this.row = val[2];
        this.columnSelected();
    },
    get val() {
        return [this.colSandbox, this.colInternal, this.row];
    },
    columnSelected: () =>{
        columns.val.forEach(column => {
            $(column).removeClass("elevation-2");
            $(column).removeClass("border");
        });
        selectedColumn.val[1].addClass("elevation-2");
        selectedColumn.val[1].addClass("border");
        $("#remove_element_btn").prop("disabled",false);
        $("#remove_element_btn").removeClass("disabled");
        $("#edit_element_btn").prop("disabled",false);
        $("#edit_element_btn").removeClass("disabled");
        $("#add_column_btn").prop("disabled",true);
        if(!$("#add_column_btn").hasClass("disabled"))
            $("#add_column_btn").addClass("disabled");
    }
}
const rows = {
    internal : [],
    get val(){
        return this.internal;
    },
    set val(val){
        this.internal = val;
    }
};
const columns = {
    internal : [],
    get val(){
        return this.internal;
    },
    set val(val) {
        this.internal = val;
    }
}

const fetchData =  _ => {
    if(columns.val.length > 0 || rows.val.length > 0)
        reset();
    const rowsInsideDiv = element.val.find(".row");
    if(rowsInsideDiv.length === 0)
        return console.log("Nothing in this section!");
    printRowsThenCols(rowsInsideDiv);
}

const reset = () =>{
    columns.val.forEach(element => {
        element.remove();
    })
    rows.val.forEach(element => {
        element.remove();
    })
    columns.val = [];
    rows.val = [];
    $("#rowsHolder").empty();
}

const printRowsThenCols = (rowsInsideDiv) =>{
    for (let i = 0; i < rowsInsideDiv.length; i++) {
        const row = rowsInsideDiv[i];
        const model = $(rowModel);
        model.attr("data-corespondent", $(row).data("panelid"));
        model.appendTo("#rowsHolder");
        model.html("<div class='text-center d-flex justify-content-center align-items-center'> Row </div>");
        rows.val.push(model);
        model.on("click", (event) => { selectRow(event);} );
        printColsInsideDiv(model,row);
    }
}

const printColsInsideDiv = (rowModel,row) =>{
    const cols = getColsInsideDiv(row);
    for (let i = 0; i < cols.length; i++) {
        const model = $(colModel);
        const $this = $(cols[i]);
        model.attr("data-corespondent",$this.data("panelid"));
        cols.length === 1 ? model.text("Full width") :
        cols.length === 2 ? model.text("Half width") :
        cols.length === 3 ? model.text("Third") :
        model.text("Quarter");
        model.appendTo(rowModel);
        columns.val.push(model);
        model.on("click", (event) => { event.stopPropagation(); event.preventDefault(); selectColumn(event,row); } );
    }
}

const selectRow = (event) => {
    selectedRow.val = [
        globals.sandbox.contents().find("."+$(event.currentTarget).data("corespondent")),
        $(event.currentTarget)
    ];
    isColumn = false;
}

const selectColumn = (event,row) => {
    const $this = $(event.currentTarget);
    selectedRow.val = null;
    selectedColumn.val = [
        globals.sandbox.contents().find("."+$this.data("corespondent")),
        $this,
        $(row)
    ];
    isColumn = true;
}

const getColsInsideDiv = (div) =>{
    const cols = $(div).find("div[class^='col-md-'],div[class*=' col-md-']");
    if(cols.length > 0)
        return cols;
    return 0;
}

const addRowManual = async () =>{
    const id = globals.ID();
    const last = element.val.find(".row").last();
    const itmToBeAdded = '<div class="row '+id+'" style="min-height: 100px;" editable="editable" data-panel="row" data-panelid="'+id+'"></div>';
    if(last.length === 0)
    {
        element.val.append(itmToBeAdded);
        element.addChield($(itmToBeAdded));
    }else{
        last.after(itmToBeAdded);
        element.addAfter($(last).data("panelid"),$(itmToBeAdded));

    }
    fetchData();
}

const addColManual = () => {
    const columns = selectedRow.val[0].find("div[class^='col-md-'],div[class*=' col-md-']");
    if(columns.length > 3)
    {
        return globals.notoast("Maximum number of columns achieved!");
    }
    const id = ID();
    const itmToBeAdded = $('<div class="col-md-3 ' + id + '" editable="editable" data-panel="column" data-panelid="' + id + '"></div>');
    const last = columns.last();
    if(last.length === 0)
        selectedRow.val[0].append(itmToBeAdded);
    else
        last.after(itmToBeAdded);
    
    updateColumns($(selectedRow.val[0])).then((result)=>{
        console.log(result);
        element.addColumn(
            result.rowClass,
            result.newClass,
            itmToBeAdded
        );
    })
    fetchData();
}

const updateColumns = async (row) =>{
    const columns = row.find("div[class^='col-md-'],div[class*=' col-md-']");
    const rowClass = row.data("panelid");
    let oldClass;
    let newClass;
    if(columns.length === 0)
        return new Object({
            "rowClass":rowClass,
            "newClass":"col-md-12"
        });

    for (let i = 0; i < columns.length; i++) {
        const column = $(columns[i]);
        oldClass = fetchOldClassNumber(column);
        newClass = fetchNewClassValue(columns);
        column.removeClass(oldClass);
        column.addClass(newClass);
    }
    return new Object({
        "rowClass":row.data("panelid"),
        "newClass":newClass
    });
}
const fetchOldClassNumber = (column) =>{
    let returnValue;
    const classes = column.attr("class").split(" ");
    classes.forEach(className => {
        if (className.indexOf("col-md-") >= 0) {
            returnValue = className;
        }
    })
    return returnValue;
}

const fetchNewClassValue = (columns) => {
    var x;
    for (let index = 0; index < columns.length; index++) {
        const el = $(columns[index]);
        if (el.is('[class*=col-md-]')) {
            const classes = el.attr("class").split(" ");
            classes.forEach(_e => {
                if (_e.indexOf("col-md-") >= 0) {
                    let n_c = "col-"+_e.split("-")[1] + "-" + 12 / columns.length;
                    n_c === "col-md-2.4" ? n_c = "col-md-2" : n_c = n_c;
                    x = n_c;
                }
            });
        }
    }
    return x;
}

const removeMe = () =>{
    if(isColumn === null)
        console.log("Nothing selected!");
    if(isColumn){
        const row = $(selectedColumn.val[2]);
        const column = (selectedColumn.val[0]).data("panelid");
        selectedColumn.val[0].remove();
        const columns = row.find("div[class^='col-md-'],div[class*=' col-md-']");
        const newClassValue = fetchNewClassValue(columns);
        updateColumns(row).then( () => {
            fetchData();
            element.removeColumn(
                row.data("panelid"),
                column,
                newClassValue
            );
        })
    }else{
        const prev = element.val;
        selectedRow.val[0].click();
        //element.checkToolboxAndTooltip();
        element.remove().then(_=>
            {
                prev.click();
                fetchData();
                geteditablecontent();
            })
        }
}

export {
fetchData,
addRowManual,
addColManual,
removeMe
}