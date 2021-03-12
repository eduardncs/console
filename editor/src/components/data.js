const User = {
    internalUser : null,
    /**
     * @param {any} value
     */
    set setUser(value){
        if(value === null || typeof value === typeof undefined)
            return;
        this.internalUser = value;
    },
    get getUser(){
        return this.internalUser;
    }
}

const Project = {
    internalProject : null,
    /**
     * @param {any} value
     */
    set setProject(value){
        if(value === null || typeof value === typeof undefined)
            return;
        this.internalProject = value;
    },
    get getProject(){
        return this.internalProject;
    }
}
export default User;
export {
    User,
    Project
}