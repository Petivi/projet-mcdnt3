import { RouterModule } from '@angular/router';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';
import { GestionCompteComponent } from './admin/gestionCompte/gestionCompte.component';
import { ContactComponent } from './contact/contact.component';

export const appRouting = RouterModule.forRoot([
    {
        path: 'accueil',
        component: AccueilComponent
    },
    {
        path: 'admin',
        component: AdminComponent,        
    },
    {
        path: 'admin/gestionCompte',
        component: GestionCompteComponent,
    },
    {
        path: 'inscription',
        component: InscriptionComponent
    },
    {
        path: 'infoUser',
        component: InfoUtilisateurComponent
    },
    {
        path: 'item',
        component: ItemComponent
    },
    {
        path: 'login',
        component: LoginComponent
    },
    {
        path: 'login/confirm',
        component: LoginComponent
    },
    {
        path: 'login/:token',
        component: LoginComponent
    },
    {
        path: 'creationPersonnage',
        component: CreationPersonnageComponent
    },
    {
        path: 'recherche',
        component: RechercheComponent
    },
    {
        path: 'contact',
        component: ContactComponent
    },
])