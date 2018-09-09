import { RouterModule } from '@angular/router';

import { AccueilComponent } from './accueil/accueil.component';
import { AdminComponent } from './admin/admin.component';
import { InscriptionComponent } from './inscription/inscription.component';
import { InfoUtilisateurComponent } from './infoUtilisateur/infoUtilisateur.component';
import { ItemComponent } from './item/item.component';
import { LoginComponent } from './login/login.component';
import { RechercheComponent } from './recherche/recherche.component';
import { CreationPersonnageComponent } from './creationPersonnage/creationPersonnage.component';

import { AccueilResolver, AdminResolver, InscriptionResolver, InfoUtilisateurResolver, ItemResolver, LoginResolver, PersonnageResolver, RechercheResolver, CreationPersonnageResolver } from './app.resolver';

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
        path: 'inscription',
        component: InscriptionComponent,
        resolve: {
            resolve: InscriptionResolver
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
        path: 'creationPersonnage',
        component: CreationPersonnageComponent,
        resolve: {
            resolve: CreationPersonnageResolver
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