import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReunionPageRoutingModule } from './reunionPage.routing';
import { SharedModule } from 'src/app/theme/shared/shared.module';
import { ReunionPageComponent } from './reunionPage.component';
import { ReunionModule } from 'src/app/componente/reunion/reunion.module';

@NgModule({
  declarations: [ReunionPageComponent],
  imports: [
    CommonModule,
    SharedModule,
    ReunionPageRoutingModule,
    ReunionModule
  ],
  exports:[ReunionPageComponent]
})
export class ReunionPageModule { }
