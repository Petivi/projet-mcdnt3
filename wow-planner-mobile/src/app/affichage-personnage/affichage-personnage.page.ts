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

  constructor(private _activatedRoute: ActivatedRoute) { }

  ngOnInit() {
    this.obsInit = this._activatedRoute.data.subscribe(res => {
      console.log(res)
    })
  }

}
