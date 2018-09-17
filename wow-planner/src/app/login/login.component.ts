import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AppService } from '../app.service'

import { User } from '../model/app.model'

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

    constructor(private _formBuilder: FormBuilder, private _service: AppService) { }

    ngOnInit() {
        this.buildControl({});
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.loginForm = this._formBuilder.group(this.controls(value));
    }

    inscription() {
        if (this.loginForm.valid) {
            this._service.post('action/login.php', this.user);
        } else this.valid = false;
    }
}