import { Injectable } from '@angular/core';
import { Resolve, Router, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Rx';
import * as _ from 'underscore';
import { AppService } from './app.service';


@Injectable()
export class CreationPersonnageResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(): Promise<any> {
        return Observable.forkJoin([
            this._appService.getCreationPersonnage(),
            this._appService.getWords(['common'])
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { races: data[0][0].races, classes: data[0][1].classes, words: data[1] };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}
