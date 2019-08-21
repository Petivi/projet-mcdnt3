import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subscription } from 'rxjs';

import { NavController } from '@ionic/angular';

import { AffichagePersonnagePage } from '../affichage-personnage/affichage-personnage.page';

import { AppService } from '../app.service';

import { User, Word } from '../model/app.model';

import * as globals from '../../assets/data/globals';

@Component({
	selector: 'app-home',
	templateUrl: 'home.page.html',
	styleUrls: ['home.page.scss'],
})
export class HomePage implements OnInit, OnDestroy {

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
			this.ttCharacter = res.accueil.characters && res.accueil.characters.length > 0 ? res.accueil.characters : [];
			this.words = res.accueil.words;
			this.ttBonusStats = globals.bonusStats.map(bs => {
				if (this._appService.getLangue() === 'fr') {
					return { id: bs.id, libelle: bs.nameFr }
				} else {
					return { id: bs.id, libelle: bs.nameEn }
				}
			});
		});
	}

	ngOnDestroy() {

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
