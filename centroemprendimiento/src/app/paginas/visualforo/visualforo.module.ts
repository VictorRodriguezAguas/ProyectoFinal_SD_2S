import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { VisualforoRoutingModule } from './visualforo-routing.module';
import { VisualforoComponent } from './visualforo.component';
import { NgbDropdownModule } from '@ng-bootstrap/ng-bootstrap';
import { SharedModule } from 'src/app/theme/shared/shared.module';



@NgModule({
  declarations: [VisualforoComponent],
  imports: [
    CommonModule,
    VisualforoRoutingModule,
    SharedModule,
    NgbDropdownModule
  ],
  exports:[VisualforoComponent]
})
export class VisualforoModule { }
