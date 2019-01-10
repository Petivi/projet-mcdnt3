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
import { PersonnageComponent } from './personnage/personnage.component';

import { CreationPersonnageResolver, GestionCompteResolver, ContactResolver } from './app.resolver';

export const appRouting = RouterModule.forRoot([
    {
        path: 'accueil',
        component: AccueilComponent
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
        path: 'inscription',
        component: InscriptionComponent
    },
    {
        path: 'infoUser',
        component: InfoUtilisateurComponent
    },
    {
        path: 'personnage',
        component: PersonnageComponent
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
        component: CreationPersonnageComponent,
        resolve: {
            resolver: CreationPersonnageResolver
        }
    },
    {
        path: 'recherche',
        component: RechercheComponent
    },
    {
        path: 'contact',
        component: ContactComponent,
        resolve: {
            resolver: ContactResolver
        }
    },
])