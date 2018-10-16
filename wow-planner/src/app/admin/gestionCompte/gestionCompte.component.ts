import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../../app.service';

import { Word, User, WordSimplified } from '../../model/app.model'


@Component({
    selector: 'gestion-compte-cpt',
    templateUrl: './gestionCompte.component.html',
})
export class GestionCompteComponent implements OnInit {
    users: User[] = [];
    controls = () => ({
    });
    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this._appService.post('action/admin/usersManagement.php', JSON.parse(localStorage.getItem('userConnected')))
            .then(res => {
                if(res.response && res.response.length > 0) {
                    this.users = res.response
                    
                }
            });
    }
}