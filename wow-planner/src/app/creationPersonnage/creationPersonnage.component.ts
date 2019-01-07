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
        this._appService.getCreationPersonnage().then(res => {
            if(res[0].races && res[1].classes) {
                this.tabRaces = res[0].races;
                this.tabClasses = res[1].classes;
                console.log(res)
            }
        });
    }
    
    validChar() {
        this._appService.post('action/api-blizzard/addNewCharacter.php',
            { session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character: this.character }).then(res => {

            });
        console.log(this.character);
    }
}