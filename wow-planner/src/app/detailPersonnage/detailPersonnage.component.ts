import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, DefaultUrlSerializer } from '@angular/router';
import { Subscription } from 'rxjs';

import { AppService } from '../app.service';

import { Word, Commentaire } from '../model/app.model';

@Component({
    selector: 'detail-personnage-cpt',
    templateUrl: './detailPersonnage.component.html',
})
export class DetailPersonnageComponent implements OnInit, OnDestroy {
    obsInit: Subscription;
    displayDetail: boolean = false;
    mesPersonnages: boolean = false;
    ttCommentaire: Commentaire[] = [];
    words: Word[] = [];
    character: any;
    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.words = res.resolver.words;
            this.character = res.resolver.character[0];
            this.mesPersonnages = res.resolver.mesPersonnages;
            this.ttCommentaire = res.resolver.comments;
        });
    } 

    ngOnDestroy() {

    }
}
