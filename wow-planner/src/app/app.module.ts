import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppService } from './app.service';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';

import { CustomHttpInterceptor } from './common/customHttpInterceptor';

import { AppComponent } from './app.component';

import { appRouting } from './app.routing';

import { AccueilResolver, AdminResolver, InscriptionResolver, InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver, RechercheResolver, CreationPersonnageResolver } from './app.resolver';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { PersonnageComponent } from './personnage/personnage.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';


@NgModule({
    declarations: [
        AppComponent,
        AccueilComponent,
        AdminComponent,
        InscriptionComponent,
        InfoUtilisateurComponent,
        ItemComponent,
        LoginComponent,
        PersonnageComponent,
        RechercheComponent,
        CreationPersonnageComponent
    ],
    imports: [
        BrowserModule,
        HttpClientModule,
        ReactiveFormsModule, FormsModule,
        appRouting
    ],
    providers: [
        { provide: HTTP_INTERCEPTORS, useClass: CustomHttpInterceptor, multi: true },
        AppService, AccueilResolver, AdminResolver, InscriptionResolver,
        InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver,
        RechercheResolver, CreationPersonnageResolver ],
    bootstrap: [AppComponent]
})
export class AppModule { }
