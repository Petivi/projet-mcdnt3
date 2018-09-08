import { RouterModule } from '@angular/router';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { CreationJoueurComponent } from './creationJoueur/creationJoueur.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { PersonnageComponent } from './personnage/personnage.component';
import { RechercheComponent } from './recherche/recherche.component';

import { AccueilResolver, AdminResolver, CreationJoueurResolver, InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver, RechercheResolver } from './app.resolver';

export const appRouting = RouterModule.forRoot([
    {
        path: 'accueil',
        component: AccueilComponent,
        resolve: {
            resolve: AccueilResolver
        }
    },    
    {
        path: 'admin',
        component: AdminComponent,
        resolve: {
            resolve: AdminResolver
        }
    },    
    {
        path: 'creationJoueur',
        component: CreationJoueurComponent,
        resolve: {
            resolve: CreationJoueurResolver
        }
    },    
    {
        path: 'infoUser',
        component: InfoUtilisateurComponent,
        resolve: {
            resolve: InfoUtilisateurResolver
        }
    },    
    {
        path: 'item',
        component: ItemComponent,
        resolve: {
            resolve: ItemResolver
        }
    },    
    {
        path: 'login',
        component: LoginComponent,
        resolve: {
            resolve: LoginResolver
        }
    },    
    {
        path: 'personnage',
        component: PersonnageComponent,
        resolve: {
            resolve: PersonnageResolver
        }
    },    
    {
        path: 'recherche',
        component: RechercheComponent,
        resolve: {
            resolve: RechercheResolver
        }
    }
])