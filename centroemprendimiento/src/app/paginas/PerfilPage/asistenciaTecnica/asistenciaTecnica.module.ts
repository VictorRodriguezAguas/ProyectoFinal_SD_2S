import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { NgbDropdownModule, NgbTooltipModule, NgbCarouselModule, NgbPopoverModule, NgbTabsetModule } from '@ng-bootstrap/ng-bootstrap';
import { LightboxModule } from 'ngx-lightbox';
import { PerfilModule} from 'src/app/componente/perfil/perfil.module';
import { FullCalendarModule } from '@fullcalendar/angular'
import { ComponentAppModule } from 'src/app/componente/componentApp.module';
import { PerfilHeaderModule } from '../perfilHeader/perfilHeader.module';
import { AsistenciaTecnicaComponent } from './asistenciaTecnica.component';
import { AsistenteTecnicoCalendarioModule } from '../../asistente-tecnico-calendario/asistente-tecnico-calendario.module';
import { ArchivoModule } from 'src/app/componente/archivo/archivo.module';
import { NgbProgressbarModule } from '@ng-bootstrap/ng-bootstrap';
import {BarRatingModule} from 'ngx-bar-rating';
import { AvancePerfilModule } from '../perfilHeader/avancePerfil/avancePerfil.module';

@NgModule({
  declarations: [AsistenciaTecnicaComponent],
  imports: [
    CommonModule,    
    SharedModule,
    NgbDropdownModule,
    NgbTooltipModule,
    NgbCarouselModule,
    LightboxModule,
    PerfilModule,
    FullCalendarModule,
    ComponentAppModule,
    PerfilHeaderModule,
    AsistenteTecnicoCalendarioModule,
    ArchivoModule,
    NgbProgressbarModule,
    BarRatingModule,
    NgbPopoverModule,
    AvancePerfilModule,
    NgbTabsetModule
  ],
  exports:[AsistenciaTecnicaComponent]
})
export class AsistenciaTecnicaModule { }
