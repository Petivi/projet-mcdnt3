import { Component, OnInit } from '@angular/core';

import { AppService } from '../app.service';

import { Word } from '../model/app.model';

@Component({
    selector: 'creation-personnage-cpt',
    templateUrl: './creationPersonnage.component.html',
})
export class CreationPersonnageComponent implements OnInit {
    words: Word[] = [];
    tabClasses: any[];
    tabRaces: any[];
    character: any = { name: "", race_id: null, class_id: null };

    constructor(private _appService: AppService) { }

    ngOnInit() {
        this._appService.getWords(['common']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            console.log(this.words)
        });
        this._appService.setPage('accueil');
        this.getRaces();
        this.getClasses();
        this._appService.post('action/api-blizzard/api-blizzard.php',
            { url_missing: 'challenge/hyjal', tabParam: [{ key: 'locale', value: 'fr_UE' }] }).then(res => {
                console.log(res)
            });
    }

    getRaces() {
        this._appService.post('action/api-blizzard/api-blizzard.php',
            { url_missing: 'data/character/races', tabParam: [{ key: 'locale', value: 'fr_UE' }] }).then(res => {
                this.tabRaces = res.races;
                console.log(this.tabRaces)
            });
    }
    getClasses() {
        this._appService.post('action/api-blizzard/api-blizzard.php',
            { url_missing: 'data/character/classes', tabParam: [{ key: 'locale', value: 'fr_UE' }] }).then(res => {
                this.tabClasses = res.classes;
                console.log(this.tabClasses)
            });
    }
    validChar() {
        this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {

            });
        console.log(this.character);
    }
}