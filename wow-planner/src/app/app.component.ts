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
        console.log(this._appService.getTest());
        this.http.get("http://localhost/wow-planner/test.php").subscribe(res => {
            console.log(res)
        });
    }
}
