import { Component, OnInit } from '@angular/core';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { AppService } from '../app.service';

import { Word } from '../model/app.model'


@Component({
    selector: 'contact-cpt',
    templateUrl: './contact.component.html',
})
export class ContactComponent implements OnInit {

    errors: string[] = [];
    words: Word[] = [];
    mailSent: boolean = false;
    submitted: boolean = false;
    contact_subject: string = '';
    contact_mail: string = '';
    contact_text: string = '';
    contactForm: FormGroup;

    controls = () => ({
        contact_subject: [this.contact_subject, Validators.required],
        contact_text: [this.contact_text, Validators.required],
        contact_mail: [this.contact_mail, Validators.required],
    });
    constructor(private _formBuilder: FormBuilder, private _appService: AppService, private _router: Router) { }

    ngOnInit() {
        this._appService.getWords(['common', 'contact']).then(res => {
            res.forEach(w => {
                this.words.push(w);
            });
            console.log(this.words)
        });
        this.buildControl();
    }

    buildControl() {
        this.contactForm = this._formBuilder.group(this.controls());
        this.contactForm.valueChanges.subscribe(() => {
            this.bindModelForm();
        });
    }

    bindModelForm() {
        for (let k in this.contactForm.value) {
            this[k] = this.contactForm.value[k];
        }
    }

    enter(e: KeyboardEvent) {
        if (e.key === 'Enter') this.envoyer();
    }

    envoyer() {
        this.mailSent = false;
        this.errors =  [];
        this.submitted = true;
        if(this.contactForm.valid) {
            this._appService.post('action/contactUs.php', {contact_mail: this.contact_mail, contact_subject: this.contact_subject, contact_text: this.contact_text})
            .then(res => {
                if(res.response) {
                    this.mailSent = true;
                    this.submitted = false;                    
                    for(let k in this.contactForm.value) {
                        this.contactForm.controls[k].reset();
                    }
                }
            });
        } else {
            this.errors.push(this.words.find(w => w.msg_name === 'msg_inputVide').value);
        }
    }

}