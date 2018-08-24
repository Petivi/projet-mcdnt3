import { Component, OnInit } from '@angular/core';
import { AppService } from './app.service';
import { HttpClient } from '@angular/common/http';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
    title = 'app';

    constructor(private _appService: AppService, private http: HttpClient) { }
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
            lastname: 'Bauman', firstname: 'Eric', pseudo: 'Neskwik',
            password: 'coucou', birth_date: "29-02-1996"
        };
        this.http.post("http://localhost/wow-planner-app/action/addNewUser.php", JSON.stringify(user)).subscribe(res => {
            console.log(res)
        });
    }
}
