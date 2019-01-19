import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import Swal from 'sweetalert2'

import { AppService } from '../app.service'

import { User, Word } from '../model/app.model'
import { Router } from '@angular/router';

@Component({
    selector: 'inscription-cpt',
    templateUrl: './inscription.component.html',
})
export class InscriptionComponent implements OnInit, OnDestroy {
    errors: string[] = [];
    valid: boolean = true;
    user: User = new User({});
    mailTaken: boolean = false;
    pseudoTaken: boolean = false;
    words: Word[] = [];
    submitted: boolean = false;
    cfPassword: string;
    inscriptionForm: FormGroup;
    controls = () => ({
        firstname: [this.user && this.user.firstname ? this.user.firstname : ''],
        lastname: [this.user && this.user.lastname ? this.user.lastname : ''],
        pseudo: [this.user && this.user.pseudo ? this.user.pseudo : '', Validators.required],
        mail: [this.user && this.user.mail ? this.user.mail : '', Validators.required],
        password: [this.user && this.user.password ? this.user.password : '', Validators.required],
        cfPassword: [this.cfPassword ? this.cfPassword : '', Validators.required],
    });

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) {
            this._router.navigate(['/accueil']);
        } else {
            this._appService.setPage('inscription');
            this.buildControl();
            this._appService.getWords(['common', 'inscription']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
        }
    }

    ngOnDestroy() {

    }

    buildControl() {
        this.inscriptionForm = this._formBuilder.group(this.controls());
        this.inscriptionForm.valueChanges.subscribe(() => {
            this.bindFormModel();
        });
    }

    bindFormModel() {
        for (let k in this.inscriptionForm.value) {
            if (k === 'cfPassword') this[k] = this.inscriptionForm.value[k];
            else {
                this.user[k] = this.inscriptionForm.value[k];
            }
        }
        this.user = new User({ ...this.user, ...this.inscriptionForm.value });
    }

    inscription() {
        this.valid = true;
        this.mailTaken = false;
        this.pseudoTaken = false;
        this.errors = [];
        this.submitted = true;
        if (this.inscriptionForm.valid && this.user.password === this.cfPassword) {
            this._appService.post('action/addNewUser.php', { user: this.user, lang: this._appService.getLangue() }).then(res => {
                if (res.error) {
                    this.valid = false;
                    switch (res.error) {
                        case 'Mail already taken':
                            this.errors.push(this.words.find(w => w.msg_name === 'msg_mailTaken').value);
                            this.mailTaken = true;
                            break;
                        case 'Pseudo already taken':
                            this.errors.push(this.words.find(w => w.msg_name === 'msg_pseudoTaken').value);
                            this.pseudoTaken = true;
                            break;
                    }
                } else {
                    Swal({
                        title: 'Confirmation',
                        text: this.words.find(w => w.msg_name === 'msg_creationCompte').value,
                        type: 'success',
                        confirmButtonText: 'OK',
                    }).then(res => {
                        this._router.navigate(['/login']);
                    });
                }
            });
        } else {
            this.valid = false;
            if (this.inscriptionForm.invalid) {
                this.errors.push(this.words.find(w => w.msg_name === 'msg_errorForm').value);
            }
            if (this.user.password !== this.cfPassword) {
                this.errors.push(this.words.find(w => w.msg_name === 'msg_errorCfPassword').value);
            }
        }
        // console.log(this.errors)
    }

    enter(e: KeyboardEvent) {
        if (e.key === 'Enter') this.inscription();
    }
}
