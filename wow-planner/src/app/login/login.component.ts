import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import Swal from 'sweetalert2'

import { AppService } from '../app.service';
import { WordSimplified } from '../model/app.model';
import { setGrowl } from '../common/function';

@Component({
    selector: 'login-cpt',
    templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit, OnDestroy {
    errors: string[] = [];
    words: WordSimplified[] = [];
    newPassword: string;
    cfPassword: string;
    token: string;
    newPass: boolean = false;
    linkMail: boolean = false;
    activGrowl: boolean = false;
    msgGrowl: any;
    newMailSent: boolean = false;
    mailResetPass: boolean = false;
    reinitPass: boolean = false;
    valid: boolean = true;
    user: any = { login: '', password: '' };
    submitted: boolean = false;
    loginForm: FormGroup;
    controls = (value: any = {}) => ({
        loginGroup: this._formBuilder.group({
            login: [value.login, Validators.required],
            password: [value.password, Validators.required],
        }),
        newPassGroup: this._formBuilder.group({
            newPassword: [value.newPassword, Validators.required],
            cfPassword: [value.cfPassword, Validators.required],
        }),
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (this._appService.getUserConnected()) { // si quelqu'un est connecté il ne peut pas aller sur la page login donc on le renvoi a l'accueil
            this._router.navigate(['/accueil']);
        } else {
            this._appService.setPage('login');
            this._appService.getWords(['common', 'connexion', 'confirmation']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
                console.log(this.words)
                this.buildControl({});
                if (this._router.url === ('/login/confirm')) { // si l'utilisateur a cliqué sur le lien pour activer son compte
                    Swal({
                        title: 'Confirmation',
                        text: this.words.find(w => w.msg_name === 'msg_confirmation').value,
                        type: 'success',
                        confirmButtonText: 'OK',
                    });
                } else {
                    let tabUrl = this._router.url.split('/');
                    if (tabUrl[tabUrl.length - 1] !== 'login') { // si on a demandé un nouveau mdp et qu'on a le token dans l'url
                        this.token = tabUrl[tabUrl.length - 1];
                        this.newPass = true;
                    }
                }
            });
        }
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.loginForm = this._formBuilder.group(this.controls(value));
    }

    login() {
        this.errors = [];
        this.submitted = true;
        if (this.loginForm.get('loginGroup').valid) {
            this._appService.connexion(this.user)
                .then(res => {
                    if (res === 'connected') {
                        this._router.navigate(['/accueil']);
                        window.location.reload();
                    } else {
                        if (res.error) {
                            this.linkMail = false;
                            this.valid = false;
                            switch (res.error) {
                                case 'Account Suspended':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_suspended').value);
                                    break;
                                case 'Account Deleted':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_deleted').value);
                                    break;
                                case 'Account not activated':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_notActivated').value);
                                    this.linkMail = true;
                                    break;
                                case 'Wrong pseudo/password':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_wrongLoginPassword').value);
                                    break;
                            }
                        }
                    }
                });
        } else {
            this.errors.push(this.words.find(w => w.msg_name === 'msg_errorForm').value)
            this.valid = false;
        }
    }

    enter(e: KeyboardEvent) {
        if (e.key === 'Enter') {
            if (this.newPass) {
                this.newMdp();
            } else {
                this.login();
            }
        }
    }

    newMail(type: number) { // type 0 = pas reçu type 1 = reset mdp
        Swal({
            title: this.words.find(w => w.msg_name === 'msg_inputMail').value,
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'OK', // faire les text des swal
            cancelButtonText: this.words.find(w => w.msg_name === 'msg_cancel').value,
        }).then(res => {
            this.newMailSent = false;
            if (!res.dismiss) {
                if (res.value) {
                    if (type === 0) {
                        this._appService.post('action/resendMailConfirm.php', { mail: res.value, lang: this._appService.getLangue() })
                            .then(res => {
                                if (!res.error) {
                                    this.errors.splice(this.errors.findIndex(e => e === this.words.find(w => w.msg_name === 'msg_notActivated').value), 1);
                                    if (this.errors.length === 0) this.valid = true;
                                    this.newMailSent = true;
                                    setTimeout(() => {
                                        this.newMailSent = false;
                                    }, 10000);
                                } else {
                                    this.newMail(0);
                                    this.msgGrowl = { msg: setGrowl({ title: this.words.find(w => w.msg_name === 'msg_error').value, body: this.words.find(w => w.msg_name === 'msg_inputVide').value }) };
                                    this.activGrowl = true;
                                    setTimeout(() => {
                                        this.activGrowl = false;
                                    }, 8000);
                                }
                            });
                    }
                    if (type === 1) {
                        this._appService.post('action/resetPassword.php', { mail: res.value, lang: this._appService.getLangue() })
                            .then(res => {
                                if (res.response) {
                                    this.mailResetPass = true;
                                } else {
                                    this.errors = [];
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_errorUnknown').value);
                                }
                            });
                    }
                }
            }
        });
    }

    newMdp() {
        this.errors = [];
        this.submitted = true;
        if (this.loginForm.get('newPassGroup').valid) {
            if (this.newPassword === this.cfPassword && this.newPassword) {
                this._appService.post('action/resetPassword.php', { token_temp: this.token, password: this.newPassword })
                    .then(res => {
                        this.valid = true;
                        console.log(res)
                        if (res.error) {
                            this.valid = false;
                            this.errors.push(this.words.find(w => w.msg_name === 'msg_linkExpired').value);
                            setTimeout(() => {
                                this._router.navigate(['/login']);
                            }, 3000);
                        } else {
                            this.reinitPass = true;
                            setTimeout(() => {
                                this._router.navigate(['/login']);
                            }, 2000);
                        }
                    });
            } else { //erreur entre la confirmation et le mot de passe
                this.valid = false;
                this.errors.push(this.words.find(w => w.msg_name === 'msg_errorCfPassword').value);
            }
        } else { // champs vide
            this.valid = false;
            this.errors.push(this.words.find(w => w.msg_name === 'msg_inputVide').value);
        }
    }
}