import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import Swal from 'sweetalert2';

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
        this._appService.getUserConnected(localStorage.getItem('userConnected')).then(res => {
            console.log(res)
            this.userConnected = res;
        });
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
            setTimeout(() => {
                if (this._router.url === '/') {
                    this._router.navigate(['/accueil']);
                }
            }, 100)
        });
    }

    desinscription() {
        Swal({
            title: 'Confirmation',
            text: this.words.find(w => w.msg_name === 'msg_confirmationDesinscription').value,
            showCancelButton: true,
            confirmButtonText: 'OK',
            cancelButtonText: this.words.find(w => w.msg_name === 'msg_cancel').value,
        }).then(res => {
            if (res.value) {
                this._appService.post('action/unsubscribe.php', JSON.parse(localStorage.getItem('userConnected'))).then(res => {
                    if (res.response) {
                        localStorage.removeItem('userConnected');
                        Swal({
                            title: 'Confirmation',
                            text: this.words.find(w => w.msg_name === 'msg_confDesinscription').value,
                            type: 'success',
                            confirmButtonText: 'OK',
                        }).then(res => {
                            this._router.navigate(['/login']);
                        });
                    }
                });
            }
        });
    }
}
