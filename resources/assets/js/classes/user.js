'use strict';

/** Helper class for Errors **/

class User  {

    constructor(){

        this.name =  '';

        this.email = '';

        this.password = '';

        this.password_confirmation = '';

        this.nickname = '';

        this.access_token = '';

        this.admin = false;
    }

    set(field, value){

        this[field] = value;
    }

    get(field){

        if(this[field]) {
            return this[field];
        }
    }

    parse(auth_user)
    {
        this.name = auth_user.name;
        this.email = auth_user.email;
        this.nickname = auth_user.nickname;
        this.admin = auth_user.admin == 0 ? false : true;
    }
    
    logged()
    {
        return (this.access_token != '' ? true : false);
    }

};


module.exports = User;