import { Component } from '@angular/core';
import { Platform } from '@ionic/angular';
import { Router } from '@angular/router';
import { Subscription } from 'rxjs';

import { SplashScreen } from '@ionic-native/splash-screen/ngx';
import { StatusBar } from '@ionic-native/status-bar/ngx';

import { Word, User } from './model/app.model';

import { AppService } from './app.service';
import { UserService } from './user.service';

@Component({
    selector: 'app-root',
    templateUrl: 'app.component.html'
})
export class AppComponent {
    appPages = [];
    words: Word[] = [];
    userToken: string;
    obsUser: Subscription;
    langue: string;

    constructor(private _platform: Platform, private _splashScreen: SplashScreen, private _statusBar: StatusBar, private _appService: AppService, private _userService: UserService, private _router: Router) {
        this.initializeApp();
    }

    initializeApp() {
        this._platform.ready().then(() => {
            this._statusBar.styleDefault();
            this._splashScreen.hide();
            this.langue = this._appService.getLangue();
            let token = JSON.parse(localStorage.getItem('userConnected'));
            if (token) {
                this._userService.setUser(token.session_token);
            }
            this.obsUser = this._userService.checkUser().subscribe((userToken: string) => {
                if (userToken !== '' && userToken !== null && userToken !== undefined) {
                    this.userToken = userToken;
                } else {
                    this.userToken = null;
                }
                this.setMenu();
            });
        });
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

    setMenu() {
        this._appService.getWords(['menu', 'common']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            if (this.userToken) {
                this.appPages = [{
                    title: this.words.find(w => w.msg_name === 'msg_mesPersonnages').value,
                    url: '/mesPersonnages',
                    icon: 'people'
                }, {
                    title: this.words.find(w => w.msg_name === 'msg_signout').value,
                    url: '/logout',
                    icon: 'log-out'
                }];
            } else {
                this.appPages = [{
                    title: this.words.find(w => w.msg_name === 'msg_connect').value,
                    url: '/login',
                    icon: 'person'
                }];
            }
            this.appPages.push({
                title: this.words.find(w => w.msg_name === 'msg_langue').value + ' : ' + this.langue,
                url: '/home',
                icon: 'globe'
            });
            this.appPages.unshift({
                title: this.words.find(w => w.msg_name === 'msg_accueil').value,
                url: '/home',
                icon: 'home'
            });
            setTimeout(() => {
                if (this._router.url === '/') {
                    this._router.navigate(['/home']);
                }
            }, 100)
        });
    }
    testChangeLangue(p) {
        if (p.title.includes('fr') || p.title.includes('en')) {
            this.changeLangue();
        }
    }
}
