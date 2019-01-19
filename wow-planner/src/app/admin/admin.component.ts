import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AppService } from '../app.service';

import {  User } from '../model/app.model';


@Component({
    selector: 'admin-cpt',
    templateUrl: './admin.component.html',
})
export class AdminComponent implements OnInit {
    valid: boolean = true;
    userConnected: User;
    controls = () => ({
    });
    constructor(private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.buildControl();
        this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
            // console.log(res)
            if (res.error) {
                this._router.navigate(['/accueil']);
            }
        });
    }

    buildControl() {
    }

    bindModelForm() {
    }
}
