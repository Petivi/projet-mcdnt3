import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../app.service'

@Component({
    selector: 'login-cpt',
    templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit, OnDestroy {
    error: string = 'Les champs pseudo/mail et mot de passe sont obligatoires';
    valid: boolean = true;
    user: any = {login: '', password: ''};
    loginForm: FormGroup;
    controls = (value: any = {}) => ({
        login: [value.login, Validators.required],
        password: [value.password, Validators.required],
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        // TODO: enlever la negation pour que ça marche
        /* if(!this._appService.getUserConnected()) {
            this._router.navigate(['/accueil']);
        } else { */
            this._appService.setPage('login');
            this.buildControl({});
        /* } */
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.loginForm = this._formBuilder.group(this.controls(value));
    }

    login() {
        if (this.loginForm.valid) {
            this._appService.connexion(this.user);
            window.location.reload();
            this._router.navigate(['/accueil']);
        } else this.valid = false;
    }    
}