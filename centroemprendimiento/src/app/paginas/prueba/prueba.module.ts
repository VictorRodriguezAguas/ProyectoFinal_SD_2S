import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PruebaComponent } from './prueba.component';
import { PruebaRoutingModule } from './prueba-routing.module';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { AgendarPruebaModule } from './agendarPrueba/agendarPrueba.module';


@NgModule({
  declarations: [PruebaComponent],
  imports: [
    CommonModule,
    PruebaRoutingModule,
    SharedModule,
    AgendarPruebaModule

  ],
  exports:[PruebaComponent]
})
export class PruebaModule { }
