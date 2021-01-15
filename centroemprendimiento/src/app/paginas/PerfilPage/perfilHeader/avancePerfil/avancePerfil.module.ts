import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { NgbProgressbarModule } from '@ng-bootstrap/ng-bootstrap';
import {NgbPopoverModule, NgbTooltipModule} from '@ng-bootstrap/ng-bootstrap';
import { AvancePerfilComponent } from './avancePerfil.component';

@NgModule({
  declarations: [AvancePerfilComponent],
  imports: [
    CommonModule,
    SharedModule,
    NgbProgressbarModule,
    NgbPopoverModule,
    NgbTooltipModule
  ],
  exports:[AvancePerfilComponent]
})
export class AvancePerfilModule { }
