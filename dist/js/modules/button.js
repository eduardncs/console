import element from "./element.js";

const preview = () => {
    const preview = $("#button-preview");
    const classes = element.val.attr("class").split(" ");
    const text = element.val.text();
    for (let i = 0; i < classes.length; i++) {
        const cls = classes[i];
        if(cls === "mt-5" || cls === "mt-4" || cls === "mt-3" || cls === "mt-2" || cls === "mt-1" || cls === "mb-5" || cls === "mb-4" || cls === "mb-3" || cls === "mb-2" || cls === "mb-1")
            continue;
        preview.addClass(cls);
    }
    preview.text(text);
    $("#text-to-display").val(text);
    $("#btn-link-href").val(element.val.attr("href"));

    if(element.val.attr("class").includes("btn-outline"))
        $("#btn-outline").prop("checked",true);
    else if(element.val.attr("class").includes("btn-rounded"))
        $("#btn-rounded").prop("checked",true);
    else
        $("#btn-default").prop("checked",true);

    let size = "2";
    element.val.hasClass("btn-xs") ? size = 0 :
    element.val.hasClass("btn-sm") ? size = 1 :
    element.val.hasClass("btn-lg") ? size = 3:
    element.val.hasClass("btn-xl") ? size = 4 :
    element.val.hasClass("btn-block") ? size = 5 :
    size = 2;
    $('#btn-size').ionRangeSlider({
        grid: true,
        from: size,
        values: [
            'XS', 'S', 'M', 'L', 'XL', 'XXL'
        ],
        onFinish: (data) => {
            const value = data.from;
            let className = "";
            value === 0 ? className = 'btn-xs' :
            value === 1 ? className = 'btn-sm' :
            value === 3 ? className = 'btn-lg' :
            value === 4 ? className = 'btn-xl' :
            value === 5 ? className = 'btn-block' :
            value = "";
            element.addClass(value,['btn-xs','btn-sm','btn-lg','btn-xl','btn-block']);
        }
    })
}

const changeDisplayStyle = (style) => {
    const preview = $("#button-preview");
    if(style == "default"){
        if(element.val.hasClass('btn-squared'))
            return;

        if(preview.hasClass("btn-squared"))
            return;

        preview.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info btn-squared btn-rounded');
        element.val.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info btn-squared btn-rounded');
        
        preview.addClass("btn-squared");
        element.addClass('btn-squared',[
            'btn-outline-primary',
            'btn-outline-secondary',
            'btn-outline-dark',
            'btn-outline-light',
            'btn-outline-success',
            'btn-outline-danger',
            'btn-outline-warning',
            'btn-outline-info',
            'btn-rounded',
            'btn-squared',
            'btn-primary',
            'btn-secondary',
            'btn-dark',
            'btn-light',
            'btn-green',
            'btn-success',
            'btn-danger',
            'btn-warning',
            'btn-info',
            'btn-rounded',
            'btn-squared']);
    }else if(style == "rounded"){
        if(element.val.hasClass("btn-rounded"))
            return;

        if(preview.hasClass("btn-rounded"))
            return;

        preview.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-squared btn-rounded');
        element.val.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-squared btn-rounded');
        
        preview.addClass("btn-rounded");
        element.addClass('btn-rounded',[
            'btn-outline-primary',
            'btn-outline-secondary',
            'btn-outline-dark',
            'btn-outline-light',
            'btn-outline-success',
            'btn-outline-danger',
            'btn-outline-warning',
            'btn-outline-info',
            'btn-rounded',
            'btn-squared']);
    }else {
        if(element.val.is('[class^=btn-outline]'))
            return;

        if(preview.is('[class^=btn-outline]'))
            return;

        preview.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info btn-squared btn-rounded');
        element.val.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info btn-squared btn-rounded');
        preview.addClass("btn-outline-primary");
        
        element.val.addClass('btn-outline-primary');
        element.addClass('btn-outline-primary',[
            'btn-outline-primary',
            'btn-outline-secondary',
            'btn-outline-dark',
            'btn-outline-light',
            'btn-outline-success',
            'btn-outline-danger',
            'btn-outline-warning',
            'btn-outline-info',
            'btn-rounded',
            'btn-squared',
            'btn-primary',
            'btn-secondary',
            'btn-dark',
            'btn-light',
            'btn-green',
            'btn-success',
            'btn-danger',
            'btn-warning',
            'btn-info'
        ]);
    }
}

const changeColor = (colorClass) => {
    const preview = $("#button-preview");
    const isOutline = element.val.attr("class").includes("btn-outline");
    console.log(element.val.attr("class"));
    if(colorClass === 'transparent')
        colorClass = '';
    else
        if(isOutline)
            colorClass = "btn-outline-"+colorClass;
            else
                colorClass = "btn-"+colorClass

    preview.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info');
    element.val.removeClass('btn-outline-primary btn-outline-secondarybtn-outline-dark btn-outline-light btn-outline-success btn-outline-danger btn-outline-warning btn-outline-info btn-primary btn-secondary btn-dark btn-light btn-success btn-danger btn-warning btn-info');
    preview.addClass(colorClass);
    element.addClass(colorClass,[
        'btn-outline-primary',
        'btn-outline-secondary',
        'btn-outline-dark',
        'btn-outline-light',
        'btn-outline-success',
        'btn-outline-danger',
        'btn-outline-warning',
        'btn-outline-info',
        'btn-primary',
        'btn-secondary',
        'btn-dark',
        'btn-light',
        'btn-success',
        'btn-danger',
        'btn-warning',
        'btn-info'])
}

const changeText = (text) => {
    const preview = $("#button-preview");
    preview.text(text);
    element.val.text(text);
    element.saveText(text);
}

const changeHref = (href) =>{
    const preview = $("#button-preview");
    preview.attr("href",href);
    element.val.attr("href",href);
    element.changeAttribute("href",href);
}

export {
    preview,
    changeDisplayStyle,
    changeColor,
    changeText,
    changeHref
}