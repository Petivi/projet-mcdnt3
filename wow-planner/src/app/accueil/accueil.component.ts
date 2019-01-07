import { Component, OnInit, OnDestroy } from '@angular/core';

import { AppService } from '../app.service';

import { User } from '../model/app.model';

@Component({
    selector: 'accueil-cpt',
    templateUrl: './accueil.component.html',
})
export class AccueilComponent implements OnInit, OnDestroy {
    userConnected: User;
    tabClasses: any [];
    tabRaces: any [];
    character: any = {name:"", race_id:null, class_id:null};
    constructor(private _appService: AppService) { }

    ngOnInit() {
        this._appService.setPage('accueil');
        this.getRaces();
        this.getClasses();
    }

    ngOnDestroy() {

    }

    getRaces(){
      this._appService.post('action/api-blizzard/api-blizzard.php',
      {url_missing: 'data/character/races', tabParam: [{key: 'locale', value: 'fr_UE'}] }).then(res => {
        this.tabRaces = res.races;
        console.log(this.tabRaces)
      });
    }
    getClasses(){
      this._appService.post('action/api-blizzard/api-blizzard.php',
      {url_missing: 'data/character/classes', tabParam: [{key: 'locale', value: 'fr_UE'}] }).then(res => {
        this.tabClasses = res.classes;
        console.log(this.tabClasses)
      });
    }
    validChar(){
      this._appService.post('action/api-blizzard/addNewCharacter.php',
      {session_token: JSON.parse(localStorage.getItem("userConnected")).session_token, character:this.character}).then(res => {

      });
      console.log(this.character);
    }
}
