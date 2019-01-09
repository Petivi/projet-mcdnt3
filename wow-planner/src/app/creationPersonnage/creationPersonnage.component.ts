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
    ttItemGauche: any[] = [{ class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_head.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_neck.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_shoulder.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_chest.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_chest.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_shirt.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_tabard.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_wrists.jpg' }];
    ttItemDroit: any[] = [{ class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_hands.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_waist.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_legs.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_feet.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_finger.jpg' },
    { class: '4', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_trinket.jpg' },
    { class: '2', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_mainhand.jpg' },
    { class: '2', url: 'https://wow.zamimg.com/images/wow/icons/medium/inventoryslot_offhand.jpg' }];
    displayCharacter: boolean = false;
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

    validChar() {
        this.displayCharacter = true;
        /* this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {

            });
        console.log(this.character); */
    }

    getItem(classe: number) {
        this._appService.post('action/api-blizzard/getItemsId.php', { class: classe, subClass: this.subClass }).then(res => {
            console.log(res);
        });
    }
}