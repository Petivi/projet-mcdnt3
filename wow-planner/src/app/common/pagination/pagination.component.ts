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
    totalPage: number = 0;
    @Input() ttPageAff: string[];
    controls = () => ({
    });
    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
    }

    setPage(value: string) {
        this.changePage.emit(value);
    }
}