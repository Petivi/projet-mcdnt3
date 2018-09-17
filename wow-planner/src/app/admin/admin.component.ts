import { Component, OnInit, OnDestroy } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';

import { AppService } from '../app.service';

import { Word } from '../model/app.model'


@Component({
    selector: 'admin-cpt',
    templateUrl: './admin.component.html',
})
export class AdminComponent implements OnInit, OnDestroy {
    valid: boolean = true;
    tabWord: Word[] = [];
    word: Word = new Word();
    wordForm: FormGroup;
    controls = (value: any = {}) => ({
        msg_name: [value.msg_name],
        page: [value.page],
        msg_fr: [value.msg_fr],
        msg_en: [value.msg_en],
    });
    constructor(private _formBuilder: FormBuilder, private _service: AppService) { }

    ngOnInit() {
        this.buildControl({});
    }

    ngOnDestroy() {

    }

    addWord() {
        this.word.pseudo = 'admin';
        this._service.post('action/addWords.php', this.word).then(res => {
            console.log(res)
        });
    }

    buildControl(value: any) {
        this.wordForm = this._formBuilder.group(this.controls(value));
    }
}