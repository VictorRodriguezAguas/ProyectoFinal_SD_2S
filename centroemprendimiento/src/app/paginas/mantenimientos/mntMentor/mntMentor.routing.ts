import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { MntMentorComponent } from './mntMentor.component';

const routes: Routes = [
  {
    path: '',
    component: MntMentorComponent
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MntMentorRoutes{}
