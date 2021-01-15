import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import {FormsModule} from '@angular/forms';
import {AmazingTimePickerModule} from 'amazing-time-picker';

import { MntHorarioComponent } from './mntHorario.component';
import {DataTablesModule} from 'angular-datatables';
import { SharedModule } from 'src/app/theme/shared/shared.module';


@NgModule({
  declarations: [MntHorarioComponent],
  imports: [
    CommonModule,
    SharedModule,
    FormsModule,
    AmazingTimePickerModule,
    DataTablesModule
  ],
  providers: [],
  exports:[MntHorarioComponent],
})
export class MntHorarioModule { }