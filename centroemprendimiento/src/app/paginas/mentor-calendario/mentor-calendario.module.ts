import { NgModule } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';

import { MentorCalendarioRoutingModule } from './mentor-calendario.routing';
import { MentorCalendarioComponent } from './mentor-calendario.component';
import {FormsModule} from '@angular/forms';

import { FullCalendarModule } from '@fullcalendar/angular'; // the main connector. must go first
import {NgbDropdownModule} from '@ng-bootstrap/ng-bootstrap';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { ReunionModule } from 'src/app/componente/reunion/reunion.module';

@NgModule({
  declarations: [MentorCalendarioComponent],
  imports: [
    CommonModule,
    MentorCalendarioRoutingModule,
    SharedModule,
    FormsModule,
    FullCalendarModule,
    NgbDropdownModule,
    ReunionModule
  ],
  providers:[
    DatePipe
  ],
  exports:[MentorCalendarioComponent] 
})
export class MentorCalendarioModule { }
