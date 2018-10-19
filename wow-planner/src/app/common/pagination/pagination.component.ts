import { Component, OnInit, EventEmitter, Output, Input } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../../app.service';

import { Word } from '../../model/app.model'


@Component({
    selector: 'pagination-cpt',
    templateUrl: './pagination.component.html',
})
export class PaginationComponent implements OnInit {

    @Output() changePage: EventEmitter<any> = new EventEmitter();
    @Input() ttPage: string[];
    page: string = '1';
    totalPage: number = 0;
    ttPageAff: string[] = [];

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.ttPageAff = this.ttPage.slice(0, 5);
    }

    setPage(value: string, sens: string = null) {
        this.page = value;
        if(!sens) {
            if (this.ttPageAff.indexOf(this.page) === this.ttPageAff.length - 1 && this.ttPage.indexOf(this.page) !== this.ttPage.length - 1) {
                let tt = [];
                this.ttPageAff.forEach(p => {
                    let page = +p;
                    page++;
                    p = page.toString();
                    tt.push(p);
                });
                this.ttPageAff = tt;
            }
            if (this.ttPageAff.indexOf(this.page) === 0 && this.ttPage.indexOf(this.page) !== 0) {
                let tt = [];
                this.ttPageAff.forEach(p => {
                    let page = +p;
                    page--;
                    p = page.toString();
                    tt.push(p);
                });
                this.ttPageAff = tt;
            }
        } else {
            if(sens === 'debut') {
                this.ttPageAff = this.ttPage.slice(0, 5);
            } else {
                this.ttPageAff = this.ttPage.slice(this.ttPage.length - 5, this.ttPage.length);
            }
        }
        this.changePage.emit(value);
    }

    btnSetPage(signe: string) {
        switch (signe) {
            case 'debut':
                this.setPage('1', 'debut');
                break;
            case 'moins':
                if (this.page !== '1') {
                    this.setPage((+this.page - 1).toString());
                }
                break;
            case 'plus':
                if (this.page !== this.ttPage[this.ttPage.length - 1]) {
                    this.setPage((+this.page + 1).toString());
                }
                break;
            case 'fin':
                this.setPage(this.ttPage[this.ttPage.length - 1], 'fin');
                break;
        }
    }
}