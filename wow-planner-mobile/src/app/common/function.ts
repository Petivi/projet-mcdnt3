import * as globals from '../../assets/data/globals';
import * as jQuery from 'jquery';

export function setGrowl(obj: any): string {
    return '<h5>' + obj.title + '</h5><div>' + obj.body + '</div>';
}

export function setHeaderEditFixed() {
    jQuery(window).scroll(function () {
        var height = jQuery(window).scrollTop();
        if (height >= 100) {
            jQuery('.menu').addClass('scrollOn');
            jQuery('#imgWow').css('width', '150px');
        } else {
            jQuery('.menu').removeClass('scrollOn');
            jQuery('#imgWow').css('width', '400px');
        }
    });
}

export function setTtItem(ttItemG, ttItemD, character): Promise<any> {
    return new Promise((resolve, reject) => {
        let iconUrl: string = globals.blizzardIconUrl;
        ttItemG.forEach(ig => {
            if (ig.id === 1 && character.head.id) {
                if (character.head.icon) character.head.imgUrl = iconUrl + character.head.icon + '.jpg';
                ig.item = character.head;
            }
            if (ig.id === 2 && character.neck.id) {
                if (character.neck.icon) character.neck.imgUrl = iconUrl + character.neck.icon + '.jpg';
                ig.item = character.neck;
            }
            if (ig.id === 3 && character.shoulder.id) {
                if (character.shoulder.icon) character.shoulder.imgUrl = iconUrl + character.shoulder.icon + '.jpg';
                ig.item = character.shoulder;
            }
            if (ig.id === 4 && character.back.id) {
                if (character.back.icon) character.back.imgUrl = iconUrl + character.back.icon + '.jpg';
                ig.item = character.back;
            }
            if (ig.id === 5 && character.chest.id) {
                if (character.chest.icon) character.chest.imgUrl = iconUrl + character.chest.icon + '.jpg';
                ig.item = character.chest;
            }
            if (ig.id === 6 && character.wrist.id) {
                if (character.wrist.icon) character.wrist.imgUrl = iconUrl + character.wrist.icon + '.jpg';
                ig.item = character.wrist;
            }
            if (ig.id === 7 && character.hands.id) {
                if (character.hands.icon) character.hands.imgUrl = iconUrl + character.hands.icon + '.jpg';
                ig.item = character.hands;
            }
            if (ig.id === 8 && character.waist.id) {
                if (character.waist.icon) character.waist.imgUrl = iconUrl + character.waist.icon + '.jpg';
                ig.item = character.waist;
            }
        });
        ttItemD.forEach(id => {
            if (id.id === 9 && character.legs.id) {
                if (character.legs.icon) character.legs.imgUrl = iconUrl + character.legs.icon + '.jpg';
                id.item = character.legs;
            }
            if (id.id === 10 && character.feet.id) {
                if (character.feet.icon) character.feet.imgUrl = iconUrl + character.feet.icon + '.jpg';
                id.item = character.feet;
            }
            if (id.id === 11 && character.finger1.id) {
                if (character.finger1.icon) character.finger1.imgUrl = iconUrl + character.finger1.icon + '.jpg';
                id.item = character.finger1;
            }
            if (id.id === 12 && character.finger2.id) {
                if (character.finger2.icon) character.finger2.imgUrl = iconUrl + character.finger2.icon + '.jpg';
                id.item = character.finger2;
            }
            if (id.id === 13 && character.trinket1.id) {
                if (character.trinket1.icon) character.trinket1.imgUrl = iconUrl + character.trinket1.icon + '.jpg';
                id.item = character.trinket1;
            }
            if (id.id === 14 && character.trinket2.id) {
                if (character.trinket2.icon) character.trinket2.imgUrl = iconUrl + character.trinket2.icon + '.jpg';
                id.item = character.trinket2;
            }
            if (id.id === 15 && character.main_hand.id) {
                if (character.main_hand.icon) character.main_hand.imgUrl = iconUrl + character.main_hand.icon + '.jpg';
                id.item = character.head;
            }
            if (id.id === 16 && character.off_hand.id) {
                if (character.off_hand.icon) character.off_hand.imgUrl = iconUrl + character.off_hand.icon + '.jpg';
                id.item = character.off_hand;
            }
        });
        resolve({ ttItemD: ttItemD, ttItemG: ttItemG });
    });
}