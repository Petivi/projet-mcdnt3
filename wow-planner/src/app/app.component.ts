import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { AppService } from './app.service';
import { Observable } from 'rxjs';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {

    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
        this._appService.getWords();
    }
}
