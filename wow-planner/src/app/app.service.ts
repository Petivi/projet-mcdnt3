import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import 'rxjs/add/operator/map';

import { Word, User } from './model/app.model';

@Injectable()
export class AppService {
    urlServeur: string = 'http://localhost/wow-planner-app/';
    words: Word[];
    userConnected: User;
    langue: string;
    constructor(private _http: HttpClient) { }

    get(url: string, parametre: any = {}): any {
        let parametres = parametre ? '{"filters": ' + JSON.stringify(parametre) + '}' : '';
        let params = new HttpParams().set('filter', parametres);
        return this._http.get(this.urlServeur + url, { params: params })
            .toPromise()
            .then(res => {
                return JSON.parse(res['body']);
            });
    }

    post(url: string, value: any) {
        let httpOptions = {
            headers: new HttpHeaders({
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            })
        };
        value = JSON.stringify(value);
        return this._http.post(this.urlServeur + url, value, httpOptions)
            .toPromise();
    }

    connexion(value: any) {
        let httpOptions = {
            headers: new HttpHeaders({
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            })
        };
        value = JSON.stringify(value);
        this._http.post(this.urlServeur + 'action/login.php', value, httpOptions)
            .toPromise()
            .then(res => {
                let value = JSON.parse(res['body']);
                if (value.response) {
                    this.userConnected = value.response;
                    localStorage.setItem('userConnected', JSON.stringify(this.userConnected));
                }
            });
    }

    getWords(page: string): Promise<any> {
        this.langue = this.getLangue();
        if (!this.words || this.words.length === 0) {
            return this._http.get(this.urlServeur + 'action/getWords.php')
                .map(res => {
                    let data = JSON.parse(res['body']);
                    if (data.response) {
                        this.words = [];
                        data.response.forEach(word => {
                            this.words.push(word);
                        });
                        return this.getWordsReturn(page);
                    }
                }).toPromise();
        } else {
            return new Promise((resolve) => {
                resolve(this.getWordsReturn(page));
            });
        }
    }

    getWordsReturn(page) {
        let wordsReturn = [];
        this.words.forEach(w => {
            if (w.page === page) {
                wordsReturn.push({ page: w.page, msg_name: w.msg_name, value: (this.langue === 'fr' ? w.msg_fr : w.msg_en) })
            }
        });
        this.words.filter(w => w.page === page);
        return wordsReturn;
    }

    getUserConnected() {
        if (localStorage.getItem('userConnected')) {
            return JSON.parse(localStorage.getItem('userConnected'));
        }
    }

    deconnexion() {
        if (localStorage.getItem('userConnected')) {
            localStorage.removeItem('userConnected');
            this.userConnected = null;
        }
    }

    setLangue(langue: string) {
        localStorage.setItem('langue', langue);
        this.langue = langue;
        console.log(this.langue);
    }

    getLangue() {
        if(localStorage.getItem('langue')) {
            return localStorage.getItem('langue');
        } else return 'fr';
    }

    setPage(page: string) {
        localStorage.setItem('page', page);
    }

    getPage() {
        if (localStorage.getItem('page')) {
            return localStorage.getItem('page');
        } else return ('accueil');
    }
}
