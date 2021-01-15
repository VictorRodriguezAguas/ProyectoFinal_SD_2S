import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CambiarMentorComponent } from './cambiarMentor.component';
import { NgbPopoverModule, NgbTooltipModule } from '@ng-bootstrap/ng-bootstrap';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { SelectMentorModule } from '../asignarMentor/selectMentor/selectMentor.module';

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    NgbPopoverModule,
    NgbTooltipModule,
    SelectMentorModule
  ],
  declarations: [CambiarMentorComponent],
  exports:[CambiarMentorComponent]
})
export class CambiarMentorModule { }
