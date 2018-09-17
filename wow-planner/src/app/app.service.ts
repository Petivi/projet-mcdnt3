import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import 'rxjs/add/operator/map';

@Injectable()
export class AppService {
    urlServeur: string = 'http://localhost/';
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
    post(url: string, value: any, parametre: any = {}) {
        let httpOptions = {
            headers: new HttpHeaders({
                'Content-Type': 'application/json'
            })
        };
        let parameters = parametre ? '{"filters": ' + JSON.stringify(parametre) + '}' : '';
        let params = new HttpParams().set('filter', parameters);
        return this._http.post(this.urlServeur + url + params, value, httpOptions)
        .toPromise();
    }
}