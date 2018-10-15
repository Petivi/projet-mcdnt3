import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import 'rxjs/add/operator/map';

import { Word, User } from './model/app.model';

@Injectable()
export class AppService {
    urlServeur: string = 'http://localhost/wow-planner-app/';
    words: Word[];
    httpOptions = {
        headers: new HttpHeaders({
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        })
    }
    userConnected: User;
    langue: string;
    constructor(private _http: HttpClient) { }

    post(url: string, value: any) {
        value = JSON.stringify(value);
        return this._http.post(this.urlServeur + url, value, this.httpOptions)
            .toPromise()
            .then(res => {
                console.log(res)
                console.log(JSON.parse(res['body']));
                if (res['body']) {
                    let value = JSON.parse(res['body']);
                    return value;
                } else return true;
            });
    }

    connexion(value: any): Promise<any> {
        value = JSON.stringify(value);
        return this._http.post(this.urlServeur + 'action/login.php', value, this.httpOptions)
            .toPromise()
            .then(res => {
                console.log(res)
                let value = JSON.parse(res['body']);
                if (value.response) {
                    this.userConnected = value.response;
                    localStorage.setItem('userConnected', JSON.stringify(this.userConnected));
                    return 'connected';
                } else return value;
            });
    }

    getWords(page: string[]): Promise<any> {
        this.langue = this.getLangue();
        return this._http.get(this.urlServeur + 'action/getWords.php')
            .map(res => {
                let data = JSON.parse(res['body']);
                if (data.response) {
                    this.words = [];
                    data.response.forEach(word => {
                        this.words.push(word);
                    });
                    localStorage.setItem('words', JSON.stringify(this.words));
                    return this.getWordsReturn(page);
                }
            }).toPromise();
    }

    getWordsReturn(page: string[]) {
        let wordsReturn = [];
        this.words.forEach(w => {
            page.forEach(p => {
                if (w.page === p) {
                    wordsReturn.push({ page: w.page, msg_name: w.msg_name, value: (this.langue === 'fr' ? w.msg_fr : w.msg_en) })
                }
            });
        });
        return wordsReturn;
    }

    getUserConnected(value): any {
        return this._http.post(this.urlServeur + 'action/getUserInfo.php', value, this.httpOptions)
            .toPromise()
            .then(res => {
                console.log(JSON.parse(res['body']))
                let returnRes = JSON.parse(res['body'])
                if (returnRes.response) {
                    returnRes.response.session_token = JSON.parse(value).session_token;
                    return returnRes.response;
                } else {
                    console.log('oui')
                    return null;
                }
            });
    }




    deconnexion() {
        if (localStorage.getItem('userConnected')) {
            this.getUserConnected(localStorage.getItem('userConnected')).then(res => {
                if (!res.error) {
                    this.post('action/disconnection.php', res).then(res => {
                        if (!res.error) {
                            localStorage.removeItem('userConnected');
                            this.userConnected = null;
                        }
                    });
                }
            });
        }
    }

    setLangue(langue: string) {
        localStorage.setItem('langue', langue);
        this.langue = langue;
        console.log(this.langue);
    }

    getLangue() {
        if (localStorage.getItem('langue')) {
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
