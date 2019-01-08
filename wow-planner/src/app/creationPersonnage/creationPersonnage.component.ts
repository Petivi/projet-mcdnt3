import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

import { AppService } from '../app.service';

import { Word } from '../model/app.model';
import { Subscription } from 'rxjs';

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    obsInit: Subscription;
    words: Word[] = [];
    tabClasses: any[];
    tabRaces: any[];
    character: any = { name: "", race_id: null, class_id: null };

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            console.log(res);
            this.tabRaces = res.resolver.races;
            this.tabClasses = res.resolver.classes;
            this.words = res.resolver.words;
            console.log(this.words)
        });
        
    }

    validChar() {
        this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {

            });
        console.log(this.character);
    }

    getItems() {
        this._appService.getBlizzard('data/item/classes').then(res => {
            console.log(res);
        })
    }
}