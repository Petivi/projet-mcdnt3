import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../app.service'

import { WordSimplified } from '../model/app.model';

@Component({
    selector: 'login-cpt',
    templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit, OnDestroy {
    error: string = 'Les champs pseudo/mail et mot de passe sont obligatoires';
    words: WordSimplified[] = [];
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
            this._appService.getWords(['common', 'connexion']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
            this.buildControl({});
        }
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.loginForm = this._formBuilder.group(this.controls(value));
    }

    login() {
        this.submitted = true;
        if (this.loginForm.valid) {
            this._appService.connexion(this.user);
            window.location.reload();
            this._router.navigate(['/accueil']);
        } else this.valid = false;
    }

    enter(e: KeyboardEvent) {
        if (e.key === 'Enter') {
            this.login();
        }
    }
}