import { NgModule } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';

import { SharedModule } from 'src/app/theme/shared/shared.module';
import {ColorPickerModule} from 'ngx-color-picker';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { PersonaComponent } from './persona.component';


@NgModule({
  declarations: [PersonaComponent],
  imports: [
    CommonModule,
    SharedModule,
    ColorPickerModule,
    NgbModule
  ],
  exports:[PersonaComponent],
  providers: [
    DatePipe
  ]
})
export class PersonaModule { }
