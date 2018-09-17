import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

import { AppService } from './app.service';
import { Observable } from 'rxjs';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
})
export class AppComponent implements OnInit {

    constructor(private _appService: AppService, private http: HttpClient, private _router: Router) { }
    ngOnInit() {
        this.http.post("http://localhost/wow-planner-app/action/login.php", JSON.stringify({login: 'Parfaitement', password: 'coucou'})).subscribe(res => {
            console.log(res);
        });

    }
}
