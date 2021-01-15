import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AgendarPruebaComponent } from './agendarPrueba.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { FullCalendarModule} from '@fullcalendar/angular'


@NgModule({
  declarations: [AgendarPruebaComponent],
  imports: [
    CommonModule,
    SharedModule,
    FullCalendarModule
  ],
  exports:[AgendarPruebaComponent],
  providers: []
})
export class AgendarPruebaModule { }