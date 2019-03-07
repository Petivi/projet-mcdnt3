import { Component, OnInit, OnDestroy, Input } from '@angular/core';

import { AppService } from '../app.service';

import { Item, itemSlot, Word } from '../model/app.model';

import * as globals from '../../assets/data/globals';

@Component({
    selector: 'affichage-personnage-cpt',
    templateUrl: './affichagePersonnage.component.html',
})
export class AffichagePersonnageComponent implements OnInit, OnDestroy {
    @Input() words: Word[] = [];
    selectedItem: Item;
    displayItemDetailPerso: boolean = false;
    ttBonusStats: any[] = [];
    libelleAttack: string;
    ttItemGauche: itemSlot[] = [
        { id: 1, class: 4, inventoryType: 1, imgUrl: 'assets/img/inventoryslot_head.jpg', item: null },
        { id: 2, class: 4, inventoryType: 2, imgUrl: 'assets/img/inventoryslot_neck.jpg', item: null },
        { id: 3, class: 4, inventoryType: 3, imgUrl: 'assets/img/inventoryslot_shoulder.jpg', item: null },
        { id: 4, class: 4, inventoryType: 16, imgUrl: 'assets/img/inventoryslot_chest.jpg', item: null }, //cape mais pas encore l'image
        { id: 5, class: 4, inventoryType: 5, imgUrl: 'assets/img/inventoryslot_chest.jpg', item: null },
        /* { class: 4, inventoryType: 6, imgUrl: 'assets/img/inventoryslot_shirt.jpg', item: null }, */ //sait pas ce que c'est 
        /* { class: 4, inventoryType: 4, imgUrl: 'assets/img/inventoryslot_tabard.jpg', item: null }, */
        { id: 6, class: 4, inventoryType: 9, imgUrl: 'assets/img/inventoryslot_wrists.jpg', item: null },
        { id: 7, class: 4, inventoryType: 10, imgUrl: 'assets/img/inventoryslot_hands.jpg', item: null },
        { id: 8, class: 4, inventoryType: 6, imgUrl: 'assets/img/inventoryslot_waist.jpg', item: null },
    ];
    ttItemDroit: itemSlot[] = [
        { id: 9, class: 4, inventoryType: 7, imgUrl: 'assets/img/inventoryslot_legs.jpg', item: null },
        { id: 10, class: 4, inventoryType: 8, imgUrl: 'assets/img/inventoryslot_feet.jpg', item: null },
        { id: 11, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 12, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 13, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 14, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 15, class: 2, inventoryType: 13, imgUrl: 'assets/img/inventoryslot_mainhand.jpg', item: null },
        { id: 16, class: 4, inventoryType: 14, imgUrl: 'assets/img/inventoryslot_offhand.jpg', item: null }
    ];

    @Input() character: any;

    constructor(private _appService: AppService) { }

    ngOnInit() {
        console.log(this.words)
        console.log(this.character)
        this.ttBonusStats = globals.bonusStats.map(bs => {
            if (this._appService.getLangue() === 'fr') {
                return { id: bs.id, libelle: bs.nameFr }
            } else {
                return { id: bs.id, libelle: bs.nameEn }
            }
        });
        let statId = globals.statsClass.find(sc => sc.class == this.character.class_id) ? globals.statsClass.find(sc => sc.class == this.character.class_id).stat_id : null;
        this.libelleAttack = statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : undefined;
        for (let k in this.character) {
            if (this.character[k].icon) {
                this.character[k].icon = 'http://media.blizzard.com/wow/icons/56/' + this.character[k].icon + '.jpg';
            }
        }
        this.ttItemGauche.forEach(ig => {
            if (ig.id === 1 && this.character.head.id) {
                ig.item = this.character.head;
            }
            if (ig.id === 2 && this.character.neck.id) {
                ig.item = this.character.neck;
            }
            if (ig.id === 3 && this.character.shoulder.id) {
                ig.item = this.character.shoulder;
            }
            if (ig.id === 4 && this.character.back.id) {
                ig.item = this.character.back;
            }
            if (ig.id === 5 && this.character.chest.id) {
                ig.item = this.character.chest;
            }
            if (ig.id === 6 && this.character.wrist.id) {
                ig.item = this.character.wrist;
            }
            if (ig.id === 7 && this.character.hands.id) {
                ig.item = this.character.hands;
            }
            if (ig.id === 8 && this.character.waist.id) {
                ig.item = this.character.waist;
            }
        });
        this.ttItemGauche.forEach(id => {
            if (id.id === 9 && this.character.legs.id) {
                id.item = this.character.legs;
            }
            if (id.id === 10 && this.character.feet.id) {
                id.item = this.character.feet;
            }
            if (id.id === 11 && this.character.finger1.id) {
                id.item = this.character.finger1;
            }
            if (id.id === 12 && this.character.finger2.id) {
                id.item = this.character.finger2;
            }
            if (id.id === 13 && this.character.trinket1.id) {
                id.item = this.character.trinket1;
            }
            if (id.id === 14 && this.character.trinket2.id) {
                id.item = this.character.trinket2;
            }
            if (id.id === 15 && this.character.main_hand.id) {
                id.item = this.character.head;
            }
            if (id.id === 16 && this.character.off_hand.id) {
                id.item = this.character.off_hand;
            }
        });
        /* this._appService.getBlizzard('character/hyjal/Mananga', [{ key: 'fields', value: 'items' }]).then(res => {
            // console.log(res);
        }); */
    }

    ngOnDestroy() {

    }

    getItemInfo(itemSlot: itemSlot): Promise<itemSlot> {
        return new Promise((resolve, reject) => {
            let oldIcon: string = itemSlot.item ? itemSlot.item.icon : '';
            if (itemSlot.item && !itemSlot.item.name) {
                this._appService.getBlizzard('item/' + itemSlot.item.id).then(res => {
                    if (res.bonusStats && res.bonusStats.length > 0) {
                        res.bonusStats.forEach(bonus => {
                            let bonusLarge = globals.bonusStats.find(bs => bs.id === bonus.stat);
                            if (bonusLarge) {
                                bonus.statLibelle = this._appService.getLangue() === 'fr' ? bonusLarge.nameFr : bonusLarge.nameEn;
                            }
                        });
                        itemSlot.item = { ...itemSlot.item, ...res };
                        itemSlot.item.icon = oldIcon ? oldIcon : itemSlot.item.icon;
                        resolve(itemSlot);
                    } else {
                        resolve(itemSlot);
                    }
                });
            } else {
                resolve(itemSlot);
            }
        });
    }

    showItemDetail(itemSlot: itemSlot = null) {
        if (itemSlot && itemSlot.item) {
            this.getItemInfo(itemSlot).then(res => {
                console.log(res)
                this.selectedItem = res.item;
                this.selectedItem.itemSlotId = itemSlot.id;
                this.displayItemDetailPerso = true;
            });
        } else {
            this.selectedItem = null;
            this.displayItemDetailPerso = false;
        }
    }

}
