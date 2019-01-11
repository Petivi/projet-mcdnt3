import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { AppService } from '../app.service';

import { Word, RecherchCreationPersonnage } from '../model/app.model';
import { Subscription } from 'rxjs';

/* donnée LOUIS pseudo: Mananga, Pteracuda serveur: Hyjal */

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    obsInit: Subscription;
    ttItemGauche: any[] = [
        { class: 4, inventoryType: 1, url: 'assets/img/inventoryslot_head.jpg' },
        { class: 4, inventoryType: 2, url: 'assets/img/inventoryslot_neck.jpg' },
        { class: 4, inventoryType: 3, url: 'assets/img/inventoryslot_shoulder.jpg' },
        { class: 4, inventoryType: 15, url: 'assets/img/inventoryslot_chest.jpg' },
        { class: 4, inventoryType: 5, url: 'assets/img/inventoryslot_chest.jpg' },
        { class: 4, inventoryType: 6, url: 'assets/img/inventoryslot_shirt.jpg' },
        { class: 4, inventoryType: 4, url: 'assets/img/inventoryslot_tabard.jpg' },
        { class: 4, inventoryType: 9, url: 'assets/img/inventoryslot_wrists.jpg' }
    ];
    ttItemDroit: any[] = [
        { class: 4, inventoryType: 10, url: 'assets/img/inventoryslot_hands.jpg' },
        { class: 4, inventoryType: 6, url: 'assets/img/inventoryslot_waist.jpg' },
        { class: 4, inventoryType: 7, url: 'assets/img/inventoryslot_legs.jpg' },
        { class: 4, inventoryType: 8, url: 'assets/img/inventoryslot_feet.jpg' },
        { class: 4, inventoryType: 11, url: 'assets/img/inventoryslot_finger.jpg' },
        { class: 4, inventoryType: 12, url: 'assets/img/inventoryslot_finger.jpg' },
        { class: 4, inventoryType: 13, url: 'assets/img/inventoryslot_trinket.jpg' },
        { class: 4, inventoryType: 13, url: 'assets/img/inventoryslot_trinket.jpg' },
        { class: 2, inventoryType: 16, url: 'assets/img/inventoryslot_mainhand.jpg' },
        { class: 2, inventoryType: 17, url: 'assets/img/inventoryslot_offhand.jpg' }
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
    ttItem: any[] = [];
    inventoryType: number = 1;
    displayChoixItem: boolean = false;
    ttClasseItem: any[] = [];
    words: Word[] = [];
    tabClasses: any[];
    tabRaces: any[];
    character: any = { name: "", race_id: null, class_id: null };

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            console.log(res);
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
                inventory_type: this.recherche.inventoryType,
                required_level_min: this.recherche.lvlMin,
                required_level_max: this.recherche.lvlMax,
                race_id: this.character.race_id,
                class_id: this.character.class_id,
                lang: this._appService.getLangue()
            }).catch(err => {
                reject(err);
            }).then(res => {
                console.log(res);
                this.ttItem = res.response;
                resolve(res);
            });
        });
    }

    getItemInfo(item) {
        if(!item.description) {
            this._appService.getBlizzard('item/' + item.item_id).then(res => { //rename les variables item_id et item_icon en id et icon pour que ce soit comme dans l'api comme ça pas de probleme au remplacement
                this.ttItem.splice(this.ttItem.findIndex(item => item.item_id == res.id), 1, res);
                console.log(this.ttItem.find(item => item.id = res.id))
            });
        }
    }
}