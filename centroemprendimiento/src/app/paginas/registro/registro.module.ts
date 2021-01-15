import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RegistroComponent } from './registro.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';

@NgModule({
  imports: [
    CommonModule,
    SharedModule
  ],
  declarations: [RegistroComponent]
})
export class RegistroModule { }
