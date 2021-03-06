import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AppService } from './app.service';

import { User, Word } from './model/app.model';

import { setHeaderEditFixed } from './common/function';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {
    words: Word[] = [];
    compteAdmin: boolean = false;
    chargement: boolean = true;
    userConnected: User;
    langue: string;
    constructor(private _appService: AppService, private _router: Router) { }
    ngOnInit() {
        this.langue = this._appService.getLangue();
        this._appService.getUserConnected(localStorage.getItem('userConnected')).then(res => {
            this.userConnected = res;
        });
        setHeaderEditFixed();
        this.getPageWords();
        this.checkAdmin();
    }

    deconnexion() {
        this.userConnected = null;
        this.compteAdmin = false;
        this._appService.deconnexion();
        this._router.navigate(['/accueil']);
    }

    changeLangue() {
        this.langue = this.langue === 'en' ? 'fr' : 'en';
        this._appService.setLangue(this.langue);
        if (localStorage.getItem('userConnected')) {
            let session_token = JSON.parse(localStorage.getItem('userConnected')).session_token;
            this._appService.post('action/storeUserLang.php', { lang: this.langue, session_token: session_token });
        }
        window.location.reload();
    }

    getPageWords() {
        this._appService.getWords(['menu', 'common']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            this.chargement = false;
            setTimeout(() => {
                if (this._router.url === '/') {
                    this._router.navigate(['/accueil']);
                }
            }, 100)
        });
    }

    checkAdmin() {
        if (localStorage.getItem('userConnected')) {
            this._appService.post('action/checkIfAdmin.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
                this.compteAdmin = res.response ? true : false;
            });
        }
    }
}
