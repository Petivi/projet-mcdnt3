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
import { ListeRequeteComponent } from './admin/listeRequete/listeRequete.component';
import { GestionLogComponent } from './admin/gestionLog/gestionLog.component';
import { ListePersonnageComponent } from './listePersonnage/listePersonnage.component';

import { CreationPersonnageResolver, GestionCompteResolver, ContactResolver, ListePersonnageResolver, AccueilResolver, DetailPersonnageResolver } from './app.resolver';
import { DetailPersonnageComponent } from './detailPersonnage/detailPersonnage.component';

export const appRouting = RouterModule.forRoot([
    {
        path: 'accueil',
        component: AccueilComponent,
        resolve: {
            resolver: AccueilResolver
        }
    },
    {
        path: 'admin',
        component: AdminComponent,
        children: [
            {
                path: 'gestionCompte',
                component: GestionCompteComponent,
                resolve: {
                    resolver: GestionCompteResolver
                }
            },
            {
                path: 'listeRequete',
                component: ListeRequeteComponent,
            },
            {
                path: 'gestionLog',
                component: GestionLogComponent,
            },
        ]
    },
    {
        path: 'contact',
        component: ContactComponent,
        resolve: {
            resolver: ContactResolver
        }
    },
    {
        path: 'creationPersonnage',
        component: CreationPersonnageComponent,
        resolve: {
            resolver: CreationPersonnageResolver
        }
    },
    {
        path: 'creationPersonnage/:id',
        component: CreationPersonnageComponent,
        resolve: {
            resolver: CreationPersonnageResolver
        }
    },
    {
        path: 'accueil/detailPersonnage/:id',
        component: DetailPersonnageComponent,
        resolve: {
            resolver: DetailPersonnageResolver
        }
    },
    {
        path: 'listePersonnage/detailPersonnage/:id',
        component: DetailPersonnageComponent,
        resolve: {
            resolver: DetailPersonnageResolver
        }
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
        path: 'listePersonnage',
        component: ListePersonnageComponent,
        resolve: {
            resolver: ListePersonnageResolver
        }
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
        path: 'recherche',
        component: RechercheComponent
    },
])