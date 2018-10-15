import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
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
    word: Word = new Word();
    wordForm: FormGroup;
    controls = (value: any = {}) => ({
        msg_name: [value.msg_name],
        page: [value.page],
        msg_fr: [value.msg_fr],
        msg_en: [value.msg_en],
    });
    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this._appService.getUserConnected(localStorage.getItem('userConnected')).then(res => {
            if (!res.error) {
                this.userConnected = res;
                if (this.userConnected.firstname !== 'Admin') {
                    // TODO: tester si admin
                    this._router.navigate(['/accueil']);
                }
                this.buildControl({});
            }
        });
    }

    addWord() {
        this.word.pseudo = this.userConnected;
        this._appService.post('action/addWords.php', this.word).then(res => {
            console.log(res)
        });
    }

    buildControl(value: any) {
        this.wordForm = this._formBuilder.group(this.controls(value));
    }
}