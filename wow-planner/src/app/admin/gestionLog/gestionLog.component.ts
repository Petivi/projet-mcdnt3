import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

import { PaginationComponent } from '../../common/pagination/pagination.component'

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
    ttPage: string[] = [];
    page: string = '1';
    ttLogsUsers: LogUser[] = [];
    ttLogsUsersBlocked: LogUserBlocked[] = [];
    ttLogsUsersManagement: LogUserManagement[] = [];
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

    getLogs(page: string = this.page) {
        this.page = page ? page : this.page;
        let path: string;
        switch (this.logSelected) {
            case 'user':
                path = 'filterGetLogsUsers.php';
                break;
            case 'admin':
                path = 'filterGetLogsUsersManagement.php';
                break;
            case 'account':
                path = 'filterGetLogsUsersBlocked.php';
                break;
        }
        this._appService.post('action/admin/' + path, { session_token: this.token, page: this.page, data: this.strFiltre }).then(res => {
            if (res.response) {
                if (this.logSelected === 'user') {
                    this.ttLogsUsers = res.response.valeur;
                } else if (this.logSelected === 'admin') {
                    this.ttLogsUsersManagement = res.response.valeur;
                } else if (this.logSelected === 'account') {
                    console.log('ui')
                    this.ttLogsUsersBlocked = res.response.valeur;
                    console.log(this.ttLogsUsersBlocked)
                }
                this.ttPage = [];
                for (let i = 1; i < res.response.total_page + 1; i++) {
                    this.ttPage.push(i.toString());
                }
                console.log(res)
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