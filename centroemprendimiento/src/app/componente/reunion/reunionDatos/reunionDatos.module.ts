import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReunionDatosComponent } from './reunionDatos.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';

@NgModule({
  imports: [
    CommonModule,
    SharedModule
  ],
  declarations: [ReunionDatosComponent],
  exports:[ReunionDatosComponent]
})
export class ReunionDatosModule { }
