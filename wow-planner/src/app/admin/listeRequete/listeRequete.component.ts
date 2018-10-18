import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../../app.service';

import { Requete } from '../../model/app.model'


@Component({
    selector: 'liste-requete-cpt',
    templateUrl: './listeRequete.component.html',
})

export class ListeRequeteComponent implements OnInit {

    valid: boolean = true;
    ttRequete: Requete[] = [];

    controls = () => ({
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.buildControl();
        this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
            if (res.error) {
                this._router.navigate(['/accueil']);
            } else {
                this._appService.post('action/admin/getContactMessagesList.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
                    console.log(res)
                });
            }
        });
    } 

    buildControl() {
    }

    bindModelForm() {
    }
}