import { NgModule } from '@angular/core';
import {ForosComponent}  from "./foros.component";
import { RouterModule, Routes } from '@angular/router';


const routes: Routes = [
  {
    path: '',
    component: ForosComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class ForosRoutingModule { }
