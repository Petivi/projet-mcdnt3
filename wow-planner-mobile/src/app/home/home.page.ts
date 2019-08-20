import { Component, OnInit, OnDestroy } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs';

import { AppService } from '../app.service';

import { User, Word } from '../model/app.model';

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
  constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

  ngOnInit() {
      this.obsInit = this._activatedRoute.data.subscribe(res => {
          this.ttCharacter = res.accueil.characters && res.accueil.characters.length > 0 ? res.accueil.characters : [];
          this.words = res.accueil.words;
      });
  }

  ngOnDestroy() {

  }

}
