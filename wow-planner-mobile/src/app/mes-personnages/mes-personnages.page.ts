import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';

import { User, Word } from '../model/app.model';

import { AppService } from '../app.service';

import * as globals from '../../assets/data/globals';

@Component({
    selector: 'app-mes-personnages',
    templateUrl: './mes-personnages.page.html',
    styleUrls: ['./mes-personnages.page.scss'],
})
export class MesPersonnagesPage implements OnInit {

    characterDetail: any;
    displayDetail: boolean = false;
    userConnected: User;
    obsInit: Subscription;
    words: Word[] = [];
    ttCharacter: any[] = [];
    ttBonusStats: any[] = [];

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute, private _router: Router) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttCharacter = res.mesPersonnages.characters && res.mesPersonnages.characters.length > 0 ? res.mesPersonnages.characters : [];
            this.words = res.mesPersonnages.words;
            this.ttBonusStats = globals.bonusStats.map(bs => {
                if (this._appService.getLangue() === 'fr') {
                    return { id: bs.id, libelle: bs.nameFr }
                } else {
                    return { id: bs.id, libelle: bs.nameEn }
                }
            });
        });
    }

    getWord(libelle: string) {
        return this.words.find(w => w.msg_name === libelle).value;
    }

    getHealthLibelle() {
        return this.ttBonusStats.find(bs => bs.id === 1).libelle;
    }

    getLibelleAttack(character) {
        let statId = globals.statsClass.find(sc => sc.class == character.class_id) ? globals.statsClass.find(sc => sc.class == character.class_id).stat_id : null;
        return statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : '';
    }

    displayPersonnage(character) {
        this._router.navigate(['/affichage-personnage/' + character.character_id]);
    }

    like(statut: number, character: any) {
        this._appService.post('action/api-blizzard/updateStatutLike.php', { session_token: this._appService.getToken(), character_id: character.character_id, statut: statut })
            .then((res: any) => {
                if (res.response) {
                    if (res.response.decrement === 'like') {
                        character.total_like--;
                        character.statut_like = '';
                    }
                    if (res.response.decrement === 'dislike') {
                        character.total_dislike--;
                        character.statut_like = '';
                    }
                    if (res.response.increment === 'like') {
                        character.total_like++;
                        character.statut_like = 'like';
                    }
                    if (res.response.increment === 'dislike') {
                        character.total_dislike++;
                        character.statut_like = 'dislike';
                    }
                }
            });
    }
}
