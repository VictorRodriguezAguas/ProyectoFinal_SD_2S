import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AgmCoreModule } from '@agm/core';
import { AgmComponent } from './agm.component';
import { SharedModule } from 'src/app/theme/shared/shared.module';


@NgModule({
  declarations: [AgmComponent],
  imports: [
    CommonModule,
    SharedModule,
    AgmCoreModule.forRoot({
      apiKey: 'AIzaSyAoF7F4-cI0u75-hCo9N5KDDgUPqG_ph9Q'
    })
  ],
  exports: [AgmComponent],
  providers: []
})
export class AgmModule { }