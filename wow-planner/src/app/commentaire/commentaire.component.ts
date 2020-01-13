import { Component, Input } from '@angular/core';

import { AppService } from '../app.service';
import { Word, Commentaire } from '../model/app.model';

@Component({
    selector: 'commentaire-cpt',
    templateUrl: './commentaire.component.html',
})
export class CommentaireComponent {
    @Input() words: Word[];
    @Input() ttCommentaire: Commentaire[];
    @Input() character: any;

    commentaire: Commentaire = new Commentaire({ comment: '' });
    commentToEdit: Commentaire;

    constructor(private _appService: AppService) { }

    addComment() {
        if (this.commentaire.comment.length > 0) {
            this._appService.post('action/api-blizzard/addComment.php', { session_token: this._appService.getToken(), comment: this.commentaire.comment, character_id: this.character.character_id })
                .then(res => {
                    if (res.response) {
                        this.ttCommentaire.unshift(res.response);
                        this.commentaire = new Commentaire({ comment: '' });
                    }
                });
        }
    }

    editComment(commentToEdit: Commentaire) {
        this.commentToEdit = Object.assign({}, commentToEdit);
    }

    validEditComment() {
        this._appService.post('action/api-blizzard/editComment.php', { session_token: this._appService.getToken(), comment: this.commentToEdit.comment, character_id: this.character.character_id, comment_id: this.commentToEdit.comment_id })
            .then(res => {
                if (res.response) {
                    res.response.comment_id = this.commentToEdit.comment_id;
                    let id = this.ttCommentaire.findIndex(c => c.comment_id === res.response.comment_id);
                    this.ttCommentaire.splice(id, 1, res.response);
                    this.commentToEdit = null;
                }
            });
    }

    deleteComment(comment) {
        this._appService.post('action/api-blizzard/deleteComment.php', { session_token: this._appService.getToken(), character_id: this.character.character_id, comment_id: comment.comment_id })
            .then(res => {
                if (res.response) {
                    let id = this.ttCommentaire.findIndex(c => c.comment_id === comment.comment_id);
                    this.ttCommentaire.splice(id, 1);
                }
            });
    }

}
