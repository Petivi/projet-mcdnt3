import { Injectable } from '@angular/core';
import { Resolve, Router, ActivatedRouteSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Rx';


@Injectable()
export class AccueilResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class AdminResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class CreationJoueurResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class InfoUtilisateurResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class ItemResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class LoginResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class PersonnageResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}
@Injectable()
export class RechercheResolver implements Resolve<any> {
    constructor() { }

    resolve(route: ActivatedRouteSnapshot): Promise<any> {

        return Observable.forkJoin([
        ]).map(
            (data: any) => {
                return { accueilResolve: 'ça fonctionne'  };
            }
        ).toPromise();
    }
}