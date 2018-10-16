import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../app.service';

import { Word, User, WordSimplified } from '../model/app.model'


@Component({
    selector: 'admin-cpt',
    templateUrl: './admin.component.html',
})
export class AdminComponent implements OnInit {
    valid: boolean = true;
    words: WordSimplified[] = [];
    userConnected: User;
    word: Word = new Word({});
    wordForm: FormGroup;
    controls = () => ({
        msg_name: [this.word && this.word.msg_name ? this.word.msg_name : ''],
        page: [this.word && this.word.page ? this.word.page : ''],
        msg_fr: [this.word && this.word.msg_fr ? this.word.msg_fr : ''],
        msg_en: [this.word && this.word.msg_en ? this.word.msg_en : ''],
    });
    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.buildControl();
        this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
            console.log(res)
            if (res.error) {
                this._router.navigate(['/accueil']);
            }
        });
    }

    buildControl() {
        this.wordForm = this._formBuilder.group(this.controls());
        this.wordForm.valueChanges.subscribe(() => {
            this.bindModelForm();
        });
    }

    bindModelForm() {
        this.word = new Word({ ...this.word, ...this.wordForm.value });
    }
}