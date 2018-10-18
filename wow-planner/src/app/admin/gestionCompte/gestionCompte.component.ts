import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../../app.service';

import { Word, User } from '../../model/app.model'


@Component({
    selector: 'gestion-compte-cpt',
    templateUrl: './gestionCompte.component.html',
})
export class GestionCompteComponent implements OnInit {
    editMode: boolean = false;
    submitted: boolean = false;
    words: Word[] = [];
    errors: string [] = [];
    typeCompteActif: any[] = [{code: 0, value: 'Bannis'}, {code: 1, value: 'Actif'}, {code: 2, value: 'Supprimé'}];

    raison: string = '';
    action: string = '';
    userActif: User = null;
    users: User[] = [];
    userForm: FormGroup;

    controls = () => ({
        userGroup: this._formBuilder.group({
            firstname: [this.userActif && this.userActif.firstname ? this.userActif.firstname : ''],
            lastname: [this.userActif && this.userActif.lastname ? this.userActif.lastname : ''],
            pseudo: [this.userActif && this.userActif.pseudo ? this.userActif.pseudo : '', Validators.required],
            active_account: [this.userActif && this.userActif.active_account ? this.userActif.active_account : ''],
        }),
        adminGroup: this._formBuilder.group({
            action: [this.action, Validators.required],
            raison: [this.raison, Validators.required],
        }),
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.buildControl();
        this._appService.getWords(['common', 'gestionCompte', 'infoUser']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            console.log(this.words)
            this._appService.post('action/admin/usersManagement.php', JSON.parse(localStorage.getItem('userConnected')))
                .then(res => {
                    if (res.response && res.response.length > 0) {
                        this.users = res.response;
                        this.users.forEach(u => {
                            switch (u.checked_mail) {
                                case '0':
                                    u.libelle_checked_mail = 'Mail non vérifié';
                                    break;
                                case '1':
                                    u.libelle_checked_mail = 'Mail vérifié';
                                    break;
                            }
                            switch (u.active_account) {
                                case '0':
                                    u.libelle_active_account = 'Banni';
                                    break;
                                case '1':
                                    u.libelle_active_account = 'Actif';
                                    break;
                                case '2':
                                    u.libelle_active_account = 'Supprimé';
                                    break;
                            }
                        });
                    }
                });
        });
    }

    buildControl() {
        this.userForm = this._formBuilder.group(this.controls());
        this.userForm.valueChanges.subscribe(() => {
            this.bindFormModel();
        });
    }

    bindFormModel() {
        this.userActif = new User({ ...this.userActif, ...this.userForm.get('userGroup').value });
        for(let k in this.userForm.get('adminGroup').value) {
            this[k] = this.userForm.get('adminGroup').value[k];
        }
    }

    retour() {
        this.userActif = null;
        this.editMode = false;
        this.errors = [];
    }

    showUser(user: User) {
        this.userActif = user;
        this.buildControl();
    }

    sendUser() {
        this.errors = [];
        this.submitted = true;
        if(this.userForm.dirty) {
            if(this.userForm.valid) {
                this.submitted = false;
                this.editMode = false;
                let session_token = JSON.parse(localStorage.getItem('userConnected')).session_token;
                this._appService.post('action/admin/saveUserInfo.php',
                {session_token: session_token, action: this.action, comment: this.raison, user: this.userActif}).then(res => {
                    if (res.error) {
                        this._router.navigate(['/accueil']);
                    }
                });
            } else { //champs pseudo Modif ou raison vide
                this.errors.push('Les champs pseudo, modification et raison sont obligatoires');
            }
        }
    }
}