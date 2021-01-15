import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { SelectMentorComponent } from './selectMentor.component';

@NgModule({
  imports: [
    CommonModule, SharedModule
  ],
  declarations: [SelectMentorComponent],
  exports:[SelectMentorComponent]
})
export class SelectMentorModule { }

