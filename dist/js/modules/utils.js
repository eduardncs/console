export default class Utils {
    static HTTP_GET(key){
        let map = this.HTTP_GET_ALL();

        if(map.has(key)){
            return map.get(key);
        }else {
            return null;
        }
    }

    static HTTP_GET_ALL(){
        let parts = window.location.search.substr(1).split("&");
        let map = new Map();

        for (let i = 0; i < parts.length; i++) {
            let temp = parts[i].split("=");
            map.set(decodeURIComponent(temp[0]), decodeURIComponent(temp[1]));
        }

        return map;
    }
}