import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { User } from '../model/app.model'

@Component({
    selector: 'inscription-cpt',
    templateUrl: './inscription.component.html',
})
export class InscriptionComponent implements OnInit, OnDestroy {
    user: User = new User();
    inscriptionForm: FormGroup;
    controls = (value: any = {}) => ({
        firstName: [value.firstName],
        lastName: [value.lastName],
        pseudo: [value.pseudo, Validators.required],
        mail: [value.mail, Validators.required],
        password: [value.password, Validators.required],
        cfPassword: [value.cfPassword, Validators.required],
    });

    constructor(private _formBuilder: FormBuilder) { }

    ngOnInit() {
        this.buildControl({});
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.inscriptionForm = this._formBuilder.group(this.controls(value));
    }

    inscription() {
        console.log(this.user);
    }
}