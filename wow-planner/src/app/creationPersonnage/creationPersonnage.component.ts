import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { AppService } from '../app.service';

import { Word } from '../model/app.model';
import { Subscription } from 'rxjs';

/* donnée LOUIS pseudo: Mananga, Pteracuda serveur: Hyjal */

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    obsInit: Subscription;
    ttItemGauche: any[] = [{ class: '4', url: 'assets/img/inventoryslot_head.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_neck.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_shoulder.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_chest.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_chest.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_shirt.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_tabard.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_wrists.jpg' }];
    ttItemDroit: any[] = [{ class: '4', url: 'assets/img/inventoryslot_hands.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_waist.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_legs.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_feet.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_finger.jpg' },
    { class: '4', url: 'assets/img/inventoryslot_trinket.jpg' },
    { class: '2', url: 'assets/img/inventoryslot_mainhand.jpg' },
    { class: '2', url: 'assets/img/inventoryslot_offhand.jpg' }];
    displayCharacter: boolean = false;
    inventoryType: number = 7;
    subClass: number = 2;
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

    getItem(classe: number) {
        this._appService.post('action/api-blizzard/getItemsInfo.php', { class: classe, subClass: this.subClass, inventory_type: this.inventoryType, lang: this._appService.getLangue() }).then(res => {
            console.log(res);
        });
    }
}