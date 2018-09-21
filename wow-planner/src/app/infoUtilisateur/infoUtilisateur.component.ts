import { Component, OnInit, OnDestroy } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { AppService } from '../app.service';

import { WordSimplified, User } from '../model/app.model';
import Swal from 'sweetalert2';

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
    controls = (value: any = {}) => ({
        profileGroup: this._formBuilder.group({
            firstname: [value.firstname],
            lastname: [value.lastname],
            pseudo: [value.pseudo],
            mail: [value.mail],
        }),
        passwordGroup: this._formBuilder.group({
            oldPassword: [value.password, Validators.required],
            newPassword: [value.newPassword, Validators.required],
            cfPassword: [value.cfPassword, Validators.required],
        })
    });
    constructor(private _appService: AppService, private _router: Router, private _formBuilder: FormBuilder) { }

    ngOnInit() {
        if (!this._appService.getUserConnected()) {
            this._router.navigate(['/accueil']);
        } else {
            this.userConnected = this._appService.getUserConnected();
            this.newUser = Object.assign({}, this.userConnected);
            this._appService.setPage('inscription');
            this.buildControl({});
            console.log(this.infoUserForm)
            this._appService.getWords(['common', 'infoUser']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
                console.log(this.words)
            });
        }
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.infoUserForm = this._formBuilder.group(this.controls(value));
    }

    sendUser() {
        this.submitted = true;
        this.editMode = false;
        this._appService.post('action/editUserInfo.php', { user: this.userConnected, newUser: this.newUser });
    }

    sendPass() {
        this.wrongCf = false;
        this.wrongPass = false;
        this.submitted = true;
        this.passwordErrors = [];
        if (this.newPassword === this.cfPassword) {
            this._appService.post('action/editPassword.php', { user: this.userConnected, oldPassword: this.oldPassword, newPassword: this.newPassword })
                .then(res => {
                    if (res.error) {
                        switch (res.error) {
                            case 'Wrong password':
                                if(!this.oldPassword) this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_inputVide').value);      
                                else this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_wrongPassword').value);
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
        } else {
            this.passwordErrors.push(this.words.find(w => w.msg_name === 'msg_errorCfPassword').value);
            this.wrongCf = true;
        }
    }
}