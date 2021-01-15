import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AgendarPageMentoriaRoutes } from './agendarPageMentoria.routing';
import { AgendarPageMentoriaComponent } from './agendarPageMentoria.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { NgbDropdownModule, NgbTooltipModule, NgbCarouselModule } from '@ng-bootstrap/ng-bootstrap';
import { LightboxModule } from 'ngx-lightbox';
import { AgendarModule } from 'src/app/componente/agendar/agendar.module';

@NgModule({
  declarations: [AgendarPageMentoriaComponent],
  imports: [
    CommonModule,
    AgendarPageMentoriaRoutes,
    SharedModule,
    NgbDropdownModule,
    NgbTooltipModule,
    NgbCarouselModule,
    LightboxModule,
    AgendarModule
  ],
  exports:[AgendarPageMentoriaComponent]
})
export class AgendarPageMentoriaModule { }
