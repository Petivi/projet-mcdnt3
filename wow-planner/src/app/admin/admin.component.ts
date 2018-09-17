import { Component, OnInit, OnDestroy } from '@angular/core';
import { Word } from '../model/app.model'

@Component({
    selector: 'admin-cpt',
    templateUrl: './admin.component.html',
})
export class AdminComponent implements OnInit, OnDestroy {
    tabWord: Word[] = [];
    word: Word = new Word();

    constructor() { }

    ngOnInit() {

    }

    ngOnDestroy() {

    }

    addWord() {

    }
}