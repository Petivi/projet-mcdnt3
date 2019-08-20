import { Component } from '@angular/core';
import { Platform } from '@ionic/angular';
import { Router } from '@angular/router';

import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';

import { Word, User } from './model/app.model';

import { AppService } from './app.service';

@Component({
    selector: 'app-root',
    templateUrl: 'app.component.html'
})
export class AppComponent {
    appPages = [];
    words: Word[] = [];
    userConnected: User;
    langue: string;

    constructor(private _platform: Platform, private _splashScreen: SplashScreen, private _statusBar: StatusBar, private _appService: AppService, private _router: Router) {
        this.initializeApp();
    }

    initializeApp() {
        this._platform.ready().then(() => {
            this._statusBar.styleDefault();
            this._splashScreen.hide();
            this.langue = this._appService.getLangue();
            this._appService.getUserConnected(localStorage.getItem('userConnected')).then(res => {
                this.userConnected = res;
            });
            this.getPageWords();
        });
    }

    deconnexion() {
        this.userConnected = null;
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
            if (this.userConnected) {
                this.appPages = [
                  {
                      title: this.words.find(w => w.msg_name === 'msg_homeTitle').value,
                      url: '/home',
                      icon: 'home'
                  },{
                        title: this.words.find(w => w.msg_name === 'msg_mesPersonnages').value,
                        url: '/listePersonnage',
                        icon: 'people'
                    }, {
                        title: this.words.find(w => w.msg_name === 'msg_signout').value,
                        url: '/login',
                        icon: 'log-out'
                    }
                ];
            } else {
                this.appPages = [
                  {
                      title: this.words.find(w => w.msg_name === 'msg_homeTitle').value,
                      url: '/home',
                      icon: 'home'
                  },{
                    title: this.words.find(w => w.msg_name === 'msg_registration').value,
                    url: '/inscription',
                    icon: 'person-add'
                }, {
                    title: this.words.find(w => w.msg_name === 'msg_connect').value,
                    url: '/login',
                    icon: 'person'
                }];
            }
            this.appPages.unshift({
                title: this.words.find(w => w.msg_name === 'msg_accueil').value,
                url: '/accueil',
                icon: 'home'
            });
            setTimeout(() => {
                if (this._router.url === '/') {
                    this._router.navigate(['/accueil']);
                }
            }, 100)
        });
    }
}
