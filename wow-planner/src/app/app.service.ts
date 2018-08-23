import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import 'rxjs/add/operator/map';

@Injectable()
export class AppService {
    constructor(private _http: HttpClient) { }

    getTest(): any {
        return this._http.get("http://localhost/test.php");
    }
}