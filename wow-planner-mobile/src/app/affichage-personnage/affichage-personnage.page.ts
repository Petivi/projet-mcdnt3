import { Component, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { ActivatedRoute } from '@angular/router';

import * as globals from '../../assets/data/globals';

import { AppService } from '../app.service';

import { Word } from '../model/app.model';

@Component({
    selector: 'app-affichage-personnage',
    templateUrl: './affichage-personnage.page.html',
    styleUrls: ['./affichage-personnage.page.scss'],
})
export class AffichagePersonnagePage implements OnInit {

    obsInit: Subscription;
    ttItems: any[] = [];
    words: Word[] = [];
    ttBonusStats: any[] = [];
    openCharac: boolean = true;
    openItem: boolean = true;
    openComment: boolean = true;

    constructor(private _activatedRoute: ActivatedRoute, private _appService: AppService) { }

    ngOnInit() {
        this.obsInit = this._activatedRoute.data.subscribe(res => {
            this.ttItems = res.affichagePersonnage.character;
            this.words = res.affichagePersonnage.words;
            this.ttBonusStats = globals.bonusStats.map(bs => {
                if (this._appService.getLangue() === 'fr') {
                    return { id: bs.id, libelle: bs.nameFr }
                } else {
                    return { id: bs.id, libelle: bs.nameEn }
                }
            });
            // this.ttItems = res.affichagePersonnage.character && res.accueil.affichagePersonnage.character.length > 0 ? res.affichagePersonnage.character : [];
            console.log(this.ttItems);
            console.log(this.ttBonusStats);
        })
    }

    getWord(libelle: string) {
        return this.words.find(w => w.msg_name === libelle).value;
    }

    getHealthLibelle() {
        return this.ttBonusStats.find(bs => bs.id === 1).libelle;
    }

    getLibelleAttack() {
      let statId = globals.statsClass.find(sc => sc.class == this.ttItems[0].class_id) ? globals.statsClass.find(sc => sc.class == this.ttItems[0].class_id).stat_id : null;
      return statId ? this.ttBonusStats.find(bs => bs.id === statId).libelle : '';
    }

    getLibelleStat(id_stat) {
      let libelleStat = this.ttBonusStats.find(ls => ls.id === id_stat).libelle;
      return libelleStat;
    }

}
