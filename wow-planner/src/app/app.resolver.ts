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
            this._appService.post('action/api-blizzard/getCharacters.php', { session_token: this._appService.getToken(), data: 'perso' })
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
            this._appService.post('action/api-blizzard/getCharacters.php', { session_token: this._appService.getToken(), data: 'all' })
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
export class DetailPersonnageResolver implements Resolve<any> {
    constructor(private _appService: AppService, private _router: Router) { }
    resolve(route: ActivatedRouteSnapshot): Promise<any> {
        let id = route.params['id'];
        let mesPersonages = route.url[0].path === 'accueil' ? false : true;
        return Observable.forkJoin([
            this._appService.getWords(['common', 'detailPersonnage']),
            this._appService.post('action/api-blizzard/getOneCharacter.php', { session_token: this._appService.getToken(), character_id: id }),
            this._appService.post('action/api-blizzard/getComments.php', { session_token: this._appService.getToken(), character_id: id }),
        ]).map(
            (data: any) => {
                if (data[0]) {
                    return { words: data[0], character: data[1].response, comments: data[2].response, mesPersonnages: mesPersonages };
                } else {
                    this._router.navigate(['/accueil']);
                    return false;
                }
            }
        ).toPromise();
    }
}