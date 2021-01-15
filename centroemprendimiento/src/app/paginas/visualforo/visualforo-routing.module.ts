import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VisualforoComponent } from './visualforo.component';
import { Routes, RouterModule } from '@angular/router';



const routes: Routes = [
  {
    path: '',
    component: VisualforoComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class VisualforoRoutingModule { }
