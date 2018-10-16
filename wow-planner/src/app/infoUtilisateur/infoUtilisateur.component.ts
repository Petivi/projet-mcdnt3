import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import Swal from 'sweetalert2';

import { AppService } from '../app.service';

import { WordSimplified, User } from '../model/app.model';

@Component({
    selector: 'info-utilisateur-cpt',
    templateUrl: './infoUtilisateur.component.html',
})
export class InfoUtilisateurComponent implements OnInit, OnDestroy {
    words: WordSimplified[] = [];
    passwordErrors: string[] = [];
    profileErrors: string[] = [];
    editMode: boolean = false;
    changePass: boolean = false;
    submitted: boolean = false;
    wrongCf: boolean = false;
    wrongPass: boolean = false;
    userConnected: User;
    newUser: User;
    oldPassword: string;
    cfPassword: string;
    newPassword: string;
    infoUserForm: FormGroup;
    controls = () => ({
        profileGroup: this._formBuilder.group({
            firstname: [this.newUser && this.newUser.firstname ? this.newUser.firstname : ''],
            lastname: [this.newUser && this.newUser.lastname ? this.newUser.lastname : ''],
            pseudo: [this.newUser && this.newUser.pseudo ? this.newUser.pseudo : ''],
            mail: [this.newUser && this.newUser.mail ? this.newUser.mail : ''],
        }),
        passwordGroup: this._formBuilder.group({
            oldPassword: [this.oldPassword && this.oldPassword ? this.oldPassword : '', Validators.required],
            newPassword: [this.newPassword && this.newPassword ? this.newPassword : '', Validators.required],
            cfPassword: [this.cfPassword && this.cfPassword ? this.cfPassword : '', Validators.required],
        })
    });
    constructor(private _appService: AppService, private _router: Router, private _formBuilder: FormBuilder) { }

    ngOnInit() {
        if (!localStorage.getItem('userConnected')) {
            this._router.navigate(['/accueil']);
        } else {
            this._appService.getUserConnected(localStorage.getItem('userConnected')).then(res => {
                this.userConnected = res;
                this.newUser = Object.assign({}, this.userConnected);
                this._appService.setPage('inscription');
                this.buildControl();
                this._appService.getWords(['common', 'infoUser']).then(res => {
                    res.forEach(w => {
                        this.words.push(w);
                    });
                });
            });
        }
    }

    ngOnDestroy() {

    }

    buildControl() {
        this.infoUserForm = this._formBuilder.group(this.controls());
        this.infoUserForm.valueChanges.subscribe(() => {
            this.bindFormModel();
        });
    }

    bindFormModel() {
        for (let k in this.infoUserForm.get('profileGroup').value) {
            this.newUser[k] = this.infoUserForm.get('profileGroup').value[k];
        }
        for (let k in this.infoUserForm.get('passwordGroup').value) {
            this[k] = this.infoUserForm.get('passwordGroup').value[k];
        }
    }

    sendUser() {
        this.submitted = true;
        this.editMode = false;
        this._appService.post('action/editUserInfo.php', { user: this.userConnected, newUser: this.newUser, lang: this._appService.getLangue()}).then(res => {
            if (res.response) {
                this.userConnected = Object.assign({}, this.newUser);
            }
        });
    }

    sendPass() {
        this.wrongCf = false;
        this.wrongPass = false;
        this.submitted = true;
        this.passwordErrors = [];
        if (this.infoUserForm.controls.passwordGroup.valid) {
            if (this.newPassword === this.cfPassword) {
                this._appService.post('action/editPassword.php', { user: this.userConnected, oldPassword: this.oldPassword, newPassword: this.newPassword })
                    .then(res => {
                        if (res.error) {
                            switch (res.error) {
                                case 'Wrong password':
                                    this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_wrongPassword').value);
                                    this.wrongPass = true;
                                    break;
                                case 'An Error Occured':
                                    this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_errorUnknown').value);
                                    break;
                            }
                        } else if (res.response) {
                            this.changePass = false;
                            Swal({
                                title: 'Confirmation',
                                text: this.words.find(w => w.msg_name === 'msg_passwordChanged').value,
                                type: 'success',
                                confirmButtonText: 'OK',
                            });
                        }
                    });
            } else { // confirmation de mdp différente du nouveau mdp
                this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_errorCfPassword').value);
                this.wrongCf = true;
            }
        } else { // input vide
            this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_inputVide').value);
        }
    }
}