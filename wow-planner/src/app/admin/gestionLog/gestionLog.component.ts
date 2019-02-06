import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { List } from 'immutable';

import { AppService } from '../../app.service';

import { LogUser, LogUserManagement, LogUserBlocked } from '../../model/app.model'

@Component({
    selector: 'gestion-log-cpt',
    templateUrl: './gestionLog.component.html',
})

export class GestionLogComponent implements OnInit {

    strFiltre: string = '';
    logActif: any = null;
    token: string;
    ttLogsUsers: LogUser[] = [];
    gridDataLogsUsers: List<LogUser> = List([]);
    ttLogsUsersBlocked: LogUserBlocked[] = [];
    gridDataLogsUsersBlocked: List<LogUserBlocked> = List([]);
    ttLogsUsersManagement: LogUserManagement[] = [];
    gridDataLogsUsersManagement: List<LogUserManagement> = List([]);
    logSelected: string = 'user';

    constructor(private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.token = this._appService.getToken();
        this._appService.post('action/checkIfAdmin.php', { session_token: this.token }).then(res => {
            if (res.error) {
                this._router.navigate(['/accueil']);
            } else {
                this.getLogs();
            }
        });
    }

    getLogs() {
        let path: string;
        switch (this.logSelected) {
            case 'user':
                path = 'getLogsUsers.php';
                break;
            case 'admin':
                path = 'getLogsUsersManagement.php';
                break;
            case 'account':
                path = 'getLogsUsersBlocked.php';
                break;
        }
        this._appService.post('action/admin/' + path, { session_token: this.token }).then(res => {
            if (res.response) {
                if (this.logSelected === 'user') {
                    this.ttLogsUsers = res.response.valeur;
                    this.gridDataLogsUsers = List(this.ttLogsUsers);
                } else if (this.logSelected === 'admin') {
                    this.ttLogsUsersManagement = res.response.valeur;
                    this.gridDataLogsUsersManagement = List(this.ttLogsUsersManagement);
                } else if (this.logSelected === 'account') {
                    this.ttLogsUsersBlocked = res.response.valeur;
                    this.gridDataLogsUsersBlocked = List(this.ttLogsUsersBlocked);
                }
            };
        });
    }

    changeTypeLog(typeLog: string) {
        this.logSelected = typeLog;
        this.getLogs();
    }

    showLog(log) {
        this.logActif = log;
    }
}