import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

import { AccueilResolver, DetailPersonnageResolver, MesPersonnagesResolver } from './app.resolver';

const routes: Routes = [
    {
        path: '',
        redirectTo: 'home',
        pathMatch: 'full'
    },
    {
        path: 'home',
        resolve: {
            accueil: AccueilResolver
        },
        loadChildren: './home/home.module#HomePageModule'
    },
    {
        path: 'list',
        loadChildren: './list/list.module#ListPageModule'
    },
    {
        path: 'login',
        loadChildren: './login/login.module#LoginPageModule'
    },
    {
        path: 'logout',
        loadChildren: './logout/logout.module#LogoutPageModule'
    },
    {
        path: 'affichage-personnage/:id',
        resolve: {
            affichagePersonnage: DetailPersonnageResolver
        },
        loadChildren: './affichage-personnage/affichage-personnage.module#AffichagePersonnagePageModule'
    },
    {
        path: 'mesPersonnages',
        resolve: {
            mesPersonnages: MesPersonnagesResolver
        },
        loadChildren: './mes-personnages/mes-personnages.module#MesPersonnagesPageModule'
    }
];

@NgModule({
    imports: [
        RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
    ],
    exports: [RouterModule]
})
export class AppRoutingModule { }
