import { Component, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { ActivatedRoute } from '@angular/router';

import * as globals from '../../assets/data/globals';

import { AppService } from '../app.service';

import { Word, ItemSlot } from '../model/app.model';

import { setTtItem } from '../common/function';

@Component({
    selector: 'app-affichage-personnage',
    templateUrl: './affichage-personnage.page.html',
    styleUrls: ['./affichage-personnage.page.scss'],
})
export class AffichagePersonnagePage implements OnInit {

    obsInit: Subscription;
    pageTitle: string = '';
    words: Word[] = [];
    iconUrl = globals.blizzardIconUrl;
    ttBonusStats: any[] = [];
    ttComments: any[] = [];
    openCharac: boolean = true;
    openItem: boolean = true;
    openComment: boolean = true;
    character: any;
    ttItemGauche: ItemSlot[] = [
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
    ttItemDroit: ItemSlot[] = [
        { id: 9, class: 4, inventoryType: 7, imgUrl: 'assets/img/inventoryslot_legs.jpg', item: null },
        { id: 10, class: 4, inventoryType: 8, imgUrl: 'assets/img/inventoryslot_feet.jpg', item: null },
        { id: 11, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 12, class: 4, inventoryType: 11, imgUrl: 'assets/img/inventoryslot_finger.jpg', item: null },
        { id: 13, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 14, class: 4, inventoryType: 12, imgUrl: 'assets/img/inventoryslot_trinket.jpg', item: null },
        { id: 15, class: 2, inventoryType: 13, imgUrl: 'assets/img/inventoryslot_mainhand.jpg', item: null },
        { id: 16, class: 4, inventoryType: 14, imgUrl: 'assets/img/inventoryslot_offhand.jpg', item: null }
    ];

    constructor(private _activatedRoute: ActivatedRoute, private _appService: AppService) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.character = res.affichagePersonnage.character[0];
            this.pageTitle = this.character.name;
            this.ttComments = res.affichagePersonnage.comments;
            console.log(this.ttComments)
            this.words = res.affichagePersonnage.words;
            this.ttBonusStats = globals.bonusStats.map(bs => {
                if (this._appService.getLangue() === 'fr') {
                    return { id: bs.id, libelle: bs.nameFr }
                } else {
                    return { id: bs.id, libelle: bs.nameEn }
                }
            });
            setTtItem(this.ttItemGauche, this.ttItemDroit, this.character).then(res => {
                this.ttItemDroit = res.ttItemD;
                this.ttItemGauche = res.ttItemG;
                this.getItemInfo(this.ttItemDroit);
                this.getItemInfo(this.ttItemGauche);
            });
        });
    }

    async getItemInfo(ttItem) {
        if (ttItem) {
            for await (let id of ttItem) {
                let oldIcon: string = id.item ? id.item.icon : '';
                if (id.item) {
                    await this._appService.getBlizzard('item/' + id.item.id).then(res => {
                        if (res.bonusStats && res.bonusStats.length > 0) {
                            res.bonusStats.forEach(bonus => {
                                let bonusLarge = globals.bonusStats.find(bs => bs.id === bonus.stat);
                                if (bonusLarge) {
                                    bonus.statLibelle = this._appService.getLangue() === 'fr' ? bonusLarge.nameFr : bonusLarge.nameEn;
                                }
                            });
                            id.item = { ...id.item, ...res };
                            id.item.icon = oldIcon ? oldIcon : id.item.icon;
                        }
                    });
                }
            }
        }
    }

    getWord(libelle: string) {
        return this.words.find(w => w.msg_name === libelle).value;
    }

    getHealthLibelle() {
        return this.ttBonusStats.find(bs => bs.id === 1).libelle;
    }

    getLibelleAttack() {
        let statId = globals.statsClass.find(sc => sc.class == this.character.class_id) ? globals.statsClass.find(sc => sc.class == this.character.class_id).stat_id : null;
        return statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : '';
    }

    getLibelleStat(id_stat) {
        let libelleStat = this.ttBonusStats.find(ls => ls.id === id_stat).libelle;
        return libelleStat;
    }

}
