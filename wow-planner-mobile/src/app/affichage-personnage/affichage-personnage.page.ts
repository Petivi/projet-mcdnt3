import { Component, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { ActivatedRoute } from '@angular/router';
import * as globals from '../../assets/data/globals';


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

  constructor(private _activatedRoute: ActivatedRoute) { }

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
    })
  }

  getWord(libelle: string) {
    return this.words.find(w => w.msg_name === libelle).value;
  }

  getHealthLibelle() {
		return this.ttBonusStats.find(bs => bs.id === 1).libelle;
	}

}
