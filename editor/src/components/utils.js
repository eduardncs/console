export default class Utils {
    static HTTP_GET = (key) =>{
        let map = this.HTTP_GET_ALL();

        if(map.has(key)){
            return map.get(key);
        }else {
            return null;
        }
    }

    static HTTP_GET_ALL = () =>{
        let parts = window.location.search.substr(1).split("&");
        let map = new Map();

        for (let i = 0; i < parts.length; i++) {
            let temp = parts[i].split("=");
            map.set(decodeURIComponent(temp[0]), decodeURIComponent(temp[1]));
        }

        return map;
    }
    static setCookie = (name,value,days) => {
        let expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    static getCookie = (name) => {
        const nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i=0;i < ca.length;i++) {
            let c = ca[i];
            while (c.charAt(0)===' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    }
    static eraseCookie = (name) => {   
        document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
}