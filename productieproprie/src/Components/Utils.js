import $ from 'jquery';
import Swal from 'sweetalert2';

/* The main url of the website*/
export const domain = "http://localhost/productieproprie/";

export const HTTP_GET = (key) =>{
    let map = HTTP_GET_ALL();

    if(map.has(key)){
        return map.get(key);
    }else {
        return null;
    }
}

export const HTTP_GET_ALL = () =>{
    let parts = window.location.search.substr(1).split("&");
    let map = new Map();

    for (let i = 0; i < parts.length; i++) {
        let temp = parts[i].split("=");
        map.set(decodeURIComponent(temp[0]), decodeURIComponent(temp[1]));
    }

    return map;
}
export const setCookie = (name,value,days) => {
    let expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
export const getCookie = (name) => {
    const nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i=0;i < ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
export const eraseCookie = (name) => {   
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

/**
 * 
 * @param {string} url Url to the server end point, EX: processors/editor.req.php
 * @param {string} method DEF: GET
 * @param {string} action Value of $_GET['action'] on the backend
 * @param {object} dataToSend Object containing get values you need to passs to the backend
 * @param {string} loader DEF: null, 
 * @returns Object data on success or object Error on error
 */
export const postMan = async(url, method='get', action, dataToSend, loader = null) =>{
    return await $.ajax({
        url: domain+url,
        method: method,
        dataType: 'json',
        data: {"action":action ,"data": dataToSend},
        success: (data) =>{
            return data;
        },
        error: (error) =>{
            console.error("Could not fetch data from the server, did you forget to change domain ?");
            console.error(error.responseText);
            return error;
        }
    })
}

export const postManPlus = async (url, method='post', action, dataToSend, files) =>{
    const formData = new FormData();
    formData.append("action", action);
    for(let i = 0; i < files.length; i++)
    {
        const file = files[i];
        formData.append("files[]", file, file.name);
    }
    formData.append("data", dataToSend);
    console.log(formData);
    $.ajax({
        url: domain+url,
        type: method,
        data: formData,
        async: true,
		cache: false,
		contentType: false,
		processData: false,
		timeout: 60000,
        success: (data) =>{
            console.log(data);
        },
        error: (error) =>{
            console.error("Could not fetch data from the server, did you forget to change domain ?");
            console.error(error.responseText);
            return error;
        }
    })
}

export const handleResponse = (response) =>{
    if(typeof response.success !== typeof undefined)
    {
        return toast(response.success);
    }else if(typeof response.error !== typeof undefined)
    {
        return etoast(response.error);
    }else if(typeof response.warning !== typeof undefined)
    {
        return wtoast(response.warning);
    }else if(typeof response.info !== typeof undefined)
    {
        return itoast(response.info);
    }
}

export const isNullOrUndefined = (i) =>{
    if(typeof i === typeof undefined)
        return true;
    else if(i === "undefined")
        return true;
    else if(i === null)
        return true;
}

export const toast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"success",title:t})};
export const etoast=async(o)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"error",title:o})};
export const wtoast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"warning",title:t})};
export const itoast=async(t)=>{Swal.mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:3e3}).fire({icon:"info",title:t})};