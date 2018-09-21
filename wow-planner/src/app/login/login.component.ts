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
    linkMail: boolean = false;
    activGrowl: boolean = false;
    msgGrowl: any;
    newMailSent: boolean = false;
    valid: boolean = true;
    user: any = { login: '', password: '' };
    submitted: boolean = false;
    loginForm: FormGroup;
    controls = (value: any = {}) => ({
        login: [value.login, Validators.required],
        password: [value.password, Validators.required],
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (this._appService.getUserConnected()) {
            this._router.navigate(['/accueil']);
        } else {
            this._appService.setPage('login');
            this._appService.getWords(['common', 'connexion', 'confirmation']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
                this.buildControl({});
                if (this._router.url === ('/login/confirm')) {
                    Swal({
                        title: 'Confirmation',
                        text: this.words.find(w => w.msg_name === 'msg_confirmation').value,
                        type: 'success',
                        confirmButtonText: 'OK',
                    });
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
        if (this.loginForm.valid) {
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
            this.login();
        }
    }

    newMail() {
        Swal({
            title: this.words.find(w => w.msg_name === 'msg_inputMail').value,
            input: 'text',
            showCancelButton: true,
            confirmButtonText: 'OK', // faire les text des swal
            cancelButtonText: this.words.find(w => w.msg_name === 'msg_cancel').value,
        }).then(res => {
            this.newMailSent = false;
            if (!res.dismiss) {
                this._appService.post('action/resendMailConfirm.php', { mail: res.value, lang: this._appService.getLangue() })
                    .then(res => {
                        if (!res.error) {
                            this.errors.splice(this.errors.findIndex(e => e === this.words.find(w => w.msg_name === 'msg_notActivated').value), 1);
                            if (this.errors.length === 0) this.valid = true;
                            this.newMailSent = true;
                        } else {
                            this.newMail();
                            this.msgGrowl = { msg: setGrowl({ title: this.words.find(w => w.msg_name === 'msg_error').value, body: this.words.find(w => w.msg_name === 'msg_inputVide').value }) };
                            this.activGrowl = true;
                            setTimeout(() => {
                                this.activGrowl = false;
                            }, 8000);
                        }
                    });
            }
        });
    }
}