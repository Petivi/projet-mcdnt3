import { Component, OnInit, OnDestroy } from '@angular/core';

import { AppService } from '../app.service';

import { User } from '../model/app.model';

@Component({
    selector: 'accueil-cpt',
    templateUrl: './accueil.component.html',
})
export class AccueilComponent implements OnInit, OnDestroy {
    userConnected: User;
    constructor(private _appService: AppService) { }

    ngOnInit() {
        this._appService.setPage('accueil');
    }

    ngOnDestroy() {

    }

}
