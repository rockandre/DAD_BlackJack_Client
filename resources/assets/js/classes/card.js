'use strict';

/** Helper class for Errors **/

class Card  {

    constructor(idAux, deck_idAux, imageAux, suiteAux, valueAux){

        this.id =  idAux;

        this.deck_id = deck_idAux;

        this.image = imageAux;

        this.suite = suiteAux;

        this.value = valueAux;
    }

    set(field, value){

        this[field] = value;
    }

    get(field){

        if(this[field]) {
            return this[field];
        }
    }

    parse(card)
    {
        this.id = card.id;
        this.deck_id = card.deck_id;
        this.path = card.path;
        this.suite = card.suite;
        this.value = card.value;
    }
};


module.exports = Card;