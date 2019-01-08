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
            this._appService.getCreationPersonnage()
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { races: data[0].races, classes: data[1].classes };
                } else {
                    this._router.navigate(['/RegistreMandat/ListeMandat']);
                    return false;
                }
            }
        ).toPromise();
    }
}
