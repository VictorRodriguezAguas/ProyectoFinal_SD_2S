import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { RouterModule, Routes } from '@angular/router';
import { PruebaComponent } from './prueba.component';


const routes: Routes = [
  {
    path: '',
    component: PruebaComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PruebaRoutingModule { }
