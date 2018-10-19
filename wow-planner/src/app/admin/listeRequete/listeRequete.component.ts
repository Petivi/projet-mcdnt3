import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';
import Swal from 'sweetalert2';

import { PaginationComponent } from '../../common/pagination/pagination.component'

import { AppService } from '../../app.service';

import { Requete } from '../../model/app.model';


@Component({
    selector: 'liste-requete-cpt',
    templateUrl: './listeRequete.component.html',
})

export class ListeRequeteComponent implements OnInit {
    valid: boolean = true;
    ttRequete: Requete[] = [];
    requeteActive: Requete = null;
    token: string = null;
    ttPage: string[] = [];
    reponse: string = '';
    page: string = '1';
    totalPage: string = '1';
    setReponse: boolean = false; //affiche le formulaire de reponse

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) this.token = JSON.parse(localStorage.getItem('userConnected')).session_token;
        else this.token = null;
        this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
            if (res.error) {
                this._router.navigate(['/accueil']);
            } else {
                this.getMessages(this.page);
            }
        });
    }

    getMessages(page: string) {
        this._appService.post('action/admin/getContactMessagesList.php', {session_token: this.token, page: page}).then(res => {
            if (res.response) {
                this.ttRequete = res.response.valeur;
                this.ttPage = [];
                for(let i = 1; i < res.response.total_page + 1; i++) {
                    this.ttPage.push(i.toString());
                }
                console.log(this.ttPage)
                this.ttRequete.forEach(r => {
                    r.libelle_request_closed = r.request_closed === '0' ? 'non' : 'oui';
                });
            };
        });
    }

    showRequete(requette: Requete) {
        this.requeteActive = requette;
    }

    retour() {
        this.requeteActive = null
    }

    changePage(event) {
        this.getMessages(event);
    }

    supprimer(requete) {
        Swal({
            title: 'Confirmation',
            text: 'Etes vous sur de vouloir supprimer la requête ' + requete.id,
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
        }).then(res => {
            if (res.value && this.token) {
                this._appService.post('action/admin/deleteContactMessage.php', { session_token: this.token, id: requete.id }).then(res => {
                    if (res.response) {
                        this.getMessages(this.page);
                    }
                });
            }
        });
    }

    sendResponse() {
        if (this.requeteActive) {
            Swal({
                title: 'Confirmation',
                text: 'Un mail va être envoyé a cette adresse : ' + this.requeteActive.user_mail + ', confirmez vous cet envoie ?',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non',
            }).then(res => {
                if(res.value) {
                    this._appService.post('action/admin/answerContactMessage.php',
                    { session_token: this.token, id: this.requeteActive.id, mail: this.requeteActive.user_mail, data: this.reponse, request_ref: this.requeteActive.request_ref })
                    .then(res => {
                        if (res.response) {
                            this.getMessages(this.page);
                            this.setReponse = false;
                            this.reponse = '';
                            this.requeteActive = null;
                        }
                    });
                }
            });
        }
    }
}