import { Component, OnInit, OnDestroy } from '@angular/core';

import { AppService } from '../app.service';

import { User } from '../model/app.model';

@Component({
    selector: 'confirm-compt-cpt',
    template: 'ouit',
})
export class ConfirmCompt implements OnInit, OnDestroy {
    userConnected: User;
    message: string='ouit';
    constructor(private _appService: AppService) { }

    ngOnInit() {
    }

    ngOnDestroy() {

    }
}