import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../app.service';

import { Word, User } from '../model/app.model';

@Component({
    selector: 'app-login',
    templateUrl: './login.page.html',
    styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
    words: Word[] = [];
    loginForm: FormGroup;
    mail: string;
    user: User;

    controls = (value: any = {}) => ({
        login: [value.login, Validators.required],
        password: [value.password, Validators.required],
    });
    constructor(private _router: Router, private _appService: AppService, private _formBuilder: FormBuilder) { }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) { // si quelqu'un est connecté il ne peut pas aller sur la page login donc on le renvoi a l'accueil
            this._router.navigate(['/accueil']);
        } else {
            this._appService.getWords(['common', 'connexion', 'confirmation']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
                this.buildControl();
            });
        }
    }

    buildControl() {
        this.loginForm = this._formBuilder.group(this.controls());
        this.loginForm.valueChanges.subscribe(() => {
            this.bindModelForm();
        });
    }

    bindModelForm() {
        for (let k in this.loginForm.get('loginGroup').value) {
            if (k === 'mail') {
                this.mail = this.loginForm.get('loginGroup').value[k];
            } else {
                this.user[k] = this.loginForm.get('loginGroup').value[k];
            }
        }
        for (let k in this.loginForm.get('newPassGroup').value) {
            this[k] = this.loginForm.get('newPassGroup').value[k];
        }
    }

}
