import { Component, OnInit, OnDestroy, Input } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Subscription } from 'rxjs';

import { AppService } from '../app.service';
import { Word } from '../model/app.model';

@Component({
    selector: 'commentaire-like-cpt',
    templateUrl: './commentaireLike.component.html',
})
export class CommentaireLikeComponent implements OnInit, OnDestroy {
    @Input() words: Word[];

    constructor(private _appService: AppService, private _activatedRoute: ActivatedRoute) { }

    ngOnInit() {
    }

    ngOnDestroy() {

    }
}