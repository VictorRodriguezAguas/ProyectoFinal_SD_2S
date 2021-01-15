import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { FormularioComponent } from './formulario.component';

@NgModule({
  declarations: [FormularioComponent],
  imports: [
    CommonModule,
    SharedModule
  ],
  exports:[FormularioComponent]
})
export class FormularioModule { }
