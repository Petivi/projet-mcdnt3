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
    requeteActive: Requete = null;
    token: string = null;


    controls = () => ({
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) this.token = JSON.parse(localStorage.getItem('userConnected')).session_token;
        else this.token = null;
        this.buildControl();
        this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
            if (res.error) {
                this._router.navigate(['/accueil']);
            } else {
                this._appService.post('action/admin/getContactMessagesList.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
                    console.log(res)
                    if (res.response && res.response.length > 0) {
                        this.ttRequete = res.response;
                        this.ttRequete.forEach(r => {
                            r.libelle_request_closed = r.request_closed === '0' ? 'non' : 'oui';
                        });
                    };
                });
            }
        });
    }

    buildControl() {
    }

    bindModelForm() {
    }

    showRequete(requette: Requete) {
        this.requeteActive = requette;
    }

    retour() {
        this.requeteActive = null
    }

    supprimer(requete) {
        console.log(requete)
        console.log(this.token)
        if (this.token) {
            this._appService.post('action/admin/deleteContactMessage.php', { session_token: this.token, id: requete.id }).then(res => {
                console.log(res);
            });
        }
    }
}