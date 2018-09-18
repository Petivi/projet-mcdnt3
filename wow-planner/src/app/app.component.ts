import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

import { AppService } from './app.service';
import { User, WordSimplified } from './model/app.model';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {
    words: WordSimplified[] = [];
    userConnected: User;
    langue: string;
    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
        this.langue = this._appService.getLangue();
        this.userConnected = this._appService.getUserConnected();
        console.log(this.userConnected)
        this.getPageWords();
        this._router.navigate(['/' + this._appService.getPage()]);
    }

    deconnexion() {
        this.userConnected = null;
        this._appService.deconnexion();
    }

    changeLangue() {
        this.langue = this.langue === 'en' ? 'fr' : 'en';
        this._appService.setLangue(this.langue);
        window.location.reload();
    }

    getPageWords() {
        this._appService.getWords('menu').then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            this._appService.getWords('common').then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
            console.log(this.words)
        });
    }
}
