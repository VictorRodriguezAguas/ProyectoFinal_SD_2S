import { NgModule } from '@angular/core';
import { AgendarPageMentoriaComponent } from './agendarPageMentoria.component';
import { Routes, RouterModule } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    component: AgendarPageMentoriaComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class AgendarPageMentoriaRoutes { }
