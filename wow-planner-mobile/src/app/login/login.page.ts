import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AppService } from '../app.service';
import { UserService } from '../user.service';

import { Word, User } from '../model/app.model';

@Component({
    selector: 'app-login',
    templateUrl: './login.page.html',
    styleUrls: ['./login.page.scss'],
})
export class LoginPage implements OnInit {
    words: Word[] = [];
    user: User = new User({ login: '', password: '' });
    mail: string;
    errors: string[] = [];
    submitted: boolean = false;
    linkMail: boolean = false;
    valid: boolean = true;

    constructor(private _router: Router, private _appService: AppService, private _userService: UserService) {
    }

    ngOnInit() {
        if (localStorage.getItem('userConnected')) { // si quelqu'un est connecté il ne peut pas aller sur la page login donc on le renvoi a l'accueil
            this._router.navigate(['/home']);
        } else {
            this._appService.getWords(['common', 'connexion', 'confirmation']).then(res => {
                res.forEach(w => {
                    this.words.push(w);
                });
            });
        }
    }

    getWord(libelle: string) {
        return this.words.find(w => w.msg_name === libelle).value;
    }

    login() {
        this.errors = [];
        this.submitted = true;
        if (this.user.login && this.user.password) {
            this._appService.connexion(this.user)
                .then(res => {
                    if (res === 'connected') {
                        let token = JSON.parse(localStorage.getItem('userConnected'));
                        this._userService.setUser(token.session_token);
                        this._router.navigate(['/home']);
                    } else {
                        if (res.error) {
                            this.linkMail = false;
                            this.valid = false;
                            switch (res.error) {
                                case 'Account Suspended':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_suspended').value);
                                    break;
                                case 'Account Deleted':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_deleted').value);
                                    break;
                                case 'Account not activated':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_notActivated').value);
                                    this.linkMail = true;
                                    break;
                                case 'Wrong pseudo/password':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_wrongLoginPassword').value);
                                    break;
                                case 'Account Blocked':
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_cptBlocked').value);
                                    break;
                                default:
                                    this.errors.push(this.words.find(w => w.msg_name === 'msg_wrongLoginPassword').value);
                                    break;
                            }
                        }
                    }
                });
        } else {
            this.errors.push(this.words.find(w => w.msg_name === 'msg_errorForm').value)
            this.valid = false;
        }
    }
}