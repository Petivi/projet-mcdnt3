import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AppService } from '../app.service'

import { User, WordSimplified } from '../model/app.model'
import { Router } from '@angular/router';

@Component({
    selector: 'inscription-cpt',
    templateUrl: './inscription.component.html',
})
export class InscriptionComponent implements OnInit, OnDestroy {
    error: string = 'Les champes pseudo, mail, et mot de passe sont obligatoire';
    valid: boolean = true;
    user: User = new User();
    words: WordSimplified[] = [];
    submitted: boolean = false;
    inscriptionForm: FormGroup;
    controls = (value: any = {}) => ({
        firstname: [value.firstname],
        lastname: [value.lastname],
        pseudo: [value.pseudo, Validators.required],
        mail: [value.mail, Validators.required],
        password: [value.password, Validators.required],
        cfPassword: [value.cfPassword, Validators.required],
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (this._appService.getUserConnected()) {
            this._router.navigate(['/accueil']);
        } else {
            this._appService.setPage('inscription');
            this.buildControl({});
            this._appService.getWords(['common', 'inscription']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
        }
    }

    ngOnDestroy() {

    }

    buildControl(value: any) {
        this.inscriptionForm = this._formBuilder.group(this.controls(value));
    }

    inscription() {
        this.submitted = true;
        if (this.inscriptionForm.valid) {
            this._appService.post('action/addNewUser.php', this.user);
            window.location.reload();
        } else this.valid = false;
    }

    enter(e: KeyboardEvent) {
        if (e.key === 'Enter') this.inscription();
    }
}