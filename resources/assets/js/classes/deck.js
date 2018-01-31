'use strict';

/** Helper class for Errors **/

class Deck  {

    constructor(){

        this.id = ''

        this.cards =  [];

        this.active = '';

        this.complete = '';

        this.hidden_face_image_path = '';

        this.name = '';
    }

    set(field, value){

        this[field] = value;
    }

    get(field){

        if(this[field]) {
            return this[field];
        }
    }

    parse(deck)
    {
        this.id = deck.id;
        this.active = deck.active;
        this.complete = deck.complete;
        this.hidden_face_image_path = deck.hidden_face_image_path;
        this.name = deck.name;
    }

    constroy(deck) {
        this.id = deck.id;
        this.active = deck.active;
        this.complete = deck.complete;
        this.hidden_face_image_path = deck.hidden_face_image_path;
        this.name = deck.name;
    }

};


module.exports = Deck;