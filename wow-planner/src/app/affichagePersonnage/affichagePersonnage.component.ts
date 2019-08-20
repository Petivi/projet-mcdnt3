import { Component, OnInit, OnDestroy, Input, Output, EventEmitter } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import Swal from 'sweetalert2';

import { AppService } from '../app.service';

import { Item, ItemSlot, Word } from '../model/app.model';

import { setTtItem } from '../common/function';

import * as globals from '../../assets/data/globals';

@Component({
    selector: 'affichage-personnage-cpt',
    templateUrl: './affichagePersonnage.component.html',
})
export class AffichagePersonnageComponent implements OnInit, OnDestroy {
    @Input() words: Word[] = [];
    @Input() mesPersonnages: boolean;
    @Input() detailPersonnage: boolean;
    @Input() character: any;
    @Output() deleted = new EventEmitter<boolean>();

    iconUrl = globals.blizzardIconUrl;
    urlRedirectionDetail: string = 'accueil/detailPersonnage';
    urlRetour: string = 'accueil';
    urlEdit: string = '/creationPersonnage';
    selectedItem: Item;
    displayItemDetailPerso: boolean = false;
    ttBonusStats: any[] = [];
    libelleAttack: string;
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

    constructor(private _appService: AppService, private _router: Router, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.urlRetour = '/' + this._router.url.split('/')[1];
        this.urlRedirectionDetail = this._router.url === '/accueil' ? '/accueil' : '/listePersonnage';
        this.urlRedirectionDetail += '/detailPersonnage';
        this.ttBonusStats = globals.bonusStats.map(bs => {
            if (this._appService.getLangue() === 'fr') {
                return { id: bs.id, libelle: bs.nameFr }
            } else {
                return { id: bs.id, libelle: bs.nameEn }
            }
        });
        let statId = globals.statsClass.find(sc => sc.class == this.character.class_id) ? globals.statsClass.find(sc => sc.class == this.character.class_id).stat_id : null;
        this.libelleAttack = statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : undefined;
        setTtItem(this.ttItemGauche, this.ttItemDroit, this.character).then(res => {
            this.ttItemDroit = res.ttItemD;
            this.ttItemGauche = res.ttItemG;
        });
        /* this._appService.getBlizzard('character/hyjal/Mananga', [{ key: 'fields', value: 'items' }]).then(res => {
            
        }); */
    }

    ngOnDestroy() {

    }

    getItemInfo(itemSlot: ItemSlot): Promise<ItemSlot> {
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

    showItemDetail(itemSlot: ItemSlot = null) {
        if (itemSlot && itemSlot.item) {
            this.getItemInfo(itemSlot).then(res => {
                this.selectedItem = res.item;
                this.selectedItem.itemSlotId = itemSlot.id;
                this.displayItemDetailPerso = true;
            });
        } else {
            this.selectedItem = null;
            this.displayItemDetailPerso = false;
        }
    }

    like(statut: number) {
        this._appService.post('action/api-blizzard/updateStatutLike.php', { session_token: this._appService.getToken(), character_id: this.character.character_id, statut: statut })
            .then(res => {
                if (res.response) {
                    if (res.response.decrement === 'like') {
                        this.character.total_like--;
                        this.character.statut_like = '';
                    }
                    if (res.response.decrement === 'dislike') {
                        this.character.total_dislike--;
                        this.character.statut_like = '';
                    }
                    if (res.response.increment === 'like') {
                        this.character.total_like++;
                        this.character.statut_like = 'like';
                    }
                    if (res.response.increment === 'dislike') {
                        this.character.total_dislike++;
                        this.character.statut_like = 'dislike';
                    }
                }
            });
    }

    deleteCharacter() {
        Swal({
            title: this.words.find(w => w.msg_name === 'msg_deleteConfirmation').value,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: this.words.find(w => w.msg_name === 'msg_yes').value, // faire les text des swal
            cancelButtonText: this.words.find(w => w.msg_name === 'msg_no').value,
        }).then(res => {
            this._appService.post('action/api-blizzard/deleteCharacter.php', { session_token: this._appService.getToken(), character: { character_id: this.character.character_id } })
                .then(res => {
                    if (res.response) {
                        this.deleted.emit(this.character.character_id);
                    }
                });
        });
    }

}
