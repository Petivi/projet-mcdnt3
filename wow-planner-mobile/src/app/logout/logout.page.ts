import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { AppService } from '../app.service';
import { UserService } from '../user.service';

@Component({
    selector: 'app-logout',
    templateUrl: './logout.page.html',
    styleUrls: ['./logout.page.scss'],
})
export class LogoutPage implements OnInit {

    constructor(private _appService: AppService, private _userService: UserService, private _router: Router) { }

    ngOnInit() {
        this._appService.deconnexion();
        this._userService.setUser();
        this._router.navigate(['/home']);
    }
}


