import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ListToDoComponent } from './listToDo.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';

@NgModule({
  declarations: [ListToDoComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports:[ListToDoComponent],
  providers: []
})
export class ListToDoModule { }