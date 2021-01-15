import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MentorComponent } from './mentor.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { PerfilModule } from 'src/app/componente/perfil/perfil.module';
import { PerfilHeaderModule } from '../perfilHeader/perfilHeader.module';
import { EventosModule } from 'src/app/componente/eventos/eventos.module';
import { RedEmprendedoresModule } from 'src/app/componente/redEmprendedores/redEmprendedores.module';
import { MentorCalendarioModule } from '../../mentor-calendario/mentor-calendario.module';
import { AvancePerfilModule } from '../perfilHeader/avancePerfil/avancePerfil.module';

@NgModule({
  imports: [
    CommonModule,
    SharedModule,
    PerfilModule,
    PerfilHeaderModule,
    EventosModule,
    RedEmprendedoresModule,
    MentorCalendarioModule,
    AvancePerfilModule
  ],
  declarations: [MentorComponent],
  exports:[MentorComponent]
})
export class MentorModule { }
