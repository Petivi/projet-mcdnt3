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
            this._appService.getWords(['common', 'creationPersonnage']),
            this._appService.getBlizzard('data/item/classes')
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { races: data[0][0].races, classes: data[0][1].classes, words: data[1], classesItem: data[2] };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}

@Injectable()
export class GestionCompteResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(): Promise<any> {
        return Observable.forkJoin([
            this._appService.getWords(['common', 'gestionCompte', 'infoUser'])
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { words: data[0] };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}

@Injectable()
export class ContactResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(): Promise<any> {
        return Observable.forkJoin([
            this._appService.getWords(['common', 'contact'])
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { words: data[0] };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}

@Injectable()
export class ListePersonnageResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(): Promise<any> {
        return Observable.forkJoin([
            this._appService.getWords(['common', 'listePersonnage']),
            this._appService.post('action/api-blizzard/getCharacters.php', {session_token: this._appService.getToken()})
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { words: data[0], characters: data[1].response };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}

@Injectable()
export class AccueilResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(): Promise<any> {
        return Observable.forkJoin([
            this._appService.getWords(['common', 'accueil']),
            this._appService.post('action/api-blizzard/getCharacters.php', {session_token: null})
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { words: data[0], characters: data[1].response };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}