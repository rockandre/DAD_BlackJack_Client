'use strict';

/** Helper class for Errors **/

class Card  {

    constructor(){

        this.value =  '';

        this.suite = '';

        this.deckId = '';

        this.path = '';
    }

    set(field, value){

        this[field] = value;
    }

    get(field){

        if(this[field]) {
            return this[field];
        }
    }
};


module.exports = Card;