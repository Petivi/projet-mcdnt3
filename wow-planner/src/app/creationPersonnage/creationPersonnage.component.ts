import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs';
import { List } from 'immutable'

import { AppService } from '../app.service';
import * as globals from '../../assets/data/globals';

import { Word, RecherchCreationPersonnage } from '../model/app.model';

/* donnée LOUIS pseudo: Mananga, Pteracuda serveur: Hyjal */

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    obsInit: Subscription;
    ttItemGauche: any[] = [
        { class: 4, inventoryType: 1, url: 'assets/img/inventoryslot_head.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 2, url: 'assets/img/inventoryslot_neck.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 3, url: 'assets/img/inventoryslot_shoulder.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 15, url: 'assets/img/inventoryslot_chest.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 5, url: 'assets/img/inventoryslot_chest.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 6, url: 'assets/img/inventoryslot_shirt.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 4, url: 'assets/img/inventoryslot_tabard.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 9, url: 'assets/img/inventoryslot_wrists.jpg', urlCharactere: '' }
    ];
    ttItemDroit: any[] = [
        { class: 4, inventoryType: 10, url: 'assets/img/inventoryslot_hands.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 6, url: 'assets/img/inventoryslot_waist.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 7, url: 'assets/img/inventoryslot_legs.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 8, url: 'assets/img/inventoryslot_feet.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 11, url: 'assets/img/inventoryslot_finger.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 12, url: 'assets/img/inventoryslot_finger.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 13, url: 'assets/img/inventoryslot_trinket.jpg', urlCharactere: '' },
        { class: 4, inventoryType: 13, url: 'assets/img/inventoryslot_trinket.jpg', urlCharactere: '' },
        { class: 2, inventoryType: 16, url: 'assets/img/inventoryslot_mainhand.jpg', urlCharactere: '' },
        { class: 2, inventoryType: 17, url: 'assets/img/inventoryslot_offhand.jpg', urlCharactere: '' }
    ];
    ttMatiere: any[] = [
        { id: 2, name: 'msg_cuir' },
        { id: 3, name: 'msg_maille' },
        { id: 4, name: 'msg_plate' },
        { id: 6, name: 'msg_bouclier' },
        { id: 0, name: 'msg_divers' }
    ];
    ttQuality: any[] = [
        { id: 0, name: 'msg_qualityPoor' },
        { id: 1, name: 'msg_qualityCommon' },
        { id: 2, name: 'msg_qualityUncommon' },
        { id: 3, name: 'msg_qualityRare' },
        { id: 4, name: 'msg_qualityEpic' },
        { id: 5, name: 'msg_qualityLegendary' },
        { id: 6, name: 'msg_qualityArtifact' },
        { id: 7, name: 'msg_qualityHeirloom' },
        { id: 8, name: 'msg_qualityWowtoken' },
    ];
    recherche: RecherchCreationPersonnage = { quality: -1, lvlMin: 0, lvlMax: 110, matiere: 2, name: '', class: null, inventoryType: null };
    displayCharacter: boolean = false;
    displayChoixItem: boolean = false;
    displayItemDetail: boolean = false;
    selectedItem: any;
    ttItem: any[] = []; // Typer quand je saurais ce qu'on garde
    gridData: List<any> = List([]);
    inventoryType: number = 1;
    ttClasseItem: any[] = [];
    words: Word[] = [];
    tabClasses: any[];
    tabRaces: any[];
    character: any = { name: "", race_id: null, class_id: null };

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        console.log(globals.bonusStats)
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            // console.log(res);
            this.words = res.resolver.words;
            this.ttClasseItem = res.resolver.classesItem ? res.resolver.classesItem : [];
            this.tabRaces = res.resolver.races;
            this.tabClasses = res.resolver.classes;
            if (this.tabRaces && this.tabClasses) {
                this.character.race_id = this.tabRaces[0].id;
                this.character.class_id = this.tabClasses[0].id;
            }
        });
    }

    ngOnDestroy() {
        this.obsInit.unsubscribe();
    }

    setGridData() {
        if (this.ttItem) {
            this.gridData = List(this.ttItem);
        }
    }

    validChar() {
        this.displayCharacter = true;
        /* this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {

            });
        console.log(this.character); */
    }

    setRecherche(classe: number, inventoryType: number) {
        this.recherche.class = classe;
        this.recherche.inventoryType = inventoryType;
        this.getTtItem().then(res => {
            this.displayChoixItem = !this.displayChoixItem;
        });
    }

    getTtItem() {
        return new Promise((resolve, reject) => {
            this._appService.post('action/api-blizzard/getItemsInfo.php', {
                class: this.recherche.class,
                subClass: this.recherche.matiere,
                quality: this.recherche.quality,
                inventory_type: this.recherche.inventoryType,
                required_level_min: this.recherche.lvlMin,
                required_level_max: this.recherche.lvlMax,
                race_id: this.character.race_id,
                class_id: this.character.class_id,
                lang: this._appService.getLangue()
            }).catch(err => {
                reject(err);
            }).then(res => {
                // console.log(res);
                this.ttItem = res.response;
                this.ttItem.forEach(item => {
                    item.item_required_level = parseInt(item.item_required_level);
                    item.affichage = 0;
                    item.qualityName = this.ttQuality.find(qual => qual.id === parseInt(item.item_quality)) ? this.ttQuality.find(qual => qual.id == item.item_quality).name : '';
                });
                this.setGridData();
                console.log(this.ttItem)
                resolve(true);
            });
        });
    }

    getItemInfo(item) {
        return new Promise((resolve, reject) => {
            if (!item.id) {
                console.log('la', item)
                this._appService.getBlizzard('item/' + item.item_id).then(res => {
                    res.bonusStats.forEach(bonus => {
                        let bonusLarge = globals.bonusStats.find(bs => bs.id === bonus.stat);
                        if (bonusLarge) {
                            bonus.statLibelle = bonusLarge.nameEn;//this._appService.getLangue() === 'fr' ? bonusLarge.nameFr : bonusLarge.nameEn;
                        }
                    });
                    let newItem = this.ttItem.find(item => item.item_id == res.id);
                    newItem = { ...newItem, ...res };
                    this.ttItem.splice(this.ttItem.findIndex(item => item.item_id == newItem.id), 1, newItem);
                    this.setGridData();
                    resolve(newItem);
                });
            } else {
                resolve(item);
            }
        })
    }

    showItemDetail(item = null) {
        if (item) {
            if (!this.selectedItem || item.id !== this.selectedItem.id) {
                this.getItemInfo(item).then(res => {
                    this.selectedItem = res;
                    console.log(this.selectedItem)
                    console.log(this.ttItem)
                    this.displayItemDetail = !this.displayItemDetail;
                });
            }
        } else {
            this.displayItemDetail = !this.displayItemDetail;
            this.selectedItem = null;
        }
    }

    selectItem(event) {
        this.displayChoixItem = false;
        this.selectItem = event.dataItem;
        console.log(event);
    }
}
