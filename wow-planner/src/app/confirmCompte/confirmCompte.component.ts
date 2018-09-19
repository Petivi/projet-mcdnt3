import { Component, OnInit } from '@angular/core';

import { AppService } from '../app.service';

@Component({
    selector: 'confirm-compt-cpt',
    templateUrl: 'confirmCompte.component.html',
})
export class ConfirmCompteComponent implements OnInit {
    message: string;
    constructor(private _appService: AppService) { }

    ngOnInit() {

    }
}