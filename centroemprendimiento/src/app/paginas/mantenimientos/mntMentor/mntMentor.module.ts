import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import {DataTablesModule} from 'angular-datatables';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { GeneralModule } from '../general/general.module';
import {ColorPickerModule} from 'ngx-color-picker';
import { MntMentorComponent } from './mntMentor.component';
import { MntMentorRoutes } from './mntMentor.routing';
import { EditarModule } from 'src/app/paginas/PerfilPage/editar/editar.module';
import { MntHorarioModule } from 'src/app/componente/mntHorario/mntHorario.module';
import { EvaluacionModule } from 'src/app/componente/evaluacion/evaluacion.module';
import { MntPeriodoModule } from 'src/app/componente/mntPeriodo/mntPeriodo.module';
import { ExportarModule } from 'src/app/componente/exportar/exportar.module';

@NgModule({
  declarations: [MntMentorComponent],
  imports: [
    CommonModule,
    SharedModule,
    DataTablesModule,
    MntMentorRoutes,
    GeneralModule,
    ColorPickerModule,
    EditarModule,
    MntHorarioModule,
    EvaluacionModule,
    MntPeriodoModule,
    ExportarModule
  ],
  exports:[MntMentorComponent]
})
export class MntMentorModule { }
