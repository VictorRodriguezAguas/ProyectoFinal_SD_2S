import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import {FormsModule} from '@angular/forms';

import { MntAsistentesTecnicosHorarioRoutingModule } from './mnt-asistentes-tecnicos-horario-routing.module';
import { MntAsistentesTecnicosHorarioComponent } from './mnt-asistentes-tecnicos-horario.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { MntHorarioModule } from 'src/app/componente/mntHorario/mntHorario.module';


@NgModule({
  declarations: [MntAsistentesTecnicosHorarioComponent],
  imports: [
    CommonModule,
    MntAsistentesTecnicosHorarioRoutingModule,
    SharedModule,
    FormsModule,
    MntHorarioModule
  ],
  providers: []
})
export class MntAsistentesTecnicosHorarioModule { }