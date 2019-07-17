import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AppService } from '../app.service';

import { Word, User } from '../model/app.model';

@Component({
    selector: 'app-login',
    templateUrl: './login.page.html',
    styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
    words: Word[] = [];
    user: User = new User({login: '', password: ''});
    constructor(private _router: Router, private _appService: AppService) { }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) { // si quelqu'un est connecté il ne peut pas aller sur la page login donc on le renvoi a l'accueil
            this._router.navigate(['/accueil']);
        } else {
            this._appService.getWords(['common', 'connexion', 'confirmation']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
        }
    }
}
