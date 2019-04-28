import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs';

import { AffichagePersonnageComponent } from '../affichagePersonnage/affichagePersonnage.component';
import { CommentaireComponent } from '../commentaire/commentaire.component';

import { AppService } from '../app.service';

import { Word } from '../model/app.model';

@Component({
    selector: 'liste-personnage-cpt',
    templateUrl: './listePersonnage.component.html',
})
export class ListePersonnageComponent implements OnInit, OnDestroy {
    obsInit: Subscription;
    characterDetail: any;
    displayDetail: boolean = false;
    words: Word[] = [];
    ttCharacter: any[] = [];
    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttCharacter = res.resolver.characters && res.resolver.characters.length > 0 ? res.resolver.characters : [];
            this.words = res.resolver.words;
        });
        /* this._appService.getBlizzard('character/hyjal/Mananga', [{key: 'fields', value: 'items'}]).then(res => {
            // console.log(res);
        }); */
    } 

    ngOnDestroy() {

    }
}
