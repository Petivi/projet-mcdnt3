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
import { PersonnageComponent } from './personnage/personnage.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';
import { GestionCompteComponent } from './admin/gestionCompte/gestionCompte.component';
import { ContactComponent } from './contact/contact.component';
import { ListeRequeteComponent } from './admin/listeRequete/listeRequete.component';
import { PaginationComponent } from './common/pagination/pagination.component';
import { GestionLogComponent } from './admin/gestionLog/gestionLog.component';

import { CreationPersonnageResolver, GestionCompteResolver, ContactResolver } from './app.resolver';


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
        PersonnageComponent,
        RechercheComponent,
        CreationPersonnageComponent,
        GestionCompteComponent,
        ContactComponent,
        ListeRequeteComponent,
        PaginationComponent,
        GestionLogComponent,
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        ReactiveFormsModule, FormsModule,
        appRouting,
        InputsModule,
        BrowserAnimationsModule,
        GridModule
    ],
    providers: [
        { provide: HTTP_INTERCEPTORS, useClass: CustomHttpInterceptor, multi: true },
        AppService,
        CreationPersonnageResolver, GestionCompteResolver, ContactResolver
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
