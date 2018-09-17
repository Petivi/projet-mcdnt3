import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient, HttpHeaders } from '@angular/common/http';

import { AppService } from './app.service';
import { Observable } from 'rxjs';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {

    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
        let httpOptions = {
            headers: new HttpHeaders({
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            })
        };
        let user = {
            lastname: 'Admin', firstname: 'Admin', pseudo: 'admin',
            password: 'admin', mail: 'admin@admin.admin'
        };
        this.http.post("http://localhost/wow-planner-app/action/addNewUser.php", JSON.stringify(user), httpOptions).catch((err) => {
            return Observable.throw(err);
        }).subscribe(res => {
            console.log(res)
        });
        let userConnection = {
            login: 'admin', password: 'admin'
        };
        this.http.post("http://localhost/wow-planner-app/action/login.php", JSON.stringify(userConnection)).subscribe(res => {
            console.log(res);
        });
    }
}
