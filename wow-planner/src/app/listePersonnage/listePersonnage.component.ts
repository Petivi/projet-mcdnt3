import { Component, OnInit, OnDestroy, AfterViewInit, ViewChildren, QueryList, ComponentFactoryResolver } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';

import { AffichagePersonnageComponent } from '../affichagePersonnage/affichagePersonnage.component';

import { AppService } from '../app.service';

import { Word, Character } from '../model/app.model';

@Component({
    selector: 'liste-personnage-cpt',
    templateUrl: './listePersonnage.component.html',
})
export class ListePersonnageComponent implements OnInit, OnDestroy, AfterViewInit {
    obsInit: Subscription;
    displayDetail: boolean = false;
    words: Word[] = [];
    ttCharacToHide: string[] = [];
    ttCharacter: Character[] = [];

    @ViewChildren(AffichagePersonnageComponent) ttAffPersComp: QueryList<AffichagePersonnageComponent>;
    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute, private _router: Router, private _cfr: ComponentFactoryResolver) { }

    ngOnInit() {
        console.log('oui')
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttCharacter = res.resolver.characters && res.resolver.characters.length > 0 ? res.resolver.characters : [];
            this.words = res.resolver.words;
        });
        /* this._appService.getBlizzard('character/hyjal/Mananga', [{key: 'fields', value: 'items'}]).then(res => {
            // console.log(res);
        }); */
    }

    ngAfterViewInit() {
        console.log(this.ttAffPersComp);
        this.ttAffPersComp.changes.subscribe((r) => { console.log(this.ttAffPersComp); });
    }

    ngOnDestroy() {
    }

    deleted(event) {
        this.ttCharacToHide.push(event);
        let id = this.ttCharacter.findIndex(c => c.character_id === event);
        if (id) {
            this.ttCharacter.splice(id, 1);
        }
    }

    checkCharacToHide(character: Character) {
        return !this.ttCharacToHide.find(cth => cth === character.character_id)
    }
}
