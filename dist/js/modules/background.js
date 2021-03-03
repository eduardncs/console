import element from './element.js';

const preview = () => {
    const background = element.val.css('background-image');
    $("#section_preview").css('background-image', background);
}

const save = async () => {
    const w = $("#scale_w").val();
    const h = $("#scale_h").val();
    const isChecked = $("#bg-repeat").is(":checked");
    const pos = $("#bg-position").val();
    const color = $("#bg-color").val();
    let repeat = "no-repeat";
    isChecked ? repeat = "repeat" : repeat = "no-repeat";
    element.setCSS(
        ['background-size','background-repeat','background-position','background-color'],
        [w+'% '+h+'%', repeat, pos, color]
    );
}

const selectMedia = async (url,prop) => {
$("#section_preview").css('background-image', "url("+url+")");
element.val.css('background-image',"url("+url+")");
element.setCSS(
    ["background-image"],
    ["url("+ url +")"]
    );
}

const populate = () =>{
    const item = element.val;
    const item_repeat = item.css('background-repeat');
    const item_position = item.css('background-position');
    let item_bgcolor = item.css('background-color');
    let item_size = item.css('background-size');

    item_size === "cover" ? item_size = "0% 0%" :
        item_size === "auto" ? item_size = "0% 0%" :
        item_size === "initial" ? item_size = "0% 0%" :
        item_size === "inhert" ? item_size = "0% 0%" : item_size = item_size;
    item_bgcolor === null ? item_bgcolor = "transparent" : item_bgcolor = item_bgcolor;

    item_size = item_size.split(" ");

    item_repeat === "repeat" ? $("#bg-repeat").prop('checked', true) : $("#bg-repeat").prop('checked', false);

    $("#bg-position").val(item_position);
    $("#bg-color").val(item_bgcolor);

    $('#scale_w').ionRangeSlider({
        min: 0,
        max: 100,
        from: item_size[0].replace("%", ""),
        type: 'single',
        step: 1,
        postfix: '%',
        prettify: false,
        hasGrid: true
    })
    $('#scale_h').ionRangeSlider({
        min: 0,
        max: 100,
        from: item_size[1].replace("%", ""),
        type: 'single',
        step: 1,
        postfix: '%',
        prettify: false,
        hasGrid: true
    })
}

export {
    preview,
    save,
    populate,
    selectMedia
}