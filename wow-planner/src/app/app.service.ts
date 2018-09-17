import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import 'rxjs/add/operator/map';

@Injectable()
export class AppService {
    urlServeur: string = 'http://localhost/wow-planner-app/';
    constructor(private _http: HttpClient) { }

    get(url: string, parametre: any = {}): any {
        let parametres = parametre ? '{"filters": ' + JSON.stringify(parametre) + '}' : '';
        let params = new HttpParams().set('filter', parametres);
        return this._http.get(this.urlServeur + url, { params: params })
            .toPromise()
            .then(res => {
                return res
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
}