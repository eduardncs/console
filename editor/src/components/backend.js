import $ from 'jquery';
import { toast, etoast, itoast, wtoast } from './globals';
import Utils from './utils';
import { User, Project } from './data';

/* The main url of the website*/
export const domain = "http://localhost/";
/**
 * 
 * @param {string} url Url to the server end point, EX: processors/editor.req.php
 * @param {string} method DEF: GET
 * @param {string} action Value of $_GET['action'] on the backend
 * @param {object} dataToSend Object containing get values you need to passs to the backend
 * @returns Object data on success or object Error on error
 */
export const postMan = async(url, method='get', action, dataToSend) =>{
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

export const getGlobals = async () =>{
    const user_cookie = JSON.parse(unescape(Utils.getCookie('NCS_USER')));
    const proj_cookie = Utils.getCookie('NCS_PROJECT');

    console.log(user_cookie);
    console.log(proj_cookie);

    const data = await postMan(
        "processors/editor.backend.req.php",
        'get',
        'reactGetGlobals',
        {
            "UserID":user_cookie.UID,
            "ProjectID":proj_cookie
        }
    )
    User.setUser = data.User;
    Project.setProject = data.Project;
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