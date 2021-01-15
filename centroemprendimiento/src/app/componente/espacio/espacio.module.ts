import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ChartsModule } from 'ng2-charts';

import { EspacioComponent } from './espacio.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { EspacioRoutes } from './espacio.routing';
import { FullCalendarModule} from '@fullcalendar/angular'

@NgModule({
  declarations: [EspacioComponent],
  imports: [
    CommonModule,
    SharedModule,
    ChartsModule,
    FullCalendarModule
  ],
  exports:[EspacioComponent, EspacioRoutes],
  providers: []
})
export class EspacioModule { }
