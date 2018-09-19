import { RouterModule } from '@angular/router';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';

import { ConfirmCompteComponent } from './confirmCompte/confirmCompte.component';

export const appRouting = RouterModule.forRoot([
    {
        path: 'accueil',
        component: AccueilComponent
    },    
    {
        path: 'confirm',
        component: ConfirmCompteComponent
    },    
    {
        path: 'admin',
        component: AdminComponent
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
        path: 'creationPersonnage',
        component: CreationPersonnageComponent
    },    
    {
        path: 'recherche',
        component: RechercheComponent
    }
])