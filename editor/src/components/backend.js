import $ from 'jquery';
import { toast, etoast, itoast, wtoast } from "./globals";

/* The main url of the website*/
const domain = "http://localhost/";
/**
 * 
 * @param {string} url Url to the server end point, EX: processors/editor.req.php
 * @param {string} method DEF: GET
 * @param {string} action Value of $_GET['action'] on the backend
 * @param {object} dataToSend Object containing get values you need to passs to the backend
 * @returns Object data on success or object Error on error
 */
const postMan = async(url, method='get', action, dataToSend) =>{
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

const handleResponse = (response) =>{
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

export {
    domain,
    postMan,
    handleResponse
}