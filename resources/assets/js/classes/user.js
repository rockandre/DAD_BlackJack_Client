'use strict';

/** Helper class for Errors **/

class User  {

    constructor(){
        this.id = '';

        this.name =  '';

        this.email = '';

        this.password = '';

        this.password_confirmation = '';

        this.nickname = '';

        this.access_token = '';

        this.blocked = false;

        this.admin = false;

        this.total_points = 0;

        this.total_games_played = 0;

        this.avatar = 'blank.png';
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
        this.id = auth_user.id;
        this.name = auth_user.name;
        this.email = auth_user.email;
        this.nickname = auth_user.nickname;
        this.admin = auth_user.admin == 0 ? false : true;
        this.blocked = auth_user.blocked == 0 ? false : true;
        this.total_points = auth_user.total_points;
        this.total_games_played = auth_user.total_games_played;
        this.avatar = auth_user.avatar;
    }
    
    logged()
    {
        return (this.access_token != '' ? true : false);
    }

};


module.exports = User;