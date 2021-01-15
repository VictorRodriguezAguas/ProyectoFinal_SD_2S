import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Redes_socialesComponent } from './redes_sociales.component';
import {NgbPopoverModule, NgbTooltipModule} from '@ng-bootstrap/ng-bootstrap';

@NgModule({
  imports: [
    CommonModule,
    NgbPopoverModule,
    NgbTooltipModule
  ],
  declarations: [Redes_socialesComponent],
  exports:[Redes_socialesComponent]
})
export class Redes_socialesModule { }
