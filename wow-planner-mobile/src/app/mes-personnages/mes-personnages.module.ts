import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Routes, RouterModule } from '@angular/router';

import { IonicModule } from '@ionic/angular';

import { MesPersonnagesPage } from './mes-personnages.page';

const routes: Routes = [
  {
    path: '',
    component: MesPersonnagesPage
  }
];

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RouterModule.forChild(routes)
  ],
  declarations: [MesPersonnagesPage]
})
export class MesPersonnagesPageModule {}
