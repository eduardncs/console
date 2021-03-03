import element from './element.js';

const preview = () => {
    const isFormated = element.val.attr("src").includes('/clients/' + BN + '/' + PN );
    let url = element.val.attr("src");
    if(!isFormated)
        url = '../clients/' + BN + '/' + PN + element.val.attr("src");

    $("#image_preview").attr("src",url);
    $("#img_alt").val(element.val.attr("alt"));
    $("#img_title").val(element.val.attr("title"));
}

const saveMeta = () => {
    const title = $("#img_title").val();
    const alt = $("#img_alt").val();
    element.val.attr("title",title);
    element.val.attr("alt",alt);
    element.changeAttributes(['title','alt'],[title,alt]);
}

const changeSource = (url) => {
    $("#image_preview").attr("src",url);
    element.val.attr("src",url);
    element.changeAttribute("src",url);
    console.log("It's done!");
}

export {
    preview,
    saveMeta,
    changeSource
};