import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

import { AppService } from './app.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {

    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
         const headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        console.log(this._appService.getTest());
        this.http.get("http://localhost/wow-planner-app/test.php").subscribe(res => {
            console.log(res)
        });
        let coucou = 'mabite';
        this.http.post("http://localhost/wow-planner-app/testEnvoi.php", JSON.stringify({ coucou: coucou })).subscribe(res => {
            console.log(res)
        });
        let user = {
            lastname: 'Bauman', firstname: 'Eric', pseudo: 'Parfaitement2',
            password: 'coucou', mail: 'test2@laposte.net'
        };
        this.http.post("http://localhost/wow-planner-app/action/addNewUser.php", JSON.stringify(user)).subscribe(res => {
            console.log(res)
        });
        let userConnection = {
            login: 'Parfaitement', password: 'coucou'
        };
        this.http.post("http://localhost/wow-planner-app/action/login.php", JSON.stringify(userConnection)).subscribe(res => {
            console.log(res)
        });
        this.http.get("http://localhost/wow-planner-app/testSession.php").subscribe(res => {
            console.log(res)
        });
    }
}
