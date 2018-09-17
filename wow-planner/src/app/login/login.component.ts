import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AppService } from '../app.service'

import { User } from '../model/app.model'

@Component({
    selector: 'login-cpt',
    templateUrl: './login.component.html',
})
export class LoginComponent implements OnInit, OnDestroy {
    error: string = 'Les champes pseudo, mail, et mot de passe sont obligatoire';
    valid: boolean = true;
    user: User = new User();
    inscriptionForm: FormGroup;
    controls = (value: any = {}) => ({
        firstname: [value.firstname],
        lastname: [value.lastname],
        pseudo: [value.pseudo, Validators.required],
        mail: [value.mail, Validators.required],
        password: [value.password, Validators.required],
        cfPassword: [value.cfPassword, Validators.required],
    });

    constructor(private _formBuilder: FormBuilder, private _service: AppService) { }

    ngOnInit() {
        this.buildControl({});
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.inscriptionForm = this._formBuilder.group(this.controls(value));
    }

    inscription() {
        if (this.inscriptionForm.valid) {
            this._service.post('action/addNewUser.php', this.user);
        } else this.valid = false;
    }
}