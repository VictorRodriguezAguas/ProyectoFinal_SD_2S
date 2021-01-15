import { NgModule } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { LightboxModule } from 'ngx-lightbox';
import { ReunionComponent } from './reunion.component';

import { ArchivoModule } from 'src/app/componente/archivo/archivo.module';
import { ListToDoModule } from 'src/app/componente/listToDo/listToDo.module';
import { FormularioModule } from 'src/app/componente/formulario/formulario.module';
import { AsignarActividadModule } from 'src/app/componente/asignarActividad/asignarActividad.module';
import { EvaluacionModule } from 'src/app/componente/evaluacion/evaluacion.module';
import { ReunionDatosModule } from 'src/app/componente/reunion/reunionDatos/reunionDatos.module';

@NgModule({
  declarations: [ReunionComponent],
  imports: [
    CommonModule,
    SharedModule,
    LightboxModule,
    ArchivoModule,
    ListToDoModule,
    FormularioModule,
    AsignarActividadModule,
    EvaluacionModule,
    ReunionDatosModule
  ],
  providers:[
    DatePipe
  ],
  exports:[ReunionComponent]
})
export class ReunionModule { }
