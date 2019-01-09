import { Component, OnInit, OnDestroy } from '@angular/core';

import { AppService } from '../app.service';

@Component({
    selector: 'personnage-cpt',
    templateUrl: './personnage.component.html',
})
export class PersonnageComponent implements OnInit, OnDestroy {

    constructor(private _appService: AppService) { }

    ngOnInit() {
        this._appService.getBlizzard('character/hyjal/Mananga', [{key: 'fields', value: 'items'}]).then(res => {
            console.log(res);
        });
    }

    ngOnDestroy() {

    }
}