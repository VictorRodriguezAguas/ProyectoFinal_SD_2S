import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AsistenteTecnicoCalendarioRoutingModule } from './asistente-tecnico-calendario-routing.module';
import { AsistenteTecnicoCalendarioComponent } from './asistente-tecnico-calendario.component';
import {FormsModule} from '@angular/forms';

import { FullCalendarModule } from '@fullcalendar/angular'; // the main connector. must go first
import {NgbDropdownModule} from '@ng-bootstrap/ng-bootstrap';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { ReunionModule } from 'src/app/componente/reunion/reunion.module';

@NgModule({
  declarations: [AsistenteTecnicoCalendarioComponent],
  imports: [
    CommonModule,
    AsistenteTecnicoCalendarioRoutingModule,
    SharedModule,
    FormsModule,
    FullCalendarModule,
    NgbDropdownModule,
    ReunionModule
  ],
  exports:[AsistenteTecnicoCalendarioComponent] 
})
export class AsistenteTecnicoCalendarioModule { }
