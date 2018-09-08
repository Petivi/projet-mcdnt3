import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppService } from './app.service';
import { HttpClientModule } from '@angular/common/http';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';

import { appRouting } from './app.routing';

import { AccueilResolver, AdminResolver, CreationJoueurResolver, InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver, RechercheResolver } from './app.resolver';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { CreationJoueurComponent } from './creationJoueur/creationJoueur.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { PersonnageComponent } from './personnage/personnage.component';
import { RechercheComponent } from './recherche/recherche.component';


@NgModule({
    declarations: [
        AppComponent,
        AccueilComponent,
        AdminComponent,
        CreationJoueurComponent,
        InfoUtilisateurComponent,
        ItemComponent,
        LoginComponent,
        PersonnageComponent,
        RechercheComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        ReactiveFormsModule, FormsModule,
        appRouting
    ],
    providers: [AppService, AccueilResolver, AdminResolver, CreationJoueurResolver,
        InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver,
        RechercheResolver],
    bootstrap: [AppComponent]
})
export class AppModule { }
