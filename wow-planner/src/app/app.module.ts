import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
import { InputsModule } from '@progress/kendo-angular-inputs';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { GridModule } from '@progress/kendo-angular-grid';

import { CustomHttpInterceptor } from './common/customHttpInterceptor';

import { AppComponent } from './app.component';
import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { ListePersonnageComponent } from './listePersonnage/listePersonnage.component';
import { AffichagePersonnageComponent } from './affichagePersonnage/affichagePersonnage.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';
import { GestionCompteComponent } from './admin/gestionCompte/gestionCompte.component';
import { ContactComponent } from './contact/contact.component';
import { ListeRequeteComponent } from './admin/listeRequete/listeRequete.component';
import { GestionLogComponent } from './admin/gestionLog/gestionLog.component';

import { CreationPersonnageResolver, GestionCompteResolver, ContactResolver, ListePersonnageResolver, AccueilResolver } from './app.resolver';


import { AppService } from './app.service';

import { appRouting } from './app.routing';

import { FilterPipe } from './common/pipe/string.pipe';





@NgModule({
    declarations: [
        FilterPipe,
        AppComponent,
        AccueilComponent,
        AdminComponent,
        InscriptionComponent,
        InfoUtilisateurComponent,
        ItemComponent,
        LoginComponent,
        ListePersonnageComponent,
        RechercheComponent,
        CreationPersonnageComponent,
        GestionCompteComponent,
        ContactComponent,
        ListeRequeteComponent,
        GestionLogComponent,
        AffichagePersonnageComponent,
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        ReactiveFormsModule,
        FormsModule,
        appRouting,
        InputsModule,
        BrowserAnimationsModule,
        GridModule,
    ],
    providers: [
        { provide: HTTP_INTERCEPTORS, useClass: CustomHttpInterceptor, multi: true },
        AppService,
        CreationPersonnageResolver, GestionCompteResolver, ContactResolver, ListePersonnageResolver, AccueilResolver
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
