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
    chargement: boolean = true;
    userConnected: User;
    langue: string;
    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
        this.langue = this._appService.getLangue();
        this.userConnected = this._appService.getUserConnected();
        this.getPageWords();        
    }

    deconnexion() {
        this.userConnected = null;
        this._appService.deconnexion();
        this._router.navigate(['/accueil']);
    }

    changeLangue() {
        this.langue = this.langue === 'en' ? 'fr' : 'en';
        this._appService.setLangue(this.langue);
        window.location.reload();
    }

    getPageWords() {
        this._appService.getWords(['menu', 'common']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            this.chargement = false;
            if(this._router.url === '/') {
                this._router.navigate(['/accueil']);
            }
        });
    }
}
