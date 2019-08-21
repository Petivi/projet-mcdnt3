import { Component, OnInit } from '@angular/core';
import { Subscription } from 'rxjs';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-affichage-personnage',
  templateUrl: './affichage-personnage.page.html',
  styleUrls: ['./affichage-personnage.page.scss'],
})
export class AffichagePersonnagePage implements OnInit {

  obsInit: Subscription;
  ttItems: any[] = [];
  words: Word[] = [];

  constructor(private _activatedRoute: ActivatedRoute) { }

  ngOnInit() {
    this.obsInit = this._activatedRoute.data.subscribe(res => {
      console.log(res.affichagePersonnage.character)
      this.ttItems = res.affichagePersonnage.character;
      this.words = res.affichagePersonnage.words;
      // this.ttItems = res.affichagePersonnage.character && res.accueil.affichagePersonnage.character.length > 0 ? res.affichagePersonnage.character : [];
      console.log(this.words);
    })
  }

  getWord(libelle: string) {
    return this.words.find(w => w.msg_name === libelle).value;
  }

}
