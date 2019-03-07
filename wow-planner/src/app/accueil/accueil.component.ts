import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from 'rxjs';
import { ActivatedRoute } from '@angular/router';

import { AppService } from '../app.service';

import { User, Word } from '../model/app.model';

@Component({
    selector: 'accueil-cpt',
    templateUrl: './accueil.component.html',
})
export class AccueilComponent implements OnInit, OnDestroy {
    userConnected: User;
    obsInit: Subscription;
    words: Word[] = [];
    ttCharacter: any[] = [];
    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttCharacter = res.resolver.characters && res.resolver.characters.length > 0 ? res.resolver.characters : [];
            this.words = res.resolver.words;
            console.log(res)
            console.log(this.ttCharacter);
        });
    }

    ngOnDestroy() {

    }

}
