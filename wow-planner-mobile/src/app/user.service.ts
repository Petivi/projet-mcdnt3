import { Injectable } from '@angular/core';
import { BehaviorSubject, Subject } from 'rxjs';
import { startWith } from 'rxjs/operators';

@Injectable({
    providedIn: 'root'
})
export class UserService {
    userToken: string;
    userToken$: Subject<string> = new BehaviorSubject<string>(this.userToken);;

    constructor() { }

    setUser(userToken: string = undefined) {
        this.userToken = userToken;
        this.userToken$.next(this.userToken);
    }

    checkUser() {
        return this.userToken$.asObservable().pipe(startWith(this.userToken));
    }
}
