import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {ReunionPageComponent} from './reunionPage.component';

const routes: Routes = [
  {
    path: '',
    component: ReunionPageComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})


export class ReunionPageRoutingModule { }
