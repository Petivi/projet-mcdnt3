import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs';

import { AffichagePersonnageComponent } from '../affichagePersonnage/affichagePersonnage.component';

import { AppService } from '../app.service';

@Component({
    selector: 'liste-personnage-cpt',
    templateUrl: './listePersonnage.component.html',
})
export class ListePersonnageComponent implements OnInit, OnDestroy {
    obsInit: Subscription;
    ttCharacter: any[] = [];
    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttCharacter = res.resolver.characters && res.resolver.characters.length > 0 ? res.resolver.characters : [];
            console.log(res)
            console.log(this.ttCharacter);
        });
        /* this._appService.getBlizzard('character/hyjal/Mananga', [{key: 'fields', value: 'items'}]).then(res => {
            // console.log(res);
        }); */
    }

    ngOnDestroy() {

    }
}
