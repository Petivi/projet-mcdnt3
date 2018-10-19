import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { PaginationComponent } from '../../common/pagination/pagination.component'

import { AppService } from '../../app.service';

import { User, LogUser, LogUserManagement } from '../../model/app.model'

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
    ttLogsUsersManagement: LogUserManagement[] = [];
    logSelected: string = 'user';

    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this.token = this._appService.getToken();
        this._appService.post('action/checkIfAdmin.php', { session_token: this.token }).then(res => {
            if (res.error) {
                this._router.navigate(['/accueil']);
            } else {
                this.getLogs(this.page);
            }
        });
    }

    getLogs(page: string) {
        let path: string = this.logSelected === 'user' ? 'getLogsUsers.php' : 'getLogsUsersManagement.php';
        this._appService.post('action/admin/' + path, { session_token: this.token, page: page }).then(res => {
            if (res.response) {
                if(this.logSelected === 'user') {
                    this.ttLogsUsers = res.response.valeur;
                } else {
                    this.ttLogsUsersManagement = res.response.valeur;
                }
                this.ttPage = [];
                for (let i = 1; i < res.response.total_page + 1; i++) {
                    this.ttPage.push(i.toString());
                }
                console.log(this.ttPage)
            };
        });
    }

    changeTypeLog(typeLog: string) {
        this.logSelected = typeLog;
        this.getLogs(this.page);
    }

    showLog(log) {
        this.logActif = log;
    }

    filtre() {
        let path: string = this.logSelected === 'user' ? 'filterGetLogsUsers.php' : 'filterGetLogsUsersManagement.php';
        this._appService.post('action/admin/' + path, { session_token: this.token, page: this.page, data: this.strFiltre }).then(res => {
            if (res.response) {
                if(this.logSelected === 'user') {
                    this.ttLogsUsers = res.response.valeur;
                } else {
                    this.ttLogsUsersManagement = res.response.valeur;
                }
                this.ttPage = [];
                for (let i = 1; i < res.response.total_page + 1; i++) {
                    this.ttPage.push(i.toString());
                }
                console.log(res)
            };
        });
    }
}